/**
* Template Name: HeroBiz - v2.3.1
* Template URL: https://bootstrapmade.com/herobiz-bootstrap-business-template/
* Author: BootstrapMade.com
* License: https://bootstrapmade.com/license/
*/
let Questions = [];
let counter = 0;
let prog = 0;
let MAX_cnt = 3;


document.addEventListener('DOMContentLoaded', () => {
  "use strict";
    /* define the class for questions*/
    
    let input = document.querySelectorAll('.form-input');
    let header = document.querySelectorAll('.form-header');
    let headerTxt = document.querySelectorAll('.form-header-style');
    

    // create the questions
    questionCreate();
    // initialize header
    // initialize the first question and init the load page
    resetStart(Questions, input, header, headerTxt);

    const moveright = document.querySelector('.form-go-right');
    moveRight(moveright, input, header, headerTxt, Questions, 'register');
    
    const moveleft = document.querySelector('.form-go-left');
    moveLeft(moveleft, input, header, headerTxt, Questions);
    

});


//
// this function eventually comes from user costomization and design of his app.
function questionCreate(){

    let Obj = new question(0, '1/3. enter your email?', '', 0, 'email', [''], [''], false, true);
    Obj.pushData(Obj);
    Obj = new question(0, '2/3. choose a password?', '', 1, 'password', [''], [''], false, true);
    Obj.pushData(Obj);
    Obj = new question(0, '3/3. re-enter your password?', '', 2, 'password', [''], [''], false, true);
    Obj.pushData(Obj);
}

