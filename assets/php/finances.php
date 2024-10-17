
<?php
// invoice status:
// 0 -> paid
// 1 -> pending
// 2 -> overdue
// 3 -> not sent to the client yet: Able to remove
// 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php';
require '../../vendor/phpmailer/phpmailer/src/SMTP.php';
require '../../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../../vendor/phpmailer/phpmailer/src/Exception.php';

function getUserInvoices($conn, $userId) {
    $tablename   = "finance";
    $sql         = "SELECT * FROM $tablename WHERE userId = '$userId';";
    $data        = $conn->query($sql);
    $result      = array();
    while($dbOut = $data->fetch_assoc()){
        array_push($result, $dbOut);
    }
    return $result;
}

function setUserInvoices($conn, $fields){
    

    if($fields == false){
        $userInvoices = false;
    } else {
        
        $userId	      = $fields->userId;
        $clientId	  = $fields->clientId;
        $clientName	  = $fields->clientName;
        $invoiceNum	  = 'INV' . strval(mt_rand(1000000, 9999999));
        $invoiceStatus= 3;
        $fee	      = $fields->fee;
        $fee          = str_replace('$', '', $fee);
        $numHour	  = $fields->numHour;
        $numHour      = str_replace('hr', '', $numHour);
        $numHour      = str_replace('h', '', $numHour);
        $serviceStart = $fields->serviceStart;
        $serviceEnd   = $fields->serviceEnd;
        $dueDate      = $fields->invoiceDue;
        // retrieve users stripe key:
        $tablename    = "authentication";
        $sql          = "SELECT userStripeToken FROM $tablename WHERE userId = '$userId';";
        $data         = $conn->query($sql);
        $userToken    = $data->fetch_assoc();
        \Stripe\Stripe::setApiKey($userToken['userStripeToken']);
        // getting the client email
        $tablename   = 'userAllocation';
        $sql         = "SELECT cEmail FROM $tablename WHERE userId = '$userId' AND clientId = '$clientId';";
        $db_out      = $conn->query($sql);
        $clientEmail = $db_out->fetch_assoc();
        $clientEmail = $clientEmail['cEmail'];
        $invoiceTot  = $numHour * $fee;

        // see if stripe customer exists already
        $tablename   = 'finance';
        $sql         = "SELECT stripeCustomerId FROM $tablename WHERE userId = '$userId' AND clientId = '$clientId';";
        $db_out      = $conn->query($sql);
        $stripeCustomerId = $db_out->fetch_assoc();
        
        if(isset($stripeCustomerId['stripeCustomerId'])) {
            // get the customer ID
            $stripeCustomerId = $stripeCustomerId['stripeCustomerId'];
            
        } else {
            // corresponding Stripe invoice
            $customer = \Stripe\Customer::create([
                'name' => $clientName,
                'email' => $clientEmail,
                'metadata' => [
                    'clientId' => $clientId
                ]
            ]);
            $stripeCustomerId = $customer->id;
            // Create the invoice
        }

        // get the pending invoice ID
        $stripeInvoices = \Stripe\Invoice::all(['customer' => $stripeCustomerId]); 
        
        $invoiceFound = false;
        if (count($stripeInvoices->data) > 0) {
            foreach ($stripeInvoices->data as $invoice) {
                if($invoice->status == 'draft'){  // status can be 'draft', 'open', 'paid', 'uncollectible', or 'void'
                    $retrivedInvoice = $invoice;
                    $invoiceFound = true;
                    break;
                }
            }
        }
        if($invoiceFound == false) {
            
            $invoice = \Stripe\Invoice::create([
                'customer' => $stripeCustomerId,
                'collection_method' => 'send_invoice',
                'auto_advance' => true, // Automatically finalize the invoice
                'days_until_due' => floor((strtotime($dueDate) - strtotime('now')) / (60 * 60 * 24)),
                'metadata' => [
                    'clientId' => $clientId,
                    'userId' => $userId
                ],
            ]);
        } else {
            $invoice = $retrivedInvoice;
        }
        $tablename    = "finance";
        $sql          = "INSERT INTO " . $tablename . " (userId, clientId, stripeCustomerId, clientName, 
                                                        invoiceNum, invoiceStatus, fee, 
                                                        numHour, serviceStart, serviceEnd, dueDate) 
                                                        VALUES('$userId','$clientId','$stripeCustomerId',
                                                        '$clientName','$invoiceNum', '$invoiceStatus', '$fee',
                                                        '$numHour', '$serviceStart', '$serviceEnd', '$dueDate');";
        $conn->query($sql); 


        $invoiceItem = \Stripe\InvoiceItem::create([
            'customer' =>  $stripeCustomerId,
            'amount' => $invoiceTot * 100, // amount in cents
            'invoice' => $invoice->id,
            'currency' => 'usd', // Change to your preferred currency
            'description' => 'Invoice for services rendered',
            'metadata' => [
                'clientId' => $clientId,
                'userId' => $userId
            ],
        ]);
        $invoiceTot = strval($invoiceTot) . '$';
        $userInvoices = array('invoiceStatus' => $invoiceStatus,
                              'clientName'    => $fields->clientName,
                              'invoiceNum'    => $invoiceNum,
                              'invoiceTot'    => $invoiceTot
                        );
    }
    return $userInvoices;
}

