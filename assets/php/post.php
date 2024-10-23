<?php

function getPexelToken(){
   $servername  = "127.0.0.1";
   $loginname   = "root";
   $password    = "@Ssia123";
   $dbname      = "Users";
   $conn        = new mysqli($servername, $loginname, $password, $dbname);
   $tablename   = "admin";
   $sql         = "SELECT pexelToken FROM $tablename;";
   $db_out      = $conn->query($sql);
   $pexelToken = $db_out->fetch_assoc();
   $pexelToken = $pexelToken['pexelToken'];
   return $pexelToken;
}


header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');

$eventType  = $_GET['EventType'];
$topic      = $_GET['topic'];
$philosophy = $_GET['philosophy'];
$category   = $_GET['category'];
$image      = $_GET['image']; 


switch ($eventType) {
   case 'content':
      $contentComplete = "write a short blog about " . $topic . " from a nutritionist perspective who believes in " . $philosophy;
      $newTokenSize = "10";
      break;
   case 'image':
      $apiKey = getPexelToken(); // Replace with your actual Pexels API key
      $query  = $image; // Search query for images
      $apiUrl = 'https://api.pexels.com/v1/search?query=' . urlencode($query) . '&per_page=1' . '&orienation=landscape';
      // Initialize a cURL session
      $ch = curl_init();  
      // Set cURL options
      curl_setopt($ch, CURLOPT_URL, $apiUrl);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, [
          'Authorization: ' . $apiKey // Pass the API key in the request headers
      ]);
      // Execute the cURL request and get the response
      $response = curl_exec($ch);
      // Close the cURL session
      curl_close($ch);
      // Decode the JSON response
      $data = json_decode($response, true);
      // Extract the first image URL if available
      $image_url = $data['photos'][0]['src']['medium']; // Use the 'medium' size image
      $process = '<|assistant|> ' . $image_url . ' DONE';
      break;
   case 'title':
      $contentComplete = "write a title for a blog post about " . $topic . " that falls into category of " . $category;
      $newTokenSize = "10";
      break;   
}


if($eventType != 'image') {
   // Call Python script with JSON input
   $pythonScript = '../py/ai.py';
   $message_list = [
      [
         "role" => "system", 
         "content" => "You are a friendly chatbot. you answer questions accurately."
      ],
      [
         "role" => "user", 
         "content" => $contentComplete
      ]
   ]; 
   $jsonInput = json_encode($message_list); 
   $command   = "python3 " . $pythonScript . " " . escapeshellarg($jsonInput) . " " . escapeshellarg($newTokenSize);

   // Optionally: execute the command
   $process = shell_exec($command);
} 
$process = explode(' ', $process);
// streaming the output message
$i = 0;
$start = false;
while (1) {
   $tmp = $process[$i];
   if(str_contains($tmp, '<|assistant|>')){
      $start = true;
      $tmp = '';
   } 
   if($start) {
      $tmp = str_replace('\n',' NewLine ',$tmp);
      $tmp = str_replace('"','', $tmp);
      $tmp = str_replace('<\s>','', $tmp);
      $tmp = str_replace('\\','', $tmp);
      $tmp = explode(' ', $tmp);
      for($kk = 0; $kk < count($tmp); $kk++){
         echo "data: " . $tmp[$kk] . "\n\n";
         ob_flush();
         flush();
         usleep(50000);  // Adjust the sleep time as needed
      }
   }
   $i++;
}

?>



