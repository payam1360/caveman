/**
* Template Name: HeroBiz - v2.3.1
* Template URL: https://bootstrapmade.com/herobiz-bootstrap-business-template/
* Author: BootstrapMade.com
* License: https://bootstrapmade.com/license/
*/

// event delegate for click on the dynamic button elements


document.addEventListener('DOMContentLoaded', () => {
  "use strict";
    /* define the class for questions*/
    // create the questions
    let input = document.querySelectorAll('.form-input');
    let header = document.querySelectorAll('.form-header');
    let headerTxt = document.querySelectorAll('.form-header-style');
    let userTxt = document.querySelector('.user-text');
    getUserInfo(userTxt);
    // this function will be getting the info from the Admin Panel.
    // initialize the first question and init the load page
    resetStart(input, header, headerTxt, 'clients');
    
    const moveright = document.querySelector('.form-go-right');
    moveRight(moveright, input, header, headerTxt, Questions, 'clients');
    
    const moveleft = document.querySelector('.form-go-left');
    moveLeft(moveleft, input, header, headerTxt, Questions);
    
    const clients = document.querySelectorAll('.client-list');
    listClients(clients, Questions);
    
});




