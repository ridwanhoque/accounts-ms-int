
$(document).ready(function(){

  
    $('form').on('focus', 'input[type=number]', function(e){
    $(this).on('mousewheel.disableScroll', function(e){
      e.preventDefault()
    })
  });
   
      // Restore scroll on number inputs.
      $('form').on('blur', 'input[type=number]', function(e) {
          $(this).off('wheel');
      });
   
      // Disable up and down keys.
      $('form').on('keydown', 'input[type=number]', function(e) {
          if ( e.which == 38 || e.which == 40 )
              e.preventDefault();
      });  

    //   $.ajaxSetup({
    //     headers: {
    //       Authorization: 'Bearer:' + $('meta[name="api-token"]').attr("content")
    //     }
    //   });
  
  });