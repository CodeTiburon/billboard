/**
 * Company: CodeTiburon
 * Date: 2014-03-27
 */
$(function() {
  $('form').ajaxForm({
    success: function(data) {
      if (data.status) {
        window.location = '/';
      } else {
        $('#data').text(data.error).stop().fadeOut();
      }
    }
  });
});