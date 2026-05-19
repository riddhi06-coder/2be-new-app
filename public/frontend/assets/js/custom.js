$(document).ready(function(){
  $(".owl-carousel").owlCarousel({
    items: 1,
    loop: true,
    autoplay: false,
    autoplayTimeout: 3000,
    navText : ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"], 
    animateOut: 'fadeOut',
    animateIn: 'fadeIn',
    smartSpeed: 1000,
    dots: true,
    nav: true,
    responsive: {
         0: {
            nav: false,
         },
         1000: {
            nav: true,
         }
     }
  });
});


$('.awards-slider').owlCarousel({
     loop: false,
     items: 2,
     margin: 20,
     mouseDrag: true,
     autoplay: true,
     autoplayTimeout: 5000,
     dots: true,
     autoplayHoverPause: false,
     nav: true,
     navText: ["<span class='fa fa-angle-left'></span>", "<span class='fa fa-angle-right'></span>"],
     responsiveClass: true,
     responsive: {
         0: {
            items: 1,
            dots: false,
         },
         480: {
             items: 2
         },
         600: {
             items: 2
         },
         1000: {
             items: 3
         }
     }
});

$('.testimonial-slider').owlCarousel({
     loop: false,
     items: 2,
     margin: 20,
     mouseDrag: true,
     autoplay: true,
     autoplayTimeout: 5000,
     dots: true,
     autoplayHoverPause: false,
     nav: false,
     navText: ["<span class='fa fa-angle-left'></span>", "<span class='fa fa-angle-right'></span>"],
     responsiveClass: true,
     responsive: {
         0: {
            items: 1,
            dots: false,
         },
         480: {
             items: 2
         },
         600: {
             items: 2
         },
         1000: {
             items: 3
         }
     }
});

// slider
$('.specialities-carousal').owlCarousel({
     loop: false,
     items: 2,
     margin: 20,
     mouseDrag: true,
     autoplay: false,
     autoplayTimeout: 5000,
     dots: true,
     autoplayHoverPause: false,
     nav: true,
     navText: ["<span class='fa fa-angle-left'></span>", "<span class='fa fa-angle-right'></span>"],
     responsiveClass: true,
     responsive: {
         0: {
            items: 1,
            dots: false,
         },
         480: {
             items: 1
         },
         600: {
             items: 1
         },
         1200: {
             items: 2
         },
         1600: {
             items: 2
         }
     }
});

function odometerController() {
    if (jQuery(".odometer").length > 0) {
      var om = jQuery(".odometer");
      om.each(function () {
        jQuery(this).appear(function () {
          var numCount = jQuery(this).attr("data-count");
          jQuery(this).html(numCount);
        });
      });
    }
  }

/*==========   Add active class to accordions   ==========*/

 var accordionToggle = function() {
  var speed = {duration: 400};
  $('.toggle-content').hide();
  $('.accordion-toggle .toggle-title.active').siblings('.toggle-content').show();
  $('.accordion').find('.toggle-title').on('click', function() {
      $(this).toggleClass('active');
      $(this).next().slideToggle(speed);
      $(".toggle-content").not($(this).next()).slideUp(speed);
      if ($(this).is('.active')) {
          $(this).closest('.accordion').find('.toggle-title.active').toggleClass('active')
          $(this).toggleClass('active');
      };
  });
}; // Accordion Toggle

accordionToggle();



AOS.init({
  once: true
})