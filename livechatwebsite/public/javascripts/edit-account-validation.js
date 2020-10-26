var edit_username_valid = false;
var edit_email_valid = false;

$("#edit-username").keyup(function() {
    $.ajax ({
      url: "/account/index/?un="+ encodeURIComponent(this.value),
      success: function(result) {
        var obj = JSON.parse(result);

        if(obj.valid == true) {
          $("#edit-username").attr('class', 'form-control is-valid')
          $("#username-feedback").attr('class', 'valid-feedback');
    
          edit_username_valid = true;
        }
        else {
          $("#edit-username").attr('class', 'form-control is-invalid')
          $("#username-feedback").attr('class', 'invalid-feedback');
    
          edit_username_valid = false;
        }
      $("#username-feedback").html(obj.message);
    }});
  });
    
  $("#edit-email").keyup(function() {
    $.ajax ({
      url: "/account/index/?em="+ encodeURIComponent(this.value),
      success: function(result) {
        var obj = JSON.parse(result);

        if(obj.valid == true) {
          $("#edit-email").attr('class', 'form-control is-valid')
          $("#edit-email-feedback").attr('class', 'valid-feedback');
    
          edit_email_valid = true;
        }
        else {
          $("#edit-email").attr('class', 'form-control is-invalid')
          $("#edit-email-feedback").attr('class', 'invalid-feedback');  
    
          edit_email_valid = false;
        }
      $("#edit-email-feedback").html(obj.message);
    }});
  });

  $("#edit-username-form").submit(function() {
    if(edit_username_valid == true)
      return true;
    else
      return false;
  });

  $("#edit-email-form").submit(function() {
    if(edit_email_valid == true)
      return true;
    else
      return false;
  });
