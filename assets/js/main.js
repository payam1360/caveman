/**
* Template Name: HeroBiz - v2.3.1
* Template URL: https://bootstrapmade.com/herobiz-bootstrap-business-template/
* Author: BootstrapMade.com
* License: https://bootstrapmade.com/license/
*/
let Questions = [];
let counter = 0;
let prog = 0;
let MAX_cnt = 7;
let progChart = [];
let ctx = document.querySelector('#ProgressCircle');
let userid = Math.round(Math.random() * 1000).toString();
const progress = {
  datasets: [{
    data: [0, 100],
    backgroundColor: [
      '#FF7F50',
      '#808080'
    ],
  }]
};
const config = {
  type: 'doughnut',
  data: progress,
  options: {
    responsive: true,
    maintainAspectRatio: false,
    cutout: 35,
  }
};
class question {
    constructor(userid, question, answer, Qidx, Type, Options, visited){
        this.question = question; // must be a text string
        this.answer = answer;
        this.Qidx = Qidx;
        this.type = Type;
        this.options = Options;
        this.userid = userid;
        this.visited = visited;
    }
    pushData (){
        Questions.push(this);
    }
};


// event delegate for click on the dynamic button elements
document.addEventListener('click',function(e){
    let alt = [];
    if(e.target && e.target.id == 'form-button') {
        alt = e.target.attributes.alt;
        getUserButtonSelection(alt);
    }
    else if(e.target && e.target.parentNode.id == 'form-button') {
        alt = e.target.parentNode.attributes.alt;
        getUserButtonSelection(alt);
    }
    else if(e.target && e.target.firstChild && e.target.firstChild.id == 'form-button') {
        alt = e.target.firstChild.attributes.alt;
        getUserButtonSelection(alt);
    }
    
});

document.addEventListener('DOMContentLoaded', () => {
  "use strict";
    /* define the class for questions*/
    // create the questions
    let input = document.querySelectorAll('.form-input');
    let header = document.querySelectorAll('.form-header');
    let headerTxt = document.querySelectorAll('.form-header-style');

    // this function will be getting the info from the Admin Panel.
    // For now, t is generating questions locally
    questionCreate(userid);
    // initialize the first question and init the load page
    resetStart(Questions, input, header, headerTxt);
    // generate the chart
    progChart = new Chart(
      ctx,
      config
    );
    // log in button handle
    const login = document.querySelector('.login');
    if (login) {
        login.addEventListener('click', function(event) {
            window.location.assign('login.html');
        })
    }
    // register button handle
    const register = document.querySelector('.register');
    if (register) {
        register.addEventListener('click', function(event) {
            window.location.assign('register.html');
        })
    }
    
    const moveright = document.querySelector('.form-go-right');
    moveRight(moveright, input, header, headerTxt, Questions);
    
    const moveleft = document.querySelector('.form-go-left');
    moveLeft(moveleft, input, header, headerTxt, Questions);
    
});


// AUX functions start here



// function to set styles for animation
function ChangeForm(querySel, sec, pixel, opacity, width){
    querySel.style.transitionDuration = sec;
    querySel.style.transform = ["translateX(" + pixel + "px)"];
    querySel.style.opacity = opacity;
    querySel.style.width = width;
}

// function to reset the form type back to normal text
function resetFormType(querySelIn){
    while( querySelIn.childElementCount > 0){
        querySelIn.removeChild(querySelIn.children[0]);
    }
}



function getUserButtonSelection(alt){
    
    Questions[counter].answer = alt.value;
    let formButtonStyle = document.querySelectorAll('.form-button-style');
    for(let kk = 0; kk < formButtonStyle.length; kk++){
        formButtonStyle[kk].style.backgroundColor = '#ffffff';
    }
    formButtonStyle[alt.value].style.backgroundColor = '#f08080';
}

