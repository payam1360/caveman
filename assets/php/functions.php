<?php

session_start();

function kg2lb($kg) {
    if($kg == []) {
        return([]);
    } else {
        return($kg * 2.2);
    }
}
function lb2kg($lb) {
    if($lb == []) {
        return([]);
    } else {
        return($lb * 0.45);
    }
}
function in2cm($in) {
    if($in == []) {
        return([]);
    } else {
        return($in * 2.54);
    }
}
function cm2in($cm) {
    if($cm == []) {
        return([]);
    } else {
        return($cm * 0.39);
    }
}
function in2ft($in) {
    if($in == []) {
        return([]);
    } else {
        return($in * 0.083);
    }
}
function ft2in($ft) {
    if($ft == []) {
        return([]);
    } else {
        return($ft * 12);
    }
}

// returns weight in lb  <-- Important
function getWeight($data) {
    $kk = 0;
    $weightDone = false;
    $Userweight = [];
    while(isset($data[$kk]->qIdx)){
        if($data[$kk]->qKey[0] == 'weight' && $weightDone == false){
            $Userweight = $data[$kk]->qAnswer;
            if(str_contains($Userweight, '<')) {
                $Userweight = 70; // minimum weigh in lb
            } elseif(str_contains($Userweight, '>')){
                $Userweight = 300; // maximum weight in lb
            } else {
                $Userweight = intval($Userweight);
            }
            $weightDone = true;
        } else { // weight is required
            $Userweight = [];
        }
        if($weightDone == true){
            break;
        }
        $kk++;
    }
    return($Userweight);
}

// returns height in inches  <-- Important
function getHeight($data) {
    $kk = 0;
    $heightDone = false;
    $Userheight = [];
    while(isset($data[$kk]->qIdx)){
        if($data[$kk]->qKey[0] == 'height' && $heightDone == false){
            $Userheight = $data[$kk]->qAnswer;
            if(isset($Userheight) && $Userheight != ''){
                if(str_contains($Userheight, '<')){
                    $Userheight = ft2in(4); // minimum heigh in inches
                } elseif(str_contains($Userheight, '>')){
                    $Userheight = ft2in(7); // maximum height in inches
                } else {
                    if(str_contains($Userheight, '-')){
                        $height = explode('-', $Userheight);
                        $Userheight = ft2in(intval($height[0])) + intval($height[1]); // hright in inches
                    } else {
                        $Userheight = ft2in(intval($Userheight));
                    }
                }
                $heightDone = true;
            } else {
                $Userheight = [];
            }
        } else {
            $Userheight = [];
        }
        if($heightDone == true){
            break;
        }
        $kk++;
    }
    return($Userheight);
}

// returns age rounded by year
function getAge($data) {
    $ageDone    = false;
    $kk         = 0;
    $Userage = [];
    while(isset($data[$kk]->qIdx)){
        if($data[$kk]->qKey[0] == 'age' && $ageDone == false){
            $Userage = $data[$kk]->qAnswer;
            if(str_contains($Userage, '<')) {
                $Userage = 18; // minimum age
            } elseif(str_contains($Userage, '>')){
                $Userage = 90; // maximum age
            } else {
                $Userage = intval($Userage);
            }
            $ageDone = true;
        } else {
            // age is needed
            $Userage = [];
        }
        if($ageDone == true){
            break;
        }
        $kk++;
    }
    return($Userage);
}

// returns user gender
function getGender($data) {
    $genderDone    = false;
    $kk            = 0;
    $Usergender = [];
    while(isset($data[$kk]->qIdx)){
        if($data[$kk]->qKey[0] == 'gender' && $genderDone == false && isset($data[$kk]->qAnswer) && $data[$kk]->qAnswer != ''){
            $UsergenderChoice = $data[$kk]->optionsText[0][$data[$kk]->qAnswer];
            if(str_contains($UsergenderChoice, 'female')) {
                $Usergender = 'female';
            } elseif(str_contains($UsergenderChoice, 'male')) {
                $Usergender = 'male';
            } else {
                $Usergender = []; // by default
            }
            $genderDone = true;
        } else {
            $Usergender = []; // by default
        }
        if($genderDone == true){
            break;
        }
        $kk++;
    }
    return($Usergender);
}
// user's stress 
function getStress($data) {
    $stressDone    = false;
    $kk            = 0;
    $Userstress = [];
    while(isset($data[$kk]->qIdx)){
        if($data[$kk]->qKey[0] == 'stress' && $stressDone == false && isset($data[$kk]->qAnswer) && $data[$kk]->qAnswer != ''){
            $UserstressChoice = $data[$kk]->optionsText[0][$data[$kk]->qAnswer];
            if(str_contains($UserstressChoice, 'high')) {
                $Userstress = 'high';
            } elseif(str_contains($UserstressChoice, 'medium')) {
                $Userstress = 'medium';
            } else {
                $Userstress = 'low';
            }
            $stressDone = true;
        } else {
            $Userstress = []; // by default
        }
        if($stressDone == true){
            break;
        }
        $kk++;
    }
    return($Userstress);
}
// user's stress 
function getSleep($data) {
    $sleepDone    = false;
    $kk            = 0;
    $Usersleep = [];
    while(isset($data[$kk]->qIdx)){
        if($data[$kk]->qKey[0] == 'sleep' && $sleepDone == false && isset($data[$kk]->qAnswer) && $data[$kk]->qAnswer != ''){
            $UsersleepChoice = $data[$kk]->optionsText[0][$data[$kk]->qAnswer];
            if(str_contains($UsersleepChoice, 'rested')) {
                $Usersleep = 'rested';
            } elseif(str_contains($UsersleepChoice, 'tired')) {
                $Usersleep = 'tired';
            } else {
                $Usersleep = 'rested';
            }
            $sleepDone = true;
        } else {
            $Usersleep = []; // by default
        }
        if($sleepDone == true){
            break;
        }
        $kk++;
    }
    return($Usersleep);
}

// user's goal
function getGoal($data) {
    $goalDone    = false;
    $kk          = 0;
    $Usergoal    = [];
    while(isset($data[$kk]->qIdx)){
        if($data[$kk]->qKey[0] == 'goal' && $goalDone == false && isset($data[$kk]->qAnswer) && $data[$kk]->qAnswer != ''){
            $UsergoalChoice = $data[$kk]->optionsText[0][$data[$kk]->qAnswer];
            if(str_contains($UsergoalChoice, 'lose')) {
                $Usergoal = 'lose';
            } elseif(str_contains($UsergoalChoice, 'gain')) {
                $Usergoal = 'gain';
            } else {
                $Usergoal = 'lose'; // by default the goal is to lose weight
            }
            $goalDone = true;
        } else {
            $Usergoal = []; // by default
        }
        if($goalDone == true){
            break;
        }
        $kk++;
    }
    return($Usergoal);
}

function calculateBmi($data){
    $Userweight = getWeight($data);
    $Userheight = getHeight($data);
    $Userage    = getAge($data);
    $Usergender = getGender($data);
    $Usergoal   = getGoal($data);
    $Userstress = getStress($data);
    $Usersleep  = getSleep($data);

    if($Userweight != [] && $Userheight != []) {
        $BMI['val']  = lb2kg($Userweight) / pow(in2cm($Userheight)/100, 2);
        if($data[0]->nutritionEng == "0") { // AI request has priority 
            $BMI['desc'] = requestGpt($Userweight, $Userheight, $Userage, $Usergender, $Usergoal, $Userstress, $Usersleep, 'Bmi'); //["This text should be generated using AI request!"];
        } elseif($data[0]->nutritionEng == "1") { // check dB, if exists, use it <- nutritionist, otherwise use software
            $BMI['desc'] = requestdB($BMI['val'], $Userweight, $Userheight, $Userage, $Usergender, $Usergoal, $Userstress, $Usersleep, $data[0]->userId, $data[0]->clientId, 'Bmi');
        }
    } else {
        $BMI['val']  = [];
        $BMI['desc'] = ['Please provide your comments here'];
    }
    return($BMI);
}

// this function is using Harris-Benedict equation for BMR calculation
function calculateBmr($data) {
    
    // convert measurements to standard
    $Userweight = lb2kg(getWeight($data)); 
    $Userheight = in2cm(getHeight($data));
    $Userage    = getAge($data);
    $Usergender = getGender($data);
    $Userstress = getStress($data);
    $Usersleep  = getSleep($data);
    $Usergoal   = getGoal($data);
    $BMI        = calculateBmi($data);

    if($Userweight != [] && $Userheight != [] && $Userage != [] 
            && $Usergender != [] && $Userstress != []) {
        if(str_contains($Userstress, 'low')) {
            $stressFactor = 1.2;
        } elseif(str_contains($Userstress, 'medium')) {
            $stressFactor = 1.6;
        } else {
            $stressFactor = 1.9;
        }
        if(str_contains($Usergender, 'female')) {
            $BMR['val'] = floor($stressFactor * ( 655.1 + (9.56 * $Userweight) + (1.85 * $Userheight) - (4.7 * $Userage)));
        } else {
            $BMR['val'] = floor($stressFactor * ( 66.47 + (13.75 * $Userweight) + (5.003 * $Userheight) - (6.75 * $Userage)));
        }
        if($data[0]->nutritionEng == "0") { // AI request has priority 
            $BMR['desc'] = requestGpt($Userweight, $Userheight, $Userage, $Usergender, $Usergoal, $Userstress, $Usersleep, 'Bmr'); //["This text should be generated using AI request!"];
        } elseif($data[0]->nutritionEng == "1") { // check dB, if exists, use it <- nutritionist, otherwise use software
            $BMR['desc'] = requestdB($BMI['val'], $Userweight, $Userheight, $Userage, $Usergender, $Usergoal, $Userstress, $Usersleep, $data[0]->userId, $data[0]->clientId, 'Bmr');
        }

    } else {
        $BMR['val'] = [];
        $BMR['desc'] = ['Please provide your comments here'];
    }
    return($BMR);
}

function calculateIf($data){
    // this function is the algorithm for calculating intervals in which the user is 
    // recommended for fasting. Fasting increase IGF or growth hormons.  
    // calculate intermittent fasting interval
    $IF['val']  = [[16,16,16,16,16,16,16],[8,8,8,8,8,8,8]];
    $IF['desc'] = [''];
    $Userage    = getAge($data);
    $Usergender = getGender($data);
    $Usergoal   = getGoal($data);
    $Userheight = getHeight($data);
    $Userweight = getWeight($data);
    $Userstress = getStress($data);
    $Usersleep  = getSleep($data);
    $BMI        = calculateBmi($data);

    // IF suggestion based on user's spec
    // -----------------------------------------------------------
    if($Userweight != []  && $Userage != [] 
            && $Usergender != [] && $Usergoal != []) {
        $valid = true;
    } else {
        $valid = false;       
    }
    if($valid) {

        $Bmi = $BMI['val'];

        $HighYoungMaleLose   = $Bmi > 25 && $Usergender == 'male'   && $Userage < 35 && $Usergoal == 'lose';
        $HighYoungMaleGain   = $Bmi > 25 && $Usergender == 'male'   && $Userage < 35 && $Usergoal == 'gain';
        $HighYoungFemaleLose = $Bmi > 25 && $Usergender == 'female' && $Userage < 35 && $Usergoal == 'lose';
        $HighYoungFemaleGain = $Bmi > 25 && $Usergender == 'female' && $Userage < 35 && $Usergoal == 'gain';
        $LowYoungMaleLose    = $Bmi < 25 && $Usergender == 'male'   && $Userage < 35 && $Usergoal == 'lose';
        $LowYoungMaleGain    = $Bmi < 25 && $Usergender == 'male'   && $Userage < 35 && $Usergoal == 'gain';
        $LowYoungFemaleLose  = $Bmi < 25 && $Usergender == 'female' && $Userage < 35 && $Usergoal == 'lose';
        $LowYoungFemaleGain  = $Bmi < 25 && $Usergender == 'female' && $Userage < 35 && $Usergoal == 'gain';
    
        $HighOldMaleLose   = $Bmi > 25 && $Usergender == 'male'   && $Userage >= 35 && $Usergoal == 'lose';
        $HighOldMaleGain   = $Bmi > 25 && $Usergender == 'male'   && $Userage >= 35 && $Usergoal == 'gain';
        $HighOldFemaleLose = $Bmi > 25 && $Usergender == 'female' && $Userage >= 35 && $Usergoal == 'lose';
        $HighOldFemaleGain = $Bmi > 25 && $Usergender == 'female' && $Userage >= 35 && $Usergoal == 'gain';
        $LowOldMaleLose    = $Bmi < 25 && $Usergender == 'male'   && $Userage >= 35 && $Usergoal == 'lose';
        $LowOldMaleGain    = $Bmi < 25 && $Usergender == 'male'   && $Userage >= 35 && $Usergoal == 'gain';
        $LowOldFemaleLose  = $Bmi < 25 && $Usergender == 'female' && $Userage >= 35 && $Usergoal == 'lose';
        $LowOldFemaleGain  = $Bmi < 25 && $Usergender == 'female' && $Userage >= 35 && $Usergoal == 'gain';
    
        if($HighYoungMaleLose) {
            $IF['val'] = [[16,16,16,16,16,16,16],[8,8,8,8,8,8,8]];
        }
        if($HighYoungMaleGain) {
            $IF['val'] = [[16,16,16,16,16,16,16],[8,8,8,8,8,8,8]];
        }
        if($HighYoungFemaleLose) {
            $IF['val'] = [[16,16,16,16,16,16,16],[8,8,8,8,8,8,8]];
        }
        if($HighYoungFemaleGain) {
            $IF['val'] = [[16,16,16,16,16,16,16],[8,8,8,8,8,8,8]];
        }
        if($LowYoungMaleLose) {
            $IF['val'] = [[12,12,12,12,12,12,12],[12,12,12,12,12,12,12]];
        }
        if($LowYoungMaleGain) {
            $IF['val'] = [[14,14,14,14,14,14,14],[10,10,10,10,10,10,10]];
        }
        if($LowYoungFemaleLose) {
            $IF['val'] = [[12,12,12,12,12,12,12],[12,12,12,12,12,12,12]];
        }
        if($LowYoungFemaleGain) {
            $IF['val'] = [[14,14,14,14,14,14,14],[10,10,10,10,10,10,10]];
        }
        if($HighOldMaleLose) {
            $IF['val'] = [[16,16,16,16,16,16,16],[8,8,8,8,8,8,8]];
        }
        if($HighOldMaleGain) {
            $IF['val'] = [[16,16,16,16,16,16,16],[8,8,8,8,8,8,8]];
        }
        if($HighOldFemaleLose) {
            $IF['val'] = [[16,16,16,16,16,16,16],[8,8,8,8,8,8,8]];
        }
        if($HighOldFemaleGain) {
            $IF['val'] = [[14,14,14,14,14,14,14],[10,10,10,10,10,10,10]];
        }        
        if($LowOldMaleLose) {
            $IF['val'] = [[12,12,12,12,12,12,12],[12,12,12,12,12,12,12]];
        }
        if($LowOldMaleGain) {
            $IF['val'] = [[14,14,14,14,14,14,14],[10,10,10,10,10,10,10]];
        }
        if($LowOldFemaleLose) {
            $IF['val'] = [[12,12,12,12,12,12,12],[12,12,12,12,12,12,12]];
        }
        if($LowOldFemaleGain) {
            $IF['val'] = [[14,14,14,14,14,14,14],[10,10,10,10,10,10,10]];
        }

        if($data[0]->nutritionEng == "0") { // AI request has priority 
            $IF['desc'] = requestGpt($Userweight, $Userheight, $Userage, $Usergender, $Usergoal, $Userstress, $Usersleep, 'If'); //["This text should be generated using AI request!"];
        } elseif($data[0]->nutritionEng == "1") { // check dB, if exists, use it <- nutritionist, otherwise use software
            $IF['desc'] = requestdB($BMI['val'], $Userweight, $Userheight, $Userage, $Usergender, $Usergoal, $Userstress, $Usersleep, $data[0]->userId, $data[0]->clientId, 'If');
        }
    } else {
        $IF['val']  = [];
        $IF['desc'] = ['Please provide your comments here'];
    }
        // -----------------------------------------------------------
    return($IF);
}



function calculateCalories($data){
    // this function is the algorithm for calculating calories during 8 weeks of
    // program working with the nutritionist.
    $Userage    = getAge($data);
    $Usergender = getGender($data);
    $Usergoal   = getGoal($data);
    $Userheight = getHeight($data);
    $Userweight = getWeight($data);
    $Userstress = getStress($data);
    $Usersleep  = getSleep($data);
    $BMI        = calculateBmi($data);
    $BMR        = calculateBmr($data);
    $CAL['val']  = [$BMR['val'],$BMR['val'],$BMR['val'],
                    $BMR['val'],$BMR['val'],$BMR['val'],$BMR['val'],$BMR['val']];
    $CAL['desc'] = [''];
    // IF suggestion based on user's spec
    // -----------------------------------------------------------
    if($Userweight != []  && $Userage != [] 
            && $Usergender != [] && $Usergoal != []) {
        $valid = true;
    } else {
        $valid = false;       
    }
    if($valid) {


        $Bmi = $BMI['val'];

        $HighYoungMaleLose   = $Bmi > 25 && $Usergender == 'male'   && $Userage < 35 && $Usergoal == 'lose';
        $HighYoungMaleGain   = $Bmi > 25 && $Usergender == 'male'   && $Userage < 35 && $Usergoal == 'gain';
        $HighYoungFemaleLose = $Bmi > 25 && $Usergender == 'female' && $Userage < 35 && $Usergoal == 'lose';
        $HighYoungFemaleGain = $Bmi > 25 && $Usergender == 'female' && $Userage < 35 && $Usergoal == 'gain';
        $LowYoungMaleLose    = $Bmi < 25 && $Usergender == 'male'   && $Userage < 35 && $Usergoal == 'lose';
        $LowYoungMaleGain    = $Bmi < 25 && $Usergender == 'male'   && $Userage < 35 && $Usergoal == 'gain';
        $LowYoungFemaleLose  = $Bmi < 25 && $Usergender == 'female' && $Userage < 35 && $Usergoal == 'lose';
        $LowYoungFemaleGain  = $Bmi < 25 && $Usergender == 'female' && $Userage < 35 && $Usergoal == 'gain';
    
        $HighOldMaleLose   = $Bmi > 25 && $Usergender == 'male'   && $Userage >= 35 && $Usergoal == 'lose';
        $HighOldMaleGain   = $Bmi > 25 && $Usergender == 'male'   && $Userage >= 35 && $Usergoal == 'gain';
        $HighOldFemaleLose = $Bmi > 25 && $Usergender == 'female' && $Userage >= 35 && $Usergoal == 'lose';
        $HighOldFemaleGain = $Bmi > 25 && $Usergender == 'female' && $Userage >= 35 && $Usergoal == 'gain';
        $LowOldMaleLose    = $Bmi < 25 && $Usergender == 'male'   && $Userage >= 35 && $Usergoal == 'lose';
        $LowOldMaleGain    = $Bmi < 25 && $Usergender == 'male'   && $Userage >= 35 && $Usergoal == 'gain';
        $LowOldFemaleLose  = $Bmi < 25 && $Usergender == 'female' && $Userage >= 35 && $Usergoal == 'lose';
        $LowOldFemaleGain  = $Bmi < 25 && $Usergender == 'female' && $Userage >= 35 && $Usergoal == 'gain';

        if($HighYoungMaleLose) {
            $Adjust = -1;
        }
        if($HighYoungMaleGain) {
            $Adjust = +1;
        }
        if($HighYoungFemaleLose) {
            $Adjust = -1;
        }
        if($HighYoungFemaleGain) {
            $Adjust = +1;
        }
        if($LowYoungMaleLose) {
            $Adjust = -1;
        }
        if($LowYoungMaleGain) {
            $Adjust = +1;
        }
        if($LowYoungFemaleLose) {
            $Adjust = -1;
        }
        if($LowYoungFemaleGain) {
            $Adjust = +1;
        }
        if($HighOldMaleLose) {
            $Adjust = -1;
        }
        if($HighOldMaleGain) {
            $Adjust = +1;
        }
        if($HighOldFemaleLose) {
            $Adjust = -1;
        }
        if($HighOldFemaleGain) {
            $Adjust = +1;
        }        
        if($LowOldMaleLose) {
            $Adjust = -1;
        }
        if($LowOldMaleGain) {
            $Adjust = +1;
        }
        if($LowOldFemaleLose) {
            $Adjust = -1;
        }
        if($LowOldFemaleGain) {
            $Adjust = +1;
        }
        $Adjust *= 500; // calories deficit or surplus.
        
        for($kk = 0; $kk < count($CAL['val']); $kk++){
            $CAL['val'][$kk] = $CAL['val'][$kk] + $kk * $Adjust;
        }
        if($data[0]->nutritionEng == "0") { // AI request has priority 
            $CAL['desc'] = requestGpt($Userweight, $Userheight, $Userage, $Usergender, $Usergoal, $Userstress, $Usersleep, 'Cal'); //["This text should be generated using AI request!"];
        } elseif($data[0]->nutritionEng == "1") { // check dB, if exists, use it <- nutritionist, otherwise use software
            $CAL['desc'] = requestdB($BMI['val'], $Userweight, $Userheight, $Userage, $Usergender, $Usergoal, $Userstress, $Usersleep, $data[0]->userId, $data[0]->clientId, 'Cal');
        }
    } else {
        $CAL['val']  = [];
        $CAL['desc'] = ['Please provide your comments here'];
    }
        // -----------------------------------------------------------
    return($CAL);
}


// function to calculate adjusted body weight
function calculateAjBW($data) {
    $Userweight = getWeight($data); // lb
    $Userheight = getHeight($data); // inch
    $Usergender = getGender($data);
    if($Userweight != [] && $Userheight != []  && $Usergender != [] ) {
        // calculation
        if(str_contains($Usergender, 'female')) {
            //$idealBodyWeight = 49 kg + 1.7 kg per every inch over 5 feet
            $idealBodyWeight = 49 + 1.7 * ($Userheight - 60); // kg
        } else {
            //$idealBodyWeight = 52 kg + 1.9 kg per every inch over 5 feet
            $idealBodyWeight = 52 + 1.9 * ($Userheight - 60); // kg
        }
        $adjustedBodyWeight = $idealBodyWeight + 0.4 * (lb2kg($Userweight) - $idealBodyWeight);
    } else {
        $adjustedBodyWeight = [];
    }
    return(kg2lb($adjustedBodyWeight));
}

