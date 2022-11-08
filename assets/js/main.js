/**
* Template Name: HeroBiz - v2.3.1
* Template URL: https://bootstrapmade.com/herobiz-bootstrap-business-template/
* Author: BootstrapMade.com
* License: https://bootstrapmade.com/license/
*/

document.addEventListener('DOMContentLoaded', () => {
  "use strict";
    /* define the class for questions*/
    let Questions = [];
    let counter = 0;
    let input = document.querySelectorAll('.form-input');
    // reset the question bar
    input[0].style.width = '0%';
    input[1].style.opacity = 1;
    input[2].style.width = '0%';
    let MAX_cnt = 3;
    class question {
        constructor(question, answer, Qidx){
            this.question = question; // must be a text string
            this.answer = answer;
            this.Qidx = Qidx;
            
        }
        pushData (){
            Questions.push(this);
        }
    };
    function questionCreate(){
        let Obj = new question('what is your goal?', '', 0);
        Obj.pushData(Obj);
        Obj = new question('what is your name?', '', 1);
        Obj.pushData(Obj);
        Obj = new question('what is your weight?', '', 2);
        Obj.pushData(Obj);
        Obj = new question('what is your height?', '', 3);
        Obj.pushData(Obj);
    }
    // create the questions
    questionCreate();

    
    //document.getElementsByClassName('form-header')[counter].innerHTML = Questions[0].question;
    //document.getElementById('answerId').value = '';
    
  /**
   * Preloader
   */
  const preloader = document.querySelector('#preloader');
  if (preloader) {
    window.addEventListener('load', () => {
      preloader.remove();
    });
  }

  /**
   * Sticky header on scroll
   */
  const selectHeader = document.querySelector('#header');
  if (selectHeader) {
    document.addEventListener('scroll', () => {
      window.scrollY > 100 ? selectHeader.classList.add('sticked') : selectHeader.classList.remove('sticked');
    });
  }

  /**
   * Navbar links active state on scroll
   */
  let navbarlinks = document.querySelectorAll('#navbar .scrollto');

  function navbarlinksActive() {
    navbarlinks.forEach(navbarlink => {

      if (!navbarlink.hash) return;

      let section = document.querySelector(navbarlink.hash);
      if (!section) return;

      let position = window.scrollY;
      if (navbarlink.hash != '#header') position += 200;

      if (position >= section.offsetTop && position <= (section.offsetTop + section.offsetHeight)) {
        navbarlink.classList.add('active');
      } else {
        navbarlink.classList.remove('active');
      }
    })
  }
  window.addEventListener('load', navbarlinksActive);
  document.addEventListener('scroll', navbarlinksActive);

  /**
   * Function to scroll to an element with top ofset
   */
  function scrollto(el) {
    const selectHeader = document.querySelector('#header');
    let offset = 0;

    if (selectHeader.classList.contains('sticked')) {
      offset = document.querySelector('#header.sticked').offsetHeight;
    } else if (selectHeader.hasAttribute('data-scrollto-offset')) {
      offset = selectHeader.offsetHeight - parseInt(selectHeader.getAttribute('data-scrollto-offset'));
    }
    window.scrollTo({
      top: document.querySelector(el).offsetTop - offset,
      behavior: 'smooth'
    });
  }

  /**
   * Fires the scrollto function on click to links .scrollto
   */
  let selectScrollto = document.querySelectorAll('.scrollto');
  selectScrollto.forEach(el => el.addEventListener('click', function(event) {
    if (document.querySelector(this.hash)) {
      event.preventDefault();

      let mobileNavActive = document.querySelector('.mobile-nav-active');
      if (mobileNavActive) {
        mobileNavActive.classList.remove('mobile-nav-active');

        let navbarToggle = document.querySelector('.mobile-nav-toggle');
        navbarToggle.classList.toggle('bi-list');
        navbarToggle.classList.toggle('bi-x');
      }
      scrollto(this.hash);
    }
  }));

  /**
   * Scroll with ofset on page load with hash links in the url
   */
  window.addEventListener('load', () => {
    if (window.location.hash) {
      if (document.querySelector(window.location.hash)) {
        scrollto(window.location.hash);
      }
    }
  });

  /**
   * Mobile nav toggle
   */
  const mobileNavToogle = document.querySelector('.mobile-nav-toggle');
  if (mobileNavToogle) {
    mobileNavToogle.addEventListener('click', function(event) {
      event.preventDefault();

      document.querySelector('body').classList.toggle('mobile-nav-active');

      this.classList.toggle('bi-list');
      this.classList.toggle('bi-x');
    });
  }


    // aux functions
    const moveright = document.querySelector('.form-go-right');
    if (moveright) {
        moveright.addEventListener('click', function(event) {
            
            //Questions[counter].answer = document.getElementById('answerId').value;
            let input = document.querySelectorAll('.form-input');
            let gap = [];
            gap[0] = input[1].getBoundingClientRect().left-input[0].getBoundingClientRect().left;
            gap[1] = input[2].getBoundingClientRect().left-input[1].getBoundingClientRect().left;
            
            input[0].style.transitionDuration = '0.5s';
            input[0].style.transform = ["translateX(" + gap[0].toString() + "px)"];
            input[0].style.opacity = 1;
            input[0].style.width = '40%';
            input[0].addEventListener('transitionend', () => {
                //Reset
                input[0].style.transitionDuration = '0.0s';
                input[0].style.transform = 'translateX(0px)';
                input[0].style.opacity = 0;
                input[0].style.width = '0%';
                
            });
            input[1].style.transitionDuration = '0.5s';
            input[1].style.transform = ["translateX(" + gap[1].toString() + "px)"];
            input[1].style.opacity = 0;
            input[1].style.width = '0%';
            input[1].addEventListener('transitionend', () => {
                //Reset
                input[1].style.transitionDuration = '0.0s';
                input[1].style.transform = 'translateX(0px)';
                input[1].style.opacity = 1;
                input[1].style.width = '40%';
                
            });
            
            
            counter++;
      });
    }
    const moveleft = document.querySelector('.form-go-left');
    if (moveleft) {
        moveleft.addEventListener('click', function(event) {
            //Questions[counter].answer = document.getElementById('answerId').value;
            let input = document.querySelectorAll('.form-input');
            let gap = [];
            gap[0] = input[1].getBoundingClientRect().right-input[2].getBoundingClientRect().right;
            gap[1] = input[0].getBoundingClientRect().right-input[1].getBoundingClientRect().right;
            input[2].style.transitionDuration = '0.5s';
            input[2].style.transform = ["translateX(" + gap[0].toString() + "px)"];
            input[2].style.opacity = 1;
            input[2].style.width = '40%';
            input[2].addEventListener('transitionend', () => {
                //Reset
                input[2].style.transitionDuration = '0.0s';
                input[2].style.transform = 'translateX(0px)';
                input[2].style.opacity = 0;
                input[2].style.width = '0%';
            });
            input[1].style.transitionDuration = '0.5s';
            input[1].style.transform = ["translateX(" + gap[1].toString() + "px)"];
            input[1].style.opacity = 0;
            input[1].style.width = '0%';
            input[1].addEventListener('transitionend', () => {
                //Reset
                input[1].style.transitionDuration = '0.0s';
                input[1].style.transform = 'translateX(0px)';
                input[1].style.opacity = 1;
                input[1].style.width = '40%';
            });
            counter--;
            
        });
    }
    
    
});



