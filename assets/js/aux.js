/**
* Template Name: HeroBiz - v2.3.1
* Template URL: https://bootstrapmade.com/herobiz-bootstrap-business-template/
* Author: BootstrapMade.com
* License: https://bootstrapmade.com/license/
*/
let Questions = [];
let counter   = 0;
let prog      = 0;
let progChart = [];
let MAX_cnt   = 0;
// user <-> client class definition
class question {
    constructor(userId, qContent, qAnswer, qIdx, qType, options, optionsText, visited, qRequired){
        this.qContent = qContent; // must be a text string
        this.qAnswer = qAnswer;
        this.qIdx = qIdx;
        this.qType = qType;
        this.qRequired = qRequired;
        this.options = options;
        this.optionsText = optionsText;
        this.visited = visited;
        this.userId = userId;
    }
    pushData (){
        Questions.push(this);
    }
};
// AUX functions start here
// function to set styles for animation
function moveRight(moveright, input, header, headerTxt, Questions, page){
    if (moveright) {
        moveright.addEventListener('click', function(event) {
            // validate the current input
            let valid = false;
            let called = false;
            let inputStyle = document.querySelector('.form-input-style');
            if(Questions[counter].qType != 'message') {
                valid = inputStyle.validity.valid;
            }
            let userResponse = [];
            if(Questions[counter].qType == 'button') {
                userResponse = Questions[counter].qAnswer;
            } else if(Questions[counter].qType != 'message') {
                userResponse = inputStyle.value;
            }
            // get the answer validation
            valid = validate_input(valid, Questions[counter].qType, Questions[counter].qRequired, userResponse);
            if(valid == true){
                if(Questions[counter].qType != 'button') {
                    Questions[counter].qAnswer = inputStyle.value;
                }
            }
            if(valid == true && Questions[counter].visited == false) {
                Questions[counter].visited = true;
                prog++;
            }
            counter++;
            //
            // submit the users data here
            if(counter == MAX_cnt - 1) {
                let resultBtn = document.querySelector('.results-btn');
                moveright.disabled = true;
                moveright.style.opacity = 0;
                if(page == 'register') {
                    let moveleft = document.querySelector('.form-go-left');
                    moveleft.disabled = true;
                    moveleft.style.opacity = 0;
                }
                if(resultBtn){
                    resultBtn.style.display = 'block';
                    resultBtn.addEventListener('click', function(event) {
                        callsubmitUserData('main');
                    });
                }
            }
            if(counter == 1) {
                let moveleft = document.querySelector('.form-go-left');
                if(moveleft.disabled == true) {
                    moveleft.style.opacity = 1;
                    moveleft.disabled = false;
                    moveleft.firstChild.style.color = '#f08080';
                }
            }
            // set form 0 header
            dynamicQcontent(page);
            headerTxt[0].innerHTML = Questions[counter].qContent;
            // set form 0 type
            resetFormType(input[0]);
            setFormType(input[0], Questions[counter]);
            let gap = [];
            gap[0] = input[1].getBoundingClientRect().left-input[0].getBoundingClientRect().left;
            gap[1] = input[2].getBoundingClientRect().left-input[1].getBoundingClientRect().left;
            
            ChangeForm(input[0], '0.5s', gap[0].toString(), 1, '50%');
            input[0].addEventListener('transitionend', () => {
                //Reset
                ChangeForm(input[0], '0.0s', '0', 0, '0%');
                resetFormType(input[0]);
            });
            ChangeForm(header[0], '0.5s', gap[0].toString(), 1, '50%');
            header[0].addEventListener('transitionend', () => {
                //Reset
                ChangeForm(header[0], '0.0s', '0', 0, '0%');
            });
            ChangeForm(input[1], '0.5s', gap[1].toString(), 0, '0%');
            input[1].addEventListener('transitionend', () => {
                //Reset
                resetFormType(input[1]);
                setFormType(input[1], Questions[counter]);
                if(counter == MAX_cnt - 1 && page == 'login' && !called) {
                    callLoginUser(input[1], Questions);
                    called = true;
                }
                if(page == 'register' && !called) {
                    callRegister(input[1], Questions, headerTxt);
                    called = true;
                }
                if(page == 'questions'&& !called) {
                    submitQuestionBackEndData(input[1], Questions, headerTxt);
                    called = true;
                }
                ChangeForm(input[1], '0s', '0', 1, '50%');
                restorePrevAnswer();
            });
            
            ChangeForm(header[1], '0.5s', gap[1].toString(), 0, '0%');
            header[1].addEventListener('transitionend', () => {
                //Reset
                headerTxt[1].innerHTML = Questions[counter].qContent;
                ChangeForm(header[1], '0.0s', '0', 1, '50%');
            });
            
            // updating the progress
            if(page == 'main' || page == 'questions' || page == 'analysis') {
                let p = (prog / (MAX_cnt - 1));
                progChart.data.datasets[0].data.pop(0);
                progChart.data.datasets[0].data.pop(1);
                progChart.data.datasets[0].data.push(p * 100);
                progChart.data.datasets[0].data.push((1 - p) * 100);
                progChart.update();
                
                let percent = document.querySelector('.progress-percent');
                let p_string = Math.round(p * 100);
                percent.innerHTML = p_string.toString() + '%';
            }
        });
    }
}

