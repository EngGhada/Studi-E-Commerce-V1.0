$(function() {
  
    'use strict';
    //Switch between Login & Signup.
     $('.login-page h1 span').click(function() {
      $(this).addClass('selected').siblings().removeClass('selected');
      $('.login-page form').hide();
      $('.'+$(this).data('class')).fadeIn(100);
      
     });

    // trigger the SelectBoxIt plugin

        $('select').selectBoxIt({
          autoWidth:false 
        });

  //Hide placeholder on forum focus

      $('[placeholder]').focus(function() {
        if ($(this).val() === '') {
          $(this).data('placeholder', $(this).attr('placeholder'));
          $(this).attr('placeholder', '');
        }
      }).blur(function() {
        if ($(this).val() === '') {
          $(this).attr('placeholder', $(this).data('placeholder'));
        }
      });

     // Add asterisk on the required field
        $('input').each(function() {
          if ($(this).is('[required]')) {
            $(this).wrap('<div class="input-group "></div>');
            $(this).after('<span class="text-danger  asterisk">*</span>');

            // Check if the input is already filled (for Remember Me)
              if ($(this).val() !== '') {
                $(this).next('.asterisk').hide(); // Hide the asterisk if the field is filled
              }

              // Dynamically show or hide asterisk as user types
              $(this).on('input', function() {
                $(this).next('.asterisk').toggle($(this).val() === '');
              });

          }
        });
    
    // Confirmation Message on Button
        $('.confirm').on('click', function(){
        
          return confirm('Are you sure');
      });

  // function to add title and price and description to the card  at the moment of writing the data

  $('.live').keyup(function(){
   
     
     $($(this).data('class')).text($(this).val());

  });

});

 