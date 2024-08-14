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
let globalQidx = 0;
let choiceTracker = [[0], [0]];
let multiButtonSelect = [];
let eventSourceQueue = {Bmi:false, If:false, Macro:false, MicroTrace:false, MicroVit:false, Cal:false, Meal:false};
let allowNewAiStream = true;
let intervalID = [];
let multiTextSelect = [];
let stripe = Stripe('pk_test_51Odb1JGvkwgMtml81N0ajd4C9xKKHD9DhnMhcfyBegjRS8eatgXQdBj1o2fnlpwCcEHOZrJJ7Sd7D0UJqXipzRmQ00CPr9wDNl');
let stripeElements;
let gSearchClients = [];
// user <-> client class definition
class question {
    constructor(userId, qContent, qAnswer, qIdx, qType, options, optionsText, visited, qRequired, qKey, clientId, campaignId){
        this.qContent = qContent; // must be a text string
        this.qAnswer = qAnswer;
        this.qIdx = qIdx;
        this.qType = qType;
        this.qRequired = qRequired;
        this.options = options;
        this.optionsText = optionsText;
        this.visited = visited;
        this.userId = userId;
        this.clientId = clientId;
        this.campaignId = campaignId;
        this.qKey   = qKey;
    }
    pushData (){
        Questions.push(this);
    }
    popData (){
        Questions.pop();
    }
};