function removeUserInvoices($conn, $userId, $clientId, $invoiceNum) {
    // retrieve users stripe key:
    $tablename    = "authentication";
    $sql          = "SELECT userStripeToken FROM $tablename WHERE userId = '$userId';";
    $data         = $conn->query($sql);
    $userToken    = $data->fetch_assoc();
    \Stripe\Stripe::setApiKey($userToken['userStripeToken']);

    $tablename   = "finance";
    $sql         = "DELETE FROM $tablename WHERE userId = '$userId' AND clientId = '$clientId' AND invoiceNum = '$invoiceNum'";
    $conn->query($sql); 
    $invoices = \Stripe\Invoice::all(['limit' => 100]); 

    // Loop through invoices to find matching metadata
    foreach ($invoices->data as $invoice) {
        // Check if metadata matches the search criteria
        if (isset($invoice->metadata['userId']) && $invoice->metadata['userId'] === $userId &&
            isset($invoice->metadata['clientId']) && $invoice->metadata['clientId'] === $clientId) {
            
            // Retrieve invoice items for the found invoice
            $invoiceItems = \Stripe\InvoiceItem::all(['invoice' => $invoice->id]);
            $invoice->voidInvoice();
            foreach ($invoiceItems->data as $invoiceItem) {
                // Check if metadata matches the search criteria
                if (isset($invoiceItem->metadata['userId']) && $invoiceItem->metadata['userId'] === $userId &&
                    isset($invoiceItem->metadata['clientId']) && $invoiceItem->metadata['clientId'] === $clientId) {
                    $invoiceItem->delete();
                }
            }
        }
    }
    return true;
}

