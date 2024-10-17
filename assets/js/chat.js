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
      getUserInfo();
      // this function will be getting the info from the Admin Panel.
      // initialize the first question and init the load page
      setTimeout(function() {chatPageSetup();}, 1000);
  });
  