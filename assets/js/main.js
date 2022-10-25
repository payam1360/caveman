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
    class question {
        constructor(question, Qidx){
            this.question = question; // must be a text string
            this.Qidx = Qidx;
        }
        pushData (){
            Questions.push(this);
        }
    };
    function questionCreate(){
        let Obj = new question('what is your goal?', 0);
        Obj.pushData(Obj);
        Obj = new question('what is your name?', 1);
        Obj.pushData(Obj);
    }
    questionCreate();
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
            document.getElementsByClassName('form-input')[0].style.transform = "translateX(50%)";
            document.getElementsByClassName('form-input')[0].style.opacity = 0;
            document.getElementsByClassName('form-header')[0].style.transform = "translateX(50%)";
            document.getElementsByClassName('form-header')[0].style.opacity = 0;
            console.log(Questions[0].question);
            // new form created
 /*           let HeaderDiv = document.getElementsByClassName('form-header-center')[0];
            let newP = document.createElement("p");
            newP.setAttribute("id", "form-header-id");
            HeaderDiv.appendChild(newP);
            
            let InputDiv = document.getElementsByClassName('form-input-center')[0];
            let newIn = document.createElement("input");
            newIn.setAttribute("type", "text");
            newIn.setAttribute("class", "col-xs-6 col-sm-6 col-md-6 col-lg-6 form-input-style");
            InputDiv.appendChild(newIn); */
      });
    }

    const moveleft = document.querySelector('.form-go-left');
    if (moveleft) {
        moveleft.addEventListener('click', function(event) {
            document.getElementsByClassName('form-input-center')[0].style.transform = "translateX(-50%)";
            document.getElementsByClassName('form-input-center')[0].style.opacity = 0;
            document.getElementsByClassName('form-header-center')[0].style.transform = "translateX(-50%)";
            document.getElementsByClassName('form-header-center')[0].style.opacity = 0;
            
        });
    }
    

    /*      let newFormDiv = document.createElement('div');
          newFormDiv.classList.add('justify-content-center d-flex');
          let newFormIn = document.createElement("input");
          newFormIn.setAttribute("type", "text");
          newFormIn.classList.add('col-xs-6 col-sm-6 col-md-6 col-lg-6 form-input-style');
          newFormDiv.appendChild(newFormIn);
          let newFormParent = document.createElement('div');
          newFormParent.classList.add('form-input-js');
          newFormParent.appendChild(newFormDiv);
      */
    
    
});



