$(document).ready(function () {
  // Function to check screen size and update button classes
  function updateButtonClasses() {
    if ($(window).width() < 576) {
      // Check if screen size is smaller than Bootstrap's sm breakpoint
      $("button.offset-md-1").each(function () {
        $(this).removeClass("col-sm-4");
        $(this).removeClass("me-2");
        $(this).addClass("col-5");
        $(this).addClass("me-4");
        // Check for additional class match
        if ($(this).hasClass("mb-2") && $(this).hasClass("offset-md-1")) {
          // Perform your specific actions here
          console.log("Button has both mb-2 and col-sm-3 classes");
        }
      });
    } else {
      // Optionally, add back the col-sm-4 class if screen size is larger
      $("button.offset-md-1").each(function () {
        if (!$(this).hasClass("col-sm-4")) {
          $(this).addClass("col-sm-4");
          $(this).addClass("me-2");
          $(this).removeClass("col-5");
          $(this).removeClass("me-4");
        }
      });
    }
  }

  // Initial check
  updateButtonClasses();

  // Check on window resize
  $(window).resize(function () {
    updateButtonClasses();
  });
  $("[class*='edit_']").on("click", function () {
    if ($(window).width() < 768) {
      // Check if the screen width is less than 768px (Bootstrap's small screen breakpoint)
      $("html, body").animate({ scrollTop: 0 }, "slow");
    }
  });

});