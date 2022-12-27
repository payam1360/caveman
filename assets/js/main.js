/**
* Template Name: HeroBiz - v2.3.1
* Template URL: https://bootstrapmade.com/herobiz-bootstrap-business-template/
* Author: BootstrapMade.com
* License: https://bootstrapmade.com/license/
*/
let Questions = [];
let counter = 0;
let prog = 0;
let MAX_cnt = 6;
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
    cutout: 40,
  }
};
class question {
    constructor(userid, question, answer, Qidx, Type, Options){
        this.question = question; // must be a text string
        this.answer = answer;
        this.Qidx = Qidx;
        this.type = Type;
        this.options = Options;
        this.userid = userid;
    }
    pushData (){
        Questions.push(this);
    }
};


// event delegate for click on the dynamic button elements
document.addEventListener('click',function(e){
    if(e.target && e.target.id == 'form-button'){
        getUserButtonSelection(e.target.alt, progChart, Questions);
    }
});

document.addEventListener('DOMContentLoaded', () => {
  "use strict";
    /* define the class for questions*/
    
    let input = document.querySelectorAll('.form-input');
    let header = document.querySelectorAll('.form-header');
    let headerTxt = document.querySelectorAll('.form-header-style');
    let inputStyle = document.querySelectorAll('.form-input-style');

    // reset the question bar
    input[0].style.width = '0%';
    input[1].style.opacity = 1;
    input[2].style.width = '0%';
    header[0].style.width = '0%';
    header[1].style.opacity = 1;
    header[2].style.width = '0%';
    // create the questions
    questionCreate(userid);
    // initialize header
    headerTxt[1].innerHTML = [Questions[counter].question];
    // initialize the input based on form Type
    resetFormType(input[0]);
    resetFormType(input[1]);
    resetFormType(input[2]);
    setFormType(input[1], Questions[counter]);
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
    // log in button handle
    const register = document.querySelector('.register');
    if (register) {
        register.addEventListener('click', function(event) {
            window.location.assign('register.html');
        })
    }
    
    const moveright = document.querySelector('.form-go-right');
    if (moveright) {
        
        moveright.addEventListener('click', function(event) {
            counter++;
            if(counter == MAX_cnt){
                counter = 0;
            }
            // set form 0 header
            headerTxt[0].innerHTML = Questions[counter].question;
            // set form 0 type
            resetFormType(input[0]);
            setFormType(input[0], Questions[counter]);
            let gap = [];
            gap[0] = input[1].getBoundingClientRect().left-input[0].getBoundingClientRect().left;
            gap[1] = input[2].getBoundingClientRect().left-input[1].getBoundingClientRect().left;
            
            ChangeForm(input[0], '0.5s', gap[0].toString(), 1, '40%');
            input[0].addEventListener('transitionend', () => {
                //Reset
                ChangeForm(input[0], '0.0s', '0', 0, '0%');
                resetFormType(input[0]);
            });
            ChangeForm(header[0], '0.5s', gap[0].toString(), 1, '40%');
            header[0].addEventListener('transitionend', () => {
                //Reset
                ChangeForm(header[0], '0.0s', '0', 0, '0%');
            });
            ChangeForm(input[1], '0.5s', gap[1].toString(), 0, '0%');
            input[1].addEventListener('transitionend', () => {
                //Reset
                resetFormType(input[1]);
                setFormType(input[1], Questions[counter]);
                ChangeForm(input[1], '0s', '0', 1, '40%');
            });
            
            ChangeForm(header[1], '0.5s', gap[1].toString(), 0, '0%');
            header[1].addEventListener('transitionend', () => {
                //Reset
                headerTxt[1].innerHTML = Questions[counter].question;
                ChangeForm(header[1], '0.0s', '0', 1, '40%');
            });
      });
    }
    
    const moveleft = document.querySelector('.form-go-left');
    if (moveleft) {
        moveleft.addEventListener('click', function(event) {
            if(counter == 0){
                counter = MAX_cnt;
            }
            counter--;
            headerTxt[2].innerHTML = Questions[counter].question;
            // set form 0 type
            resetFormType(input[2]);
            setFormType(input[2], Questions[counter]);
            let gap = [];
            gap[0] = input[1].getBoundingClientRect().right-input[2].getBoundingClientRect().right;
            gap[1] = input[0].getBoundingClientRect().right-input[1].getBoundingClientRect().right;
            ChangeForm(input[2], '0.5s', gap[0].toString(), 1, '40%');
            input[2].addEventListener('transitionend', () => {
                //Reset
                ChangeForm(input[2], '0.0s', '0', 0, '0%');
                resetFormType(input[2]);
            });
            ChangeForm(header[2], '0.5s', gap[0].toString(), 1, '40%');
            header[2].addEventListener('transitionend', () => {
                //Reset
                ChangeForm(header[2], '0.0s', '0', 0, '0%');
            });
            ChangeForm(input[1], '0.5s', gap[1].toString(), 0, '0%');
            input[1].addEventListener('transitionend', () => {
                //Reset
                resetFormType(input[1]);
                setFormType(input[1], Questions[counter]);
                ChangeForm(input[1], '0.0s', '0', 1, '40%');
            });
            ChangeForm(header[1], '0.5s', gap[1].toString(), 0, '0%');
            header[1].addEventListener('transitionend', () => {
                //Reset
                headerTxt[1].innerHTML = Questions[counter].question;
                ChangeForm(header[1], '0.0s', '0', 1, '40%');
            });
        });
    }
    
    const move_ok = document.querySelector('.form-ok');
    if (move_ok) {
        move_ok.addEventListener('click', function(event) {
            let inputStyle = document.querySelectorAll('.form-input-style');
            let headerTxt = document.querySelectorAll('.form-header-style');
            let input = document.querySelectorAll('.form-input');
            let header = document.querySelectorAll('.form-header');
            let valid = false;
            // get the user answer
            valid = validate_input(inputStyle[0].value);
            if(valid == true){
                prog++;
                Questions[counter].answer = inputStyle[0].value;
                inputStyle[0].value = '';
            }
            counter++;
            if(counter == MAX_cnt){
                counter = 0;
            }
            // set form 0 header
            headerTxt[0].innerHTML = Questions[counter].question;
            // set form 0 type
            resetFormType(input[0]);
            setFormType(input[0], Questions[counter]);
            let gap = [];
            gap[0] = input[1].getBoundingClientRect().left-input[0].getBoundingClientRect().left;
            gap[1] = input[2].getBoundingClientRect().left-input[1].getBoundingClientRect().left;
            
            ChangeForm(input[0], '0.5s', gap[0].toString(), 1, '40%');
            input[0].addEventListener('transitionend', () => {
                //Reset
                ChangeForm(input[0], '0.0s', '0', 0, '0%');
                resetFormType(input[0]);
            });
            ChangeForm(header[0], '0.5s', gap[0].toString(), 1, '40%');
            header[0].addEventListener('transitionend', () => {
                //Reset
                ChangeForm(header[0], '0.0s', '0', 0, '0%');
            });
            ChangeForm(input[1], '0.5s', gap[1].toString(), 0, '0%');
            input[1].addEventListener('transitionend', () => {
                //Reset
                resetFormType(input[1]);
                setFormType(input[1], Questions[counter]);
                ChangeForm(input[1], '0s', '0', 1, '40%');
            });
            
            ChangeForm(header[1], '0.5s', gap[1].toString(), 0, '0%');
            header[1].addEventListener('transitionend', () => {
                //Reset
                headerTxt[1].innerHTML = Questions[counter].question;
                ChangeForm(header[1], '0.0s', '0', 1, '40%');
            });
            
            
            // updating the progress
            let p = (prog / MAX_cnt);
            progChart.data.datasets[0].data.pop(0);
            progChart.data.datasets[0].data.pop(1);
            progChart.data.datasets[0].data.push(p * 100);
            progChart.data.datasets[0].data.push((1 - p) * 100);
            progChart.update();
            let percent = document.querySelector('.progress-percent');
            let p_string = Math.round(p * 100);
            percent.innerHTML = p_string.toString() + '%';
            if(prog == MAX_cnt) {
                submitUserData(Questions);
            }
            });
        }

});


