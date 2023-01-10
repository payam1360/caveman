/**
* Template Name: HeroBiz - v2.3.1
* Template URL: https://bootstrapmade.com/herobiz-bootstrap-business-template/
* Author: BootstrapMade.com
* License: https://bootstrapmade.com/license/
*/

// AUX functions start here
// function to set styles for animation
function moveRight(moveright, input, header, headerTxt, Questions){
    if (moveright) {
        moveright.addEventListener('click', function(event) {
            
            // validate the current input
            let valid = false;
            let inputStyle = document.querySelector('.form-input-style');
            // get the user answer
            valid = validate_input(inputStyle, inputStyle.value);
            if(valid == true){
                if(Questions[counter].type != 'button') {
                    Questions[counter].answer = inputStyle.value;
                }
            } else {
                window.alert('input incorrect');
            }
            if(valid == true && Questions[counter].visited == false) {
                Questions[counter].visited = true;
                prog++;
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
                restorePrevAnswer();
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
}

function moveLeft(moveleft, input, header, headerTxt, Questions){
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
                ChangeForm(input[1], '0.0s', '0', 1, '40%');
                setFormType(input[1], Questions[counter]);
                restorePrevAnswer();
            });
            ChangeForm(header[1], '0.5s', gap[1].toString(), 0, '0%');
            header[1].addEventListener('transitionend', () => {
                //Reset
                headerTxt[1].innerHTML = Questions[counter].question;
                ChangeForm(header[1], '0.0s', '0', 1, '40%');
            });
        });
    }
}

// function to set the form type
function setFormType(querySelIn, userStruct){
    let newIn = [];
    switch(userStruct.type) {
        case 'text':
            newIn = document.createElement('input');
            newIn.setAttribute('class', 'form-input-style');
            newIn.setAttribute('pattern', '[A-Za-z0-9]+');
            newIn.setAttribute('type', userStruct.type);
            querySelIn.appendChild(newIn);
            querySelIn.style.borderBottom = '2px solid coral';
            break;
        case 'email':
            newIn = document.createElement('input');
            newIn.setAttribute('class', 'form-input-style');
            newIn.setAttribute('pattern', '[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$');
            newIn.setAttribute('type', userStruct.type);
            querySelIn.appendChild(newIn);
            querySelIn.style.borderBottom = '2px solid coral';
            break;
        case 'password':
            newIn = document.createElement('input');
            newIn.setAttribute('class', 'form-input-style');
            newIn.setAttribute('pattern', '.{8,}');
            newIn.setAttribute('type', userStruct.type);
            querySelIn.appendChild(newIn);
            querySelIn.style.borderBottom = '2px solid coral';
            break;
        case 'list':
            let newInList = document.createElement('select');
            newInList.setAttribute('class', 'form-input-style');
            newInList.setAttribute('type', 'list');
            newInList.setAttribute('name', 'inputList');
            newInList.setAttribute('id', 'inputList');
            // placeholder
            let Option = document.createElement('option');
            Option.value = '--Select options--';
            Option.innerHTML = '--Select options--';
            // add the list
            newInList.appendChild(Option);
            userStruct.options.forEach(function(item){
                let Option = document.createElement('option');
                Option.value = item;
                Option.innerHTML = item;
                newInList.appendChild(Option);
            });
            querySelIn.appendChild(newInList);
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
                newImgSpan.appendChild(newI);
                newInbtn.appendChild(newImgSpan);
                querySelIn.appendChild(newInbtn);
                i++;
            });
            querySelIn.style.borderBottom = '2px solid white';
            break;
    }
}

function resetStart(Questions, input, header, headerTxt) {

    let LFT_IDX = 0;
    let MDL_IDX = 1;
    let RHT_IDX = 2;
    // reset the question bar
    input[RHT_IDX].style.width = '0%';
    input[MDL_IDX].style.opacity = 1;
    input[LFT_IDX].style.width = '0%';
    header[RHT_IDX].style.width = '0%';
    header[MDL_IDX].style.opacity = 1;
    header[LFT_IDX].style.width = '0%';
    // initialize header
    headerTxt[MDL_IDX].innerHTML = Questions[counter].question;
    // initialize the input based on form Type
    resetFormType(input[RHT_IDX]);
    resetFormType(input[MDL_IDX]);
    resetFormType(input[LFT_IDX]);
    setFormType(input[MDL_IDX], Questions[counter]);
}

function restorePrevAnswer() {
    // restore the previous answer on the screen
    if(Questions[counter].type == 'text' || Questions[counter].type == 'email' || Questions[counter].type == 'password') {
        let inputStyle = document.querySelector('.form-input-style');
        inputStyle.value = Questions[counter].answer;
    } else if(Questions[counter].type == 'button'){
        
        let formButtonStyle = document.querySelectorAll('.form-button-style');
        for(let kk = 0; kk < formButtonStyle.length; kk++){
            formButtonStyle[kk].style.backgroundColor = '#ffffff';
        }
        if(Questions[counter].answer.length != 0) {
            formButtonStyle[Questions[counter].answer].style.backgroundColor = '#f08080';
        }
        
    } else if(Questions[counter].type == 'list'){
        
        let formButtonStyle = document.querySelector('.form-input-style');
        if(Questions[counter].answer.length == 0) {
            Questions[counter].answer = '--Select options--';
        }
        formButtonStyle.value = Questions[counter].answer;
    }
}


function validate_input(input, Questions){
    if(Questions.type == 'button') {
        if(Questions.answer.length == 0) {
            return(false);
        } else {
            return(true);
        }
    } else if(Questions.type == 'list') {
        
        if(input.value == '--Select options--') {
            return(false);
        } else {
            return(true);
        }
        // use text pattern match results
    } else  {
        return(input.validity.valid);
    }
}
