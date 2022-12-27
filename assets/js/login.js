/**
* Template Name: HeroBiz - v2.3.1
* Template URL: https://bootstrapmade.com/herobiz-bootstrap-business-template/
* Author: BootstrapMade.com
* License: https://bootstrapmade.com/license/
*/
let User = [];
let counter = 0;
let prog = 0;
let MAX_cnt = 2;

class user {
    constructor(question, answer){
        this.question = question;
        this.answer = answer; // must be a text string
    }
    pushData (){
        User.push(this);
    }
};


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
    questionCreate();
    // initialize header
    headerTxt[1].innerHTML = [User[counter].question];
    // initialize the input based on form Type
    resetFormType(input[0]);
    resetFormType(input[1]);
    resetFormType(input[2]);
    setFormType(input[1]);
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
            headerTxt[0].innerHTML = User[counter].question;
            // set form 0 type
            resetFormType(input[0]);
            setFormType(input[0]);
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
                setFormType(input[1]);
                ChangeForm(input[1], '0s', '0', 1, '40%');
            });
            
            ChangeForm(header[1], '0.5s', gap[1].toString(), 0, '0%');
            header[1].addEventListener('transitionend', () => {
                //Reset
                headerTxt[1].innerHTML = User[counter].question;
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
            headerTxt[2].innerHTML = User[counter].question;
            // set form 0 type
            resetFormType(input[2]);
            setFormType(input[2]);
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
                setFormType(input[1]);
                ChangeForm(input[1], '0.0s', '0', 1, '40%');
            });
            ChangeForm(header[1], '0.5s', gap[1].toString(), 0, '0%');
            header[1].addEventListener('transitionend', () => {
                //Reset
                headerTxt[1].innerHTML = User[counter].question;
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
                User[counter].answer = inputStyle[0].value;
                inputStyle[0].value = '';
            }
            counter++;
            if(counter == MAX_cnt){
                counter = 0;
            }
            // set form 0 header
            headerTxt[0].innerHTML = User[counter].question;
            // set form 0 type
            resetFormType(input[0]);
            setFormType(input[0]);
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
                setFormType(input[1]);
                ChangeForm(input[1], '0s', '0', 1, '40%');
            });
            
            ChangeForm(header[1], '0.5s', gap[1].toString(), 0, '0%');
            header[1].addEventListener('transitionend', () => {
                //Reset
                headerTxt[1].innerHTML = User[counter].question;
                ChangeForm(header[1], '0.0s', '0', 1, '40%');
            });
            
            if(prog == MAX_cnt) {
                submitUserData(User);
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
function setFormType(querySelIn){

    let newIn = document.createElement('input');
    newIn.setAttribute('class', 'form-input-style');
    newIn.setAttribute('type', 'text');
    querySelIn.appendChild(newIn);
    querySelIn.style.borderBottom = '2px solid coral';
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

//
// this function eventually comes from user costomization and design of his app.
function questionCreate(){

    let Obj = new user('1. what is your username?', '');
    Obj.pushData(Obj);
    Obj = new user('2. what is your password?', '');
    Obj.pushData(Obj);
}


function submitUserData(userDataBlob) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            let data = JSON.parse(this.response);
            if(data.flag == 0){
                window.location.assign('admin.html');
            } else if(data.flag == 2) {
                reg = document.querySelector('.register_txt');
                reg.innerHTML = 'please register';
            }
        }
    };
    // sending the request
    xmlhttp.open("POST", "assets/php/login.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    var userdata = "userInfo="+JSON.stringify(userDataBlob);
    xmlhttp.send(userdata);
}
