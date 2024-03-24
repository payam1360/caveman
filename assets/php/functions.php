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
                    $height = explode('-', $Userheight);
                    $Userheight = ft2in(intval($height[0])) + intval($height[1]); // hright in inches
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
                $Usergoal = 'increase testosterone';
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
            $BMI['desc'] = requestdB($BMI['val'], $Userage, $Usergender, $Usergoal, $data[0]->userId, $data[0]->clientId, 'Bmi');
        }
    } else {
        $BMI['val']  = [];
        $BMI['desc'] = [];
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
            $stressFactor = 1.65;
        } else {
            $stressFactor = 2.25;
        }
        if(str_contains($Usergender, 'female')) {
            $BMR['val'] = $stressFactor * ( 655.1 + (9.56 * $Userweight) + (1.85 * $Userheight) - (4.7 * $Userage));
        } else {
            $BMR['val'] = $stressFactor * ( 66.47 + (13.75 * $Userweight) + (5.003 * $Userheight) - (6.75 * $Userage));
        }
        if($data[0]->nutritionEng == "0") { // AI request has priority 
            $BMR['desc'] = requestGpt($Userweight, $Userheight, $Userage, $Usergender, $Usergoal, $Userstress, $Usersleep, 'Bmr'); //["This text should be generated using AI request!"];
        } elseif($data[0]->nutritionEng == "1") { // check dB, if exists, use it <- nutritionist, otherwise use software
            $BMR['desc'] = requestdB($BMI['val'], $Userage, $Usergender, $Usergoal, $data[0]->userId, $data[0]->clientId, 'Bmr');
        }

    } else {
        $BMR['val'] = [];
        $BMR['desc'] = [];
    }
    return($BMR);
}