function calculateMacro($data){
    // add Macro computation code
    // this function returns Macro requirements to maintain current weight
    // first calculate BMR
    // based on age, gender and goal do the followings:
    // 10-35% protein
    // 45-65% carbs
    // 20-35% fat
    // fiber
    $Userage    = getAge($data);
    $Usergender = getGender($data);
    $Usergoal   = getGoal($data);
    $Userheight = getHeight($data);
    $Userweight = getWeight($data);  
    $Userstress = getStress($data);
    $Usersleep  = getSleep($data);
    $BMI        = calculateBmi($data);
    $BMR        = calculateBmr($data);

    if($BMR['val'] != []) {
        // factor in the user's age
        if($Userage < 20) {
            $ageFactor = 0.95;
        } elseif($Userage >= 20 && $Userage < 30) {
            $ageFactor = 1;
        } elseif($Userage >= 30 && $Userage < 40) {
            $ageFactor = 1.05;
        } elseif($Userage >= 40 && $Userage < 50) {
            $ageFactor = 0.95;
        } elseif($Userage >= 50) {
            $ageFactor = 0.9;
        }

        $Bmi = $BMI['val'];

        $HighYoungMaleLose   = $Bmi > 25 && $Usergender == 'male'   && $Userage < 35 && $Usergoal == 'lose';
        $HighYoungMaleGain   = $Bmi > 25 && $Usergender == 'male'   && $Userage < 35 && $Usergoal == 'gain';
        $HighYoungFemaleLose = $Bmi > 25 && $Usergender == 'female' && $Userage < 35 && $Usergoal == 'lose';
        $HighYoungFemaleGain = $Bmi > 25 && $Usergender == 'female' && $Userage < 35 && $Usergoal == 'gain';
        $LowYoungMaleLose    = $Bmi < 25 && $Usergender == 'male'   && $Userage < 35 && $Usergoal == 'lose';
        $LowYoungMaleGain    = $Bmi < 25 && $Usergender == 'male'   && $Userage < 35 && $Usergoal == 'gain';
        $LowYoungFemaleLose  = $Bmi < 25 && $Usergender == 'female' && $Userage < 35 && $Usergoal == 'lose';
        $LowYoungFemaleGain  = $Bmi < 25 && $Usergender == 'female' && $Userage < 35 && $Usergoal == 'gain';
    
        $HighOldMaleLose   = $Bmi > 25 && $Usergender == 'male'   && $Userage >= 35 && $Usergoal == 'lose';
        $HighOldMaleGain   = $Bmi > 25 && $Usergender == 'male'   && $Userage >= 35 && $Usergoal == 'gain';
        $HighOldFemaleLose = $Bmi > 25 && $Usergender == 'female' && $Userage >= 35 && $Usergoal == 'lose';
        $HighOldFemaleGain = $Bmi > 25 && $Usergender == 'female' && $Userage >= 35 && $Usergoal == 'gain';
        $LowOldMaleLose    = $Bmi < 25 && $Usergender == 'male'   && $Userage >= 35 && $Usergoal == 'lose';
        $LowOldMaleGain    = $Bmi < 25 && $Usergender == 'male'   && $Userage >= 35 && $Usergoal == 'gain';
        $LowOldFemaleLose  = $Bmi < 25 && $Usergender == 'female' && $Userage >= 35 && $Usergoal == 'lose';
        $LowOldFemaleGain  = $Bmi < 25 && $Usergender == 'female' && $Userage >= 35 && $Usergoal == 'gain';

        if($HighYoungMaleLose) {
            $p = 0.3 * $ageFactor;
            $c = 0.5 * (2 - $ageFactor);
            $f = 1 - $p - $c;
            $fi = 28;
            $Macro['val'] =  [$p * $BMR['val'] / 4, $c * $BMR['val'] / 4, 
                              $f * $BMR['val'] / 9, $fi]; // [protein, carb, fat];               
    }
        if($HighYoungMaleGain) {
            $p = 0.3 * $ageFactor;
            $c = 0.55 * (2 - $ageFactor);
            $f = 1 - $p - $c;
            $fi = 30;
            $Macro['val'] =  [$p * $BMR['val'] / 4, $c * $BMR['val'] / 4, 
                              $f * $BMR['val'] / 9, $fi]; // [protein, carb, fat];               
        }
        if($HighYoungFemaleLose) {
            $p = 0.3 * $ageFactor;
            $c = 0.5 * (2 - $ageFactor);
            $f = 1 - $p - $c;
            $fi = 25;
            $Macro['val'] =  [$p * $BMR['val'] / 4, $c * $BMR['val'] / 4, 
                              $f * $BMR['val'] / 9, $fi]; // [protein, carb, fat];               
        }
        if($HighYoungFemaleGain) {
            $p = 0.3 * $ageFactor;
            $c = 0.55 * (2 - $ageFactor);
            $f = 1 - $p - $c;
            $fi = 25;
            $Macro['val'] =  [$p * $BMR['val'] / 4, $c * $BMR['val'] / 4, 
                              $f * $BMR['val'] / 9, $fi]; // [protein, carb, fat];               
        }
        if($LowYoungMaleLose) {
            $p = 0.35 * $ageFactor;
            $c = 0.45 * (2 - $ageFactor);
            $f = 1 - $p - $c;
            $fi = 38;
            $Macro['val'] =  [$p * $BMR['val'] / 4, $c * $BMR['val'] / 4, 
                              $f * $BMR['val'] / 9, $fi]; // [protein, carb, fat];               
        }
        if($LowYoungMaleGain) {
            $p = 0.3 * $ageFactor;
            $c = 0.6 * (2 - $ageFactor);
            $f = 1 - $p - $c;
            $fi = 38;
            $Macro['val'] =  [$p * $BMR['val'] / 4, $c * $BMR['val'] / 4, 
                              $f * $BMR['val'] / 9, $fi]; // [protein, carb, fat];               
        }
        if($LowYoungFemaleLose) {
            $p = 0.35 * $ageFactor;
            $c = 0.5 * (2 - $ageFactor);
            $f = 1 - $p - $c;
            $fi = 25;
            $Macro['val'] =  [$p * $BMR['val'] / 4, $c * $BMR['val'] / 4, 
                              $f * $BMR['val'] / 9, $fi]; // [protein, carb, fat];               
        }
        if($LowYoungFemaleGain) {
            $p = 0.3 * $ageFactor;
            $c = 0.5 * (2 - $ageFactor);
            $f = 1 - $p - $c;
            $fi = 30;
            $Macro['val'] =  [$p * $BMR['val'] / 4, $c * $BMR['val'] / 4, 
                              $f * $BMR['val'] / 9, $fi]; // [protein, carb, fat];               
        }
        if($HighOldMaleLose) {
            $p = 0.3 * $ageFactor;
            $c = 0.35 * (2 - $ageFactor);
            $f = 1 - $p - $c;
            $fi = 30;
            $Macro['val'] =  [$p * $BMR['val'] / 4, $c * $BMR['val'] / 4, 
                              $f * $BMR['val'] / 9, $fi]; // [protein, carb, fat];               
        }
        if($HighOldMaleGain) {
            $p = 0.3 * $ageFactor;
            $c = 0.4 * (2 - $ageFactor);
            $f = 1 - $p - $c;
            $fi = 30;
            $Macro['val'] =  [$p * $BMR['val'] / 4, $c * $BMR['val'] / 4, 
                              $f * $BMR['val'] / 9, $fi]; // [protein, carb, fat];               
        }
        if($HighOldFemaleLose) {
            $p = 0.3 * $ageFactor;
            $c = 0.35 * (2 - $ageFactor);
            $f = 1 - $p - $c;
            $fi = 25;
            $Macro['val'] =  [$p * $BMR['val'] / 4, $c * $BMR['val'] / 4, 
                              $f * $BMR['val'] / 9, $fi]; // [protein, carb, fat];               
        }
        if($HighOldFemaleGain) {
            $p = 0.3 * $ageFactor;
            $c = 0.4 * (2 - $ageFactor);
            $f = 1 - $p - $c;
            $fi = 25;
            $Macro['val'] =  [$p * $BMR['val'] / 4, $c * $BMR['val'] / 4, 
                              $f * $BMR['val'] / 9, $fi]; // [protein, carb, fat];               
        }        
        if($LowOldMaleLose) {
            $p = 0.25 * $ageFactor;
            $c = 0.45 * (2 - $ageFactor);
            $f = 1 - $p - $c;
            $fi = 25;
            $Macro['val'] =  [$p * $BMR['val'] / 4, $c * $BMR['val'] / 4, 
                              $f * $BMR['val'] / 9, $fi]; // [protein, carb, fat];               
        }
        if($LowOldMaleGain) {
            $p = 0.25 * $ageFactor;
            $c = 0.45 * (2 - $ageFactor);
            $f = 1 - $p - $c;
            $fi = 25;
            $Macro['val'] =  [$p * $BMR['val'] / 4, $c * $BMR['val'] / 4, 
                              $f * $BMR['val'] / 9, $fi]; // [protein, carb, fat];               
        }
        if($LowOldFemaleLose) {
            $p = 0.3 * $ageFactor;
            $c = 0.35 * (2 - $ageFactor);
            $f = 1 - $p - $c;
            $fi = 25;
            $Macro['val'] =  [$p * $BMR['val'] / 4, $c * $BMR['val'] / 4, 
                              $f * $BMR['val'] / 9, $fi]; // [protein, carb, fat];               
        }
        if($LowOldFemaleGain) {
            $p = 0.3 * $ageFactor;
            $c = 0.5 * (2 - $ageFactor);
            $f = 1 - $p - $c;
            $fi = 30;
            $Macro['val'] =  [$p * $BMR['val'] / 4, $c * $BMR['val'] / 4, 
                              $f * $BMR['val'] / 9, $fi]; // [protein, carb, fat];               
        }
        $Macro['val'] = array_map('floor', $Macro['val']);
        if($data[0]->nutritionEng == "0") { // AI request has priority 
            $Macro['desc'] = requestGpt($Userweight, $Userheight, $Userage, $Usergender, $Usergoal, $Userstress, $Usersleep, 'Macro'); //["This text should be generated using AI request!"];
        } elseif($data[0]->nutritionEng == "1") { // check dB, if exists, use it <- nutritionist, otherwise use software
            $Macro['desc'] = requestdB($BMI['val'], $Userweight, $Userheight, $Userage, $Usergender, $Usergoal, $Userstress, $Usersleep, $data[0]->userId, $data[0]->clientId, 'Macro');
        }
    } else {
        $Macro['val'] = [];
        $Macro['desc']  = ['Please provide your comments here'];
    } 
    return($Macro);
}

function calculateMeal($data){
    // add Micro computation code
    // vitamin         = [A, B1, B2, B3, B5, B6, B7, B9, B12, C, D, E, K]
    // trace minerals  = [Calcium, Chromium, Copper, Fluoride, 
    //                    Iodine, Iron, Magnesium, Manganese, 
    //                    Molybdenum, Phosphorus, Potassium, Selenium, Sodium, Zinc, Chloride]
    $Userage    = getAge($data);
    $Usergender = getGender($data);
    $Usergoal   = getGoal($data);
    $Userheight = getHeight($data);
    $Userweight = getWeight($data);
    $Userstress = getStress($data);
    $Usersleep  = getSleep($data);
    $BMI        = calculateBmi($data);
    $Meal['val'] = array('gender' => $Usergender,
                   'goal' => $Usergoal,
                   'height' => $Userheight,
                   'weight' => $Userweight,
                   'stress' => $Userstress,
                   'sleep' => $Usersleep, 
                   'age' => $Userage); 
    if(!isset($data[0])){
        $Meal['desc'] = [''];
    } elseif($data[0]->mealEng == "0") { // AI request has priority 
        $Meal['desc']   = requestGpt($Userweight, $Userheight, $Userage, $Usergender, $Usergoal, $Userstress, $Usersleep, 'Meal'); 
    } elseif($data[0]->mealEng == "1") { // check dB, if exists, use it <- nutritionist, otherwise use software
        $Meal['desc']   = requestdB($BMI['val'], $Userweight, $Userheight, $Userage, $Usergender, $Usergoal, $Userstress, $Usersleep, $data[0]->userId, $data[0]->clientId, 'Meal');
    } else {
        $Macro['val'] = [];
        $Macro['desc']  = ['Please provide your comments here'];
    }
    return($Meal);
}

function calculateMicro($data){
    // add Micro computation code
    // vitamin         = [A, B1, B2, B3, B5, B6, B7, B9, B12, C, D, E, K]
    // trace minerals  = [Calcium, Chromium, Copper, Fluoride, 
    //                    Iodine, Iron, Magnesium, Manganese, 
    //                    Molybdenum, Phosphorus, Potassium, Selenium, Sodium, Zinc, Chloride]
    $Userage    = getAge($data);
    $Usergender = getGender($data);
    $Usergoal   = getGoal($data);
    $Userheight = getHeight($data);
    $Userweight = getWeight($data);
    $Userstress = getStress($data);
    $Usersleep  = getSleep($data);
    $BMI        = calculateBmi($data);
    if($Userage != [] && $Usergender != []) {
        $valid = true;
    } else {
        $valid = false;
    }
    $vNames = ['A Retinol', 'Thiamin B1', 'Riboflavin B2', 
                   'Niacin B3','Pantothenic Acid B5', 'B6', 'Biotin B7', 'Folate B9', 'B12',
                   'C', 'D', 'E', 'K'];
    $tNames = ['Calcium', 'Chromium', 'Copper', 'Fluoride', 'Iodine', 'Iron', 'Magnesium', 
               'Manganese', 'Molybdenum', 'Phosphorus', 'Potassium', 'Selenium', 'Sodium',
               'Zinc', 'Chloride'];
    // units vector for vitamins
    $vUnits = ['ug RAE', 'mg', 'mg', 'mg', 'mg', 'mg', 'ug', 'ug DFE', 'ug', 'mg','IU', 'IU', 'ug'];
    // units vector for trace minerals
    $tUnits = ['g', 'ug', 'ug', 'mg', 'ug', 'mg', 'mg', 'mg', 'ug', 'mg', 'g', 'ug', 'g', 'mg', 'g'];
    $vScale = [100, 1, 1, 2, 1, 1, 10, 100, 1, 10, 100, 10, 20];
    $tScale = [1, 100, 100, 1, 100, 1, 100, 1, 10, 100, 1, 10, 1, 1, 1];
    if($valid) {
        if(str_contains($Usergender, 'female')) {
            if($Userage > 70) {
                $vValues = [7, 1.1, 1.1, 7, 5, 1.3, 3, 4, 2.4, 7.5, 8, 2.25, 4.5]; 
                $tValues = [1.2, 2, 9, 3, 1.5, 8, 3.2, 1.8, 4.5, 7, 4.7, 5.5, 1.2, 8, 3.1];
            } elseif($Userage >= 50 && $Userage <= 70) {
                $vValues = [7, 1.1, 1.1, 7, 5, 1.3, 3, 4, 2.4, 7.5, 6, 2.25, 4.5];
                $tValues = [1.2, 2, 9, 3, 1.5, 8, 3.2, 1.8, 4.5, 7, 4.7, 5.5, 1.3, 8, 3.1];
            } else {
                $vValues = [7, 1.1, 1.1, 7, 5, 1.3, 3, 4, 2.4, 7.5, 6, 2.25, 4.5];
                $tValues = [1, 2.5, 9, 3, 1.5, 18, 3.1, 1.8, 4.5, 7, 4.7, 5.5, 1.5, 8, 3.1];
            }       
        } elseif($Usergender === 'male') {
            if($Userage > 70) {
                $vValues = [9, 1.2, 1.3, 8, 5, 1.3, 3, 4, 2.4, 9, 8, 2.25, 6];
                $tValues = [1.2, 3, 9, 4, 1.5, 8, 4.2, 2.3, 4.5, 7, 4.7, 5.5, 1.2, 11, 3.1];
            } elseif($Userage >= 50 && $Userage <= 70) {
                $vValues = [9, 1.2, 1.3, 8, 5, 1.3, 3, 4, 2.4, 9, 6, 2.25, 6];
                $tValues = [1, 3, 9, 4, 1.5, 8, 4.2, 2.3, 4.5, 7, 4.7, 5.5, 1.3, 11, 3.1];
            } else {
                $vValues = [9, 1.2, 1.3, 8, 5, 1.3, 3, 4, 2.4, 9, 6, 2.25, 6];
                $tValues = [1, 3.5, 9, 4, 1.5, 8, 4, 2.3, 4.5, 7, 4.7, 5.5, 1.5, 11, 3.1];
            }
        } 
    } else {
        $vValues = [];
        $tValues = [];
    }   
 
    $Micro['val'] = array('vNames' => $vNames,
                   'tNames' => $tNames,
                   'vValues' => $vValues,
                   'tValues' => $tValues,
                   'vUnits' => $vUnits,
                   'tUnits' => $tUnits, 
                   'vScale' => $vScale,
                   'tScale' => $tScale);  
    if($valid)  {                                      
        if($data[0]->nutritionEng == "0") { // AI request has priority 
            $Micro['descVit']   = requestGpt($Userweight, $Userheight, $Userage, $Usergender, $Usergoal, $Userstress, $Usersleep, 'MicroVit'); //["This text should be generated using AI request!"];
            $Micro['descTrace'] = requestGpt($Userweight, $Userheight, $Userage, $Usergender, $Usergoal, $Userstress, $Usersleep, 'MicroTrace'); //["This text should be generated using AI request!"];
        } elseif($data[0]->nutritionEng == "1") { // check dB, if exists, use it <- nutritionist, otherwise use software
            $Micro['descVit']   = requestdB($BMI['val'], $Userweight, $Userheight, $Userage, $Usergender, $Usergoal, $Userstress, $Usersleep, $data[0]->userId, $data[0]->clientId, 'MicroVit');
            $Micro['descTrace'] = requestdB($BMI['val'], $Userweight, $Userheight, $Userage, $Usergender, $Usergoal, $Userstress, $Usersleep, $data[0]->userId, $data[0]->clientId, 'MicroTrace');
        }
    } else {
        $Micro['descVit']    = ['Please provide your comments here'];
        $Micro['descTrace']  = ['Please provide your comments here'];
    } 

    return($Micro);
}

function dataPrep($user_bmi, $user_bmr, $user_if, $user_macro, $user_micro, $user_cal, $meal){
    $data = array('status' => 0,
                 'bmi'   => $user_bmi,
                 'bmr'   => $user_bmr,
                 'if'    => $user_if,
                 'macro' => $user_macro,
                 'micro' => $user_micro,
                 'cal'   => $user_cal,
                 'meal'  => $meal);
    return $data;
}

function dbMealCon($weight, $height, $age, $gender, $goal, $stress, $sleep) {
    $servername   = "127.0.0.1";
    $loginname    = "root";
    $password     = "@Ssia123";
    $dbname       = "Users";
    $tablename    = "ai";
    // Create connection
    $query_flag = empty($weight) && empty($height) && empty($age) && empty($gender) && 
                  empty($goal) && empty($stress) && empty($sleep);
    $conn         = new mysqli($servername, $loginname, $password, $dbname);
    if(!$query_flag) {
        $sql          = "SELECT meal FROM $tablename WHERE 
                                                        age    = '$age' AND 
                                                        gender = '$gender' AND 
                                                        stress = '$stress' AND 
                                                        sleep  = '$sleep' AND 
                                                        height = '$height' AND 
                                                        weight = '$weight' AND 
                                                        goal   = '$goal';";
        $database_out = $conn->query($sql);
        $database_out = $database_out->fetch_assoc();
    } else {
        $database_out['meal'] = '';
    }
    return($database_out);
}

function dbCon($userId, $clientId) {
    $servername   = "127.0.0.1";
    $loginname    = "root";
    $password     = "@Ssia123";
    $dbname       = "Users";
    $tablename    = "userAllocation";
    // Create connection
    $conn         = new mysqli($servername, $loginname, $password, $dbname);
    // check if the client exists.
    $sql          = "SELECT * FROM $tablename WHERE userId = '$userId' AND clientId = '$clientId';";
    $database_out         = $conn->query($sql); 
    return($database_out);
}


