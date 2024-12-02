$(function() {
  
    'use strict';
    //Dashboard hide and appear card-body
    $('.toggle-info').click(function () {
        $(this).toggleClass('selected').parent().next('.card-body').fadeToggle(100);
        if($(this).hasClass('selected')){

          $(this).html('<i class="fa fa-minus fa-lg"></i>');

        }else{

          $(this).html('<i class="fa fa-plus fa-lg"></i>');

        }
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
      }
    });
    // Confirmation Message on Button
    $('.confirm').on('click', function(){
      return confirm('Êtes-vous sûr(e)?');
   });

   //category view option 
  //The fadeToggle => that toggles the visibility of an element by fading it in or out.
   
   $('.cat h3').click(function(){
    $(this).next('.full-view').fadeToggle(200);
   });
   $('.option span').click(function(){

      $(this).addClass('active').siblings('span').removeClass('active');

      if($(this).data('view')==='full') {
          $('.cat .full-view').fadeIn(200);
        }else{
          $('.cat .full-view').fadeOut(200);
        }
  
    });

    //Show Delete Button on Child Cat 

    $('.child-link').hover(function(){
      $(this).find('.show-delete').fadeIn(400);

    },function(){
      $(this).find('.show-delete').fadeOut(400);
    });

});

 