// AUX functions start here
// function to set styles for animation
function moveRight(moveright, input, header, headerTxt, Questions, page){
    if (moveright) {
        moveright.addEventListener('click', function(event) {
            // validate the current input
            let serverStruct = choiceTracker[0].pop();
            let valid      = false;
            let inputStyle = document.querySelector('.form-input-style');
            if(Questions[counter].qType[serverStruct] != 'message' && Questions[counter].qType[serverStruct] != 'stripe') {
                valid = inputStyle.validity.valid;
            }
            let userResponse = [];
            if(Questions[counter].qType[serverStruct] == 'button' || Questions[counter].qType[serverStruct] == 'multiButton') {
                userResponse = Questions[counter].qAnswer;
            } else if(Questions[counter].qType[serverStruct] != 'message' && Questions[counter].qType[serverStruct] != 'stripe') {
                userResponse = inputStyle.value;
            }
            // get the answer validation
            valid = validate_input(valid, Questions[counter].qType[serverStruct], Questions[counter].qRequired, userResponse);
            if(valid == true){
                Questions[counter].qAnswer = userResponse;
            }
            choiceTracker[0].push(serverStruct);
            if(valid == true && Questions[counter].visited == false) {
                Questions[counter].visited = true;
                prog++;
            }
            counter++;
            //
            // submit the users data here
            if(counter == MAX_cnt - 1 && page != 'questions') {
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
            if(page == 'register' && counter == 3) {
                let spinner   = document.querySelector('.spinner-js');
                spinner.style.opacity = 1;
            }

            if(counter == 1) {
                let moveleft = document.querySelector('.form-go-left');
                if(moveleft.disabled == true) {
                    moveleft.style.opacity = 1;
                    moveleft.disabled = false;
                }
            }
            dynamicQcontent('name');
            
            if(counter == MAX_cnt - 1 && page == 'login') {
                callLoginUser(header, headerTxt, input, Questions);
            }
            else if(page == 'register') {
                if(counter == MAX_cnt - 1 && MAX_cnt == 10) { // confirm payment here
                    confirmStripePayment();
                    transition2Right(header, headerTxt, input, Questions, 0, 0);
                } else {
                    callRegister(header, headerTxt, input, Questions);
                }
            }
            else if(page == 'questions') {
                submitQuestionBackEndData(header, headerTxt, input, Questions);
            }
            else if(counter == MAX_cnt - 1 && page == 'addClients') {
                transition2Right(header, headerTxt, input, Questions, 0, 0);
                submitaddClients(header, headerTxt, input, Questions);
            }else {
                transition2Right(header, headerTxt, input, Questions, 0, 0);
            }
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

function transition2Right(header, headerTxt, input, Questions, serverStruct = 0, serverStructOption = 0) {
    
    headerTxt[0].innerHTML = Questions[counter].qContent[serverStruct];
    // set form 0 type
    choiceTracker[0].push(serverStruct);
    choiceTracker[1].push(serverStructOption);
    resetFormType(input[0]);
    setFormType(input[0], Questions[counter], serverStruct, serverStructOption);
    let gap = [];
    gap[0] = input[1].getBoundingClientRect().left-input[0].getBoundingClientRect().left;
    gap[1] = input[2].getBoundingClientRect().left-input[1].getBoundingClientRect().left;
    header[0].setAttribute('serverStruct', serverStruct);
    ChangeForm(header[0], '0.5s', gap[0].toString(), 1, '50%');
    header[1].setAttribute('serverStruct', serverStruct);
    ChangeForm(header[1], '0.5s', gap[1].toString(), 0, '0%');
    input[0].setAttribute('serverStruct', serverStruct);
    input[0].setAttribute('serverStructOption', serverStructOption);
    ChangeForm(input[0], '0.5s', gap[0].toString(), 1, '50%');
    input[1].setAttribute('serverStruct', serverStruct);
    input[1].setAttribute('serverStructOption', serverStructOption);
    ChangeForm(input[1], '0.5s', gap[1].toString(), 0, '0%');
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
            resetDynamicQcontent('name');
            transition2Left(header, headerTxt, input, Questions);
        });
    }
}


function transition2Left(header, headerTxt, input, Questions) {
    
    // Need to pop twice to get back to the state of the previous
    choiceTracker[0].pop();
    choiceTracker[1].pop();
    serverStruct = choiceTracker[0].pop();
    serverStructOption = choiceTracker[1].pop();
    choiceTracker[0].push(serverStruct);
    choiceTracker[1].push(serverStructOption);
    headerTxt[2].innerHTML = Questions[counter].qContent[serverStruct];
    // set form 0 type
    resetFormType(input[2]);
    setFormType(input[2], Questions[counter], serverStruct, serverStructOption);
    let gap = [];
    gap[0] = input[1].getBoundingClientRect().right-input[2].getBoundingClientRect().right;
    gap[1] = input[0].getBoundingClientRect().right-input[1].getBoundingClientRect().right;
    
    input[2].setAttribute('serverStruct', serverStruct);
    input[2].setAttribute('serverStructOption', serverStructOption);
    ChangeForm(input[2], '0.5s', gap[0].toString(), 1, '50%');
    header[2].setAttribute('serverStruct', serverStruct);
    ChangeForm(header[2], '0.5s', gap[0].toString(), 1, '50%');
    input[1].setAttribute('serverStruct', serverStruct);
    input[1].setAttribute('serverStructOption', serverStructOption);
    ChangeForm(input[1], '0.5s', gap[1].toString(), 0, '0%');
    header[1].setAttribute('serverStruct', serverStruct);
    ChangeForm(header[1], '0.5s', gap[1].toString(), 0, '0%');
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
            submitUserData(Questions, page, Questions[0].userId + Questions[0].clientId + Questions[0].campaignId);
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
            mDiv.innerHTML =  userStruct.optionsText[serverStruct][serverStructOption];
            iconDiv = document.createElement('div');
            iDiv = document.createElement('i');
            iDiv.setAttribute('class', 'message-icon ' + userStruct.options[serverStruct][serverStructOption]);
            iDiv.style.display = 'inline-block';
            iDiv.style.color = 'mediumseagreen';
            iDiv.style.height = '80px';
            iconDiv.appendChild(iDiv);
            mDiv.appendChild(iconDiv);
            querySelIn.appendChild(mDiv);
            querySelIn.style.borderBottom = '0px';
            break;
        case 'text':
            newIn = document.createElement('input');
            newIn.setAttribute('class', 'form-input-style');
            newIn.setAttribute('pattern', '[A-Za-z0-9 _.,!#@"\'/$\\s\\?;-]{1,}');
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
            userStruct.options[serverStruct].forEach(function(item){
                let Option = document.createElement('option');
                Option.value = item;
                Option.innerHTML = item;
                newInList.appendChild(Option);
            });
            querySelIn.appendChild(newInList);
            querySelIn.style.borderBottom = '2px solid coral';
            break;
        case 'button':
            let width = Math.floor(100/userStruct.options[serverStruct].length);
            width = width.toString().concat('%');
            let i = 0;
            userStruct.options[serverStruct].forEach(function(item){
                let newInbtn = document.createElement('button');
                newInbtn.style.width = width;
                newInbtn.type = 'button';
                newInbtn.setAttribute('class', 'form-input-style');
                newInbtn.style.height = '165px';
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
                newI.style.color = 'mediumseagreen';
                newI.style.height = '50px';
                newI.style.paddingTop = '40px';
                let newP = document.createElement('p');
                newP.setAttribute('class', 'form-button-back-text');
                newP.innerHTML = userStruct.optionsText[serverStruct][i];
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
        case 'multiButton':
            // the Line input
            newIn = document.createElement('input');
            newIn.setAttribute('class', 'form-input-style');
            newIn.setAttribute('pattern', '[A-Za-z0-9 _.,!#@"\'/$\\s\\?;-]{1,}');
            newIn.setAttribute('required', userStruct.qRequired);
            newIn.setAttribute('type', userStruct.qType[serverStruct]);
            newIn.setAttribute('id', 'multi-select-text');
            newIn.style.borderBottom = '2px solid coral';
            newIn.style.height = '70px';
            newIn.style.marginBottom = '30px';
            querySelIn.appendChild(newIn);
            // the button array
            let numCol = 5;
            let widthiCon = Math.floor(100 / numCol);
            widthiCon = widthiCon.toString().concat('%');
            let iconCount = 0;
            userStruct.options[serverStruct].forEach(function(item){
                let newInbtn = document.createElement('button');
                newInbtn.style.width = widthiCon;
                newInbtn.type = 'button';
                newInbtn.setAttribute('class', 'form-input-style');
                newInbtn.setAttribute('id', 'form-multiButton-id');
                newInbtn.style.height = '60px'; // this has to be calculated
                newInbtn.style.marginBottom = '20px'; // this has to be calculated
                let newImgSpan = document.createElement('span');
                newImgSpan.setAttribute('id', 'form-button');
                newImgSpan.setAttribute('class', 'form-multiButton-style');
                newImgSpan.setAttribute('alt', iconCount);
                newImgSpan.style.display = 'inline-block';
                newImgSpan.style.height = '50px'; // this has to be calculated
                newImgSpan.style.width = widthiCon;
                let newI = document.createElement('i');
                newI.setAttribute('class', item);
                newI.setAttribute('id', 'form-button');
                newI.setAttribute('alt', iconCount);
                newI.style.color = 'mediumseagreen';
                newI.style.height = '40px';
                newI.style.paddingTop = '5px';
                let newP = document.createElement('p');
                newP.setAttribute('class', 'multiButton-tooltip');
                newP.innerHTML = userStruct.optionsText[serverStruct][iconCount];
                newImgSpan.appendChild(newI);
                newImgSpan.appendChild(newP);
                newImgSpan.addEventListener('mouseenter',function(e) {
                    e.target.children[1].style.opacity = 0.7;
                });
                newImgSpan.addEventListener('mouseleave',function(e) {
                    e.target.children[1].style.opacity = 0;
                });
                
                newImgSpan.addEventListener('click',function(e){
                    let alt = [];
                    if(e.target && e.target.id == 'form-button') {
                        alt = e.target.attributes.alt;
                        getUsermultiButtonSelection(alt, newP.innerHTML);
                    }
                    else if(e.target && e.target.parentNode.id == 'form-button') {
                        alt = e.target.parentNode.attributes.alt;
                        getUsermultiButtonSelection(alt, newP.innerHTML);
                    }
                    else if(e.target && e.target.firstChild && e.target.firstChild.id == 'form-button') {
                        alt = e.target.firstChild.attributes.alt;
                        getUsermultiButtonSelection(alt, newP.innerHTML);
                    }
                });
                newInbtn.appendChild(newImgSpan);
                querySelIn.appendChild(newInbtn);
                if(iconCount % numCol == 0) {
                    let brEl1 = document.createElement("br");
                    let brEl2 = document.createElement("br");
                    let dynamicSpace = document.getElementById("dynamicSpace");
                    dynamicSpace.appendChild(brEl1);
                    dynamicSpace.appendChild(brEl2);
                }
                iconCount++;
            });
            querySelIn.style.borderBottom = '2px solid white';
        break; 
        // implement stripe elements
        case 'stripe':
            const appearance = {
                theme: 'flat',
                variables: {
                    colorPrimary: '#0570de',
                    colorBackground: '#ffffff',
                    colorText: 'mediumseagreen',
                    colorDanger: '#df1b41',
                    fontFamily: 'Lucida Casual, Comic Sans MS',
                    fontSize: '20px',
                    spacingUnit: '0px',
                    borderRadius: '10px',
                    // See all possible variables below
                },
                rules: {
                    '.Input': {
                      border: '1px solid coral',
                    },
                    '.Label': {
                        opacity: 0,
                    }
                }
            };
            stripeElements = stripe.elements({
                mode: 'payment',
                currency: 'usd',
                amount: 50, // cents
                appearance: appearance,
            });
            
            let paymentElement = stripeElements.create('payment', {
                paymentMethodOrder: ['card'],
            });
            let stripeCard = document.createElement('div');
            stripeCard.setAttribute('id', 'stripeId');
            querySelIn.appendChild(stripeCard);
            paymentElement.mount('#stripeId');
            querySelIn.style.borderBottom = '0px solid coral';
            
        break;

    }
}


function dynamicQcontent(context) {
    
    if(Questions[counter].qContent[0].includes('#dynomicContent')){
        for(kk = 0; kk < counter; kk++){
            if(Questions[kk].qKey == context){
                let dyno = Questions[counter].qContent[0].replace('#dynomicContent', Questions[kk].qAnswer + '!!');
                Questions[counter].qContent[0] = dyno;
            }
        }
    } 
}

function resetDynamicQcontent(context) {
    if(Questions[counter + 1].qContent[0].includes('!!')){
        for(kk = 0; kk < counter + 1; kk++){
            if(Questions[kk].qKey == context){
                let dyno = Questions[counter + 1].qContent[0].replace(Questions[kk].qAnswer, '#dynomicContent');
                Questions[counter + 1].qContent[0] = dyno;
            }
        }
    } 
}

function getUserButtonSelection(alt){
    Questions[counter].qAnswer = alt.value;
    let formButtonStyle = document.querySelectorAll('.form-button-style');
    for(let kk = 0; kk < formButtonStyle.length; kk++){
        formButtonStyle[kk].style.backgroundColor = '#ffffff';
    }
    formButtonStyle[alt.value].style.backgroundColor = 'lightgrey';
}

function getUsermultiButtonSelection(alt, selectedText){

    // set the button selection and text selection
    // button -> options icons attribute
    // texts -> optionsText attribute
    if(multiButtonSelect.includes(alt.value)) {
        multiButtonSelect.splice(multiButtonSelect.indexOf(alt.value), 1);
        multiTextSelect.splice(multiTextSelect.indexOf(selectedText), 1);
    } else {
        multiButtonSelect.push(alt.value);
        multiTextSelect.push([selectedText, '::']);
    }
    Questions[counter].qAnswer = multiButtonSelect;
    let formButtonStyleId = document.querySelectorAll('#form-multiButton-id');
    for(let kk = 0; kk < formButtonStyleId.length; kk++){
        formButtonStyleId[kk].style.backgroundColor = '#ffffff';
    }
    for(let kk = 0; kk < multiButtonSelect.length; kk++){
        formButtonStyleId[multiButtonSelect[kk]].style.backgroundColor = 'lightgrey';
    }
    // set the text for the user to know what he has selected
    let formMultiTextId = document.querySelector('#multi-select-text');
    formMultiTextId.value = multiTextSelect;

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

function resetStart(input, header, headerTxt, page, questionPageResetFlag = 0) {

    let clientId = document.querySelector('.clientId');
    let campaignId = document.querySelector('.campaignId');
    let userId = document.querySelector('.userId');

    if(clientId != null && campaignId != null && userId != null) {
        userPage = userId.innerHTML + clientId.innerHTML + campaignId.innerHTML;
    } else if(clientId == null && campaignId && userId){
        userPage = userId.innerHTML + campaignId.innerHTML;
    } else {
        userPage = 0;
    }

    // reset the question bar
    input[2].style.width = '0%';
    input[2].setAttribute('serverStruct', 0);
    input[2].setAttribute('serverStructOption', 0);
    input[1].style.opacity = 1;
    input[1].setAttribute('serverStruct', 0);
    input[1].setAttribute('serverStructOption', 0);
    input[0].style.width = '0%';
    input[0].setAttribute('serverStruct', 0);
    input[0].setAttribute('serverStructOption', 0);
    header[2].style.width = '0%';
    header[2].setAttribute('serverStruct', 0);
    header[1].style.opacity = 1;
    header[1].setAttribute('serverStruct', 0);
    header[0].style.width = '0%';
    header[0].setAttribute('serverStruct', 0);

    if(questionPageResetFlag){
        counter = 1;
        input[2].setAttribute('serverStruct', 1);
        input[2].setAttribute('serverStructOption', 0);
        input[1].setAttribute('serverStruct', 1);
        input[1].setAttribute('serverStructOption', 0);
        input[0].setAttribute('serverStruct', 1);
        input[0].setAttribute('serverStructOption', 0);
        
        header[2].setAttribute('serverStruct', 1);
        header[1].setAttribute('serverStruct', 1);
        header[0].setAttribute('serverStruct', 1);
    }else {
        counter = 0;
    }
    questionCreate(headerTxt[1], header[1], input[1], page, userPage);

    // initialize the input based on form Type
    resetFormType(input[2]);
    resetFormType(input[1]);
    resetFormType(input[0]);
    let moveleft = document.querySelector('.form-go-left');  
    let spinner = document.querySelector('.spinner-js');
    moveleft.disabled = true;
    moveleft.style.opacity = 0;

    header[0].addEventListener('transitionend', function(event) {
        ChangeForm(event.target, '0.0s', '0', 0, '0%');
    });
    header[1].addEventListener('transitionend', function(event) {
        headerTxt[1].innerHTML = Questions[counter].qContent[event.target.getAttribute('serverStruct')];
        ChangeForm(event.target, '0.0s', '0', 1, '50%');
    });
    header[2].addEventListener('transitionend', function(event) {
        ChangeForm(event.target, '0.0s', '0', 0, '0%');
    });

    input[0].addEventListener('transitionend', function(event) {
        ChangeForm(event.target, '0.0s', '0', 0, '0%');
        resetFormType(event.target);
    });
    input[1].addEventListener('transitionend', function(event) {
        resetFormType(event.target);
        setFormType(event.target, Questions[counter], event.target.getAttribute('serverStruct'), event.target.getAttribute('serverStructOption'));
        ChangeForm(event.target, '0s', '0', 1, '50%');
        restorePrevAnswer(event.target.getAttribute('serverStruct'), event.target.getAttribute('serverStructOption'));
        if(spinner != null && spinner.style.opacity != 0){
            spinner.style.opacity = 0;
        }
    });
    // enter keypress also works for navigation
    input[1].addEventListener('keypress', function(s) {
        if (s.key == "Enter") {
           s.preventDefault();
        }
    });

    // dynamic search clients
    // enter keypress also works for navigation
    if(page == 'clients') {
        input[1].addEventListener('keydown', function(s) {
            if(s.key == 'Backspace'){
                if(gSearchClients.length > 0) {
                    gSearchClients = gSearchClients.slice(0, gSearchClients.length - 1);
                } else if(gSearchClients.length == 0) {
                    gSearchClients = gSearchClients;
                }
            } else {
                gSearchClients += s.key; 
            }
            let searchStruct = {'searchStr': gSearchClients, 'key': Questions[0].qAnswer};
            searchClients(searchStruct);
        });
    }

    input[2].addEventListener('transitionend', function(event) {
        ChangeForm(event.target, '0.0s', '0', 0, '0%');
        resetFormType(event.target);
    });
    
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
            stripe = Stripe('pk_test_51Odb1JGvkwgMtml81N0ajd4C9xKKHD9DhnMhcfyBegjRS8eatgXQdBj1o2fnlpwCcEHOZrJJ7Sd7D0UJqXipzRmQ00CPr9wDNl');
        })
    }

    prog = 0;
    //flush the choiceTracker
    choiceTracker = [[0],[0]];
    multiButtonSelect = [];
    multiTextSelect = [];
}


function searchClients(searchStruct) {
    fetchClients(searchStruct);
}


function restorePrevAnswer(serverStruct = 0, serverStructOption = 0) {
    
    if(Questions[counter].qType[serverStruct] == 'text' || Questions[counter].qType[serverStruct] == 'email' || Questions[counter].qType[serverStruct] == 'password') {
        let inputStyle = document.querySelector('.form-input-style');
        
        inputStyle.value = Questions[counter].qAnswer;
        
    } else if(Questions[counter].qType[serverStruct] == 'button'){
        let formButtonStyle = document.querySelectorAll('.form-button-style');
        for(let kk = 0; kk < formButtonStyle.length; kk++){
            formButtonStyle[kk].style.backgroundColor = '#ffffff';
        }
        if(Questions[counter].qAnswer.length != 0 ) {
            formButtonStyle[Number(Questions[counter].qAnswer)].style.backgroundColor = 'lightgrey';
        }
            
    } else if(Questions[counter].qType[serverStruct] == 'list'){
        
        let formButtonStyle = document.querySelector('.form-input-style');
        if(Questions[counter].qAnswer.length == 0) {
            Questions[counter].qAnswer = '--Select options--';
        }
        formButtonStyle.value = Questions[counter].qAnswer;
    }
}


function validate_input(valid, type, required, value){
    if(type == 'button' || type == 'multiButton') {
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
    } else if(type == 'text' && value.length == 0) {
        return(false && valid);
    } else {
        return (valid);
    }
}


function callLoginUser(header, headerTxt, querySelIn, inputDataBlob){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            let data = JSON.parse(this.response);
            if(data.status == 0) {
                transition2Right(header, headerTxt, querySelIn, inputDataBlob, 0, 0);
                window.location.assign('admin.html');
            } else if(data.status == 1) {
                transition2Right(header, headerTxt, querySelIn, inputDataBlob, 0, 1);
            } else if(data.status == 2) {
                transition2Right(header, headerTxt, querySelIn, inputDataBlob, 0, 2);
            }
        }
    };
    // sending the request
    xmlhttp.open("POST", "assets/php/login.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    var userdata = "userInfo="+JSON.stringify(inputDataBlob);
    xmlhttp.send(userdata);
}


