<?php

class mealAttributes {
   public $gender;
   public $age;
   public $height;
   public $weight;
   public $goal;
   public $stress;
   public $sleep;
}

function db_call($process, $location, $rw) {
   $servername  = "127.0.0.1";
   $loginname   = "root";
   $password    = "@Ssia123";
   $dbname      = "Users";
   $tablename   = "ai";
   // Create connection
   $conn        = new mysqli($servername, $loginname, $password, $dbname);
   // check if the client exists.
   $gender = $location->gender; // male female
   $age    = $location->age;    // young mid old
   $height = $location->height; // tall mid short
   $weight = $location->weight; // fat mid skinny
   $goal   = $location->goal;   // lose, gain, 
   $stress = $location->stress; // lose, gain, 
   $sleep  = $location->sleep;  // lose, gain,
   $process = str_replace('\'','', $process);

   if($rw == 'w') { 
      $sql      = "INSERT INTO $tablename (age, gender, height, weight, goal, stress, sleep, meal) VALUES('$age', '$gender', '$height', '$weight', '$goal', '$stress','$sleep', '$process');";
      $db_out   = $conn->query($sql);
      $meal     = $process;
   } else {
      $meal = $process;
   }
   return($meal);
}


header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');

session_start();
$loc = new mealAttributes();
$loc->meal   = $_SESSION['meal'];
$loc->age    = $_SESSION['age'];
$loc->gender = $_SESSION['gender'];
$loc->height = $_SESSION['height'];
$loc->weight = $_SESSION['weight'];
$loc->goal   = $_SESSION['goal'];
$loc->stress = $_SESSION['stress'];
$loc->sleep  = $_SESSION['sleep'];

$content     = "create a meal plan for a " . 
               $loc->gender . 
               " , of " . 
               strval($loc->age) . 
               " age , with height of " . 
               strval($loc->height) . 
               " inches, weight of " . 
               strval($loc->weight) . 
               " lb, wanting to " . 
               $loc->goal . 
               " , with " . 
               $loc->stress . 
               " stress levels who sleeps " . 
               $loc->sleep;

if(json_decode($loc->meal) == '') { // run the ai model
   // Call Python script with JSON input
   $pythonScript = '../py/ai.py';
   $message_list = [
      [
         "role" => "system", 
         "content" => "You are a friendly chatbot. you answer questions accurately."
      ],
      [
         "role" => "user", 
         "content" => $content
      ]
   ]; 
   $jsonInput = json_encode($message_list); 
   $command = "python3 " . $pythonScript . " '" . $jsonInput . "'";

   $process = shell_exec($command);
   db_call($process, $loc, 'w');
   $process = explode(' ', $process); 

} else { // load from the database ...
   $process = db_call($loc->meal, $loc, 'r');
   $process = explode(' ', $process);
}

$i = 0;
while (1) {
   $tmp = $process[$i];
   $tmp = str_replace('\n',' NewLine ',$tmp);
   $tmp = str_replace('<|system|>',' Trainer: ', $tmp);
   $tmp = str_replace('"','', $tmp);
   $tmp = str_replace('<\s>','', $tmp);
   $tmp = str_replace('\\','', $tmp);
   $tmp = str_replace('<|assistant|>',' AI: ',$tmp);
   $tmp = str_replace('<|user|>',' Q: ',$tmp);
   $tmp = explode(' ', $tmp);
   for($kk = 0; $kk < count($tmp); $kk++){
      echo "data: " . $tmp[$kk] . "\n\n";
      ob_flush();
      flush();
      usleep(50000);  // Adjust the sleep time as needed
   }
   $i++;
}


?>