function moveLeft(moveleft, input, header, headerTxt, Questions, page){
    if (moveleft) {
        moveleft.addEventListener('click', function(event) {
            if(counter == 0) {
                counter = MAX_cnt;
            }
            if(counter == 1 && moveleft.disabled == false){
                moveleft.style.opacity = 0;
                moveleft.disabled = true;
            }
            let moveright = document.querySelector('.form-go-right');
            if(moveright.disabled == true) {
                moveright.style.opacity = 1;
                moveright.disabled = false;
            }
            counter--;
            resetDynamicQcontent(page);
            headerTxt[2].innerHTML = Questions[counter].qContent;
            // set form 0 type
            resetFormType(input[2]);
            setFormType(input[2], Questions[counter]);
            let gap = [];
            gap[0] = input[1].getBoundingClientRect().right-input[2].getBoundingClientRect().right;
            gap[1] = input[0].getBoundingClientRect().right-input[1].getBoundingClientRect().right;
            ChangeForm(input[2], '0.5s', gap[0].toString(), 1, '50%');
            input[2].addEventListener('transitionend', () => {
                //Reset
                ChangeForm(input[2], '0.0s', '0', 0, '0%');
                resetFormType(input[2]);
            });
            ChangeForm(header[2], '0.5s', gap[0].toString(), 1, '50%');
            header[2].addEventListener('transitionend', () => {
                //Reset
                ChangeForm(header[2], '0.0s', '0', 0, '0%');
            });
            ChangeForm(input[1], '0.5s', gap[1].toString(), 0, '0%');
            input[1].addEventListener('transitionend', () => {
                //Reset
                resetFormType(input[1]);
                ChangeForm(input[1], '0.0s', '0', 1, '50%');
                setFormType(input[1], Questions[counter]);
                restorePrevAnswer();
            });
            ChangeForm(header[1], '0.5s', gap[1].toString(), 0, '0%');
            header[1].addEventListener('transitionend', () => {
                //Reset
                headerTxt[1].innerHTML = Questions[counter].qContent;
                ChangeForm(header[1], '0.0s', '0', 1, '50%');
            });
        });
    }
}

function callsubmitUserData(page){
    counter++;
    if(counter == MAX_cnt) {
        let allReq = true;
        counter = 0;
        // check if all required questions are answered correctly
        for(let kk = 0; kk < MAX_cnt; kk++){
            if(Questions[kk].qRequired) {
                if(Questions[kk].visited) {
                    allReq = allReq && true;
                } else {
                    allReq = allReq && false;
                }
            } else {
                allReq = allReq && true;
            }
        }
        if(allReq) {
            
            submitUserData(Questions, page);
        }
    }
}