function callRegister(header, headerTxt, querySelIn, inputDataBlob) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            let data = JSON.parse(this.response);
            MAX_cnt = data.MAX_cnt;
            if(data.status == 0) {
                transition2Right(header, headerTxt, querySelIn, inputDataBlob, 0, 0);
            } else if(data.status == 1) {
                transition2Right(header, headerTxt, querySelIn, inputDataBlob, 0, 1);
            } else if(data.status == 2) {
                transition2Right(header, headerTxt, querySelIn, inputDataBlob, 0, 0);
            } else if(data.status == 3) {
                transition2Right(header, headerTxt, querySelIn, inputDataBlob, 0, 0);
                let moveright = document.querySelector('.form-go-right');
                moveright.style.opacity = 0;
                moveright.disabled = true;
            } else if(data.status == 4) {
                transition2Right(header, headerTxt, querySelIn, inputDataBlob, 0, 0);
            } else if(data.status == 5) {
                transition2Right(header, headerTxt, querySelIn, inputDataBlob, 0, 1);
            } else if(data.status == 6) {
                transition2Right(header, headerTxt, querySelIn, inputDataBlob, 0, 1);
            } else if(data.status == 7) {
                transition2Right(header, headerTxt, querySelIn, inputDataBlob, 1, 0);
            } else if(data.status == 8) {
                transition2Right(header, headerTxt, querySelIn, inputDataBlob, 0, 0);
            } else if(data.status == 9) {
                transition2Right(header, headerTxt, querySelIn, inputDataBlob, 0, 1);
            }
        }
    };
    // sending the request
    xmlhttp.open("POST", "assets/php/register.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    var userdata = "userInfo="+JSON.stringify(inputDataBlob);
    xmlhttp.send(userdata);
}

function confirmStripePayment(){
    let data = {'name': 'payam',
                'email': 'rabiei.p@gmail.com'};
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
        }
    };
    // sending the request
    xmlhttp.open("POST", "assets/php/paymentGateway.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    var userdata = "userInfo="+JSON.stringify(data);
    xmlhttp.send(userdata);
}


// submitting the form
function submitQuestionBackEndData(header, headerTxt, querySelIn, inputDataBlob) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            let data = JSON.parse(this.response);
            MAX_cnt = data.MAX_cnt;
            if(data.status == 0) {
                transition2Right(header, headerTxt, querySelIn, inputDataBlob, 0, 0);
                let moveright = document.querySelector('.form-go-right');
                moveright.style.opacity = 0;
                moveright.disabled = true;
            } else if(data.status == 1) {
                transition2Right(header, headerTxt, querySelIn, inputDataBlob, 1, 0); 
                globalQidx = 9;   
            } else if(data.status == 100) {
                transition2Right(header, headerTxt, querySelIn, inputDataBlob, 1, 0);       
            } else if(data.status == 3) { 
                transition2Right(header, headerTxt, querySelIn, inputDataBlob, 0, 0);    
            } else if(data.status == 4) {
                transition2Right(header, headerTxt, querySelIn, inputDataBlob, 1, 0);
            } else if(data.status == 5) {
                transition2Right(header, headerTxt, querySelIn, inputDataBlob, 1, 0);
            } else if(data.status == 6) {
                transition2Right(header, headerTxt, querySelIn, inputDataBlob, 1, 0);
            } else if(data.status == 7) {
                transition2Right(header, headerTxt, querySelIn, inputDataBlob, 1, 0);
            } else if(data.status == 14) {
                transition2Right(header, headerTxt, querySelIn, inputDataBlob, 1, 0);
            } else if(data.status == 15) {
                transition2Right(header, headerTxt, querySelIn, inputDataBlob, 1, 1);
            } else if(data.status == 16) {
                transition2Right(header, headerTxt, querySelIn, inputDataBlob, 0, 0);
            } else if(data.status == 34) {
                transition2Right(header, headerTxt, querySelIn, inputDataBlob, 0, 0);
            } else if(data.status == 35) {
                transition2Right(header, headerTxt, querySelIn, inputDataBlob, 0, 0);
            } else if(data.status == 24) {
                transition2Right(header, headerTxt, querySelIn, inputDataBlob, 1, 1);
            } else if(data.status == 25) {
                transition2Right(header, headerTxt, querySelIn, inputDataBlob, 1, 1);
            } else if(data.status == 26) {
                transition2Right(header, headerTxt, querySelIn, inputDataBlob, 2, 2);
            } else if(data.status == 27) {
                transition2Right(header, headerTxt, querySelIn, inputDataBlob, 1, 1);
            } else if(data.status == 11) { // reset form for next question 
                resetStart(querySelIn, header, headerTxt, 'questions', 1);
                globalQidx++;
            }
            else if(data.status == 12) { // end the form 
                transition2Right(header, headerTxt, querySelIn, inputDataBlob, 0, 0);
                let moveright = document.querySelector('.form-go-right');
                moveright.style.opacity = 0;
                moveright.disabled = true;
                let moveleft = document.querySelector('.form-go-left');
                moveleft.style.opacity = 0;
                moveleft.disabled = true;
                globalQidx = 0; // end of the form
            }
        }
    };
    // sending the request
    xmlhttp.open("POST", "assets/php/questions.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    let inputDataAndCntBlob = {'data': inputDataBlob, 'counter': counter, 'qIdx': globalQidx};
    var userdata = "userInfo="+JSON.stringify(inputDataAndCntBlob);
    xmlhttp.send(userdata);
}

// submitting the form
function submitUserData(inputDataBlob, page, userPage) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            let data = JSON.parse(this.response);
            if(data.status == 0 && page == 'main'){
                eventSourceQueue = {Bmi:false, If:false, Macro:false, MicroTrace:false, MicroVit:false, Cal:false, Meal:false};
                allowNewAiStream = true;
                plotBmi(data.bmi);  // 0
                plotIf(data.if);    // 1
                plotMacro(data.macro); // 2
                plotMicro(data.micro); // 3
                plotMicroVit(data.micro); // 4
                plotCalories(data.cal); // 5
                displayMeal(data.meal, inputDataBlob, userPage); // 6
                intervalID = setInterval(handleAi, 2000, [userPage, 1, 1]);
            }
        }
    };
    // sending the request
    if(userPage == 0){
        // sending the request
        xmlhttp.open("POST", "assets/php/" + page + ".php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        let inputDataAndUserBlob = {'data': inputDataBlob, 'IdInfo': userPage};
        var userdata = "userInfo="+JSON.stringify(inputDataAndUserBlob);
    } else {
        // sending the request
        xmlhttp.open("POST", "../assets/php/" + page + ".php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        let inputDataAndUserBlob = {'data': inputDataBlob, 'IdInfo': userPage};
        var userdata = "userInfo="+JSON.stringify(inputDataAndUserBlob);
    }
    
    xmlhttp.send(userdata);
}


// add new client
// submitting the form
function submitaddClients(header, headerTxt, input, Questions) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
        }
    };
    // sending the request
    xmlhttp.open("POST", "assets/php/addClients.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    var userdata = "userInfo="+JSON.stringify(Questions);
    xmlhttp.send(userdata);
}


function getUserInfo(userTxt, welcomeTxt){
    // enter here from form design page. 
    let address = window.location.href;
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          
            user = JSON.parse(this.response);
            if(user.status == 1) {
                window.location.assign('login.html');
            } else {
                userTxt.innerHTML = user.username;
                welcomeTxt.innerHTML = 'What\'s up ' + user.username;
                for(let kk = 0; kk < Questions.length; kk++) {
                    Questions[kk].clientId = user.clientId;
                    Questions[kk].campaignId = user.campaignId;
                    Questions[kk].userId = user.userid;
                }
            }
        }
    };
    
    // sending the request
    xmlhttp.open("POST", "assets/php/admin.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    request = 'address=' + address;
    xmlhttp.send(request);
}