// AUX functions start here



// function to set styles for animation
function ChangeForm(querySel, sec, pixel, opacity, width){
    querySel.style.transitionDuration = sec;
    querySel.style.transform = ["translateX(" + pixel + "px)"];
    querySel.style.opacity = opacity;
    querySel.style.width = width;
}

// function to set the form type
function setFormType(querySelIn, userStruct){
    switch(userStruct.type) {
        case 'text':
            let newIn = document.createElement('input');
            newIn.setAttribute('class', 'form-input-style');
            newIn.setAttribute('type', 'text');
            querySelIn.appendChild(newIn);
            querySelIn.style.borderBottom = '2px solid coral';
            break;
        case 'list':
            let newInList = document.createElement('input');
            newInList.setAttribute('class', 'form-input-style');
            newInList.setAttribute('type', 'text');
            newInList.setAttribute('list', 'inputList');
            newInList.placeholder = 'select';
            querySelIn.appendChild(newInList);
            const dataList = document.createElement("datalist");
            dataList.setAttribute('id', 'inputList');
            userStruct.options.forEach(function(item){
                let Option = document.createElement("option");
                Option.value = item;
                dataList.appendChild(Option);
            });
            querySelIn.appendChild(dataList);
            querySelIn.style.borderBottom = '2px solid coral';
            break;
        case 'button':
            let width = Math.floor(100/userStruct.options.length);
            width = width.toString().concat('%');
            let i = 0;
            userStruct.options.forEach(function(item){
                let newInbtn = document.createElement('button');
                newInbtn.style.width = width;
                newInbtn.type = 'button';
                newInbtn.setAttribute('class', 'form-input-style');
                newInbtn.style.height = '140px';
                let newImg = document.createElement('img');
                newImg.setAttribute('src', item);
                newImg.style.width = '100%';
                newImg.setAttribute('id', 'form-button');
                newImg.setAttribute('class', 'form-button-style');
                newImg.setAttribute('alt', i);
                newImg.style.height = '100px';
                newInbtn.appendChild(newImg);
                querySelIn.appendChild(newInbtn);
                i++;
            });
            querySelIn.style.borderBottom = '2px solid white';
            break;
    }
}

