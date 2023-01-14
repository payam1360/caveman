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

// event delegate for click on the dynamic button elements


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
    moveRight(moveright, input, header, headerTxt, Questions, 'main');
    
    const moveleft = document.querySelector('.form-go-left');
    moveLeft(moveleft, input, header, headerTxt, Questions);
    
});

//
// this function eventually comes from user customization and design of his app.

function questionCreate(userid){
    let Obj = new question(userid, '1. what is your goal?', '', 0, 'button', ['fa-solid fa-weight-scale','fa-solid fa-dumbbell', 'fa-solid fa-microscope'], ['trying to lose weight', 'increase muscle mass', 'increase Testostrone'], false, true);
    Obj.pushData(Obj);
    Obj = new question(userid, '2. what is your name?', '', 1, 'text', [''], [''], false, true);
    Obj.pushData(Obj);
    Obj = new question(userid, '3. what is your weight?', '', 2, 'list', ['80lb-90lb','90lb-100lb','100lb-110lb','110lb-120lb','120lb-130lb','130lb-140lb','140lb-150lb','150lb-160lb','160lb-170lb','170lb-180lb','180lb-190lb','190lb-200lb','200lb-210lb','210lb-220lb','220lb-230lb','230lb-240lb','240lb-250lb','250lb+'], [''], false, true);
    Obj.pushData(Obj);
    Obj = new question(userid, '4. what is your height?', '', 3, 'list', ['<5ft','5ft-5.1ft','5.1ft-5.2ft','5.2ft-5.3ft','5.3ft-5.4ft','5.4ft-5.5ft','5.5ft-5.6ft','5.6ft-5.7ft','5.7ft-5.8ft','5.8ft-5.9ft','5.9ft-5.10ft','5.10ft-5.11ft','5.11ft-6.0ft','6.0ft-6.1ft','6.1ft-6.2ft','6.2ft-6.3ft','6.3ft-6.4ft','6.4ft-6.5ft', '6.5ft+'], [''], false, true);
    Obj.pushData(Obj);
    Obj = new question(userid, '5. how is your sleep?', '', 4, 'button', ['fa-regular fa-moon','fa-solid fa-battery-quarter'], ['great sleep', 'spuer tired, no sleep'], false, false);
    Obj.pushData(Obj);
    Obj = new question(userid, '6. what is your email?', '', 5, 'email', [''], [''], false, false);
    Obj.pushData(Obj);
    Obj = new question(userid, '7. what is your password?', '', 6, 'password', [''], [''], false, true);
    Obj.pushData(Obj);
}



