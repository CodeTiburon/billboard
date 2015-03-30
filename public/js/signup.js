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
        $('#form-error')
          .text(data.error)
          .stop().show()
          .fadeOut(5000);
      }
    },

    error: function(jqXHR, textStatus, errorThrown) {
      if (console.log) {
        console.log(textStatus, errorThrown);
      }
    }
  });
});