// this function eventually comes from user costomization and design of his app.
function questionCreate(headerTxt, header, input, page, userPage){
    let request = [];
    let address = window.location.href;
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            let data = JSON.parse(this.response);
            MAX_cnt = data.MAX_cnt;
            let Obj = new question();
            // reset the global Questions
            for(let kk = 0; kk < MAX_cnt; kk++){
                Obj.popData();
            }
            for(let kk = 0; kk < MAX_cnt; kk++){
                Obj = new question(data.userid, data.qContent[kk], '', data.qIdx[kk], data.qType[kk],
                                   data.options[kk], data.optionsText[kk], false, data.qRequired[kk], data.qKey[kk], data.clientId, data.campaignId);
                Obj.pushData(Obj);
            }
            // initialize header
            headerTxt.innerHTML = Questions[counter].qContent[header.getAttribute('serverStruct')];
            // initialize the input based on form Type
            resetFormType(input);
            setFormType(input, Questions[counter], input.getAttribute('serverStruct'), input.getAttribute('serverStructOption'));
        }
    };
    // sending the request
    if(userPage == 0){
        xmlhttp.open("POST", "assets/php/filler.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        request = 'request='+JSON.stringify({'page': page,'address': address});
    } else {
        xmlhttp.open("POST", "../assets/php/filler.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        request = 'request='+JSON.stringify({'page': userPage, 'address': address});
    }
    xmlhttp.send(request);
}


// function to plot BMI data returned by the server for the given user
function plotBmi(bmi, bmiTxt = 0, bmiDiv = 0, bmiDesc = 0){
    // Canvas element section
    let bmiElement = document.querySelector('#Bmi');
    if(bmiDiv == 0){
        bmiDiv = document.querySelector('.Bmi');
    }
    if(bmiTxt == 0) {
        bmiTxt = document.querySelector('.BMI_text');
    }
    if(bmiDesc == 0) {
        bmiDesc = document.querySelector('.BMI_text_description');
    }
    
    bmiTxt.style.display = 'block';
    bmiDiv.style.display = 'block';
    bmiDesc.style.display = 'block';
    bmiDesc.style.margin = '20px';
    
    bmiDesc.innerHTML = bmi['desc'];
    eventSourceQueue['Bmi'] = true; // arm stream even for SSE for BMI info
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
        if(x < bmi['val'] && x > bmi['val'] - step){
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
    const bgColor = {
        id: 'bgColor',
        beforeDraw: (chart, options) => {
            const {ctx, width, height} = chart;
            ctx.fillStyle = 'white';
            ctx.fillRect(0, 0, width, height);
            ctx.restore();
        }
    }
    const bmiConfig = {
      type: 'line',
      data: bmiData,
      options: {
        responsive: true,
        maintainAspectRatio: false,
        devicePixelRatio: 2,
        aspectRatio: 1,
      },
        plugins: [bgColor],
    };
    
    bmiChart = new Chart(
      bmiElement,
      bmiConfig,
    );
}

// function to plot IF data returned by the server for the given user
function plotIf(If, ifTxt = 0, ifDiv = 0, ifDesc = 0){
    // Canvas element section
    if(ifDiv == 0){
        ifDiv = document.querySelector('.IntermittentFasting');
    }
    if(ifTxt == 0) {
        ifTxt = document.querySelector('.IF_text');
    }
    if(ifDesc == 0) {
        ifDesc = document.querySelector('.IF_text_description');
    }
    let ifElement  = document.querySelector('#IntermittentFasting');
    
    ifDesc.style.margin = '20px';
    eventSourceQueue['If'] = true;
    ifDesc.innerHTML     = If['desc'];
    ifTxt.style.display  = 'block';
    ifDiv.style.display  = 'block';
    ifDesc.style.display = 'block';

    const ifData = {
      labels: ['Sat','Sun','Mon','Tue','Wed','Thu','Fri'],
      datasets: [
        {
            label: 'Eating interval (hrs)',
            data: If['val'][0],
            backgroundColor: 'dodgerblue',
            borderColor: 'grey',
            borderWidth: 1,
        },
        {
            label:  'Fasting interval (hrs)',
            data: If['val'][1],
            backgroundColor: 'mediumseagreen',
            borderColor: 'grey',
            borderWidth: 1,
        }
    ]
    };
    const config = {
      type: 'bar',
      data: ifData,
      options: {
        indexAxis: 'y',
        responsive: true,
        devicePixelRatio: 2,
        scales: {
            x: {
              stacked: true,
            },
            y: {
              stacked: true
            }
        }
      }
    };
    ifChart = new Chart(
      ifElement,
      config
    );
}

// function to plot Calory intake data returned by the server for the given user
function plotCalories(Cal, calTxt = 0, calDiv = 0, calDesc = 0){
    // Canvas element section
    if(calDiv == 0){
        calDiv = document.querySelector('.Calories');
    }
    if(calTxt == 0) {
        calTxt = document.querySelector('.Cal_text');
    }
    if(calDesc == 0) {
        calDesc = document.querySelector('.Cal_description');
    }
    let calElement  = document.querySelector('#Calories');
    
    calDesc.style.margin = '20px';
    eventSourceQueue['Cal'] = true;
    calDesc.innerHTML     = Cal['desc'];
    calTxt.style.display  = 'block';
    calDiv.style.display  = 'block';
    calDesc.style.display = 'block';

    const calData = {
      labels: ['1','2','3','4','5','6','7','8'],
      datasets: [
        {
            label: 'Daily Calories intake (kCal) over 8 week period',
            data: Cal['val'],
            backgroundColor: 'mediumseagreen',
            borderColor: 'grey',
            borderWidth: 1,
        }
    ]
    };
    const config = {
      type: 'bar',
      data: calData,
      options: {
        indexAxis: 'y',
        responsive: true,
        devicePixelRatio: 2,
        scales: {
            x: {
              stacked: true,
            },
            y: {
              stacked: true
            }
        }
      }
    };
    calChart = new Chart(
      calElement,
      config
    );
}


// function to plot Macro data returned by the server for the given user
function plotMacro(macro, macroTxt = 0, macroDiv = 0, macroDesc = 0){
    // Canvas element section
    let macroElement  = document.querySelector('#Macro');
    if(macroDiv == 0){
        macroDiv = document.querySelector('.Macro');
    }
    if(macroTxt == 0) {
        macroTxt = document.querySelector('.MACRO_text');
    }
    if(macroDesc == 0) {
        macroDesc = document.querySelector('.MACRO_text_description');
    }
    
    macroTxt.style.display = 'block';
    macroDiv.style.display = 'block';
    macroDesc.style.display = 'block';

    macroDesc.style.margin = '20px';

    macroDesc.innerHTML = macro['desc'];
    eventSourceQueue['Macro'] = true;

    const macroData = {
      labels: ['fat','carbs', 'protein', 'fiber'],
      datasets: [{
        data: macro['val'],
        labels: ['fat','carbs', 'protein', 'fiber'],
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
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(context) {
                        let label = context.dataset.labels[context.dataIndex] + ": " + 
                        context.dataset.data[context.dataIndex] + " gr";
                        return label;
                    }
                }
            }
        },
        responsive: true,
        devicePixelRatio: 2,
        maintainAspectRatio: false,
      }
    };
    macroChart = new Chart(
      macroElement,
      config
    );
}
// function to plot Micro data returned by the server for the given user
function plotMicro(micro, microDiv = 0, microTxt = 0, microDesc = 0){
    // Canvas element section
    let microElement  = document.querySelector('#Micro');
    if(microDiv == 0) {
        microDiv = document.querySelector('.Micro');
    }
    if(microTxt == 0) {
        microTxt = document.querySelector('.MICRO_text');
    }
    if(microDesc == 0) {
        microDesc = document.querySelector('.MICRO_text_description');
    }
    microTxt.style.display = 'block';
    microDiv.style.display = 'block';
    microDesc.style.display = 'block';
    

    microDesc.style.margin = '20px';
    microDesc.innerHTML = micro['descTrace'];
    eventSourceQueue['MicroTrace'] = true;
    tColors = ['coral','lightblue','limegreen','cyan','blue','green','orange',
               'magenta','Aqua','DeepSkyBlue','MediumPurple','MistyRose','PaleGoldenRod',
               'Peru','Sienna'];
    const microData = {
        labels: micro['val'].tNames,
        datasets: [{
            data: micro['val'].tValues,
            backgroundColor: tColors,
            labels: micro['val'].tNames,
            units: micro['val'].tUnits,
            scale: micro['val'].tScale,
        }]
    };
    const bgColor = {
        id: 'bgColor',
        beforeDraw: (chart, options) => {
            const {ctx, width, height} = chart;
            ctx.fillStyle = 'white';
            ctx.fillRect(0, 0, width, height);
            ctx.restore();
        }
    }
    const config = {
      type: 'polarArea',
      data: microData,
      options: {
        plugins: {
            legend: {
                position: 'right',
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        let label = context.dataset.labels[context.dataIndex] + ": " + 
                        context.dataset.data[context.dataIndex] * context.dataset.scale[context.dataIndex]+ " " + context.dataset.units[context.dataIndex];
                        return label;
                    }
                }
            }
        },
        responsive: true,
        devicePixelRatio: 2,
        maintainAspectRatio: false,
      },
        plugins: [bgColor],
    };
    microChart = new Chart(
      microElement,
      config
    );
}
// function to plot Micro data returned by the server for the given user
function plotMicroVit(micro, microDiv = 0, microTxt = 0, microDesc = 0){
    // Canvas element section
    let microElement  = document.querySelector('#Micro_vit');
    if(microDiv == 0) {
        microDiv = document.querySelector('.Micro_vit');
    }
    if(microTxt == 0) {
        microTxt = document.querySelector('.MICRO_vit_text');
    }
    if(microDesc == 0) {
        microDesc = document.querySelector('.MICRO_vit_text_description');
    }
    microTxt.style.display = 'block';
    microDiv.style.display = 'block';
    microDesc.style.display = 'block';
    microDesc.style.margin = '20px';

    eventSourceQueue['MicroVit'] = true;
    microDesc.innerHTML = micro['descVit'];
    vColors = ['coral','lightblue','limegreen','cyan','blue','green','orange','magenta','Aqua','DeepSkyBlue','MediumPurple','MistyRose','PaleGoldenRod','Peru','Sienna'];
    const microData = {
        labels: micro['val'].vNames,
        datasets: [{
            data: micro['val'].vValues,
            backgroundColor: vColors,
            labels: micro['val'].vNames,
            units: micro['val'].vUnits,
            scale: micro['val'].vScale,
        }]
    };
    const bgColor = {
        id: 'bgColor',
        beforeDraw: (chart, options) => {
            const {ctx, width, height} = chart;
            ctx.fillStyle = 'white';
            ctx.fillRect(0, 0, width, height);
            ctx.restore();
        }
    }
    const config = {
      type: 'polarArea',
      data: microData,
      options: {
        plugins: {
            legend: {
                position: 'right',
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        let label = context.dataset.labels[context.dataIndex] + ": " + 
                        context.dataset.data[context.dataIndex] * context.dataset.scale[context.dataIndex]+ " " + context.dataset.units[context.dataIndex];
                        return label;
                    }
                }
            },
        },
        responsive: true,
        devicePixelRatio: 2,
        maintainAspectRatio: false,
      },
        plugins: [bgColor],
    };
    microChart = new Chart(
      microElement,
      config
    );
}
// function to display meal plan data returned by the server for the given user
function displayMeal(mealIn, inputDataBlob, userPage){
    // sending data first
    eventSourceQueue['Meal'] = true;
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            txt_var     = document.querySelector('.meal_text');
            display_var = document.querySelector('.meal_plan');  
            title_var   = document.querySelector('.Meal_title');  
            display_var.style.display = 'block';
            title_var.style.display = 'block';
            txt_var.style.margin = '20px';
            txt_var.innerHTML = mealIn['desc'];
        }
    };
    // sending the request
    if(userPage == 0) {
        xmlhttp.open("POST", "assets/php/aiRx.php", true);
    } else {
        xmlhttp.open("POST", "../assets/php/aiRx.php", true);
    }
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    var request = "userInfo="+JSON.stringify(inputDataBlob);
    xmlhttp.send(request);

}

function handleAi(inPut) {

    userPage     = inPut[0];
    nutritionEng = inPut[1];
    mealEng      = inPut[2];
    contextSet   = ['Bmi', 'If', 'Macro', 'MicroTrace', 'MicroVit', 'Cal', 'Meal'];
    // creating the server side update event source
    if(mealEng == 1 && nutritionEng == 1) {
        allowNewAiStream = false;
        clearInterval(intervalID);
    }
    for(contextCnt = 0; contextCnt < contextSet.length; contextCnt++){
        if(nutritionEng == 0 && eventSourceQueue[contextSet[contextCnt]] == true && allowNewAiStream == true){
            if(contextCnt == 0) { // handle first in the queue
                display_var = document.querySelector('.Bmi');
                txt_var     = document.querySelector('.BMI_text_description');
                typeEventSource    = contextSet[contextCnt];
                display_var.style.display = 'block';
                activeSSE   = contextCnt;
                break;
            } else if(contextCnt == 1) {
                display_var = document.querySelector('.IntermittentFasting');
                txt_var     = document.querySelector('.IF_text_description');
                typeEventSource    = contextSet[contextCnt];
                display_var.style.display = 'block';
                activeSSE   = contextCnt;
                break;
            } else if(contextCnt == 2) {
                display_var = document.querySelector('.Macro');
                txt_var     = document.querySelector('.MACRO_text_description');
                typeEventSource    = contextSet[contextCnt];
                display_var.style.display = 'block';
                activeSSE   = contextCnt;
                break;
            } else if(contextCnt == 3) {
                display_var = document.querySelector('.Micro');
                txt_var     = document.querySelector('.MICRO_text_description');
                typeEventSource    = contextSet[contextCnt];
                display_var.style.display = 'block';
                activeSSE   = contextCnt;
                break;
            } else if(contextCnt == 4) {
                display_var = document.querySelector('.Micro_vit');
                txt_var     = document.querySelector('.MICRO_vit_text_description');
                typeEventSource    = contextSet[contextCnt];
                display_var.style.display = 'block';
                activeSSE   = contextCnt;
                break;
            } else if(contextCnt == 5) {
                display_var = document.querySelector('.Calories');
                txt_var     = document.querySelector('.Cal_description');
                typeEventSource    = contextSet[contextCnt];
                display_var.style.display = 'block';
                activeSSE   = contextCnt;
                if(mealEng == 1) {
                    clearInterval(intervalID);
                }
                break;
            } 
        } 
        if(contextCnt == 6 && mealEng == 0 && eventSourceQueue[contextSet[contextCnt]] == true && allowNewAiStream == true) {
            txt_var     = document.querySelector('.meal_text');
            display_var = document.querySelector('.meal_plan');
            typeEventSource    = contextSet[contextCnt];
            display_var.style.display = 'block';
            activeSSE   = contextCnt;
            clearInterval(intervalID);
            break;
        }
    }
    if(allowNewAiStream == true && (mealEng == 0 || nutritionEng == 0)) {
        if(userPage == 0){
            eventSource = new EventSource("assets/php/ai.php?type=" + typeEventSource);
        } else {
            eventSource = new EventSource("../assets/php/ai.php?type=" + typeEventSource);
        }
        allowNewAiStream = false; // lock serving other evenSources
        txt_var.innerHTML = '<br> Created by Zephyr 7 billion beta language model from Hugging Face:<br><br>';
        eventSource.onmessage = function (e) {
            aiText = e.data;
            if(aiText.includes("DONE")) {
                eventSourceQueue[contextSet[activeSSE]] = false;
                txt_var.innerHTML += "<br><br>Thank you!";
                eventSource.close();
                allowNewAiStream = true;
            } else {
                // styling the text as it comes through
                aiText = aiText.replace(/NewLine/g, '<br>');
                if(aiText.includes('AI:') || aiText.includes('Trainer:') || aiText.includes('Q:')) {
                    s = document.createElement('span');
                    s.innerHTML = aiText;
                    s.style.fontSize = "16px";
                    s.style.color = 'seagreen';
                    txt_var.appendChild(s);
                } else if(parseFloat(aiText) && aiText.includes('.')) {
                    s = document.createElement('span');
                    s.innerHTML = '<br>&nbsp;' + aiText + ' ';
                    s.style.fontSize = "16px";
                    s.style.color = 'brown';
                    txt_var.appendChild(s);
                } else {
                    txt_var.innerHTML += aiText + ' ';
                }
            }
        };
    }
}