function sendUserInvoices($conn, $userInfo){
    $userId      = $userInfo->userId;
    $clientId    = $userInfo->clientId;
    $clientName  = $userInfo->clientName;
    $invoiceNum  = $userInfo->invoiceNum;
    $fee         = $userInfo->fee;
    $invoiceHr   = $userInfo->invoiceHr;
    $serviceStart= $userInfo->serviceStart;
    $serviceEnd  = $userInfo->serviceEnd;
    $dueDate     = $userInfo->invoiceDue;
    $invoiceTotal= $userInfo->invoiceTotal;
    $tablename   = 'userAllocation';
    $sql         = "SELECT cEmail FROM $tablename WHERE userId = '$userId' AND clientId = '$clientId';";
    $db_out      = $conn->query($sql);
    $clientEmail = $db_out->fetch_assoc();
    $clientEmail = $clientEmail['cEmail'];

    $mail = new PHPMailer(true);
    //$emailAddr        = $clientEmail;
    $emailAddr        = 'rabiei.p@gmail.com';
    
    // handle Stripe
    // retrieve users stripe key:
    $tablename    = "authentication";
    $sql          = "SELECT userStripeToken FROM $tablename WHERE userId = '$userId';";
    $data         = $conn->query($sql);
    $userToken    = $data->fetch_assoc();
    \Stripe\Stripe::setApiKey($userToken['userStripeToken']);
    // Finalize the Invoice (this step is required before sending)
    $invoices = \Stripe\Invoice::all(['limit' => 100]); 
    // Loop through invoices to find matching metadata
    foreach ($invoices->data as $invoice) {
        // Check if metadata matches the search criteria
        if (isset($invoice->metadata['userId']) && $invoice->metadata['userId'] === $userId &&
            isset($invoice->metadata['clientId']) && $invoice->metadata['clientId'] === $clientId) {
                $finalizedInvoice = \Stripe\Invoice::retrieve($invoice->id);
                $finalizedInvoice = $invoice->finalizeInvoice();
                // Send the Invoice to the customer
                $finalizedInvoice->sendInvoice();
                $invoice_payment_page = $invoice->hosted_invoice_url;
                break;
        }
    }
    // app password: azqb ochq lfot btnc
    // Server settings
    $mail->SMTPDebug  = 0;                         //Enable verbose debug output
    $mail->isSMTP();                              // Set mailer to use SMTP
    $mail->Host       = 'smtp.gmail.com';         // Specify main and backup SMTP servers
    $mail->SMTPAuth   = true;                     // Enable SMTP authentication
    $mail->Username   = 'rabiei.p@gmail.com';     // SMTP username
    $mail->Password   = 'azqb ochq lfot btnc';    // SMTP password
    $mail->SMTPSecure = 'ssl';                    // Enable TLS encryption, `ssl` also accepted
    $mail->Port       = 465;                      // TCP port to connect to
    // Sender info
    $mail->setFrom('rabiei.p@gmail.com', 'Payam Rabiei');
    // Add a recipient
    $mail->addAddress($emailAddr);
    // Set email format to HTML
    $mail->isHTML(true);
    // Mail subject
    $mail->Subject = 'Invoice from NutriAi';
    // Mail body content
    $imagePath = '../img/nutrition4guys.png';
    $imageData = base64_encode(file_get_contents($imagePath));
    $src       = 'data:image/png;base64,' . $imageData;
    $bodyContent = 
    '<!DOCTYPE html>
    <html lang="en">
    <head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>ManHub</title>
    <meta content="" name="description">
    <meta content="" name="keywords">
  
    <link rel="shortcut icon" href="#"> 
    <!-- Vendor CSS Files -->
    <style>
    .client-page {
    height:60px;
    width: 70%;
    margin: auto;
    background-color: dodgerblue;
    } 
    .footer-page {
    height:50px;
    width: 50%;
    margin: auto;
    background-color: transparent;
    } 
    .client-footer {
    height:2px;
    width: 70%;
    margin: auto;
    background-color: grey;
    } 
    .header-clients-text{
    font-size: clamp(14px, 2vw, 18px);
    color: white;
    text-align: center;
    font-weight: normal;
    font-family: "Arial";
    padding-top: 20px;
    }
    .email-body{
    text-align: center;
    margin-top: 5px;
    }
    .email-text{
    font-size: clamp(14px, 2vw, 18px);
    color: #A9A9A9;
    margin-top: 5px;
    font-weight: normal;
    font-family: "Verdana";
    }
    .footer-clients-text{
    font-size: clamp(14px, 2vw, 18px);
    color: black;
    text-align: center;
    font-weight: normal;
    font-family: "Arial";
    }
    .invoiceOutput {
        max-width: 600px;
        margin: 20px auto;
        padding: 20px;
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 8px;
        box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
    }
    
    .invoiceOutput h1 {
        margin-top: 0;
        font-size: 24px;
        text-align: center;
    }
    
    .invoice-details {
        display: flex;
        flex-direction: column;
    }
    
    .detail {
        display: flex;
        justify-content: space-between;
        margin: 10px 0;
        font-size: 16px;
    }
    
    .detail strong {
        font-weight: bold;
    }
    
    .detail span {
        margin-left: 10px;
    }
    .invoice-pay {
        display: flex;
        background-color: dodgerblue;
        color: white;
        text-align: center;
        font-size: 16px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        padding: 12px 24px;
    }

    .pay-div-button {
        display: flex;
        margin: auto;
        justify-content: center;
        text-align: center;
        padding-top: 20px;
    }
    </style>
    </head>
    <header>
    </header>
    <body> 
        <div><a href="index.html">
            <img style= "background-color: transparent" src="' . $src . '" width="150" height="150"/>
            </a>
        </div>
    <div class="client-page">
        <p class="header-clients-text">Invoice</p>
    </div>
    <!-- This div is set up for displaying the invoice -->
    <div class="invoiceOutput">
        <h1>Invoice</h1>
        <div class="invoice-details">
        <div class="detail">
                <strong>Payment Due:</strong>
                <span id="invoiceDue">'. $dueDate .'</span>
            </div>
            <div class="detail">
                <strong>Client Name:</strong>
                <span id="invoiceClientName">'. $clientName .'</span>
            </div>
            <div class="detail">
                <strong>Client ID:</strong>
                <span id="invoiceClientID">'. $clientId .'</span>
            </div>
            <div class="detail">
                <strong>Invoice Number:</strong>
                <span id="invoiceNum">'. $invoiceNum .'</span>
            </div>
            <div class="detail">
                <strong>Service Fee per Hour:</strong>
                <span id="invoiceFee">'. $fee .'</span>
            </div>
            <div class="detail">
                <strong>Number of Service Hours:</strong>
                <span id="invoiceHr">'. $invoiceHr .'</span>
            </div>
            <div class="detail">
                <strong>Service Start Date:</strong>
                <span id="invoiceStart">'. $serviceStart .'</span>
            </div>
            <div class="detail">
                <strong>Service End Date:</strong>
                <span id="invoiceEnd">'. $serviceEnd .'</span>
            </div>
            <div class="detail">
                <strong>Total Charges:</strong>
                <span id="invoiceTotal">'. $invoiceTotal .'$</span>
            </div>
        </div>
        <!-- Buttons at the bottom -->
        
        <div class="pay-div-button">
            <a href="' . $invoice_payment_page . '" class="invoice-pay" target="_blank">Make a Payment</a>
        </div>
    </div>   
    <div class="client-footer"></div>
    <div class="footer-page">
        <p class="footer-clients-text">Nutri-Ai All rights reserved &copy 2024</p> 
    </div>
    </body>
    </html>';
    $mail->Body    = $bodyContent;
    // Send email
    // change the invoice status to pending
    $tablename   = "finance";
    $sql = "UPDATE $tablename SET invoiceStatus = '1' WHERE userId = '$userId' AND clientId = '$clientId' AND invoiceNum = '$invoiceNum';";
    $conn->query($sql);


    if(!$mail->send()) {
        echo 'Message could not be sent. Mailer Error: '.$mail->ErrorInfo;
        $result['status']    = -1;
    } else {
        $result['status']    = 0;
    }

    return $data;

}