//
// this function eventually comes from user costomization and design of his app.
function questionCreate(userid){
    let Obj = new question(userid, '1. what is your goal?', '', 0, 'button', ['fa-solid fa-weight-scale','fa-solid fa-dumbbell', 'fa-solid fa-microscope'], false);
    Obj.pushData(Obj);
    Obj = new question(userid, '2. what is your name?', '', 1, 'text', [''], false);
    Obj.pushData(Obj);
    Obj = new question(userid, '3. what is your weight?', '', 2, 'list', ['80lb-90lb','90lb-100lb','100lb-110lb','110lb-120lb','120lb-130lb','130lb-140lb','140lb-150lb','150lb-160lb','160lb-170lb','170lb-180lb','180lb-190lb','190lb-200lb','200lb-210lb','210lb-220lb','220lb-230lb','230lb-240lb','240lb-250lb','250lb+'], false);
    Obj.pushData(Obj);
    Obj = new question(userid, '4. what is your height?', '', 3, 'list', ['<5ft','5ft-5.1ft','5.1ft-5.2ft','5.2ft-5.3ft','5.3ft-5.4ft','5.4ft-5.5ft','5.5ft-5.6ft','5.6ft-5.7ft','5.7ft-5.8ft','5.8ft-5.9ft','5.9ft-5.10ft','5.10ft-5.11ft','5.11ft-6.0ft','6.0ft-6.1ft','6.1ft-6.2ft','6.2ft-6.3ft','6.3ft-6.4ft','6.4ft-6.5ft', '6.5ft+'], false);
    Obj.pushData(Obj);
    Obj = new question(userid, '5. how is your sleep?', '', 4, 'button', ['fa-regular fa-moon','fa-solid fa-battery-quarter'], false);
    Obj.pushData(Obj);
    Obj = new question(userid, '6. what is your email?', '', 5, 'email', [''], false);
    Obj.pushData(Obj);
    Obj = new question(userid, '7. what is your password?', '', 6, 'password', [''], false);
    Obj.pushData(Obj);
}



function submitUserData(inputDataBlob) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            let data = JSON.parse(this.response);
            if(data.ok == true){
                plotBmi(data.bmi);
                plotIf(data.If);
                plotMacro(data.macro);
                plotMicro(data.micro);
                displayMeal(data.mealData)
            }
            
        }
    };
    // sending the request
    xmlhttp.open("POST", "assets/php/main.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    var userdata = "userInfo="+JSON.stringify(inputDataBlob);
    xmlhttp.send(userdata);
}


// function to plot BMI data returned by the server for the given user
function plotBmi(bmi){
    // Canvas element section
    let bmiElement = document.querySelector('#Bmi');
    let bmiDiv = document.querySelector('.Bmi');
    let bmiTxt = document.querySelector('.BMI_text');
    let bmiDesc = document.querySelector('.BMI_text_description');
    
    bmiTxt.style.display = 'block';
    bmiDiv.style.display = 'block';
    bmiDesc.style.display = 'block';
    
    bmiDesc.innerHTML = 'This text must come from the server about BMI!';
    // Config section
    let meanBmi = 25;
    let varBmi = 3.1;
    const pdf = (x) => {
      const m = Math.sqrt(varBmi * 2 * Math.PI);
      const e =  Math.exp(-Math.pow(x - meanBmi, 2) / (2 * varBmi));
      return e / m;
    };
    const bell = [];
    const xAxis = [];
    const pointBackgroundColor = [];
    const pointRadius = [];
    const startX = meanBmi - 2.5 * varBmi;
    const endX = meanBmi + 2.5 * varBmi;
    const step = varBmi / 10;
    for(let x = startX; x<=endX; x+=step) {
      bell.push(pdf(x));
      xAxis.push(Math.round(x * 100) / 100);
        if(x < bmi && x > bmi - step){
            pointBackgroundColor.push('limegreen');
            pointRadius.push(6);
        } else {
            pointBackgroundColor.push('coral');
            pointRadius.push(1);
        }
    }
    const bmiData = {
      labels: xAxis,
      datasets: [{
        label: 'BMI',
        fill: false,
        data: bell,
        borderColor: 'coral',
        backgroundColor: pointBackgroundColor,
        pointRadius: pointRadius,
      },{
        label: 'You',
        backgroundColor: 'limegreen',
      }]
    };
    const bmiConfig = {
      type: 'line',
      data: bmiData,
      options: {
        responsive: true,
        maintainAspectRatio: false,
        aspectRatio: 1,
      }
    };
    
    bmiChart = new Chart(
      bmiElement,
      bmiConfig,
    );
}

