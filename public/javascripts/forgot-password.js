var email_valid;

  $("#forgot-password-email").keyup(function() {
    $.ajax ({
      url: "/forgotPassword/index/?em="+ encodeURIComponent(this.value),
      success: function(result) {
        var obj = JSON.parse(result);

        if(obj.valid == true) {
          $("#forgot-password-email").attr('class', 'form-control is-valid')
          $("#forgot-password-email-feedback").attr('class', 'valid-feedback');

          email_valid = true;
        }
        else {
          $("#forgot-password-email").attr('class', 'form-control is-invalid')
          $("#forgot-password-email-feedback").attr('class', 'invalid-feedback');  

          email_valid = false;
        }
      $("#forgot-password-email-feedback").html(obj.message);
    }});
  });

  $("#forgot-password-form").submit(function() {

    if(email_valid == true)
      return true;
    else 
      return false;
  });