function setMissingFields($conn, $userInfo){

    // get clientName from the database
    if($userInfo->clientName == '' && $userInfo->clientId != '') {
        $userId      = $userInfo->userId;
        $clientId    = $userInfo->clientId;
        $tablename   = 'userAllocation';
        $sql         = "SELECT name FROM $tablename WHERE userId = '$userId' AND clientId = '$clientId';";
        $db_out      = $conn->query($sql);
        $clientName  = $db_out->fetch_assoc();
        if($clientName == NULL) {
            $userInfo = false;
            return $userInfo;
        } else {
            $userInfo->clientName = $clientName['name'];
        }

    } elseif($userInfo->clientId == '') {
        $userInfo = false;
        return $userInfo;
    }
    if($userInfo->invoiceDue == '') {
        $userInfo->invoiceDue = '0';
    }
    if($userInfo->fee == '') {
        $userInfo->fee = '0';
    }
    if($userInfo->numHour == '') {
        $userInfo->numHour = '0';
    }
    if($userInfo->serviceStart == '') {
        $userInfo->serviceStart = '0';
    }
    if($userInfo->serviceEnd == '') {
        $userInfo->serviceEnd = '0';
    }
    return $userInfo;
}

function recordUserStripeInfo($conn, $userInfo) {

    $filePath = './stripeId.txt';
    // Use file_get_contents to read the file into a variable
    $stripeId    = file_get_contents($filePath);
    unlink($filePath);
    $filePath = './stripeToken.txt';
    // Use file_get_contents to read the file into a variable
    $stripeToken    = file_get_contents($filePath);
    unlink($filePath);

    $userId      = $userInfo->userId;
    $tablename   = 'authentication';
    $sql         = "UPDATE $tablename SET userStripeId = '$stripeId' WHERE userId = '$userId';";
    $conn->query($sql);
    $sql         = "UPDATE $tablename SET userStripeToken = '$stripeToken' WHERE userId = '$userId';";
    $conn->query($sql);
    return true;
}

function checkUserStripeConnection($conn, $userInfo) {

    $userId      = $userInfo->userId;
    $tablename   = 'authentication';
    $sql         = "SELECT userStripeId FROM $tablename WHERE userId = '$userId';";
    $dbOut       = $conn->query($sql);
    $dbOut       = $dbOut->fetch_assoc();
    if($dbOut['userStripeId'] != ''){
        return true;
    } else {
        return false;
    }
    
} 


$userInfo        = json_decode($_POST['userInfo']);
// server connect
$servername  = "127.0.0.1";
$loginname   = "root";
$password    = "@Ssia123";
$dbname      = "Users";
$conn        = new mysqli($servername, $loginname, $password, $dbname);
// --------
if($userInfo->flag == 'get') {
    $userInvoices = getUserInvoices($conn, $userInfo->userId);
} elseif($userInfo->flag == 'create') {
    $userInfo     = setMissingFields($conn, $userInfo);
    $userInvoices = setUserInvoices($conn, $userInfo);
} elseif($userInfo->flag == 'remove') {
    $userInvoices = removeUserInvoices($conn, $userInfo->userId, $userInfo->clientId, $userInfo->invoiceNum);
} elseif($userInfo->flag == 'send') {
    $userInvoices = sendUserInvoices($conn, $userInfo);
} elseif($userInfo->flag == 'addStripe') {
    $userInvoices = recordUserStripeInfo($conn, $userInfo);
} elseif($userInfo->flag == 'checkStripe') {
    $userInvoices = checkUserStripeConnection($conn, $userInfo);
}
$conn->close();
echo json_encode($userInvoices);
?>