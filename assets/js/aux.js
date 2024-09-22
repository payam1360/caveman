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
let stripeApi = [];
let stripe;
let stripeElements;
let gSearchClients = [];
let gSearchInvoices = [];
let selectedPhoneNumber = [];
let clientTelegramUserName = [];
let chatArea = ''; 
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
            if(counter == MAX_cnt - 1 && page == 'questions') {
                let moveleft = document.querySelector('.form-go-left');
                moveleft.disabled = true;
                moveleft.style.opacity = 0;
            }
            if(counter == MAX_cnt - 1 && page != 'questions') {
                let resultBtn = document.querySelector('.results-btn');
                moveright.disabled = true;
                moveright.style.opacity = 0;
                if(page == 'register' || page == 'main' || page == 'addClients') {
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

            if(page == 'questions'){
                dynamicQcontent(Questions[4].qAnswer);
            } else {
                dynamicQcontent('name');
            }
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
            // handling invoice and finance page
            if(page == 'finances') {
                for(idxFound = 0; idxFound < Questions.length; idxFound++){
                    
                    if(Questions[idxFound].qKey[0] == 'invoiceClientID'){
                        invoiceClientID = document.getElementById('invoiceClientID');
                        invoiceClientID.innerHTML = Questions[idxFound].qAnswer;
                    }
                    if(Questions[idxFound].qKey[0] == 'invoiceDue'){
                        invoiceDue = document.getElementById('invoiceDue');
                        invoiceDue.innerHTML = Questions[idxFound].qAnswer;
                    }

                    if(Questions[idxFound].qKey[0] == 'invoiceFee'){
                        invoiceFee = document.getElementById('invoiceFee');
                        invoiceFee.innerHTML = Questions[idxFound].qAnswer;
                    }
                    if(Questions[idxFound].qKey[0] == 'invoiceHr'){
                        invoiceHr = document.getElementById('invoiceHr');
                        invoiceHr.innerHTML = Questions[idxFound].qAnswer;
                    }
                    if(Questions[idxFound].qKey[0] == 'invoiceStart'){
                        invoiceStart = document.getElementById('invoiceStart');
                        invoiceStart.innerHTML = Questions[idxFound].qAnswer;
                    }
                    if(Questions[idxFound].qKey[0] == 'invoiceEnd'){
                        invoiceEnd = document.getElementById('invoiceEnd');
                        invoiceEnd.innerHTML = Questions[idxFound].qAnswer;
                    }
                }
                if(counter == MAX_cnt - 1) {
                    InvoiceCreate = document.getElementsByClassName('invoice-create');
                    InvoiceCreate[0].disabled = false;
                }
                
            }

            // updating the progress
            if(page == 'main' || page == 'questions' || page == 'register' || page == 'login' || page == 'addClients') {
                let p = (prog / (MAX_cnt - 1));
                progChart.data.datasets[0].data.pop();
                progChart.data.datasets[0].data.pop();
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


function transition2Right(header, headerTxt, input, Questions, serverStruct = 0, serverStructOption = 0, page = '') {
    
    headerTxt[0].innerHTML = Questions[counter].qContent[serverStruct];
    // set form 0 type
    choiceTracker[0].push(serverStruct);
    choiceTracker[1].push(serverStructOption);
    resetFormType(input[0]);
    if(page == 'questions' && Questions[counter].qType[serverStruct] == 'list'){
        QuestionsChoice = 'list';
    } else {
        QuestionsChoice = '';
    }
    setFormType(input[0], Questions[counter], serverStruct, serverStructOption, QuestionsChoice);
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
            if(page == 'questions'){
                resetDynamicQcontent(Questions[4].qAnswer);
            } else {
                resetDynamicQcontent('name');
            }
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
function setFormType(querySelIn, userStruct, serverStruct = 0, serverStructOption = 0, QuestionsChoice = ''){
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
            newIn.setAttribute('placeholder', userStruct.optionsText[0][0]);
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
                // this part is for handling items in Questions page
                if(QuestionsChoice == 'list'){
                    if(Questions[1].qAnswer == 0) { // 
                        if(item == 'sugar' || item == 'water' || item == 'alcohol'){
                            Option.style.color = 'grey';
                            Option.disabled = true;
                        }
                    } else if(Questions[1].qAnswer == 2) {
                        if(item == 'workout' || item == 'calories'){
                            Option.style.color = 'grey';
                            Option.disabled = true;
                        }
                    } else {

                    }
                }
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
                      height: '100px',
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
    // handle Questions page also
    done = false;
    for(serverStructCounter = 0; serverStructCounter < Questions[counter].qContent.length; serverStructCounter++){
        if(Questions[counter].qContent[serverStructCounter].includes('#dynomicContent')){
            for(kk = 0; kk < counter; kk++){
                if(Questions[kk].qKey[0] == context){
                    let dyno = Questions[counter].qContent[serverStructCounter].replace('#dynomicContent', Questions[kk].qAnswer + '!!');
                    Questions[counter].qContent[serverStructCounter] = dyno;
                    done = true;
                }
            }
            if(done == false){
                let dyno = Questions[counter].qContent[serverStructCounter].replace('#dynomicContent', context + '!!');
                Questions[counter].qContent[serverStructCounter] = dyno;
            }
        } 
    }
}

function resetDynamicQcontent(context) {
    done = false;
    for(serverStructCounter = 0; serverStructCounter < Questions[counter + 1].qContent.length; serverStructCounter++){
        if(Questions[counter + 1].qContent[serverStructCounter].includes('!!')){
            for(kk = 0; kk < counter + 1; kk++){
                if(Questions[kk].qKey[0] == context){
                    let dyno = Questions[counter + 1].qContent[serverStructCounter].replace(Questions[kk].qAnswer, '#dynomicContent');
                    Questions[counter + 1].qContent[serverStructCounter] = dyno;
                    done = true;
                }
            }
            if(done == false){
                for(kk = 0; kk < counter + 1; kk++){
                    if(Questions[kk].qAnswer == context){
                        let dyno = Questions[counter + 1].qContent[serverStructCounter].replace(Questions[kk].qAnswer, '#dynomicContent');
                        Questions[counter + 1].qContent[serverStructCounter] = dyno;
                    }
                }
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
    //reset the Question form
    Questions = [];
    questionCreate(headerTxt[1], header[1], input[1], page, userPage);

    // initialize the input based on form Type
    resetFormType(input[2]);
    resetFormType(input[1]);
    resetFormType(input[0]);
    let moveleft = document.querySelector('.form-go-left'); 
    moveleft.disabled = true;
    moveleft.style.opacity = 0;
    let moveright = document.querySelector('.form-go-right');
    moveright.disabled = false;
    moveright.style.opacity = 1; 

    let resultBtn = document.querySelector('.results-btn');
    if(resultBtn) {
        resultBtn.innerHTML = 'Show results';
        resultBtn.onclick = null;
    }
    let spinner = document.querySelector('.spinner-js');

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
        if(page == 'questions' && Questions[counter].qType[event.target.getAttribute('serverStruct')] == 'list'){
            QuestionsChoice = 'list';
        } else {
            QuestionsChoice = '';
        }
        setFormType(event.target, Questions[counter], event.target.getAttribute('serverStruct'), event.target.getAttribute('serverStructOption'), QuestionsChoice);
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
    input[2].addEventListener('transitionend', function(event) {
        ChangeForm(event.target, '0.0s', '0', 0, '0%');
        resetFormType(event.target);
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
        });
        
    }

    prog = 0;
    //flush the choiceTracker
    choiceTracker = [[0],[0]];
    multiButtonSelect = [];
    multiTextSelect = [];
    // reset the progress bar
    let ctx = document.querySelector('#ProgressCircle');
    if(typeof ctx !== undefined){
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
        // generate the chart
        
        if(progChart.length != 0 ){
            
            progChart.destroy(); 
        }
        if(ctx){
            progChart = new Chart(ctx, config);
        }
    }
    if(page == 'finances' || page == 'financesSearch'){
        plotPaymentsInvoices();
        plotRevenue();
        InvoiceSend = document.getElementsByClassName('invoice-send');
        InvoiceSend[0].disabled = true;
        InvoiceCreate = document.getElementsByClassName('invoice-create');
        InvoiceCreate[0].disabled = true;
        let connectStripe = document.getElementById('connectStripeButton');
        connectStripe.addEventListener('click', function(s) {
            connectUserToStripe();
        });
        InvoiceCreate = document.getElementsByClassName('invoice-create');
        if(InvoiceCreate) {
            InvoiceCreate[0].addEventListener('click', function(s) {
                if(InvoiceCreate[0].innerHTML == 'create') {
                    createInvoice(Questions);
                } else if(InvoiceCreate[0].innerHTML == 'remove') {
                    removeInvoice(Questions[0].userId, Questions[0].qAnswer, document.getElementById('invoiceNum').innerHTML, 0);
                    InvoiceCreate[0].innerHTML = 'create';
                }
            });
        }
    }

    if(page == 'main'){
        bmiDiv = document.querySelector('.Bmi');
        bmiDiv.style.display = 'none';
        ifDiv = document.querySelector('.IntermittentFasting');
        ifDiv.style.display = 'none'; 
        macroDiv = document.querySelector('.Macro');
        macroDiv.style.display = 'none';
        microDiv = document.querySelector('.Micro');
        microDiv.style.display = 'none';           
        microVitDiv = document.querySelector('.Micro_vit');
        microVitDiv.style.display = 'none';
        calDiv = document.querySelector('.Calories');
        calDiv.style.display = 'none';   
        mealDiv = document.querySelector('.meal_plan');
        mealDiv.style.display = 'none';   
        adDiv = document.querySelector('.Advertisement');
        adDiv.style.display = 'none';       
    }
}

function connectUserToStripe() {
    
    document.getElementById('stripeModal').style.display = 'block';
    document.getElementById('connectStripe').addEventListener('click', function() {
        // Redirect to Stripe OAuth2 authorization                                                                                      
        window.location.href = "https://connect.stripe.com/oauth/authorize?response_type=code&client_id=ca_Qmt1Xt64gBsUCBgpCr9lTdagfE0vQiv8&scope=read_write&redirect_uri=http://localhost/assets/php/stripeRedirect.php";
    });
    document.getElementById('closeButton').addEventListener('click', function() {
        document.getElementById('stripeModal').style.display = 'none';
    });  
}

function addUserStripeInfo(uId) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            let connectStripe = document.getElementById('connectStripeButton');
            connectStripe.disabled = true;
            connectStripe.style.opacity = 0;
        }
    };
    // sending the request
    xmlhttp.open("POST", "assets/php/finances.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    let inputData = {'flag': 'addStripe', 'userId': uId};
    let request = "userInfo="+JSON.stringify(inputData);
    xmlhttp.send(request);  
}

function confirmStripeConnection(uId) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            let stripeConnection  = JSON.parse(this.response);
            if(stripeConnection) {
                let connectStripe = document.getElementById('connectStripeButton');
                connectStripe.disabled = true;
                connectStripe.style.opacity = 0;
            }
        }
    };
    // sending the request
    xmlhttp.open("POST", "assets/php/finances.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    let inputData = {'flag': 'checkStripe', 'userId': uId};
    let request = "userInfo="+JSON.stringify(inputData);
    xmlhttp.send(request);  
}


window.onload = function() {
    const params = new URLSearchParams(window.location.search);
    if (params.get('addUserStripeInfo') === 'true') {
        addUserStripeInfo(Questions[0].userId);
    } 
};


function searchClients(searchStruct) {
    fetchClients(searchStruct);
}

function searchInvoices(searchInvStruct, uId) {
    fetchInvoices(searchInvStruct, uId);
}

function removeInvoice(uId, cId, iId, alt) {

    document.getElementById('invoiceClientID').innerHTML = '';
    document.getElementById('invoiceFee').innerHTML = '';
    document.getElementById('invoiceHr').innerHTML = '';
    document.getElementById('invoiceStart').innerHTML = '';
    document.getElementById('invoiceEnd').innerHTML = ''; 
    document.getElementById('invoiceDue').innerHTML = ''; 
    document.getElementById('invoiceTotal').innerHTML = ''; 
    document.getElementById('invoiceClientName').innerHTML = '';
    document.getElementById('invoiceNum').innerHTML = ''; 
    // remove the db entry
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            InvoiceCreate = document.getElementsByClassName('invoice-create');
            InvoiceCreate[alt].disabled = true;
            InvoiceSend = document.getElementsByClassName('invoice-send');
            InvoiceSend[alt].disabled = true;
            window.alert('Invoice deleted');
        }
    };
    // sending the request
    xmlhttp.open("POST", "assets/php/finances.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    let inputData = {'flag': 'remove', 'userId': uId, 'clientId': cId, 'invoiceNum': iId};
    let request = "userInfo="+JSON.stringify(inputData);
    xmlhttp.send(request);
}


function getPublicStripeKey(){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            stripeApi = JSON.parse(this.response);
            stripe = Stripe(stripeApi);
        }
    };
    // sending the request
    xmlhttp.open("POST", "assets/php/getStripePublicKey.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    let inputData = {'userId': ''};
    let request = "userInfo="+JSON.stringify(inputData);
    xmlhttp.send(request);
}