// function to set the form type
function setFormType(querySelIn, userStruct, serverStruct = 0, serverStructOption = 0){
    let newIn = [];
    
    switch(userStruct.qType[serverStruct]) {
        case 'message':
            mDiv = document.createElement('p');
            mDiv.setAttribute('class', 'message-style');
            mDiv.innerHTML =  userStruct.optionsText[serverStructOption][serverStruct];
            iconDiv = document.createElement('div');
            iDiv = document.createElement('i');
            iDiv.setAttribute('class', 'message-icon ' + userStruct.options[serverStructOption][serverStruct]);
            iDiv.style.display = 'inline-block';
            iDiv.style.color = 'green';
            iDiv.style.height = '80px';
            iconDiv.appendChild(iDiv);
            mDiv.appendChild(iconDiv);
            querySelIn.appendChild(mDiv);
            querySelIn.style.borderBottom = '0px';
            break;
        case 'text':
            newIn = document.createElement('input');
            newIn.setAttribute('class', 'form-input-style');
            newIn.setAttribute('pattern', '[A-Za-z0-9\\s\\?;-]{1,}');
            newIn.setAttribute('required', userStruct.qRequired);
            newIn.setAttribute('type', userStruct.qType[serverStruct]);
            querySelIn.appendChild(newIn);
            querySelIn.style.borderBottom = '2px solid coral';
            break;
        case 'email':
            newIn = document.createElement('input');
            newIn.setAttribute('class', 'form-input-style');
            newIn.setAttribute('pattern', '[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$');
            newIn.setAttribute('type', userStruct.qType[serverStruct]);
            newIn.setAttribute('required', userStruct.qRequired);
            querySelIn.appendChild(newIn);
            querySelIn.style.borderBottom = '2px solid coral';
            break;
        case 'password':
            newIn = document.createElement('input');
            newIn.setAttribute('class', 'form-input-style');
            newIn.setAttribute('pattern', '.{8,}');
            newIn.setAttribute('type', userStruct.qType[serverStruct]);
            newIn.setAttribute('required', userStruct.qRequired);
            querySelIn.appendChild(newIn);
            querySelIn.style.borderBottom = '2px solid coral';
            break;
        case 'list':
            let newInList = document.createElement('select');
            newInList.setAttribute('class', 'form-input-style');
            newInList.setAttribute('type', 'list');
            newInList.setAttribute('name', 'inputList');
            newInList.setAttribute('id', 'inputList');
            newInList.setAttribute('required', userStruct.qRequired);
            // placeholder
            let Option = document.createElement('option');
            Option.value = '--Select options--';
            Option.innerHTML = '--Select options--';
            // add the list
            newInList.appendChild(Option);
            userStruct.options[serverStructOption].forEach(function(item){
                let Option = document.createElement('option');
                Option.value = item;
                Option.innerHTML = item;
                newInList.appendChild(Option);
            });
            querySelIn.appendChild(newInList);
            querySelIn.style.borderBottom = '2px solid coral';
            break;
        case 'button':
            let width = Math.floor(100/userStruct.options[serverStructOption].length);
            width = width.toString().concat('%');
            let i = 0;
            userStruct.options[serverStructOption].forEach(function(item){
                let newInbtn = document.createElement('button');
                newInbtn.style.width = width;
                newInbtn.type = 'button';
                newInbtn.setAttribute('class', 'form-input-style');
                newInbtn.style.height = '140px';
                let newImgSpan = document.createElement('span');
                newImgSpan.setAttribute('class', 'form-button-style');
                newImgSpan.setAttribute('id', 'form-button');
                newImgSpan.setAttribute('alt', i);
                newImgSpan.style.display = 'inline-block';
                newImgSpan.style.height = '140px';
                newImgSpan.style.width = '90%';
                let newI = document.createElement('i');
                newI.setAttribute('class', item);
                newI.setAttribute('id', 'form-button');
                newI.setAttribute('alt', i);
                newI.style.color = 'grey';
                newI.style.height = '50px';
                newI.style.paddingTop = '40px';
                let newP = document.createElement('p');
                newP.setAttribute('class', 'form-button-back-text');
                newP.innerHTML = userStruct.optionsText[serverStructOption][i];
                newP.style.opacity = 0;
                newImgSpan.appendChild(newI);
                newImgSpan.appendChild(newP);
                newImgSpan.addEventListener('mouseenter',function(e) {
                    e.target.children[0].style.opacity = 0;
                    e.target.children[1].style.opacity = 1;
                });
                newImgSpan.addEventListener('mouseleave',function(e) {
                    e.target.children[0].style.opacity = 1;
                    e.target.children[1].style.opacity = 0;
                });
                newImgSpan.addEventListener('click',function(e){
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
                newInbtn.appendChild(newImgSpan);
                querySelIn.appendChild(newInbtn);
                i++;
            });
            querySelIn.style.borderBottom = '2px solid white';
            break;
    }
}

function dynamicQcontent(page) {
    
    if(page == 'clients') {
        let searchTag = [];
        if(Questions[counter-1].qAnswer == 0) {
            searchTag = 'ID';
        } else if(Questions[counter-1].qAnswer == 1) {
            searchTag = 'email';
        } else if(Questions[counter-1].qAnswer == 2) {
            searchTag = 'name';
        }
        let dyno = Questions[counter].qContent[0].replace('#clientsTag', searchTag);
        Questions[counter].qContent[0] = dyno;
    } else if(page == 'main' && counter > 0) {
        let dyno = Questions[counter].qContent[0].replace('#mainNameTag', Questions[counter-1].qAnswer);
        Questions[counter].qContent[0] = dyno;
    } else if (page == 'register' && counter > 0) {
        let dyno = Questions[counter].qContent[0].replace('#nameRegister', Questions[counter-1].qAnswer);
        Questions[counter].qContent[0] = dyno;
    }
}

function resetDynamicQcontent(page) {
    
    if(page == 'clients') {
        let searchTag = [];
        if(Questions[counter].qAnswer == 0) {
            searchTag = 'ID';
        } else if(Questions[counter].qAnswer == 1) {
            searchTag = 'email';
        } else if(Questions[counter].qAnswer == 2) {
            searchTag = 'name';
        }
        let dyno = Questions[counter + 1].qContent[0].replace(searchTag, '#clientsTag');
        Questions[counter + 1].qContent[0] = dyno;
    } else if(page == 'main' && counter == 0) {
        let dyno = Questions[counter + 1].qContent[0].replace(Questions[counter].qAnswer, '#mainNameTag');
        Questions[counter + 1].qContent[0] = dyno;
    } else if (page == 'register' && counter == 0) {
        
        let dyno = Questions[counter + 1].qContent[0].replace(Questions[counter].qAnswer, '#nameRegister');
        Questions[counter + 1].qContent[0] = dyno;
    }
}


function getUserButtonSelection(alt){
    Questions[counter].qAnswer = alt.value;
    let formButtonStyle = document.querySelectorAll('.form-button-style');
    for(let kk = 0; kk < formButtonStyle.length; kk++){
        formButtonStyle[kk].style.backgroundColor = '#ffffff';
    }
    formButtonStyle[alt.value].style.backgroundColor = '#f08080';
}

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

function resetStart(input, header, headerTxt, page) {

    questionCreate(headerTxt[1], input[1], page);
    // reset the question bar
    input[2].style.width = '0%';
    input[1].style.opacity = 1;
    input[0].style.width = '0%';
    header[2].style.width = '0%';
    header[1].style.opacity = 1;
    header[0].style.width = '0%';
    // initialize the input based on form Type
    resetFormType(input[2]);
    resetFormType(input[1]);
    resetFormType(input[0]);
    let moveleft = document.querySelector('.form-go-left');
    moveleft.disabled = true;
    moveleft.style.opacity = 0;
}

function restorePrevAnswer() {
    // restore the previous answer on the screen
    if(Questions[counter].qType == 'text' || Questions[counter].qType == 'email' || Questions[counter].qType == 'password') {
        let inputStyle = document.querySelector('.form-input-style');
        inputStyle.value = Questions[counter].qAnswer;
    } else if(Questions[counter].qType == 'button'){
        
        let formButtonStyle = document.querySelectorAll('.form-button-style');
        for(let kk = 0; kk < formButtonStyle.length; kk++){
            formButtonStyle[kk].style.backgroundColor = '#ffffff';
        }
        if(Questions[counter].qAnswer.length != 0) {
            formButtonStyle[Questions[counter].qAnswer].style.backgroundColor = '#f08080';
        }
        
    } else if(Questions[counter].qType == 'list'){
        
        let formButtonStyle = document.querySelector('.form-input-style');
        if(Questions[counter].qAnswer.length == 0) {
            Questions[counter].qAnswer = '--Select options--';
        }
        formButtonStyle.value = Questions[counter].qAnswer;
    }
}


function validate_input(valid, type, required, value){
    if(type == 'button') {
        if(required && value.length == 0) {
            return(false && valid);
        } else {
            return(true && valid);
        }
    } else if(type == 'list') {
        
        if(value == '--Select options--') {
            return(false && valid);
        } else {
            return(true && valid);
        }
        // use text pattern match results
    } else  {
        return(valid);
    }
}


function callLoginUser(querySelIn, inputDataBlob){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            let data = JSON.parse(this.response);
            if(data.status == 0) {
                window.location.assign('admin.html');
            } else {
                resetFormType(querySelIn);
                setFormType(querySelIn, inputDataBlob[counter], data.status);
            }
        }
    };
    // sending the request
    xmlhttp.open("POST", "assets/php/login.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    var userdata = "userInfo="+JSON.stringify(inputDataBlob);
    xmlhttp.send(userdata);
}


function callRegister(querySelIn, inputDataBlob, headerTxt) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            let data = JSON.parse(this.response);
            if(data.status == 0) {
                resetFormType(querySelIn);
                setFormType(querySelIn, inputDataBlob[counter], 0, 1);
            } else if(data.status == 1) {
                resetFormType(querySelIn);
                setFormType(querySelIn, inputDataBlob[counter], 0, 1);
            } else if(data.status == 2) {
                resetFormType(querySelIn);
                setFormType(querySelIn, inputDataBlob[counter], 0, 0);
                let moveright = document.querySelector('.form-go-right');
                moveright.style.opacity = 0;
                moveright.disabled = true;
                headerTxt[1].innerHTML = '';
            } else if(data.status == 3) {
                resetFormType(querySelIn);
                setFormType(querySelIn, inputDataBlob[counter], 0, 0);
                let moveright = document.querySelector('.form-go-right');
                moveright.style.opacity = 0;
                moveright.disabled = true;
            } else if(data.status == 4) {
                resetFormType(querySelIn);
                setFormType(querySelIn, inputDataBlob[counter], 0, 0);
            } else if(data.status == 5) {
                resetFormType(querySelIn);
                setFormType(querySelIn, inputDataBlob[counter], 0, 1);
            }
        }
    };
    // sending the request
    xmlhttp.open("POST", "assets/php/register.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    var userdata = "userInfo="+JSON.stringify(inputDataBlob);
    xmlhttp.send(userdata);
}

