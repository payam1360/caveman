/**
* Template Name: HeroBiz - v2.3.1
* Template URL: https://bootstrapmade.com/herobiz-bootstrap-business-template/
* Author: BootstrapMade.com
* License: https://bootstrapmade.com/license/
*/
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
    
    // initialize the first question and init the load page
    resetStart(input, header, headerTxt, 'main');
    // generate the chart
    progChart = new Chart(
      ctx,
      config
    );
    const moveright = document.querySelector('.form-go-right');
    moveRight(moveright, input, header, headerTxt, Questions, 'main');
    
    const moveleft = document.querySelector('.form-go-left');
    moveLeft(moveleft, input, header, headerTxt, Questions, 'main');
    
});