function sendInvoices(uId, cardData, alt) {
    var invoiceClientID = '';
    var invoiceClientName = '';
    var invoiceFee = '';
    var invoiceHr = '';
    var invoiceStart = '';
    var invoiceEnd = '';
    var invoiceDue = '';
    var invoiceTotal = '';
    var invoiceNum = '';

    if(cardData != '') {
        invoiceClientID = cardData.clientId;
        invoiceClientName = cardData.clientName;
        invoiceFee = cardData.fee;
        invoiceHr = cardData.numHour;
        invoiceStart = cardData.serviceStart;
        invoiceEnd = cardData.serviceEnd;
        invoiceDue = cardData.dueDate;
        invoiceTotal = cardData.fee * cardData.numHour;
        invoiceNum = cardData.invoiceNum;
        
    } else {
        invoiceClientID = document.getElementById('invoiceClientID').innerHTML;
        invoiceClientName = document.getElementById('invoiceClientName').innerHTML;    
        invoiceFee = document.getElementById('invoiceFee').innerHTML;
        invoiceHr = document.getElementById('invoiceHr').innerHTML;
        invoiceStart = document.getElementById('invoiceStart').innerHTML;
        invoiceEnd = document.getElementById('invoiceEnd').innerHTML;
        invoiceDue = document.getElementById('invoiceDue').innerHTML;  
        invoiceTotal = document.getElementById('invoiceTotal').innerHTML; 
        invoiceNum = document.getElementById('invoiceNum').innerHTML; 
    }
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            let InvoiceCreate = document.getElementsByClassName('invoice-create');
            InvoiceCreate[alt].disabled = true;
            let InvoiceSend = document.getElementsByClassName('invoice-send');
            InvoiceSend[alt].disabled = true;
            window.alert('Invoice sent');
        }
    };
    // sending the request
    xmlhttp.open("POST", "assets/php/finances.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    let inputData = {'flag': 'send', 'userId': uId, 
                     'clientId': invoiceClientID, 'clientName': invoiceClientName,
                     'fee': invoiceFee, 'invoiceHr': invoiceHr, 'invoiceDue': invoiceDue,
                     'serviceStart': invoiceStart, 'serviceEnd': invoiceEnd, 
                     'invoiceNum': invoiceNum, 'invoiceTotal': invoiceTotal};
    let request = "userInfo="+JSON.stringify(inputData);
    xmlhttp.send(request);

}

function fetchInvoices(searchInvStruct, uId) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            let invoiceInfo = JSON.parse(this.response);
            constructInvoices(invoiceInfo, searchInvStruct, uId);
        }
    };
    // sending the request
    xmlhttp.open("POST", "assets/php/finances.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    let inputData = {'flag': 'get', 'userId': uId};
    let request = "userInfo="+JSON.stringify(inputData);
    xmlhttp.send(request);
}


function constructInvoices(invoiceInfo, searchInvStruct, uId) {
    
    
    const container = document.getElementById('card-container');
    container.innerHTML = ''; // Clear any existing cards
    var i = 1;
    invoiceInfo.forEach(cardData => {
        // search function
        // only display matching search strings
        if(typeof(searchInvStruct) == 'undefined' || searchInvStruct == '') {
        } else if(typeof(searchInvStruct) !== 'undefined' ) {
            if(searchInvStruct.searchStr.length == 0) {
            } else {
                if(searchInvStruct.key == 0) { // search client ID
                    if(cardData.clientId.slice(0, searchInvStruct.searchStr.length) != searchInvStruct.searchStr){
                        return;
                    }
                } else if(searchInvStruct.key == 2) { // search client name
                    if(cardData.clientName.slice(0, searchInvStruct.searchStr.length) != searchInvStruct.searchStr){
                        return;
                    }
                } else if(searchInvStruct.key == 1) { // search invoice ID
                    let invoiceID = cardData.invoiceNum.toLowerCase();
                    if(invoiceID.slice(0, searchInvStruct.searchStr.length) != searchInvStruct.searchStr.toLowerCase()){
                        return;
                    }
                }
            }
        } 
        // Create card container
        const card = document.createElement('div');
        card.className = 'card col-md-4'; // Bootstrap class for responsive column
        
        // Create card header with status
        const invoiceStatusElem = document.createElement('p');
        let invoiceStatus = [];
        let cardColor = [];
        let buttonCaption = [];
        if(cardData.invoiceStatus == 0){
            invoiceStatus = 'paid';
            cardColor = '#90EE90';
            buttonCaption = [];
        } else if(cardData.invoiceStatus == 1) {
            invoiceStatus = 'pending';
            cardColor = '#FFD580';
            buttonCaption = [];
        } else if(cardData.invoiceStatus == 2) {
            invoiceStatus = 'overdue';
            cardColor = '#FF474C';
            buttonCaption = ['Remind'];
        } else if(cardData.invoiceStatus == 3) {
            invoiceStatus = 'Not yet sent';
            cardColor = 'lightblue';
            buttonCaption = ['Delete', 'Send'];
        } else {
            invoiceStatus = 'No status';
            cardColor = 'lightgrey';
            buttonCaption = [];
        }
        invoiceStatusElem.innerHTML = `<span style="padding-top: 5px; font-weight: bold; font-size: 20px; text-align: left"> ${invoiceStatus} </span>`;
        const cardHeader = document.createElement('div');
        cardHeader.className = 'card-header';
        cardHeader.style.backgroundColor = cardColor;
        cardHeader.appendChild(invoiceStatusElem);
        card.appendChild(cardHeader);

        // Create card body with information
        const cardBody = document.createElement('div');
        cardBody.className = 'card-body';
        
        const clientNameElem = document.createElement('p');
        clientNameElem.innerHTML = `<span class="label">Client Name:</span> ${cardData.clientName}`;
        cardBody.appendChild(clientNameElem);

        const clientIDElem = document.createElement('p');
        clientIDElem.innerHTML = `<span class="label">Client ID:</span> ${cardData.clientId}`;
        cardBody.appendChild(clientIDElem);

        const invoiceIDElem = document.createElement('p');
        invoiceIDElem.innerHTML = `<span class="label">Invoice ID:</span> ${cardData.invoiceNum}`;
        cardBody.appendChild(invoiceIDElem);

        const invoiceAmountElem = document.createElement('p');
        let totalAmount = parseInt(cardData.numHour) * parseInt(cardData.fee)
        invoiceAmountElem.innerHTML = `<span class="label">Invoice Amount:</span> ${totalAmount.toString()}`;
        cardBody.appendChild(invoiceAmountElem);

        const dueDateElem = document.createElement('p');
        dueDateElem.innerHTML = `<span class="label">Due Date:</span> ${cardData.dueDate}`;
        cardBody.appendChild(dueDateElem);

        const buttonContainer = document.createElement('div');
        buttonContainer.className = 'container';
        const buttonRow = document.createElement('div');
        buttonRow.className = 'row';
        const buttonDiv = document.createElement('div');
        buttonDiv.className = 'd-flex justify-content-end';
        for(kk = 0; kk < buttonCaption.length; kk++){
            const buttonElem = document.createElement('button');
            buttonElem.style.marginRight = '5px';
            buttonElem.innerHTML = buttonCaption[kk];
            buttonElem.setAttribute('alt', i);
            if(buttonCaption[kk] == 'Send' || buttonCaption[kk] == 'Remind') {
                buttonElem.className = 'btn btn-outline-primary invoice-send';
            } else {
                buttonElem.className = 'btn btn-outline-primary invoice-create';
            }
            
            buttonElem.addEventListener('click', function(s) {
                alt = s.target.getAttribute('alt');
                if(buttonElem.innerHTML == 'Send' || buttonElem.innerHTML == 'Remind') {
                    sendInvoices(uId, cardData, alt);
                    buttonElem.disabled = true;
                    buttonElem.style.backgroundColor = 'lightgrey';
                } else if(buttonElem.innerHTML == 'Delete') {
                    removeInvoice(uId, cardData.clientId, cardData.invoiceNum, alt);
                    buttonElem.disabled = true;
                    buttonElem.style.backgroundColor = 'lightgrey';
                }
            });
            buttonDiv.appendChild(buttonElem);
        }
        buttonRow.appendChild(buttonDiv);
        buttonContainer.appendChild(buttonRow);
        cardBody.appendChild(dueDateElem);
        cardBody.appendChild(buttonContainer);


        card.appendChild(cardBody);
        // Append card to the container
        container.appendChild(card);
        i++;
    });

}