// submitting the form
function submitQuestionBackEndData(querySelIn, inputDataBlob, headerTxt) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            let data = JSON.parse(this.response);
            //console.log(this.response)
            if(data.status == 0) {
                resetFormType(querySelIn);
                setFormType(querySelIn, inputDataBlob[counter], 0, 1);
                let moveright = document.querySelector('.form-go-right');
                moveright.style.opacity = 0;
                moveright.disabled = true;
            } else if(data.status == 1) {
                resetFormType(querySelIn);
                setFormType(querySelIn, inputDataBlob[counter], 1, 0);
                headerTxt[1].innerHTML = inputDataBlob[counter].qContent[1];
            } else if(data.status == 2) {
                resetFormType(querySelIn);
                setFormType(querySelIn, inputDataBlob[counter], 0, 0);
                headerTxt[1].innerHTML = inputDataBlob[counter].qContent[0];
            } else if(data.status == 3) {
                resetFormType(querySelIn);
                setFormType(querySelIn, inputDataBlob[counter], 1, 0);
                headerTxt[1].innerHTML = inputDataBlob[counter].qContent[1];
            } else if(data.status == 4) {
                resetFormType(querySelIn);
                setFormType(querySelIn, inputDataBlob[counter], 1, 0);
                headerTxt[1].innerHTML = inputDataBlob[counter].qContent[1];
            } else if(data.status == 5) {
                resetFormType(querySelIn);
                setFormType(querySelIn, inputDataBlob[counter], 0, 0);
                headerTxt[1].innerHTML = inputDataBlob[counter].qContent[0];
            } else if(data.status == 6) {
                resetFormType(querySelIn);
                setFormType(querySelIn, inputDataBlob[counter], 1, 0);
                headerTxt[1].innerHTML = inputDataBlob[counter].qContent[1];
            } else if(data.status == 7) {
                resetFormType(querySelIn);
                setFormType(querySelIn, inputDataBlob[counter], 1, 0);
                headerTxt[1].innerHTML = inputDataBlob[counter].qContent[1];
            } else if(data.status == 8) {
                resetFormType(querySelIn);
                setFormType(querySelIn, inputDataBlob[counter], 1, 0);
                headerTxt[1].innerHTML = inputDataBlob[counter].qContent[1];
            } else if(data.status == 9) {
                resetFormType(querySelIn);
                setFormType(querySelIn, inputDataBlob[counter], 0, 0);
                headerTxt[1].innerHTML = inputDataBlob[counter].qContent[0];
            }
        }
    };
    // sending the request
    xmlhttp.open("POST", "assets/php/questions.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    let inputDataAndCntBlob = {'data': inputDataBlob, 'counter': counter};
    var userdata = "userInfo="+JSON.stringify(inputDataAndCntBlob);
    xmlhttp.send(userdata);
}