function calculateIf($data){
    // this function is the algorithm for calculating intervals in which the user is 
    // recommended for fasting. Fasting increase IGF or growth hormons.  
    // calculate intermittent fasting interval
    $IF['val']  = [[24,24,24,24,24,24,24],[0,0,0,0,0,0,0]];
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
        if($Userweight >= 70 && $Userweight < 120) {
            if(str_contains($Usergender, 'female')){
                if(str_contains($Usergoal, 'lose')) {
                    $IF['val'] = [[24,24,24,24,16,24,8],[0,0,0,0,8,0,16]];
                } elseif(str_contains($Usergoal, 'gain')) {
                    $IF['val'] = [[24,24,24,24,16,24,8],[0,0,0,0,8,0,16]];
                }
            } elseif($Usergender === 'male'){
                if(str_contains($Usergoal, 'lose')) {
                    $IF['val'] = [[24,24,24,24,16,24,8],[0,0,0,0,8,0,16]];
                } elseif(str_contains($Usergoal, 'gain')) {
                    $IF['val'] = [[24,24,24,24,16,24,8],[0,0,0,0,8,0,16]];
                }
            }
        }
        elseif($Userweight >= 120 && $Userweight < 220) {
            if(str_contains($Usergender, 'female')){
                if(str_contains($Usergoal, 'lose')) {
                    $IF['val'] = [[24,24,24,24,16,24,8],[0,0,0,0,8,0,16]];
                } elseif(str_contains($Usergoal, 'gain')) {
                    $IF['val'] = [[24,24,24,24,16,24,8],[0,0,0,0,8,0,16]];
                }
            } elseif($Usergender === 'male'){
                if(str_contains($Usergoal, 'lose')) {
                    $IF['val'] = [[24,24,24,24,16,24,8],[0,0,0,0,8,0,16]];
                } elseif(str_contains($Usergoal, 'gain')) {
                    $IF['val'] = [[24,24,24,24,16,24,8],[0,0,0,0,8,0,16]];
                }
            }
        }
        elseif($Userweight >= 220 && $Userweight < 300) {
            if(str_contains($Usergender, 'female')){
                if(str_contains($Usergoal, 'lose')) {
                    $IF['val'] = [[24,24,24,24,16,24,8],[0,0,0,0,8,0,16]];
                } elseif(str_contains($Usergoal, 'gain')) {
                    $IF['val'] = [[24,24,24,24,16,24,8],[0,0,0,0,8,0,16]];
                }
            } elseif($Usergender === 'male'){
                if(str_contains($Usergoal, 'lose')) {
                    $IF['val'] = [[24,24,24,24,16,24,8],[0,0,0,0,8,0,16]];
                } elseif(str_contains($Usergoal, 'gain')) {
                    $IF['val'] = [[24,24,24,24,16,24,8],[0,0,0,0,8,0,16]];
                }
            }
        }
        if($data[0]->nutritionEng == "0") { // AI request has priority 
            $IF['desc'] = requestGpt($Userweight, $Userheight, $Userage, $Usergender, $Usergoal, $Userstress, $Usersleep, 'If'); //["This text should be generated using AI request!"];
        } elseif($data[0]->nutritionEng == "1") { // check dB, if exists, use it <- nutritionist, otherwise use software
            $IF['desc'] = requestdB($BMI['val'], $Userage, $Usergender, $Usergoal, $data[0]->userId, $data[0]->clientId, 'If');
        }
    } else {
        $IF['val']  = [];
        $IF['desc'] = [];
    }
        // -----------------------------------------------------------
    return($IF);
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
        if(str_contains($Usergender, 'female')){
            if(str_contains($Usergoal, 'lose')) {
                $p = 0.3 * $ageFactor;
                $c = 0.6 * (1 - $ageFactor);
                $f = 0.9 - $p - $c;
                $fi= 0.1;
                $caloriDeficit = 250;
                $Macro['val'] =  [$p * $BMR['val'] - $caloriDeficit, $c * $BMR['val'] - $caloriDeficit, 
                                  $f * $BMR['val'] - $caloriDeficit, $fi * $BMR['val'] - $caloriDeficit]; // [protein, carb, fat];               
            } elseif(str_contains($Usergoal, 'gain')) {
                $p = 0.3 * $ageFactor;
                $c = 0.6 * (1 - $ageFactor);
                $f = 0.9 - $p - $c;
                $fi= 0.1;
                $caloriSurplus = 250;
                $Macro['val'] =  [$p * $BMR['val'] + $caloriSurplus, $c * $BMR['val'] + $caloriSurplus, 
                                  $f * $BMR['val'] + $caloriSurplus, $fi * $BMR['val'] - $caloriSurplus]; // [protein, carb, fat];                 
            } else {
                $p = 0.3 * $ageFactor;
                $c = 0.6 * (1 - $ageFactor);
                $f = 0.9 - $p - $c;
                $fi= 0.1;
                $caloriSurplus = 250;
                $Macro['val'] =  [$p * $BMR['val'] + $caloriSurplus, $c * $BMR['val'] + $caloriSurplus, 
                                  $f * $BMR['val'] + $caloriSurplus, $fi * $BMR['val'] - $caloriSurplus]; // [protein, carb, fat];            
            }
        } elseif($Usergender === 'male'){
            if(str_contains($Usergoal, 'lose')) {
                $p = 0.2 * $ageFactor;
                $c = 0.65 * (1 - $ageFactor);
                $f = 0.9 - $p - $c;
                $fi= 0.1;
                $caloriDeficit = 250;
                $Macro['val'] =  [$p * $BMR['val'] - $caloriDeficit, $c * $BMR['val'] - $caloriDeficit, 
                                  $f * $BMR['val'] - $caloriDeficit, $fi * $BMR['val'] - $caloriDeficit]; // [protein, carb, fat];
            } elseif(str_contains($Usergoal, 'gain')) {
                $p = 0.25 * $ageFactor;
                $c = 0.65 * (1 - $ageFactor);
                $f = 0.9 - $p - $c;
                $fi= 0.1;
                $caloriSurplus = 250;
                $Macro['val'] =  [$p * $BMR['val'] + $caloriSurplus, $c * $BMR['val'] + $caloriSurplus, 
                                  $f * $BMR['val'] + $caloriSurplus, $fi * $BMR['val'] - $caloriSurplus]; // [protein, carb, fat];
            } else {
                $p = 0.25 * $ageFactor;
                $c = 0.65 * (1 - $ageFactor);
                $f = 0.9 - $p - $c;
                $fi= 0.1;
                $caloriSurplus = 250;
                $Macro['val'] =  [$p * $BMR['val'] + $caloriSurplus, $c * $BMR['val'] + $caloriSurplus, 
                                  $f * $BMR['val'] + $caloriSurplus, $fi * $BMR['val'] - $caloriSurplus]; // [protein, carb, fat];
            }
        } 
        if($data[0]->nutritionEng == "0") { // AI request has priority 
            $Macro['desc'] = requestGpt($Userweight, $Userheight, $Userage, $Usergender, $Usergoal, $Userstress, $Usersleep, 'Macro'); //["This text should be generated using AI request!"];
        } elseif($data[0]->nutritionEng == "1") { // check dB, if exists, use it <- nutritionist, otherwise use software
            $Macro['desc'] = requestdB($BMI['val'], $Userage, $Usergender, $Usergoal, $data[0]->userId, $data[0]->clientId, 'Macro');
        }
    } else {
        $Macro['val'] = [];
        $Macro['desc']  = [];
    } 
    return($Macro);
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
            $Micro['descVit']   = requestdB($BMI['val'], $Userage, $Usergender, $Usergoal, $data[0]->userId, $data[0]->clientId, 'MicroVit');
            $Micro['descTrace'] = requestdB($BMI['val'], $Userage, $Usergender, $Usergoal, $data[0]->userId, $data[0]->clientId, 'MicroTrace');
        }
    } else {
        $Micro['descVit']    = [];
        $Micro['descTrace']  = [];
    } 

    return($Micro);
}

