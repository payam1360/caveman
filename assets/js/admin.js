/*
// Scripts
*/
/**
* Template Name: HeroBiz - v2.3.1
* Template URL: https://bootstrapmade.com/herobiz-bootstrap-business-template/
* Author: BootstrapMade.com
* License: https://bootstrapmade.com/license/
*/

document.addEventListener('DOMContentLoaded', () => {
  "use strict";
    /* define the class for questions*/
    let userTxt = document.querySelector('.user-text');
    let welcomeTxt = document.querySelector('.navbar-brand');
    getUserInfo(userTxt, welcomeTxt);
    let container = document.querySelectorAll('.admin-dashbord');
    for(let kk = 0; kk < container.length; kk++) {
        container[kk].addEventListener('mouseenter',function(e) {
            e.target.children[1].style.transform = 'scale(1.3, 1.3)';
        });
        container[kk].addEventListener('mouseleave',function(e) {
            e.target.children[1].style.transform = 'scale(1, 1)';
        });
        container[kk].addEventListener('click',function(e) {
            if(kk == 0){
                window.location.assign('addClients.html');
            } else if(kk == 1) {
                window.location.assign('clients.html');
            }
        });
    }
});


