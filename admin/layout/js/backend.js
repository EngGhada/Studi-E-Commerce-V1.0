$(function() {
  
    'use strict';
    
     //alert('JavaScript code is running!');
     
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
      }
    });
    // Confirmation Message on Button
    $('.confirm').on('click', function(){
    
      return confirm('Are you sure');
   });

});

 