// function to plot IF data returned by the server for the given user
function plotIf(If){
    // Canvas element section
    let ifElement  = document.querySelector('#IntermittentFasting');
    let ifDiv = document.querySelector('.IntermittentFasting');
    let ifTxt = document.querySelector('.IF_text');
    let ifDesc = document.querySelector('.IF_text_description');
    
    
    ifDesc.innerHTML = 'This text must come from the server about IF!';
    ifTxt.style.display = 'block';
    ifDiv.style.display = 'block';
    ifDesc.style.display = 'block';

    const ifData = {
      labels: ['Eating interval (hrs)', 'Fasting interval (hrs)'],
      datasets: [{
        data: [24-If, If],
        backgroundColor: [
          'coral',
          'lightblue'
        ],
      }]
    };
    const config = {
      type: 'doughnut',
      data: ifData,
      options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: 35,
      }
    };
    ifChart = new Chart(
      ifElement,
      config
    );
}

// function to plot Macro data returned by the server for the given user
function plotMacro(macro){
    // Canvas element section
    let macroElement  = document.querySelector('#Macro');
    let macroDiv = document.querySelector('.Macro');
    let macroTxt = document.querySelector('.MACRO_text');
    let macroDesc = document.querySelector('.MACRO_text_description');
    macroTxt.style.display = 'block';
    macroDiv.style.display = 'block';
    macroDesc.style.display = 'block';

    macroDesc.innerHTML = 'This text must come from the server about Macro!';
    
    const macroData = {
      labels: ['fat','carbs', 'protein', 'fiber'],
      datasets: [{
        data: macro,
        backgroundColor: [
          'coral',
          'lightblue',
          'limegreen',
          'cyan'
        ],
      }]
    };
    const config = {
      type: 'pie',
      data: macroData,
      options: {
        responsive: true,
        maintainAspectRatio: false,
      }
    };
    macroChart = new Chart(
      macroElement,
      config
    );
}
// function to plot Micro data returned by the server for the given user
function plotMicro(micro){
    // Canvas element section
    let microElement  = document.querySelector('#Micro');
    let microDiv = document.querySelector('.Micro');
    let microTxt = document.querySelector('.MICRO_text');
    let microDesc = document.querySelector('.MICRO_text_description');
    microTxt.style.display = 'block';
    microDiv.style.display = 'block';
    microDesc.style.display = 'block';
    
    microDesc.innerHTML = 'This text must come from the server about Micro!';
    
    const microData = {
      labels: ['calcium','folate', 'iron', 'vitamin B-6', 'vitamin B-12', 'vitamin C', 'vitamin E', 'zinc'],
      datasets: [{
        data: micro,
        backgroundColor: [
          'coral',
          'lightblue',
          'limegreen',
          'cyan',
          'blue',
          'green',
          'orange',
          'magenta',
        ],
      }]
    };
    const config = {
      type: 'polarArea',
      data: microData,
      options: {
        responsive: true,
        maintainAspectRatio: false,
      }
    };
    microChart = new Chart(
      microElement,
      config
    );
}

// function to display meal plan data returned by the server for the given user
function displayMeal(mealData){
    // Canvas element section
    let meal0_txt  = document.querySelector('.meal_text0');
    let meal1_txt  = document.querySelector('.meal_text1');
    let meal2_txt  = document.querySelector('.meal_text2');
    let meal0  = document.querySelector('.meal_plan0');
    let meal1  = document.querySelector('.meal_plan1');
    let meal2  = document.querySelector('.meal_plan2');


    meal0.style.display = 'block';
    meal1.style.display = 'block';
    meal2.style.display = 'block';
    
    meal0_txt.innerHTML = mealData.info0;
    meal1_txt.innerHTML = mealData.info1;
    meal2_txt.innerHTML = mealData.info2;
}