function dataPrep($user_bmi, $user_bmr, $user_if, $user_macro, $user_micro){
    $data = array('status' => 0,
                 'bmi'   => $user_bmi,
                 'bmr'   => $user_bmr,
                 'if'    => $user_if,
                 'macro' => $user_macro,
                 'micro' => $user_micro);
    return $data;
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
    $database_out = $conn->query($sql);
    return($database_out);
}


function requestdB($Bmi, $Userage, $Usergender, $Usergoal, $userId, $clientId, $contextFlag){
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
                $desc = ['As a young male whose objective is to lose weight, you need to combine routine workout and healthy diet regiment to bring down your BMI.'];
            }
            if($HighYoungMaleGain) {
                $desc = ['As a young male whose objective is to gain weight, you need strict training programs for gaining more muscle mass.'];
            }
            if($HighYoungFemaleLose) {
                $desc = ['As a young female whose objective is to lose weight, you need strict training programs as well as low carb diet to reach your goals in a reasonable amount of time.'];
            }
            if($HighYoungFemaleGain) {
                $desc = ['As a young female with high BMI, your objective should be to have a balanced low carb diet combined with great training program in order to achieve your goal to increase your BMI further. The assumption here is that you are a professional athlete.'];
            }
            // -------
            if($LowYoungMaleLose) {
                $desc = ['As a young male with low BMI, your objective should not be lose further weight. Instead, try to increase muscle mass by proper nutrition and high intensity interval training.'];
            }
            if($LowYoungMaleGain) {
                $desc = ['As a young male with low BMI, to increase muscle mass you need to have proper nutrition with slight calorie surplus and high intensity interval training to reach your goals.'];
            }
            if($LowYoungFemaleLose) {
                $desc = ['As a young female with low BMI, it is not recommended to further lower your body mass index value. Instead you can stay steay by having a balanced diet and frequent exercise.'];
            }
            if($LowYoungFemaleGain) {
                $desc = ['As a young female with low BMI, it is recommended to have a slight calorie surplus diet and frequent exercises to reach your goals of gaining more muscle mass.'];
            }

            if($HighOldMaleLose) {
                $desc = ['As a male whose objective is to lose weight, you need to combine routine workout and healthy diet regiment to bring down your BMI.'];
            }
            if($HighOldMaleGain) {
                $desc = ['As a male whose objective is to gain weight, you need strict training programs for gaining more muscle mass.'];
            }
            if($HighOldFemaleLose) {
                $desc = ['As a female whose objective is to lose weight, you need strict training programs as well as low carb diet to reach your goals in a reasonable amount of time.'];
            }
            if($HighOldFemaleGain) {
                $desc = ['As a female with high BMI, your objective should be to have a balanced low carb diet combined with great training program in order to achieve your goal to increase your BMI further. The assumption here is that you are a professional athlete.'];
            }
            // -------
            if($LowOldMaleLose) {
                $desc = ['As a male with low BMI, your objective should not be lose further weight. Instead, try to increase muscle mass by proper nutrition and high intensity interval training.'];
            }
            if($LowOldMaleGain) {
                $desc = ['As a male with low BMI, to increase muscle mass you need to have proper nutrition with slight calorie surplus and high intensity interval training to reach your goals.'];
            }
            if($LowOldFemaleLose) {
                $desc = ['As a female with low BMI, it is not recommended to further lower your body mass index value. Instead you can stay steay by having a balanced diet and frequent exercise.'];
            }
            if($LowOldFemaleGain) {
                $desc = ['As a female with low BMI, it is recommended to have a slight calorie surplus diet and frequent exercises to reach your goals of gaining more muscle mass.'];
            }
        }
    } elseif($contextFlag == "Bmr") {
        if(!empty($dbOutRow['descBmr'])) { // use the entry by the user
            $desc = [$dbOutRow['descBmr']];
        } else { 
            $desc = ['This number gives an estimate of how much calories you need per day at your current activity / stress levels', 
            'to reach your fitness goals.'];
        }
    } elseif($contextFlag == "If") {
        if(!empty($dbOutRow['descIf'])) { // use the entry by the user
            $desc = [$dbOutRow['descIf']];
        } else { 
            if($HighYoungMaleLose) {
                $desc = ['As a young male whose objective is to lose weight, it is recommended to keep the eating window per day to a minimum.'];
            }
            if($HighYoungMaleGain) {
                $desc = ['As a young male whose objective is to gain weight, given your BMI is high, you can consider fasting few days per week to keep your insulin resistance to a minimum.'];
            }
            if($HighYoungFemaleLose) {
                $desc = ['As a young female whose objective is to lose weight, it is recomended to keep your eating window to a minimum. This will help you with insulin resistance.'];
            }
            if($HighYoungFemaleGain) {
                $desc = ['As a young female with high BMI, you could benefit from intermitent fasting to maximize your insulin sensitivity.'];
            }
            // -------
            if($LowYoungMaleLose) {
                $desc = ['As a young male with low BMI, normal eating windows are recommended.'];
            }
            if($LowYoungMaleGain) {
                $desc = ['As a young male with low BMI, to increase muscle mass, you can fast few times a week to maximize your gains by increasing your insulin sensitivity.'];
            }
            if($LowYoungFemaleLose) {
                $desc = ['As a young female with low BMI, you should increase your calorie intake but incorporate intermttent fasting will increase your insulin sensitivity and therefore maximize your nutrition intake.'];
            }
            if($LowYoungFemaleGain) {
                $desc = ['As a young female with low BMI looking to gain more muscle, routine intermittent fasting is benefitial for you as recommended in the chart above.'];
            }
            if($HighOldMaleLose) {
                $desc = ['As a male whose objective is to lose weight, it is recommended to keep the eating window per day to a minimum.'];
            }
            if($HighOldMaleGain) {
                $desc = ['As a male whose objective is to gain weight, given your BMI is high, you can consider fasting few days per week to keep your insulin resistance to a minimum.'];
            }
            if($HighOldFemaleLose) {
                $desc = ['As a female whose objective is to lose weight, it is recomended to keep your eating window to a minimum. This will help you with insulin resistance.'];
            }
            if($HighOldFemaleGain) {
                $desc = ['As a female with high BMI, you could benefit from intermitent fasting to maximize your insulin sensitivity.'];
            }
            // -------
            if($LowOldMaleLose) {
                $desc = ['As a male with low BMI, normal eating windows are recommended.'];
            }
            if($LowOldMaleGain) {
                $desc = ['As a male with low BMI, to increase muscle mass, you can fast few times a week to maximize your gains by increasing your insulin sensitivity.'];
            }
            if($LowOldFemaleLose) {
                $desc = ['As a female with low BMI, you should increase your calorie intake but incorporate intermttent fasting will increase your insulin sensitivity and therefore maximize your nutrition intake.'];
            }
            if($LowOldFemaleGain) {
                $desc = ['As a female with low BMI, looking to gain more muscle, routine intermittent fasting is benefitial for you as recommended in the chart above.'];
            }
        }
    } elseif($contextFlag == "Macro") {
        if(!empty($dbOutRow['descMacro'])) { // use the entry by the user
            $desc = [$dbOutRow['descMacro']];
        } else { 
            if($HighYoungMaleLose) {
                $desc = ['Recomended macro for a male with high BMI trying to lose weight, is to keep protein and fat intake high. Carbs should be in the form of leafy greens to keep insulin spike in check.'];
            }
            if($HighYoungMaleGain) {
                $desc = ['As a young male trying to gain more muscle who is already on the higher end of BMI scale, it is recommended to keep carbs intake at the minimum.'];
            }
            if($HighYoungFemaleLose) {
                $desc = ['Avoid starchy carbs like rice. Stick with leafy greens and increase your fat and protein intake instead.'];
            }
            if($HighYoungFemaleGain) {
                $desc = ['You are already on the higher side of BMI. It is assumed you are an athlete and therefore, high protein intake is recommended. This also depends on your workout routine and stress levels.'];
            }
            // -------
            if($LowYoungMaleLose) {
                $desc = ['As a young male who is on the lower side of BMI, trying to lose more weight is not a great option for your overall health. You should take enough protein and more carbs in the form of vegetables.'];
            }
            if($LowYoungMaleGain) {
                $desc = ['Increase your fat and protein intake. Avoid starchy carbs and replace with good carbs from leafy greens.'];
            }
            if($LowYoungFemaleLose) {
                $desc = ['It is not recommended to lose more weight since your BMI, as a young woman, is already on the lower side. Increase your carbs in the form of vegetables.'];
            }
            if($LowYoungFemaleGain) {
                $desc = ['Increase your calorie intake by increasing your protein and carbs in the form of leafy greens.'];
            }
            if($HighOldMaleLose) {
                $desc = ['Recomended macro for a male with high BMI trying to lose weight, is to keep protein and fat intake high. Carbs should be in the form of leafy greens to keep insulin spike in check.'];
            }
            if($HighOldMaleGain) {
                $desc = ['As an adult male trying to gain more muscle who is already on the higher end of BMI scale, it is recommended to keep carbs intake at the minimum.'];
            }
            if($HighOldFemaleLose) {
                $desc = ['Avoid starchy carbs like rice. Stick with leafy greens and increase your fat and protein intake instead.'];
            }
            if($HighOldFemaleGain) {
                $desc = ['You are already on the higher side of BMI. It is assumed you are an athlete and therefore, high protein intake is recommended. This also depends on your workout routine and stress levels.'];
            }
            // -------
            if($LowOldMaleLose) {
                $desc = ['As an adult male who is on the lower side of BMI, trying to lose more weight is not a great option for your overall health. You should take enough protein and more carbs in the form of vegetables.'];
            }
            if($LowOldMaleGain) {
                $desc = ['Increase your fat and protein intake. Avoid starchy carbs and replace with good carbs from leafy greens.'];
            }
            if($LowOldFemaleLose) {
                $desc = ['It is not recommended to lose more weight since your BMI, as an adult woman, is already on the lower side. Increase your carbs in the form of vegetables.'];
            }
            if($LowOldFemaleGain) {
                $desc = ['Increase your calorie intake by increasing your protein and carbs in the form of leafy greens.'];
            }
        }
    } elseif($contextFlag == "MicroVit") {
        if(!empty($dbOutRow['descMicroVit'])) { // use the entry by the user
            $desc = [$dbOutRow['descMicroVit']];
        } else { 
            if($HighYoungMaleLose) {
                $desc = ['Vitamins are not made in our bodies and need to be consumed from food. Generally, the amount needed is determined by gender and age. Fat soluable vitamins can be accumulated in our bodies but water soluble vitamins are not and any excess amount are flushed out.'];
            }
            if($HighYoungMaleGain) {
                $desc = ['Vitamins are not made in our bodies and need to be consumed from food. Generally, the amount needed is determined by gender and age. Fat soluable vitamins can be accumulated in our bodies but water soluble vitamins are not and any excess amount are flushed out.'];
            }
            if($HighYoungFemaleLose) {
                $desc = ['Vitamins are not made in our bodies and need to be consumed from food. Generally, the amount needed is determined by gender and age. Fat soluable vitamins can be accumulated in our bodies but water soluble vitamins are not and any excess amount are flushed out.'];
            }
            if($HighYoungFemaleGain) {
                $desc = ['Vitamins are not made in our bodies and need to be consumed from food. Generally, the amount needed is determined by gender and age. Fat soluable vitamins can be accumulated in our bodies but water soluble vitamins are not and any excess amount are flushed out.'];
            }
            // -------
            if($LowYoungMaleLose) {
                $desc = ['Vitamins are not made in our bodies and need to be consumed from food. Generally, the amount needed is determined by gender and age. Fat soluable vitamins can be accumulated in our bodies but water soluble vitamins are not and any excess amount are flushed out.'];
            }
            if($LowYoungMaleGain) {
                $desc = ['Vitamins are not made in our bodies and need to be consumed from food. Generally, the amount needed is determined by gender and age. Fat soluable vitamins can be accumulated in our bodies but water soluble vitamins are not and any excess amount are flushed out.'];
            }
            if($LowYoungFemaleLose) {
                $desc = ['Vitamins are not made in our bodies and need to be consumed from food. Generally, the amount needed is determined by gender and age. Fat soluable vitamins can be accumulated in our bodies but water soluble vitamins are not and any excess amount are flushed out.'];
            }
            if($LowYoungFemaleGain) {
                $desc = ['Vitamins are not made in our bodies and need to be consumed from food. Generally, the amount needed is determined by gender and age. Fat soluable vitamins can be accumulated in our bodies but water soluble vitamins are not and any excess amount are flushed out.'];
            }
            if($HighOldMaleLose) {
                $desc = ['Vitamins are not made in our bodies and need to be consumed from food. Generally, the amount needed is determined by gender and age. Fat soluable vitamins can be accumulated in our bodies but water soluble vitamins are not and any excess amount are flushed out.'];
            }
            if($HighOldMaleGain) {
                $desc = ['Vitamins are not made in our bodies and need to be consumed from food. Generally, the amount needed is determined by gender and age. Fat soluable vitamins can be accumulated in our bodies but water soluble vitamins are not and any excess amount are flushed out.'];
            }
            if($HighOldFemaleLose) {
                $desc = ['Vitamins are not made in our bodies and need to be consumed from food. Generally, the amount needed is determined by gender and age. Fat soluable vitamins can be accumulated in our bodies but water soluble vitamins are not and any excess amount are flushed out.'];
            }
            if($HighOldFemaleGain) {
                $desc = ['Vitamins are not made in our bodies and need to be consumed from food. Generally, the amount needed is determined by gender and age. Fat soluable vitamins can be accumulated in our bodies but water soluble vitamins are not and any excess amount are flushed out.'];
            }
            // -------
            if($LowOldMaleLose) {
                $desc = ['Vitamins are not made in our bodies and need to be consumed from food. Generally, the amount needed is determined by gender and age. Fat soluable vitamins can be accumulated in our bodies but water soluble vitamins are not and any excess amount are flushed out.'];
            }
            if($LowOldMaleGain) {
                $desc = ['Vitamins are not made in our bodies and need to be consumed from food. Generally, the amount needed is determined by gender and age. Fat soluable vitamins can be accumulated in our bodies but water soluble vitamins are not and any excess amount are flushed out.'];
            }
            if($LowOldFemaleLose) {
                $desc = ['Vitamins are not made in our bodies and need to be consumed from food. Generally, the amount needed is determined by gender and age. Fat soluable vitamins can be accumulated in our bodies but water soluble vitamins are not and any excess amount are flushed out.'];
            }
            if($LowOldFemaleGain) {
                $desc = ['Vitamins are not made in our bodies and need to be consumed from food. Generally, the amount needed is determined by gender and age. Fat soluable vitamins can be accumulated in our bodies but water soluble vitamins are not and any excess amount are flushed out.'];
            }
        }
    } elseif($contextFlag == "MicroTrace") {
        if(!empty($dbOutRow['descMicroTrace'])) { // use the entry by the user
            $desc = [$dbOutRow['descMicroTrace']];
        } else { 
            if($HighYoungMaleLose) {
                $desc = ['Trace minerals should be consumed in tiny doses. The recomended daily doses are presented for you in the above chart.'];
            }
            if($HighYoungMaleGain) {
                $desc = ['Trace minerals should be consumed in tiny doses. The recomended daily doses are presented for you in the above chart.'];
            }
            if($HighYoungFemaleLose) {
                $desc = ['Trace minerals should be consumed in tiny doses. The recomended daily doses are presented for you in the above chart.'];
            }
            if($HighYoungFemaleGain) {
                $desc = ['Trace minerals should be consumed in tiny doses. The recomended daily doses are presented for you in the above chart.'];
            }
            // -------
            if($LowYoungMaleLose) {
                $desc = ['Trace minerals should be consumed in tiny doses. The recomended daily doses are presented for you in the above chart.'];
            }
            if($LowYoungMaleGain) {
                $desc = ['Trace minerals should be consumed in tiny doses. The recomended daily doses are presented for you in the above chart.'];
            }
            if($LowYoungFemaleLose) {
                $desc = ['Trace minerals should be consumed in tiny doses. The recomended daily doses are presented for you in the above chart.'];
            }
            if($LowYoungFemaleGain) {
                $desc = ['Trace minerals should be consumed in tiny doses. The recomended daily doses are presented for you in the above chart.'];
            }
            if($HighOldMaleLose) {
                $desc = ['Trace minerals should be consumed in tiny doses. The recomended daily doses are presented for you in the above chart.'];
            }
            if($HighOldMaleGain) {
                $desc = ['Trace minerals should be consumed in tiny doses. The recomended daily doses are presented for you in the above chart.'];
            }
            if($HighOldFemaleLose) {
                $desc = ['Trace minerals should be consumed in tiny doses. The recomended daily doses are presented for you in the above chart.'];
            }
            if($HighOldFemaleGain) {
                $desc = ['Trace minerals should be consumed in tiny doses. The recomended daily doses are presented for you in the above chart.'];
            }
            // -------
            if($LowOldMaleLose) {
                $desc = ['Trace minerals should be consumed in tiny doses. The recomended daily doses are presented for you in the above chart.'];
            }
            if($LowOldMaleGain) {
                $desc = ['Trace minerals should be consumed in tiny doses. The recomended daily doses are presented for you in the above chart.'];
            }
            if($LowOldFemaleLose) {
                $desc = ['Trace minerals should be consumed in tiny doses. The recomended daily doses are presented for you in the above chart.'];
            }
            if($LowOldFemaleGain) {
                $desc = ['Trace minerals should be consumed in tiny doses. The recomended daily doses are presented for you in the above chart.'];
            } 
        } 
    } 
    return($desc); 
}


// no DB checking ... these will be always from AI. 
// might need to get a fast GPU when launching on the actual server.
function requestGpt($weight, $height, $age, $gender, $goal, $stress, $sleep, $context) {
    $_SESSION[$context]['meal']    = json_encode(''); // this is either empty or it is already filled by the language model
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