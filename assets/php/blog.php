<?php

function saveBlogPost($conn, $fields){
    
    $tablename     = "blog";
    $userId        = $fields->userId;
    $blogDate      = date('Ymd');
    $blogCategory  = $fields->blogCategory;
    $blogTitle     = $fields->blogTitle;
    $blogMediaNum  = 'BLOG' . strval(mt_rand(1000000, 9999999));
    $sql           = "INSERT INTO " . $tablename . " (userId, blogDate, blogCategory, blogNumLikes, blogRate, blogTitle, blogMediaNum) 
                                            VALUES('$userId','$blogDate','$blogCategory','0',        '0',  '$blogTitle', '$blogMediaNum');";                                        
    $conn->query($sql); 
    // creating media files: blog image, blog content and blog comments files
    $imgBlogPath = "../img/blog/{$blogMediaNum}.jpg";
    $blogContentPath = "../../blogContent/{$blogMediaNum}.md";
    $blogCommentsPath = "../../blogContent/{$blogMediaNum}.txt";

    // Create the files with the same random name in different directories
    if (filter_var($fields->blogImage, FILTER_VALIDATE_URL)) {
        // Image source is a URL
        saveImageFromURL($fields->blogImage, $imgBlogPath);
    } else if (preg_match('/^data:image\/(\w+);base64,/', $fields->blogImage, $type)) {
        // Image source is a base64 encoded data URL
        saveImageFromBase64($fields->blogImage, $imgBlogPath);
    }
    
    file_put_contents($blogContentPath, $fields->blogText);
    file_put_contents($blogCommentsPath, '');
    $out['status'] = true;
    return $out;
}

// Function to save image from URL
function saveImageFromURL($url, $savePath) {
    // Fetch image content
    $imageContent = file_get_contents($url);
    // Save the image as a .jpg file
    file_put_contents($savePath, $imageContent);
}

// Function to save image from base64
function saveImageFromBase64($dataUrl, $savePath) {
    // Get the file extension (type) from the data URL
    preg_match('/^data:image\/(\w+);base64,/', $dataUrl, $type);
    $extension = strtolower($type[1]); // jpg, png, gif, etc.
    // Remove the base64 prefix from the data URL
    $base64Image = str_replace('data:image/' . $extension . ';base64,', '', $dataUrl);
    $base64Image = str_replace(' ', '+', $base64Image);
    // Decode the base64 image data
    $imageData = base64_decode($base64Image);
    // Save the image as a .jpg file
    file_put_contents($savePath, $imageData);
}

function loadBlogPost($conn, $fields) {
    $tablename     = "blog";
    $limit         = (int)$fields->limit;
    $offset        = (int)$fields->offset;
    $sql           = "SELECT * FROM $tablename ORDER BY blogDate DESC LIMIT $limit OFFSET $offset;";
    $dbOut         = $conn->query($sql);
    $result        = array();
    
    while($data = $dbOut->fetch_assoc()){
        // files containing blog post information 
        $blogMediaNum     = $data['blogMediaNum'];
        $userId           = $data['userId'];
        $imgBlogPath      = "../img/blog/" . $blogMediaNum . ".jpg";
        $blogContentPath  = "../../blogContent/" . $blogMediaNum . ".md";
        $blogCommentsPath = "../../blogContent/" . $blogMediaNum . ".txt";
        $data['blogContent'] = file_get_contents($blogContentPath);
        $data['comments']    = file_get_contents($blogCommentsPath);
        $imageData           = base64_encode(file_get_contents($imgBlogPath));
        $imageType           = pathinfo($imgBlogPath, PATHINFO_EXTENSION);  // Get file extension
        $data['blogImage']   = "data:image/{$imageType};base64,{$imageData}";
        // finding the name from authentication table:
        $sql          = "SELECT name FROM authentication WHERE userId = '$userId';";
        $authorName   = $conn->query($sql);
        $authorName   = $authorName->fetch_assoc();
        $data['author'] = $authorName['name'];
        array_push($result, $data);
    }
    return $result;
}

function searchBlogPost($conn, $searchItems) {

    $searchBlogCreator = $searchItems->searchBlogCreator ?? '';
    $searchBlogContent = $searchItems->searchBlogContent ?? '';
    $searchBlogDate = $searchItems->searchBlogDate ?? '';
    $searchBlogRate = $searchItems->searchBlogRate ?? '';

    $tablename  = 'authentication';
    $sql        = "SELECT userId FROM $tablename WHERE name = '$searchBlogCreator';";
    $userId     = $conn->query($sql);
    $userId     = $userId->fetch_assoc();


    
    $tablename = 'blog';
    // Start the base query
    $sql = "SELECT * FROM $tablename WHERE 1=1";
    
    // Add conditions based on non-empty search items
    if (!empty($searchBlogContent)) {
        $sql .= " AND blogTitle LIKE '%" . $conn->real_escape_string($searchBlogContent) . "%'";
    }
    if (!empty($userId)) {
        $sql .= " AND userId LIKE '%" . $conn->real_escape_string($userId) . "%'";
    }
    if (!empty($searchBlogDate)) {
        $sql .= " AND blogDate = '" . $conn->real_escape_string($searchBlogDate) . "'";
    }
    if (!empty($searchBlogRate)) {
        $sql .= " AND blogRate = '" . $conn->real_escape_string($searchBlogRate) . "'";
    }

    // Execute the query
    $dbOut = $conn->query($sql);

    // Fetch results
    $result   = array();
    if ($dbOut && $dbOut->num_rows > 0) {
        while ($data = $dbOut->fetch_assoc()) {
            $blogMediaNum     = $data['blogMediaNum'];
            $userId           = $data['userId'];
            $imgBlogPath      = "../img/blog/" . $blogMediaNum . ".jpg";
            $blogContentPath  = "../../blogContent/" . $blogMediaNum . ".md";
            $blogCommentsPath = "../../blogContent/" . $blogMediaNum . ".txt";
            $data['blogContent'] = file_get_contents($blogContentPath);
            $data['comments']    = file_get_contents($blogCommentsPath);
            $imageData           = base64_encode(file_get_contents($imgBlogPath));
            $imageType           = pathinfo($imgBlogPath, PATHINFO_EXTENSION);  // Get file extension
            $data['blogImage']   = "data:image/{$imageType};base64,{$imageData}";
            array_push($result, $data);
        }
    }

    return $result;
}

function rateBlogPost($conn, $info){
    $tablename     = "blog";
    $rate = $info->rating;
    $blogMediaNum = $info->blogMediaNum;
    $sql           = "UPDATE $tablename SET blogRate = '$rate' WHERE blogMediaNum = '$blogMediaNum';";
    $conn->query($sql);
    $sql           = "UPDATE $tablename SET blogNumLikes = blogNumLikes + 1 WHERE blogMediaNum = '$blogMediaNum';";
    $conn->query($sql);
}


$userInfo        = json_decode($_POST['userInfo']);
// server connect
$servername  = "127.0.0.1";
$loginname   = "root";
$password    = "@Ssia123";
$dbname      = "Users";
$conn        = new mysqli($servername, $loginname, $password, $dbname);
// --------
if($userInfo->flag == 'save') {
    $data = saveBlogPost($conn, $userInfo);
} elseif($userInfo->flag == 'load') {
    $data = loadBlogPost($conn, $userInfo);
} elseif($userInfo->flag == 'search') {
    $data = searchBlogPost($conn, $userInfo->searchItems);
} elseif($userInfo->flag == 'rate') {
    $data = rateBlogPost($conn, $userInfo->values);
} 
$conn->close();
echo json_encode($data);
?>