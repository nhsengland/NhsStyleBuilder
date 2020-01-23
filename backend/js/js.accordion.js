(function ($) {
  $(function () {
    $('.kd_accordion > div').hide();
    $('.kd_accordions').each(function() {
      $(this).find('.kd_accordion > div').eq(0).show();
      $(this).find('.kd_accordion > h2').eq(0).addClass('active');
    });
    $('.kd_accordion').each(function () {
      if($(this).hasClass('click-added')) {
        return ;
      }
      else {
        $(this).addClass('click-added');
        $(this).find('h2').click(function () {
          if($(this).hasClass('active')) {
            return ;
          }
          $(this).parents('.kd_accordions').find('.kd_accordion > div').slideUp();
          $(this).parents('.kd_accordions').find('.kd_accordion > h2').removeClass('active');
          $(this).toggleClass('active');
          $(this).next().slideToggle();
        });
      }
  });
    });
})(jQuery);