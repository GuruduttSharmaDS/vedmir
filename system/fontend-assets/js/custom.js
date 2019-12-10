
/********** Home Page Sliders **********/
/* Banner Slider */
$(".banner-slider").slick({
  dots: false,
  infinite: true,
  arrow: true,
  fade:true,
  autoplay:true,
  autoplaySpeed:6000,
  speed:1200,
  pauseOnHover:true,
});

/* popular category slider */
$('.popular-category-slider').slick({ 
  dots: false,
  slidesToShow: 1,
  slidesToScroll: 1,
  infinite: false,
});

/* Popular course slider */
$('.popular-course').slick({ 
  dots: false,
  slidesPerRow: 4,
  rows: 2,
  infinite: false,
  responsive: [
    {
      breakpoint: 1024,
      settings: {
        slidesPerRow: 3,
        rows: 2,
      }
    },
    {
      breakpoint: 640,
      settings: {
        slidesPerRow: 2,
        rows: 2,
      }
    }
  ]
});

/* Recent View course slider */
  $('.recent-view-slider').slick({
  dots: false,
  slidesToShow: 4,
  slidesToScroll: 1,
  infinite: false,
});

/* Testimonial slider */
$(".testimonial-slider").slick({
  dots: true,
  infinite: true,
  arrow: false,
  slidesToShow: 3,
  slidesToScroll: 1,
});
/********** end Home Page Sliders **********/

/***** login and forgot jquery ********/
$(".forgot-btn").click(function(){
  $("#forgotPassword").removeClass("hide");
  $("#loginForm").addClass("hide");
});
$(".login-btn").click(function(){
  $("#loginForm").removeClass("hide");
  $("#forgotPassword").addClass("hide");
});

/*****  end login and forgot jquery ********/  