// function to reset the form type back to normal text
function resetFormType(querySelIn){
    while( querySelIn.childElementCount > 0){
        querySelIn.removeChild(querySelIn.children[0]);
    }
}


function validate_input(input){
    return(true);
}

function getUserButtonSelection(alt, progChart, Questions){
    let inputStyle = document.querySelectorAll('.form-input-style');
    let headerTxt = document.querySelectorAll('.form-header-style');
    let input = document.querySelectorAll('.form-input');
    let header = document.querySelectorAll('.form-header');
    Questions[counter].answer = alt;
    counter++;
    prog++;
    if(counter == MAX_cnt){
        counter = 0;
    }
    headerTxt[0].innerHTML = Questions[counter].question;
    resetFormType(input[0]);
    setFormType(input[0], Questions[counter]);
    let gap = [];
    gap[0] = input[1].getBoundingClientRect().left-input[0].getBoundingClientRect().left;
    gap[1] = input[2].getBoundingClientRect().left-input[1].getBoundingClientRect().left;

    ChangeForm(input[0], '0.5s', gap[0].toString(), 1, '40%');
    input[0].addEventListener('transitionend', () => {
        //Reset
        ChangeForm(input[0], '0.0s', '0', 0, '0%');
        resetFormType(input[0]);
    });
    ChangeForm(header[0], '0.5s', gap[0].toString(), 1, '40%');
    header[0].addEventListener('transitionend', () => {
        //Reset
        ChangeForm(header[0], '0.0s', '0', 0, '0%');
    });
    ChangeForm(input[1], '0.5s', gap[1].toString(), 0, '0%');
    input[1].addEventListener('transitionend', () => {
        //Reset
        ChangeForm(input[1], '0.0s', '0', 1, '40%');
        resetFormType(input[1]);
        setFormType(input[1], Questions[counter]);
    });
    ChangeForm(header[1], '0.5s', gap[1].toString(), 0, '0%');
    header[1].addEventListener('transitionend', () => {
        //Reset
        headerTxt[1].innerHTML = Questions[counter].question;
        ChangeForm(header[1], '0.0s', '0', 1, '40%');
    });
    let p = (prog / MAX_cnt);
    let percent = document.querySelector('.progress-percent');
    let p_string = Math.round(p * 100);
    percent.innerHTML = p_string.toString() + '%';
    progChart.data.datasets[0].data.pop(0);
    progChart.data.datasets[0].data.pop(1);
    progChart.data.datasets[0].data.push(p * 100);
    progChart.data.datasets[0].data.push((1 - p) * 100);
    progChart.update();
    if(prog == MAX_cnt) {
        submitUserData(Questions);
    }
}

//
// this function eventually comes from user costomization and design of his app.
function questionCreate(userid){
    let Obj = new question(userid, '1. what is your goal?', '', 0, 'button', ['assets/img/arrow-through-heart.svg','assets/img/bank2.svg','assets/img/cart3.svg']);
    Obj.pushData(Obj);
    Obj = new question(userid, '2. what is your name?', '', 1, 'text', ['']);
    Obj.pushData(Obj);
    Obj = new question(userid, '3. what is your weight?', '', 2, 'list', ['80lb-90lb','90lb-100lb','100lb-110lb','110lb-120lb','120lb-130lb','130lb-140lb','140lb-150lb','150lb-160lb','160lb-170lb','170lb-180lb','180lb-190lb','190lb-200lb','200lb-210lb','210lb-220lb','220lb-230lb','230lb-240lb','240lb-250lb','250lb+']);
    Obj.pushData(Obj);
    Obj = new question(userid, '4. what is your height?', '', 3, 'list', ['<5ft','5ft-5.1ft','5.1ft-5.2ft','5.2ft-5.3ft','5.3ft-5.4ft','5.4ft-5.5ft','5.5ft-5.6ft','5.6ft-5.7ft','5.7ft-5.8ft','5.8ft-5.9ft','5.9ft-5.10ft','5.10ft-5.11ft','5.11ft-6.0ft','6.0ft-6.1ft','6.1ft-6.2ft','6.2ft-6.3ft','6.3ft-6.4ft','6.4ft-6.5ft', '6.5ft+']);
    Obj.pushData(Obj);
    Obj = new question(userid, '5. how is your sleep?', '', 4, 'button', ['assets/img/arrow-through-heart.svg','assets/img/arrow-through-heart.svg']);
    Obj.pushData(Obj);
    Obj = new question(userid, '6. what is your email?', '', 5, 'text', ['']);
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
