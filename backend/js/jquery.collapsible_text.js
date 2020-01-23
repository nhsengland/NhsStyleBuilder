(function ($) {
  $(function () {
    $('.text.expandable').each(function() {
      $(this).find('.collapsible-button').each(function() {
        if($(this).hasClass('click-added')) {
          return ;
        }
        else {
          $(this).addClass('click-added');
          $(this).click(function() {
            if($(this).hasClass('expanded')) {
              $(this).removeClass('expanded').addClass('collapsed');
              $(this).parents('.expandable').find('.expandable_part').slideUp();
              $(this).text('Read More');
            }
            else {
              $(this).removeClass('collapsed').addClass('expanded');
              $(this).parents('.expandable').find('.expandable_part').slideDown();
              $(this).text('Less');
            }
          });
        }
      });
    });

  });
})(jQuery);