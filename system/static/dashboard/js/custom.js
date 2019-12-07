$("#menu-toggle").click(function(e) {
      e.preventDefault();
      $("#wrapper").toggleClass("toggled");
    });
  
  $(document).ready(function(){
    if($(window).width() < 767){
      $("#wrapper").addClass("toggled");
    }
  });

