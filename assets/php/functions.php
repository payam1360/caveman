<?php

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
        if($data[$kk]->qKey[0] == 'gender' && $genderDone == false){
            $UsergenderChoice = $data[$kk]->optionsText[0][$data[$kk]->qAnswer];
            if($UsergenderChoice == 'Male') {
                $Usergender = 'Male';
            } else {
                $Usergender = 'Female';
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
        if($data[$kk]->qKey[0] == 'stress' && $stressDone == false){
            $UserstressChoice = $data[$kk]->optionsText[0][$data[$kk]->qAnswer];
            if($UserstressChoice == 'high') {
                $Userstress = 'High';
            } elseif($UserstressChoice == 'manageable') {
                $Userstress = 'Medium';
            } else {
                $Userstress = 'Low';
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
// user's goal
function getGoal($data) {
    $goalDone    = false;
    $kk          = 0;
    $Usergoal = [];
    while(isset($data[$kk]->qIdx)){
        if($data[$kk]->qKey[0] == 'goal' && $goalDone == false){
            $UsergoalChoice = $data[$kk]->optionsText[0][$data[$kk]->qAnswer];
            
            if($UsergoalChoice == 'lose weight') {
                $Usergoal = 'Lose';
            } elseif($UsergoalChoice == 'gain muscles') {
                $Usergoal = 'Gain';
            } else {
                $Usergoal = 'LessTired';
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
    if($Userweight != [] && $Userheight != []) {
        $BMI = lb2kg($Userweight) / pow(in2cm($Userheight)/100, 2);
    } else {
        $BMI = [];
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

    if($Userweight != [] && $Userheight != [] && $Userage != [] 
            && $Usergender != [] && $Userstress != []) {
        if($Userstress == 'Low') {
            $stressFactor = 1.2;
        } elseif($Userstress == 'Medium') {
            $stressFactor = 1.65;
        } else {
            $stressFactor = 2.25;
        }
        if($Usergender == 'Male') {
            $BMR = $stressFactor * ( 66.47 + (13.75 * $Userweight) + (5.003 * $Userheight) - (6.75 * $Userage));
        } else {
            $BMR = $stressFactor * ( 655.1 + (9.56 * $Userweight) + (1.85 * $Userheight) - (4.7 * $Userage));
        }
    } else {
        $BMR = [];
    }
    return($BMR);
}

function calculateIf($data){
    // this function is the algorithm for calculating intervals in which the user is 
    // recommended for fasting. Fasting increase IGF or growth hormons.  
    // calculate intermittent fasting interval
    $IF         = [[24,0],[24,0],[24,0],[24,0],[24,0],[24,0],[24,0]];
    $Userweight = getWeight($data);
    $Userage    = getAge($data);
    $Usergender = getGender($data);
    $Usergoal   = getGoal($data);

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
            if($Usergender == 'Male'){
                if($Usergoal == 'Lose') {
                    $IF = [[24,24,24,24,16,24,8],[0,0,0,0,8,0,16]];
                } elseif($Usergoal == 'Gain') {
                    $IF = [[24,24,24,24,16,24,8],[0,0,0,0,8,0,16]];
                }
            } elseif($Usergender == 'Female'){
                if($Usergoal == 'Lose') {
                    $IF = [[24,24,24,24,16,24,8],[0,0,0,0,8,0,16]];
                } elseif($Usergoal == 'Gain') {
                    $IF = [[24,24,24,24,16,24,8],[0,0,0,0,8,0,16]];
                }
            }
        }
        elseif($Userweight >= 120 && $Userweight < 220) {
            if($Usergender == 'Male'){
                if($Usergoal == 'Lose') {
                    $IF = [[24,24,24,24,16,24,8],[0,0,0,0,8,0,16]];
                } elseif($Usergoal == 'Gain') {
                    $IF = [[24,24,24,24,16,24,8],[0,0,0,0,8,0,16]];
                }
            } elseif($Usergender == 'Female'){
                if($Usergoal == 'Lose') {
                    $IF = [[24,24,24,24,16,24,8],[0,0,0,0,8,0,16]];
                } elseif($Usergoal == 'Gain') {
                    $IF = [[24,24,24,24,16,24,8],[0,0,0,0,8,0,16]];
                }
            }
        }
        elseif($Userweight >= 220 && $Userweight < 300) {
            if($Usergender == 'Male'){
                if($Usergoal == 'Lose') {
                    $IF = [[24,24,24,24,16,24,8],[0,0,0,0,8,0,16]];
                } elseif($Usergoal == 'Gain') {
                    $IF = [[24,24,24,24,16,24,8],[0,0,0,0,8,0,16]];
                }
            } elseif($Usergender == 'Female'){
                if($Usergoal == 'Lose') {
                    $IF = [[24,24,24,24,16,24,8],[0,0,0,0,8,0,16]];
                } elseif($Usergoal == 'Gain') {
                    $IF = [[24,24,24,24,16,24,8],[0,0,0,0,8,0,16]];
                }
            }
        }
    } else {
        $IF = [];
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
        if($Usergender == 'Female') {
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
    $BMR        = calculateBmr($data);

    if($BMR != []) {
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
        if($Usergender == 'Male'){
            if($Usergoal == 'Lose') {
                $p = 0.3 * $ageFactor;
                $c = 0.6 * (1 - $ageFactor);
                $f = 0.9 - $p - $c;
                $fi= 0.1;
                $caloriDeficit = 250;
                $Macro =  [$p * $BMR - $caloriDeficit, $c * $BMR - $caloriDeficit, 
                           $f * $BMR - $caloriDeficit, $fi * $BMR - $caloriDeficit]; // [protein, carb, fat];
            } elseif($Usergoal == 'Gain') {
                $p = 0.3 * $ageFactor;
                $c = 0.6 * (1 - $ageFactor);
                $f = 0.9 - $p - $c;
                $fi= 0.1;
                $caloriSurplus = 250;
                $Macro =  [$p * $BMR + $caloriSurplus, $c * $BMR + $caloriSurplus, 
                           $f * $BMR + $caloriSurplus, $fi * $BMR - $caloriSurplus]; // [protein, carb, fat];
            } else {
                $p = 0.3 * $ageFactor;
                $c = 0.6 * (1 - $ageFactor);
                $f = 0.9 - $p - $c;
                $fi= 0.1;
                $caloriSurplus = 250;
                $Macro =  [$p * $BMR + $caloriSurplus, $c * $BMR + $caloriSurplus, 
                           $f * $BMR + $caloriSurplus, $fi * $BMR - $caloriSurplus]; // [protein, carb, fat];            
            }
        } elseif($Usergender == 'Female'){
            if($Usergoal == 'Lose') {
                $p = 0.2 * $ageFactor;
                $c = 0.65 * (1 - $ageFactor);
                $f = 0.9 - $p - $c;
                $fi= 0.1;
                $caloriDeficit = 250;
                $Macro =  [$p * $BMR - $caloriDeficit, $c * $BMR - $caloriDeficit, 
                           $f * $BMR - $caloriDeficit, $fi * $BMR - $caloriDeficit]; // [protein, carb, fat];
            } elseif($Usergoal == 'Gain') {
                $p = 0.25 * $ageFactor;
                $c = 0.65 * (1 - $ageFactor);
                $f = 0.9 - $p - $c;
                $fi= 0.1;
                $caloriSurplus = 250;
                $Macro =  [$p * $BMR + $caloriSurplus, $c * $BMR + $caloriSurplus, 
                           $f * $BMR + $caloriSurplus, $fi * $BMR - $caloriSurplus]; // [protein, carb, fat];
            } else {
                $p = 0.25 * $ageFactor;
                $c = 0.65 * (1 - $ageFactor);
                $f = 0.9 - $p - $c;
                $fi= 0.1;
                $caloriSurplus = 250;
                $Macro =  [$p * $BMR + $caloriSurplus, $c * $BMR + $caloriSurplus, 
                           $f * $BMR + $caloriSurplus, $fi * $BMR - $caloriSurplus]; // [protein, carb, fat];
            }
        }
    } else {
        $Macro = [];
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
        if($Usergender == 'Male') {
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
        } elseif($Usergender == 'Female') {
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
        } 
    } else {
        $vValues = [];
        $tValues = [];
    }   
 
    $Micro = array('vNames' => $vNames,
                   'tNames' => $tNames,
                   'vValues' => $vValues,
                   'tValues' => $tValues,
                   'vUnits' => $vUnits,
                   'tUnits' => $tUnits, 
                   'vScale' => $vScale,
                   'tScale' => $tScale);
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


?>