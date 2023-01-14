/**
* Template Name: HeroBiz - v2.3.1
* Template URL: https://bootstrapmade.com/herobiz-bootstrap-business-template/
* Author: BootstrapMade.com
* License: https://bootstrapmade.com/license/
*/
let Questions = [];
let counter = 0;
let prog = 0;
let MAX_cnt = 2;
let userid = Math.round(Math.random() * 1000).toString();


document.addEventListener('DOMContentLoaded', () => {
  "use strict";
    /* define the class for questions*/
    
    let input = document.querySelectorAll('.form-input');
    let header = document.querySelectorAll('.form-header');
    let headerTxt = document.querySelectorAll('.form-header-style');
    

    // create the questions
    questionCreate(userid);
    // initialize header
    // initialize the first question and init the load page
    resetStart(Questions, input, header, headerTxt);
    
    // log in button handle
    const register = document.querySelector('.register');
    if (register) {
        register.addEventListener('click', function(event) {
            window.location.assign('register.html');
        })
    }
    
    const moveright = document.querySelector('.form-go-right');
    moveRight(moveright, input, header, headerTxt, Questions, 'login');
    
    
    const moveleft = document.querySelector('.form-go-left');
    moveLeft(moveleft, input, header, headerTxt, Questions);

});


//
// this function eventually comes from user costomization and design of his app.
function questionCreate(userid){

    let Obj = new question(userid, '1. what is your email?', '', 0, 'email', [''], [''], false, true);
    Obj.pushData(Obj);
    Obj     = new question(userid, '2. what is your password?', '', 1, 'password', [''], [''], false, true);
    Obj.pushData(Obj);
}