function requestdB($Bmi, $Userweight, $Userheight, $Userage, $Usergender, $Usergoal, $Userstress, $Usersleep, $userId, $clientId, $contextFlag){
    $dbOut    = dbCon($userId, $clientId);
    $dbOutRow = $dbOut->fetch_assoc();
    $desc = [];
    // bunch of mutually exclusive flags
    $HighYoungMaleLose   = $Bmi > 25 && $Usergender == 'male'   && $Userage < 35 && $Usergoal == 'lose';
    $HighYoungMaleGain   = $Bmi > 25 && $Usergender == 'male'   && $Userage < 35 && $Usergoal == 'gain';
    $HighYoungFemaleLose = $Bmi > 25 && $Usergender == 'female' && $Userage < 35 && $Usergoal == 'lose';
    $HighYoungFemaleGain = $Bmi > 25 && $Usergender == 'female' && $Userage < 35 && $Usergoal == 'gain';
    $LowYoungMaleLose    = $Bmi < 25 && $Usergender == 'male'   && $Userage < 35 && $Usergoal == 'lose';
    $LowYoungMaleGain    = $Bmi < 25 && $Usergender == 'male'   && $Userage < 35 && $Usergoal == 'gain';
    $LowYoungFemaleLose  = $Bmi < 25 && $Usergender == 'female' && $Userage < 35 && $Usergoal == 'lose';
    $LowYoungFemaleGain  = $Bmi < 25 && $Usergender == 'female' && $Userage < 35 && $Usergoal == 'gain';

    $HighOldMaleLose   = $Bmi > 25 && $Usergender == 'male'   && $Userage >= 35 && $Usergoal == 'lose';
    $HighOldMaleGain   = $Bmi > 25 && $Usergender == 'male'   && $Userage >= 35 && $Usergoal == 'gain';
    $HighOldFemaleLose = $Bmi > 25 && $Usergender == 'female' && $Userage >= 35 && $Usergoal == 'lose';
    $HighOldFemaleGain = $Bmi > 25 && $Usergender == 'female' && $Userage >= 35 && $Usergoal == 'gain';
    $LowOldMaleLose    = $Bmi < 25 && $Usergender == 'male'   && $Userage >= 35 && $Usergoal == 'lose';
    $LowOldMaleGain    = $Bmi < 25 && $Usergender == 'male'   && $Userage >= 35 && $Usergoal == 'gain';
    $LowOldFemaleLose  = $Bmi < 25 && $Usergender == 'female' && $Userage >= 35 && $Usergoal == 'lose';
    $LowOldFemaleGain  = $Bmi < 25 && $Usergender == 'female' && $Userage >= 35 && $Usergoal == 'gain';

    if($contextFlag == 'Bmi') {
        if(!empty($dbOutRow['descBmi'])) { // use the entry by the user
            $desc = [$dbOutRow['descBmi']];
        } else { // use this software intelligence. (pre-set text not GPT ai)
            if($HighYoungMaleLose) {
                $desc = ['BMI Overview for Young Males Under 35.<br>
                <b>What is BMI?</b><br>
                Body Mass Index (BMI) estimates body fat based on height and weight.<br>
                <b>BMI Categories: </b><br>
                <b>Normal:</b> (18.5-24.9),<br> 
                <b>Overweight:</b> (25-29.9),<br>
                <b>Obese:</b> (30+).<br>
                <b>High BMI Concerns:</b><br>
                High BMI can lead to health risks like heart disease, diabetes, and high blood pressure.<br>
                <b>Weight Loss Tips:</b><br>
                <b>Set Goals:</b> Aim to lose 1-2 pounds per week.<br>
                <b>Healthy Eating:</b> Choose whole foods, reduce sugar and fat intake.<br>
                <b>Exercise:</b> At least 150 minutes of moderate activity weekly, plus strength training.<br>
                <b>Hydration:</b> Drink plenty of water.<br>
                <b>Sleep:</b> Get 7-9 hours of sleep per night.<br>
                <b>Track Progress:</b> Monitor weight and habits.<br>
                <b>Seek Support:</b> Consult healthcare providers for guidance.<br>
                Stay patient and consistent for long-term success.'];
            }
            if($HighYoungMaleGain) {
                $desc = ['<b>BMI Overview for Young Males Under 35</b><br>
                <b>What is BMI?</b><br>
                Body Mass Index (BMI) estimates body fat based on height and weight.<br>
                <b>BMI Categories:</b> Normal (18.5-24.9), Overweight (25-29.9), Obese (30+).<br>
                <b>High BMI and Muscle Gain</b><br>
                High BMI can include muscle mass, not just fat.<br>
                Muscle mass gain involves strength training and proper nutrition.<br>
                <b>Muscle Gain Tips</b><br>
                <b>Strength Training:</b> Focus on compound exercises (squats, deadlifts, bench press) 3-4 times a week.<br>
                <b>Nutrition:</b> Increase protein intake (chicken, fish, beans), eat balanced meals.<br>
                <b>Caloric Surplus:</b> Consume more calories than you burn, focusing on healthy foods.<br>
                <b>Hydration:</b> Drink plenty of water to support muscle recovery.<br>
                <b>Rest:</b> Ensure 7-9 hours of sleep per night for muscle repair.<br>
                <b>Track Progress:</b> Monitor muscle growth and adjust your plan as needed.<br>
                <b>Professional Advice:</b> Consider consulting a fitness trainer or nutritionist for personalized guidance.<br>
                Consistency and proper technique are key for effective muscle gain.<br>'];
            }
            if($HighYoungFemaleLose) {
                $desc = ['<b>BMI Overview for Young Females Under 35</b><br>
                <b>What is BMI?</b><br>
                Body Mass Index (BMI) estimates body fat based on height and weight.<br>
                <b>BMI Categories:</b> Normal (18.5-24.9), Overweight (25-29.9), Obese (30+).<br>
                <b>High BMI Concerns</b><br>
                High BMI can lead to health risks like heart disease, diabetes, and high blood pressure.<br>
                <b>Weight Loss Tips</b><br>
                <b>Set Goals:</b> Aim to lose 1-2 pounds per week.<br>
                <b>Healthy Eating:</b> Choose whole foods, reduce sugar and fat intake.<br>
                <b>Exercise:</b> At least 150 minutes of moderate activity weekly, plus strength training.<br>
                <b>Hydration:</b> Drink plenty of water.<br>
                <b>Sleep:</b> Get 7-9 hours of sleep per night.<br>
                <b>Track Progress:</b> Monitor weight and habits.<br>
                <b>Seek Support:</b> Consult healthcare providers for guidance.<br>
                Patience and consistency are key to successful weight loss.<br>'];
            }
            if($HighYoungFemaleGain) {
                $desc = ['<b>BMI Overview for Young Females Under 35</b><br>
                <b>What is BMI?</b><br>
                Body Mass Index (BMI) estimates body fat based on height and weight.<br>
                <b>BMI Categories:</b> Normal (18.5-24.9), Overweight (25-29.9), Obese (30+).<br>
                <b>High BMI and Muscle Gain</b><br>
                High BMI can include muscle mass, not just fat.<br>
                Muscle gain requires strength training and proper nutrition.<br>
                <b>Muscle Gain Tips</b><br>
                <b>Strength Training:</b> Focus on compound exercises (squats, deadlifts, bench press) 3-4 times a week.<br>
                <b>Nutrition:</b> Increase protein intake (chicken, fish, beans), eat balanced meals.<br>
                <b>Caloric Surplus:</b> Consume more calories than you burn, focusing on healthy foods.<br>
                <b>Hydration:</b> Drink plenty of water to support muscle recovery.<br>
                <b>Rest:</b> Ensure 7-9 hours of sleep per night for muscle repair.<br>
                <b>Track Progress:</b> Monitor muscle growth and adjust your plan as needed.<br>
                <b>Professional Advice:</b> Consider consulting a fitness trainer or nutritionist for personalized guidance.<br>
                Consistency and proper technique are key for effective muscle gain.<br>'];
            }
            // -------
            if($LowYoungMaleLose) {
                $desc = ['<b>Body Mass Index (BMI)</b> is a measure that uses height and 
                weight to estimate body fat. It is calculated by dividing a person’s 
                weight in kilograms by the square of their height in meters (kg/m²).<br>
                The <b>BMI categories are:</b><br>
                <b>Underweight:</b> BMI less than 18.5<br>
                <b>Normal weight:</b> BMI 18.5-24.9<br>
                <b>Overweight:</b> BMI 25-29.9<br>
                <b>Obesity:</b> BMI more than 30<br>
                <b>Advice</b><br>
                If you already have a low BMI (under 18.5) and are considering losing 
                more weight, it is important to prioritize your health. Further weight 
                loss could lead to various health issues, including nutrient deficiencies,
                weakened immune system, and decreased muscle mass. It is strongly 
                recommended to consult with a healthcare provider or a nutritionist to 
                ensure your health and well-being.<br>'];
            }
            if($LowYoungMaleGain) {
                $desc = ['<b>BMI Description and Definition</b><br>
                Body Mass Index (BMI) is a measure that uses height and weight to 
                estimate body fat. It is calculated by dividing a person’s 
                weight in kilograms by the square of their height in meters (kg/m²).<br>
                The <b>BMI categories are:</b><br>
                <b>Underweight:</b> BMI less than 18.5<br>
                <b>Normal weight:</b> BMI 18.5-24.9<br>
                <b>Overweight:</b> BMI 25-29.9<br>
                <b>Obesity:</b> BMI ≥ 30<br>
                For a young male under 35 with a low BMI (below 18.5), gaining muscle 
                mass involves a strategic approach. This individual is likely 
                underweight and should aim to increase both caloric intake and protein 
                consumption to support muscle growth. A well-rounded diet rich in lean 
                proteins, complex carbohydrates, and healthy fats is essential, 
                alongside a consistent strength training regimen.<br>
                The ultimate goal is to achieve a healthier BMI through muscle gain 
                and improved overall body composition.<br>'];
            }
            if($LowYoungFemaleLose) {
                $desc = ['<b>Body Mass Index (BMI)</b> is a measure that uses height 
                and weight to estimate body fat. It is calculated by dividing a 
                person’s weight in kilograms by the square of their height in meters 
                (kg/m²).<br>
                The <b>BMI categories are:</b><br>
                <b>Underweight:</b> BMI less than 18.5<br>
                <b>Normal weight:</b> BMI 18.5-24.9<br>
                <b>Overweight:</b> BMI 25-29.9<br>
                <b>Obesity:</b> BMI more than 30<br>
                <b>Advice</b><br>
                If you already have a low BMI (under 18.5) and are considering 
                losing more weight, it is important to prioritize your health. 
                Further weight loss could lead to various health issues, 
                including nutrient deficiencies, weakened immune system, and 
                decreased muscle mass. It is strongly recommended to consult with 
                a healthcare provider or a nutritionist to ensure your health and 
                well-being.<br>'];
            }
            if($LowYoungFemaleGain) {
                $desc = ['<b>BMI Description for a Young Female Under 35 with Low BMI 
                Trying to Gain Muscle Mass</b><br>
                Body Mass Index (BMI) is a measure that uses height and weight to 
                estimate body fat. It is calculated by dividing a person’s weight 
                in kilograms by the square of their height in meters (kg/m²). 
                The <b>BMI categories are:</b><br>
                <b>Underweight:</b> BMI less than 18.5<br>
                <b>Normal weight:</b> BMI 18.5-24.9<br>
                <b>Overweight:</b> BMI 25-29.9<br>
                <b>Obesity:</b> BMI more than 30<br>
                <b>Advice</b><br>
                For a young female with a low BMI looking to gain muscle mass:<br>
                <b>Increase Caloric Intake:</b> Eat more calories than you burn, 
                focusing on nutrient-dense foods.<br>
                <b>Consume Protein:</b> Include high-protein foods like lean meats, 
                dairy, legumes, and protein supplements.<br>
                <b>Strength Training:</b> Engage in regular strength training exercises 
                to build muscle.<br>
                <b>Rest and Recover:</b> Ensure adequate sleep and rest days to support 
                muscle growth.<br>
                Always consider consulting a healthcare provider or nutritionist for 
                personalized guidance.<br>'];
            }

            if($HighOldMaleLose) {
                $desc = ['<b>BMI Description for an Older Male Above 40 with High BMI Trying to Lose Weight</b><br>
                Body Mass Index (BMI) is a measure of body fat based on height and weight, calculated as weight (kg) divided by height (m²).<br>
                <b>Overweight:</b> BMI 25-29.9<br>
                <b>Obesity:</b> BMI ≥ 30<br>
                <b>Advice</b><br>
                For an older male with a high BMI:<br>
                <b>Aim for a Caloric Deficit:</b> Consume fewer calories than you burn to promote weight loss.<br>
                <b>Focus on Balanced Nutrition:</b> Include a mix of lean proteins, healthy fats, and complex carbohydrates.<br>
                <b>Incorporate Physical Activity:</b> Engage in regular exercise, including both cardio and strength training.<br>
                <b>Monitor Health:</b> Consider regular check-ups to manage any age-related health issues.<br>
                Consult with a healthcare provider for personalized advice and to ensure a safe weight loss approach.<br>'];
            }
            if($HighOldMaleGain) {
                $desc = ['<b>BMI Description for an Older Male Above 40 with High BMI Trying to Gain Muscle Mass</b><br>
                Body Mass Index (BMI) is a measure of body fat based on height and weight:<br>
                <b>Overweight:</b> BMI 25-29.9<br>
                <b>Obesity:</b> BMI ≥ 30<br>
                <b>Advice</b><br>
                For an older male with a high BMI looking to gain muscle mass:<br>
                <b>Increase Caloric Intake:</b> Focus on nutrient-dense, higher-calorie foods to support muscle growth.<br>
                <b>Emphasize Protein:</b> Include high-protein foods to aid muscle development.<br>
                <b>Strength Training:</b> Engage in regular resistance exercises to build muscle.<br>
                <b>Monitor Progress:</b> Track changes in muscle mass and adjust your diet and training as needed.<br>
                Consult with a healthcare provider to ensure a safe and effective approach.<br>'];
            }
            if($HighOldFemaleLose) {
                $desc = ['<b>BMI Description for an Older Female Above 40 with High BMI Trying to Lose Weight</b><br>
                Body Mass Index (BMI) is a measure of body fat based on height and weight:<br>
                <b>Overweight:</b> BMI 25-29.9<br>
                <b>Obesity:</b> BMI ≥ 30<br>
                <b>Advice</b><br>
                For an older female with a high BMI:<br>
                <b>Create a Caloric Deficit:</b> Consume fewer calories than you burn to promote weight loss.<br>
                <b>Balanced Diet:</b> Focus on nutrient-rich foods like lean proteins, vegetables, and whole grains.<br>
                <b>Regular Exercise:</b> Incorporate both cardio and strength training to support weight loss and maintain muscle mass.<br>
                <b>Monitor Health:</b> Consult with a healthcare provider to ensure the weight loss plan is safe and effective.<br>'];
            }
            if($HighOldFemaleGain) {
                $desc = ['<b>BMI Description for an Older Female Above 40 with High BMI Trying to Gain Muscle Mass</b><br>
                Body Mass Index (BMI) is a measure of body fat based on height and weight:<br>
                <b>Overweight:</b> BMI 25-29.9<br>
                <b>Obesity:</b> BMI ≥ 30<br>
                <b>Advice</b><br>
                For an older female with a high BMI aiming to gain muscle mass:<br>
                <b>Increase Caloric Intake:</b> Eat more calories with a focus on nutrient-dense foods.<br>
                <b>High-Protein Diet:</b> Include protein-rich foods to support muscle growth.<br>
                <b>Strength Training:</b> Engage in regular resistance exercises.<br>
                <b>Monitor Progress:</b> Track muscle gains and adjust your diet and exercise plan as needed.<br>
                Consult a healthcare provider to ensure a safe and effective approach.<br>'];
            }
            // -------
            if($LowOldMaleLose) {
                $desc = ['<b>BMI Description for an Older Male Above 40 with Low BMI Trying to Lose Weight</b><br>
                Body Mass Index (BMI) is a measure of body fat based on height and weight:<br>
                <b>Underweight:</b> BMI less than 18.5<br>
                <b>Advice</b><br>
                For an older male with a low BMI trying to lose weight:<br>
                <b>Monitor Health:</b> Focus on overall health rather than just weight loss.<br>
                <b>Consult a Healthcare Provider:</b> Ensure weight loss is safe and appropriate given the low BMI.<br>
                <b>Balanced Approach:</b> Maintain a balanced diet and incorporate regular physical activity, but prioritize health over further weight loss.<br>'];
            }
            if($LowOldMaleGain) {
                $desc = ['<b>BMI Description for an Older Male Above 40 with Low BMI Trying to Gain Muscle Mass</b><br>
                Body Mass Index (BMI) is a measure of body fat based on height and weight:<br>
                <b>Underweight:</b> BMI less than 18.5<br>
                <b>Advice</b><br>
                For an older male with a low BMI aiming to gain muscle mass:<br>
                <b>Increase Caloric Intake:</b> Eat more calories with a focus on nutrient-dense foods.<br>
                <b>Protein-Rich Diet:</b> Include plenty of protein to support muscle growth.<br>
                <b>Strength Training:</b> Engage in regular resistance exercises.<br>
                <b>Monitor Progress:</b> Track changes in muscle mass and adjust your diet and exercise routine as needed.<br>
                Consult with a healthcare provider to ensure a safe and effective plan.<br>'];
            }
            if($LowOldFemaleLose) {
                $desc = ['<b>BMI Description for an Older Female Above 40 with Low BMI Trying to Lose Weight</b><br>
                Body Mass Index (BMI) is a measure of body fat based on height and weight:<br>
                <b>Underweight:</b> BMI less than 18.5<br>
                <b>Advice</b><br>
                For an older female with a low BMI trying to lose weight:<br>
                <b>Consult a Healthcare Provider:</b> Ensure that further weight loss is safe and appropriate given the low BMI.<br>
                <b>Focus on Health:</b> Prioritize overall health and well-being rather than just weight loss.<br>
                <b>Balanced Diet:</b> Maintain a nutrient-rich diet and consider moderate physical activity.<br>'];
            }
            if($LowOldFemaleGain) {
                $desc = ['<b>BMI Description for an Older Female Above 40 with Low BMI Trying to Gain Muscle Mass</b><br>
                Body Mass Index (BMI) is a measure of body fat based on height and weight:<br>
                <b>Underweight:</b> BMI less than 18.5<br>
                <b>Advice</b><br>
                For an older female with a low BMI aiming to gain muscle mass:<br>
                <b>Increase Caloric Intake:</b> Eat more calories with a focus on nutrient-dense foods.<br>
                <b>Protein-Rich Diet:</b> Include high-protein foods to support muscle growth.<br>
                <b>Strength Training:</b> Engage in regular resistance exercises.<br>
                <b>Monitor Progress:</b> Track changes in muscle mass and adjust your diet and exercise plan as needed.<br>
                Consult a healthcare provider to ensure a safe and effective approach.<br>'];
            }
        }
    } elseif($contextFlag == "Bmr") {
        if(!empty($dbOutRow['descBmr'])) { // use the entry by the user
            $desc = [$dbOutRow['descBmr']];
        } else { 
            $desc = ['<b>Basal Metabolic Rate (BMR) Definition and Description</b><br>
            <b>What is BMR?</b><br>
            Basal Metabolic Rate (BMR) is the number of calories your body needs to maintain basic physiological functions while at rest. These functions include breathing, circulation, cell production, nutrient processing, and temperature regulation.<br>
            <b>Key Points About BMR:</b><br>
            <b>Basic Function Maintenance:</b> BMR represents the energy required for essential bodily functions, not including physical activities or digestion.<br>
            <b>Measurement Conditions:</b> BMR is typically measured under very specific conditions: after a full night’s sleep, in a fasted state, and in a thermoneutral environment to ensure the body is at rest and not expending extra energy for digestion or temperature regulation.<br>
            <b>Influencing Factors:</b> Several factors influence BMR, including:<br>
            <b>Age:</b> BMR generally decreases with age.<br>
            <b>Sex:</b> Males typically have a higher BMR than females due to a greater muscle mass.<br>
            <b>Body Composition:</b> More muscle mass increases BMR, while more fat mass does not.<br>
            <b>Genetics:</b> Genetic makeup can influence BMR.<br>
            <b>Hormones:</b> Hormonal levels (e.g., thyroid hormones) can affect BMR.<br>
            <b>Calculating BMR:</b> While direct measurement requires specialized equipment, BMR can be estimated using equations such as the Harris-Benedict Equation or the Mifflin-St Jeor Equation, which take into account age, sex, weight, and height.<br>
            <b>Importance of BMR:</b><br>
            <b>Weight Management:</b> Understanding your BMR can help in planning dietary intake and exercise routines for weight loss, maintenance, or gain.<br>
            <b>Caloric Needs:</b> BMR forms the baseline for calculating Total Daily Energy Expenditure (TDEE), which includes additional calories burned through physical activity and digestion.<br>
            <b>Example Calculation (Using Mifflin-St Jeor Equation):</b><br>
            For a 30-year-old woman, 65 kg, 165 cm tall:<br>
            BMR = (10 * weight in kg) + (6.25 * height in cm) - (5 * age in years) - 161<br>
            BMR = (10 * 65) + (6.25 * 165) - (5 * 30) - 161<br>
            BMR = 650 + 1031.25 - 150 - 161<br>
            BMR ≈ 1370 kcal/day<br>
            This means she needs approximately 1370 calories per day to maintain basic bodily functions at rest.<br>'];
        }
    } elseif($contextFlag == "If") {
        if(!empty($dbOutRow['descIf'])) { // use the entry by the user
            $desc = [$dbOutRow['descIf']];
        } else { 
            if($HighYoungMaleLose) {
                $desc = ['<b>Intermittent Fasting Recommendation for a Young Male Under 35 with High BMI Trying to Lose Weight</b><br>
                Intermittent Fasting (IF) involves cycling between periods of eating and fasting. It can help with weight loss by reducing calorie intake and improving metabolic health.<br>
                <b>Simple IF Recommendation:</b><br>
                <b>16/8 Method:</b><br>
                <b>Fasting Window:</b> Fast for 16 hours.<br>
                <b>Eating Window:</b> Eat all your meals within an 8-hour window (e.g., 12 PM to 8 PM).<br>
                <b>Eat Balanced Meals:</b> Focus on nutrient-dense foods during your eating window, including lean proteins, healthy fats, and plenty of vegetables.<br>
                <b>Stay Hydrated:</b> Drink water, tea, or black coffee during the fasting period to stay hydrated.<br>
                <b>Consistency:</b> Stick to the schedule daily for best results.<br>
                <b>Note:</b><br>
                Consult with a healthcare provider before starting any new diet regimen to ensure it is safe and appropriate for your individual health needs.'];
            }
            if($HighYoungMaleGain) {
                $desc = ['<b>Intermittent Fasting Recommendation for a Young Male Under 35 with High BMI Trying to Gain Muscle Mass</b><br>
                Intermittent Fasting (IF) can help manage your eating schedule while focusing on muscle gain. Here is a simple approach:<br>
                <b>16/8 Method:</b><br>
                <b>Fasting Window:</b> Fast for 16 hours.<br>
                <b>Eating Window:</b> Eat all meals within an 8-hour window (e.g., 12 PM to 8 PM).<br>
                <b>Nutrient-Dense Meals:</b><br>
                <b>Protein:</b> Include protein-rich foods like lean meats, fish, eggs, and dairy.<br>
                <b>Healthy Fats:</b> Eat avocados, nuts, seeds, and olive oil.<br>
                <b>Complex Carbs:</b> Focus on whole grains, fruits, and vegetables.<br>
                <b>Pre- and Post-Workout Nutrition:</b><br>
                Eat a balanced meal or snack before and after workouts within your eating window to support muscle growth and recovery.<br>
                <b>Stay Hydrated:</b><br>
                Drink water, black coffee, or tea during fasting periods.<br>
                <b>Supplements:</b><br>
                Consider protein supplements and creatine if needed.<br>
                <b>Note:</b><br>
                Consult with a healthcare provider or nutritionist to ensure this plan suits your individual health needs and goals.'];
            }
            if($HighYoungFemaleLose) {
                $desc = ['<b>Intermittent Fasting Recommendation for a Young Male Under 35 with High BMI Trying to Lose Weight</b><br>
                Intermittent Fasting (IF) can be an effective strategy for weight loss. Here is a straightforward approach:<br>
                <b>16/8 Method:</b><br>
                <b>Fasting Window:</b> Fast for 16 hours.<br>
                <b>Eating Window:</b> Consume all meals within an 8-hour window (e.g., 12 PM to 8 PM).<br>
                <b>Balanced Meals:</b><br>
                <b>Nutrient-Dense Foods:</b> Focus on lean proteins, vegetables, whole grains, and healthy fats.<br>
                <b>Portion Control:</b> Be mindful of portion sizes to maintain a calorie deficit.<br>
                <b>Stay Hydrated:</b><br>
                Drink plenty of water, black coffee, or tea during fasting periods.<br>
                <b>Exercise:</b><br>
                Incorporate regular physical activity, including both cardio and strength training.<br>
                <b>Note:</b><br>
                Consult with a healthcare provider to ensure this plan is safe and effective for your individual needs.'];
            }
            if($HighYoungFemaleGain) {
                $desc = ['<b>Intermittent Fasting Recommendation for a Young Male Under 35 with High BMI Trying to Gain Muscle Mass</b><br>
                <b>16/8 Method:</b><br>
                <b>Fasting Window:</b> 16 hours.<br>
                <b>Eating Window:</b> 8 hours (e.g., 12 PM to 8 PM).<br>
                <b>Focus on Nutrient-Dense Meals:</b><br>
                <b>Protein:</b> Include sources like lean meats, fish, and protein supplements.<br>
                <b>Healthy Fats:</b> Add avocados, nuts, and olive oil.<br>
                <b>Complex Carbs:</b> Eat whole grains, fruits, and vegetables.<br>
                <b>Pre- and Post-Workout Nutrition:</b><br>
                Ensure meals around workouts to support energy and recovery.<br>
                <b>Hydrate:</b><br>
                Drink plenty of water, and consider black coffee or tea during fasting.<br>
                <b>Note:</b><br>
                Consult a healthcare provider to tailor the plan to your specific needs.'];
            }
            // -------
            if($LowYoungMaleLose) {
                $desc = ['<b>Intermittent Fasting Recommendation for a Young Male Under 35 with Low BMI Trying to Lose Weight</b><br>
                <b>12/12 Method:</b><br>
                <b>Fasting Window:</b> 12 hours.<br>
                <b>Eating Window:</b> 12 hours (e.g., 8 AM to 8 PM).<br>
                <b>Balanced Nutrition:</b><br>
                <b>Nutrient-Dense Foods:</b> Focus on lean proteins, healthy fats, and whole grains.<br>
                <b>Maintain Energy:</b> Ensure you are not undereating, even while creating a caloric deficit.<br>
                <b>Hydrate:</b><br>
                Drink water, and black coffee or tea during fasting.<br>
                <b>Monitor Health:</b><br>
                Track progress and adjust as needed. Consult a healthcare provider to ensure safety.<br>
                <b>Note:</b><br>
                A cautious approach is crucial to avoid further reducing an already low BMI.'];
            }
            if($LowYoungMaleGain) {
                $desc = ['<b>Intermittent Fasting Recommendation for a Young Male Under 35 with Low BMI Trying to Gain Muscle Mass</b><br>
                <b>14/10 Method:</b><br>
                <b>Fasting Window:</b> 14 hours.<br>
                <b>Eating Window:</b> 10 hours (e.g., 10 AM to 8 PM).<br>
                <b>High-Calorie, Nutrient-Dense Meals:</b><br>
                <b>Protein:</b> Include lean meats, fish, eggs, and protein shakes.<br>
                <b>Healthy Fats:</b> Add avocados, nuts, and olive oil.<br>
                <b>Complex Carbs:</b> Eat whole grains, fruits, and vegetables.<br>
                <b>Pre- and Post-Workout:</b><br>
                Ensure adequate meals or snacks around workouts for energy and recovery.<br>
                <b>Hydrate:</b><br>
                Drink water, and consider black coffee or tea during fasting.<br>
                <b>Note:</b><br>
                Consult a healthcare provider to tailor the approach to your needs and avoid further reducing BMI.'];
            }
            if($LowYoungFemaleLose) {
                $desc = ['<b>Intermittent Fasting Recommendation for a Young Female Under 35 with Low BMI Trying to Lose Weight</b><br>
                <b>12/12 Method:</b><br>
                <b>Fasting Window:</b> 12 hours.<br>
                <b>Eating Window:</b> 12 hours (e.g., 8 AM to 8 PM).<br>
                <b>Nutrient-Dense Foods:</b><br>
                <b>Balanced Meals:</b> Focus on lean proteins, healthy fats, and whole grains.<br>
                <b>Monitor Intake:</b> Ensure you are still meeting nutritional needs even with a caloric deficit.<br>
                <b>Hydrate:</b><br>
                Drink water, and black coffee or tea during fasting.<br>
                <b>Consult a Healthcare Provider:</b><br>
                Ensure that the approach is safe given your low BMI and adjust as needed.<br>
                <b>Note:</b><br>
                Be cautious to avoid further reducing an already low BMI.'];
            }
            if($LowYoungFemaleGain) {
                $desc = ['<b>Intermittent Fasting Recommendation for a Young Female Under 35 with Low BMI Trying to Gain Muscle Mass</b><br>
                <b>14/10 Method:</b><br>
                <b>Fasting Window:</b> 14 hours.<br>
                <b>Eating Window:</b> 10 hours (e.g., 10 AM to 8 PM).<br>
                <b>High-Calorie, Nutrient-Dense Meals:</b><br>
                <b>Protein:</b> Include sources like lean meats, fish, eggs, and protein shakes.<br>
                <b>Healthy Fats:</b> Add avocados, nuts, and olive oil.<br>
                <b>Complex Carbs:</b> Focus on whole grains, fruits, and vegetables.<br>
                <b>Pre- and Post-Workout Nutrition:</b><br>
                Ensure meals or snacks around workouts to support muscle growth and recovery.<br>
                <b>Hydrate:</b><br>
                Drink water, and consider black coffee or tea during fasting.<br>
                <b>Note:</b><br>
                Consult a healthcare provider to ensure the approach supports muscle gain without negatively affecting your low BMI.'];
            }
            // --------
            if($HighOldMaleLose) {
                $desc = ['<b>Intermittent Fasting Recommendation for an Older Male Above 35-40 with High BMI Trying to Lose Weight</b><br>
                <b>16/8 Method:</b><br>
                <b>Fasting Window:</b> 16 hours.<br>
                <b>Eating Window:</b> 8 hours (e.g., 12 PM to 8 PM).<br>
                <b>Balanced Nutrition:</b><br>
                <b>Lean Proteins:</b> Include sources like chicken, fish, and legumes.<br>
                <b>Healthy Fats:</b> Add nuts, seeds, and olive oil.<br>
                <b>Complex Carbs:</b> Focus on whole grains and vegetables.<br>
                <b>Hydrate:</b><br>
                Drink plenty of water, and consider black coffee or tea during fasting.<br>
                <b>Exercise:</b><br>
                Incorporate regular physical activity, including both cardio and strength training.<br>
                <b>Note:</b><br>
                Consult with a healthcare provider to ensure the approach is safe and effective for your health needs.'];
            }
            if($HighOldMaleGain) {
                $desc = ['<b>Intermittent Fasting Recommendation for an Older Male Above 35-40 with High BMI Trying to Gain Muscle Mass</b><br>
                <b>16/8 Method:</b><br>
                <b>Fasting Window:</b> 16 hours.<br>
                <b>Eating Window:</b> 8 hours (e.g., 12 PM to 8 PM).<br>
                <b>High-Calorie, Nutrient-Dense Meals:</b><br>
                <b>Protein:</b> Include sources like lean meats, fish, eggs, and protein shakes.<br>
                <b>Healthy Fats:</b> Add avocados, nuts, and olive oil.<br>
                <b>Complex Carbs:</b> Focus on whole grains, fruits, and vegetables.<br>
                <b>Pre- and Post-Workout Nutrition:</b><br>
                Eat balanced meals or snacks around workouts for muscle support and recovery.<br>
                <b>Hydrate:</b><br>
                Drink plenty of water, and consider black coffee or tea during fasting.<br>
                <b>Note:</b><br>
                Consult with a healthcare provider to ensure the approach aligns with your health and muscle gain goals.
                '];
            }
            if($HighOldFemaleLose) {
                $desc = ['<b>Intermittent Fasting Recommendation for an Older Female Above 35-40 with High BMI Trying to Lose Weight</b><br>
                <b>16/8 Method:</b><br>
                <b>Fasting Window:</b> 16 hours.<br>
                <b>Eating Window:</b> 8 hours (e.g., 12 PM to 8 PM).<br>
                <b>Balanced Diet:</b><br>
                <b>Lean Proteins:</b> Include sources like chicken, fish, and legumes.<br>
                <b>Healthy Fats:</b> Add nuts, seeds, and olive oil.<br>
                <b>Complex Carbs:</b> Focus on whole grains and vegetables.<br>
                <b>Hydrate:</b><br>
                Drink plenty of water, and consider black coffee or tea during fasting.<br>
                <b>Exercise:</b><br>
                Include regular physical activity, such as walking or strength training.<br>
                <b>Note:</b><br>
                Consult with a healthcare provider to ensure the plan is safe and effective for your individual health needs.
                '];
            }
            if($HighOldFemaleGain) {
                $desc = ['<b>Intermittent Fasting Recommendation for an Older Female Above 35-40 with High BMI Trying to Gain Muscle Mass</b><br>
                <b>14/10 Method:</b><br>
                <b>Fasting Window:</b> 14 hours.<br>
                <b>Eating Window:</b> 10 hours (e.g., 10 AM to 8 PM).<br>
                <b>High-Calorie, Nutrient-Dense Meals:</b><br>
                <b>Protein:</b> Include lean meats, fish, eggs, and protein shakes.<br>
                <b>Healthy Fats:</b> Add avocados, nuts, and olive oil.<br>
                <b>Complex Carbs:</b> Focus on whole grains, fruits, and vegetables.<br>
                <b>Pre- and Post-Workout Nutrition:</b><br>
                Ensure balanced meals or snacks around workouts for muscle support and recovery.<br>
                <b>Hydrate:</b><br>
                Drink plenty of water, and consider black coffee or tea during fasting.<br>
                <b>Note:</b><br>
                Consult a healthcare provider to tailor the approach to your needs and ensure safety.
                '];
            }
            // -------
            if($LowOldMaleLose) {
                $desc = ['<b>Intermittent Fasting Recommendation for an Older Male Above 35-40 with Low BMI Trying to Lose Weight</b><br>
                <b>12/12 Method:</b><br>
                <b>Fasting Window:</b> 12 hours.<br>
                <b>Eating Window:</b> 12 hours (e.g., 8 AM to 8 PM).<br>
                <b>Balanced Nutrition:</b><br>
                <b>Nutrient-Dense Foods:</b> Focus on lean proteins, healthy fats, and complex carbs.<br>
                <b>Monitor Intake:</b> Ensure you are not undereating, even with a caloric deficit.<br>
                <b>Hydrate:</b><br>
                Drink water, and consider black coffee or tea during fasting.<br>
                <b>Consult a Healthcare Provider:</b><br>
                Ensure the approach is safe given your low BMI and adjust as needed.<br>
                <b>Note:</b><br>
                Be cautious to avoid further reducing an already low BMI.
                '];
            }
            if($LowOldMaleGain) {
                $desc = ['<b>Intermittent Fasting Recommendation for an Older Male Above 35-40 with Low BMI Trying to Gain Muscle Mass</b><br>
                <b>14/10 Method:</b><br>
                <b>Fasting Window:</b> 14 hours.<br>
                <b>Eating Window:</b> 10 hours (e.g., 10 AM to 8 PM).<br>
                <b>High-Calorie, Nutrient-Dense Meals:</b><br>
                <b>Protein:</b> Include lean meats, fish, eggs, and protein shakes.<br>
                <b>Healthy Fats:</b> Add avocados, nuts, and olive oil.<br>
                <b>Complex Carbs:</b> Focus on whole grains, fruits, and vegetables.<br>
                <b>Pre- and Post-Workout Nutrition:</b><br>
                Ensure balanced meals or snacks around workouts for muscle growth and recovery.<br>
                <b>Hydrate:</b><br>
                Drink plenty of water, and consider black coffee or tea during fasting.<br>
                <b>Note:</b><br>
                Consult a healthcare provider to ensure the approach is appropriate for your low BMI and muscle gain goals.
                '];
            }
            if($LowOldFemaleLose) {
                $desc = ['<b>Intermittent Fasting Recommendation for an Older Female Above 35-40 with Low BMI Trying to Lose Weight</b><br>
                <b>12/12 Method:</b><br>
                <b>Fasting Window:</b> 12 hours.<br>
                <b>Eating Window:</b> 12 hours (e.g., 8 AM to 8 PM).<br>
                <b>Balanced Diet:</b><br>
                <b>Nutrient-Dense Foods:</b> Focus on lean proteins, healthy fats, and complex carbs.<br>
                <b>Monitor Intake:</b> Ensure you are meeting nutritional needs while maintaining a slight caloric deficit.<br>
                <b>Hydrate:</b><br>
                Drink water, and consider black coffee or tea during fasting.<br>
                <b>Consult a Healthcare Provider:</b><br>
                Ensure the approach is safe and appropriate for your low BMI and health status.<br>
                <b>Note:</b><br>
                Be cautious to avoid further reducing your already low BMI.
                '];
            }
            if($LowOldFemaleGain) {
                $desc = ['<b>Daily Intermittent Fasting Recommendation for an Older Female Above 35-40 with Low BMI Trying to Gain Muscle Mass</b><br>
                <b>14/10 Method:</b><br>
                <b>Fasting Window:</b> 14 hours.<br>
                <b>Eating Window:</b> 10 hours (e.g., 10 AM to 8 PM).<br>
                <b>High-Calorie, Nutrient-Dense Meals:</b><br>
                <b>Protein:</b> Include sources like lean meats, fish, eggs, and protein shakes.<br>
                <b>Healthy Fats:</b> Add avocados, nuts, and olive oil.<br>
                <b>Complex Carbs:</b> Focus on whole grains, fruits, and vegetables.<br>
                <b>Pre- and Post-Workout Nutrition:</b><br>
                Ensure balanced meals or snacks around workouts for muscle growth and recovery.<br>
                <b>Hydrate:</b><br>
                Drink plenty of water, and consider black coffee or tea during fasting.<br>
                <b>Note:</b><br>
                Consult with a healthcare provider to ensure the approach supports muscle gain without negatively impacting your low BMI.
                '];
            }
        }
    } elseif($contextFlag == "Cal") {
        if(!empty($dbOutRow['descCal'])) { // use the entry by the user
            $desc = [$dbOutRow['descCal']];
        } else { 
            if($HighYoungMaleLose) {
                $desc = ['Caloric Intake Recommendation for a <b>Young Male Under 35 with High BMI Trying to Lose Weight</b><br>
                <b>Calculate Daily Caloric Needs:</b> Determine your Total Daily Energy Expenditure (TDEE) using an online calculator or formula.<br>
                <b>Create a Caloric Deficit:</b> Aim to consume 500-750 calories less than your TDEE to lose weight at a healthy rate of about 1-1.5 pounds per week.<br>
                <b>Monitor and Adjust:</b> Track your weight loss progress and adjust your calorie intake as needed based on your results and goals.<br>
                <b>Note:</b><br>
                Consulting with a healthcare provider or nutritionist for personalized advice is recommended.'];
            }
            if($HighYoungMaleGain) {
                $desc = ['Caloric Intake Recommendation for a <b>Young Male Under 35 with High BMI Trying to Gain Muscle Mass</b><br>
                <b>Caloric Surplus:</b><br>
                Target: Consume 250-500 calories above your maintenance level to support muscle growth.<br>
                <b>Focus on Nutrient-Dense Foods:</b><br>
                Proteins: Include lean meats, fish, eggs, and dairy.<br>
                Carbohydrates: Eat whole grains, fruits, and vegetables.<br>
                Fats: Add sources like avocados, nuts, and olive oil.<br>
                <b>Monitor and Adjust:</b><br>
                Track progress and adjust intake as needed to continue gaining muscle without excessive fat gain.<br>
                <b>Note:</b><br>
                Consult with a healthcare provider or nutritionist for personalized advice.'];
            }
            if($HighYoungFemaleLose) {
                $desc = ['Caloric Intake Recommendation for a <b>Young Female Under 35 with High BMI Trying to Lose Weight</b><br>
                <b>Caloric Deficit:</b><br>
                Target: Consume 500-750 calories below your maintenance level to promote steady weight loss.<br>
                <b>Focus on Nutrient-Dense Foods:</b><br>
                Proteins: Include lean meats, fish, eggs, and legumes.<br>
                Carbohydrates: Eat whole grains, fruits, and vegetables.<br>
                Fats: Include healthy fats like avocados, nuts, and olive oil in moderation.<br>
                <b>Monitor and Adjust:</b><br>
                Track weight loss progress and adjust caloric intake as needed.<br>
                <b>Note:</b><br>
                Consult with a healthcare provider or nutritionist to ensure the approach is appropriate for your specific needs.'];
            }
            if($HighYoungFemaleGain) {
                $desc = ['Caloric Intake Recommendation for a <b>Young Female Under 35 with High BMI Trying to Gain Muscle Mass</b><br>
                <b>Caloric Surplus:</b><br>
                Target: Consume 250-500 calories above your maintenance level to support muscle growth.<br>
                <b>Focus on Nutrient-Dense Foods:</b><br>
                Proteins: Include lean meats, fish, eggs, and protein shakes.<br>
                Carbohydrates: Eat whole grains, fruits, and vegetables.<br>
                Fats: Add sources like avocados, nuts, and olive oil.<br>
                <b>Monitor and Adjust:</b><br>
                Track muscle gain and adjust caloric intake as needed to continue building muscle without excessive fat gain.<br>
                <b>Note:</b><br>
                Consult with a healthcare provider or nutritionist for personalized guidance.'];
            }
            // -------
            if($LowYoungMaleLose) {
                $desc = ['Caloric Intake Recommendation for a <b>Young Male Under 35 with Low BMI Trying to Lose Weight</b><br>
                <b>Caloric Deficit:</b><br>
                Target: Consume 250-500 calories below your maintenance level to promote weight loss without compromising overall health.<br>
                <b>Focus on Nutrient-Dense Foods:</b><br>
                Proteins: Include lean meats, fish, eggs, and legumes.<br>
                Carbohydrates: Eat whole grains, fruits, and vegetables.<br>
                Fats: Include healthy fats like avocados, nuts, and olive oil in moderation.<br>
                <b>Monitor and Adjust:</b><br>
                Track weight loss progress and adjust caloric intake as needed.<br>
                <b>Note:</b><br>
                Consult with a healthcare provider to ensure the approach is appropriate given your low BMI.'];
            }
            if($LowYoungMaleGain) {
                $desc = ['Caloric Intake Recommendation for a <b>Young Male Under 35 with Low BMI Trying to Gain Muscle Mass</b><br>
                <b>Caloric Surplus:</b><br>
                Target: Consume 250-500 calories above your maintenance level to support muscle growth.<br>
                <b>Focus on Nutrient-Dense Foods:</b><br>
                Proteins: Include lean meats, fish, eggs, and protein shakes.<br>
                Carbohydrates: Eat whole grains, fruits, and vegetables.<br>
                Fats: Add sources like avocados, nuts, and olive oil.<br>
                <b>Monitor and Adjust:</b><br>
                Track muscle gain progress and adjust caloric intake as needed to continue building muscle without excessive fat gain.<br>
                <b>Note:</b><br>
                Consult with a healthcare provider or nutritionist for personalized guidance.'];
            }
            if($LowYoungFemaleLose) {
                $desc = ['Caloric Intake Recommendation for a <b>Young Female Under 35 with Low BMI Trying to Lose Weight</b><br>
                <b>Moderate Caloric Deficit:</b><br>
                Target: Consume 250-500 calories below your maintenance level to promote weight loss while ensuring you get enough nutrients.<br>
                <b>Focus on Nutrient-Dense Foods:</b><br>
                Proteins: Include lean meats, fish, eggs, and legumes.<br>
                Carbohydrates: Eat whole grains, fruits, and vegetables.<br>
                Fats: Include healthy fats like avocados, nuts, and olive oil in moderation.<br>
                <b>Monitor and Adjust:</b><br>
                Track weight loss progress and adjust caloric intake as needed.<br>
                <b>Note:</b><br>
                Consult with a healthcare provider to ensure the approach is safe and appropriate given your low BMI.'];
            }
            if($LowYoungFemaleGain) {
                $desc = ['Caloric Intake Recommendation for a <b>Young Female Under 35 with Low BMI Trying to Gain Muscle Mass</b><br>
                <b>Caloric Surplus:</b><br>
                Target: Consume 250-500 calories above your maintenance level to support muscle growth.<br>
                <b>Focus on Nutrient-Dense Foods:</b><br>
                Proteins: Include lean meats, fish, eggs, and protein shakes.<br>
                Carbohydrates: Eat whole grains, fruits, and vegetables.<br>
                Fats: Add sources like avocados, nuts, and olive oil.<br>
                <b>Monitor and Adjust:</b><br>
                Track muscle gain progress and adjust caloric intake as needed.<br>
                <b>Note:</b><br>
                Consult with a healthcare provider or nutritionist for personalized guidance.'];
            }
            // -------
            if($HighOldMaleLose) {
                $desc = ['Caloric Intake Recommendation for an <b>Older Male Above 35-40 with High BMI Trying to Lose Weight</b><br>
                <b>Caloric Deficit:</b><br>
                Target: Consume 500-750 calories below your maintenance level to promote steady weight loss.<br>
                <b>Focus on Nutrient-Dense Foods:</b><br>
                Proteins: Include lean meats, fish, eggs, and legumes.<br>
                Carbohydrates: Eat whole grains, fruits, and vegetables.<br>
                Fats: Include healthy fats like avocados, nuts, and olive oil in moderation.<br>
                <b>Monitor and Adjust:</b><br>
                Track weight loss progress and adjust caloric intake as needed.<br>
                <b>Note:</b><br>
                Consult with a healthcare provider or nutritionist to ensure the approach is appropriate for your specific needs and health status.'];
            }
            if($HighOldMaleGain) {
                $desc = ['Caloric Intake Recommendation for an <b>Older Male Above 35-40 with High BMI Trying to Gain Muscle Mass</b><br>
                <b>Caloric Surplus:</b><br>
                Target: Consume 250-500 calories above your maintenance level to support muscle growth.<br>
                <b>Focus on Nutrient-Dense Foods:</b><br>
                Proteins: Include lean meats, fish, eggs, and protein shakes.<br>
                Carbohydrates: Eat whole grains, fruits, and vegetables.<br>
                Fats: Add sources like avocados, nuts, and olive oil.<br>
                <b>Monitor and Adjust:</b><br>
                Track muscle gain progress and adjust caloric intake as needed to ensure healthy muscle development without excessive fat gain.<br>
                <b>Note:</b><br>
                Consult with a healthcare provider or nutritionist for personalized guidance.'];
            }
            if($HighOldFemaleLose) {
                $desc = ['Caloric Intake Recommendation for an <b>Older Female Above 35-40 with High BMI Trying to Lose Weight</b><br>
                <b>Caloric Deficit:</b><br>
                Target: Consume 500-750 calories below your maintenance level to promote steady weight loss.<br>
                <b>Focus on Nutrient-Dense Foods:</b><br>
                Proteins: Include lean meats, fish, eggs, and legumes.<br>
                Carbohydrates: Eat whole grains, fruits, and vegetables.<br>
                Fats: Include healthy fats like avocados, nuts, and olive oil in moderation.<br>
                <b>Monitor and Adjust:</b><br>
                Track weight loss progress and adjust caloric intake as needed.<br>
                <b>Note:</b><br>
                Consult with a healthcare provider or nutritionist to ensure the approach is appropriate for your specific needs and health status.'];
            }
            if($HighOldFemaleGain) {
                $desc = ['Caloric Intake Recommendation for an <b>Older Female Above 35-40 with High BMI Trying to Gain Muscle Mass</b><br>
                <b>Caloric Surplus:</b><br>
                Target: Consume 250-500 calories above your maintenance level to support muscle growth.<br>
                <b>Focus on Nutrient-Dense Foods:</b><br>
                Proteins: Include lean meats, fish, eggs, and protein shakes.<br>
                Carbohydrates: Eat whole grains, fruits, and vegetables.<br>
                Fats: Add sources like avocados, nuts, and olive oil.<br>
                <b>Monitor and Adjust:</b><br>
                Track muscle gain progress and adjust caloric intake as needed to ensure healthy muscle development without excessive fat gain.<br>
                <b>Note:</b><br>
                Consult with a healthcare provider or nutritionist for personalized guidance.'];
            }
            // -------
            if($LowOldMaleLose) {
                $desc = ['Caloric Intake Recommendation for an <b>Older Male Above 35-40 with Low BMI Trying to Lose Weight</b><br>
                <b>Moderate Caloric Deficit:</b><br>
                Target: Consume 250-500 calories below your maintenance level to promote weight loss while ensuring you get enough nutrients.<br>
                <b>Focus on Nutrient-Dense Foods:</b><br>
                Proteins: Include lean meats, fish, eggs, and legumes.<br>
                Carbohydrates: Eat whole grains, fruits, and vegetables.<br>
                Fats: Include healthy fats like avocados, nuts, and olive oil in moderation.<br>
                <b>Monitor and Adjust:</b><br>
                Track weight loss progress and adjust caloric intake as needed.<br>
                <b>Note:</b><br>
                Consult with a healthcare provider to ensure the approach is safe and appropriate given your low BMI.'];
            }
            if($LowOldMaleGain) {
                $desc = ['Caloric Intake Recommendation for an <b>Older Male Above 35-40 with Low BMI Trying to Gain Muscle Mass</b><br>
                <b>Caloric Surplus:</b><br>
                Target: Consume 250-500 calories above your maintenance level to support muscle growth.<br>
                <b>Focus on Nutrient-Dense Foods:</b><br>
                Proteins: Include lean meats, fish, eggs, and protein shakes.<br>
                Carbohydrates: Eat whole grains, fruits, and vegetables.<br>
                Fats: Add sources like avocados, nuts, and olive oil.<br>
                <b>Monitor and Adjust:</b><br>
                Track muscle gain progress and adjust caloric intake as needed.<br>
                <b>Note:</b><br>
                Consult with a healthcare provider or nutritionist for personalized advice to ensure safe and effective muscle gain.'];
            }
            if($LowOldFemaleLose) {
                $desc = ['Caloric Intake Recommendation for an <b>Older Female Above 35-40 with Low BMI Trying to Lose Weight</b><br>
                <b>Moderate Caloric Deficit:</b><br>
                Target: Consume 250-500 calories below your maintenance level to promote weight loss without compromising health.<br>
                <b>Focus on Nutrient-Dense Foods:</b><br>
                Proteins: Include lean meats, fish, eggs, and legumes.<br>
                Carbohydrates: Eat whole grains, fruits, and vegetables.<br>
                Fats: Include healthy fats like avocados, nuts, and olive oil in moderation.<br>
                <b>Monitor and Adjust:</b><br>
                Track weight loss progress and adjust caloric intake as needed.<br>
                <b>Note:</b><br>
                Consult with a healthcare provider to ensure the approach is safe given your low BMI.'];
            }
            if($LowOldFemaleGain) {
                $desc = ['Caloric Intake Recommendation for an <b>Older Female Above 35-40 with Low BMI Trying to Gain Muscle Mass</b><br>
                <b>Caloric Surplus:</b><br>
                Target: Consume 250-500 calories above your maintenance level to support muscle growth.<br>
                <b>Focus on Nutrient-Dense Foods:</b><br>
                Proteins: Include lean meats, fish, eggs, and protein shakes.<br>
                Carbohydrates: Eat whole grains, fruits, and vegetables.<br>
                Fats: Add sources like avocados, nuts, and olive oil.<br>
                <b>Monitor and Adjust:</b><br>
                Track muscle gain progress and adjust caloric intake as needed.<br>
                <b>Note:</b><br>
                Consult with a healthcare provider or nutritionist for personalized advice to ensure safe and effective muscle gain.'];
            }
        }
    } elseif($contextFlag == "Macro") {
        if(!empty($dbOutRow['descMacro'])) { // use the entry by the user
            $desc = [$dbOutRow['descMacro']];
        } else { 
            if($HighYoungMaleLose) {
                $desc = ['Macronutrient Recommendations for a <b>Young Male Under 35 with High BMI Trying to Lose Weight</b><br>
                <b>Protein:</b> Aim for 25-30% of total daily calories from protein to support muscle maintenance and satiety. Sources include lean meats, fish, eggs, and legumes.<br>
                <b>Fats:</b> Target 20-35% of daily calories from healthy fats. Include avocados, nuts, seeds, and olive oil.<br>
                <b>Carbohydrates:</b> Make up the remaining 45-55% of daily calories with complex carbohydrates such as whole grains, vegetables, and fruits.<br>
                <b>Note:</b> Adjust these ratios based on personal progress and dietary preferences. Consulting with a nutritionist can provide tailored guidance.'];
            }
            if($HighYoungMaleGain) {
                $desc = ['Macronutrient Percentage Recommendations Including Fiber for a <b>Young Male Under 35 with High BMI Trying to Gain Muscle Mass</b><br>
                <b>Proteins:</b> 25-30% of total daily calories<br>
                <b>Carbohydrates:</b> 45-55% of total daily calories<br>
                <b>Fiber:</b> Aim for 25-38 grams per day within the carbohydrate intake<br>
                <b>Fats:</b> 20-30% of total daily calories<br>
                <b>Note:</b> Adjust based on individual progress and specific needs.'];
            }
            if($HighYoungFemaleLose) {
                $desc = ['Macronutrient Percentage Recommendations Including Fiber for a <b>Young Female Under 35 with High BMI Trying to Lose Weight</b><br>
                <b>Proteins:</b> 25-30% of total daily calories<br>
                <b>Carbohydrates:</b> 40-50% of total daily calories<br>
                <b>Fiber:</b> Aim for 21-25 grams per day within the carbohydrate intake<br>
                <b>Fats:</b> 25-30% of total daily calories<br>
                <b>Note:</b> Adjust based on individual progress and specific needs.'];
            }
            if($HighYoungFemaleGain) {
                $desc = ['Macronutrient Percentage Recommendations Including Fiber for a <b>Young Female Under 35 with High BMI Trying to Gain Muscle Mass</b><br>
                <b>Proteins:</b> 25-30% of total daily calories<br>
                <b>Carbohydrates:</b> 45-55% of total daily calories<br>
                <b>Fiber:</b> Aim for 21-25 grams per day within the carbohydrate intake<br>
                <b>Fats:</b> 20-30% of total daily calories<br>
                <b>Note:</b> Adjust based on progress and individual needs.'];
            }
            // -------
            if($LowYoungMaleLose) {
                $desc = ['Macronutrient Percentage Recommendations Including Fiber for a <b>Young Male Under 35 with Low BMI Trying to Lose Weight</b><br>
                <b>Proteins:</b> 30-35% of total daily calories<br>
                <b>Carbohydrates:</b> 35-45% of total daily calories<br>
                <b>Fiber:</b> Aim for 30-38 grams per day within the carbohydrate intake<br>
                <b>Fats:</b> 25-30% of total daily calories<br>
                <b>Note:</b> Adjust based on progress and individual needs.'];
            }
            if($LowYoungMaleGain) {
                $desc = ['Macronutrient Percentage Recommendations Including Fiber for a <b>Young Male Under 35 with Low BMI Trying to Gain Weight</b><br>
                <b>Proteins:</b> 25-30% of total daily calories<br>
                <b>Carbohydrates:</b> 50-60% of total daily calories<br>
                <b>Fiber:</b> Aim for 30-38 grams per day within the carbohydrate intake<br>
                <b>Fats:</b> 20-30% of total daily calories<br>
                <b>Note:</b> Adjust based on progress and individual needs.'];
            }
            if($LowYoungFemaleLose) {
                $desc = ['Macronutrient Percentage Recommendations Including Fiber for a <b>Young Female Under 35 with Low BMI Trying to Lose Weight</b><br>
                <b>Proteins:</b> 30-35% of total daily calories<br>
                <b>Carbohydrates:</b> 40-50% of total daily calories<br>
                <b>Fiber:</b> Aim for 21-25 grams per day within the carbohydrate intake<br>
                <b>Fats:</b> 20-30% of total daily calories<br>
                <b>Note:</b> Adjust based on progress and individual needs.'];
            }
            if($LowYoungFemaleGain) {
                $desc = ['For a <b>young female under 35 with a low BMI aiming to gain muscle mass</b>, here are some concise macronutrient percentage recommendations, including fiber:<br>
                <b>Protein:</b> 25-30% of total daily calories<br>
                <b>Carbohydrates:</b> 45-50% of total daily calories (ensure these include complex carbs and fiber)<br>
                <b>Fats:</b> 20-25% of total daily calories<br>
                <b>Fiber:</b> Aim for 25-30 grams per day'];
            }
            // -------
            if($HighOldMaleLose) {
                $desc = ['For an <b>older male (over 35-40) with a high BMI aiming to lose weight</b>, here are some concise macronutrient percentage recommendations, including fiber:<br>
                <b>Protein:</b> 25-30% of total daily calories<br>
                <b>Carbohydrates:</b> 35-40% of total daily calories (focus on high-fiber, low-glycemic carbs)<br>
                <b>Fats:</b> 30-35% of total daily calories (prioritize healthy fats)<br>
                <b>Fiber:</b> Aim for 30-35 grams per day'];
            }
            if($HighOldMaleGain) {
                $desc = ['For an <b>older male (over 35-40) with a high BMI aiming to gain muscle mass</b>, here are some concise macronutrient percentage recommendations, including fiber:<br>
                <b>Protein:</b> 25-30% of total daily calories<br>
                <b>Carbohydrates:</b> 40-45% of total daily calories (focus on complex carbs with adequate fiber)<br>
                <b>Fats:</b> 25-30% of total daily calories (include healthy fats)<br>
                <b>Fiber:</b> Aim for 30-35 grams per day'];
            }
            if($HighOldFemaleLose) {
                $desc = ['For an <b>older female (over 35-40) with a high BMI aiming to lose weight</b>, here are some concise macronutrient percentage recommendations, including fiber:<br>
                <b>Protein:</b> 25-30% of total daily calories<br>
                <b>Carbohydrates:</b> 35-40% of total daily calories (focus on high-fiber, low-glycemic carbs)<br>
                <b>Fats:</b> 30-35% of total daily calories (prioritize healthy fats)<br>
                <b>Fiber:</b> Aim for 25-30 grams per day'];
            }
            if($HighOldFemaleGain) {
                $desc = ['For an <b>older female (over 35-40) with a high BMI aiming to gain muscle mass</b>, here are some concise macronutrient percentage recommendations, including fiber:<br>
                <b>Protein:</b> 25-30% of total daily calories<br>
                <b>Carbohydrates:</b> 40-45% of total daily calories (focus on complex carbs with adequate fiber)<br>
                <b>Fats:</b> 25-30% of total daily calories (include healthy fats)<br>
                <b>Fiber:</b> Aim for 25-30 grams per day'];
            }
            // -------
            if($LowOldMaleLose) {
                $desc = ['For an <b>older male (over 35-40) with a low BMI aiming to lose weight</b>, here are some concise macronutrient percentage recommendations, including fiber:<br>
                <b>Protein:</b> 25-30% of total daily calories<br>
                <b>Carbohydrates:</b> 40-45% of total daily calories (focus on high-fiber, low-glycemic carbs)<br>
                <b>Fats:</b> 25-30% of total daily calories (prioritize healthy fats)<br>
                <b>Fiber:</b> Aim for 25-30 grams per day'];
            }
            if($LowOldMaleGain) {
                $desc = ['For an <b>older male (over 35-40) with a low BMI aiming to gain muscle mass</b>, here are some concise macronutrient percentage recommendations, including fiber:<br>
                <b>Protein:</b> 25-30% of total daily calories<br>
                <b>Carbohydrates:</b> 45-50% of total daily calories (focus on complex carbs with adequate fiber)<br>
                <b>Fats:</b> 20-25% of total daily calories (include healthy fats)<br>
                <b>Fiber:</b> Aim for 25-30 grams per day'];
            }
            if($LowOldFemaleLose) {
                $desc = ['For an <b>older female (over 35-40) with a low BMI aiming to lose weight</b>, here are some concise macronutrient percentage recommendations, including fiber:<br>
                <b>Protein:</b> 25-30% of total daily calories<br>
                <b>Carbohydrates:</b> 35-40% of total daily calories (focus on high-fiber, low-glycemic carbs)<br>
                <b>Fats:</b> 30-35% of total daily calories (prioritize healthy fats)<br>
                <b>Fiber:</b> Aim for 25-30 grams per day'];
            }
            if($LowOldFemaleGain) {
                $desc = ['For an <b>older female (over 35-40) with a low BMI aiming to gain muscle mass</b>, here are some concise macronutrient percentage recommendations, including fiber:<br>
                <b>Protein:</b> 25-30% of total daily calories<br>
                <b>Carbohydrates:</b> 45-50% of total daily calories (focus on complex carbs with adequate fiber)<br>
                <b>Fats:</b> 20-25% of total daily calories (include healthy fats)<br>
                <b>Fiber:</b> Aim for 25-30 grams per day'];
            }
        }
    } elseif($contextFlag == "MicroVit") {
        if(!empty($dbOutRow['descMicroVit'])) { // use the entry by the user
            $desc = [$dbOutRow['descMicroVit']];
        } else { 
            if($HighYoungMaleLose) {
                $desc = ['For a <b>young male under 35 with a high BMI trying to lose weight</b>, here are some concise micronutrient vitamin recommendations:<br>
                <b>Vitamin D:</b> Supports bone health and metabolism. Sources include sunlight exposure and fortified foods or supplements.<br>
                <b>Vitamin C:</b> Important for immune function and tissue repair. Found in citrus fruits, bell peppers, and leafy greens.<br>
                <b>B Vitamins:</b> Aid in energy metabolism. Sources include whole grains, lean meats, eggs, and legumes.<br>
                <b>Vitamin E:</b> Acts as an antioxidant. Obtain from nuts, seeds, and vegetable oils.<br>
                <b>Note:</b> A balanced diet should cover these vitamins, but consulting a healthcare provider can help ensure you meet your individual needs.'];
            }
            if($HighYoungMaleGain) {
                $desc = ['For a <b>young male under 35 with a high BMI aiming to gain muscle mass</b>, here are some concise micronutrient vitamin recommendations:<br>
                <b>Vitamin D:</b> 600-800 IU per day (supports muscle function and bone health)<br>
                <b>Vitamin C:</b> 90 mg per day (aids in collagen formation and tissue repair)<br>
                <b>Vitamin E:</b> 15 mg per day (acts as an antioxidant)<br>
                <b>Vitamin B12:</b> 2.4 mcg per day (essential for energy metabolism and muscle growth)<br>
                <b>Vitamin B6:</b> 1.3-1.7 mg per day (involved in protein metabolism)<br>
                <b>Folate (Vitamin B9):</b> 400 mcg per day (important for cell growth and repair)<br>
                Ensure these vitamins are included in a balanced diet or consider supplements if necessary.'];
            }
            if($HighYoungFemaleLose) {
                $desc = ['For a <b>young female under 35 with a high BMI aiming to lose weight</b>, here are some concise micronutrient vitamin recommendations:<br>
                <b>Vitamin D:</b> 600-800 IU per day (supports metabolism and bone health)<br>
                <b>Vitamin C:</b> 75 mg per day (aids in tissue repair and immune function)<br>
                <b>Vitamin E:</b> 15 mg per day (acts as an antioxidant)<br>
                <b>Vitamin B12:</b> 2.4 mcg per day (important for energy metabolism)<br>
                <b>Vitamin B6:</b> 1.3-1.5 mg per day (supports protein metabolism and energy production)<br>
                <b>Folate (Vitamin B9):</b> 400 mcg per day (supports cell function and metabolism)<br>
                These vitamins can help support overall health and metabolic function during weight loss.'];
            }
            if($HighYoungFemaleGain) {
                $desc = ['For a <b>young female under 35 with a high BMI aiming to gain muscle mass</b>, here are some concise micronutrient vitamin recommendations:<br>
                <b>Vitamin D:</b> 600-800 IU per day (supports muscle function and bone health)<br>
                <b>Vitamin C:</b> 75 mg per day (aids in collagen formation and recovery)<br>
                <b>Vitamin E:</b> 15 mg per day (acts as an antioxidant)<br>
                <b>Vitamin B12:</b> 2.4 mcg per day (important for energy and muscle growth)<br>
                <b>Vitamin B6:</b> 1.3-1.5 mg per day (helps with protein metabolism)<br>
                <b>Folate (Vitamin B9):</b> 400 mcg per day (supports cell growth and repair)<br>
                These vitamins can help with muscle development and overall health.'];
            }
            // -------
            if($LowYoungMaleLose) {
                $desc = ['<b>For a young male under 35 with a low BMI aiming to lose weight, here are some concise micronutrient vitamin recommendations:</b><br>
                <b>Vitamin D:</b> 600-800 IU per day (supports bone health and metabolism)<br>
                <b>Vitamin C:</b> 90 mg per day (aids in immune function and tissue repair)<br>
                <b>Vitamin E:</b> 15 mg per day (acts as an antioxidant)<br>
                <b>Vitamin B12:</b> 2.4 mcg per day (important for energy levels)<br>
                <b>Vitamin B6:</b> 1.3-1.7 mg per day (supports metabolism and energy production)<br>
                <b>Folate (Vitamin B9):</b> 400 mcg per day (important for cell function and repair)<br>
                <b>These vitamins support overall health and metabolic function during weight loss.</b>'];
            }
            if($LowYoungMaleGain) {
                $desc = ['<b>For a young male under 35 with a low BMI aiming to gain muscle mass, here are some concise micronutrient vitamin recommendations:</b><br>
                <b>Vitamin D:</b> 600-800 IU per day (supports muscle function and bone health)<br>
                <b>Vitamin C:</b> 90 mg per day (aids in muscle repair and immune function)<br>
                <b>Vitamin E:</b> 15 mg per day (acts as an antioxidant)<br>
                <b>Vitamin B12:</b> 2.4 mcg per day (important for energy and muscle growth)<br>
                <b>Vitamin B6:</b> 1.3-1.7 mg per day (supports protein metabolism)<br>
                <b>Folate (Vitamin B9):</b> 400 mcg per day (important for cell growth and repair)<br>
                <b>These vitamins support muscle development and overall health.</b>'];
            }
            if($LowYoungFemaleLose) {
                $desc = ['<b>For a young female under 35 with a low BMI aiming to lose weight, here are some concise micronutrient vitamin recommendations:</b><br>
                <b>Vitamin D:</b> 600-800 IU per day (supports bone health and metabolism)<br>
                <b>Vitamin C:</b> 75 mg per day (aids in immune function and skin health)<br>
                <b>Vitamin E:</b> 15 mg per day (acts as an antioxidant)<br>
                <b>Vitamin B12:</b> 2.4 mcg per day (important for energy and red blood cell formation)<br>
                <b>Vitamin B6:</b> 1.3-1.5 mg per day (supports metabolism and energy production)<br>
                <b>Folate (Vitamin B9):</b> 400 mcg per day (supports cell function and repair)<br>
                <b>These vitamins help with overall health and metabolic function during weight loss.</b>'];
            }
            if($LowYoungFemaleGain) {
                $desc = ['<b>For a young female under 35 with a low BMI aiming to gain muscle mass, here are some concise micronutrient vitamin recommendations:</b><br>
                <b>Vitamin D:</b> 600-800 IU per day (supports muscle function and bone health)<br>
                <b>Vitamin C:</b> 75 mg per day (aids in muscle repair and immune function)<br>
                <b>Vitamin E:</b> 15 mg per day (acts as an antioxidant)<br>
                <b>Vitamin B12:</b> 2.4 mcg per day (important for energy and muscle growth)<br>
                <b>Vitamin B6:</b> 1.3-1.5 mg per day (supports protein metabolism)<br>
                <b>Folate (Vitamin B9):</b> 400 mcg per day (supports cell growth and repair)<br>
                <b>These vitamins help with muscle development and overall health.</b>'];
            }
            // ------
            if($HighOldMaleLose) {
                $desc = ['<b>For an older male (above 35-40) with a high BMI aiming to lose weight, here are some concise micronutrient vitamin recommendations:</b><br>
                <b>Vitamin D:</b> 600-800 IU per day (supports bone health and metabolism)<br>
                <b>Vitamin C:</b> 90 mg per day (aids in immune function and skin health)<br>
                <b>Vitamin E:</b> 15 mg per day (acts as an antioxidant)<br>
                <b>Vitamin B12:</b> 2.4 mcg per day (important for energy and red blood cell formation)<br>
                <b>Vitamin B6:</b> 1.3-1.7 mg per day (supports metabolism and energy production)<br>
                <b>Folate (Vitamin B9):</b> 400 mcg per day (supports cell function and repair)<br>
                <b>These vitamins support overall health and metabolic function during weight loss.</b>'];
            }
            if($HighOldMaleGain) {
                $desc = ['<b>For an older male (above 35-40) with a high BMI aiming to gain muscle mass, here are some concise micronutrient vitamin recommendations:</b><br>
                <b>Vitamin D:</b> 600-800 IU per day (supports muscle function and bone health)<br>
                <b>Vitamin C:</b> 90 mg per day (aids in muscle repair and immune function)<br>
                <b>Vitamin E:</b> 15 mg per day (acts as an antioxidant)<br>
                <b>Vitamin B12:</b> 2.4 mcg per day (important for energy and muscle growth)<br>
                <b>Vitamin B6:</b> 1.3-1.7 mg per day (supports protein metabolism)<br>
                <b>Folate (Vitamin B9):</b> 400 mcg per day (supports cell growth and repair)<br>
                <b>These vitamins can help with muscle development and overall health.</b>'];
            }
            if($HighOldFemaleLose) {
                $desc = ['<b>For an older female (above 35-40) with a high BMI aiming to lose weight, here are some concise micronutrient vitamin recommendations:</b><br>
                <b>Vitamin D:</b> 600-800 IU per day (supports bone health and metabolism)<br>
                <b>Vitamin C:</b> 75 mg per day (aids in immune function and skin health)<br>
                <b>Vitamin E:</b> 15 mg per day (acts as an antioxidant)<br>
                <b>Vitamin B12:</b> 2.4 mcg per day (important for energy and red blood cell formation)<br>
                <b>Vitamin B6:</b> 1.3-1.5 mg per day (supports metabolism and energy production)<br>
                <b>Folate (Vitamin B9):</b> 400 mcg per day (supports cell function and repair)<br>
                <b>These vitamins support overall health and metabolic function during weight loss.</b>'];
            }
            if($HighOldFemaleGain) {
                $desc = ['<b>For an older female (above 35-40) with a high BMI aiming to gain muscle mass, here are some concise micronutrient vitamin recommendations:</b><br>
                <b>Vitamin D:</b> 600-800 IU per day (supports muscle function and bone health)<br>
                <b>Vitamin C:</b> 75 mg per day (aids in muscle repair and immune function)<br>
                <b>Vitamin E:</b> 15 mg per day (acts as an antioxidant)<br>
                <b>Vitamin B12:</b> 2.4 mcg per day (important for energy and muscle growth)<br>
                <b>Vitamin B6:</b> 1.3-1.5 mg per day (supports protein metabolism)<br>
                <b>Folate (Vitamin B9):</b> 400 mcg per day (supports cell growth and repair)<br>
                <b>These vitamins help with muscle development and overall health.</b>'];
            }
            // -------
            if($LowOldMaleLose) {
                $desc = ['<b>For an older male (above 35-40) with a low BMI aiming to lose weight, here are some concise micronutrient vitamin recommendations:</b><br>
                <b>Vitamin D:</b> 600-800 IU per day (supports bone health and metabolism)<br>
                <b>Vitamin C:</b> 90 mg per day (aids in immune function and tissue repair)<br>
                <b>Vitamin E:</b> 15 mg per day (acts as an antioxidant)<br>
                <b>Vitamin B12:</b> 2.4 mcg per day (important for energy levels)<br>
                <b>Vitamin B6:</b> 1.3-1.7 mg per day (supports metabolism and energy production)<br>
                <b>Folate (Vitamin B9):</b> 400 mcg per day (supports cell function and repair)<br>
                <b>These vitamins support overall health and metabolic function during weight loss.</b>'];
            }
            if($LowOldMaleGain) {
                $desc = ['<b>For an older male (above 35-40) with a low BMI aiming to gain muscle mass, here are some concise micronutrient vitamin recommendations:</b><br>
                <b>Vitamin D:</b> 600-800 IU per day (supports muscle function and bone health)<br>
                <b>Vitamin C:</b> 90 mg per day (aids in muscle repair and immune function)<br>
                <b>Vitamin E:</b> 15 mg per day (acts as an antioxidant)<br>
                <b>Vitamin B12:</b> 2.4 mcg per day (important for energy and muscle growth)<br>
                <b>Vitamin B6:</b> 1.3-1.7 mg per day (supports protein metabolism)<br>
                <b>Folate (Vitamin B9):</b> 400 mcg per day (supports cell growth and repair)<br>
                <b>These vitamins can help with muscle development and overall health.</b>'];
            }
            if($LowOldFemaleLose) {
                $desc = ['<b>For an older female (above 35-40) with a low BMI aiming to lose weight, here are some concise micronutrient vitamin recommendations:</b><br>
                <b>Vitamin D:</b> 600-800 IU per day (supports bone health and metabolism)<br>
                <b>Vitamin C:</b> 75 mg per day (aids in immune function and tissue repair)<br>
                <b>Vitamin E:</b> 15 mg per day (acts as an antioxidant)<br>
                <b>Vitamin B12:</b> 2.4 mcg per day (important for energy levels and red blood cell formation)<br>
                <b>Vitamin B6:</b> 1.3-1.5 mg per day (supports metabolism and energy production)<br>
                <b>Folate (Vitamin B9):</b> 400 mcg per day (supports cell function and repair)<br>
                <b>These vitamins support overall health and metabolic function during weight loss.</b>'];
            }
            if($LowOldFemaleGain) {
                $desc = ['<b>For an older female (above 35-40) with a low BMI aiming to gain muscle mass, here are some concise micronutrient vitamin recommendations:</b><br>
                <b>Vitamin D:</b> 600-800 IU per day (supports muscle function and bone health)<br>
                <b>Vitamin C:</b> 75 mg per day (aids in muscle repair and immune function)<br>
                <b>Vitamin E:</b> 15 mg per day (acts as an antioxidant)<br>
                <b>Vitamin B12:</b> 2.4 mcg per day (important for energy and muscle growth)<br>
                <b>Vitamin B6:</b> 1.3-1.5 mg per day (supports protein metabolism)<br>
                <b>Folate (Vitamin B9):</b> 400 mcg per day (supports cell growth and repair)<br>
                <b>These vitamins help with muscle development and overall health.</b>'];
            }
        }
    } elseif($contextFlag == "MicroTrace") {
        if(!empty($dbOutRow['descMicroTrace'])) { // use the entry by the user
            $desc = [$dbOutRow['descMicroTrace']];
        } else { 
            if($HighYoungMaleLose) {
                $desc = ['<b>Micronutrient Trace Minerals Recommendation for a Young Male</b> <br>
                <b>Under 35 with High BMI Trying to Lose Weight</b> <br>
                <b>Iron:</b> Essential for energy levels and metabolism. Sources include <br>
                lean meats, beans, and fortified cereals. <br>
                <b>Zinc:</b> Important for immune function and metabolism. Found in meat, <br>
                shellfish, and nuts. <br>
                <b>Magnesium:</b> Supports muscle function and energy production. Obtain <br>
                from nuts, seeds, whole grains, and leafy greens. <br>
                <b>Selenium:</b> Contributes to antioxidant defense. Sources include nuts, <br>
                seafood, and whole grains. <br>
                <b>Note:</b> <br>
                Ensure a balanced diet to cover these trace minerals, and consider <br>
                consulting a healthcare provider for personalized advice.'];
            }
            if($HighYoungMaleGain) {
                $desc = ['<b>For a young male under 35 with a high BMI aiming to gain</b> <br>
                <b>muscle mass, here are some concise trace mineral recommendations:</b> <br>
                <b>Iron:</b> 8-11 mg per day (supports oxygen transport and energy) <br>
                <b>Zinc:</b> 11 mg per day (important for muscle growth and immune function) <br>
                <b>Magnesium:</b> 400-420 mg per day (supports muscle function and recovery) <br>
                <b>Copper:</b> 900 mcg per day (important for energy production and connective tissue) <br>
                <b>Selenium:</b> 55 mcg per day (acts as an antioxidant and supports metabolism) <br>
                These trace minerals are essential for muscle growth and overall health.'];
            }
            if($HighYoungFemaleLose) {
                $desc = ['<b>For a young female under 35 with a high BMI aiming to lose</b> <br>
                <b>weight, here are some concise trace mineral recommendations:</b> <br>
                <b>Iron:</b> 18 mg per day (supports oxygen transport and energy levels) <br>
                <b>Zinc:</b> 8 mg per day (important for metabolism and immune function) <br>
                <b>Magnesium:</b> 310-320 mg per day (supports metabolism and muscle function) <br>
                <b>Copper:</b> 900 mcg per day (important for energy production and connective tissue) <br>
                <b>Selenium:</b> 55 mcg per day (acts as an antioxidant and supports metabolism) <br>
                These trace minerals are essential for metabolic health and overall <br>
                well-being during weight loss.
                '];
            }
            if($HighYoungFemaleGain) {
                $desc = ['<b>For a young female under 35 with a high BMI aiming to gain</b> <br>
                <b>muscle mass, here are some concise trace mineral recommendations:</b> <br>
                <b>Iron:</b> 18 mg per day (supports oxygen transport and energy) <br>
                <b>Zinc:</b> 8 mg per day (important for muscle growth and immune function) <br>
                <b>Magnesium:</b> 310-320 mg per day (supports muscle function and recovery) <br>
                <b>Copper:</b> 900 mcg per day (important for energy production and connective tissue) <br>
                <b>Selenium:</b> 55 mcg per day (acts as an antioxidant and supports metabolism) <br>
                These trace minerals support muscle development and overall health.
                '];
            }
            // -------
            if($LowYoungMaleLose) {
                $desc = ['<b>For a young male under 35 with a low BMI aiming to lose</b> <br>
                <b>weight, here are some concise trace mineral recommendations:</b> <br>
                <b>Iron:</b> 8-11 mg per day (supports oxygen transport and energy) <br>
                <b>Zinc:</b> 11 mg per day (important for metabolism and immune function) <br>
                <b>Magnesium:</b> 400-420 mg per day (supports muscle function and metabolism) <br>
                <b>Copper:</b> 900 mcg per day (important for energy production and connective tissue) <br>
                <b>Selenium:</b> 55 mcg per day (acts as an antioxidant and supports metabolism) <br>
                These trace minerals help maintain overall health and metabolic function <br>
                during weight loss.
                '];
            }
            if($LowYoungMaleGain) {
                $desc = ['<b>For a young male under 35 with a low BMI aiming to gain</b> <br>
                <b>muscle mass, here are some concise trace mineral recommendations:</b> <br>
                <b>Iron:</b> 8-11 mg per day (supports oxygen transport and energy) <br>
                <b>Zinc:</b> 11 mg per day (important for muscle growth and immune function) <br>
                <b>Magnesium:</b> 400-420 mg per day (supports muscle function and recovery) <br>
                <b>Copper:</b> 900 mcg per day (important for energy production and connective tissue) <br>
                <b>Selenium:</b> 55 mcg per day (acts as an antioxidant and supports metabolism) <br>
                These trace minerals are essential for muscle development and overall <br>
                health.
                '];
            }
            if($LowYoungFemaleLose) {
                $desc = ['<b>For a young female under 35 with a low BMI aiming to lose</b> <br>
                <b>weight, here are some concise trace mineral recommendations:</b> <br>
                <b>Iron:</b> 18 mg per day (supports oxygen transport and energy) <br>
                <b>Zinc:</b> 8 mg per day (important for metabolism and immune function) <br>
                <b>Magnesium:</b> 310-320 mg per day (supports metabolism and muscle function) <br>
                <b>Copper:</b> 900 mcg per day (important for energy production and connective tissue) <br>
                <b>Selenium:</b> 55 mcg per day (acts as an antioxidant and supports metabolism) <br>
                These trace minerals support overall health and metabolic function during weight loss.
                '];
            }
            if($LowYoungFemaleGain) {
                $desc = ['<b>For a young female under 35 with a low BMI aiming to gain</b> <br>
                <b>muscle mass, here are some concise trace mineral recommendations:</b> <br>
                <b>Iron:</b> 18 mg per day (supports oxygen transport and energy) <br>
                <b>Zinc:</b> 8 mg per day (important for muscle growth and immune function) <br>
                <b>Magnesium:</b> 310-320 mg per day (supports muscle function and recovery) <br>
                <b>Copper:</b> 900 mcg per day (important for energy production and connective tissue) <br>
                <b>Selenium:</b> 55 mcg per day (acts as an antioxidant and supports metabolism) <br>
                These trace minerals are essential for muscle development and <br>
                overall health.
                '];
            }
            // -------
            if($HighOldMaleLose) {
                $desc = ['<b>For an older male (above 35-40) with a high BMI aiming to lose</b> <br>
                <b>weight, here are some concise trace mineral recommendations:</b> <br>
                <b>Iron:</b> 8 mg per day (supports oxygen transport and energy) <br>
                <b>Zinc:</b> 11 mg per day (important for metabolism and immune function) <br>
                <b>Magnesium:</b> 400-420 mg per day (supports muscle function and metabolism) <br>
                <b>Copper:</b> 900 mcg per day (important for energy production and connective tissue) <br>
                <b>Selenium:</b> 55 mcg per day (acts as an antioxidant and supports metabolism) <br>
                These trace minerals support overall health and metabolic function during weight loss.
                '];
            }
            if($HighOldMaleGain) {
                $desc = ['<b>For an older male (above 35-40) with a high BMI aiming to</b> <br>
                <b>gain muscle mass, here are some concise trace mineral recommendations:</b> <br>
                <b>Iron:</b> 8 mg per day (supports oxygen transport and energy) <br>
                <b>Zinc:</b> 11 mg per day (important for muscle growth and immune function) <br>
                <b>Magnesium:</b> 400-420 mg per day (supports muscle function and recovery) <br>
                <b>Copper:</b> 900 mcg per day (important for energy production and connective tissue) <br>
                <b>Selenium:</b> 55 mcg per day (acts as an antioxidant and supports metabolism) <br>
                These trace minerals help support muscle development and overall health.
                '];
            }
            if($HighOldFemaleLose) {
                $desc = ['<b>For an older female (above 35-40) with a high BMI aiming</b> <br>
                <b>to lose weight, here are some concise trace mineral recommendations:</b> <br>
                <b>Iron:</b> 8 mg per day (supports oxygen transport and energy levels) <br>
                <b>Zinc:</b> 8 mg per day (important for metabolism and immune function) <br>
                <b>Magnesium:</b> 320 mg per day (supports muscle function and metabolism) <br>
                <b>Copper:</b> 900 mcg per day (important for energy production and connective tissue) <br>
                <b>Selenium:</b> 55 mcg per day (acts as an antioxidant and supports metabolism) <br>
                These trace minerals support overall health and metabolic <br>
                function during weight loss.
                '];
            }
            if($HighOldFemaleGain) {
                $desc = ['<b>For an older female (above 35-40) with a high BMI aiming</b> <br>
                <b>to gain muscle mass, here are some concise trace mineral recommendations:</b> <br>
                <b>Iron:</b> 8 mg per day (supports oxygen transport and energy) <br>
                <b>Zinc:</b> 8 mg per day (important for muscle growth and immune function) <br>
                <b>Magnesium:</b> 320 mg per day (supports muscle function and recovery) <br>
                <b>Copper:</b> 900 mcg per day (important for energy production and connective tissue) <br>
                <b>Selenium:</b> 55 mcg per day (acts as an antioxidant and supports metabolism) <br>
                These trace minerals help support muscle development and overall health.
                '];
            }
            // -------
            if($LowOldMaleLose) {
                $desc = ['<b>For an older male (above 35-40) with a low BMI aiming to</b> <br>
                <b>lose weight, here are some concise trace mineral recommendations:</b> <br>
                <b>Iron:</b> 8 mg per day (supports oxygen transport and energy) <br>
                <b>Zinc:</b> 11 mg per day (important for metabolism and immune function) <br>
                <b>Magnesium:</b> 400-420 mg per day (supports muscle function and metabolism) <br>
                <b>Copper:</b> 900 mcg per day (important for energy production and connective tissue) <br>
                <b>Selenium:</b> 55 mcg per day (acts as an antioxidant and supports metabolism) <br>
                These trace minerals support overall health and metabolic function <br>
                during weight loss.
                '];
            }
            if($LowOldMaleGain) {
                $desc = ['<b>For an older male (above 35-40) with a low BMI aiming to</b> <br>
                <b>gain muscle mass, here are some concise trace mineral recommendations:</b> <br>
                <b>Iron:</b> 8 mg per day (supports oxygen transport and energy) <br>
                <b>Zinc:</b> 11 mg per day (important for muscle growth and immune function) <br>
                <b>Magnesium:</b> 400-420 mg per day (supports muscle function and recovery) <br>
                <b>Copper:</b> 900 mcg per day (important for energy production and connective tissue) <br>
                <b>Selenium:</b> 55 mcg per day (acts as an antioxidant and supports metabolism) <br>
                These trace minerals help support muscle development and overall health.
                '];
            }
            if($LowOldFemaleLose) {
                $desc = ['<b>For an older female (above 35-40) with a low BMI aiming to</b> <br>
                <b>lose weight, here are some concise trace mineral recommendations:</b> <br>
                <b>Iron:</b> 8 mg per day (supports oxygen transport and energy levels) <br>
                <b>Zinc:</b> 8 mg per day (important for metabolism and immune function) <br>
                <b>Magnesium:</b> 320 mg per day (supports muscle function and metabolism) <br>
                <b>Copper:</b> 900 mcg per day (important for energy production and connective tissue) <br>
                <b>Selenium:</b> 55 mcg per day (acts as an antioxidant and supports metabolism) <br>
                These trace minerals support overall health and metabolic function <br>
                during weight loss.'];
            }
            if($LowOldFemaleGain) {
                $desc = ['<b>For an older female (above 35-40) with a low BMI aiming to</b> <br>
                <b>gain muscle mass, here are some concise trace mineral recommendations:</b> <br>
                <b>Iron:</b> 8 mg per day (supports oxygen transport and energy) <br>
                <b>Zinc:</b> 8 mg per day (important for muscle growth and immune function) <br>
                <b>Magnesium:</b> 320 mg per day (supports muscle function and recovery) <br>
                <b>Copper:</b> 900 mcg per day (important for energy production and connective tissue) <br>
                <b>Selenium:</b> 55 mcg per day (acts as an antioxidant and supports metabolism) <br>
                These trace minerals help support muscle development and overall health.
                '];
            } 
        } 
    } elseif($contextFlag == "Meal") {
        if(!empty($dbOutRow['descMeal'])) { // use the entry by the user
            $desc = [$dbOutRow['descMeal']];
        } else { 
            if($HighYoungMaleLose) {
                $desc = ['<b>For weight loss, especially with a high BMI, it is important</b> <br>
                <b>to focus on a balanced, calorie-controlled meal plan that supports</b> <br>
                <b>a sustainable caloric deficit while providing adequate nutrition.</b> <br>
                <b>Here is a sample meal plan:</b> <br>
                <b>General Guidelines:</b> <br>
                <b>Caloric Intake:</b> Aim for a daily intake around 1,500-2,000 calories, <br>
                depending on your activity level and specific needs. <br>
                <b>Macronutrient Balance:</b> Focus on a balanced intake of proteins, <br>
                healthy fats, and complex carbohydrates. <br>
                <b>Meal Timing:</b> Consider intermittent fasting (e.g., 16:8) if it <br>
                aligns with your lifestyle. This involves eating within an 8-hour <br>
                window and fasting for 16 hours. <br>
                <b>Hydration:</b> Drink plenty of water throughout the day—aim for at <br>
                least 2-3 liters. <br>
                <b>Sample Meal Plan:</b> <br>
                <b>Morning (Optional for IF)</b> <br>
                <b>Green Tea or Black Coffee:</b> No sugar, minimal calories, helps <br>
                boost metabolism. <br>
                <b>Meal 1 (Breaking Fast - Around 12 PM)</b> <br>
                <b>Protein-Rich Breakfast:</b> <br>
                3 scrambled eggs or a veggie omelette (spinach, tomatoes, mushrooms). <br>
                1 slice of whole-grain toast. <br>
                1 avocado (half or a quarter) or a handful of nuts for healthy fats. <br>
                1 serving of Greek yogurt with berries. <br>
                <b>Meal 2 (Lunch - Around 3 PM)</b> <br>
                <b>Lean Protein + Veggies:</b> <br>
                Grilled chicken breast or tofu (150-200g). <br>
                Mixed salad with leafy greens, tomatoes, cucumbers, and a light <br>
                vinaigrette. <br>
                Quinoa or brown rice (1/2 cup) for fiber and carbs. <br>
                Steamed broccoli or roasted sweet potatoes. <br>
                <b>Snack (Optional - Around 5 PM)</b> <br>
                <b>Healthy Snack:</b> <br>
                A small handful of almonds or walnuts. <br>
                1 apple or carrot sticks with hummus. <br>
                <b>Meal 3 (Dinner - Around 7 PM)</b> <br>
                <b>Low-Carb, High-Protein Dinner:</b> <br>
                Baked salmon or grilled lean meat (150-200g). <br>
                Steamed asparagus, zucchini, or cauliflower rice. <br>
                A side salad with olive oil and lemon dressing. <br>
                <b>Evening (Post-Dinner - Optional)</b> <br>
                <b>Herbal Tea:</b> Peppermint or chamomile to aid digestion and relaxation. <br>
                <b>Additional Tips:</b> <br>
                <b>Exercise:</b> Incorporate regular physical activity, including both <br>
                cardio and strength training, to boost metabolism and muscle mass. <br>
                <b>Sleep:</b> Aim for 7-9 hours of quality sleep per night, as poor sleep <br>
                can hinder weight loss efforts. <br>
                <b>Mindful Eating:</b> Eat slowly and pay attention to hunger and fullness <br>
                cues.
                '];
            }
            if($HighYoungMaleGain) {
                $desc = ['<b>For gaining muscle mass, especially with a high BMI, it is</b> <br>
                <b>crucial to focus on a high-protein, calorie-controlled diet that</b> <br>
                <b>supports muscle growth while minimizing fat gain. Here is a sample</b> <br>
                <b>meal plan:</b> <br>
                <b>General Guidelines:</b> <br>
                <b>Caloric Intake:</b> Aim for a slight caloric surplus, around <br>
                2,500-3,000 calories daily, depending on your activity level and <br>
                metabolism. <br>
                <b>Macronutrient Balance:</b> Prioritize protein intake (1.2-2.2 grams <br>
                per kg of body weight) along with complex carbs and healthy fats. <br>
                <b>Meal Frequency:</b> Eat 4-6 meals throughout the day to ensure a <br>
                steady supply of nutrients for muscle growth. <br>
                <b>Hydration:</b> Drink plenty of water—aim for at least 3-4 liters <br>
                daily, especially around workouts. <br>
                <b>Sample Meal Plan:</b> <br>
                <b>Meal 1 (Breakfast - 7 AM)</b> <br>
                <b>High-Protein Breakfast:</b> <br>
                5 egg whites + 2 whole eggs scrambled with spinach and <br>
                bell peppers. <br>
                1 cup of oatmeal topped with a tablespoon of peanut butter <br>
                or mixed berries. <br>
                1 banana or apple. <br>
                1 glass of low-fat milk or a protein shake. <br>
                <b>Meal 2 (Mid-Morning Snack - 10 AM)</b> <br>
                <b>Protein and Carbs:</b> <br>
                1 cup of Greek yogurt with honey and granola. <br>
                A handful of almonds or a protein bar. <br>
                <b>Meal 3 (Lunch - 1 PM)</b> <br>
                <b>Lean Protein + Complex Carbs:</b> <br>
                Grilled chicken breast, turkey, or lean beef (200-250g). <br>
                1 cup of brown rice, quinoa, or whole-grain pasta. <br>
                Steamed broccoli, green beans, or a mixed vegetable stir-fry. <br>
                A small salad with olive oil dressing. <br>
                <b>Meal 4 (Afternoon Snack - 4 PM)</b> <br>
                <b>Muscle-Building Snack:</b> <br>
                Cottage cheese with a handful of mixed nuts. <br>
                Whole-grain toast with avocado slices or a smoothie with <br>
                protein powder, spinach, and a banana. <br>
                <b>Meal 5 (Dinner - 7 PM)</b> <br>
                <b>Protein and Veggies:</b> <br>
                Grilled salmon, tuna, or chicken (200-250g). <br>
                1 large sweet potato or mashed potatoes. <br>
                Roasted asparagus, Brussels sprouts, or a side of sautéed greens. <br>
                1 cup of mixed berries or a small fruit salad. <br>
                <b>Meal 6 (Evening Snack - 9 PM)</b> <br>
                <b>Slow-Digesting Protein:</b> <br>
                Casein protein shake or a serving of cottage cheese. <br>
                A small handful of walnuts or chia seeds. <br>
                <b>Additional Tips:</b> <br>
                <b>Workout:</b> Incorporate resistance training at least 4-5 times <br>
                a week, focusing on compound exercises like squats, deadlifts, <br>
                bench presses, and rows. <br>
                <b>Recovery:</b> Ensure adequate rest between workouts and prioritize <br>
                sleep to support muscle recovery. <br>
                <b>Supplements:</b> Consider using whey protein, creatine, and <br>
                branched-chain amino acids (BCAAs) to enhance muscle growth, <br>
                but consult with a healthcare professional first.
                '];
            }
            if($HighYoungFemaleLose) {
                $desc = ['<b>For weight loss in a high BMI young female under 35 years old,</b> <br>
                <b>the goal is to create a balanced, calorie-controlled meal plan</b> <br>
                <b>that promotes fat loss while maintaining muscle mass and overall</b> <br>
                <b>health. Here is a sample meal plan:</b> <br>
                <b>General Guidelines:</b> <br>
                <b>Caloric Intake:</b> Aim for a daily intake of 1,200-1,600 calories, <br>
                depending on activity level and metabolism. <br>
                <b>Macronutrient Balance:</b> Focus on lean proteins, healthy fats, <br>
                and complex carbohydrates. <br>
                <b>Meal Timing:</b> Consider eating smaller, balanced meals throughout <br>
                the day to keep metabolism active. <br>
                <b>Hydration:</b> Drink plenty of water—2-3 liters per day. <br>
                <b>Sample Meal Plan:</b> <br>
                <b>Meal 1 (Breakfast - Around 7 AM)</b> <br>
                <b>Protein and Fiber-Rich Breakfast:</b> <br>
                2 boiled eggs or a veggie omelette (with spinach, mushrooms, <br>
                and tomatoes). <br>
                1 slice of whole-grain toast or 1/2 cup of oatmeal with a <br>
                sprinkle of flaxseeds. <br>
                1 piece of fruit (apple, orange, or berries). <br>
                Herbal tea or black coffee. <br>
                <b>Meal 2 (Mid-Morning Snack - Around 10 AM)</b> <br>
                <b>Light Snack:</b> <br>
                1 small handful of almonds or walnuts. <br>
                1 cup of Greek yogurt or a small piece of fruit. <br>
                <b>Meal 3 (Lunch - Around 1 PM)</b> <br>
                <b>Lean Protein + Veggies:</b> <br>
                Grilled chicken breast, tofu, or fish (100-150g). <br>
                Mixed salad with leafy greens, cucumbers, cherry tomatoes, <br>
                and a light vinaigrette. <br>
                1/2 cup of quinoa, brown rice, or a small sweet potato. <br>
                Steamed broccoli or green beans. <br>
                <b>Meal 4 (Afternoon Snack - Around 4 PM)</b> <br>
                <b>Healthy Snack:</b> <br>
                Carrot sticks or cucumber slices with hummus. <br>
                A small handful of berries or a low-fat string cheese. <br>
                <b>Meal 5 (Dinner - Around 7 PM)</b> <br>
                <b>Low-Carb, High-Protein Dinner:</b> <br>
                Grilled salmon, turkey, or chicken (100-150g). <br>
                Stir-fried or steamed vegetables (zucchini, bell peppers, spinach). <br>
                A side salad with olive oil and lemon dressing. <br>
                <b>Meal 6 (Evening Snack - Optional)</b> <br>
                <b>Light Snack (If Needed):</b> <br>
                A small serving of cottage cheese or a casein protein shake. <br>
                Herbal tea, such as chamomile or peppermint. <br>
                <b>Additional Tips:</b> <br>
                <b>Exercise:</b> Incorporate a mix of cardio (e.g., brisk walking, <br>
                cycling) and strength training to enhance fat loss and muscle tone. <br>
                <b>Sleep:</b> Ensure 7-8 hours of sleep per night to support weight loss <br>
                efforts. <br>
                <b>Mindful Eating:</b> Focus on eating slowly and recognizing hunger <br>
                and fullness cues to prevent overeating.
                '];
            }
            if($HighYoungFemaleGain) {
                $desc = ['<b>To gain muscle mass, especially for a high BMI young female</b> <br>
                <b>under 35, the focus should be on a high-protein, balanced diet</b> <br>
                <b>that supports muscle growth while avoiding excessive fat gain.</b> <br>
                <b>Here is a sample meal plan:</b> <br>
                <b>General Guidelines:</b> <br>
                <b>Caloric Intake:</b> Aim for a slight caloric surplus, around 2,000-2,500 <br>
                calories daily, depending on your activity level and metabolism. <br>
                <b>Macronutrient Balance:</b> Prioritize protein (1.2-2.0 grams per kg <br>
                of body weight) along with complex carbohydrates and healthy fats. <br>
                <b>Meal Frequency:</b> Eat 4-6 meals throughout the day to keep a steady <br>
                supply of nutrients for muscle building. <br>
                <b>Hydration:</b> Drink at least 3-4 liters of water daily, especially <br>
                around workouts. <br>
                <b>Sample Meal Plan:</b> <br>
                <b>Meal 1 (Breakfast - 7 AM)</b> <br>
                <b>Protein-Packed Breakfast:</b> <br>
                3 egg whites + 1 whole egg scrambled with spinach and bell peppers. <br>
                1 cup of oatmeal with a tablespoon of almond butter or a <br>
                handful of nuts. <br>
                1 banana or mixed berries. <br>
                1 glass of low-fat milk or a protein shake. <br>
                <b>Meal 2 (Mid-Morning Snack - 10 AM)</b> <br>
                <b>Protein and Healthy Fats:</b> <br>
                1 cup of Greek yogurt with honey and a sprinkle of granola. <br>
                A small handful of almonds or a slice of whole-grain toast <br>
                with avocado. <br>
                <b>Meal 3 (Lunch - 1 PM)</b> <br>
                <b>Lean Protein + Complex Carbs:</b> <br>
                Grilled chicken breast, turkey, or tofu (150-200g). <br>
                1 cup of brown rice, quinoa, or whole-grain pasta. <br>
                A large mixed salad with leafy greens, cucumbers, cherry <br>
                tomatoes, and a light vinaigrette. <br>
                Steamed vegetables like broccoli or green beans. <br>
                <b>Meal 4 (Afternoon Snack - 4 PM)</b> <br>
                <b>Muscle-Building Snack:</b> <br>
                Cottage cheese with a handful of mixed nuts. <br>
                A protein smoothie with spinach, a banana, and a scoop of <br>
                protein powder. <br>
                <b>Meal 5 (Dinner - 7 PM)</b> <br>
                <b>Protein and Veggies:</b> <br>
                Grilled salmon, chicken, or lean beef (150-200g). <br>
                1 large sweet potato or mashed potatoes. <br>
                Roasted vegetables like asparagus, Brussels sprouts, or zucchini. <br>
                A small side of brown rice or quinoa if needed. <br>
                <b>Meal 6 (Evening Snack - 9 PM)</b> <br>
                <b>Slow-Digesting Protein:</b> <br>
                Casein protein shake or a serving of cottage cheese. <br>
                A small handful of walnuts or a tablespoon of chia seeds. <br>
                <b>Additional Tips:</b> <br>
                <b>Workout:</b> Engage in strength training 4-5 times a week, <br>
                focusing on compound movements like squats, deadlifts, and <br>
                bench presses. <br>
                <b>Recovery:</b> Ensure adequate rest and recovery between workouts, <br>
                including 7-8 hours of sleep per night. <br>
                <b>Supplements:</b> Consider using whey protein, creatine, and <br>
                branched-chain amino acids (BCAAs) to support muscle growth, <br>
                but consult with a healthcare professional before starting <br>
                any new supplement.
                '];
            }
            // -------
            if($LowYoungMaleLose) {
                $desc = ['<b>For a young male under 35 years old with a low BMI trying</b> <br>
                <b>to lose weight, the focus should be on creating a balanced diet</b> <br>
                <b>that ensures you maintain muscle mass while losing fat.</b> <br>
                <b>Since a low BMI indicates a leaner body type, it is essential to avoid</b> <br>
                <b>excessive calorie restriction to prevent muscle loss.</b> <br>
                <b>Here is a sample meal plan:</b> <br>
                <b>General Guidelines:</b> <br>
                <b>Caloric Intake:</b> Aim for a slight caloric deficit, around 1,500-2,000 <br>
                calories daily, depending on your activity level and goals. <br>
                <b>Macronutrient Balance:</b> Prioritize lean proteins, complex carbohydrates, and healthy fats. <br>
                <b>Meal Timing:</b> Eat 3-5 small, balanced meals throughout the day to <br>
                maintain energy levels. <br>
                <b>Hydration:</b> Drink plenty of water—at least 2-3 liters per day. <br>
                <b>Sample Meal Plan:</b> <br>
                <b>Meal 1 (Breakfast - 7 AM)</b> <br>
                <b>Balanced Breakfast:</b> <br>
                2 whole eggs or an omelette with veggies like spinach, tomatoes, <br>
                and mushrooms. <br>
                1 slice of whole-grain toast or 1/2 cup of oatmeal with a <br>
                sprinkle of chia seeds. <br>
                1 small piece of fruit (apple, orange, or berries). <br>
                Herbal tea or black coffee. <br>
                <b>Meal 2 (Mid-Morning Snack - 10 AM)</b> <br>
                <b>Light Snack:</b> <br>
                1 cup of Greek yogurt with a small handful of mixed nuts or seeds. <br>
                1 small piece of fruit like a banana or pear. <br>
                <b>Meal 3 (Lunch - 1 PM)</b> <br>
                <b>Lean Protein + Complex Carbs:</b> <br>
                Grilled chicken breast, turkey, or tofu (100-150g). <br>
                1/2 cup of quinoa, brown rice, or sweet potato. <br>
                Steamed vegetables such as broccoli, green beans, or carrots. <br>
                A small side salad with leafy greens and a light vinaigrette. <br>
                <b>Meal 4 (Afternoon Snack - 4 PM)</b> <br>
                <b>Healthy Snack:</b> <br>
                Carrot sticks or cucumber slices with hummus. <br>
                A small handful of almonds or walnuts. <br>
                <b>Meal 5 (Dinner - 7 PM)</b> <br>
                <b>Low-Carb, High-Protein Dinner:</b> <br>
                Grilled fish (such as salmon or tilapia) or lean meat (100-150g). <br>
                Stir-fried or steamed vegetables like zucchini, asparagus, or spinach. <br>
                A side salad with olive oil and lemon dressing. <br>
                <b>Meal 6 (Evening Snack - Optional)</b> <br>
                <b>Light Snack (If Needed):</b> <br>
                A small serving of cottage cheese or a casein protein shake. <br>
                Herbal tea, such as chamomile or peppermint. <br>
                <b>Additional Tips:</b> <br>
                <b>Exercise:</b> Focus on a combination of light resistance training <br>
                to maintain muscle mass and moderate cardio for fat loss. <br>
                <b>Sleep:</b> Ensure 7-8 hours of quality sleep each night to support <br>
                recovery and weight loss. <br>
                <b>Mindful Eating:</b> Eat slowly, paying attention to hunger and <br>
                fullness cues, to avoid overeating.
                '];
            }
            if($LowYoungMaleGain) {
                $desc = ['<b>For a young male under 35 years old with a low BMI aiming</b> <br>
                <b>to gain muscle mass, the focus should be on a high-calorie,</b> <br>
                <b>protein-rich diet to support muscle growth.</b> <br>
                <b>Here is a sample meal plan:</b> <br>
                <b>General Guidelines:</b> <br>
                <b>Caloric Intake:</b> Aim for a caloric surplus, around 2,500-3,000 <br>
                calories daily, depending on your metabolism and activity level. <br>
                <b>Macronutrient Balance:</b> Prioritize protein intake (1.5-2.0 grams per <br>
                kg of body weight), along with healthy fats and complex carbohydrates. <br>
                <b>Meal Frequency:</b> Eat 5-6 meals throughout the day to ensure a steady <br>
                supply of nutrients. <br>
                <b>Hydration:</b> Drink at least 3-4 liters of water daily, especially <br>
                around workouts. <br>
                <b>Sample Meal Plan:</b> <br>
                <b>Meal 1 (Breakfast - 7 AM)</b> <br>
                <b>High-Calorie Breakfast:</b> <br>
                3 whole eggs scrambled with spinach, bell peppers, and onions. <br>
                1 cup of oatmeal with a tablespoon of almond butter or peanut butter. <br>
                1 banana or a handful of mixed berries. <br>
                1 glass of whole milk or a protein shake. <br>
                <b>Meal 2 (Mid-Morning Snack - 10 AM)</b> <br>
                <b>Protein and Healthy Fats:</b> <br>
                1 cup of Greek yogurt with honey and granola. <br>
                A handful of mixed nuts or a protein bar. <br>
                <b>Meal 3 (Lunch - 1 PM)</b> <br>
                <b>Lean Protein + Complex Carbs:</b> <br>
                Grilled chicken breast, turkey, or lean beef (200-250g). <br>
                1 cup of brown rice, quinoa, or whole-grain pasta. <br>
                A large salad with leafy greens, cucumbers, tomatoes, and a <br>
                light vinaigrette. <br>
                Steamed vegetables like broccoli or green beans. <br>
                <b>Meal 4 (Afternoon Snack - 4 PM)</b> <br>
                <b>Muscle-Building Snack:</b> <br>
                Cottage cheese with a handful of mixed nuts. <br>
                A protein smoothie with spinach, a banana, and a scoop of protein powder. <br>
                <b>Meal 5 (Dinner - 7 PM)</b> <br>
                <b>Protein and Veggies:</b> <br>
                Grilled salmon, chicken, or lean beef (200-250g). <br>
                1 large sweet potato or mashed potatoes. <br>
                Roasted vegetables like asparagus, Brussels sprouts, or zucchini. <br>
                A small side of brown rice or quinoa if needed. <br>
                <b>Meal 6 (Evening Snack - 9 PM)</b> <br>
                <b>Slow-Digesting Protein:</b> <br>
                Casein protein shake or a serving of cottage cheese. <br>
                A small handful of walnuts or a tablespoon of chia seeds. <br>
                <b>Additional Tips:</b> <br>
                <b>Workout:</b> Engage in strength training 4-5 times a week, focusing <br>
                on compound movements like squats, deadlifts, and bench presses. <br>
                <b>Recovery:</b> Ensure adequate rest and recovery between workouts, <br>
                including 7-8 hours of sleep per night. <br>
                <b>Supplements:</b> Consider using whey protein, creatine, and <br>
                branched-chain amino acids (BCAAs) to support muscle growth, <br>
                but consult with a healthcare professional before starting any <br>
                new supplement.
                '];
            }
            if($LowYoungFemaleLose) {
                $desc = ['<b>For a low BMI young female under 35 years old trying to</b> <br>
                <b>lose weight, the focus should be on a balanced, nutrient-dense</b> <br>
                <b>diet that supports fat loss while preserving lean muscle mass.</b> <br>
                <b>Here is a sample meal plan:</b> <br>
                <b>General Guidelines:</b> <br>
                <b>Caloric Intake:</b> Aim for a slight caloric deficit, around 1,200-1,500 <br>
                calories daily, depending on activity level. <br>
                <b>Macronutrient Balance:</b> Emphasize lean proteins, healthy fats, and <br>
                complex carbohydrates. <br>
                <b>Meal Timing:</b> Eat 3-5 small, balanced meals throughout the day to <br>
                keep metabolism active. <br>
                <b>Hydration:</b> Drink plenty of water—2-3 liters per day. <br>
                <b>Sample Meal Plan:</b> <br>
                <b>Meal 1 (Breakfast - 7 AM)</b> <br>
                <b>Protein and Fiber-Rich Breakfast:</b> <br>
                2 whole eggs or an omelette with veggies like spinach, tomatoes, <br>
                and mushrooms. <br>
                1 slice of whole-grain toast or 1/2 cup of oatmeal with a sprinkle <br>
                of flaxseeds. <br>
                1 piece of fruit (apple, orange, or berries). <br>
                Herbal tea or black coffee. <br>
                <b>Meal 2 (Mid-Morning Snack - 10 AM)</b> <br>
                <b>Light Snack:</b> <br>
                1 cup of Greek yogurt with a small handful of almonds or walnuts. <br>
                1 small piece of fruit like a banana or a few berries. <br>
                <b>Meal 3 (Lunch - 1 PM)</b> <br>
                <b>Lean Protein + Complex Carbs:</b> <br>
                Grilled chicken breast, tofu, or fish (100-150g). <br>
                1/2 cup of quinoa, brown rice, or sweet potato. <br>
                Steamed vegetables such as broccoli, green beans, or carrots. <br>
                A small side salad with leafy greens and a light vinaigrette. <br>
                <b>Meal 4 (Afternoon Snack - 4 PM)</b> <br>
                <b>Healthy Snack:</b> <br>
                Carrot sticks or cucumber slices with hummus. <br>
                A small handful of mixed nuts or a low-fat string cheese. <br>
                <b>Meal 5 (Dinner - 7 PM)</b> <br>
                <b>Low-Carb, High-Protein Dinner:</b> <br>
                Grilled fish (such as salmon or tilapia) or lean meat (100-150g). <br>
                Stir-fried or steamed vegetables like zucchini, asparagus, or spinach. <br>
                A side salad with olive oil and lemon dressing. <br>
                <b>Meal 6 (Evening Snack - Optional)</b> <br>
                <b>Light Snack (If Needed):</b> <br>
                A small serving of cottage cheese or a casein protein shake. <br>
                Herbal tea, such as chamomile or peppermint. <br>
                <b>Additional Tips:</b> <br>
                <b>Exercise:</b> Focus on a combination of light resistance training <br>
                to maintain muscle mass and moderate cardio for fat loss. <br>
                <b>Sleep:</b> Ensure 7-8 hours of quality sleep each night to support <br>
                recovery and weight loss. <br>
                <b>Mindful Eating:</b> Eat slowly, paying attention to hunger and <br>
                fullness cues, to avoid overeating.
                '];
            }
            if($LowYoungFemaleGain) {
                $desc = ['<b>For a young female under 35 years old with a low BMI aiming</b> <br>
                <b>to gain muscle mass, the focus should be on a high-calorie, protein-rich</b> <br>
                <b>diet that supports muscle growth. Here is a sample meal plan:</b> <br>
                <b>General Guidelines:</b> <br>
                <b>Caloric Intake:</b> Aim for a caloric surplus, around 2,000-2,500 <br>
                calories daily, depending on your activity level. <br>
                <b>Macronutrient Balance:</b> Prioritize protein intake (1.5-2.0 grams per <br>
                kg of body weight) along with healthy fats and complex carbohydrates. <br>
                <b>Meal Frequency:</b> Eat 5-6 meals throughout the day to ensure a steady <br>
                supply of nutrients for muscle growth. <br>
                <b>Hydration:</b> Drink at least 3-4 liters of water daily, especially <br>
                around workouts. <br>
                <b>Sample Meal Plan:</b> <br>
                <b>Meal 1 (Breakfast - 7 AM)</b> <br>
                <b>Protein-Packed Breakfast:</b> <br>
                2 whole eggs scrambled with spinach, tomatoes, and mushrooms. <br>
                1 cup of oatmeal with a tablespoon of almond butter or peanut butter. <br>
                1 banana or a handful of mixed berries. <br>
                1 glass of whole milk or a protein shake. <br>
                <b>Meal 2 (Mid-Morning Snack - 10 AM)</b> <br>
                <b>Protein and Healthy Fats:</b> <br>
                1 cup of Greek yogurt with honey and granola. <br>
                A handful of mixed nuts or a slice of whole-grain toast with avocado. <br>
                <b>Meal 3 (Lunch - 1 PM)</b> <br>
                <b>Lean Protein + Complex Carbs:</b> <br>
                Grilled chicken breast, turkey, or tofu (150-200g). <br>
                1 cup of brown rice, quinoa, or whole-grain pasta. <br>
                A large salad with leafy greens, cucumbers, cherry tomatoes, and a light vinaigrette. <br>
                Steamed vegetables like broccoli or green beans. <br>
                <b>Meal 4 (Afternoon Snack - 4 PM)</b> <br>
                <b>Muscle-Building Snack:</b> <br>
                Cottage cheese with a handful of mixed nuts. <br>
                A protein smoothie with spinach, a banana, and a scoop of protein powder. <br>
                <b>Meal 5 (Dinner - 7 PM)</b> <br>
                <b>Protein and Veggies:</b> <br>
                Grilled salmon, chicken, or lean beef (150-200g). <br>
                1 large sweet potato or mashed potatoes. <br>
                Roasted vegetables like asparagus, Brussels sprouts, or zucchini. <br>
                A small side of brown rice or quinoa if needed. <br>
                <b>Meal 6 (Evening Snack - 9 PM)</b> <br>
                <b>Slow-Digesting Protein:</b> <br>
                Casein protein shake or a serving of cottage cheese. <br>
                A small handful of walnuts or a tablespoon of chia seeds. <br>
                <b>Additional Tips:</b> <br>
                <b>Workout:</b> Engage in strength training 4-5 times a week, focusing <br>
                on compound movements like squats, deadlifts, and bench presses. <br>
                <b>Recovery:</b> Ensure adequate rest and recovery between workouts, <br>
                including 7-8 hours of sleep per night. <br>
                <b>Supplements:</b> Consider using whey protein, creatine, and branched-chain <br>
                amino acids (BCAAs) to support muscle growth, but consult with a <br>
                healthcare professional before starting any new supplement.
                '];
            }
            // -------
            if($HighOldMaleLose) {
                $desc = ['<b>For a high BMI older male above 35-40 years old trying to</b> <br>
                <b>lose weight, the focus should be on a balanced, calorie-controlled</b> <br>
                <b>diet that promotes fat loss while maintaining muscle mass. Here is</b> <br>
                <b>a sample meal plan:</b> <br>
                <b>General Guidelines:</b> <br>
                <b>Caloric Intake:</b> Aim for a daily intake of 1,500-2,000 calories, <br>
                depending on activity level and metabolism. <br>
                <b>Macronutrient Balance:</b> Focus on lean proteins, healthy fats, <br>
                and complex carbohydrates. <br>
                <b>Meal Timing:</b> Eat 3-5 small, balanced meals throughout the day <br>
                to keep metabolism active. <br>
                <b>Hydration:</b> Drink plenty of water—2-3 liters per day. <br>
                <b>Sample Meal Plan:</b> <br>
                <b>Meal 1 (Breakfast - 7 AM)</b> <br>
                <b>Balanced Breakfast:</b> <br>
                3 egg whites and 1 whole egg scrambled with spinach, tomatoes, <br>
                and onions. <br>
                1 slice of whole-grain toast or 1/2 cup of oatmeal with a sprinkle <br>
                of flaxseeds. <br>
                1 piece of fruit (apple, orange, or berries). <br>
                Black coffee or green tea. <br>
                <b>Meal 2 (Mid-Morning Snack - 10 AM)</b> <br>
                <b>Light Snack:</b> <br>
                1 small handful of almonds or walnuts. <br>
                1 cup of Greek yogurt with a small amount of honey or berries. <br>
                <b>Meal 3 (Lunch - 1 PM)</b> <br>
                <b>Lean Protein + Vegetables:</b> <br>
                Grilled chicken breast, turkey, or fish (150g). <br>
                Mixed salad with leafy greens, cucumbers, cherry tomatoes, and <br>
                a light vinaigrette. <br>
                1/2 cup of quinoa, brown rice, or a small sweet potato. <br>
                Steamed broccoli or green beans. <br>
                <b>Meal 4 (Afternoon Snack - 4 PM)</b> <br>
                <b>Healthy Snack:</b> <br>
                Carrot sticks or cucumber slices with hummus. <br>
                A small apple or a few slices of cheese. <br>
                <b>Meal 5 (Dinner - 7 PM)</b> <br>
                <b>Low-Carb, High-Protein Dinner:</b> <br>
                Grilled salmon, chicken, or lean beef (150g). <br>
                Stir-fried or steamed vegetables like zucchini, asparagus, or spinach. <br>
                A side salad with olive oil and lemon dressing. <br>
                <b>Meal 6 (Evening Snack - Optional)</b> <br>
                <b>Light Snack (If Needed):</b> <br>
                A small serving of cottage cheese or a casein protein shake. <br>
                Herbal tea, such as chamomile or peppermint. <br>
                <b>Additional Tips:</b> <br>
                <b>Exercise:</b> Incorporate a mix of moderate cardio (e.g., walking, <br>
                cycling) and strength training to support fat loss and muscle <br>
                preservation. <br>
                <b>Sleep:</b> Ensure 7-8 hours of sleep per night to support weight <br>
                loss and overall health. <br>
                <b>Mindful Eating:</b> Focus on eating slowly and recognizing hunger <br>
                and fullness cues to prevent overeating.
                '];
            }
            if($HighOldMaleGain) {
                $desc = ['<b>For a high BMI older male above 35-40 years old trying to</b> <br>
                <b>gain muscle mass, the focus should be on a nutrient-dense,</b> <br>
                <b>protein-rich diet that supports muscle growth while managing body</b> <br>
                <b>fat. Here is a sample meal plan:</b> <br>
                <b>General Guidelines:</b> <br>
                <b>Caloric Intake:</b> Aim for a moderate caloric surplus, around <br>
                2,200-2,800 calories daily, depending on your activity level. <br>
                <b>Macronutrient Balance:</b> Prioritize protein intake (1.2-1.6 grams <br>
                per kg of body weight), along with healthy fats and complex <br>
                carbohydrates. <br>
                <b>Meal Frequency:</b> Eat 4-5 meals throughout the day to ensure a steady <br>
                supply of nutrients for muscle growth. <br>
                <b>Hydration:</b> Drink at least 3-4 liters of water daily, especially <br>
                around workouts. <br>
                <b>Sample Meal Plan:</b> <br>
                <b>Meal 1 (Breakfast - 7 AM)</b> <br>
                <b>Protein and Fiber-Rich Breakfast:</b> <br>
                3 whole eggs scrambled with spinach, tomatoes, and onions. <br>
                1 cup of oatmeal with a tablespoon of almond butter or peanut butter. <br>
                1 banana or a handful of mixed berries. <br>
                1 glass of low-fat milk or a protein shake. <br>
                <b>Meal 2 (Mid-Morning Snack - 10 AM)</b> <br>
                <b>Protein and Healthy Fats:</b> <br>
                1 cup of Greek yogurt with honey and a sprinkle of granola. <br>
                A handful of mixed nuts or a slice of whole-grain toast with avocado. <br>
                <b>Meal 3 (Lunch - 1 PM)</b> <br>
                <b>Lean Protein + Complex Carbs:</b> <br>
                Grilled chicken breast, turkey, or lean beef (200g). <br>
                1 cup of brown rice, quinoa, or whole-grain pasta. <br>
                A large mixed salad with leafy greens, cucumbers, cherry tomatoes, <br>
                and a light vinaigrette. <br>
                Steamed vegetables like broccoli, cauliflower, or green beans. <br>
                <b>Meal 4 (Afternoon Snack - 4 PM)</b> <br>
                <b>Muscle-Building Snack:</b> <br>
                Cottage cheese with a handful of mixed nuts. <br>
                A protein smoothie with spinach, a banana, and a scoop of protein <br>
                powder. <br>
                <b>Meal 5 (Dinner - 7 PM)</b> <br>
                <b>Protein and Veggies:</b> <br>
                Grilled salmon, chicken, or lean beef (200g). <br>
                1 large sweet potato or mashed potatoes. <br>
                Roasted vegetables like asparagus, Brussels sprouts, or zucchini. <br>
                A small side of brown rice or quinoa if needed. <br>
                <b>Meal 6 (Evening Snack - 9 PM)</b> <br>
                <b>Slow-Digesting Protein:</b> <br>
                Casein protein shake or a serving of cottage cheese. <br>
                A small handful of walnuts or a tablespoon of chia seeds. <br>
                <b>Additional Tips:</b> <br>
                <b>Exercise:</b> Engage in strength training 3-5 times a week, focusing <br>
                on compound movements like squats, deadlifts, and bench presses. <br>
                Incorporate moderate cardio to support cardiovascular health. <br>
                <b>Recovery:</b> Prioritize rest and recovery, including 7-8 hours of <br>
                sleep per night. <br>
                <b>Supplements:</b> Consider using whey protein, creatine, and <br>
                branched-chain amino acids (BCAAs) to support muscle growth, <br>
                but consult with a healthcare professional before starting any <br>
                new supplement.
                '];
            }
            if($HighOldFemaleLose) {
                $desc = ['<b>For a high BMI older female above 35-40 years old trying</b> <br>
                <b>to lose weight, the focus should be on a balanced, calorie-controlled</b> <br>
                <b>diet that promotes fat loss while maintaining muscle mass and overall</b> <br>
                <b>health. Here is a sample meal plan:</b> <br>
                <b>General Guidelines:</b> <br>
                <b>Caloric Intake:</b> Aim for a daily intake of 1,200-1,600 calories, <br>
                depending on activity level. <br>
                <b>Macronutrient Balance:</b> Emphasize lean proteins, healthy fats, <br>
                and complex carbohydrates with a focus on nutrient-dense foods. <br>
                <b>Meal Timing:</b> Eat 3-5 small, balanced meals throughout the day to <br>
                maintain energy levels and prevent overeating. <br>
                <b>Hydration:</b> Drink at least 2-3 liters of water daily. <br>
                <b>Sample Meal Plan:</b> <br>
                <b>Meal 1 (Breakfast - 7 AM)</b> <br>
                <b>Protein and Fiber-Rich Breakfast:</b> <br>
                2 scrambled eggs or an omelette with spinach, tomatoes, and onions. <br>
                1 slice of whole-grain toast or 1/2 cup of oatmeal with a sprinkle <br>
                of chia seeds. <br>
                1 small piece of fruit (apple, orange, or berries). <br>
                Herbal tea or black coffee. <br>
                <b>Meal 2 (Mid-Morning Snack - 10 AM)</b> <br>
                <b>Light Snack:</b> <br>
                1 small handful of almonds or walnuts. <br>
                1 cup of Greek yogurt with a few berries. <br>
                <b>Meal 3 (Lunch - 1 PM)</b> <br>
                <b>Lean Protein + Vegetables:</b> <br>
                Grilled chicken breast, turkey, or fish (100-150g). <br>
                Mixed salad with leafy greens, cucumbers, cherry tomatoes, and <br>
                a light vinaigrette. <br>
                1/2 cup of quinoa, brown rice, or a small sweet potato. <br>
                Steamed vegetables like broccoli, green beans, or zucchini. <br>
                <b>Meal 4 (Afternoon Snack - 4 PM)</b> <br>
                <b>Healthy Snack:</b> <br>
                Carrot sticks or cucumber slices with hummus. <br>
                A small apple or a low-fat string cheese. <br>
                <b>Meal 5 (Dinner - 7 PM)</b> <br>
                <b>Low-Carb, High-Protein Dinner:</b> <br>
                Grilled salmon, chicken, or lean beef (100-150g). <br>
                Stir-fried or steamed vegetables like zucchini, asparagus, <br>
                or spinach. <br>
                A side salad with olive oil and lemon dressing. <br>
                <b>Meal 6 (Evening Snack - Optional)</b> <br>
                <b>Light Snack (If Needed):</b> <br>
                A small serving of cottage cheese or a casein protein shake. <br>
                Herbal tea, such as chamomile or peppermint. <br>
                <b>Additional Tips:</b> <br>
                <b>Exercise:</b> Incorporate a mix of moderate cardio (e.g., walking, <br>
                cycling) and strength training to support fat loss and muscle <br>
                preservation. <br>
                <b>Sleep:</b> Ensure 7-8 hours of quality sleep each night to support <br>
                weight loss and overall health. <br>
                <b>Mindful Eating:</b> Focus on eating slowly and recognizing hunger and <br>
                fullness cues to avoid overeating.
                '];
            }
            if($HighOldFemaleGain) {
                $desc = ['<b>For a high BMI older female above 35-40 years old trying to</b> <br>
                <b>gain muscle mass, the focus should be on a nutrient-dense, protein-rich</b> <br>
                <b>diet that supports muscle growth while managing body composition. Here is</b> <br>
                <b>a sample meal plan:</b> <br>
                <b>General Guidelines:</b> <br>
                <b>Caloric Intake:</b> Aim for a moderate caloric surplus, around <br>
                1,800-2,200 calories daily, depending on your activity level. <br>
                <b>Macronutrient Balance:</b> Prioritize protein intake (1.2-1.6 grams <br>
                per kg of body weight), along with healthy fats and complex <br>
                carbohydrates. <br>
                <b>Meal Frequency:</b> Eat 4-5 meals throughout the day to ensure a <br>
                steady supply of nutrients for muscle growth. <br>
                <b>Hydration:</b> Drink at least 2.5-3 liters of water daily, especially <br>
                around workouts. <br>
                <b>Sample Meal Plan:</b> <br>
                <b>Meal 1 (Breakfast - 7 AM)</b> <br>
                <b>Protein and Complex Carbs:</b> <br>
                3 scrambled egg whites and 1 whole egg with spinach, tomatoes, <br>
                and onions. <br>
                1/2 cup of oatmeal with a tablespoon of almond butter or peanut <br>
                butter. <br>
                1 small banana or a handful of mixed berries. <br>
                1 glass of low-fat milk or a protein shake. <br>
                <b>Meal 2 (Mid-Morning Snack - 10 AM)</b> <br>
                <b>Protein and Healthy Fats:</b> <br>
                1 cup of Greek yogurt with honey and a sprinkle of granola. <br>
                A handful of mixed nuts or a slice of whole-grain toast with avocado. <br>
                <b>Meal 3 (Lunch - 1 PM)</b> <br>
                <b>Lean Protein + Complex Carbs:</b> <br>
                Grilled chicken breast, turkey, or lean beef (150g). <br>
                1 cup of brown rice, quinoa, or whole-grain pasta. <br>
                A large mixed salad with leafy greens, cucumbers, cherry tomatoes, <br>
                and a light vinaigrette. <br>
                Steamed vegetables like broccoli, cauliflower, or green beans. <br>
                <b>Meal 4 (Afternoon Snack - 4 PM)</b> <br>
                <b>Muscle-Building Snack:</b> <br>
                Cottage cheese with a handful of mixed nuts. <br>
                A protein smoothie with spinach, a banana, and a scoop of protein <br>
                powder. <br>
                <b>Meal 5 (Dinner - 7 PM)</b> <br>
                <b>Protein and Veggies:</b> <br>
                Grilled salmon, chicken, or lean beef (150g). <br>
                1 large sweet potato or mashed potatoes. <br>
                Roasted vegetables like asparagus, Brussels sprouts, or zucchini. <br>
                A small side of brown rice or quinoa if needed. <br>
                <b>Meal 6 (Evening Snack - 9 PM)</b> <br>
                <b>Slow-Digesting Protein:</b> <br>
                Casein protein shake or a serving of cottage cheese. <br>
                A small handful of walnuts or a tablespoon of chia seeds. <br>
                <b>Additional Tips:</b> <br>
                <b>Exercise:</b> Engage in strength training 3-5 times a week, focusing <br>
                on compound movements like squats, deadlifts, and bench presses. <br>
                Incorporate moderate cardio to support cardiovascular health. <br>
                <b>Recovery:</b> Prioritize rest and recovery, including 7-8 hours of <br>
                sleep per night. <br>
                <b>Supplements:</b> Consider using whey protein, creatine, and <br>
                branched-chain amino acids (BCAAs) to support muscle growth, but <br>
                consult with a healthcare professional before starting any new <br>
                supplement.
                '];
            }
            // -------
            if($LowOldMaleLose) {
                $desc = ['<b>For a low BMI older male above 35-40 years old trying to</b> <br>
                <b>lose weight, the focus should be on a balanced diet that promotes</b> <br>
                <b>fat loss while maintaining or even slightly increasing muscle mass.</b> <br>
                <b>This approach ensures that weight loss comes from fat rather than</b> <br>
                <b>muscle, which is crucial for maintaining overall health and strength.</b> <br>
                <b>General Guidelines:</b> <br>
                <b>Caloric Intake:</b> Aim for a slight caloric deficit, around 1,500-1,800 <br>
                calories daily, depending on activity level. <br>
                <b>Macronutrient Balance:</b> Emphasize lean proteins, healthy fats, and <br>
                complex carbohydrates with a focus on nutrient-dense foods. <br>
                <b>Meal Timing:</b> Eat 3-4 small, balanced meals throughout the day to <br>
                maintain energy levels. <br>
                <b>Hydration:</b> Drink at least 2.5-3 liters of water daily. <br>
                <b>Sample Meal Plan:</b> <br>
                <b>Meal 1 (Breakfast - 7 AM)</b> <br>
                <b>Protein and Fiber-Rich Breakfast:</b> <br>
                3 egg whites and 1 whole egg scrambled with spinach, tomatoes, <br>
                and onions. <br>
                1 slice of whole-grain toast or 1/2 cup of oatmeal with a sprinkle <br>
                of flaxseeds. <br>
                1 piece of fruit (apple, orange, or berries). <br>
                Green tea or black coffee. <br>
                <b>Meal 2 (Mid-Morning Snack - 10 AM)</b> <br>
                <b>Light Snack:</b> <br>
                1 small handful of almonds or walnuts. <br>
                1 cup of Greek yogurt with a small amount of honey or berries. <br>
                <b>Meal 3 (Lunch - 1 PM)</b> <br>
                <b>Lean Protein + Vegetables:</b> <br>
                Grilled chicken breast, turkey, or fish (150g). <br>
                Mixed salad with leafy greens, cucumbers, cherry tomatoes, and <br>
                a light vinaigrette. <br>
                1/2 cup of quinoa, brown rice, or a small sweet potato. <br>
                Steamed vegetables like broccoli, green beans, or zucchini. <br>
                <b>Meal 4 (Afternoon Snack - 4 PM)</b> <br>
                <b>Healthy Snack:</b> <br>
                Carrot sticks or cucumber slices with hummus. <br>
                A small apple or a low-fat string cheese. <br>
                <b>Meal 5 (Dinner - 7 PM)</b> <br>
                <b>Low-Carb, High-Protein Dinner:</b> <br>
                Grilled salmon, chicken, or lean beef (150g). <br>
                Stir-fried or steamed vegetables like zucchini, asparagus, or spinach. <br>
                A side salad with olive oil and lemon dressing. <br>
                <b>Meal 6 (Evening Snack - Optional)</b> <br>
                <b>Light Snack (If Needed):</b> <br>
                A small serving of cottage cheese or a casein protein shake. <br>
                Herbal tea, such as chamomile or peppermint. <br>
                <b>Additional Tips:</b> <br>
                <b>Exercise:</b> Incorporate a mix of moderate cardio (e.g., walking, <br>
                cycling) and strength training to support fat loss and muscle <br>
                preservation. <br>
                <b>Sleep:</b> Ensure 7-8 hours of quality sleep each night to support <br>
                weight loss and overall health. <br>
                <b>Mindful Eating:</b> Focus on eating slowly and recognizing hunger and <br>
                fullness cues to avoid overeating.
                '];
            }
            if($LowOldMaleGain) {
                $desc = ['<b>For a low BMI older male above 35-40 years old trying to gain</b> <br>
                <b>muscle mass, the focus should be on a calorie-rich, protein-dense</b> <br>
                <b>diet that supports muscle growth while maintaining overall health.</b> <br>
                <b>Here is a sample meal plan:</b> <br>
                <b>General Guidelines:</b> <br>
                <b>Caloric Intake:</b> Aim for a moderate caloric surplus, around 2,200-2,800 <br>
                calories daily, depending on activity level. <br>
                <b>Macronutrient Balance:</b> Prioritize protein intake (1.2-1.6 grams per <br>
                kg of body weight), along with healthy fats and complex carbohydrates. <br>
                <b>Meal Frequency:</b> Eat 4-5 meals throughout the day to ensure a steady <br>
                supply of nutrients for muscle growth. <br>
                <b>Hydration:</b> Drink at least 3-4 liters of water daily, especially <br>
                around workouts. <br>
                <b>Sample Meal Plan:</b> <br>
                <b>Meal 1 (Breakfast - 7 AM)</b> <br>
                <b>Protein and Complex Carbs:</b> <br>
                3 whole eggs scrambled with spinach, tomatoes, and onions. <br>
                1 cup of oatmeal with a tablespoon of almond butter or peanut butter. <br>
                1 banana or a handful of mixed berries. <br>
                1 glass of low-fat milk or a protein shake. <br>
                <b>Meal 2 (Mid-Morning Snack - 10 AM)</b> <br>
                <b>Protein and Healthy Fats:</b> <br>
                1 cup of Greek yogurt with honey and a sprinkle of granola. <br>
                A handful of mixed nuts or a slice of whole-grain toast with avocado. <br>
                <b>Meal 3 (Lunch - 1 PM)</b> <br>
                <b>Lean Protein + Complex Carbs:</b> <br>
                Grilled chicken breast, turkey, or lean beef (200g). <br>
                1 cup of brown rice, quinoa, or whole-grain pasta. <br>
                A large mixed salad with leafy greens, cucumbers, cherry tomatoes, <br>
                and a light vinaigrette. <br>
                Steamed vegetables like broccoli, cauliflower, or green beans. <br>
                <b>Meal 4 (Afternoon Snack - 4 PM)</b> <br>
                <b>Muscle-Building Snack:</b> <br>
                Cottage cheese with a handful of mixed nuts. <br>
                A protein smoothie with spinach, a banana, and a scoop of protein <br>
                powder. <br>
                <b>Meal 5 (Dinner - 7 PM)</b> <br>
                <b>Protein and Veggies:</b> <br>
                Grilled salmon, chicken, or lean beef (200g). <br>
                1 large sweet potato or mashed potatoes. <br>
                Roasted vegetables like asparagus, Brussels sprouts, or zucchini. <br>
                A small side of brown rice or quinoa if needed. <br>
                <b>Meal 6 (Evening Snack - 9 PM)</b> <br>
                <b>Slow-Digesting Protein:</b> <br>
                Casein protein shake or a serving of cottage cheese. <br>
                A small handful of walnuts or a tablespoon of chia seeds. <br>
                <b>Additional Tips:</b> <br>
                <b>Exercise:</b> Engage in strength training 3-5 times a week, focusing <br>
                on compound movements like squats, deadlifts, and bench presses. <br>
                Incorporate moderate cardio to support cardiovascular health. <br>
                <b>Recovery:</b> Prioritize rest and recovery, including 7-8 hours of <br>
                sleep per night. <br>
                <b>Supplements:</b> Consider using whey protein, creatine, and <br>
                branched-chain amino acids (BCAAs) to support muscle growth, but <br>
                consult with a healthcare professional before starting any new <br>
                supplement.
                '];
            }
            if($LowOldFemaleLose) {
                $desc = ['<b>For a low BMI older female above 35-40 years old trying to</b> <br>
                <b>lose weight, the focus should be on a nutrient-dense diet that</b> <br>
                <b>promotes fat loss while maintaining lean muscle mass.</b> <br>
                <b>Here is a sample meal plan:</b> <br>
                <b>General Guidelines:</b> <br>
                <b>Caloric Intake:</b> Aim for a slight caloric deficit, around 1,200-1,500 <br>
                calories daily, depending on activity level. <br>
                <b>Macronutrient Balance:</b> Emphasize lean proteins, healthy fats, <br>
                and complex carbohydrates with plenty of fiber. <br>
                <b>Meal Timing:</b> Eat 3-4 small, balanced meals throughout the day to <br>
                maintain energy levels and prevent muscle loss. <br>
                <b>Hydration:</b> Drink at least 2-3 liters of water daily. <br>
                <b>Sample Meal Plan:</b> <br>
                <b>Meal 1 (Breakfast - 7 AM)</b> <br>
                <b>Protein and Fiber-Rich Breakfast:</b> <br>
                2 scrambled eggs with spinach and tomatoes. <br>
                1 slice of whole-grain toast or 1/2 cup of oatmeal with a sprinkle <br>
                of chia seeds. <br>
                1 small piece of fruit (apple, orange, or berries). <br>
                Green tea or black coffee. <br>
                <b>Meal 2 (Mid-Morning Snack - 10 AM)</b> <br>
                <b>Light Snack:</b> <br>
                1 small handful of almonds or walnuts. <br>
                1 cup of Greek yogurt with a few berries or a drizzle of honey. <br>
                <b>Meal 3 (Lunch - 1 PM)</b> <br>
                <b>Lean Protein + Vegetables:</b> <br>
                Grilled chicken breast, turkey, or fish (100-150g). <br>
                Mixed salad with leafy greens, cucumbers, cherry tomatoes, and a <br>
                light vinaigrette. <br>
                1/2 cup of quinoa, brown rice, or a small sweet potato. <br>
                Steamed vegetables like broccoli, green beans, or zucchini. <br>
                <b>Meal 4 (Afternoon Snack - 4 PM)</b> <br>
                <b>Healthy Snack:</b> <br>
                Carrot sticks or cucumber slices with hummus. <br>
                A small apple or a low-fat string cheese. <br>
                <b>Meal 5 (Dinner - 7 PM)</b> <br>
                <b>Low-Carb, High-Protein Dinner:</b> <br>
                Grilled salmon, chicken, or lean beef (100-150g). <br>
                Stir-fried or steamed vegetables like zucchini, asparagus, or spinach. <br>
                A side salad with olive oil and lemon dressing. <br>
                <b>Meal 6 (Evening Snack - Optional)</b> <br>
                <b>Light Snack (If Needed):</b> <br>
                A small serving of cottage cheese or a casein protein shake. <br>
                Herbal tea, such as chamomile or peppermint. <br>
                <b>Additional Tips:</b> <br>
                <b>Exercise:</b> Incorporate a mix of moderate cardio (e.g., walking, cycling) and strength training to preserve muscle while losing fat. <br>
                <b>Sleep:</b> Ensure 7-8 hours of quality sleep each night to support weight <br>
                loss and overall health. <br>
                <b>Mindful Eating:</b> Focus on eating slowly and recognizing hunger and <br>
                fullness cues to avoid overeating.
                '];
            }
            if($LowOldFemaleGain) {
                $desc = ['<b>For a low BMI older female above 35-40 years old trying to</b> <br>
                <b>gain muscle mass, the focus should be on a nutrient-rich,</b> <br>
                <b>calorie-dense diet that supports muscle growth while maintaining</b> <br>
                <b>overall health. Here is a sample meal plan:</b> <br>
                <b>General Guidelines:</b> <br>
                <b>Caloric Intake:</b> Aim for a moderate caloric surplus, around 1,800-2,200 <br>
                calories daily, depending on activity level. <br>
                <b>Macronutrient Balance:</b> Prioritize protein intake (1.2-1.6 grams per <br>
                kg of body weight), along with healthy fats and complex carbohydrates. <br>
                <b>Meal Frequency:</b> Eat 4-5 meals throughout the day to ensure a steady <br>
                supply of nutrients for muscle growth. <br>
                <b>Hydration:</b> Drink at least 2.5-3 liters of water daily, especially <br>
                around workouts. <br>
                <b>Sample Meal Plan:</b> <br>
                <b>Meal 1 (Breakfast - 7 AM)</b> <br>
                <b>Protein and Complex Carbs:</b> <br>
                2 whole eggs and 2 egg whites scrambled with spinach, tomatoes, <br>
                and onions. <br>
                1 cup of oatmeal with a tablespoon of almond butter or peanut butter. <br>
                1 small banana or a handful of mixed berries. <br>
                1 glass of low-fat milk or a protein shake. <br>
                <b>Meal 2 (Mid-Morning Snack - 10 AM)</b> <br>
                <b>Protein and Healthy Fats:</b> <br>
                1 cup of Greek yogurt with honey and a sprinkle of granola. <br>
                A handful of mixed nuts or a slice of whole-grain toast with avocado. <br>
                <b>Meal 3 (Lunch - 1 PM)</b> <br>
                <b>Lean Protein + Complex Carbs:</b> <br>
                Grilled chicken breast, turkey, or lean beef (150g). <br>
                1 cup of brown rice, quinoa, or whole-grain pasta. <br>
                A large mixed salad with leafy greens, cucumbers, cherry tomatoes, <br>
                and a light vinaigrette. <br>
                Steamed vegetables like broccoli, cauliflower, or green beans. <br>
                <b>Meal 4 (Afternoon Snack - 4 PM)</b> <br>
                <b>Muscle-Building Snack:</b> <br>
                Cottage cheese with a handful of mixed nuts. <br>
                A protein smoothie with spinach, a banana, and a scoop of protein <br>
                powder. <br>
                <b>Meal 5 (Dinner - 7 PM)</b> <br>
                <b>Protein and Veggies:</b> <br>
                Grilled salmon, chicken, or lean beef (150g). <br>
                1 large sweet potato or mashed potatoes. <br>
                Roasted vegetables like asparagus, Brussels sprouts, or zucchini. <br>
                A small side of brown rice or quinoa if needed. <br>
                <b>Meal 6 (Evening Snack - 9 PM)</b> <br>
                <b>Slow-Digesting Protein:</b> <br>
                Casein protein shake or a serving of cottage cheese. <br>
                A small handful of walnuts or a tablespoon of chia seeds. <br>
                <b>Additional Tips:</b> <br>
                <b>Exercise:</b> Engage in strength training 3-5 times a week, focusing on <br>
                compound movements like squats, deadlifts, and bench presses. <br>
                Incorporate moderate cardio to support cardiovascular health. <br>
                <b>Recovery:</b> Prioritize rest and recovery, including 7-8 hours of <br>
                sleep per night. <br>
                <b>Supplements:</b> Consider using whey protein, creatine, and branched-chain <br>
                amino acids (BCAAs) to support muscle growth, but consult with a <br>
                healthcare professional before starting any new supplement.
                '];
            }         
        }
    }
    return($desc); 
}


// no DB checking ... these will be always from AI. 
// might need to get a fast GPU when launching on the actual server.
function requestGpt($weight, $height, $age, $gender, $goal, $stress, $sleep, $context) {

    if($context == 'Meal') {
        $dbOutMeal = dbMealCon($weight, $height, $age, $gender, $goal, $stress, $sleep);
        if(!empty($dbOutMeal['meal'])){
            $dbOutMealRow = $dbOutMeal;
        } else {
            $dbOutMealRow['meal'] = '';
        }
    } else {
        $dbOutMealRow['meal'] = '';
    }
    if(!empty($dbOutMealRow['meal'])) { // use the entry by the user
        $desc = [$dbOutMealRow['meal']];
    } else { 
        $desc = [''];
    }
    $_SESSION[$context]['meal']    = json_encode($desc); // this is either empty or it is already filled by the language model
    $_SESSION[$context]['gender']  = $gender; 
    $_SESSION[$context]['age']     = $age; 
    $_SESSION[$context]['height']  = $height; 
    $_SESSION[$context]['weight']  = $weight; 
    $_SESSION[$context]['goal']    = $goal; 
    $_SESSION[$context]['stress']  = $stress; 
    $_SESSION[$context]['sleep']   = $sleep;
    $_SESSION[$context]['type']    = $context;
}
?>