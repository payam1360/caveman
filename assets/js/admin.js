/*
// Scripts
*/
/**
* Template Name: HeroBiz - v2.3.1
* Template URL: https://bootstrapmade.com/herobiz-bootstrap-business-template/
* Author: BootstrapMade.com
* License: https://bootstrapmade.com/license/
*/
let Questions = [];
let counter = 0;
let prog = 0;
let MAX_cnt = 4;
let userid = Math.round(Math.random() * 1000).toString();

class question {
    constructor(userId, qContent, qAnswer, qIdx, qType, Options, OptionsText){
        this.qContent = qContent; // must be a text string
        this.qAnswer = qAnswer;
        this.qIdx = qIdx;
        this.qType = qType;
        this.options = Options;
        this.optionsText = OptionsText;
        this.userId = userId;
    }
    pushData (){
        Questions.push(this);
    }
};


// event delegate for click on the dynamic button elements
document.addEventListener('click',function(e){
    if(e.target && e.target.id == 'form-button'){
        getUserButtonSelection(e.target.alt, Questions);
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

// function to reset the form type back to normal text
function resetFormType(querySelIn){
    while( querySelIn.childElementCount > 0){
        querySelIn.removeChild(querySelIn.children[0]);
    }
}


function validate_input(input){
    return(true);
}

function getUserButtonSelection(alt, Questions){
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
    if(prog == MAX_cnt) {
        submitUserData(Questions);
    }
}

//
// this function eventually comes from user costomization and design of his app.
function questionCreate(userid){
    let Obj = new question(userid, '1. what is the first question you want to ask your client?', '', 0, 'text', ['']);
    Obj.pushData(Obj);
    Obj = new question(userid, '2. what type of answer you are expecting?', '', 1, 'button', ['fa-regular fa-comment','fa-regular fa-hand-pointer', 'fa-solid fa-list-ol'], ['text answer','select list','multiple choice']);
    Obj.pushData(Obj);
    Obj = new question(userid, '3. what are the choices? (separate with semicolon)', '', 2, 'text', ['']);
    Obj.pushData(Obj);
    Obj = new question(userid, '4. add icons for your options? ex: fa-solid fa-user; fa-regular fa-heart', '', 3, 'text', ['']);
    Obj.pushData(Obj);
}



function submitUserData(inputDataBlob) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            let data = JSON.parse(this.response);
            if(data.ok == true){
            }
            
        }
    };
    // sending the request
    xmlhttp.open("POST", "assets/php/admin.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    var userdata = "userInfo="+JSON.stringify(inputDataBlob);
    xmlhttp.send(userdata);
}

