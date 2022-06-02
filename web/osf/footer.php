<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){

		/* login */
		$('.login-form').submit(function(e){
			e.preventDefault();
			var username = $('#username').val();
			var password = $('#password').val();

			if(username=="" || password=="") {

				if(username=="") {
					$('#errorUsername').addClass('alert alert-danger');
					$('#errorUsername').html('Username is required.');
				}
				if(password=="") {
					$('#errorPassword').addClass('alert alert-danger');
					$('#errorPassword').html('Password is required.');
				}
				return false;
			}

			 $.ajax({
				url: "admin/ajax.php",
				method: "POST",
				data: new FormData(this),
				contentType: false,
				processData: false,
				success: function(data) {
					var data = JSON.parse(data);
					if(data.success==1) {
						console.log(data);
						window.location.href="admin/index.php";
					} else if(data.success==2) {
						$('#errorLogin').addClass('alert alert-danger');
						$('#errorLogin').html(data.message);
					} else if(data.success==3) {
						window.location.href="admin/message.php";
					} else {
						$('#errorLogin').addClass('alert alert-danger');
						$('#errorLogin').html(data.message);
					}
				}
			});
		});
		/* end */

		/* register */
       $('#register_form').submit(function(e){
        e.preventDefault();

        var fname = $('#fname').val();
        var lname = $('#lname').val();
        var address = $('xaddress').val();
        var email = $('xemail').val();
        var contact = $('#contact').val();
        var username = $('#username').val();
        var password = $('#pass1').val();
        var password2 = $('#pass2').val();

        if(fname==""||lname==""||address==""||email==""||contact==""||username==""||password=="") {
          alert("Please fill-up all fields")
          return false;
        }

        if(password!=password2) {
        	alert("Password does not match with the confirm password");
        	return false;
        }

        if(password.length<8) {
        	alert("Password should at least be 8 characters");
        	return false;
        }

        $.ajax({
          url: "admin/ajax.php",
          method: "POST",
          data: new FormData(this),
          contentType: false,
          processData: false,
          success: function(data) {
            alert("A verification link has been sent to your email account");
            window.location.href='index.php';
          }
        });
      });
       /* end */
	});
</script>
</body>
</html>