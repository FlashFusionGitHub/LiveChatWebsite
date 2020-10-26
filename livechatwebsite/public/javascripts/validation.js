var firstname_valid = false;
var lastname_valid = false;
var username_valid = false;
var email_valid = false;
var password_valid = false;
var repeat_password_valid = false;

$("#username").keyup(function() {
    $.ajax ({
      url: "/createAccount/index/?un="+ encodeURIComponent(this.value),
      success: function(result) {
        var obj = JSON.parse(result);

        if(obj.valid == true) {
          $("#username").attr('class', 'form-control is-valid')
          $("#username-feedback").attr('class', 'valid-feedback');

          username_valid = true;
        }
        else {
          $("#username").attr('class', 'form-control is-invalid')
          $("#username-feedback").attr('class', 'invalid-feedback');

          username_valid = false;
        }
      $("#username-feedback").html(obj.message);
    }});
  });
  
  $("#email").keyup(function() {
    $.ajax ({
      url: "/createAccount/index/?em="+ encodeURIComponent(this.value),
      success: function(result) {
        var obj = JSON.parse(result);

        if(obj.valid == true) {
          $("#email").attr('class', 'form-control is-valid')
          $("#email-feedback").attr('class', 'valid-feedback');

          email_valid = true;
        }
        else {
          $("#email").attr('class', 'form-control is-invalid')
          $("#email-feedback").attr('class', 'invalid-feedback');  

          email_valid = false;
        }
      $("#email-feedback").html(obj.message);
    }});
  });
  
  $("#firstname").keyup(function() {
  
    $("#firstname").attr('class', 'form-control is-invalid')
    $("#firstname-feedback").attr('class', 'invalid-feedback');
  
    if(this.value.length == 0) {
      $("#firstname-feedback").html('Enter your first name');

      firstname_valid = false;
      return;
    }
  
    if(this.value.length > 0 && this.value.length < 64 && /^[a-zA-Z]*[a-zA-Z]$/.test(this.value) == true) {
      $("#firstname").attr('class', 'form-control is-valid')
      $("#firstname-feedback").attr('class', 'valid-feedback');
      $("#firstname-feedback").html('Firstname is valid');

      firstname_valid = true;
    }
    else {
      $("#firstname-feedback").html('First name is not valid');

      firstname_valid = false;
    }
  });
  
  $("#lastname").keyup(function() {
  
    $("#lastname").attr('class', 'form-control is-invalid')
    $("#lastname-feedback").attr('class', 'invalid-feedback');
  
    if(this.value.length == 0) {
      $("#lastname-feedback").html('Enter your last name');
      lastname_valid = false;
      return;
    }
  
    if(this.value.length > 0 && this.value.length < 64 && /^[a-zA-Z]*[a-zA-Z]$/.test(this.value) == true) {
      $("#lastname").attr('class', 'form-control is-valid')
      $("#lastname-feedback").attr('class', 'valid-feedback');
      $("#lastname-feedback").html('Lastname is valid');

      lastname_valid = true
    }
    else {
      $("#lastname-feedback").html('Last name is not valid');

      lastname_valid = false;
    }
  });
  
  $("#password").keyup(function() {
  
    $("#password").attr('class', 'form-control is-invalid');
    $("#password-feedback").attr('class', 'invalid-feedback');
  
    if(this.value.length == 0) {
      $("#password-feedback").html('Enter a password');

      password_valid = false;
    }
    else if(this.value.length > 0 && this.value.length < 6) {
      $("#password-feedback").html('Password too short');

      password_valid = false;
    }
    else if(this.value.length >= 6 && this.value.length < 32) {
      $("#password").attr('class', 'form-control is-valid')
      $("#password-feedback").attr('class', 'valid-feedback');
      $("#password-feedback").html('Password valid');

      password_valid = true;
    }
  });
  
  $("#repeat-password").keyup(function() {
  
    $password = $("#password").val();
  
    $("#repeat-password").attr('class', 'form-control is-invalid');
    $("#repeat-password-feedback").attr('class', 'invalid-feedback');
  
    if(this.value.length == 0 || this.value.length < 6) {
      $("#repeat-password-feedback").html('Repeat password');

      repeat_password_valid = false;
    }
    else if(this.value != $password) {
      $("#repeat-password-feedback").html('Password does not match');

      repeat_password_valid = false;
    }
    else {
      $("#repeat-password").attr('class', 'form-control is-valid')
      $("#repeat-password-feedback").attr('class', 'valid-feedback');
      $("#repeat-password-feedback").html('Password matches');

      repeat_password_valid = true;
    }
  });


  $("#create-account-form").submit(function() {

    if(firstname_valid == true && lastname_valid == true &&
      username_valid == true && email_valid == true &&
      password_valid == true && repeat_password_valid == true) {

        return true;
      }
      else {

        return false;
      }
  });

  $("#edit-name-form").submit(function() {

    if(firstname_valid == true)
      return true;
    else if(lastname_valid == true)
      return true;
    else
      return false;
  });

  $("#edit-password-form").submit(function() {

    if(password_valid == true && repeat_password_valid == true)
      return true;
    else 
      return false;
  });