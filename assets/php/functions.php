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
                $Usergoal = 'gain';
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
    $CAL['val']  = [[1200,1200,1200,1200,1200,1200,1200,1200]];
    $CAL['desc'] = [''];
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
                    $CAL['val'] = [[1550,1500,1450,1400,1350,1300,1250,1200]];
                } elseif(str_contains($Usergoal, 'gain')) {
                    $CAL['val'] = [[1200,1250,1300,1350,1400,1450,1500,1550]];
                }
            } elseif($Usergender === 'male'){
                if(str_contains($Usergoal, 'lose')) {
                    $CAL['val'] = [[1550,1500,1450,1400,1350,1300,1250,1200]];
                } elseif(str_contains($Usergoal, 'gain')) {
                    $CAL['val'] = [[1200,1250,1300,1350,1400,1450,1500,1550]];
                }
            }
        }
        elseif($Userweight >= 120 && $Userweight < 220) {
            if(str_contains($Usergender, 'female')){
                if(str_contains($Usergoal, 'lose')) {
                    $CAL['val'] = [[1550,1500,1450,1400,1350,1300,1250,1200]];
                } elseif(str_contains($Usergoal, 'gain')) {
                    $CAL['val'] = [[1200,1250,1300,1350,1400,1450,1500,1550]];
                }
            } elseif($Usergender === 'male'){
                if(str_contains($Usergoal, 'lose')) {
                    $CAL['val'] = [[1550,1500,1450,1400,1350,1300,1250,1200]];
                } elseif(str_contains($Usergoal, 'gain')) {
                    $CAL['val'] = [[1200,1250,1300,1350,1400,1450,1500,1550]];
                }
            }
        }
        elseif($Userweight >= 220 && $Userweight < 300) {
            if(str_contains($Usergender, 'female')){
                if(str_contains($Usergoal, 'lose')) {
                    $CAL['val'] = [[1550,1500,1450,1400,1350,1300,1250,1200]];
                } elseif(str_contains($Usergoal, 'gain')) {
                    $CAL['val'] = [[1200,1250,1300,1350,1400,1450,1500,1550]];
                }
            } elseif($Usergender === 'male'){
                if(str_contains($Usergoal, 'lose')) {
                    $CAL['val'] = [[1550,1500,1450,1400,1350,1300,1250,1200]];
                } elseif(str_contains($Usergoal, 'gain')) {
                    $CAL['val'] = [[1200,1250,1300,1350,1400,1450,1500,1550]];
                }
            }
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
                $desc = ['BMI Overview for Young Males Under 35
                What is BMI?
                Body Mass Index (BMI) estimates body fat based on height and weight.
                BMI Categories: Normal (18.5-24.9), Overweight (25-29.9), Obese (30+).
                High BMI Concerns
                High BMI can lead to health risks like heart disease, diabetes, and high blood pressure.
                Weight Loss Tips
                Set Goals: Aim to lose 1-2 pounds per week.
                Healthy Eating: Choose whole foods, reduce sugar and fat intake.
                Exercise: At least 150 minutes of moderate activity weekly, plus strength training.
                Hydration: Drink plenty of water.
                Sleep: Get 7-9 hours of sleep per night.
                Track Progress: Monitor weight and habits.
                Seek Support: Consult healthcare providers for guidance.
                Stay patient and consistent for long-term success.'];
            }
            if($HighYoungMaleGain) {
                $desc = ['BMI Overview for Young Males Under 35
                What is BMI?
                Body Mass Index (BMI) estimates body fat based on height and weight.
                BMI Categories: Normal (18.5-24.9), Overweight (25-29.9), Obese (30+).
                High BMI and Muscle Gain
                High BMI can include muscle mass, not just fat.
                Muscle mass gain involves strength training and proper nutrition.
                Muscle Gain Tips
                Strength Training: Focus on compound exercises (squats, deadlifts, bench press) 3-4 times a week.
                Nutrition: Increase protein intake (chicken, fish, beans), eat balanced meals.
                Caloric Surplus: Consume more calories than you burn, focusing on healthy foods.
                Hydration: Drink plenty of water to support muscle recovery.
                Rest: Ensure 7-9 hours of sleep per night for muscle repair.
                Track Progress: Monitor muscle growth and adjust your plan as needed.
                Professional Advice: Consider consulting a fitness trainer or nutritionist for personalized guidance.
                Consistency and proper technique are key for effective muscle gain.'];
            }
            if($HighYoungFemaleLose) {
                $desc = ['BMI Overview for Young Females Under 35
                What is BMI?
                Body Mass Index (BMI) estimates body fat based on height and weight.
                BMI Categories: Normal (18.5-24.9), Overweight (25-29.9), Obese (30+).
                High BMI Concerns
                High BMI can lead to health risks like heart disease, diabetes, and high blood pressure.
                Weight Loss Tips
                Set Goals: Aim to lose 1-2 pounds per week.
                Healthy Eating: Choose whole foods, reduce sugar and fat intake.
                Exercise: At least 150 minutes of moderate activity weekly, plus strength training.
                Hydration: Drink plenty of water.
                Sleep: Get 7-9 hours of sleep per night.
                Track Progress: Monitor weight and habits.
                Seek Support: Consult healthcare providers for guidance.
                Patience and consistency are key to successful weight loss.'];
            }
            if($HighYoungFemaleGain) {
                $desc = ['BMI Overview for Young Females Under 35
                What is BMI?
                Body Mass Index (BMI) estimates body fat based on height and weight.
                BMI Categories: Normal (18.5-24.9), Overweight (25-29.9), Obese (30+).
                High BMI and Muscle Gain
                High BMI can include muscle mass, not just fat.
                Muscle gain requires strength training and proper nutrition.
                Muscle Gain Tips
                Strength Training: Focus on compound exercises (squats, deadlifts, bench press) 3-4 times a week.
                Nutrition: Increase protein intake (chicken, fish, beans), eat balanced meals.
                Caloric Surplus: Consume more calories than you burn, focusing on healthy foods.
                Hydration: Drink plenty of water to support muscle recovery.
                Rest: Ensure 7-9 hours of sleep per night for muscle repair.
                Track Progress: Monitor muscle growth and adjust your plan as needed.
                Professional Advice: Consider consulting a fitness trainer or nutritionist for personalized guidance.
                Consistency and proper technique are key for effective muscle gain.'];
            }
            // -------
            if($LowYoungMaleLose) {
                $desc = ['For a young male under 35 with a low BMI trying to lose weight: 
                "Underweight. Focus on gaining lean muscle mass through strength 
                training and a balanced, nutrient-rich diet. Consult a healthcare 
                professional for personalized guidance.'];
            }
            if($LowYoungMaleGain) {
                $desc = ['BMI Description and Definition
                Body Mass Index (BMI) is a measure that uses height and weight to estimate 
                body fat. It is calculated by dividing a persons weight in kilograms by 
                the square of their height in meters (kg/m²). 
                The BMI categories are:
                Underweight: BMI less than 18.5
                Normal weight: BMI 18.5-24.9
                Overweight: BMI 25-29.9
                Obesity: BMI ≥ 30
                Advice for a Young Male Under 35 with Low BMI Trying to Gain Muscle Mass
                Nutritional Guidance:
                Increase Caloric Intake: Consume more calories than you burn. Aim for a 
                calorie surplus by eating nutrient-dense foods.
                Balanced Diet: Focus on a balanced diet with a mix of carbohydrates, 
                proteins, and fats. Include plenty of fruits, vegetables, whole grains, 
                and lean proteins.
                Protein-Rich Foods: Incorporate high-protein foods such as chicken, 
                beef, fish, eggs, dairy products, legumes, and protein supplements to 
                support muscle growth.
                Healthy Fats: Include healthy fats from sources like avocados, nuts, 
                seeds, and olive oil to help increase your calorie intake.
                Exercise and Training:
                Strength Training: Engage in regular strength training exercises, 
                focusing on compound movements like squats, deadlifts, bench presses, 
                and pull-ups. These exercises target multiple muscle groups and promote 
                muscle growth.
                Progressive Overload: Gradually increase the weight and intensity of 
                your workouts to challenge your muscles and encourage growth.
                Consistency: Stick to a regular workout schedule, aiming for at least 
                3-4 strength training sessions per week.
                Rest and Recovery:
                Adequate Sleep: Ensure you get 7-9 hours of sleep per night to allow 
                your muscles to recover and grow.
                Rest Days: Incorporate rest days into your training routine to prevent
                overtraining and reduce the risk of injury.
                Hydration:
                Drink plenty of water throughout the day to stay hydrated, as dehydration 
                can hinder muscle recovery and performance.
                Supplements:
                Protein Supplements: Consider protein powders, such as whey or 
                plant-based options, to help meet your daily protein requirements.
                Creatine: This supplement can improve strength and muscle mass when 
                combined with a proper training regimen.
                Monitoring Progress:
                Track Your Intake: Keep a food diary or use a nutrition app to monitor 
                your daily caloric and protein intake.
                Measure Progress: Track changes in your body composition, strength, and
                 weight to assess your progress and make necessary adjustments to 
                 your diet and exercise plan. Professional Guidance
                Consider consulting with a nutritionist or personal trainer to 
                create a personalized plan tailored to your specific needs and goals. 
                They can provide expert advice and ensure that you are following a 
                safe and effective regimen.'];
            }
            if($LowYoungFemaleLose) {
                $desc = ['Body Mass Index (BMI) is a measure that uses height and weight 
                to estimate body fat. It is calculated by dividing a persons 
                weight in kilograms by the square of their height in meters (kg/m²). 
                The BMI categories are:
                Underweight: BMI less than 18.5
                Normal weight: BMI 18.5-24.9
                Overweight: BMI 25-29.9
                Obesity: BMI more than 30
                Advice
                If you already have a low BMI (under 18.5) and are considering losing 
                more weight, it is important to prioritize your health. Further weight 
                loss could lead to various health issues, including nutrient 
                deficiencies, weakened immune system, and decreased muscle mass. It is 
                strongly recommended to consult with a healthcare provider or a 
                nutritionist to ensure your health and well-being.'];
            }
            if($LowYoungFemaleGain) {
                $desc = ['BMI Description for a Young Female Under 35 with Low BMI 
                Trying to Gain Muscle Mass
                Body Mass Index (BMI) is a measure that uses height and weight to 
                estimate body fat. It is calculated by dividing a persons weight 
                in kilograms by the square of their height in meters (kg/m²). The 
                BMI categories are:
                Underweight: BMI less than 18.5
                Normal weight: BMI 18.5-24.9
                Overweight: BMI 25-29.9
                Obesity: BMI more than 30
                Advice
                For a young female with a low BMI looking to gain muscle mass:
                Increase Caloric Intake: Eat more calories than you burn, focusing 
                on nutrient-dense foods.
                Consume Protein: Include high-protein foods like lean meats, dairy, 
                legumes, and protein supplements.
                Strength Training: Engage in regular strength training exercises to
                 build muscle.
                Rest and Recover: Ensure adequate sleep and rest days to support
                 muscle growth.
                Always consider consulting a healthcare provider or nutritionist for 
                personalized guidance.'];
            }

            if($HighOldMaleLose) {
                $desc = ['BMI Description for an Older Male Above 40 with High BMI 
                Trying to Lose Weight
                Body Mass Index (BMI) is a measure of body fat based on height and 
                weight, calculated as weight (kg) divided by height (m²).
                Overweight: BMI 25-29.9
                Obesity: BMI ≥ 30
                Advice
                For an older male with a high BMI:
                Aim for a Caloric Deficit: Consume fewer calories than you burn to 
                promote weight loss.
                Focus on Balanced Nutrition: Include a mix of lean proteins, healthy 
                fats, and complex carbohydrates.
                Incorporate Physical Activity: Engage in regular exercise, including 
                both cardio and strength training.
                Monitor Health: Consider regular check-ups to manage any age-related 
                health issues.
                Consult with a healthcare provider for personalized advice and to 
                ensure a safe weight loss approach.'];
            }
            if($HighOldMaleGain) {
                $desc = ['BMI Description for an Older Male Above 40 with High BMI 
                Trying to Gain Muscle Mass
                Body Mass Index (BMI) is a measure of body fat based on height 
                and weight:
                Overweight: BMI 25-29.9
                Obesity: BMI ≥ 30
                Advice
                For an older male with a high BMI looking to gain muscle mass:
                Increase Caloric Intake: Focus on nutrient-dense, higher-calorie foods 
                to support muscle growth.
                Emphasize Protein: Include high-protein foods to aid muscle development.
                Strength Training: Engage in regular resistance exercises to build muscle.
                Monitor Progress: Track changes in muscle mass and adjust your diet 
                and training as needed.
                Consult with a healthcare provider to ensure a safe and effective 
                approach.'];
            }
            if($HighOldFemaleLose) {
                $desc = ['BMI Description for an Older Female Above 40 with High BMI 
                Trying to Lose Weight
                Body Mass Index (BMI) is a measure of body fat based on height and weight:
                Overweight: BMI 25-29.9
                Obesity: BMI ≥ 30
                Advice
                For an older female with a high BMI:
                Create a Caloric Deficit: Consume fewer calories than you burn to 
                promote weight loss.
                Balanced Diet: Focus on nutrient-rich foods like lean proteins, 
                vegetables, and whole grains.
                Regular Exercise: Incorporate both cardio and strength training to 
                support weight loss and maintain muscle mass.
                Monitor Health: Consult with a healthcare provider to ensure the 
                weight loss plan is safe and effective.'];
            }
            if($HighOldFemaleGain) {
                $desc = ['BMI Description for an Older Female Above 40 with High BMI 
                Trying to Gain Muscle Mass
                Body Mass Index (BMI) is a measure of body fat based on height and 
                weight:
                Overweight: BMI 25-29.9
                Obesity: BMI ≥ 30
                Advice
                For an older female with a high BMI aiming to gain muscle mass:
                Increase Caloric Intake: Eat more calories with a focus on 
                nutrient-dense foods.
                High-Protein Diet: Include protein-rich foods to support muscle growth.
                Strength Training: Engage in regular resistance exercises.
                Monitor Progress: Track muscle gains and adjust your diet and 
                exercise plan as needed.
                Consult a healthcare provider to ensure a safe and effective approach.'];
            }
            // -------
            if($LowOldMaleLose) {
                $desc = ['BMI Description for an Older Male Above 40 with Low BMI Trying to Lose Weight
                Body Mass Index (BMI) is a measure of body fat based on height and weight:
                Underweight: BMI less than 18.5
                Advice
                For an older male with a low BMI trying to lose weight:
                Monitor Health: Focus on overall health rather than just weight loss.
                Consult a Healthcare Provider: Ensure weight loss is safe and 
                appropriate given the low BMI.
                Balanced Approach: Maintain a balanced diet and incorporate regular 
                physical activity, but prioritize health over further weight loss.'];
            }
            if($LowOldMaleGain) {
                $desc = ['BMI Description for an Older Male Above 40 with Low BMI 
                Trying to Gain Muscle Mass
                Body Mass Index (BMI) is a measure of body fat based on height and weight:
                Underweight: BMI less than 18.5
                Advice
                For an older male with a low BMI aiming to gain muscle mass:
                Increase Caloric Intake: Eat more calories with a focus on nutrient-dense foods.
                Protein-Rich Diet: Include plenty of protein to support muscle growth.
                Strength Training: Engage in regular resistance exercises.
                Monitor Progress: Track changes in muscle mass and adjust your diet 
                and exercise routine as needed.
                Consult with a healthcare provider to ensure a safe and effective plan.'];
            }
            if($LowOldFemaleLose) {
                $desc = ['BMI Description for an Older Female Above 40 with Low BMI Trying to Lose Weight
                Body Mass Index (BMI) is a measure of body fat based on height and weight:
                Underweight: BMI less than 18.5
                Advice
                For an older female with a low BMI trying to lose weight:
                Consult a Healthcare Provider: Ensure that further weight loss is safe and 
                appropriate given the low BMI.
                Focus on Health: Prioritize overall health and well-being rather than just weight loss.
                Balanced Diet: Maintain a nutrient-rich diet and consider moderate physical activity.'];
            }
            if($LowOldFemaleGain) {
                $desc = ['BMI Description for an Older Female Above 40 with Low BMI 
                Trying to Gain Muscle Mass
                Body Mass Index (BMI) is a measure of body fat based on height and weight:
                Underweight: BMI less than 18.5
                Advice
                For an older female with a low BMI aiming to gain muscle mass:
                Increase Caloric Intake: Eat more calories with a focus on nutrient-dense 
                foods.
                Protein-Rich Diet: Include high-protein foods to support muscle growth.
                Strength Training: Engage in regular resistance exercises.
                Monitor Progress: Track changes in muscle mass and adjust your diet 
                and exercise plan as needed.
                Consult a healthcare provider to ensure a safe and effective approach.'];
            }
        }
    } elseif($contextFlag == "Bmr") {
        if(!empty($dbOutRow['descBmr'])) { // use the entry by the user
            $desc = [$dbOutRow['descBmr']];
        } else { 
            $desc = ['Basal Metabolic Rate (BMR) Definition and Description
            What is BMR?
            Basal Metabolic Rate (BMR) is the number of calories your body needs to 
            maintain basic physiological functions while at rest. These functions 
            include breathing, circulation, cell production, nutrient processing, 
            and temperature regulation.
            Key Points About BMR:
            Basic Function Maintenance: BMR represents the energy required for 
            essential bodily functions, not including physical activities or digestion.
            Measurement Conditions: BMR is typically measured under very specific 
            conditions: after a full nights sleep, in a fasted state, and in a 
            thermoneutral environment to ensure the body is at rest and not expending 
            extra energy for digestion or temperature regulation.
            Influencing Factors: Several factors influence BMR, including:
            Age: BMR generally decreases with age.
            Sex: Males typically have a higher BMR than females due to a greater 
            muscle mass.
            Body Composition: More muscle mass increases BMR, while more fat mass 
            does not.
            Genetics: Genetic makeup can influence BMR.
            Hormones: Hormonal levels (e.g., thyroid hormones) can affect BMR.
            Calculating BMR: While direct measurement requires specialized equipment, 
            BMR can be estimated using equations such as the Harris-Benedict Equation 
            or the Mifflin-St Jeor Equation, which take into account age, sex, weight, 
            and height.
            Importance of BMR:
            Weight Management: Understanding your BMR can help in planning dietary 
            intake and exercise routines for weight loss, maintenance, or gain.
            Caloric Needs: BMR forms the baseline for calculating Total Daily Energy 
            Expenditure (TDEE), which includes additional calories burned through 
            physical activity and digestion.
            Example Calculation (Using Mifflin-St Jeor Equation):
            For a 30-year-old woman, 65 kg, 165 cm tall:
            BMR = (10 * weight in kg) + (6.25 * height in cm) - (5 * age in years) - 161
            BMR = (10 * 65) + (6.25 * 165) - (5 * 30) - 161
            BMR = 650 + 1031.25 - 150 - 161
            BMR ≈ 1370 kcal/day
            This means she needs approximately 1370 calories per day to maintain 
            basic bodily functions at rest.'];
        }
    } elseif($contextFlag == "If") {
        if(!empty($dbOutRow['descIf'])) { // use the entry by the user
            $desc = [$dbOutRow['descIf']];
        } else { 
            if($HighYoungMaleLose) {
                $desc = ['Intermittent Fasting Recommendation for a Young Male Under 35
                 with High BMI Trying to Lose Weight
                Intermittent Fasting (IF) involves cycling between periods of eating 
                and fasting. It can help with weight loss by reducing calorie intake 
                and improving metabolic health.
                Simple IF Recommendation:
                16/8 Method:
                Fasting Window: Fast for 16 hours.
                Eating Window: Eat all your meals within an 8-hour window 
                (e.g., 12 PM to 8 PM).
                Eat Balanced Meals: Focus on nutrient-dense foods during your 
                eating window, including lean proteins, healthy fats, and plenty of 
                vegetables.
                Stay Hydrated: Drink water, tea, or black coffee during the fasting 
                period to stay hydrated.
                Consistency: Stick to the schedule daily for best results.
                Note:
                Consult with a healthcare provider before starting any new diet 
                regimen to ensure it is safe and appropriate for your individual 
                health needs.'];
            }
            if($HighYoungMaleGain) {
                $desc = ['Intermittent Fasting Recommendation for a Young Male Under 35 
                with High BMI Trying to Gain Muscle Mass
                Intermittent Fasting (IF) can help manage your eating schedule while 
                focusing on muscle gain. Here is a simple approach:
                16/8 Method:
                Fasting Window: Fast for 16 hours.
                Eating Window: Eat all meals within an 8-hour window (e.g., 12 PM to 8 PM).
                Nutrient-Dense Meals:
                Protein: Include protein-rich foods like lean meats, fish, eggs, 
                and dairy.
                Healthy Fats: Eat avocados, nuts, seeds, and olive oil.
                Complex Carbs: Focus on whole grains, fruits, and vegetables.
                Pre- and Post-Workout Nutrition:
                Eat a balanced meal or snack before and after workouts within your 
                eating window to support muscle growth and recovery.
                Stay Hydrated:
                Drink water, black coffee, or tea during fasting periods.
                Supplements:
                Consider protein supplements and creatine if needed.
                Note:
                Consult with a healthcare provider or nutritionist to ensure this 
                plan suits your individual health needs and goals.
                '];
            }
            if($HighYoungFemaleLose) {
                $desc = ['Intermittent Fasting Recommendation for a Young Male Under 
                35 with High BMI Trying to Lose Weight
                Intermittent Fasting (IF) can be an effective strategy for weight 
                loss. Here is a straightforward approach:
                16/8 Method:
                Fasting Window: Fast for 16 hours.
                Eating Window: Consume all meals within an 8-hour window (e.g., 
                12 PM to 8 PM).
                Balanced Meals:
                Nutrient-Dense Foods: Focus on lean proteins, vegetables, whole 
                grains, and healthy fats.
                Portion Control: Be mindful of portion sizes to maintain a 
                calorie deficit.
                Stay Hydrated:
                Drink plenty of water, black coffee, or tea during fasting periods.
                Exercise:
                Incorporate regular physical activity, including both cardio and 
                strength training.
                Note:
                Consult with a healthcare provider to ensure this plan is safe 
                and effective for your individual needs.'];
            }
            if($HighYoungFemaleGain) {
                $desc = ['Intermittent Fasting Recommendation for a Young Male Under 
                35 with High BMI Trying to Gain Muscle Mass
                16/8 Method:
                Fasting Window: 16 hours.
                Eating Window: 8 hours (e.g., 12 PM to 8 PM).
                Focus on Nutrient-Dense Meals:
                Protein: Include sources like lean meats, fish, and protein supplements.
                Healthy Fats: Add avocados, nuts, and olive oil.
                Complex Carbs: Eat whole grains, fruits, and vegetables.
                Pre- and Post-Workout Nutrition:
                Ensure meals around workouts to support energy and recovery.
                Hydrate:
                Drink plenty of water, and consider black coffee or tea during fasting.
                Note:
                Consult a healthcare provider to tailor the plan to your specific needs.'];
            }
            // -------
            if($LowYoungMaleLose) {
                $desc = ['Intermittent Fasting Recommendation for a Young Male Under 
                35 with Low BMI Trying to Lose Weight
                12/12 Method:
                Fasting Window: 12 hours.
                Eating Window: 12 hours (e.g., 8 AM to 8 PM).
                Balanced Nutrition:
                Nutrient-Dense Foods: Focus on lean proteins, healthy fats, and whole 
                grains.
                Maintain Energy: Ensure you are not undereating, even while creating 
                a caloric deficit.
                Hydrate:
                Drink water, and black coffee or tea during fasting.
                Monitor Health:
                Track progress and adjust as needed. Consult a healthcare provider 
                to ensure safety.
                Note:
                A cautious approach is crucial to avoid further reducing an already 
                low BMI.'];
            }
            if($LowYoungMaleGain) {
                $desc = ['Intermittent Fasting Recommendation for a Young Male Under 35 
                with Low BMI Trying to Gain Muscle Mass
                14/10 Method:
                Fasting Window: 14 hours.
                Eating Window: 10 hours (e.g., 10 AM to 8 PM).
                High-Calorie, Nutrient-Dense Meals:
                Protein: Include lean meats, fish, eggs, and protein shakes.
                Healthy Fats: Add avocados, nuts, and olive oil.
                Complex Carbs: Eat whole grains, fruits, and vegetables.
                Pre- and Post-Workout:
                Ensure adequate meals or snacks around workouts for energy and recovery.
                Hydrate:
                Drink water, and consider black coffee or tea during fasting.
                Note:
                Consult a healthcare provider to tailor the approach to your needs and 
                avoid further reducing BMI.'];
            }
            if($LowYoungFemaleLose) {
                $desc = ['Intermittent Fasting Recommendation for a Young Female Under 
                35 with Low BMI Trying to Lose Weight
                12/12 Method:
                Fasting Window: 12 hours.
                Eating Window: 12 hours (e.g., 8 AM to 8 PM).
                Nutrient-Dense Foods:
                Balanced Meals: Focus on lean proteins, healthy fats, and whole grains.
                Monitor Intake: Ensure you are still meeting nutritional needs even 
                with a caloric deficit.
                Hydrate:
                Drink water, and black coffee or tea during fasting.
                Consult a Healthcare Provider:
                Ensure that the approach is safe given your low BMI and adjust as needed.
                Note:
                Be cautious to avoid further reducing an already low BMI.'];
            }
            if($LowYoungFemaleGain) {
                $desc = ['Intermittent Fasting Recommendation for a Young Female Under 
                35 with Low BMI Trying to Gain Muscle Mass
                14/10 Method:
                Fasting Window: 14 hours.
                Eating Window: 10 hours (e.g., 10 AM to 8 PM).
                High-Calorie, Nutrient-Dense Meals:
                Protein: Include sources like lean meats, fish, eggs, and protein shakes.
                Healthy Fats: Add avocados, nuts, and olive oil.
                Complex Carbs: Focus on whole grains, fruits, and vegetables.
                Pre- and Post-Workout Nutrition:
                Ensure meals or snacks around workouts to support muscle growth
                and recovery.
                Hydrate:
                Drink water, and consider black coffee or tea during fasting.
                Note:
                Consult a healthcare provider to ensure the approach supports muscle 
                gain without negatively affecting your low BMI.'];
            }
            // --------
            if($HighOldMaleLose) {
                $desc = ['Intermittent Fasting Recommendation for an Older Male Above 
                35-40 with High BMI Trying to Lose Weight
                16/8 Method:
                Fasting Window: 16 hours.
                Eating Window: 8 hours (e.g., 12 PM to 8 PM).
                Balanced Nutrition:
                Lean Proteins: Include sources like chicken, fish, and legumes.
                Healthy Fats: Add nuts, seeds, and olive oil.
                Complex Carbs: Focus on whole grains and vegetables.
                Hydrate:
                Drink plenty of water, and consider black coffee or tea during fasting.
                Exercise:
                Incorporate regular physical activity, including both cardio and 
                strength training.
                Note:
                Consult with a healthcare provider to ensure the approach is safe 
                and effective for your health needs.'];
            }
            if($HighOldMaleGain) {
                $desc = ['Intermittent Fasting Recommendation for an Older Male 
                Above 35-40 with High BMI Trying to Gain Muscle Mass
                16/8 Method:
                Fasting Window: 16 hours.
                Eating Window: 8 hours (e.g., 12 PM to 8 PM).
                High-Calorie, Nutrient-Dense Meals:
                Protein: Include sources like lean meats, fish, eggs, and protein shakes.
                Healthy Fats: Add avocados, nuts, and olive oil.
                Complex Carbs: Focus on whole grains, fruits, and vegetables.
                Pre- and Post-Workout Nutrition:
                Eat balanced meals or snacks around workouts for muscle support 
                and recovery.
                Hydrate:
                Drink plenty of water, and consider black coffee or tea during fasting.
                Note:
                Consult with a healthcare provider to ensure the approach aligns 
                with your health and muscle gain goals.'];
            }
            if($HighOldFemaleLose) {
                $desc = ['Intermittent Fasting Recommendation for an Older Female 
                Above 35-40 with High BMI Trying to Lose Weight
                16/8 Method:
                Fasting Window: 16 hours.
                Eating Window: 8 hours (e.g., 12 PM to 8 PM).
                Balanced Diet:
                Lean Proteins: Include sources like chicken, fish, and legumes.
                Healthy Fats: Add nuts, seeds, and olive oil.
                Complex Carbs: Focus on whole grains and vegetables.
                Hydrate:
                Drink plenty of water, and consider black coffee or tea during fasting.
                Exercise:
                Include regular physical activity, such as walking or strength training.
                Note:
                Consult with a healthcare provider to ensure the plan is safe and 
                effective for your individual health needs.'];
            }
            if($HighOldFemaleGain) {
                $desc = ['Intermittent Fasting Recommendation for an Older Female Above 
                35-40 with High BMI Trying to Gain Muscle Mass
                14/10 Method:
                Fasting Window: 14 hours.
                Eating Window: 10 hours (e.g., 10 AM to 8 PM).
                High-Calorie, Nutrient-Dense Meals:
                Protein: Include lean meats, fish, eggs, and protein shakes.
                Healthy Fats: Add avocados, nuts, and olive oil.
                Complex Carbs: Focus on whole grains, fruits, and vegetables.
                Pre- and Post-Workout Nutrition:
                Ensure balanced meals or snacks around workouts for muscle support and recovery.
                Hydrate:
                Drink plenty of water, and consider black coffee or tea during fasting.
                Note:
                Consult a healthcare provider to tailor the approach to your needs and 
                ensure safety.'];
            }
            // -------
            if($LowOldMaleLose) {
                $desc = ['Intermittent Fasting Recommendation for an Older Male Above 
                35-40 with Low BMI Trying to Lose Weight
                12/12 Method:
                Fasting Window: 12 hours.
                Eating Window: 12 hours (e.g., 8 AM to 8 PM).
                Balanced Nutrition:
                Nutrient-Dense Foods: Focus on lean proteins, healthy fats, and 
                complex carbs.
                Monitor Intake: Ensure you are not undereating, even with a 
                caloric deficit.
                Hydrate:
                Drink water, and consider black coffee or tea during fasting.
                Consult a Healthcare Provider:
                Ensure the approach is safe given your low BMI and adjust as needed.
                Note:
                Be cautious to avoid further reducing an already low BMI.'];
            }
            if($LowOldMaleGain) {
                $desc = ['Intermittent Fasting Recommendation for an Older Male Above 
                35-40 with Low BMI Trying to Gain Muscle Mass
                14/10 Method:
                Fasting Window: 14 hours.
                Eating Window: 10 hours (e.g., 10 AM to 8 PM).
                High-Calorie, Nutrient-Dense Meals:
                Protein: Include lean meats, fish, eggs, and protein shakes.
                Healthy Fats: Add avocados, nuts, and olive oil.
                Complex Carbs: Focus on whole grains, fruits, and vegetables.
                Pre- and Post-Workout Nutrition:
                Ensure balanced meals or snacks around workouts for muscle growth
                and recovery.
                Hydrate:
                Drink plenty of water, and consider black coffee or tea during fasting.
                Note:
                Consult a healthcare provider to ensure the approach is appropriate 
                for your low BMI and muscle gain goals.'];
            }
            if($LowOldFemaleLose) {
                $desc = ['Intermittent Fasting Recommendation for an Older Female Above 
                35-40 with Low BMI Trying to Lose Weight
                12/12 Method:
                Fasting Window: 12 hours.
                Eating Window: 12 hours (e.g., 8 AM to 8 PM).
                Balanced Diet:
                Nutrient-Dense Foods: Focus on lean proteins, healthy fats, and 
                complex carbs.
                Monitor Intake: Ensure you are meeting nutritional needs while 
                maintaining a slight caloric deficit.
                Hydrate:
                Drink water, and consider black coffee or tea during fasting.
                Consult a Healthcare Provider:
                Ensure the approach is safe and appropriate for your low BMI and 
                health status.
                Note:
                Be cautious to avoid further reducing your already low BMI.'];
            }
            if($LowOldFemaleGain) {
                $desc = ['Daily Intermittent Fasting Recommendation for an Older 
                Female Above 35-40 with Low BMI Trying to Gain Muscle Mass
                14/10 Method:
                Fasting Window: 14 hours.
                Eating Window: 10 hours (e.g., 10 AM to 8 PM).
                High-Calorie, Nutrient-Dense Meals:
                Protein: Include sources like lean meats, fish, eggs, and protein shakes.
                Healthy Fats: Add avocados, nuts, and olive oil.
                Complex Carbs: Focus on whole grains, fruits, and vegetables.
                Pre- and Post-Workout Nutrition:
                Ensure balanced meals or snacks around workouts for muscle growth and 
                recovery.
                Hydrate:
                Drink plenty of water, and consider black coffee or tea during fasting.
                Note:
                Consult with a healthcare provider to ensure the approach supports 
                muscle gain without negatively impacting your low BMI.'];
            }
        }
    } elseif($contextFlag == "Cal") {
        if(!empty($dbOutRow['descCal'])) { // use the entry by the user
            $desc = [$dbOutRow['descCal']];
        } else { 
            if($HighYoungMaleLose) {
                $desc = ['Caloric Intake Recommendation for a Young Male Under 35 with 
                High BMI Trying to Lose Weight
                Calculate Daily Caloric Needs: Determine your Total Daily Energy 
                Expenditure (TDEE) using an online calculator or formula.
                Create a Caloric Deficit: Aim to consume 500-750 calories less than 
                your TDEE to lose weight at a healthy rate of about 1-1.5 pounds per week.
                Monitor and Adjust: Track your weight loss progress and adjust your 
                calorie intake as needed based on your results and goals.
                Note:
                Consulting with a healthcare provider or nutritionist for personalized 
                advice is recommended.'];
            }
            if($HighYoungMaleGain) {
                $desc = ['Caloric Intake Recommendation for a Young Male Under 35 with 
                High BMI Trying to Gain Muscle Mass
                Caloric Surplus:
                Target: Consume 250-500 calories above your maintenance level to 
                support muscle growth.
                Focus on Nutrient-Dense Foods:
                Proteins: Include lean meats, fish, eggs, and dairy.
                Carbohydrates: Eat whole grains, fruits, and vegetables.
                Fats: Add sources like avocados, nuts, and olive oil.
                Monitor and Adjust:
                Track progress and adjust intake as needed to continue gaining 
                muscle without excessive fat gain.
                Note:
                Consult with a healthcare provider or nutritionist for personalized 
                advice.'];
            }
            if($HighYoungFemaleLose) {
                $desc = ['Caloric Intake Recommendation for a Young Female Under 35 with 
                High BMI Trying to Lose Weight
                Caloric Deficit:
                Target: Consume 500-750 calories below your maintenance level to 
                promote steady weight loss.
                Focus on Nutrient-Dense Foods:
                Proteins: Include lean meats, fish, eggs, and legumes.
                Carbohydrates: Eat whole grains, fruits, and vegetables.
                Fats: Include healthy fats like avocados, nuts, and olive oil in 
                moderation.
                Monitor and Adjust:
                Track weight loss progress and adjust caloric intake as needed.
                Note:
                Consult with a healthcare provider or nutritionist to ensure the 
                approach is appropriate for your specific needs.'];
            }
            if($HighYoungFemaleGain) {
                $desc = ['Caloric Intake Recommendation for a Young Female Under 35 with 
                High BMI Trying to Gain Muscle Mass
                Caloric Surplus:
                Target: Consume 250-500 calories above your maintenance level to 
                support muscle growth.
                Focus on Nutrient-Dense Foods:
                Proteins: Include lean meats, fish, eggs, and protein shakes.
                Carbohydrates: Eat whole grains, fruits, and vegetables.
                Fats: Add sources like avocados, nuts, and olive oil.
                Monitor and Adjust:
                Track muscle gain and adjust caloric intake as needed to continue 
                building muscle without excessive fat gain.
                Note:
                Consult with a healthcare provider or nutritionist for personalized 
                guidance.'];
            }
            // -------
            if($LowYoungMaleLose) {
                $desc = ['Caloric Intake Recommendation for a Young Male Under 35 with 
                Low BMI Trying to Lose Weight
                Caloric Deficit:
                Target: Consume 250-500 calories below your maintenance level to 
                promote weight loss without compromising overall health.
                Focus on Nutrient-Dense Foods:
                Proteins: Include lean meats, fish, eggs, and legumes.
                Carbohydrates: Eat whole grains, fruits, and vegetables.
                Fats: Include healthy fats like avocados, nuts, and olive oil in 
                moderation.
                Monitor and Adjust:
                Track weight loss progress and adjust caloric intake as needed.
                Note:
                Consult with a healthcare provider to ensure the approach is 
                appropriate given your low BMI.'];
            }
            if($LowYoungMaleGain) {
                $desc = ['Caloric Intake Recommendation for a Young Male Under 35 with 
                Low BMI Trying to Gain Muscle Mass
                Caloric Surplus:
                Target: Consume 250-500 calories above your maintenance level to 
                support muscle growth.
                Focus on Nutrient-Dense Foods:
                Proteins: Include lean meats, fish, eggs, and protein shakes.
                Carbohydrates: Eat whole grains, fruits, and vegetables.
                Fats: Add sources like avocados, nuts, and olive oil.
                Monitor and Adjust:
                Track muscle gain progress and adjust caloric intake as needed to 
                continue building muscle without excessive fat gain.
                Note:
                Consult with a healthcare provider or nutritionist for personalized 
                guidance.'];
            }
            if($LowYoungFemaleLose) {
                $desc = ['Caloric Intake Recommendation for a Young Female Under 35 with Low BMI 
                Trying to Lose Weight
                Moderate Caloric Deficit:
                Target: Consume 250–500 calories below your maintenance level to 
                promote weight loss while ensuring you get enough nutrients.
                Focus on Nutrient-Dense Foods:
                Proteins: Include lean meats, fish, eggs, and legumes.
                Carbohydrates: Eat whole grains, fruits, and vegetables.
                Fats: Include healthy fats like avocados, nuts, and olive oil in 
                moderation.
                Monitor and Adjust:
                Track weight loss progress and adjust caloric intake as needed.
                Note:
                Consult with a healthcare provider to ensure the approach is 
                safe and appropriate given your low BMI.'];
            }
            if($LowYoungFemaleGain) {
                $desc = ['Caloric Intake Recommendation for a Young Female Under 35 
                with Low BMI Trying to Gain Muscle Mass
                Caloric Surplus:
                Target: Consume 250-500 calories above your maintenance level to 
                support muscle growth.
                Focus on Nutrient-Dense Foods:
                Proteins: Include lean meats, fish, eggs, and protein shakes.
                Carbohydrates: Eat whole grains, fruits, and vegetables.
                Fats: Add sources like avocados, nuts, and olive oil.
                Monitor and Adjust:
                Track muscle gain progress and adjust caloric intake as needed.
                Note:
                Consult with a healthcare provider or nutritionist for personalized 
                guidance.'];
            }
            // -------
            if($HighOldMaleLose) {
                $desc = ['Caloric Intake Recommendation for an Older Male Above 35-40 
                with High BMI Trying to Lose Weight
                Caloric Deficit:
                Target: Consume 500-750 calories below your maintenance level to 
                promote steady weight loss.
                Focus on Nutrient-Dense Foods:
                Proteins: Include lean meats, fish, eggs, and legumes.
                Carbohydrates: Eat whole grains, fruits, and vegetables.
                Fats: Include healthy fats like avocados, nuts, and olive oil in 
                moderation.
                Monitor and Adjust:
                Track weight loss progress and adjust caloric intake as needed.
                Note:
                Consult with a healthcare provider or nutritionist to ensure the 
                approach is appropriate for your specific needs and health status.'];
            }
            if($HighOldMaleGain) {
                $desc = ['Caloric Intake Recommendation for an Older Male Above 35-40 
                with High BMI Trying to Gain Muscle Mass
                Caloric Surplus:
                Target: Consume 250-500 calories above your maintenance level to 
                support muscle growth.
                Focus on Nutrient-Dense Foods:
                Proteins: Include lean meats, fish, eggs, and protein shakes.
                Carbohydrates: Eat whole grains, fruits, and vegetables.
                Fats: Add sources like avocados, nuts, and olive oil.
                Monitor and Adjust:
                Track muscle gain progress and adjust caloric intake as needed 
                to ensure healthy muscle development without excessive fat gain.
                Note:
                Consult with a healthcare provider or nutritionist for personalized 
                guidance.'];
            }
            if($HighOldFemaleLose) {
                $desc = ['Caloric Intake Recommendation for an Older Female Above 35-40 
                with High BMI Trying to Lose Weight
                Caloric Deficit:
                Target: Consume 500-750 calories below your maintenance level to 
                promote steady weight loss.
                Focus on Nutrient-Dense Foods:
                Proteins: Include lean meats, fish, eggs, and legumes.
                Carbohydrates: Eat whole grains, fruits, and vegetables.
                Fats: Include healthy fats like avocados, nuts, and olive oil in moderation.
                Monitor and Adjust:
                Track weight loss progress and adjust caloric intake as needed.
                Note:
                Consult with a healthcare provider or nutritionist to ensure the approach is 
                appropriate for your specific needs and health status.'];
            }
            if($HighOldFemaleGain) {
                $desc = ['Caloric Intake Recommendation for an Older Female Above 35-40 
                with High BMI Trying to Gain Muscle Mass
                Caloric Surplus:
                Target: Consume 250-500 calories above your maintenance level to 
                support muscle growth.
                Focus on Nutrient-Dense Foods:
                Proteins: Include lean meats, fish, eggs, and protein shakes.
                Carbohydrates: Eat whole grains, fruits, and vegetables.
                Fats: Add sources like avocados, nuts, and olive oil.
                Monitor and Adjust:
                Track muscle gain progress and adjust caloric intake as needed to 
                ensure healthy muscle development without excessive fat gain.
                Note:
                Consult with a healthcare provider or nutritionist for personalized guidance.'];
            }
            // -------
            if($LowOldMaleLose) {
                $desc = ['Caloric Intake Recommendation for an Older Male Above 35-40 
                with Low BMI Trying to Lose Weight
                Moderate Caloric Deficit:
                Target: Consume 250-500 calories below your maintenance level to 
                promote weight loss while ensuring you get enough nutrients.
                Focus on Nutrient-Dense Foods:
                Proteins: Include lean meats, fish, eggs, and legumes.
                Carbohydrates: Eat whole grains, fruits, and vegetables.
                Fats: Include healthy fats like avocados, nuts, and olive oil in 
                moderation.
                Monitor and Adjust:
                Track weight loss progress and adjust caloric intake as needed.
                Note:
                Consult with a healthcare provider to ensure the approach is safe 
                and appropriate given your low BMI.'];
            }
            if($LowOldMaleGain) {
                $desc = ['Caloric Intake Recommendation for an Older Male Above 35-40 
                with Low BMI Trying to Gain Muscle Mass
                Caloric Surplus:
                Target: Consume 250-500 calories above your maintenance level to 
                support muscle growth.
                Focus on Nutrient-Dense Foods:
                Proteins: Include lean meats, fish, eggs, and protein shakes.
                Carbohydrates: Eat whole grains, fruits, and vegetables.
                Fats: Add sources like avocados, nuts, and olive oil.
                Monitor and Adjust:
                Track muscle gain progress and adjust caloric intake as needed.
                Note:
                Consult with a healthcare provider or nutritionist for personalized 
                advice to ensure safe and effective muscle gain.'];
            }
            if($LowOldFemaleLose) {
                $desc = ['Caloric Intake Recommendation for an Older Female Above 35-40 
                with Low BMI Trying to Lose Weight
                Moderate Caloric Deficit:
                Target: Consume 250–500 calories below your maintenance level to promote 
                weight loss without compromising health.
                Focus on Nutrient-Dense Foods:
                Proteins: Include lean meats, fish, eggs, and legumes.
                Carbohydrates: Eat whole grains, fruits, and vegetables.
                Fats: Include healthy fats like avocados, nuts, and olive oil in moderation.
                Monitor and Adjust:
                Track weight loss progress and adjust caloric intake as needed.
                Note:
                Consult with a healthcare provider to ensure the approach is safe given your 
                low BMI.'];
            }
            if($LowOldFemaleGain) {
                $desc = ['Caloric Intake Recommendation for an Older Female Above 
                35-40 with Low BMI Trying to Gain Muscle Mass
                Caloric Surplus:
                Target: Consume 250-500 calories above your maintenance level to 
                support muscle growth.
                Focus on Nutrient-Dense Foods:
                Proteins: Include lean meats, fish, eggs, and protein shakes.
                Carbohydrates: Eat whole grains, fruits, and vegetables.
                Fats: Add sources like avocados, nuts, and olive oil.
                Monitor and Adjust:
                Track muscle gain progress and adjust caloric intake as needed.
                Note:
                Consult with a healthcare provider or nutritionist for personalized 
                advice to ensure safe and effective muscle gain.'];
            }
        }
    } elseif($contextFlag == "Macro") {
        if(!empty($dbOutRow['descMacro'])) { // use the entry by the user
            $desc = [$dbOutRow['descMacro']];
        } else { 
            if($HighYoungMaleLose) {
                $desc = ['Macronutrient Recommendations for a Young Male Under 35 with 
                High BMI Trying to Lose Weight
                Protein: Aim for 25-30% of total daily calories from protein to support 
                muscle maintenance and satiety. Sources include lean meats, fish, 
                eggs, and legumes.
                Fats: Target 20-35% of daily calories from healthy fats. Include 
                avocados, nuts, seeds, and olive oil.
                Carbohydrates: Make up the remaining 45-55% of daily calories with 
                complex carbohydrates such as whole grains, vegetables, and fruits.
                Note:
                Adjust these ratios based on personal progress and dietary preferences. 
                Consulting with a nutritionist can provide tailored guidance.'];
            }
            if($HighYoungMaleGain) {
                $desc = ['Macronutrient Percentage Recommendations Including Fiber for a 
                Young Male Under 35 with High BMI Trying to Gain Muscle Mass
                Proteins: 25-30% of total daily calories
                Carbohydrates: 45-55% of total daily calories
                Fiber: Aim for 25-38 grams per day within the carbohydrate intake.
                Fats: 20-30% of total daily calories
                Note:
                Adjust based on individual progress and specific needs.'];
            }
            if($HighYoungFemaleLose) {
                $desc = ['Macronutrient Percentage Recommendations Including Fiber 
                for a Young Female Under 35 with High BMI Trying to Lose Weight
                Proteins: 25-30% of total daily calories
                Carbohydrates: 40-50% of total daily calories
                Fiber: Aim for 21-25 grams per day within the carbohydrate intake.
                Fats: 25-30% of total daily calories
                Note:
                Adjust based on individual progress and specific needs.'];
            }
            if($HighYoungFemaleGain) {
                $desc = ['Macronutrient Percentage Recommendations Including Fiber 
                for a Young Female Under 35 with High BMI Trying to Gain Muscle Mass
                Proteins: 25-30% of total daily calories
                Carbohydrates: 45-55% of total daily calories
                Fiber: Aim for 21-25 grams per day within the carbohydrate intake.
                Fats: 20-30% of total daily calories
                Note:
                Adjust based on progress and individual needs.'];
            }
            // -------
            if($LowYoungMaleLose) {
                $desc = ['Macronutrient Percentage Recommendations Including Fiber 
                for a Young Male Under 35 with Low BMI Trying to Lose Weight
                Proteins: 30-35% of total daily calories
                Carbohydrates: 35-45% of total daily calories
                Fiber: Aim for 30-38 grams per day within the carbohydrate intake.
                Fats: 25-30% of total daily calories
                Note:
                Adjust based on progress and individual needs.'];
            }
            if($LowYoungMaleGain) {
                $desc = ['Macronutrient Percentage Recommendations Including Fiber 
                for a Young Male Under 35 with Low BMI Trying to Gain Weight
                Proteins: 25-30% of total daily calories
                Carbohydrates: 50-60% of total daily calories
                Fiber: Aim for 30-38 grams per day within the carbohydrate intake.
                Fats: 20-30% of total daily calories
                Note:
                Adjust based on progress and individual needs.'];
            }
            if($LowYoungFemaleLose) {
                $desc = ['Macronutrient Percentage Recommendations Including 
                Fiber for a Young Female Under 35 with Low BMI Trying to Lose Weight
                Proteins: 30-35% of total daily calories
                Carbohydrates: 40-50% of total daily calories
                Fiber: Aim for 21-25 grams per day within the carbohydrate intake.
                Fats: 20-30% of total daily calories
                Note:
                Adjust based on progress and individual needs.'];
            }
            if($LowYoungFemaleGain) {
                $desc = ['For a young female under 35 with a low BMI aiming to gain 
                muscle mass, here are some concise macronutrient percentage recommendations, including fiber:
                Protein: 25-30% of total daily calories
                Carbohydrates: 45-50% of total daily calories (ensure these include complex carbs and fiber)
                Fats: 20-25% of total daily calories
                Fiber: Aim for 25-30 grams per day'];
            }
            // -------
            if($HighOldMaleLose) {
                $desc = ['For an older male (over 35-40) with a high BMI aiming to 
                lose weight, here are some concise macronutrient percentage 
                recommendations, including fiber:
                Protein: 25-30% of total daily calories
                Carbohydrates: 35-40% of total daily calories (focus on high-fiber, 
                low-glycemic carbs)
                Fats: 30-35% of total daily calories (prioritize healthy fats)
                Fiber: Aim for 30-35 grams per day'];
            }
            if($HighOldMaleGain) {
                $desc = ['For an older male (over 35-40) with a high BMI aiming to gain 
                muscle mass, here are some 
                concise macronutrient percentage recommendations, including fiber:
                Protein: 25-30% of total daily calories
                Carbohydrates: 40-45% of total daily calories (focus on complex carbs 
                with adequate fiber)
                Fats: 25-30% of total daily calories (include healthy fats)
                Fiber: Aim for 30-35 grams per day'];
            }
            if($HighOldFemaleLose) {
                $desc = ['For an older female (over 35-40) with a high BMI aiming 
                to lose weight, here are some concise macronutrient percentage 
                recommendations, including fiber:
                Protein: 25-30% of total daily calories
                Carbohydrates: 35-40% of total daily calories (focus on high-fiber, 
                low-glycemic carbs)
                Fats: 30-35% of total daily calories (prioritize healthy fats)
                Fiber: Aim for 25-30 grams per day'];
            }
            if($HighOldFemaleGain) {
                $desc = ['For an older female (over 35-40) with a high BMI aiming 
                to gain muscle mass, here are some concise macronutrient percentage 
                recommendations, including fiber:
                Protein: 25-30% of total daily calories
                Carbohydrates: 40-45% of total daily calories (focus on complex 
                carbs with adequate fiber)
                Fats: 25-30% of total daily calories (include healthy fats)
                Fiber: Aim for 25-30 grams per day'];
            }
            // -------
            if($LowOldMaleLose) {
                $desc = ['For an older male (over 35-40) with a low BMI aiming 
                to lose weight, here are some concise macronutrient percentage 
                recommendations, including fiber:
                Protein: 25-30% of total daily calories
                Carbohydrates: 40-45% of total daily calories (focus on high-fiber, 
                low-glycemic carbs)
                Fats: 25-30% of total daily calories (prioritize healthy fats)
                Fiber: Aim for 25-30 grams per day'];
            }
            if($LowOldMaleGain) {
                $desc = ['For an older male (over 35-40) with a low BMI aiming 
                to gain muscle mass, here are some concise macronutrient 
                percentage recommendations, including fiber:
                Protein: 25-30% of total daily calories
                Carbohydrates: 45-50% of total daily calories (focus on 
                complex carbs with adequate fiber)
                Fats: 20-25% of total daily calories (include healthy fats)
                Fiber: Aim for 25-30 grams per day'];
            }
            if($LowOldFemaleLose) {
                $desc = ['For an older female (over 35-40) with a low BMI aiming 
                to lose weight, here are some concise macronutrient percentage 
                recommendations, including fiber:
                Protein: 25-30% of total daily calories
                Carbohydrates: 35-40% of total daily calories (focus on high-fiber, 
                low-glycemic carbs)
                Fats: 30-35% of total daily calories (prioritize healthy fats)
                Fiber: Aim for 25-30 grams per day'];
            }
            if($LowOldFemaleGain) {
                $desc = ['For an older female (over 35-40) with a low BMI aiming 
                to gain muscle mass, here are some concise macronutrient percentage 
                recommendations, including fiber:
                Protein: 25-30% of total daily calories
                Carbohydrates: 45-50% of total daily calories (focus on complex 
                carbs with adequate fiber)
                Fats: 20-25% of total daily calories (include healthy fats)
                Fiber: Aim for 25-30 grams per day'];
            }
        }
    } elseif($contextFlag == "MicroVit") {
        if(!empty($dbOutRow['descMicroVit'])) { // use the entry by the user
            $desc = [$dbOutRow['descMicroVit']];
        } else { 
            if($HighYoungMaleLose) {
                $desc = ['Micronutrient Vitamins Recommendation for a Young Male Under 
                35 with High BMI Trying to Lose Weight
                Vitamin D: Supports bone health and metabolism. Sources include 
                sunlight exposure and fortified foods or supplements.
                Vitamin C: Important for immune function and tissue repair. Found 
                in citrus fruits, bell peppers, and leafy greens.
                B Vitamins: Aid in energy metabolism. Sources include whole grains, 
                lean meats, eggs, and legumes.
                Vitamin E: Acts as an antioxidant. Obtain from nuts, seeds, and 
                vegetable oils.
                Note:
                A balanced diet should cover these vitamins, but consulting a 
                healthcare provider can help ensure you meet your individual needs.'];
            }
            if($HighYoungMaleGain) {
                $desc = ['For a young male under 35 with a high BMI aiming to gain 
                muscle mass, here are some concise micronutrient vitamin recommendations:
                Vitamin D: 600-800 IU per day (supports muscle function and bone health)
                Vitamin C: 90 mg per day (aids in collagen formation and tissue repair)
                Vitamin E: 15 mg per day (acts as an antioxidant)
                Vitamin B12: 2.4 mcg per day (essential for energy metabolism and muscle 
                growth)
                Vitamin B6: 1.3-1.7 mg per day (involved in protein metabolism)
                Folate (Vitamin B9): 400 mcg per day (important for cell growth and repair)
                Ensure these vitamins are included in a balanced diet or consider 
                supplements if necessary.'];
            }
            if($HighYoungFemaleLose) {
                $desc = ['For a young female under 35 with a high BMI aiming to lose 
                weight, here are some concise micronutrient vitamin recommendations:
                Vitamin D: 600-800 IU per day (supports metabolism and bone health)
                Vitamin C: 75 mg per day (aids in tissue repair and immune function)
                Vitamin E: 15 mg per day (acts as an antioxidant)
                Vitamin B12: 2.4 mcg per day (important for energy metabolism)
                Vitamin B6: 1.3-1.5 mg per day (supports protein metabolism and energy 
                production)
                Folate (Vitamin B9): 400 mcg per day (supports cell function and 
                metabolism)
                These vitamins can help support overall health and metabolic 
                function during weight loss.'];
            }
            if($HighYoungFemaleGain) {
                $desc = ['For a young female under 35 with a high BMI aiming to gain 
                muscle mass, here are some concise micronutrient vitamin recommendations:
                Vitamin D: 600-800 IU per day (supports muscle function and bone health)
                Vitamin C: 75 mg per day (aids in collagen formation and recovery)
                Vitamin E: 15 mg per day (acts as an antioxidant)
                Vitamin B12: 2.4 mcg per day (important for energy and muscle growth)
                Vitamin B6: 1.3-1.5 mg per day (helps with protein metabolism)
                Folate (Vitamin B9): 400 mcg per day (supports cell growth and repair)
                These vitamins can help with muscle development and overall health.'];
            }
            // -------
            if($LowYoungMaleLose) {
                $desc = ['For a young male under 35 with a low BMI aiming to lose weight, 
                here are some concise micronutrient vitamin recommendations:
                Vitamin D: 600-800 IU per day (supports bone health and metabolism)
                Vitamin C: 90 mg per day (aids in immune function and tissue repair)
                Vitamin E: 15 mg per day (acts as an antioxidant)
                Vitamin B12: 2.4 mcg per day (important for energy levels)
                Vitamin B6: 1.3-1.7 mg per day (supports metabolism and energy production)
                Folate (Vitamin B9): 400 mcg per day (important for cell function and 
                repair)
                These vitamins support overall health and metabolic function during 
                weight loss.'];
            }
            if($LowYoungMaleGain) {
                $desc = ['For a young male under 35 with a low BMI aiming to gain 
                muscle mass, here are some concise micronutrient vitamin recommendations:
                Vitamin D: 600-800 IU per day (supports muscle function and bone health)
                Vitamin C: 90 mg per day (aids in muscle repair and immune function)
                Vitamin E: 15 mg per day (acts as an antioxidant)
                Vitamin B12: 2.4 mcg per day (important for energy and muscle growth)
                Vitamin B6: 1.3-1.7 mg per day (supports protein metabolism)
                Folate (Vitamin B9): 400 mcg per day (important for cell growth 
                and repair)
                These vitamins support muscle development and overall health.'];
            }
            if($LowYoungFemaleLose) {
                $desc = ['For a young female under 35 with a low BMI aiming to lose 
                weight, here are some concise micronutrient vitamin recommendations:
                Vitamin D: 600-800 IU per day (supports bone health and metabolism)
                Vitamin C: 75 mg per day (aids in immune function and skin health)
                Vitamin E: 15 mg per day (acts as an antioxidant)
                Vitamin B12: 2.4 mcg per day (important for energy and red blood cell 
                formation)
                Vitamin B6: 1.3-1.5 mg per day (supports metabolism and energy production)
                Folate (Vitamin B9): 400 mcg per day (supports cell function and repair)
                These vitamins help with overall health and metabolic function during 
                weight loss.'];
            }
            if($LowYoungFemaleGain) {
                $desc = ['For a young female under 35 with a low BMI aiming to gain 
                muscle mass, here are some concise micronutrient vitamin recommendations:
                Vitamin D: 600-800 IU per day (supports muscle function and bone health)
                Vitamin C: 75 mg per day (aids in muscle repair and immune function)
                Vitamin E: 15 mg per day (acts as an antioxidant)
                Vitamin B12: 2.4 mcg per day (important for energy and muscle growth)
                Vitamin B6: 1.3-1.5 mg per day (supports protein metabolism)
                Folate (Vitamin B9): 400 mcg per day (supports cell growth and repair)
                These vitamins help with muscle development and overall health.'];
            }
            // ------
            if($HighOldMaleLose) {
                $desc = ['For an older male (above 35-40) with a high BMI aiming to 
                lose weight, here are some concise micronutrient vitamin recommendations:
                Vitamin D: 600-800 IU per day (supports bone health and metabolism)
                Vitamin C: 90 mg per day (aids in immune function and skin health)
                Vitamin E: 15 mg per day (acts as an antioxidant)
                Vitamin B12: 2.4 mcg per day (important for energy and red blood cell 
                formation)
                Vitamin B6: 1.3-1.7 mg per day (supports metabolism and energy production)
                Folate (Vitamin B9): 400 mcg per day (supports cell function and repair)
                These vitamins support overall health and metabolic function during 
                weight loss.'];
            }
            if($HighOldMaleGain) {
                $desc = ['For an older male (above 35-40) with a high BMI aiming to gain 
                muscle mass, here are some concise micronutrient vitamin recommendations:
                Vitamin D: 600-800 IU per day (supports muscle function and bone health)
                Vitamin C: 90 mg per day (aids in muscle repair and immune function)
                Vitamin E: 15 mg per day (acts as an antioxidant)
                Vitamin B12: 2.4 mcg per day (important for energy and muscle growth)
                Vitamin B6: 1.3-1.7 mg per day (supports protein metabolism)
                Folate (Vitamin B9): 400 mcg per day (supports cell growth and repair)
                These vitamins can help with muscle development and overall health.'];
            }
            if($HighOldFemaleLose) {
                $desc = ['For an older female (above 35-40) with a high BMI aiming to 
                lose weight, here are some concise micronutrient vitamin recommendations:
                Vitamin D: 600-800 IU per day (supports bone health and metabolism)
                Vitamin C: 75 mg per day (aids in immune function and skin health)
                Vitamin E: 15 mg per day (acts as an antioxidant)
                Vitamin B12: 2.4 mcg per day (important for energy and red blood cell 
                formation)
                Vitamin B6: 1.3-1.5 mg per day (supports metabolism and energy production)
                Folate (Vitamin B9): 400 mcg per day (supports cell function and repair)
                These vitamins support overall health and metabolic function during 
                weight loss.'];
            }
            if($HighOldFemaleGain) {
                $desc = ['For an older female (above 35-40) with a high BMI aiming to gain 
                muscle mass, here are some concise micronutrient vitamin recommendations:
                Vitamin D: 600-800 IU per day (supports muscle function and bone health)
                Vitamin C: 75 mg per day (aids in muscle repair and immune function)
                Vitamin E: 15 mg per day (acts as an antioxidant)
                Vitamin B12: 2.4 mcg per day (important for energy and muscle growth)
                Vitamin B6: 1.3-1.5 mg per day (supports protein metabolism)
                Folate (Vitamin B9): 400 mcg per day (supports cell growth and repair)
                These vitamins help with muscle development and overall health.'];
            }
            // -------
            if($LowOldMaleLose) {
                $desc = ['For an older male (above 35-40) with a low BMI aiming to lose 
                weight, here are some concise micronutrient vitamin recommendations:
                Vitamin D: 600-800 IU per day (supports bone health and metabolism)
                Vitamin C: 90 mg per day (aids in immune function and tissue repair)
                Vitamin E: 15 mg per day (acts as an antioxidant)
                Vitamin B12: 2.4 mcg per day (important for energy levels)
                Vitamin B6: 1.3-1.7 mg per day (supports metabolism and energy production)
                Folate (Vitamin B9): 400 mcg per day (supports cell function and repair)
                These vitamins support overall health and metabolic function during 
                weight loss.'];
            }
            if($LowOldMaleGain) {
                $desc = ['For an older male (above 35-40) with a low BMI aiming to gain 
                muscle mass, here are some concise micronutrient vitamin recommendations:
                Vitamin D: 600-800 IU per day (supports muscle function and bone health)
                Vitamin C: 90 mg per day (aids in muscle repair and immune function)
                Vitamin E: 15 mg per day (acts as an antioxidant)
                Vitamin B12: 2.4 mcg per day (important for energy and muscle growth)
                Vitamin B6: 1.3-1.7 mg per day (supports protein metabolism)
                Folate (Vitamin B9): 400 mcg per day (supports cell growth and repair)
                These vitamins can help with muscle development and overall health.'];
            }
            if($LowOldFemaleLose) {
                $desc = ['For an older female (above 35-40) with a low BMI aiming to 
                lose weight, here are some concise micronutrient vitamin recommendations:
                Vitamin D: 600-800 IU per day (supports bone health and metabolism)
                Vitamin C: 75 mg per day (aids in immune function and tissue repair)
                Vitamin E: 15 mg per day (acts as an antioxidant)
                Vitamin B12: 2.4 mcg per day (important for energy levels and red 
                blood cell formation)
                Vitamin B6: 1.3-1.5 mg per day (supports metabolism and energy production)
                Folate (Vitamin B9): 400 mcg per day (supports cell function and repair)
                These vitamins support overall health and metabolic function during 
                weight loss.'];
            }
            if($LowOldFemaleGain) {
                $desc = ['For an older female (above 35-40) with a low BMI aiming to 
                gain muscle mass, here are some concise micronutrient vitamin recommendations:
                Vitamin D: 600-800 IU per day (supports muscle function and bone health)
                Vitamin C: 75 mg per day (aids in muscle repair and immune function)
                Vitamin E: 15 mg per day (acts as an antioxidant)
                Vitamin B12: 2.4 mcg per day (important for energy and muscle growth)
                Vitamin B6: 1.3-1.5 mg per day (supports protein metabolism)
                Folate (Vitamin B9): 400 mcg per day (supports cell growth and repair)
                These vitamins help with muscle development and overall health.'];
            }
        }
    } elseif($contextFlag == "MicroTrace") {
        if(!empty($dbOutRow['descMicroTrace'])) { // use the entry by the user
            $desc = [$dbOutRow['descMicroTrace']];
        } else { 
            if($HighYoungMaleLose) {
                $desc = ['Micronutrient Trace Minerals Recommendation for a Young Male 
                Under 35 with High BMI Trying to Lose Weight
                Iron: Essential for energy levels and metabolism. Sources include 
                lean meats, beans, and fortified cereals.
                Zinc: Important for immune function and metabolism. Found in meat, 
                shellfish, and nuts.
                Magnesium: Supports muscle function and energy production. Obtain 
                from nuts, seeds, whole grains, and leafy greens.
                Selenium: Contributes to antioxidant defense. Sources include nuts, 
                seafood, and whole grains.
                Note:
                Ensure a balanced diet to cover these trace minerals, and consider 
                consulting a healthcare provider for personalized advice.'];
            }
            if($HighYoungMaleGain) {
                $desc = ['For a young male under 35 with a high BMI aiming to gain 
                muscle mass, here are some concise trace mineral recommendations:
                Iron: 8-11 mg per day (supports oxygen transport and energy)
                Zinc: 11 mg per day (important for muscle growth and immune function)
                Magnesium: 400-420 mg per day (supports muscle function and recovery)
                Copper: 900 mcg per day (important for energy production and connective tissue)
                Selenium: 55 mcg per day (acts as an antioxidant and supports metabolism)
                These trace minerals are essential for muscle growth and overall health.'];
            }
            if($HighYoungFemaleLose) {
                $desc = ['For a young female under 35 with a high BMI aiming to lose 
                weight, here are some concise trace mineral recommendations:
                Iron: 18 mg per day (supports oxygen transport and energy levels)
                Zinc: 8 mg per day (important for metabolism and immune function)
                Magnesium: 310-320 mg per day (supports metabolism and muscle function)
                Copper: 900 mcg per day (important for energy production and connective 
                tissue)
                Selenium: 55 mcg per day (acts as an antioxidant and supports metabolism)
                These trace minerals are essential for metabolic health and overall 
                well-being during weight loss.'];
            }
            if($HighYoungFemaleGain) {
                $desc = ['For a young female under 35 with a high BMI aiming to gain 
                muscle mass, here are some concise trace mineral recommendations:
                Iron: 18 mg per day (supports oxygen transport and energy)
                Zinc: 8 mg per day (important for muscle growth and immune function)
                Magnesium: 310-320 mg per day (supports muscle function and recovery)
                Copper: 900 mcg per day (important for energy production and connective tissue)
                Selenium: 55 mcg per day (acts as an antioxidant and supports metabolism)
                These trace minerals support muscle development and overall health.'];
            }
            // -------
            if($LowYoungMaleLose) {
                $desc = ['For a young male under 35 with a low BMI aiming to lose 
                weight, here are some concise trace mineral recommendations:
                Iron: 8-11 mg per day (supports oxygen transport and energy)
                Zinc: 11 mg per day (important for metabolism and immune function)
                Magnesium: 400-420 mg per day (supports muscle function and metabolism)
                Copper: 900 mcg per day (important for energy production and connective tissue)
                Selenium: 55 mcg per day (acts as an antioxidant and supports metabolism)
                These trace minerals help maintain overall health and metabolic function 
                during weight loss.'];
            }
            if($LowYoungMaleGain) {
                $desc = ['For a young male under 35 with a low BMI aiming to gain 
                muscle mass, here are some concise trace mineral recommendations:
                Iron: 8-11 mg per day (supports oxygen transport and energy)
                Zinc: 11 mg per day (important for muscle growth and immune function)
                Magnesium: 400-420 mg per day (supports muscle function and recovery)
                Copper: 900 mcg per day (important for energy production and connective 
                tissue)
                Selenium: 55 mcg per day (acts as an antioxidant and supports metabolism)
                These trace minerals are essential for muscle development and overall 
                health.'];
            }
            if($LowYoungFemaleLose) {
                $desc = ['For a young female under 35 with a low BMI aiming to lose 
                weight, here are some concise trace mineral recommendations:
                Iron: 18 mg per day (supports oxygen transport and energy)
                Zinc: 8 mg per day (important for metabolism and immune function)
                Magnesium: 310-320 mg per day (supports metabolism and muscle function)
                Copper: 900 mcg per day (important for energy production and connective tissue)
                Selenium: 55 mcg per day (acts as an antioxidant and supports metabolism)
                These trace minerals support overall health and metabolic function during weight loss.'];
            }
            if($LowYoungFemaleGain) {
                $desc = ['For a young female under 35 with a low BMI aiming to gain 
                muscle mass, here are some concise trace mineral recommendations:
                Iron: 18 mg per day (supports oxygen transport and energy)
                Zinc: 8 mg per day (important for muscle growth and immune function)
                Magnesium: 310-320 mg per day (supports muscle function and recovery)
                Copper: 900 mcg per day (important for energy production and 
                connective tissue)
                Selenium: 55 mcg per day (acts as an antioxidant and supports metabolism)
                These trace minerals are essential for muscle development and 
                overall health.'];
            }
            // -------
            if($HighOldMaleLose) {
                $desc = ['For an older male (above 35-40) with a high BMI aiming to lose 
                weight, here are some concise trace mineral recommendations:
                Iron: 8 mg per day (supports oxygen transport and energy)
                Zinc: 11 mg per day (important for metabolism and immune function)
                Magnesium: 400-420 mg per day (supports muscle function and metabolism)
                Copper: 900 mcg per day (important for energy production and connective tissue)
                Selenium: 55 mcg per day (acts as an antioxidant and supports metabolism)
                These trace minerals support overall health and metabolic function during weight loss.'];
            }
            if($HighOldMaleGain) {
                $desc = ['For an older male (above 35-40) with a high BMI aiming to 
                gain muscle mass, here are some concise trace mineral recommendations:
                Iron: 8 mg per day (supports oxygen transport and energy)
                Zinc: 11 mg per day (important for muscle growth and immune function)
                Magnesium: 400-420 mg per day (supports muscle function and recovery)
                Copper: 900 mcg per day (important for energy production and 
                connective tissue)
                Selenium: 55 mcg per day (acts as an antioxidant and supports metabolism)
                These trace minerals help support muscle development and overall health.'];
            }
            if($HighOldFemaleLose) {
                $desc = ['For an older female (above 35-40) with a high BMI aiming 
                to lose weight, here are some concise trace mineral recommendations:
                Iron: 8 mg per day (supports oxygen transport and energy levels)
                Zinc: 8 mg per day (important for metabolism and immune function)
                Magnesium: 320 mg per day (supports muscle function and metabolism)
                Copper: 900 mcg per day (important for energy production and 
                connective tissue)
                Selenium: 55 mcg per day (acts as an antioxidant and supports metabolism)
                These trace minerals support overall health and metabolic 
                function during weight loss.'];
            }
            if($HighOldFemaleGain) {
                $desc = ['For an older female (above 35-40) with a high BMI aiming 
                to gain muscle mass, here are some concise trace mineral recommendations:
                Iron: 8 mg per day (supports oxygen transport and energy)
                Zinc: 8 mg per day (important for muscle growth and immune function)
                Magnesium: 320 mg per day (supports muscle function and recovery)
                Copper: 900 mcg per day (important for energy production and 
                connective tissue)
                Selenium: 55 mcg per day (acts as an antioxidant and supports metabolism)
                These trace minerals help support muscle development and overall health.'];
            }
            // -------
            if($LowOldMaleLose) {
                $desc = ['For an older male (above 35-40) with a low BMI aiming to 
                lose weight, here are some concise trace mineral recommendations:
                Iron: 8 mg per day (supports oxygen transport and energy)
                Zinc: 11 mg per day (important for metabolism and immune function)
                Magnesium: 400-420 mg per day (supports muscle function and metabolism)
                Copper: 900 mcg per day (important for energy production and 
                connective tissue)
                Selenium: 55 mcg per day (acts as an antioxidant and supports metabolism)
                These trace minerals support overall health and metabolic function 
                during weight loss.'];
            }
            if($LowOldMaleGain) {
                $desc = ['For an older male (above 35-40) with a low BMI aiming to 
                gain muscle mass, here are some concise trace mineral recommendations:
                Iron: 8 mg per day (supports oxygen transport and energy)
                Zinc: 11 mg per day (important for muscle growth and immune function)
                Magnesium: 400-420 mg per day (supports muscle function and recovery)
                Copper: 900 mcg per day (important for energy production and 
                connective tissue)
                Selenium: 55 mcg per day (acts as an antioxidant and supports metabolism)
                These trace minerals help support muscle development and overall health.'];
            }
            if($LowOldFemaleLose) {
                $desc = ['For an older female (above 35-40) with a low BMI aiming 
                to lose weight, here are some concise trace mineral recommendations:
                Iron: 8 mg per day (supports oxygen transport and energy levels)
                Zinc: 8 mg per day (important for metabolism and immune function)
                Magnesium: 320 mg per day (supports muscle function and metabolism)
                Copper: 900 mcg per day (important for energy production and 
                connective tissue)
                Selenium: 55 mcg per day (acts as an antioxidant and supports metabolism)
                These trace minerals support overall health and metabolic function during weight loss.'];
            }
            if($LowOldFemaleGain) {
                $desc = ['For an older female (above 35-40) with a low BMI aiming 
                to gain muscle mass, here are some concise trace mineral recommendations:
                Iron: 8 mg per day (supports oxygen transport and energy)
                Zinc: 8 mg per day (important for muscle growth and immune function)
                Magnesium: 320 mg per day (supports muscle function and recovery)
                Copper: 900 mcg per day (important for energy production and 
                connective tissue)
                Selenium: 55 mcg per day (acts as an antioxidant and supports metabolism)
                These trace minerals help support muscle development and overall health.'];
            } 
        } 
    } elseif($contextFlag == "Meal") {
        if(!empty($dbOutRow['descMeal'])) { // use the entry by the user
            $desc = [$dbOutRow['descMeal']];
        } else { 
            $desc = ['Please provide your meal plan for your client here!'];
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