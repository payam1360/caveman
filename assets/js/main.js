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
    let prog = 0;
    let input = document.querySelectorAll('.form-input');
    let inputStyle = document.querySelectorAll('.form-input-style');
    let header = document.querySelectorAll('.form-header');
    let headerTxt = document.querySelectorAll('.form-header-style');
    // reset the question bar
    input[0].style.width = '0%';
    input[1].style.opacity = 1;
    input[2].style.width = '0%';
    header[0].style.width = '0%';
    header[1].style.opacity = 1;
    header[2].style.width = '0%';
    let MAX_cnt = 5;
    class question {
        constructor(question, answer, Qidx, Type, Options){
            this.question = question; // must be a text string
            this.answer = answer;
            this.Qidx = Qidx;
            this.type = Type;
            this.options = Options;
        }
        pushData (){
            Questions.push(this);
        }
    };
    
    // this function eventually comes from user costomization and design of his app.
    function questionCreate(){
        let Obj = new question('1. what is your goal?', '', 0, 'image', ['assets/img/arrow-through-heart.svg','assets/img/arrow-through-heart.svg','assets/img/arrow-through-heart.svg']);
        Obj.pushData(Obj);
        Obj = new question('2. what is your name?', '', 1, 'text', '');
        Obj.pushData(Obj);
        Obj = new question('3. what is your weight?', '', 2, 'list', ['kir','kos']);
        Obj.pushData(Obj);
        Obj = new question('4. what is your height?', '', 3, 'list', ['hamed','ali']);
        Obj.pushData(Obj);
        Obj = new question('5. how is your sleep?', '', 4, 'image', ['assets/img/arrow-through-heart.svg','assets/img/arrow-through-heart.svg']);
        Obj.pushData(Obj);
    }
    // create the questions
    questionCreate();
    // initialize header
    headerTxt[1].innerHTML = [Questions[counter].question];
    // initialize the input based on form Type
    resetFormType(input[1]);
    setFormType(input[1], Questions[counter]);
    // Progress circular indicator
    let ctx = document.querySelector('#ProgressCircle');
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
        cutout: 40,
      }
    };
    const progChart = new Chart(
      ctx,
      config
    );
    
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
                ChangeForm(input[1], '0.0s', '0', 1, '40%');
            });
            
            ChangeForm(header[1], '0.5s', gap[1].toString(), 0, '0%');
            header[1].addEventListener('transitionend', () => {
                //Reset
                headerTxt[1].innerHTML = Questions[counter].question;
                ChangeForm(header[1], '0.0s', '0', 1, '40%');
            });
      });
    }
    const moveleft = document.querySelector('.form-go-left');
    if (moveleft) {
        moveleft.addEventListener('click', function(event) {
            if(counter == 0){
                counter = MAX_cnt;
            }
            counter--;
            headerTxt[2].innerHTML = Questions[counter].question;
            let gap = [];
            gap[0] = input[1].getBoundingClientRect().right-input[2].getBoundingClientRect().right;
            gap[1] = input[0].getBoundingClientRect().right-input[1].getBoundingClientRect().right;
            ChangeForm(input[2], '0.5s', gap[0].toString(), 1, '40%');
            input[2].addEventListener('transitionend', () => {
                //Reset
                ChangeForm(input[2], '0.0s', '0', 0, '0%');
            });
            ChangeForm(header[2], '0.5s', gap[0].toString(), 1, '40%');
            header[2].addEventListener('transitionend', () => {
                //Reset
                ChangeForm(header[2], '0.0s', '0', 0, '0%');
            });
            ChangeForm(input[1], '0.5s', gap[1].toString(), 0, '0%');
            input[1].addEventListener('transitionend', () => {
                //Reset
                ChangeForm(input[1], '0.0s', '0', 1, '40%');
            });
            ChangeForm(header[1], '0.5s', gap[1].toString(), 0, '0%');
            header[1].addEventListener('transitionend', () => {
                //Reset
                headerTxt[1].innerHTML = Questions[counter].question;
                ChangeForm(header[1], '0.0s', '0', 1, '40%');
            });
        });
    }
    
    const move_ok = document.querySelector('.form-ok');
    if (move_ok) {
        move_ok.addEventListener('click', function(event) {
            
            let valid = false;
            // get the user answer
            valid = validate_input(inputStyle[1].value);
            if(valid == true){
                prog++;
                Questions[counter].answer = inputStyle[1].value;
                inputStyle[1].value = '';
            }
            
            counter++;
            if(counter == MAX_cnt){
                counter = 0;
            }
            headerTxt[0].innerHTML = Questions[counter].question;
            
            let gap = [];
            gap[0] = input[1].getBoundingClientRect().left-input[0].getBoundingClientRect().left;
            gap[1] = input[2].getBoundingClientRect().left-input[1].getBoundingClientRect().left;
        
            ChangeForm(input[0], '0.5s', gap[0].toString(), 1, '40%');
            input[0].addEventListener('transitionend', () => {
                //Reset
                ChangeForm(input[0], '0.0s', '0', 0, '0%');
            });
            ChangeForm(header[0], '0.5s', gap[0].toString(), 1, '40%');
            header[0].addEventListener('transitionend', () => {
                //Reset
                ChangeForm(header[0], '0.0s', '0', 0, '0%');
            });
            ChangeForm(input[1], '0.5s', gap[1].toString(), 0, '0%');
            input[1].addEventListener('transitionend', () => {
                //Reset
                ChangeForm(input[1], '0.0s', '0', 1, '40%');
            });
            ChangeForm(header[1], '0.5s', gap[1].toString(), 0, '0%');
            header[1].addEventListener('transitionend', () => {
                //Reset
                headerTxt[1].innerHTML = Questions[counter].question;
                ChangeForm(header[1], '0.0s', '0', 1, '40%');
            });
            
            // updating the progress
            progChart.data.datasets[0].data.pop(0);
            progChart.data.datasets[0].data.pop(1);
            progChart.data.datasets[0].data.push(prog / MAX_cnt * 100);
            progChart.data.datasets[0].data.push((1 - prog / MAX_cnt) * 100);
            progChart.update();
            
            });
        }

});

