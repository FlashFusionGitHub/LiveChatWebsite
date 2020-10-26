function profileImagePreview(input) {

    var file = input.files[0];
    var fileTypes = ['gif', 'jpg', 'jpeg', 'png'];
    var extension = input.files[0].name.split('.').pop().toLowerCase()
    var isSuccess = fileTypes.indexOf(extension) > -1;
    var reader = new FileReader();

    if(file && isSuccess && file.size <= 2097152) {

      document.getElementById("image_div").innerHTML = '<h5 class="form-heading">New Profile Image</h5><img id="image_preview" src="" alt="" class="img-thumbnail mb-4"></img>';
      document.getElementById("upload_btn_div").innerHTML = '<button onclick="uploading(this)" type="submit" class="btn-success" name="upload_profile_image">upload</button>';

      reader.onload = function(e) {

        $('#file_error').html('');

        $('#image_preview').attr({
          'src': e.target.result,
          'alt': file.name
        });
      }
    }
    else if(file && isSuccess && file.size > 2097152) {

      reader.onload = function() {
        
        $('#file_error').html('File too large [max 2mb]');
        $('#image_div').html('');
        $('#upload_btn_div').html('');
      }
    }
    else {

      reader.onload = function() {

        $('#file_error').html('Unknown File');
        $('#image_div').html('');
        $('#upload_btn_div').html('');
      }
    }

    reader.readAsDataURL(file);
}

function uploading(input) {
  input.style.display = "none"
}