// submitting the form
function submitUserData(inputDataBlob, page) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            let data = JSON.parse(this.response);
            if(data.status == 0 && page == 'main'){
                plotBmi(data.bmi);
                plotIf(data.If);
                plotMacro(data.macro);
                plotMicro(data.micro);
                displayMeal();
            } else if(data.status == 0 && page == 'questions') {
                window.alert('data saved');
            } else if(data.status == 0 && page == 'analysis') {
                window.alert('data saved');
            }
        }
    };
    
    // sending the request
    xmlhttp.open("POST", "assets/php/" + page + ".php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    var userdata = "userInfo="+JSON.stringify(inputDataBlob);
    xmlhttp.send(userdata);
}


function getUserInfo(userTxt, welcomeTxt){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            user = JSON.parse(this.response);
            userTxt.innerHTML = user.username;
            welcomeTxt.innerHTML = 'What\'s up ' + user.username;
        }
    };
    
    // sending the request
    xmlhttp.open("GET", "assets/php/admin.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send();
}


// this function eventually comes from user costomization and design of his app.
function questionCreate(headerTxt, input, page){

    let xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            let data = JSON.parse(this.response);
            
            MAX_cnt = data.MAX_cnt;
            let Obj = new question();
            for(let kk = 0; kk < MAX_cnt; kk++){
                Obj = new question(kk, data.qContent[kk], '', data.qIdx[kk], data.qType[kk],
                                   data.options[kk], data.optionsText[kk], false, data.qRequired[kk]);
                Obj.pushData(Obj);
            }
            // initialize header
            headerTxt.innerHTML = Questions[counter].qContent;
            // initialize the input based on form Type
            setFormType(input, Questions[counter]);
        }
    };
    // sending the request
    xmlhttp.open("POST", "assets/php/filler.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    let request = "request="+JSON.stringify(page);
    xmlhttp.send(request);
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
function displayMeal(){
    
    let eventSource = new EventSource("/assets/php/ai.php");
    let meal_txt = document.querySelector('.meal_text');
    let meal = document.querySelector('.meal_plan');
    meal.style.display = 'block';
    meal_txt.innerHTML = '<br> The following information is created by the openAI chatGPT:<br><br>';
    eventSource.onmessage = function (e) {
        if(e.data == "[DONE]")
        {
            meal_txt.innerHTML += "<br><br>Thank you!";
            eventSource.close();
        }
        else {
            meal_txt.innerHTML += JSON.parse(e.data).choices[0].text;
        }
    };
}


// function to handle listing clients
function listClients(clients, Questions) {
    if(clients) {
        clients.forEach(item => item.addEventListener('click', function(event) {
            item.style.width = '60%';
            item.style.height = '85%';
            item.style.bottom = '5%';
            item.style.borderRadius = '1%'
            item.style.backgroundColor = 'lightblue';
            item.style.position = 'fixed';
            item.style.zIndex = '2';
            item.style.opacity = 0.8;
        }))
    }
    
}