function createInvoice(Questions) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            let invoiceInfo = JSON.parse(this.response);
            if(invoiceInfo == false){
                window.alert('Wrong Client ID.');
            } else {
                let invoiceClientName = document.getElementById('invoiceClientName');
                invoiceClientName.innerHTML = invoiceInfo.clientName;
                let invoiceNum = document.getElementById('invoiceNum');
                invoiceNum.innerHTML = invoiceInfo.invoiceNum;
                // get send button and activate it
                let invoiceTot = document.getElementById('invoiceTotal');
                invoiceTot.innerHTML = invoiceInfo.invoiceTot;
                InvoiceSend = document.getElementsByClassName('invoice-send');
                InvoiceSend[0].disabled = false;
                InvoiceSend[0].addEventListener('click', function(s) {
                    sendInvoices(Questions[0].userId, '', 0);
                });
                InvoiceCreate = document.getElementsByClassName('invoice-create');
                InvoiceCreate[0].innerHTML = 'remove';
            }
        }
    };
    // sending the request
    xmlhttp.open("POST", "assets/php/finances.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    let inputData = {'flag': 'create', 'userId': Questions[0].userId, 
                     'clientId': Questions[0].qAnswer, 'clientName': '',
                     'fee': Questions[1].qAnswer, 'numHour': Questions[2].qAnswer, 
                     'invoiceDue': Questions[3].qAnswer, 
                     'serviceStart': Questions[4].qAnswer, 'serviceEnd': Questions[5].qAnswer};
    let request = "userInfo="+JSON.stringify(inputData);
    xmlhttp.send(request);
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
                transition2Right(header, headerTxt, querySelIn, inputDataBlob, 0, 0, 'questions');
                let moveright = document.querySelector('.form-go-right');
                moveright.style.opacity = 0;
                moveright.disabled = true;
            } else if(data.status == 1) {
                transition2Right(header, headerTxt, querySelIn, inputDataBlob, 1, 0, 'questions'); 
                globalQidx = 9;   
            } else if(data.status == 100) {
                transition2Right(header, headerTxt, querySelIn, inputDataBlob, 1, 0, 'questions');       
            } else if(data.status == 3) { 
                transition2Right(header, headerTxt, querySelIn, inputDataBlob, 0, 0, 'questions');    
            } else if(data.status == 4) {
                transition2Right(header, headerTxt, querySelIn, inputDataBlob, 1, 0, 'questions');
            } else if(data.status == 5) {
                transition2Right(header, headerTxt, querySelIn, inputDataBlob, 1, 0, 'questions');
            } else if(data.status == 6) {
                transition2Right(header, headerTxt, querySelIn, inputDataBlob, 1, 0, 'questions');
            } else if(data.status == 7) {
                transition2Right(header, headerTxt, querySelIn, inputDataBlob, 1, 0, 'questions');
            } else if(data.status == 14) {
                transition2Right(header, headerTxt, querySelIn, inputDataBlob, 1, 0, 'questions');
            } else if(data.status == 15) {
                transition2Right(header, headerTxt, querySelIn, inputDataBlob, 1, 1, 'questions');
            } else if(data.status == 16) {
                transition2Right(header, headerTxt, querySelIn, inputDataBlob, 0, 0, 'questions');
            } else if(data.status == 34) {
                transition2Right(header, headerTxt, querySelIn, inputDataBlob, 0, 0, 'questions');
            } else if(data.status == 35) {
                transition2Right(header, headerTxt, querySelIn, inputDataBlob, 0, 0, 'questions');
            } else if(data.status == 24) {
                transition2Right(header, headerTxt, querySelIn, inputDataBlob, 1, 1, 'questions');
            } else if(data.status == 25) {
                transition2Right(header, headerTxt, querySelIn, inputDataBlob, 1, 1, 'questions');
            } else if(data.status == 26) {
                transition2Right(header, headerTxt, querySelIn, inputDataBlob, 2, 2, 'questions');
            } else if(data.status == 27) {
                transition2Right(header, headerTxt, querySelIn, inputDataBlob, 1, 1, 'questions');
            } else if(data.status == 11) { // reset form for next question 
                resetStart(querySelIn, header, headerTxt, 'questions', 1);
                globalQidx++;
            }
            else if(data.status == 12) { // end the form 
                transition2Right(header, headerTxt, querySelIn, inputDataBlob, 0, 0, 'questions');
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
                let resultBtn = document.querySelector('.results-btn');
                resultBtn.innerHTML = 'Restart';
                resultBtn.onclick = function(){
                    resultBtn.style.display = 'none';
                    let input = document.querySelectorAll('.form-input');
                    let header = document.querySelectorAll('.form-header');
                    let headerTxt = document.querySelectorAll('.form-header-style');
                    resetStart(input, header, headerTxt, page, 0);
                }
                eventSourceQueue = {Bmi:false, If:false, Macro:false, MicroTrace:false, MicroVit:false, Cal:false, Meal:false};
                allowNewAiStream = true;
                plotBmi(data.bmi);  // 0
                plotIf(data.if);    // 1
                plotMacro(data.macro); // 2
                plotMicro(data.micro); // 3
                plotMicroVit(data.micro); // 4
                plotCalories(data.cal); // 5
                displayMeal(data.meal, inputDataBlob, userPage); // 6
                callAd();
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

// function to plot IF data returned by the server for the given user
function plotPaymentsInvoices(PaymentsInvoices){
    
    let payElement  = document.querySelector('#InvoicePayment');
    PaymentsInvoices = {data: [5, 8, 3]};
    const payData = {
      labels: ['Paid','Pending','Overdue'],
      datasets: [
        {
            label: 'status',
            data: PaymentsInvoices.data,
            backgroundColor: ['#90EE90', '#FFD580', '#FF474C'],
            borderColor: 'white',
            borderRadius: '5px',
            borderWidth: 3,
            fontSize: '16px',
        },
    ]
    };
    const config = {
      type: 'bar',
      data: payData,
      options: {
        indexAxis: 'x',
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
    if(typeof payChart !== 'undefined'){
        payChart.destroy(); 
    }
    payChart = new Chart(
      payElement,
      config
    );
}


// function to plot Revenue data returned by the server for the given user
function plotRevenue(revenue){
    // Canvas element section
    let revElement = document.querySelector('#Revenue');
    let vector = [];
    let xAxis = [];
    for (let i = 0; i < 30; i++) {
        // Generate a random number between min (inclusive) and max (exclusive)
        let randomValue = Math.exp(i/10) * Math.random();
        vector.push(randomValue);
        xAxis.push(i);  
    }
    // Config section
    revenue = {data: vector};
    
    const revData = {
      labels: xAxis,
      datasets: [{
        label: 'Revenue',
        fill: false,
        data: revenue.data,
        borderColor: 'lightskyblue',
      }]
    };
    const revConfig = {
      type: 'line',
      data: revData,
      options: {
        responsive: true,
        maintainAspectRatio: false,
        devicePixelRatio: 2,
        aspectRatio: 1,
        scales: {
            y: {
              title: {
                display: true,
                text: '$'
              }
            }
          }     },
    };

    if(typeof revChart !== 'undefined'){
        revChart.destroy(); 
    }
    revChart = new Chart(
      revElement,
      revConfig,
    );
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

    if(typeof bmiChart !== 'undefined'){
        bmiChart.destroy(); 
    }
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
    if(typeof ifChart !== 'undefined'){
        ifChart.destroy(); 
    }
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
    if(typeof calChart !== 'undefined'){
        calChart.destroy(); 
    }
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
    if(typeof macroChart !== 'undefined'){
        macroChart.destroy(); 
    }
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
               'magenta','black','DeepSkyBlue','MediumPurple','MistyRose','PaleGoldenRod',
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
    if(typeof microChart !== 'undefined'){
        microChart.destroy(); 
    }
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
    vColors = ['coral','lightblue','limegreen','cyan','blue','green','orange',
    'magenta','black','DeepSkyBlue','MediumPurple','MistyRose','PaleGoldenRod',
    'Peru','Sienna'];
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
    if(typeof microChartVit !== 'undefined'){
        microChartVit.destroy(); 
    }
    microChartVit = new Chart(
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
            display_var.style.height = 'auto';
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

function callAd(adTxt = 0){
    // add section

    let adDiv  = document.querySelector('.Advertisement');
    let adTextDiv  = document.querySelector('.AD_text');

    if(adTxt == 0 ){
        adTextDiv.innerHTML = 'Your AD here';
    }
    adDiv.style.display = 'block';
    adTextDiv.style.display = 'block';
    adTextDiv.style.margin = '20px';
}


function handleAi(inPut) {

    userPage     = inPut[0];
    nutritionEng = inPut[1];
    mealEng      = inPut[2];
    let spinner  = document.getElementsByClassName('spinner-ai'); 
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
    spinner[activeSSE].style.opacity = 1;
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
                txt_var.innerHTML += "<br><br>Good Luck!";
                eventSource.close();
                allowNewAiStream = true;
            } else {
                // styling the text as it comes through
                spinner[activeSSE].style.opacity = 0;
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
            createPdf(clientData);
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
        let stressP = document.createElement('p');
        let stressPstyle = document.createElement('span');
        let sleepP = document.createElement('p');
        let sleepPstyle = document.createElement('span');
        let waterP = document.createElement('p');
        let waterPstyle = document.createElement('span');
        let ageP = document.createElement('p');
        let agePstyle = document.createElement('span');
        let emailP = document.createElement('p');
        let emailPstyle = document.createElement('span');
        let weightP = document.createElement('p');
        let weightPstyle = document.createElement('span');
        let heightP = document.createElement('p');
        let heightPstyle = document.createElement('span');
        let sugarP = document.createElement('p');
        let sugarPstyle = document.createElement('span');
        let alcoholP = document.createElement('p');
        let alcoholPstyle = document.createElement('span');
        let workoutP = document.createElement('p');
        let workoutPstyle = document.createElement('span');

        nameP.innerHTML = "Client's name: ";
        nameP.style.marginTop = '100px';
        nameP.style.fontSize = '20px';
        namesTemp = results.names[cidx].charAt(0).toUpperCase() + results.names[cidx].slice(1);
        namePstyle.innerHTML = namesTemp;
        namePstyle.style.fontSize = '40px';
        namePstyle.style.color = 'brown';
        namePstyle.setAttribute('id', 'mDivpName');
        nameP.appendChild(namePstyle);

        idP.innerHTML = "Client's ID: ";
        idP.style.fontSize = '20px';
        idPstyle.innerHTML = results.ids[cidx];
        idPstyle.style.fontSize = '40px';
        idPstyle.style.color = 'green';
        idPstyle.setAttribute('id', 'mDivpId');
        idP.appendChild(idPstyle);    

        genderP.innerHTML = "Client's gender: ";
        genderP.style.fontSize = '20px';
        genderTemp = results.genders[cidx].charAt(0).toUpperCase() + results.genders[cidx].slice(1);
        genderPstyle.innerHTML = genderTemp;
        genderPstyle.style.fontSize = '40px';
        genderPstyle.style.color = '#F4B400';
        genderPstyle.setAttribute('id', 'mDivpGender');
        genderP.appendChild(genderPstyle);  


        goalP.innerHTML = "Client's goal: ";
        goalP.style.fontSize = '20px';
        goalsTemp = results.goals[cidx].charAt(0).toUpperCase() + results.goals[cidx].slice(1);
        goalPstyle.innerHTML = goalsTemp;
        goalPstyle.style.fontSize = '40px';
        goalPstyle.style.color = '#DB4437';
        goalPstyle.setAttribute('id', 'mDivpGoal');
        goalP.appendChild(goalPstyle); 

        if(results.mealEng[cidx] == 0){
            mealText.innerHTML = 'You have selected ';
            mealText.style.fontSize = '20px';
            mealPstyle.innerHTML = 'AI';
            mealPstyle.style.fontSize = '24px';
            mealPstyle.style.color = '#DB4437';
            mealText.appendChild(mealPstyle);
            mealText.innerHTML =  mealText.innerHTML + ' for meal planning for ' + results.names[cidx];
        } else if(results.mealEng[cidx] == 1){
            mealText.innerHTML = 'You have selected ';
            mealText.style.fontSize = '20px';
            mealPstyle.innerHTML = 'nutritionist';
            mealPstyle.style.fontSize = '24px';
            mealPstyle.style.color = '#DB4437';
            mealText.appendChild(mealPstyle);
            mealText.innerHTML =  mealText.innerHTML + ' for meal planning for ' + results.names[cidx];
        }

        if(results.nutritionEng[cidx] == 0){
            nutritionText.innerHTML = 'You have selected ';
            nutritionText.style.fontSize = '20px';
            nutritionPstyle.innerHTML = 'AI';
            nutritionPstyle.style.fontSize = '24px';
            nutritionPstyle.style.color = '#DB4437';
            nutritionText.appendChild(nutritionPstyle);
            nutritionText.innerHTML =  nutritionText.innerHTML + ' for nutritional analysis for ' + results.names[cidx];
        } else if(results.nutritionEng[cidx] == 1){
            nutritionText.innerHTML = 'You have selected ';
            nutritionText.style.fontSize = '20px';
            nutritionPstyle.innerHTML = 'nutritionist';
            nutritionPstyle.style.fontSize = '24px';
            nutritionPstyle.style.color = '#DB4437';
            nutritionText.appendChild(nutritionPstyle);
            nutritionText.innerHTML =  nutritionText.innerHTML + ' for nutritional analysis for ' + results.names[cidx];
        }

        // Adding more info about some of client's responses to questions that aren't
        // directly being used to generate key results.
        keyToFind = 'stress';
        let stress = inputBlob.find(obj => obj.qKey[0] === keyToFind);
        if(stress){
            stress = stress.optionsText[0][stress.qAnswer];
        }

        keyToFind = 'sleep';
        let sleep = inputBlob.find(obj => obj.qKey[0] === keyToFind);
        if(sleep){
            sleep = sleep.optionsText[0][sleep.qAnswer];
        }

        keyToFind = 'water';
        water = inputBlob.find(obj => obj.qKey[0] === keyToFind);
        if(water){
            water = water.optionsText[0][water.qAnswer];
        }

        keyToFind = 'age';
        age = inputBlob.find(obj => obj.qKey[0] === keyToFind);
        if(age){
            age = age.qAnswer;
        }
        keyToFind = 'email';
        email = inputBlob.find(obj => obj.qKey[0] === keyToFind);
        if(email.qAnswer != ''){
            email = email.qAnswer;
        } else if(results.emails[cidx] != '') {
            email = results.emails[cidx];
        } else {
            email = '';
        }

        keyToFind = 'weight';
        weight = inputBlob.find(obj => obj.qKey[0] === keyToFind);
        if(weight){
            weight = weight.qAnswer;
        }
        keyToFind = 'height';
        height = inputBlob.find(obj => obj.qKey[0] === keyToFind);
        if(height){
            height = height.qAnswer;
        }
        keyToFind = 'sugar';
        sugar = inputBlob.find(obj => obj.qKey[0] === keyToFind);
        if(sugar){
            sugar = sugar.optionsText[0][sugar.qAnswer];
        }
        keyToFind = 'alcohol';
        alcohol = inputBlob.find(obj => obj.qKey[0] === keyToFind);
        if(alcohol){
            alcohol = alcohol.optionsText[0][alcohol.qAnswer];
        }
        keyToFind = 'workout';
        workout = inputBlob.find(obj => obj.qKey[0] === keyToFind);
        if(workout){
            workout = workout.optionsText[0][workout.qAnswer];
        }


        stressP.innerHTML = "Stress level: ";
        stressP.style.fontSize = '20px';
        if(stress){
            stressTemp = stress.charAt(0).toUpperCase() + stress.slice(1);
        } else {
            stressTemp = '';
        }
        stressPstyle.innerHTML = stressTemp;
        stressPstyle.style.fontSize = '40px';
        stressPstyle.style.color = 'brown';
        stressPstyle.setAttribute('id', 'mDivpStress');
        stressP.appendChild(stressPstyle);

        
        sleepP.innerHTML = "Sleep quality: ";
        sleepP.style.fontSize = '20px';
        if(sleep){
            sleepTemp = sleep.charAt(0).toUpperCase() + sleep.slice(1);
        } else {
            sleepTemp = '';
        }
        sleepPstyle.innerHTML = sleepTemp;
        sleepPstyle.style.fontSize = '40px';
        sleepPstyle.style.color = 'seagreen';
        sleepPstyle.setAttribute('id', 'mDivpSleep');
        sleepP.appendChild(sleepPstyle);


        waterP.innerHTML = "Water intake: ";
        waterP.style.fontSize = '20px';
        if(water){
            waterTemp = water.charAt(0).toUpperCase() + water.slice(1);
        } else {
            waterTemp = '';
        }
        waterPstyle.innerHTML = waterTemp;
        waterPstyle.style.fontSize = '40px';
        waterPstyle.style.color = '#9df9ef';
        waterPstyle.setAttribute('id', 'mDivpWater');
        waterP.appendChild(waterPstyle);


        ageP.innerHTML = "Age: ";
        ageP.style.fontSize = '20px';
        if(age){
            ageTemp = age.charAt(0).toUpperCase() + age.slice(1);
        } else {
            ageTemp = '';
        }
        agePstyle.innerHTML = ageTemp;
        agePstyle.style.fontSize = '40px';
        agePstyle.style.color = '#51e2f5';
        agePstyle.setAttribute('id', 'mDivpAge');
        ageP.appendChild(agePstyle);

        emailP.innerHTML = "Email: ";
        emailP.style.fontSize = '20px';
        if(email){
            emailTemp = email.charAt(0).toUpperCase() + email.slice(1);
        } else {
            emailTemp = '';
        }
        emailPstyle.innerHTML = emailTemp;
        emailPstyle.style.fontSize = '40px';
        emailPstyle.style.color = '#e1b382';
        emailPstyle.setAttribute('id', 'mDivpEmail');
        emailP.appendChild(emailPstyle);


        weightP.innerHTML = "Weight: ";
        weightP.style.fontSize = '20px';
        if(weight){
            weightTemp = weight + ' lb';
        } else {
            weightTemp = '';
        }
        weightPstyle.innerHTML = weightTemp;
        weightPstyle.style.fontSize = '40px';
        weightPstyle.style.color = '#bd8c7d';
        weightPstyle.setAttribute('id', 'mDivpWeight');
        weightP.appendChild(weightPstyle);



        heightP.innerHTML = "Height: ";
        heightP.style.fontSize = '20px';
        if(height){
            heightTemp = height + ' ft';
        } else {
            heightTemp = '';
        }
        heightPstyle.innerHTML = heightTemp;
        heightPstyle.style.fontSize = '40px';
        heightPstyle.style.color = ' #bf4aa8';
        heightPstyle.setAttribute('id', 'mDivpHeight');
        heightP.appendChild(heightPstyle);


        sugarP.innerHTML = "Sugar intake: ";
        sugarP.style.fontSize = '20px';
        if(sugar){
            sugarTemp = sugar.charAt(0).toUpperCase() + sugar.slice(1);
        } else {
            sugarTemp = '';
        }
        sugarPstyle.innerHTML = sugarTemp;
        sugarPstyle.style.fontSize = '40px';
        sugarPstyle.style.color = '#6B7A8F';
        sugarPstyle.setAttribute('id', 'mDivpSugar');
        sugarP.appendChild(sugarPstyle);


        alcoholP.innerHTML = "Alcohol consumption: ";
        alcoholP.style.fontSize = '20px';
        if(alcohol){
            alcoholTemp = alcohol.charAt(0).toUpperCase() + alcohol.slice(1);
        } else {
            alcoholTemp = '';
        }
        alcoholPstyle.innerHTML = alcoholTemp;
        alcoholPstyle.style.fontSize = '40px';
        alcoholPstyle.style.color = '#a4893d';
        alcoholPstyle.setAttribute('id', 'mDivpAlcohol');
        alcoholP.appendChild(alcoholPstyle);


        workoutP.innerHTML = "Workout duration: ";
        workoutP.style.fontSize = '20px';
        if(workout){
            workoutTemp = workout.charAt(0).toUpperCase() + workout.slice(1);
        } else {
            workoutTemp = '';
        }
        workoutPstyle.innerHTML = workoutTemp;
        workoutPstyle.style.fontSize = '40px';
        workoutPstyle.style.color = '#bd8c7d';
        workoutPstyle.setAttribute('id', 'mDivpWorkout');
        workoutP.appendChild(workoutPstyle);


        let link = '/userPages/' + userid + results.ids[cidx] + results.campaignidAssigned[cidx] + '.html'
        campaignP.innerHTML = 'Link to ' + results.names[cidx] + '\'s survey <a href="' + link + '"> page</a>';
        campaignP.style.fontSize = '20px';


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
        bmiDesc.style.fontSize = '20px';
        div1.appendChild(bmiTxt);
        div2.appendChild(bmiDiv);
        div3.appendChild(bmiDesc);
        bmi.appendChild(div1);
        bmi.appendChild(div2);
        bmi.appendChild(div3);
        let bmiSpinner = document.createElement('div');
        bmiSpinner.setAttribute('class', 'spinner-ai');
        bmi.appendChild(bmiSpinner);
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
        microDesc.style.fontSize = '20px';
        div1.appendChild(microTxt);
        div2.appendChild(microDiv);
        div3.appendChild(microDesc);
        micro.appendChild(div1);
        micro.appendChild(div2);
        micro.appendChild(div3);
        let microSpinner = document.createElement('div');
        microSpinner.setAttribute('class', 'spinner-ai');
        micro.appendChild(microSpinner);
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
        microVitDesc.style.fontSize = '20px';
        div1.appendChild(microVitTxt);
        div2.appendChild(microVitDiv);
        div3.appendChild(microVitDesc);
        microVit.appendChild(div1);
        microVit.appendChild(div2);
        microVit.appendChild(div3);
        let microVitSpinner = document.createElement('div');
        microVitSpinner.setAttribute('class', 'spinner-ai');
        microVit.appendChild(microVitSpinner);
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
        macroDesc.style.fontSize = '20px';
        div1.appendChild(macroTxt);
        div2.appendChild(macroDiv);
        div3.appendChild(macroDesc);
        macro.appendChild(div1);
        macro.appendChild(div2);
        macro.appendChild(div3);
        let macroSpinner = document.createElement('div');
        macroSpinner.setAttribute('class', 'spinner-ai');
        macro.appendChild(macroSpinner);
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
        ifDesc.style.fontSize = '20px';
        div1.appendChild(ifTxt);
        div2.appendChild(ifDiv);
        div3.appendChild(ifDesc);
        If.appendChild(div1);
        If.appendChild(div2);
        If.appendChild(div3);
        let ifSpinner = document.createElement('div');
        ifSpinner.setAttribute('class', 'spinner-ai');
        If.appendChild(ifSpinner);
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
        calDesc.style.fontSize = '20px';
        div1.appendChild(calTxt);
        div2.appendChild(calDiv);
        div3.appendChild(calDesc);
        Cal.appendChild(div1);
        Cal.appendChild(div2);
        Cal.appendChild(div3);
        let calSpinner = document.createElement('div');
        calSpinner.setAttribute('class', 'spinner-ai');
        Cal.appendChild(calSpinner);
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
        mealDesc.style.fontSize = '20px';
        div1.appendChild(mealDesc);
        Meal.appendChild(div1);
        let mealSpinner = document.createElement('div');
        mealSpinner.setAttribute('class', 'spinner-ai');
        Meal.appendChild(mealSpinner);
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
            addUsersuggestionContent(clientData.meal, mealDesc, mealBtn, 'desc', 1);
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
        mDiv.appendChild(emailP);
        mDiv.appendChild(genderP);
        mDiv.appendChild(goalP);
        mDiv.appendChild(idP);
        mDiv.appendChild(campaignP);
        mDiv.appendChild(mealText);
        mDiv.appendChild(nutritionText);
        mDiv.appendChild(stressP);
        mDiv.appendChild(sleepP);
        mDiv.appendChild(waterP);
        mDiv.appendChild(ageP);
        mDiv.appendChild(weightP);
        mDiv.appendChild(heightP);
        mDiv.appendChild(sugarP);
        mDiv.appendChild(workoutP);
        mDiv.appendChild(alcoholP);

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



function parseHTMLText(text) {

    const segments   = [];
    let currentIndex = 0;
    const regex = /<b>([\s\S]*?)<\/b>|<br>/gi;
    let match;

    while ((match = regex.exec(text)) !== null) {
        if (match.index > currentIndex) {
            // Add regular text before the tag
            segments.push({
                text: text.substring(currentIndex, match.index).trim(),
                bold: false
            });
        }

        if (match[0].startsWith('<b>')) {
            // Add bold text
            segments.push({
                text: match[1].trim(),
                bold: true
            });
        } else if (match[0] === '<br>') {
            // Add line break
            segments.push({
                text: '\n',
                bold: false
            });
        }

        currentIndex = match.index + match[0].length;
    }

    // Add remaining text after the last tag
    if (currentIndex < text.length) {
        segments.push({
            text: text.substring(currentIndex).trim(),
            bold: false
        });
    }

    return segments;
}

// Function to draw text with maxWidth and handle line breaks
function drawText(doc, segments, settings) {
    
    let maxWidth    = settings.maxWidth; 
    let lineHeight  = settings.lineHeight;
    let currentY    = settings.y;
    let currentX    = settings.x;
    let line        = '';
    let segmentLine = '';
    let newLine     = false;
 
    for(kk = 0; kk < segments.length; kk++) {
        // check if this is just empty text
        if(segments[kk].text == ''){
            continue;
        }
        // check if segment is just a new line
        if(segments[kk].text === '\n'){
            // flush
            doc.text(segmentLine, currentX, currentY);
            segmentLine = '';
            line = '';
            currentX  = settings.x;
            currentY += lineHeight;
            continue;
        }
        // search \n inside a segment and remove it.
        if(segments[kk].text.search(/\n/)) {
            segments[kk].text.replace('\n', '');
        }
        // Set font based on the segment bold flag
        if (segments[kk].bold) {
            doc.setFont('helvetica', 'bold');
        } else {
            doc.setFont('helvetica', 'normal');
        }
        // handling the total line width
        segmentLine = segments[kk].text;
        if(doc.getTextWidth(segmentLine) + currentX > maxWidth ) {
            while(doc.getTextWidth(segmentLine) + currentX > maxWidth) {
                r = Math.round(maxWidth / (doc.getTextWidth(segmentLine) + currentX) * 
                                                            segmentLine.length); 
                r = segmentLine.substring(0, r).lastIndexOf(' ') + 1;                                           
                line = segmentLine.substring(0, r);
                doc.text(line, currentX, currentY);
                segmentLine = segmentLine.substring(r);
                currentX  = settings.x;
                currentY += lineHeight;
            }
            newLine = true;
            line = segmentLine;
        } else {
            line = segmentLine;
            newLine = false;
        }

        doc.text(line, currentX, currentY);
        if(newLine){
            line = '';
        } else {
            currentX  += doc.getTextWidth(line) + 1;
            line = '';
            segmentLine = '';
        }
        if(currentY > 500){
            doc.addPage();
            currentX  = settings.x;
            currentY  = 50;
        }
    };
}


function printInitialInfo(doc, text2write, element, x, y){
                      
    if(element == ''){
        doc.setFontSize(22); // assume it is a title
    } else {
        doc.setFontSize(10);
    }
    doc.setTextColor('#000000');
    doc.text(text2write, x, y); 
    textWidth = Math.ceil(x + doc.getTextWidth(text2write));                     
    
    if(element != '') {
        doc.setFontSize(20);
        doc.setTextColor(element.style.color);
        doc.text(element.innerHTML, textWidth, y); 
    }
}


function createPdf(data) {

    const { jsPDF } = window.jspdf;
    
    const canvasImgBmi        = document.getElementById('Bmi').toDataURL('image/png', 1.0);
    const canvasImgMicroTrace = document.getElementById('Micro').toDataURL('image/png', 1.0);
    const canvasImgMicroVit   = document.getElementById('Micro_vit').toDataURL('image/png', 1.0);
    const canvasImgMacro      = document.getElementById('Macro').toDataURL('image/png', 1.0);
    const canvasImgIf         = document.getElementById('IntermittentFasting').toDataURL('image/png', 1.0);
    const canvasImgCal        = document.getElementById('Calories').toDataURL('image/png', 1.0);
    let nameP                 = document.getElementById('mDivpName');
    let goalP                 = document.getElementById('mDivpGoal');
    let idP                   = document.getElementById('mDivpId');
    let stressP               = document.getElementById('mDivpStress');
    let sleepP                = document.getElementById('mDivpSleep');
    let ageP                  = document.getElementById('mDivpAge');
    let sugarP                = document.getElementById('mDivpSugar');
    let alcoholP              = document.getElementById('mDivpAlcohol');
    let workoutP              = document.getElementById('mDivpWorkout');
    let weightP               = document.getElementById('mDivpWeight');
    let heightP               = document.getElementById('mDivpHeight');
    let emailP                = document.getElementById('mDivpEmail');
    let waterP                = document.getElementById('mDivpWater');


    var pdf = new jsPDF({
                         orientation: 'p',
                         unit: 'px',
                         format: 'letter',
                         putOnlyUsedFonts:true
                         });

    // name 
    text2write = 'Hey there, ';
    printInitialInfo(pdf, text2write, nameP, 50, 50);
    // goal
    text2write = 'Your goal: ';
    printInitialInfo(pdf, text2write, goalP, 50, 70);
    // ID
    text2write = 'Your ID: ';
    printInitialInfo(pdf, text2write, idP, 50, 90);
    // Email
    text2write = 'Your email: ';
    printInitialInfo(pdf, text2write, emailP, 50, 110);
    // weight
    text2write = 'Your Weight: ';
    printInitialInfo(pdf, text2write, weightP, 50, 130);
    // height
    text2write = 'Your Height: ';
    printInitialInfo(pdf, text2write, heightP, 50, 150);
    // age
    text2write = 'Your Age: ';
    printInitialInfo(pdf, text2write, ageP, 50, 170);
    // stress
    text2write = 'Your stress level: ';
    printInitialInfo(pdf, text2write, stressP, 50, 190);
    // sleep
    text2write = 'Your sleep: ';
    printInitialInfo(pdf, text2write, sleepP, 50, 210); 
    // sugar
    text2write = 'Your sugar intake: ';
    printInitialInfo(pdf, text2write, sugarP, 50, 230); 
    // water
    text2write = 'Your water intake: ';
    printInitialInfo(pdf, text2write, waterP, 50, 250); 
    // workout
    text2write = 'Your workout duration: ';
    printInitialInfo(pdf, text2write, workoutP, 50, 270); 
    // alcohol
    text2write = 'Your alcohol consumption: ';
    printInitialInfo(pdf, text2write, alcoholP, 50, 290); 
    // seperator
    pdf.line(50, 310, 400, 310, 'S');
    // BMR value
    text2write = 'Your basal metabolic rate (BMR) kcal / day: ';
    bmrElement = {innerHTML: String(Math.floor(data.bmr['val']*100)/100), 
                                        style: {color: '#FFA500'}};
    printInitialInfo(pdf, text2write, bmrElement, 50, 330); 

    pdf.setFontSize(10);
    pdf.setTextColor('#000000');
    drawTextSetting = {x: 50, y: 350, maxWidth: 350, lineHeight: 15};
    segments = parseHTMLText(data.bmr['desc'][0]);
    drawText(pdf, segments, drawTextSetting);

    pdf.addPage();

    // BMI title / plot / description
    text2write = 'BMI';
    printInitialInfo(pdf, text2write, '', 50, 50); 
    // BMI value
    text2write = 'Your body mass index (BMI): ';
    bmiElement = {innerHTML: String(Math.floor(data.bmi['val']*100)/100), 
                                            style: {color: '#2E8B57'}};
    printInitialInfo(pdf, text2write, bmiElement, 50, 70); 
    
    pdf.addImage(canvasImgBmi, 'JPEG', 80, 90, 300, 220, 'alias1', 'NONE');
    pdf.setFontSize(10);
    pdf.setTextColor('#000000');
    drawTextSetting = {x: 50, y: 330, maxWidth: 350, lineHeight: 15};
    segments = parseHTMLText(data.bmi['desc'][0]);
    drawText(pdf, segments, drawTextSetting);

    // page reset
    pdf.addPage();
    // IF title / plot / description
    text2write = 'Intermittent fasting recommendation';
    printInitialInfo(pdf, text2write, '', 50, 50); 
    pdf.addImage(canvasImgIf, 'JPEG', 80, 70, 300, 160, 'alias2', 'NONE');

    text2write = 'Fasting window: ';
    proteinP = {innerHTML: String(Math.floor(data.if['val'][0][0]*100)/100) + 'hr', 
    style: {color: 'dodgerblue'}};
    printInitialInfo(pdf, text2write, proteinP, 50, 280); 

    text2write = 'Eating window: ';
    fiberP = {innerHTML: String(Math.floor(data.if['val'][1][0]*100)/100) + 'hr', 
    style: {color: 'mediumseagreen'}};
    printInitialInfo(pdf, text2write, fiberP, 50, 300); 

    pdf.setFontSize(10);
    pdf.setTextColor('#000000');
    drawTextSetting = {x: 50, y: 320, maxWidth: 350, lineHeight: 15};
    segments = parseHTMLText(data.if['desc'][0]);
    drawText(pdf, segments, drawTextSetting);
   

    // page reset
    pdf.addPage();
    // MacroNutrient title / plot / description
    text2write = 'Macro nutrients recommendations ';
    printInitialInfo(pdf, text2write, '', 50, 50); 
    pdf.addImage(canvasImgMacro, 'JPEG', 80, 70, 300, 220, 'alias3', 'NONE');
    
    text2write = 'Fat: ';
    fatP = {innerHTML: String(Math.floor(data.macro['val'][0]*100)/100) + 'gr', 
    style: {color: 'coral'}};
    printInitialInfo(pdf, text2write, fatP, 50, 320); 

    text2write = 'Carbs: ';
    carbP = {innerHTML: String(Math.floor(data.macro['val'][1]*100)/100) + 'gr', 
    style: {color: 'lightblue'}};
    printInitialInfo(pdf, text2write, carbP, 50, 340); 

    text2write = 'Protein: ';
    proteinP = {innerHTML: String(Math.floor(data.macro['val'][2]*100)/100)+ 'gr', 
    style: {color: 'limegreen'}};
    printInitialInfo(pdf, text2write, proteinP, 50, 360); 

    text2write = 'Fiber: ';
    fiberP = {innerHTML: String(Math.floor(data.macro['val'][3]*100)/100)+ 'gr', 
    style: {color: 'cyan'}};
    printInitialInfo(pdf, text2write, fiberP, 50, 380); 

    pdf.setFontSize(10);
    pdf.setTextColor('#000000');
    drawTextSetting = {x: 50, y: 420, maxWidth: 350, lineHeight: 15};
    segments = parseHTMLText(data.macro['desc'][0]);
    drawText(pdf, segments, drawTextSetting);


    // page reset
    pdf.addPage();
    // MicroNutrient title / plot / description
    tColors = ['coral','lightblue','limegreen','cyan','blue','green','orange',
    'magenta','black','DeepSkyBlue','MediumPurple','MistyRose','PaleGoldenRod',
    'Peru','Sienna'];
    text2write = 'Micronutrients (Trace minerals) recommendation ';
    printInitialInfo(pdf, text2write, '', 50, 50); 
    pdf.addImage(canvasImgMicroTrace, 'JPEG', 80, 70, 300, 220, 'alias4', 'NONE');
    cc = 0;
    for(kk = 0; kk < data.micro['val'].tValues.length; kk++) {
        if(320 + 20*kk > 500){
            XIndex = 250;
            YIndex = 320 + 20*cc;
            cc++;
        } else {
            XIndex = 50;
            YIndex = 320 + 20*kk;
        }
        text2write = data.micro['val'].tNames[kk] + ': ';
        elementM = {innerHTML: String(Math.floor(data.micro['val'].tValues[kk] * 
                data.micro['val'].tScale[kk]*100)/100) + data.micro['val'].tUnits[kk], 
                style: {color: tColors[kk]}};
        printInitialInfo(pdf, text2write, elementM, XIndex, YIndex);
    } 

    pdf.setFontSize(10);
    pdf.setTextColor('#000000');
    drawTextSetting = {x: 50, y: 320 + 20*kk, maxWidth: 350, lineHeight: 15};
    segments = parseHTMLText(data.micro['descTrace'][0]);
    drawText(pdf, segments, drawTextSetting);

 
    // page reset
    pdf.addPage();
    // MicroNutrient vitamins title / plot / description
    vColors = ['coral','lightblue','limegreen','cyan','blue','green','orange',
    'magenta','black','DeepSkyBlue','MediumPurple','MistyRose','PaleGoldenRod',
    'Peru','Sienna'];
    text2write = 'Micronutrients (Vitamins) recommendation ';
    printInitialInfo(pdf, text2write, '', 50, 50); 
    pdf.addImage(canvasImgMicroVit, 'JPEG', 80, 70, 300, 220, 'alias5', 'NONE');
    cc = 0;
    for(kk = 0; kk < data.micro['val'].vValues.length; kk++) {
       if(320 + 20*kk > 500){
           XIndex = 250;
           YIndex = 320 + 20*cc;
           cc++;
       } else {
           XIndex = 50;
           YIndex = 320 + 20*kk;
       }
       text2write = data.micro['val'].vNames[kk] + ': ';
       elementM = {innerHTML: String(Math.floor(data.micro['val'].vValues[kk] * 
               data.micro['val'].vScale[kk]*100)/100) + data.micro['val'].vUnits[kk], 
               style: {color: vColors[kk]}};
       printInitialInfo(pdf, text2write, elementM, XIndex, YIndex);
   } 

    pdf.addPage();
    pdf.setFontSize(10);
    pdf.setTextColor('#000000');
    drawTextSetting = {x: 50, y: 50, maxWidth: 350, lineHeight: 15};
    segments = parseHTMLText(data.micro['descVit'][0]);
    drawText(pdf, segments, drawTextSetting);

    // page reset
    pdf.addPage();
    // MicroNutrient title / plot / description
    text2write = 'Calories intake recommendation';
    printInitialInfo(pdf, text2write, '', 50, 50); 
    pdf.addImage(canvasImgCal, 'JPEG', 80, 70, 300, 170, 'alias6', 'NONE');
    pdf.setFontSize(10);
    pdf.setTextColor('#000000');
    drawTextSetting = {x: 50, y: 280, maxWidth: 350, lineHeight: 15};
    segments = parseHTMLText(data.cal['desc'][0]);
    drawText(pdf, segments, drawTextSetting);

   
    // page reset
    // meal plan
    pdf.addPage();
    text2write = 'Meal plan';
    printInitialInfo(pdf, text2write, '', 50, 50); 
    pdf.setFontSize(10);
    pdf.setTextColor('#000000');
    drawTextSetting = {x: 50, y: 70, maxWidth: 350, lineHeight: 15};
    segments = parseHTMLText(data.meal['desc'][0]);
    drawText(pdf, segments, drawTextSetting);


    const currentDate = new Date().toDateString();
    pdf.save('progress report for ' + nameP.innerHTML + ' clientId ' + 
                                    idP.innerHTML + ' ' + currentDate + '.pdf');
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


function addUsersuggestionContent(data, desc, Btn, descField, mealFlag = 0) {
    
    if(Btn.innerHTML == 'Edit'){
        let userIn = document.createElement('textarea');
        let savedText = desc.parentNode.children[0];
        savedText.style.position = 'absolute';
        savedText.style.opacity = 0;
        userIn.innerHTML = desc.innerHTML;
        if(mealFlag) {
            userIn.setAttribute('rows', '80');
        }
        else {
            userIn.setAttribute('rows', '40');
        }
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
        if (this.readyState == 4 && this.status == 200) {
        }
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

function FinanceOpenTab(tabName) {
    var i, tabcontent, tablinks;
    // Hide all tab content
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].classList.remove("active");
    }

    // Remove active class from all tabs
    tablinks = document.getElementsByClassName("financeTab")[0].children;
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].classList.remove("active-tab");
    }
    // Show the current tab content and set the active tab
    document.getElementById(tabName).classList.add("active");
    document.getElementById(tabName + 'Tab').classList.add("active-tab");
    document.getElementById(tabName + 'Tab').style.borderRadius = '30px';
    document.getElementsByClassName('mainFormDiv')[0].remove();
    // dynamic search invoices
    // enter keypress also works for navigation
    counter = 0; // reset the counter
    Questions = [];
    createFormDiv(tabName);
    let input = document.querySelectorAll('.form-input');
    let header = document.querySelectorAll('.form-header');
    let headerTxt = document.querySelectorAll('.form-header-style');
    let userTxt = document.querySelector('.user-text');
    let welcomeTxt = document.querySelector('.navbar-brand');
    getUserInfo(userTxt, welcomeTxt);
    if(tabName == 'trackInvoice') {
        questionCreate(headerTxt[1], header[1], input[1], 'financesSearch', userPage);
        const moveright = document.querySelector('.form-go-right');
        moveRight(moveright, input, header, headerTxt, Questions, 'financesSearch');
        const moveleft = document.querySelector('.form-go-left');
        moveLeft(moveleft, input, header, headerTxt, Questions, 'financesSearch');
        input[1].addEventListener('keydown', function(s) {
            if(s.key == 'Backspace'){
                if(gSearchInvoices.length > 0) {
                    gSearchInvoices = gSearchInvoices.slice(0, gSearchInvoices.length - 1);
                } else if(gSearchInvoices.length == 0) {
                    gSearchInvoices = gSearchInvoices;
                }
            } else {
                gSearchInvoices += s.key; 
            }
            let searchInvStruct = {'searchStr': gSearchInvoices, 'key': Questions[0].qAnswer};
            searchInvoices(searchInvStruct, Questions[0].userId);
        });
        setTimeout(function() {searchInvoices([], Questions[0].userId);}, 500);
    } else if(tabName == 'createInvoice') {
        questionCreate(headerTxt[1], header[1], input[1], 'finances', userPage);
        const moveright = document.querySelector('.form-go-right');
        moveRight(moveright, input, header, headerTxt, Questions, 'finances');
        const moveleft = document.querySelector('.form-go-left');
        moveLeft(moveleft, input, header, headerTxt, Questions, 'finances');
    } 
}

function createFormDiv(tab) {
    // Create the main div
    parentNode = document.getElementById(tab);
    const mainDiv = document.createElement('div');
    mainDiv.className = 'mainFormDiv';
    // Create the form
    const form = document.createElement('form');
    form.className = 'form-class';

    // Create the navigation buttons container
    const navButtonsDiv = document.createElement('div');
    navButtonsDiv.className = 'd-flex form-class';

    // Create the left swipe button
    const leftButtonDiv = document.createElement('div');
    leftButtonDiv.className = 'd-flex';
    const leftButton = document.createElement('button');
    leftButton.type = 'button';
    leftButton.className = 'form-go-left';
    leftButton.disabled = true;
    leftButton.style.opacity = 0;
    const leftButtonText = document.createElement('span');
    leftButtonText.style.fontSize = '26px';
    leftButtonText.style.margin = '15px';
    leftButtonText.textContent = 'Back';
    leftButton.appendChild(leftButtonText);
    leftButtonDiv.appendChild(leftButton);

    // Create the right swipe button
    const rightButtonDiv = document.createElement('div');
    rightButtonDiv.className = 'd-flex';
    const rightButton = document.createElement('button');
    rightButton.type = 'button';
    rightButton.className = 'form-go-right';
    rightButton.disabled = false;
    rightButton.style.opacity = 1; 
    const rightButtonText = document.createElement('span');
    rightButtonText.style.fontSize = '26px';
    rightButtonText.style.margin = '15px';
    rightButtonText.textContent = 'Next';
    rightButton.appendChild(rightButtonText);
    rightButtonDiv.appendChild(rightButton);

    // Append buttons to the nav buttons container
    navButtonsDiv.appendChild(leftButtonDiv);
    navButtonsDiv.appendChild(rightButtonDiv);

    // Create the form header section
    const formHeaderDiv = document.createElement('div');
    formHeaderDiv.className = 'd-flex form-header-parent';

    for (let i = 0; i < 3; i++) {
        const headerP = document.createElement('p');
        headerP.className = 'form-header-style';
        const headerDiv = document.createElement('div');
        headerDiv.className = 'form-header';
        headerDiv.setAttribute('serverStruct', 0);
        if(i == 1){
            headerDiv.style.opacity = 1;
            headerDiv.addEventListener('transitionend', function(event) {
                headerP.innerHTML = Questions[counter].qContent[event.target.getAttribute('serverStruct')];
                ChangeForm(event.target, '0.0s', '0', 1, '50%');
            });
        } else {
            headerDiv.style.width = '0%';
            headerDiv.addEventListener('transitionend', function(event) {
                ChangeForm(event.target, '0.0s', '0', 0, '0%');
            });
        }
        headerDiv.appendChild(headerP);
        formHeaderDiv.appendChild(headerDiv);
    }

    // Create the form input section
    const formInputDiv = document.createElement('div');
    formInputDiv.className = 'd-flex form-input-parent';

    for (let i = 0; i < 3; i++) {
        const inputDiv = document.createElement('div');
        inputDiv.className = 'form-input';
        inputDiv.setAttribute('serverStruct', 0);
        inputDiv.setAttribute('serverStructOption', 0);
        if(i == 1) {
            inputDiv.style.opacity = 1;
            inputDiv.addEventListener('transitionend', function(event) {
                resetFormType(event.target);
                setFormType(event.target, Questions[counter], event.target.getAttribute('serverStruct'), event.target.getAttribute('serverStructOption'), '');
                ChangeForm(event.target, '0s', '0', 1, '50%');
                restorePrevAnswer(event.target.getAttribute('serverStruct'), event.target.getAttribute('serverStructOption'));
            });
            // enter keypress also works for navigation
            inputDiv.addEventListener('keypress', function(s) {
                if (s.key == "Enter") {
                   s.preventDefault();
                }
            });
        } else {
            inputDiv.addEventListener('transitionend', function(event) {
                ChangeForm(event.target, '0.0s', '0', 0, '0%');
                resetFormType(event.target);
            });
            inputDiv.style.width = '0%';
        }
        formInputDiv.appendChild(inputDiv);
    }

    // Append all sections to the form
    form.appendChild(navButtonsDiv);
    form.appendChild(formHeaderDiv);
    form.appendChild(formInputDiv);

    // Append the form to the main div
    mainDiv.appendChild(form);

    // Optionally, append the main div to the body or another container
    if(tab == 'trackInvoice'){
        referenceNode = document.getElementsByClassName('insertForm');
    } else {
        referenceNode = document.getElementsByClassName('invoiceOutput');
    }
    parentNode.insertBefore(mainDiv, referenceNode[0]);

}

// chat page
function chatPageSetup(){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            user = JSON.parse(this.response);
            //let username = user.username;
            let userid = user.userid;
            chatButton = document.querySelector('.send-button');
            if(chatButton) {
                chatButton.addEventListener('click', function() {
                    // Get the message from the text area
                    sendChatContent(userid, chatArea);
                    renderInTheChatBox(chatArea);
                });
            }
            let chatText = document.querySelector('.message-input');
            if(chatText){
                chatText.addEventListener('input', function(){
                    this.style.height = 'auto';
                    // Set the height according to the scrollHeight of the content
                    this.style.height = (this.scrollHeight) + 'px';
                })
            }
            generateClientCircles(userid);
            let chatEnv = document.getElementsByClassName('tab');
            if(chatEnv) {
                for(kk = 0; kk < chatEnv.length; kk++) {
                    chatEnv[kk].addEventListener('click', function(e) {
                        for(kx = 0; kx < chatEnv.length; kx++){
                            chatEnv[kx].style.backgroundColor = '#f1f1f1';
                            chatEnv[kx].style.fontWeight = 'normal';
                            chatEnv[kx].style.color = 'black';
                        }
                        e.target.style.backgroundColor = '#6ca0f3';
                        e.target.style.color = 'white';
                        e.target.style.fontWeight = 'bold';
                        chatArea = e.target.attributes.alt.value;
                        generateClientCircles(userid);
                    });
                    chatEnv[kk].addEventListener('mouseenter', function(e) {
                        e.target.style.color = '#6ca0f3';
                        e.target.style.fontWeight = 'bold';
                    });
                    chatEnv[kk].addEventListener('mouseleave', function(e) {
                        e.target.style.color = 'black';
                        e.target.style.fontWeight = 'normal';
                    });
                }
            }
        }
    };
    // sending the request
    xmlhttp.open("POST", "assets/php/admin.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    let address = '';
    let request = 'address=' + address;
    xmlhttp.send(request);
}

function sendChatContent(uId, chatArea) {
    
    const message = document.querySelector('.message-input').value;
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            data = JSON.parse(this.response);
        }
    };
    // sending the request
    xmlhttp.open("POST", "assets/php/chat.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    let info = {'flag': 'send', 'userId': uId, 'clientPhoneNumber': selectedPhoneNumber, 'clientTelegramUserName': clientTelegramUserName, 'chatArea': chatArea, 'message': message};
    var userdata = "userInfo="+JSON.stringify(info);
    xmlhttp.send(userdata);
}

function renderInTheChatBox(chatEnv){

    if(chatEnv == ''){
        window.alert('please choose you chat environment.');
    } else {
        const message = document.querySelector('.message-input').value;
        const chatContent = document.querySelector('.chat-content');
        const messageBox = document.createElement('div');
        messageBox.classList.add('message-box-NutriAi');
        // Set the message text
        messageBox.textContent = message;
        messageBox.style.width = message.length * 20 * 0.7 + 'px';
        // Append the messageBox as a child to the chat-content div
        chatContent.appendChild(messageBox);
        // Scroll to the bottom automatically when a new message is added
        chatContent.scrollTop = chatContent.scrollHeight;
        // flush the input text
        document.querySelector('.message-input').value = '';
    }
}

function generateClientCircles(uId) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            let clients = JSON.parse(this.response);
            let userSlider = document.querySelector('.user-slider');
            for(kk = 0; kk < clients.names.length; kk++){
                if(clients.names[kk] == ''){
                    continue;
                } else {
                    let userCir  = document.createElement('div');
                    let upperTxt = document.createElement('p');
                    let lowerTxt = document.createElement('p');
                    let image    = document.createElement('img');
                    lowerTxt.setAttribute('alt', kk);
                    upperTxt.setAttribute('alt', kk);
                    image.setAttribute('alt', kk);
                    userCir.setAttribute('alt', kk);
                    if(clients.genders[kk] == '1') {
                        image.setAttribute('src', 'assets/img/woman.png');
                    } else {
                        image.setAttribute('src', 'assets/img/man.png');
                    }
                    userCir.classList.add('user-circle');
                    if(clients.telegramNewChats[kk] == '1') {
                        userCir.style.border = '5px solid purple';
                        userCir.style.boxShadow =  '0 0 10px rgba(0, 0, 0, 0.2)';  
                        userCir.style.animation = 'changeColor 2s infinite';
                    } 
                    
                    userCir.addEventListener('mouseenter',function(e) {
                        userCir.style.boxShadow = '0 5px 5px rgba(0, 0, 0, 0.5)';
                    });
                    userCir.addEventListener('mouseleave',function(e) {
                        userCir.style.boxShadow = '';
                    });
                    userCir.addEventListener('click',function(e) {
                        if(chatArea == ''){
                            window.alert('please select the chat App: Telegram or Zalo.');
                        } else {
                            allCir = document.getElementsByClassName('user-circle');
                            for(kx = 0; kx < allCir.length; kx++){
                                allCir[kx].style.border = '';
                            }
                            userCir.style.boxShadow = '';
                            userCir.style.border = 'solid 3px lightblue';
                            if(clients.phoneNumbers[e.target.attributes.alt.value] == '' && clients.telegramUserNames[e.target.attributes.alt.value] == '') {
                                createChatPopUp(uId, clients.ids[e.target.attributes.alt.value]);
                            } else if(clients.phoneNumbers[e.target.attributes.alt.value] == '' && chatArea == '1') {
                                createChatPopUp(uId, clients.ids[e.target.attributes.alt.value]);
                            } else if(clients.telegramUserNames[e.target.attributes.alt.value] == '' && chatArea == '0') {
                                createChatPopUp(uId, clients.ids[e.target.attributes.alt.value]);
                            } else if(chatArea == '0') {
                                clientTelegramUserName = clients.telegramUserNames[e.target.attributes.alt.value];
                                pullTelegramChatContent(uId, clientTelegramUserName);
                            } else if(chatArea == '1') {
                                selectedPhoneNumber = clients.phoneNumbers[e.target.attributes.alt.value];
                                pullZaloChatContent(uId, selectedPhoneNumber);
                            } 
                        }
                    });
                    
                    upperTxt.classList.add('top-text');
                    upperTxt.innerHTML = clients.names[kk];
                    lowerTxt.classList.add('bottom-text');
                    lowerTxt.innerHTML = clients.ids[kk];
                    userCir.appendChild(upperTxt);
                    userCir.appendChild(image);
                    userCir.appendChild(lowerTxt);
                    userSlider.appendChild(userCir);
                }
            }
        }
    };
    // sending the request
    xmlhttp.open("POST", "assets/php/chat.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    let info = {'flag': 'getClients', 'userId': uId};
    var userdata = "userInfo="+JSON.stringify(info);
    xmlhttp.send(userdata);
}


function pullTelegramChatContent(uId, telegramUserName) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            //let chatContent = JSON.parse(this.response);
            let chatContent = this.response;
            const pattern = /(NutriAi:.*?)(?=\\n|$)|(user:.*?)(?=\\n|$)/g;
            // Use the pattern to match segments and return them as an array
            const segments = chatContent.match(pattern) || [];
            let message = '';
            let boxClass = '';
            // Process segments and categorize them
            segments.forEach(segment => {
                const trimmedSegment = segment.trim();
                if (trimmedSegment.startsWith("NutriAi:")) {
                    message = trimmedSegment.replace(/^NutriAi:\s*/, '').replace(/\\/, '');
                    boxClass = 'message-box-NutriAi';
                } else if (trimmedSegment.startsWith("user:")) {
                    message = trimmedSegment.replace(/^user:\s*/, '').replace(/\\/, '');
                    boxClass = 'message-box-user';
                }
                const chatContent = document.querySelector('.chat-content');
                const messageBox = document.createElement('div');
                messageBox.classList.add(boxClass);
                // Set the message text
                messageBox.textContent = message;
                messageBox.style.width = message.length * 20 * 0.7 + 'px';
                // Append the messageBox as a child to the chat-content div
                chatContent.appendChild(messageBox);
                // Scroll to the bottom automatically when a new message is added
                chatContent.scrollTop = chatContent.scrollHeight;
            });
            
        }
    };
    // sending the request
    xmlhttp.open("POST", "assets/php/chat.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    let info = {'flag': 'getChatFile', 'userId': uId, 'telegramUserName': telegramUserName};
    var userdata = "userInfo="+JSON.stringify(info);
    xmlhttp.send(userdata);
}