function fetchClients(searchStruct) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            user = JSON.parse(this.response);
            let username = user.username;
            let userid = user.userid;
            constructClients(userid, username, searchStruct);
        }
    };
    // sending the request
    xmlhttp.open("POST", "assets/php/admin.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    let address = '';
    let request = 'address=' + address;
    xmlhttp.send(request);
}

function fetchCampaigns() {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            user = JSON.parse(this.response);
            let username = user.username;
            let userid = user.userid;
            let campaignSourceInfo = user.campaignSourceInfo;
            constructCampaigns(userid, username, campaignSourceInfo);
        }
    };
    // sending the request
    xmlhttp.open("POST", "assets/php/admin.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    let address = '';
    let request = 'address=' + address;
    xmlhttp.send(request);
}

function constructCampaigns(userid, username, campaignSourceInfo){
    let parentNode = document.querySelector('.campaign-list-parent');
    cleanCampaignDiv(parentNode);
    kk = 0;
    while(campaignSourceInfo.campaignIdSource[kk] != '') {
        let mDiv = document.createElement('div');
        mDiv.setAttribute('class', 'col-sm-6 col-md-4 col-lg-4 campaign-list');
        mDiv.setAttribute('cidx', kk);
        mDiv.setAttribute('userid', userid);
        mDiv.setAttribute('username', username);
        mDiv.setAttribute('campaignids', campaignSourceInfo.campaignIdSource[kk]);
        mDiv.setAttribute('campaignTimeStamp', campaignSourceInfo.campaignTimeStamp[kk]);
        mDiv.addEventListener('click', function(){
            campaignDetail(campaignSourceInfo, mDiv.getAttribute('userid'), mDiv.getAttribute('cidx'));
        });
        nameP        = document.createElement('p');
        nameP.innerHTML = 'Campaign name: ';
        nameP.style.marginTop = '-50px';
        idP          = document.createElement('p');
        idP.innerHTML = 'ID: ';
        idPStyle     = document.createElement('span');
        idPStyle.textContent = campaignSourceInfo.campaignIdSource[kk];
        idPStyle.style.color = 'brown';
        idPStyle.style.fontSize = '24px';
        idP.appendChild(idPStyle);
        CtimeP          = document.createElement('p');
        CtimeP.innerHTML = 'Campaign created: ';
        CtPStyle     = document.createElement('span');
        if(campaignSourceInfo.campaignTimeStamp[kk] == null) {
            CtPStyle.textContent = 'Not created';
        } else {
            CtPStyle.textContent = campaignSourceInfo.campaignTimeStamp[kk];
        }
        CtPStyle.style.color = 'seagreen';
        CtPStyle.style.fontSize = '20px';
        CtimeP.appendChild(CtPStyle);
        avatar       = document.createElement('img');
        m = kk+1;
        avatar.setAttribute('src', './assets/img/ask' + m.toString() + '.png');
        avatar.style.width = '20%';
        avatar.style.display = 'block';
        avatar.style.marginLeft = 'auto';
        mDiv.appendChild(avatar);
        mDiv.appendChild(nameP);
        mDiv.appendChild(idP);
        mDiv.appendChild(CtimeP);
        parentNode.appendChild(mDiv);
        kk++;
    }
}

function campaignDetail(campaignSourceInfo, userid, cidx){
    
    if(campaignSourceInfo.campaignTimeStamp[cidx] == null) { // campaign page does not exist. create one
        userdata = '?userId=' + userid + '?campaignId=' + campaignSourceInfo.campaignIdSource[cidx]; 
        window.location.assign('questions.html' + userdata);
    } else if(campaignSourceInfo.completed[cidx]) { // the user wants to look at the campaign or edit
        userFile =  userid + campaignSourceInfo.campaignIdSource[cidx]; 
        link = '/userPages/' + userFile + '.html';
        window.location.assign(link);
    } 
}


function constructClients(userid, username, searchStruct){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            results = JSON.parse(this.response);
            displayClients(results, userid, username, searchStruct);
        }
    };
    // sending the request
    xmlhttp.open("POST", "assets/php/results.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    let info = {'userId': userid, 'clientId': ''};
    var userdata = "userInfo="+JSON.stringify(info);
    xmlhttp.send(userdata);
}

function displayClients(results, userid, username, searchStruct) {
    let blur = document.querySelector('.blur');
    blur.style.filter = 'blur(0px)';
    let parentNode = document.querySelector('.client-list-parent');
    cleanClientDiv(parentNode);
    numClients = results.names.length;
    for(let kk = 0; kk < numClients; kk++) {

        // only display matching search strings
        if(typeof(searchStruct) == 'undefined' || searchStruct == '') {
        } else if(typeof(searchStruct) !== 'undefined' ) {
            if(searchStruct.searchStr.length == 0){
            } else {
                if(searchStruct.key == 0) { // search ID
                    if(results.ids[kk].slice(0, searchStruct.searchStr.length) != searchStruct.searchStr){
                        continue;
                    }
                } else if(searchStruct.key == 1) { // search email
                    if(results.emails[kk].slice(0, searchStruct.searchStr.length) != searchStruct.searchStr){
                        continue;
                    }
                } else if(searchStruct.key == 2) { // search name
                    let nameClient = results.names[kk].toLowerCase();
                    if(nameClient.slice(0, searchStruct.searchStr.length) != searchStruct.searchStr.toLowerCase()){
                        continue;
                    }
                }
            }
        } 

        CampaignIdSelected = results.campaignidAssigned[kk];
        CampaignTimeSelected = '';
        kx = 0;
        while(results.campaigntime[kx] != null){
            if(CampaignIdSelected != '' && CampaignIdSelected == results.campaignids[kx]) {
                CampaignTimeSelected = results.campaigntime[kx];
                break;
            } else {
                CampaignTimeSelected = '';
            }
            kx++;
        }
        let mDiv = document.createElement('div');
        mDiv.setAttribute('class', 'col-sm-6 col-md-4 col-lg-4 client-list');
        mDiv.setAttribute('cidx', kk);
        mDiv.setAttribute('userid', userid);
        mDiv.setAttribute('username', username);
        mDiv.setAttribute('campaignids', CampaignIdSelected);
        mDiv.setAttribute('campaigntime', CampaignTimeSelected);       
        mDiv.setAttribute('clientid', results.ids[kk]);
        mDiv.addEventListener('click', function (e){
            getClientDetails(parentNode, results, this.getAttribute('cidx'));
        });
        nameP        = document.createElement('p');
        nameP.setAttribute('class', 'updateName');
        idP          = document.createElement('p');
        idPStyle     = document.createElement('span');
        genderP      = document.createElement('p');
        genderPStyle = document.createElement('span');
        emailP       = document.createElement('p');
        emailPStyle  = document.createElement('span');
        goalP        = document.createElement('p');
        goalPStyle   = document.createElement('span');
        campaignP    = document.createElement('p');
        campaignP.setAttribute('class', 'updatePlink');
        campaignP.style.opacity = 0;
        createQP     = document.createElement('p');
        createQPStyle= document.createElement('span');
        createQPStyle.setAttribute('class', 'updateCampaign');
        avatar       = document.createElement('img');
        if(results.genders[kk] == 'female') {
            avatar.setAttribute('src', './assets/img/woman.png');
        } else if (results.genders[kk] == 'male') {
            avatar.setAttribute('src', './assets/img/man.png');
        } else {
            avatar.setAttribute('src', './assets/img/addNew.png');
        }
        avatar.style.width = '60%';
        avatar.style.display = 'block';
        avatar.style.margin = '0 auto';
        nameP.innerHTML = results.names[kk];
        nameP.style.color = '#4285F4';
        nameP.style.fontSize = '30px';
        idP.innerHTML = 'ID: ';
        idPStyle.textContent = results.ids[kk];
        idPStyle.style.color = 'brown';
        idPStyle.style.fontSize = '24px';
        idP.appendChild(idPStyle);
        genderP.innerHTML = 'Gender: ';
        genderPStyle.textContent = results.genders[kk];
        genderPStyle.style.color = '#F4B400';
        genderPStyle.style.fontSize = '24px';
        genderP.appendChild(genderPStyle);

        emailP.innerHTML = 'Email: ';
        emailPStyle.textContent = results.emails[kk];
        emailPStyle.style.color = '#DB4437';
        emailPStyle.style.fontSize = '20px';
        emailP.appendChild(emailPStyle);

        goalP.innerHTML = 'Goal: ';
        goalPStyle.textContent = results.goals[kk];    
        goalPStyle.style.fontSize = '24px';  
        goalP.appendChild(goalPStyle); 
        // create a list input for campaigns
        campaignList = document.createElement('select');
        campaignList.setAttribute('id', 'campaignList');
        campaignOption = document.createElement('option');
        campaignOption.innerHTML = 'select campaign';
        campaignList.appendChild(campaignOption);
        
        campaignList.addEventListener('change', function(e){
            CampaignTimeSelected = e.target.value;
            for(ky = 0; ky < e.target.childElementCount; ky++) {
                if(e.target.children[ky].innerHTML == CampaignTimeSelected) {
                    CampaignIdSelected = e.target.children[ky].getAttribute('campaignid');
                }
            }
            getnameP = document.querySelectorAll('.updateName');
            if(getnameP.innerHTML != ''){
                getQPStyle = document.querySelectorAll('.updateCampaign');
                getQPStyle[mDiv.getAttribute('cidx')].textContent = CampaignTimeSelected;
            }
            userFile =  userid + results.ids[mDiv.getAttribute('cidx')] + CampaignIdSelected; 
            link = '/userPages/' + userFile + '.html';
            if(results.campaignidAssigned[mDiv.getAttribute('cidx')] != '') {
                getcampaignP = document.querySelectorAll('.updatePlink');
                getcampaignP[mDiv.getAttribute('cidx')].innerHTML = 'Link to ' + results.names[mDiv.getAttribute('cidx')] + ' <a href="' + link + '"> Campaign page</a>';
                getcampaignP[mDiv.getAttribute('cidx')].style.opacity = 1;
            }
            updatedBonNewCampaignAssignment(CampaignIdSelected, mDiv.getAttribute('clientid'),  mDiv.getAttribute('userid'));
        })
        // list
        i = 0;
        while(results.campaigntime[i] != null){
            campaignOption = document.createElement('option');
            campaignOption.innerHTML = results.campaigntime[i];
            campaignOption.setAttribute('campaignid', results.campaignids[i])
            campaignList.appendChild(campaignOption);
            i++;
        }
        userFile =  userid + results.ids[kk] + CampaignIdSelected; 
        createQP.innerHTML = 'Campaign created on : ';
        createQPStyle.style.fontSize = '18px';
        createQPStyle.style.color = 'seagreen';
        createQPStyle.textContent = CampaignTimeSelected;
        createQP.appendChild(createQPStyle);
        link = '/userPages/' + userFile + '.html';
        campaignP.innerHTML = 'Link to ' + results.names[kk] + ': <a href="' + link + '"> Campaign page</a>';
        mDiv.appendChild(avatar);
        mDiv.appendChild(nameP);
        mDiv.appendChild(emailP);
        mDiv.appendChild(genderP);
        mDiv.appendChild(goalP);
        mDiv.appendChild(idP);
        mDiv.appendChild(campaignP);

        if(nameP.innerHTML != ''){
            mDiv.appendChild(createQP);
            mDiv.appendChild(campaignList);
        }
        if(results.campaignidAssigned[kk] != ''){
            campaignP.style.opacity = 1;
        }
        parentNode.appendChild(mDiv);
        
    }
}
function updatedBonNewCampaignAssignment(CampaignIdSelected, clientId, userId) {

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
        }
    };
    // sending the request
    xmlhttp.open("POST", "assets/php/saveUserComments.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    let info = {'userId': userId, 'clientId': clientId, 'topic': 'campaign', 'clientText': CampaignIdSelected};
    var userdata = "userInfo="+JSON.stringify(info);
    xmlhttp.send(userdata);
}