// function to set styles for animation
function ChangeForm(querySel, sec, pixel, opacity, width){
    querySel.style.transitionDuration = sec;
    querySel.style.transform = ["translateX(" + pixel + "px)"];
    querySel.style.opacity = opacity;
    querySel.style.width = width;
}

// function to set the form type
function setFormType(querySelIn, userStruct){
    let newIn = document.createElement('input');
    switch(userStruct.type) {
        case 'text':
            newIn.setAttribute('class', 'form-input-style');
            newIn.setAttribute('type', 'text');
            querySelIn.appendChild(newIn);
            querySelIn.style.borderBottom = '2px solid coral';
            break;
        case 'list':
            newIn.setAttribute('class', 'form-input-style');
            newIn.setAttribute('type', 'text');
            newIn.setAttribute('list', 'inputList');
            querySelIn.appendChild(newIn);
            const dataList = document.createElement("datalist");
            dataList.setAttribute('id', 'inputList');
            userStruct.options.forEach(function(item){
                let Option = document.createElement("option");
                Option.value = item;
                dataList.appendChild(Option);
            });
            querySelIn.appendChild(dataList);
            break;
        case 'image':
            let width = Math.floor(100/userStruct.options.length);
            width = width.toString().concat('%');
            userStruct.options.forEach(function(item){
                let newIn = document.createElement('input');
                newIn.setAttribute('src', item);
                newIn.setAttribute('class', 'form-input-style');
                newIn.style.width = width;
                newIn.type = 'image';
                querySelIn.appendChild(newIn);
            });
            querySelIn.style.borderBottom = '2px solid white';
            break;
    }
}

// function to reset the form type back to normal text
function resetFormType(querySelIn){
    while( querySelIn.childElementCount > 0){
        querySelIn.removeChild(querySelIn.children[0]);
    }
}


function validate_input(input){
    return(true);
}
