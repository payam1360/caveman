/**
* Template Name: HeroBiz - v2.3.1
* Template URL: https://bootstrapmade.com/herobiz-bootstrap-business-template/
* Author: BootstrapMade.com
* License: https://bootstrapmade.com/license/
*/
let Questions = [];
let counter = 0;
let prog = 0;
let MAX_cnt = 0;
let progChart = [];
let ctx = document.querySelector('#ProgressCircle');
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
    let userTxt = document.querySelector('.user-text');
    getUserInfo(userTxt);
    // this function will be getting the info from the Admin Panel.
    // For now, t is generating questions locally
    FirstquestionCreate();
    // initialize the first question and init the load page
    resetStart(Questions, input, header, headerTxt);
    // generate the progress chart
    progChart = new Chart(
      ctx,
      config
    );
    
    const moveright = document.querySelector('.form-go-right');
    moveRight(moveright, input, header, headerTxt, Questions, 'questions');
    
    const moveleft = document.querySelector('.form-go-left');
    moveLeft(moveleft, input, header, headerTxt, Questions);
    
});