// this function needs to send a request to results.php
// if the user's client has filled out the form, his analysis
// will be available.

function getClientDetails(parentNode, result, cidx){
        userid = parentNode.children[cidx].getAttribute('userid');
        clientid = parentNode.children[cidx].getAttribute('clientid');
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                clientData = JSON.parse(this.response);
                    displayClientsDetails(parentNode, clientData.client, clientData.input, result, cidx);
            }
        };
        // sending the request
        xmlhttp.open("POST", "assets/php/results.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        let info = {'userId': userid, 'clientId': clientid};
        var userdata = "userInfo="+JSON.stringify(info);
        xmlhttp.send(userdata);
}


function displayClientsDetails(parentNode, clientData, inputBlob, results, cidx) {
    
    userid = parentNode.children[cidx].getAttribute('userid');
    clientid = parentNode.children[cidx].getAttribute('clientid');
    // if not set, go to add client page.
    cleanClientDiv(parentNode);
    if(results.campaignidAssigned[cidx] == '' && results.names[cidx] == '' && results.genders[cidx] == 'No response' && results.goals[cidx] == 'No response') {
        window.location.assign('addClients.html');
    } else {  
        let accountType = results.accountType[0];
        if(accountType == 'free') {
            accessDenied = true;
        } else {
            accessDenied = false;
        }
        let blur = document.querySelector('.blur');
        blur.style.zIndex = '1';
        blur.style.filter = 'blur(10px)';
        blur.addEventListener('click', function(){
            displayClients(results, userid);
        });
        
        let mDiv = document.createElement('div');
        mDiv.setAttribute('class', 'col-sm-6 col-md-4 col-lg-4 client-list-magnified');
        let closeBtn = document.createElement('button');
        closeBtn.setAttribute('class', 'closeExpandedClient');
        let spn = document.createElement('span');
        spn.setAttribute('class', 'fa-regular fa-circle-xmark');
        spn.style.fontSize = '50px';
        closeBtn.appendChild(spn);
        closeBtn.addEventListener('click', function(){
            displayClients(results, userid);
        });
        
        let pdfBtn = document.createElement('button');
        pdfBtn.setAttribute('class', 'pdfReport');
        spn = document.createElement('span');
        spn.setAttribute('class', 'fa-regular fa-file-pdf');
        spn.style.fontSize = '50px';
        pdfBtn.appendChild(spn);
        pdfBtn.addEventListener('click', function(){
            createPdf(parentNode, clientData);
        });

        let emailBtn = document.createElement('button');
        emailBtn.setAttribute('class', 'emailReport');
        spn = document.createElement('span');
        spn.setAttribute('class', 'fa-regular fa-paper-plane');
        spn.style.fontSize = '50px';
        emailBtn.appendChild(spn);
        emailBtn.addEventListener('click', function(){
            // send email from the server side ... prefer send a copy to nutritionist as well
            sendEmail(parentNode, clientData, userid, clientid);
        });
        
        let nameP = document.createElement('p');
        let namePstyle = document.createElement('span');
        let idP = document.createElement('p');
        let idPstyle = document.createElement('span');
        let genderP = document.createElement('p');
        let genderPstyle = document.createElement('span');
        let goalP = document.createElement('p');
        let goalPstyle = document.createElement('span');
        let campaignP = document.createElement('p');
        let mealText  = document.createElement('p');
        let mealPstyle = document.createElement('span');
        let nutritionText = document.createElement('p');
        let nutritionPstyle = document.createElement('span');
        
        nameP.innerHTML = "Client's name: ";
        nameP.style.marginTop = '100px';
        namesTemp = results.names[cidx].charAt(0).toUpperCase() + results.names[cidx].slice(1);
        namePstyle.innerHTML = namesTemp;
        namePstyle.style.fontSize = '40px';
        namePstyle.style.color = 'brown';
        namePstyle.setAttribute('id', 'mDivpName');
        nameP.appendChild(namePstyle);

        idP.innerHTML = "Client's ID: ";
        idPstyle.innerHTML = results.ids[cidx];
        idPstyle.style.fontSize = '40px';
        idPstyle.style.color = 'green';
        idPstyle.setAttribute('id', 'mDivpId');
        idP.appendChild(idPstyle);    

        genderP.innerHTML = "Client's gender: ";
        genderTemp = results.genders[cidx].charAt(0).toUpperCase() + results.genders[cidx].slice(1);
        genderPstyle.innerHTML = genderTemp;
        genderPstyle.style.fontSize = '40px';
        genderPstyle.style.color = '#F4B400';
        genderPstyle.setAttribute('id', 'mDivpGender');
        genderP.appendChild(genderPstyle);  


        goalP.innerHTML = "Client's goal: ";
        goalsTemp = results.goals[cidx].charAt(0).toUpperCase() + results.goals[cidx].slice(1);
        goalPstyle.innerHTML = goalsTemp;
        goalPstyle.style.fontSize = '40px';
        goalPstyle.style.color = '#DB4437';
        goalPstyle.setAttribute('id', 'mDivpGoal');
        goalP.appendChild(goalPstyle); 

        if(results.mealEng[cidx] == 0){
            mealText.innerHTML = 'You have selected ';
            mealPstyle.innerHTML = 'AI';
            mealPstyle.style.fontSize = '24px';
            mealPstyle.style.color = '#DB4437';
            mealText.appendChild(mealPstyle);
            mealText.innerHTML =  mealText.innerHTML + ' for meal planning for ' + results.names[cidx];
        } else if(results.mealEng[cidx] == 1){
            mealText.innerHTML = 'You have selected ';
            mealPstyle.innerHTML = 'nutritionist';
            mealPstyle.style.fontSize = '24px';
            mealPstyle.style.color = '#DB4437';
            mealText.appendChild(mealPstyle);
            mealText.innerHTML =  mealText.innerHTML + ' for meal planning for ' + results.names[cidx];
        }

        if(results.nutritionEng[cidx] == 0){
            nutritionText.innerHTML = 'You have selected ';
            nutritionPstyle.innerHTML = 'AI';
            nutritionPstyle.style.fontSize = '24px';
            nutritionPstyle.style.color = '#DB4437';
            nutritionText.appendChild(nutritionPstyle);
            nutritionText.innerHTML =  nutritionText.innerHTML + ' for nutritional analysis for ' + results.names[cidx];
        } else if(results.nutritionEng[cidx] == 1){
            nutritionText.innerHTML = 'You have selected ';
            nutritionPstyle.innerHTML = 'nutritionist';
            nutritionPstyle.style.fontSize = '24px';
            nutritionPstyle.style.color = '#DB4437';
            nutritionText.appendChild(nutritionPstyle);
            nutritionText.innerHTML =  nutritionText.innerHTML + ' for nutritional analysis for ' + results.names[cidx];
        }

        let link = '/userPages/' + userid + results.ids[cidx] + results.campaignidAssigned[cidx] + '.html'
        campaignP.innerHTML = 'Link to ' + results.names[cidx] + '\'s survey <a href="' + link + '"> page</a>';
        
        let divider1 = document.createElement('div');
        divider1.style.height = '2px';
        divider1.style.width = '80%';
        divider1.style.backgroundColor = 'grey';
        divider1.style.margin = 'auto';
        
        // create BMR data info
        let bmrSuggestion = document.createElement('p');
        let bmrValue = document.createElement('span');
        bmrSuggestion.innerHTML = 'Basal Metabolic Rate (BMR):';
        bmrSuggestion.style.fontSize = '30px';
        bmrValue.innerHTML = Math.floor(clientData.bmr['val']*100)/100;
        bmrValue.style.fontSize = '40px';
        bmrValue.style.color = '#4285F4';
        bmrSuggestion.setAttribute('id', 'mDivBmrSugg');
        bmrSuggestion.appendChild(bmrValue);

        // create plot BMI
        let bmiSuggestion = document.createElement('p');
        let bmiValue = document.createElement('span');
        bmiSuggestion.innerHTML = 'Body mass index: ';
        bmiSuggestion.style.fontSize = '30px';
        bmiValue.innerHTML = Math.floor(clientData.bmi['val']*100)/100;
        bmiValue.style.fontSize = '40px';
        bmiValue.style.color = '#0F9D58';
        bmiSuggestion.setAttribute('id', 'mDivBmiSugg');
        bmiSuggestion.appendChild(bmiValue);

        
        let bmi = document.createElement('div');
        bmi.setAttribute('class', 'col-sm col-lg-5 Bmi');
        bmi.style.margin = 'auto';
        let div1 = document.createElement('div');
        let div2 = document.createElement('div');
        let div3 = document.createElement('div');
        let bmiDiv = document.createElement('canvas');
        let bmiTxt = document.createElement('p');
        let bmiDesc = document.createElement('p');
        
        bmiDiv.setAttribute('id', 'Bmi');
        bmiTxt.setAttribute('class', 'BMI_text');
        bmiDesc.setAttribute('class', 'BMI_text_description');
        div1.appendChild(bmiTxt);
        div2.appendChild(bmiDiv);
        div3.appendChild(bmiDesc);
        bmi.appendChild(div1);
        bmi.appendChild(div2);
        bmi.appendChild(div3);
        // ------------------------------------
        // edit button for BMI description. User can add his comments here.
        // content of clientData.bmi['desc'] must be modified.
        let bmiBtnDiv = document.createElement('div');
        bmiBtnDiv.setAttribute('class', 'd-flex justify-content-center');
        bmiBtnDiv.style.margin = '20px';
        let bmiBtn = document.createElement('button');
        bmiBtn.setAttribute('class', 'btn btn-outline-primary');
        bmiBtn.innerHTML = 'Edit';
        bmiBtn.addEventListener('click', function(){
            addUsersuggestionContent(clientData.bmi, bmiDesc, bmiBtn, 'desc');
            if(bmiBtn.innerHTML == 'Edit') {
                saveUserCommentstoDb(clientData.bmi['desc'], userid, clientid, 'Bmi');
            }
        });    
        bmiBtnDiv.appendChild(bmiBtn);
        // ------------------------------------


        let divider2 = document.createElement('div');
        divider2.style.height = '2px';
        divider2.style.width = '80%';
        divider2.style.backgroundColor = 'grey';
        divider2.style.margin = 'auto';
        divider2.style.marginTop = '20px';
        
        // create plot MICRO
        let microSuggestion = document.createElement('p');
        microSuggestion.innerHTML = 'Micro-nutrients (Trace minerals) recommendation';
        microSuggestion.setAttribute('id', 'mDivMicroSugg');
        microSuggestion.style.fontSize = '30px';
        let micro = document.createElement('div');
        micro.setAttribute('class', 'col-sm col-lg-5 Micro');
        micro.style.margin = 'auto';
        div1 = document.createElement('div');
        div2 = document.createElement('div');
        div3 = document.createElement('div');
        let microDiv = document.createElement('canvas');
        let microTxt = document.createElement('p');
        let microDesc = document.createElement('p');
        microDiv.setAttribute('id', 'Micro');
        microTxt.setAttribute('class', 'MICRO_text');
        microDesc.setAttribute('class', 'MICRO_text_description');
        div1.appendChild(microTxt);
        div2.appendChild(microDiv);
        div3.appendChild(microDesc);
        micro.appendChild(div1);
        micro.appendChild(div2);
        micro.appendChild(div3);

        // ------------------------------------
        // edit button for mico description. User can add his comments here.
        // content of clientData.bmi['desc'] must be modified.
        let microBtnDiv = document.createElement('div');
        microBtnDiv.setAttribute('class', 'd-flex justify-content-center');
        microBtnDiv.style.margin = '20px';
        let microBtn = document.createElement('button');
        microBtn.setAttribute('class', 'btn btn-outline-primary');
        microBtn.innerHTML = 'Edit';
        microBtn.addEventListener('click', function(){
            addUsersuggestionContent(clientData.micro, microDesc, microBtn, 'descTrace');
            if(microBtn.innerHTML == 'Edit') {
                saveUserCommentstoDb(clientData.micro['descTrace'], userid, clientid, 'Micro');
            }
        });    
        microBtnDiv.appendChild(microBtn);

        // Micto vitamines
        let divider2Vit = document.createElement('div');
        divider2Vit.style.height = '2px';
        divider2Vit.style.width = '80%';
        divider2Vit.style.backgroundColor = 'grey';
        divider2Vit.style.margin = 'auto';
        divider2Vit.style.marginTop = '20px';
        
        // create plot MICRO
        let microVitSuggestion = document.createElement('p');
        microVitSuggestion.innerHTML = 'Micro-nutrients (Vitamines) recommendation';
        microVitSuggestion.style.fontSize = '30px';
        microVitSuggestion.setAttribute('id', 'mDivMicroVitSugg');
        let microVit = document.createElement('div');
        microVit.setAttribute('class', 'col-sm col-lg-5 Micro_vit');
        microVit.style.margin = 'auto';
        div1 = document.createElement('div');
        div2 = document.createElement('div');
        div3 = document.createElement('div');
        let microVitDiv = document.createElement('canvas');
        let microVitTxt = document.createElement('p');
        let microVitDesc = document.createElement('p');
        microVitDiv.setAttribute('id', 'Micro_vit');
        microVitTxt.setAttribute('class', 'MICRO_vit_text');
        microVitDesc.setAttribute('class', 'MICRO_vit_text_description');
        div1.appendChild(microVitTxt);
        div2.appendChild(microVitDiv);
        div3.appendChild(microVitDesc);
        microVit.appendChild(div1);
        microVit.appendChild(div2);
        microVit.appendChild(div3);
        // ------------------------------------
        // edit button for mico description. User can add his comments here.
        // content of clientData.bmi['desc'] must be modified.
        let microVitBtnDiv = document.createElement('div');
        microVitBtnDiv.setAttribute('class', 'd-flex justify-content-center');
        microVitBtnDiv.style.margin = '20px';
        let microVitBtn = document.createElement('button');
        microVitBtn.setAttribute('class', 'btn btn-outline-primary');
        microVitBtn.innerHTML = 'Edit';
        microVitBtn.addEventListener('click', function(){
            addUsersuggestionContent(clientData.micro, microVitDesc, microVitBtn, 'descVit');
            if(microVitBtn.innerHTML == 'Edit') {
                saveUserCommentstoDb(clientData.micro['descVit'], userid, clientid, 'MicroVit');
            }
        });    
        microVitBtnDiv.appendChild(microVitBtn);

        // -----------
        let divider3 = document.createElement('div');
        divider3.style.height = '2px';
        divider3.style.width = '80%';
        divider3.style.backgroundColor = 'grey';
        divider3.style.margin = 'auto';
        divider3.style.marginTop = '20px';

        // create plot MACRO
        let macroSuggestion = document.createElement('p');
        macroSuggestion.innerHTML = 'Macro-nutrients recommendation';
        macroSuggestion.style.fontSize = '30px';
        macroSuggestion.setAttribute('id', 'mDivMacroSugg');
        let macro = document.createElement('div');
        macro.setAttribute('class', 'col-sm col-lg-5 Macro');
        macro.style.margin = 'auto';
        div1 = document.createElement('div');
        div2 = document.createElement('div');
        div3 = document.createElement('div');
        let macroDiv = document.createElement('canvas');
        let macroTxt = document.createElement('p');
        let macroDesc = document.createElement('p');
        macroDiv.setAttribute('id', 'Macro');
        macroTxt.setAttribute('class', 'MACRO_text');
        macroDesc.setAttribute('class', 'MACRO_text_description');
        div1.appendChild(macroTxt);
        div2.appendChild(macroDiv);
        div3.appendChild(macroDesc);
        macro.appendChild(div1);
        macro.appendChild(div2);
        macro.appendChild(div3);
        // ------------------------------------
        // edit button for macro description. User can add his comments here.
        // content of clientData.bmi['desc'] must be modified.
        let macroBtnDiv = document.createElement('div');
        macroBtnDiv.setAttribute('class', 'd-flex justify-content-center');
        macroBtnDiv.style.margin = '20px';
        let macroBtn = document.createElement('button');
        macroBtn.setAttribute('class', 'btn btn-outline-primary');
        macroBtn.innerHTML = 'Edit';
        macroBtn.addEventListener('click', function(){
            addUsersuggestionContent(clientData.macro, macroDesc, macroBtn, 'desc');
            if(macroBtn.innerHTML == 'Edit') {
                saveUserCommentstoDb(clientData.macro['desc'], userid, clientid, 'Macro');
            }
        });    
        macroBtnDiv.appendChild(macroBtn);


        let divider4 = document.createElement('div');
        divider4.style.height = '2px';
        divider4.style.width = '80%';
        divider4.style.backgroundColor = 'grey';
        divider4.style.margin = 'auto';
        divider4.style.marginTop = '20px';

        // create plot Intermittent Fasting plots
        let ifSuggestion = document.createElement('p');
        ifSuggestion.innerHTML = 'Intermittent fasting recommendation';
        ifSuggestion.style.fontSize = '30px';
        ifSuggestion.setAttribute('id', 'mDivIfSugg');
        let If = document.createElement('div');
        If.setAttribute('class', 'col-sm col-lg-5 IntermittentFasting');
        If.style.margin = 'auto';
        div1 = document.createElement('div');
        div2 = document.createElement('div');
        div3 = document.createElement('div');
        let ifDiv = document.createElement('canvas');
        let ifTxt = document.createElement('p');
        let ifDesc = document.createElement('p');
        ifDiv.setAttribute('id', 'IntermittentFasting');
        ifTxt.setAttribute('class', 'IF_text');
        ifDesc.setAttribute('class', 'IF_text_description');
        div1.appendChild(ifTxt);
        div2.appendChild(ifDiv);
        div3.appendChild(ifDesc);
        If.appendChild(div1);
        If.appendChild(div2);
        If.appendChild(div3);
        // ------------------------------------
        // edit button for Intermittent fasting description. User can add his comments here.
        // content of clientData.bmi['desc'] must be modified.
        let ifBtnDiv = document.createElement('div');
        ifBtnDiv.setAttribute('class', 'd-flex justify-content-center');
        ifBtnDiv.style.margin = '20px';
        let ifBtn = document.createElement('button');
        ifBtn.setAttribute('class', 'btn btn-outline-primary');
        ifBtn.innerHTML = 'Edit';
        ifBtn.addEventListener('click', function(){
            addUsersuggestionContent(clientData.if, ifDesc, ifBtn, 'desc');
            if(ifBtn.innerHTML == 'Edit') {
                saveUserCommentstoDb(clientData.if['desc'], userid, clientid, 'If');
            }
        });    
        ifBtnDiv.appendChild(ifBtn);


        
        let divider5 = document.createElement('div');
        divider5.style.height = '2px';
        divider5.style.width = '80%';
        divider5.style.backgroundColor = 'grey';
        divider5.style.margin = 'auto';
        divider5.style.marginTop = '20px';


        // create plot Intermittent Fasting plots
        let calSuggestion = document.createElement('p');
        calSuggestion.innerHTML = 'Calories intake recommendation';
        calSuggestion.style.fontSize = '30px';
        calSuggestion.setAttribute('id', 'mDivCalSugg');
        let Cal = document.createElement('div');
        Cal.setAttribute('class', 'col-sm col-lg-5 Calories');
        Cal.style.margin = 'auto';
        div1 = document.createElement('div');
        div2 = document.createElement('div');
        div3 = document.createElement('div');
        let calDiv = document.createElement('canvas');
        let calTxt = document.createElement('p');
        let calDesc = document.createElement('p');
        calDiv.setAttribute('id', 'Calories');
        calTxt.setAttribute('class', 'Cal_text');
        calDesc.setAttribute('class', 'Cal_description');
        div1.appendChild(calTxt);
        div2.appendChild(calDiv);
        div3.appendChild(calDesc);
        Cal.appendChild(div1);
        Cal.appendChild(div2);
        Cal.appendChild(div3);
        // ------------------------------------
        // edit button for Intermittent fasting description. User can add his comments here.
        // content of clientData.bmi['desc'] must be modified.
        let calBtnDiv = document.createElement('div');
        calBtnDiv.setAttribute('class', 'd-flex justify-content-center');
        calBtnDiv.style.margin = '20px';
        let calBtn = document.createElement('button');
        calBtn.setAttribute('class', 'btn btn-outline-primary');
        calBtn.innerHTML = 'Edit';
        calBtn.addEventListener('click', function(){
            addUsersuggestionContent(clientData.cal, calDesc, calBtn, 'desc');
            if(calBtn.innerHTML == 'Edit') {
                saveUserCommentstoDb(clientData.cal['desc'], userid, clientid, 'Cal');
            }
        });    
        calBtnDiv.appendChild(calBtn);



        let divider5_1 = document.createElement('div');
        divider5_1.style.height = '2px';
        divider5_1.style.width = '80%';
        divider5_1.style.backgroundColor = 'grey';
        divider5_1.style.margin = 'auto';
        divider5_1.style.marginTop = '20px';


        // create meal plan text area 
        let meal_text = document.createElement('p');
        meal_text.innerHTML = 'meal plan';
        meal_text.style.fontSize = '30px';
        meal_text.setAttribute('id', 'mDivMealSugg');
        meal_text.setAttribute('class', 'Meal_title');
        let Meal = document.createElement('div');
        Meal.setAttribute('class', 'col-sm col-lg-5 meal_plan');
        Meal.style.margin = 'auto';
        div1 = document.createElement('div');
        let mealDesc = document.createElement('p');
        mealDesc.setAttribute('class', 'meal_text');
        div1.appendChild(mealDesc);
        Meal.appendChild(div1);
    
        // ------------------------------------
        // edit button for Intermittent fasting description. User can add his comments here.
        // content of clientData.bmi['desc'] must be modified.
        let mealBtnDiv = document.createElement('div');
        mealBtnDiv.setAttribute('class', 'd-flex justify-content-center');
        mealBtnDiv.style.margin = '20px';
        let mealBtn = document.createElement('button');
        mealBtn.setAttribute('class', 'btn btn-outline-primary');
        mealBtn.innerHTML = 'Edit';
        mealBtn.addEventListener('click', function(){
            addUsersuggestionContent(clientData.meal, mealDesc, mealBtn, 'desc');
            if(mealBtn.innerHTML == 'Edit') {
                saveUserCommentstoDb(clientData.meal['desc'], userid, clientid, 'Meal');
            }
        });    
        mealBtnDiv.appendChild(mealBtn);


        // -----------------------------
        // appending to mDiv from here:

        mDiv.appendChild(closeBtn);
        if(!accessDenied){
            mDiv.appendChild(pdfBtn);
            mDiv.appendChild(emailBtn);
        }
        mDiv.appendChild(nameP);
        mDiv.appendChild(genderP);
        mDiv.appendChild(goalP);
        mDiv.appendChild(idP);
        mDiv.appendChild(campaignP);
        mDiv.appendChild(mealText);
        mDiv.appendChild(nutritionText);

        mDiv.appendChild(divider1);
        mDiv.appendChild(bmrSuggestion);
        mDiv.appendChild(bmiSuggestion);
        mDiv.appendChild(bmi);
        if(!accessDenied){
            mDiv.appendChild(bmiBtnDiv);
        }

        mDiv.appendChild(divider2);
        mDiv.appendChild(ifSuggestion);
        mDiv.appendChild(If);
        if(!accessDenied){
            mDiv.appendChild(ifBtnDiv);
        }

        mDiv.appendChild(divider2Vit);
        mDiv.appendChild(macroSuggestion);
        mDiv.appendChild(macro);
        if(!accessDenied){
            mDiv.appendChild(macroBtnDiv);
        }

        mDiv.appendChild(divider3);
        mDiv.appendChild(microSuggestion);
        mDiv.appendChild(micro);
        if(!accessDenied){
            mDiv.appendChild(microBtnDiv);
        }

        mDiv.appendChild(divider4);
        mDiv.appendChild(microVitSuggestion);
        mDiv.appendChild(microVit);
        if(!accessDenied){
            mDiv.appendChild(microVitBtnDiv);
        }

        mDiv.appendChild(divider5);
        mDiv.appendChild(calSuggestion);
        mDiv.appendChild(Cal);
        if(!accessDenied){
            mDiv.appendChild(calBtnDiv);
        }   

        mDiv.appendChild(divider5_1);
        mDiv.appendChild(meal_text);
        mDiv.appendChild(Meal);
        if(!accessDenied){
            mDiv.appendChild(mealBtnDiv);
        }

        // resetting the Queue for re-arming.
        eventSourceQueue = {Bmi:false, If:false, Macro:false, MicroTrace:false, MicroVit:false, Cal:false, Meal:false};
        allowNewAiStream = true;
        parentNode.appendChild(mDiv);
        plotBmi(clientData.bmi, bmi, bmiTxt, bmiDesc);
        plotIf(clientData.if, If, ifTxt, ifDesc);
        plotMacro(clientData.macro, macro, macroTxt, macroDesc);
        plotMicro(clientData.micro, micro, microTxt, microDesc);
        plotMicroVit(clientData.micro, microVit, microVitTxt, microVitDesc);
        plotCalories(clientData.cal, Cal, calTxt, calDesc);
        displayMeal(clientData.meal, inputBlob, 0); 
        
        intervalID = setInterval(handleAi, 2000, [0, results.nutritionEng[cidx], results.mealEng[cidx]]);
    }
}

