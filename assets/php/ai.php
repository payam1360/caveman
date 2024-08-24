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
   $query_flag = empty($weight) && empty($height) && empty($age) && empty($gender) && 
   empty($goal) && empty($stress) && empty($sleep);
   if($rw == 'w' && !$query_flag) { 
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
if(isset($_GET['type']) && $_SESSION[$_GET['type']]['type'] == $_GET['type']) {
   $eventType   = $_GET['type'];
   $loc->meal   = $_SESSION[$eventType]['meal'];
   $loc->age    = $_SESSION[$eventType]['age'];
   $loc->gender = $_SESSION[$eventType]['gender'];
   $loc->height = $_SESSION[$eventType]['height'];
   $loc->weight = $_SESSION[$eventType]['weight'];
   $loc->goal   = $_SESSION[$eventType]['goal'];
   $loc->stress = $_SESSION[$eventType]['stress'];
   $loc->sleep  = $_SESSION[$eventType]['sleep'];
} else {
   $eventType   = '';
   $loc->meal   = '';
   $loc->age    = '';
   $loc->gender = '';
   $loc->height = '';
   $loc->weight = '';
   $loc->goal   = '';
   $loc->stress = '';
   $loc->sleep  = '';
}


switch ($eventType) {
   case 'If':
       $content = "write an intermittent fasting weekly program for a ";
       $newTokenSize = '100';
       break;
   case 'Cal':
       $content = "write a short paragraph about consistent weekly calories intake change to increase or decrease weight ";
       $newTokenSize = '100';
       break;
   case 'MicroVit':
       $content = "write a short paragraph about vitamins required by a ";
       $newTokenSize = '100';
       break;
   case 'MicroTrace':
       $content = "write a short paragraph about trace minerals required by a ";
       $newTokenSize = '100';
       break;    
   case 'Macro':
       $content = "write a short paragraph macros required by a ";
       $newTokenSize = '100';
       break;    
   case 'Bmr':
       $content = "write a short paragraph about Basal Metabolic Rate for a ";
       $newTokenSize = '100';
       break;    
   case 'Bmi':
       $content = "write a short paragraph about body mass index for a ";
       $newTokenSize = '100';
       break; 
   case 'Meal':
       $content = "create a meal plan for a ";
       $newTokenSize = '500';
       break;  
   default:
       $content = '';
       break;          
} 

// input prompt to the AI language model
$contentComplete   =  $content . $loc->gender . " , of " . strval($loc->age) . 
                     " age , with height of " . strval($loc->height) . 
                     " inches, weight of " . strval($loc->weight) . 
                     " lb, wanting to " . $loc->goal . " , with " . 
                     $loc->stress . " stress levels who sleeps " . $loc->sleep;

if((json_decode($loc->meal) == '' && $eventType == 'Meal' && !empty($loc->height)) || ($eventType != 'Meal' && $eventType != '') ) { // run the ai model
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
   $command = "python3 " . $pythonScript . " '" . $jsonInput . "'" . " " . $newTokenSize;

   $process = shell_exec($command);
   //$process = $command . " DONE";
   if($eventType == 'Meal') {
     db_call($process, $loc, 'w');
   }
   $process = explode(' ', $process); 

} elseif($eventType == 'Meal') { // load from the database ...
   $process = db_call($loc->meal, $loc, 'r');
   $process = explode(' ', $process);
} else {
   $process = ['please send the form to your client and collect their information. DONE'];
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



