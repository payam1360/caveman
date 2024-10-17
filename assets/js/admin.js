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
    
    getUserInfo();
    let container = document.querySelectorAll('.btn-admin');
    for(let kk = 0; kk < container.length; kk++) {
        container[kk].addEventListener('click',function(e) {
            if(kk == 0) {
                window.location.assign('adminQ.html');
            } else if(kk == 1) {
                window.location.assign('clients.html');
            } else if(kk == 2) {
                window.location.assign('finances.html');
            } else if(kk == 3) {
                window.location.assign('chat.html');
            } else if(kk == 4) {
                window.location.assign('post.html');
            }
        });
    }
});