function createChatPopUp(uId, cId){
    document.getElementById('phoneModal').style.display = 'block';
    document.getElementById('connectPhone').disabled = false;
    document.getElementById('connectPhone').addEventListener('click', function() {
        cPhone = document.getElementsByClassName('phoneInput')[0].value;
        selectedPhoneNumber = cPhone;
        addClientPhoneNumber(uId, cId, cPhone);
    });
    document.getElementById('connectTelegramUserName').disabled = false;
    document.getElementById('connectTelegramUserName').addEventListener('click', function() {
        tUserName = document.getElementsByClassName('telegramInput')[0].value;
        clientTelegramUserName = tUserName;
        addClientsTelegramUserName(uId, cId, tUserName);
    });
    document.getElementById('closeButton').addEventListener('click', function() {
        document.getElementById('phoneModal').style.display = 'none';
    });  
}


function addClientPhoneNumber(uId, cId, cPhone){

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            if(JSON.parse(this.response) == true) {
                window.alert('Phone number added successfully.');
                document.getElementById('connectPhone').disabled = true;
            }
        }
    };
    // sending the request
    xmlhttp.open("POST", "assets/php/chat.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    let info = {'flag': 'addClientsNumber', 'userId': uId, 'clientId': cId, 'phoneNumber': cPhone};
    var userdata = "userInfo="+JSON.stringify(info);
    xmlhttp.send(userdata);
}

function addClientsTelegramUserName(uId, cId, telegramUserName){

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            if(JSON.parse(this.response) == true) {
                window.alert('Telegram username added successfully.');
                document.getElementById('connectTelegramUserName').disabled = true;
            }
        }
    };
    // sending the request
    xmlhttp.open("POST", "assets/php/chat.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    let info = {'flag': 'addClientsTelegramUserName', 'userId': uId, 'clientId': cId, 'telegramUserName': telegramUserName};
    var userdata = "userInfo="+JSON.stringify(info);
    xmlhttp.send(userdata);
}