function createPdf(node, data) {

    const { jsPDF } = window.jspdf;
    
    const canvasImgBmi        = document.getElementById('Bmi').toDataURL('image/png', 1.0);
    const canvasImgMicroTrace = document.getElementById('Micro').toDataURL('image/png', 1.0);
    const canvasImgMicroVit   = document.getElementById('Micro_vit').toDataURL('image/png', 1.0);
    const canvasImgMacro      = document.getElementById('Macro').toDataURL('image/png', 1.0);
    const canvasImgIf         = document.getElementById('IntermittentFasting').toDataURL('image/png', 1.0);
    const canvasImgCal        = document.getElementById('Calories').toDataURL('image/png', 1.0);
    const nameP               = document.getElementById('mDivpName');
    const goalP               = document.getElementById('mDivpGoal');
    const idP                 = document.getElementById('mDivpId');


    var pdf = new jsPDF({
                         orientation: 'p',
                         unit: 'px',
                         format: 'letter',
                         putOnlyUsedFonts:true
                         });
    pdf.setFontSize(10);
    pdf.setTextColor('#000000');
    pdf.text('Hey there, ', 50, 50); 

    pdf.setFontSize(20);
    pdf.setTextColor('#4285F4');
    pdf.text(nameP.innerHTML, 85, 50); 

    pdf.setFontSize(10);
    pdf.setTextColor('#000000'); 
    pdf.text('Your goal: ', 50, 70);  

    pdf.setFontSize(20);
    pdf.setTextColor('#F4B400');
    pdf.text(goalP.innerHTML, 85, 70); 

    pdf.setFontSize(10);
    pdf.setTextColor('#000000'); 
    pdf.text('Your ID: ', 50, 90);  

    pdf.setFontSize(20);
    pdf.setTextColor('#964B00');
    pdf.text(idP.innerHTML, 85, 90); 

    pdf.line(50, 100, 400, 100, 'S');

    pdf.setFontSize(10);
    pdf.setTextColor('#000000');
    pdf.text('Your basal metabolic rate (BMR) kcal / day: ', 50, 120); 

    pdf.setFontSize(20);
    pdf.setTextColor('#FFA500');
    pdf.text(String(Math.floor(data.bmr['val']*100)/100), 200, 120); 

    pdf.setFontSize(10);
    pdf.setTextColor('#000000');
    pdf.text(data.bmr['desc'], 50, 140, {maxWidth:370}); 

    pdf.setFontSize(10);
    pdf.setTextColor('#000000');
    pdf.text('Your body mass index: ', 50, 180); 

    pdf.setFontSize(20);
    pdf.setTextColor('#2E8B57');
    pdf.text(String(Math.floor(data.bmi['val']*100)/100), 130, 180); 

    pdf.setFontSize(22);
    pdf.setTextColor('#000000');
    pdf.text('BMI plot', 50, 220);

    pdf.addImage(canvasImgBmi, 'JPEG', 80, 240, 300, 220, 'alias1', 'NONE');

    pdf.setFontSize(10);
    pdf.setTextColor('#000000');
    pdf.text(data.bmi['desc'] , 50, 480, {maxWidth:370}); 
    
    // page reset
    pdf.addPage();

    pdf.setFontSize(22);
    pdf.setTextColor('#000000');
    pdf.text('Intermittent fasting recommendation', 50, 50);

    pdf.addImage(canvasImgIf, 'JPEG', 80, 70, 300, 160, 'alias2', 'NONE');

    pdf.setFontSize(10);
    pdf.setTextColor('#000000');
    pdf.text(data.if['desc'] , 50, 280, {maxWidth:370});

    // page reset
    pdf.addPage();

    pdf.setFontSize(22);
    pdf.setTextColor('#000000');
    pdf.text('Macro nutrients recommendation', 50, 50);

    pdf.addImage(canvasImgMacro, 'JPEG', 80, 70, 300, 220, 'alias3', 'NONE');

    pdf.setFontSize(10);
    pdf.setTextColor('#000000');
    pdf.text(data.macro['desc'] , 50, 320, {maxWidth:370});

    // page reset
    pdf.addPage();

    pdf.setFontSize(22);
    pdf.setTextColor('#000000');
    pdf.text('Micro Trace mineral recommendation', 50, 50);

    pdf.addImage(canvasImgMicroTrace, 'JPEG', 80, 70, 300, 220, 'alias4', 'NONE');
    
    pdf.setFontSize(10);
    pdf.setTextColor('#000000');
    pdf.text(data.micro['descTrace'] , 50, 320, {maxWidth:370});

    // page reset
    pdf.addPage();

    pdf.setFontSize(22);
    pdf.setTextColor('#000000');
    pdf.text('Micro Vitamins recommendation', 50, 50);

    pdf.addImage(canvasImgMicroVit, 'JPEG', 80, 70, 300, 220, 'alias5', 'NONE');
        
    pdf.setFontSize(10);
    pdf.setTextColor('#000000');
    pdf.text(data.micro['descVit'] , 50, 320, {maxWidth:370});

    // page reset
    pdf.addPage();

    pdf.setFontSize(22);
    pdf.setTextColor('#000000');
    pdf.text('Calories intake recommendation', 50, 50);

    pdf.addImage(canvasImgCal, 'JPEG', 80, 70, 300, 170, 'alias6', 'NONE');
       
    pdf.setFontSize(10);
    pdf.setTextColor('#000000');
    pdf.text(data.cal['desc'] , 50, 280, {maxWidth:370});
   
    // page reset
    pdf.addPage();
    pdf.setFontSize(22);
    pdf.setTextColor('#000000');
    pdf.text('Meal plan', 50, 50);
      
    pdf.setFontSize(10);
    pdf.setTextColor('#000000');
    pdf.text(data.meal['desc'] , 50, 70, {maxWidth:370});


    let idNumber = idP.innerHTML;
    const currentDate = new Date().toDateString();
    pdf.save('progress report ' + currentDate + ' clientId ' + idNumber + '.pdf');
}

