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

class question {
    constructor(question, answer, Qidx, Type, Options){
        this.question = question; // must be a text string
        this.answer = answer;
        this.Qidx = Qidx;
        this.type = Type;
        this.options = Options;
    }
    pushData (){
        Questions.push(this);
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
    headerTxt[1].innerHTML = [Questions[counter].question];
    // initialize the input based on form Type
    resetFormType(input[0]);
    resetFormType(input[1]);
    resetFormType(input[2]);
    setFormType(input[1], Questions[counter]);

    
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

//
// this function eventually comes from user costomization and design of his app.
function questionCreate(){

    let Obj = new question('1. what is your username?', '', 0, 'text', ['']);
    Obj.pushData(Obj);
    Obj = new question('2. what is your password?', '', 1, 'text', ['']);
    Obj.pushData(Obj);
}


function submitUserData(inputDataBlob) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            let data = JSON.parse(this.response);
            if(data.ok == true){
                window.location.assign('admin.html');
            }
        }
    };
    // sending the request
    xmlhttp.open("POST", "assets/php/login.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    var userdata = "userInfo="+JSON.stringify(inputDataBlob);
    xmlhttp.send(userdata);
}
