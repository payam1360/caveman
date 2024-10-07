/**
* Template Name: HeroBiz - v2.3.1
* Template URL: https://bootstrapmade.com/herobiz-bootstrap-business-template/
* Author: BootstrapMade.com
* License: https://bootstrapmade.com/license/
*/

// event delegate for click on the dynamic button elements


document.addEventListener('DOMContentLoaded', () => {
    "use strict";
      // Attach the scroll event to the window
    window.addEventListener('scroll', handleBlogScroll);

    // Initial load
    loadMoreBlogPosts(); // Load the first set of posts when the page loads
  });