function cleanClientDiv(mDiv) {
    while( mDiv.childElementCount > 0){
        mDiv.removeChild(mDiv.children[0]);
    }
    clearInterval(intervalID);
}

function cleanCampaignDiv(mDiv) {
    while( mDiv.childElementCount > 0){
        mDiv.removeChild(mDiv.children[0]);
    }
}

function sendEmail(parentNode, clientData, userid, clientid) {

    const canvasImgBmi        = document.getElementById('Bmi').toDataURL('image/png', 1.0);
    const canvasImgIf         = document.getElementById('IntermittentFasting').toDataURL('image/png', 1.0);
    const canvasImgMicroTrace = document.getElementById('Micro').toDataURL('image/png', 1.0);
    const canvasImgMicroVit   = document.getElementById('Micro_vit').toDataURL('image/png', 1.0);
    const canvasImgMacro      = document.getElementById('Macro').toDataURL('image/png', 1.0);
    const canvasImgCal        = document.getElementById('Calories').toDataURL('image/png', 1.0);


    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            data = JSON.parse(this.response);
            if(data['status'] == 0) {
                window.alert('email sent to the client!');
            }
        }
    };
    // sending the request
    xmlhttp.open("POST", "assets/php/contact.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    let info = 
            {
                'parentNode':    parentNode.outerHTML, 
                'bmiImg':        canvasImgBmi, 
                'ifImg':         canvasImgIf,
                'microTraceImg': canvasImgMicroTrace,
                'microVitImg':   canvasImgMicroVit,
                'macroImg':      canvasImgMacro,
                'calImg':        canvasImgCal,
                'clientData':    clientData, 
                'userId':        userid, 
                'clientId':      clientid
            };
    var userdata = "userInfo="+JSON.stringify(info);
    xmlhttp.send(userdata);
}


function addUsersuggestionContent(data, desc, Btn, descField) {
    
    if(Btn.innerHTML == 'Edit'){
        let userIn = document.createElement('textarea');
        let savedText = desc.parentNode.children[0];
        savedText.style.position = 'absolute';
        savedText.style.opacity = 0;
        userIn.innerHTML = desc.innerHTML;
        userIn.setAttribute('rows', '10');
        userIn.setAttribute('cols', '35');
        userIn.style.margin = '20px';
        userIn.style.overflow = 'scroll';
        userIn.style.scrollBehavior = 'smooth';
        userIn.style.position = 'relative';
        desc.parentNode.appendChild(userIn);
        Btn.innerHTML = 'Save';
    } else {
        let savedText = desc.parentNode.children[0];
        savedText.style.opacity = 1;
        savedText.style.position = 'relative';
        savedText.innerHTML = desc.parentNode.children[1].value;
        desc.parentNode.removeChild(desc.parentNode.children[1]);
        data[descField] = savedText.innerHTML;
        desc.innerHTML = savedText.innerHTML;
        Btn.innerHTML = 'Edit';
    }
}

function saveUserCommentstoDb(clientText, userid, clientid, topic) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
    };
    // sending the request
    xmlhttp.open("POST", "assets/php/saveUserComments.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    let info = {'topic': topic, 'clientText': clientText, 'userId': userid, 'clientId': clientid};
    var userdata = "userInfo="+JSON.stringify(info);
    xmlhttp.send(userdata);
}

function chargeUser() {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            data = JSON.parse(this.response);
        }
    };
    // sending the request
    xmlhttp.open("POST", "assets/php/paymentGateway.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    let info = {};
    var userdata = "userInfo="+JSON.stringify(info);
    xmlhttp.send(userdata);
}