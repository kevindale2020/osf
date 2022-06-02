  </div>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="../js/datatables/jquery.dataTables.min.js"></script>
<script src="../js/datatables/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){

    fetch_space();
    fetch_renter();
    fetch_owner();
    fetch_pending();
    fetch_approved();
    fetch_rejected();
    fetch_cancelled();
    fetch_closed();
    fetch_feedbacks();
    fetch_rent_history();
    fetch_owner_notification();
    fetch_admin_notification();
    /* refresh every 5 seconds */
    setInterval(function(){
      fetch_owner_notification();
      fetch_admin_notification();
    }, 5000);
    /* end */
    getType();
    // getCategory();
    loadImage();
    loadProfileInfo();
    loadOwnerDashboards();
    loadAdminDashboards();

		$('.button-left').click(function(){
       		$('.sidebar').toggleClass('fliph');
   		});

    /* profile image */
    $imgSrc = $('#imgProfile').attr('src');

    $('#btnChangePicture').on('click', function () {
                // document.getElementById('profilePicture').click();
      if (!$('#btnChangePicture').hasClass('changing')) {
        $('#profilePicture').click();
      } 
    });

    $('#profilePicture').on('change', function () {
      readURL(this);
      $('#btnChangePicture').addClass('changing');
      $('#btnChangePicture').attr('value', 'Confirm');
      $('#btnChangePicture').attr('type', 'submit');
      $('#btnDiscard').removeClass('d-none');
      // $('#imgProfile').attr('src', '');
    });

     $('#btnDiscard').on('click', function () {
     // if ($('#btnDiscard').hasClass('d-none')) {
      $('#btnChangePicture').removeClass('changing');
      $('#btnChangePicture').attr('value', 'Change');
      $('#btnDiscard').addClass('d-none');
      $('#imgProfile').attr('src', $imgSrc);
      $('#profilePicture').val('');
          // }
      $('#btnChangePicture').attr('type', 'button');
      });

     $('#image_form').submit(function(e) {
      e.preventDefault();
      $('#btnDiscard').addClass('d-none');
      var image = $('#profilePicture').val();
      $.ajax({
        url: "ajax.php",
        method: "POST",
        data: new FormData(this),
        contentType: false,
        processData: false,
        success: function(data) {
          alert("Image saved");
          loadProfileInfo();
          loadImage();
          $('#btnChangePicture').attr('type', 'button');
          $('#btnChangePicture').removeClass('changing');
          $('#btnChangePicture').attr('value', 'Change');
          $('#btnDiscard').addClass('d-none');
          $('#imgProfile').attr('src', $imgSrc);
          $('#profilePicture').val(''); 
        }
      });
     });
    /* end */

    /* profile */
      $('#profile_form').submit(function(e){
        e.preventDefault();

        var fname = $('#fname').val();
        var lname = $('#lname').val();
        var address = $('#address').val();
        var email = $('#email').val();
        var contact = $('#contact').val();
        var username = $('#username').val();

        if(fname==""||lname==""||address==""||email==""||contact==""||username=="") {
          alert("Please fill-up all fields");
          return false;
        }

        $.ajax({
          url: "ajax.php",
          method: "POST",
          data: new FormData(this),
          contentType: false,
          processData: false,
          success: function(data) {
            alert(data);
            loadProfileInfo();
          }
        });
      });
    /* end */

    /* profile */
      $('#password_form').submit(function(e){
        e.preventDefault();

        var old_password = $('#old_password').val();
        var new_password = $('#new_password').val();
        var confirm_password = $('#confirm_password').val();

        if(old_password==""||new_password==""||confirm_password=="") {
          alert("Please fill-up all fields");
          return false;
        }

        if(new_password != confirm_password) {
          alert("Password does not match with the confirm password");
          return false;
        }

        $.ajax({
          url: "ajax.php",
          method: "POST",
          data: new FormData(this),
          contentType: false,
          processData: false,
          success: function(data) {
            alert(data);
            $("#password_form")[0].reset();
          }
        });
      });
    /* end */

      /* trigger space modal */
      $('#btnAddNew').click(function(){
        $('#space_modal').modal('show');
        $('#space_form')[0].reset();
      });
      /* end */

      /* submit space form */
      $('#space_form').submit(function(e){
        e.preventDefault();

        var image = $('#inputGroupFile02').val();
        var type = $('#type').val();
        var category = $('#category').val();
        var name = $('#name').val();
        var desc = $('#desc').val();
        var price = $('#price').val();
        var capacity = $('#capacity').val();
        var address = $('#address').val();
        var contact = $('#contact').val();
        var lat = $('#lat').val();
        var lng = $('#lng').val();

        if(image==""||type==""||category==""||name==""||desc==""||price==""||capacity==""||address==""||contact==""||lat==""||lng=="") {
          alert("Please fill-up all fields");
          $('#inputGroupFile02').val('');
          return false;
        }

        $.ajax({
          url: "ajax.php",
          method: "POST",
          data: new FormData(this),
          contentType: false,
          processData: false,
          success: function(data) {
            alert(data);
            $('#space_modal').modal('hide');
            $('#space_form')[0].reset();
            fetch_space();
          }
        });
      });
      /* end */

      /* trigger owner modal */
      $('#btnAddNewAccount').click(function(){
        $('#owner_modal').modal('show');
      });
      /* end */

      /* add owner account */
       $('#owner_form').submit(function(e){
        e.preventDefault();

        var fname = $('#xfname').val();
        var lname = $('#xlname').val();
        var address = $('#xaddress').val();
        var email = $('#xemail').val();
        var contact = $('#xcontact').val();
        var username = $('#xusername').val();
        var password = $('#xpassword').val();

        if(fname==""||lname==""||address==""||email==""||contact==""||username==""||password=="") {
          alert("Please fill-up all fields")
          return false;
        }

        $.ajax({
          url: "ajax.php",
          method: "POST",
          data: new FormData(this),
          contentType: false,
          processData: false,
          success: function(data) {
            alert(data);
            $('#owner_modal').modal('hide');
            $('#owner_form')[0].reset();
            fetch_owner();
          }
        });
      });
       /* end */

       /* user details */
       $(document).on('click', '.details', function(){

        var userid = $(this).attr("id");
        $('#title').html("User#"+userid);

        $.ajax({
          url: "ajax.php",
          method: "POST",
          async: false,
          data: {
            userid: userid
          },
          success: function(data) {
            var data = JSON.parse(data);
            console.log(data);
            $('#cimage').attr("src", data.image);
            $('#cfname').val(data.fname);
            $('#clname').val(data.lname);
            $('#caddress').val(data.address);
            $('#cemail').val(data.email);
            $('#ccontact').val(data.contact);
            $('#cusername').val(data.username);
            $('#cdate').val(data.date);
            $('#cactive').val(data.active);
            $('#user_details_modal').modal('show');
            $('#user_details_modal')[0].reset();

          },
          error: function(xml, error) {
            console.log(error);
          }
        });
       });
       /* end */

      /* Edit Space */
      $(document).on('click', '.edit_space', function(){
        var sid = $(this).attr("id");
        
        $.ajax({
          url: "ajax.php",
          method: "POST",
          async: false,
          data: {
            sid: sid
          },
          success: function(data) {
            var data = JSON.parse(data);
            console.log(data);
            $('#cimage').attr("src", data.image);
            $('#cname').val(data.name);
            $('#cdesc').val(data.desc);
            $('#cprice').val(data.price);
            $('#ccapacity').val(data.capacity);
            $('#caddress').val(data.address);
            $('#ccontact').val(data.contact);
            $('#clat').val(data.lat);
            $('#clng').val(data.lng);
            $('#sid').val(data.sid);
            $('#space_edit').val("edit_space");
            $('#edit_space_modal').modal('show');
            $('#old_image').val(data.str);
            $('#title').html(data.name);
            getAvailability(data.statusID, data.statusDesc);
            getCurrentType(data.typeID, data.typeDesc);
            getCurrentCategory(data.typeID, data.categoryID, data.categoryDesc);
          }
        });
      });
      /* end */

       /* edit space form */
    $('#edit_space_form').submit(function(e){
      e.preventDefault();
       var name = $('#cname').val();
       var type = $('#ctype').val();
       var category = $('#ccategory').val();
       var desc = $('#cdesc').val();
       var price = $('#cprice').val();
       var capacity = $('#ccapacity').val();
       var address = $('#caddress').val();
       var contact = $('#ccontact').val();
       var lat = $('#clat').val();
       var lng = $('#clng').val();

      if(name=="" || type=="" || category=="" || desc=="" || price==""|| capacity==""|| address==""||contact==""||lat==""||lng=="") {
        alert("Please fill up the fields");
        return false;
      } else {
           $.ajax({
            url: "ajax.php",
            method: "POST",
            data: new FormData(this),
            contentType: false,
            processData: false,
            success: function() {
              alert("Successfully updated");
              $('#edit_space_modal').modal('hide');
              $('#edit_space_form')[0].reset();
               fetch_space();
            }
          });  
        }
    });
    /* end */

      /* Delete Space */
       $(document).on('click', '.delete_space', function(){
        if(confirm('Are you sure you want to delete this space?')) {
           var sid = $(this).attr("id");
          $.ajax({
              url: "ajax.php",
              method: "POST",
              async: false,
              data: {
                "delete-space": 1,
                "sid": sid
              },
              success: function() {
                alert("Successfully deleted");
                fetch_space();
              }
           });
        } else {
          return false;
        }
      });
      /* end */

       /* Delete Feedback */
       $(document).on('click', '.delete_feedback', function(){
        if(confirm('Are you sure you want to delete this feedback?')) {
           var id = $(this).attr("id");
          $.ajax({
              url: "ajax.php",
              method: "POST",
              async: false,
              data: {
                "delete-feedback": 1,
                "id": id
              },
              success: function() {
                alert("Successfully deleted");
                fetch_feedbacks();
              }
           });
        } else {
          return false;
        }
      });
      /* end */

      /* Disable Account */
       $(document).on('click', '.disable', function(){
        if(confirm('Are you sure you want to disable this account?')) {
           var userid = $(this).attr("id");
           $.ajax({
              url: "ajax.php",
              method: "POST",
              async: false,
              data: {
                "disable": 1,
                "userid": userid
              },
              success: function() {
                alert("This account has been disabled");
                fetch_renter();
                fetch_owner();
              }
           });
        } else {
          return false;
        }
      });
      /* end */

      /* Active Account */
       $(document).on('click', '.active_account', function(){
        if(confirm('Are you sure you want to set this account to active?')) {
           var userid = $(this).attr("id");
           $.ajax({
              url: "ajax.php",
              method: "POST",
              async: false,
              data: {
                "active": 1,
                "userid": userid
              },
              success: function() {
                alert("This account has been set to active");
                fetch_renter();
                fetch_owner();
              }
           });
        } else {
          return false;
        }
      });
      /* end */

      /* approve reservation */
      $(document).on('click', '.approve_reservation', function(){

        var rid = $(this).attr("id");
        
        $.ajax({
          url: "ajax.php",
          method: "POST",
          async: false,
          data: {
            "approve-reservation": 1,
            "rid": rid
          },
          success: function(data) {
            alert(data);
            fetch_approved();
            fetch_pending();
            loadOwnerDashboards();
          }
        });
      });
      /* end */

      /* close reservation */
      $(document).on('click', '.close_reservation', function(){

        var rid = $(this).attr("id");
        if(confirm('Are you sure you want to close this reservation?')) {
          $.ajax({
            url: "ajax.php",
            method: "POST",
            async: false,
            data: {
              "close-reservation": 1,
              "rid": rid
            },
            success: function(data) {
              alert(data);
              fetch_approved();
              fetch_closed();
              loadOwnerDashboards();
            }
          });
        } else {
          return false;
        }
      });
      /* end */

       /* trigger reject modal */
      $(document).on('click', '.reject_reservation', function(){

        var rid = $(this).attr("id");

        $('#title_reject').html('Are you sure you want to reject Reservation#'+rid+'?');
        $('#reject_modal').modal('show');
        $('#reject_form')[0].reset();
        $('#rid').val(rid);
        $('#reservation_reject').val('reject_reservation');
        
      });
      /* end */

      /* reject reservation */
      $('#reject_form').submit(function(e){
        e.preventDefault();
         var reason = $('#reason').val();

      if(reason=="") {
        alert("Please fill up the field");
        return false;
      } else {
           $.ajax({
            url: "ajax.php",
            method: "POST",
            data: new FormData(this),
            contentType: false,
            processData: false,
            success: function(data) {
              alert(data);
               $('#reject_modal').modal('hide');
               $('#reject_form')[0].reset();
               fetch_pending();
               fetch_rejected();
               loadOwnerDashboards();
            }
          });  
        }
      });
      /* end */

      /* onselect change type */
      $('#type').change(function(){
        var value = "";
        value = $(this).val();
        $.ajax({
          url: "ajax.php",
          method: "POST",
          async: false,
          data: {
            "selectType": 1,
            "typeid": value
          },
          success: function(data) {
            $('#category').html(data);
          }

        });
      });
      /* end */

      /* onselect change type2 */
      $('#ctype').change(function(){
        var value = "";
        value = $(this).val();
        $.ajax({
          url: "ajax.php",
          method: "POST",
          async: false,
          data: {
            "selectType": 1,
            "typeid": value
          },
          success: function(data) {
            $('#ccategory').html(data);
          }

        });
      });
      /* end */


      /* vacant space */
      $(document).on('click', '.vacant_space', function(){

        var id = $(this).attr("id");

        if(confirm('Are you sure you want to vacant this space?')) {
          $.ajax({
          url: "ajax.php",
          method: "POST",
          async: false,
          data: {
            "vacant-space": 1,
            "id": id
          },
          success: function(data) {
            alert(data);
            fetch_rent_history();
          }
        });
        } else {
          return false;
        }
      });
      /* end */

      /* filter result by date range */
      $('#report_form').submit(function(e){
        e.preventDefault();

        var start = $("#start_date").val();
        var end = $('#end_date').val();

        if(start=="" || end=="") {
          alert('Please fill all fields');
        } else {
           $.ajax({
            url: "ajax.php",
            method: "POST",
            data: new FormData(this),
            contentType: false,
            processData: false,
            success: function(data) {
              console.log("Reports: "+data);
               $('#report_data').html(data);
            }
          });  
        }
      });
      /* end */

      /* convert to pdf */
      // $(document).on('submit', '#pdf_form', function(e){
        
      //   var start = $("#start_date").val();
      //   var end = $('#end_date').val();

      //   if(start=="" || end=="") {
      //     alert('Please fill all fields');
      //   } else {
      //      $.ajax({
      //       url: "ajax.php",
      //       method: "POST",
      //       data: new FormData(this),
      //       contentType: false,
      //       processData: false,
      //       success: function(data) {
      //         console.log("Reports generated" + data);
      //       }
      //     });  
      //   }
      // });
      /* end */

      /* turn off owner notifications */
    $(document).on('click', '.owner_notification', function(){

      $.ajax({
        url: "ajax.php",
        method: "POST",
        async: false,
        data: {
            "update-notify-owner": 1
        },
        success: function() {
          fetch_owner_notification();
        }
      });
    });
    /* end */

      /* turn off admin notifications */
    $(document).on('click', '.admin_notification', function(){

      $.ajax({
        url: "ajax.php",
        method: "POST",
        async: false,
        data: {
            "update-notify-admin": 1
        },
        success: function() {
          fetch_admin_notification();
        }
      });
    });
    /* end */

   		/* select and display image */
     $('#inputGroupFile02').on('change',function(){
        //get the file name
        var fileName = $(this).val();
        //replace the "Choose a file" label
        $(this).next('.custom-file-label').html(fileName);
      });
      $('#inputGroupFile03').on('change',function(){
        //get the file name
        var fileName = $(this).val();
        //replace the "Choose a file" label
        $(this).next('.custom-file-label').html(fileName);
      });
    /* end */ 

    /* allow numeric only */
    setInputFilter(document.getElementById("lat"), function(value) {
      return /^\d*\.?\d*$/.test(value); // Allow digits and '.' only, using a RegExp
    });
    setInputFilter(document.getElementById("lng"), function(value) {
      return /^\d*\.?\d*$/.test(value); // Allow digits and '.' only, using a RegExp
    });
    /* end */

    /* allow numeric only */
    setInputFilter(document.getElementById("clat"), function(value) {
      return /^\d*\.?\d*$/.test(value); // Allow digits and '.' only, using a RegExp
    });
    setInputFilter(document.getElementById("clng"), function(value) {
      return /^\d*\.?\d*$/.test(value); // Allow digits and '.' only, using a RegExp
    });
    /* end */
	});

   /* fetch space */
    function fetch_space() {

      var action = "fetch_space";

      $.ajax({
        url: "ajax.php",
        method: "POST",
        data: {
          action_space: action
        },
        success: function(data) {
          console.log("Spaces: "+data);
          $('#space_data').html(data);
          //  $('#dataTable').DataTable({ 
          //     "destroy": true, //use for reinitialize datatable
          // });
        }
      });

    }
  /* end */

  /* fetch reservations */
    function fetch_pending() {

      var action = "fetch_pending";

      $.ajax({
        url: "ajax.php",
        method: "POST",
        data: {
          action_reservation: action
        },
        success: function(data) {
          console.log(data);
          $('#pending_data').html(data);
           $('#dataTable').DataTable({ 
              "destroy": true, //use for reinitialize datatable
          });
        }
      });

    }
  /* end */

  /* fetch reservations */
    function fetch_approved() {

      var action = "fetch_approved";

      $.ajax({
        url: "ajax.php",
        method: "POST",
        data: {
          action_reservation: action
        },
        success: function(data) {
          console.log(data);
          $('#approved_data').html(data);
           $('#dataTable2').DataTable({ 
              "destroy": true, //use for reinitialize datatable
          });
        }
      });

    }
  /* end */

   /* fetch reservations */
    function fetch_rejected() {

      var action = "fetch_rejected";

      $.ajax({
        url: "ajax.php",
        method: "POST",
        data: {
          action_reservation: action
        },
        success: function(data) {
          console.log(data);
          $('#rejected_data').html(data);
           $('#dataTable4').DataTable({ 
              "destroy": true, //use for reinitialize datatable
          });
        }
      });

    }
  /* end */

   /* fetch reservations */
    function fetch_cancelled() {

      var action = "fetch_cancelled";

      $.ajax({
        url: "ajax.php",
        method: "POST",
        data: {
          action_reservation: action
        },
        success: function(data) {
          console.log(data);
          $('#cancelled_data').html(data);
           $('#dataTable5').DataTable({ 
              "destroy": true, //use for reinitialize datatable
          });
        }
      });

    }
  /* end */

   /* fetch reservations */
    function fetch_closed() {

      var action = "fetch_closed";

      $.ajax({
        url: "ajax.php",
        method: "POST",
        data: {
          action_reservation: action
        },
        success: function(data) {
          console.log(data);
          $('#closed_data').html(data);
           $('#dataTable3').DataTable({ 
              "destroy": true, //use for reinitialize datatable
          });
        }
      });

    }
  /* end */

  /* fetch availability */
  function getAvailability($id, $desc) {

    var action = "get_availability";
    $.ajax({
      url: "ajax.php",
      method: "POST",
      async: false,
      data: { 
        "action_availability": action,
        "id": $id,
        "desc": $desc 
      },
      success: function(data) {
        $('#availability').html(data);
      }
    });
  }
  /* end */

   /* fetch current type */
  function getCurrentType($id, $desc) {

    var action = "get_current_type";
    $.ajax({
      url: "ajax.php",
      method: "POST",
      async: false,
      data: { 
        "action_type": action,
        "id": $id,
        "desc": $desc 
      },
      success: function(data) {
        $('#ctype').html(data);
      }
    });
  }
  /* end */

   /* fetch current category */
  function getCurrentCategory($typeid, $id, $desc) {

    var action = "get_current_category";
    $.ajax({
      url: "ajax.php",
      method: "POST",
      async: false,
      data: { 
        "action_category": action,
        "id": $id,
        "desc": $desc,
        "typeid": $typeid
      },
      success: function(data) {
        $('#ccategory').html(data);
      }
    });
  }
  /* end */

   /* fetch type */
  function getType() {

    var action = "get_type";
    $.ajax({
      url: "ajax.php",
      method: "POST",
      async: false,
      data: { 
        "action_type": action
      },
      success: function(data) {
        $('#type').html(data);
      }
    });
  }
  /* end */

   /* fetch category */
  function getCategory() {

    var action = "get_category";
    $.ajax({
      url: "ajax.php",
      method: "POST",
      async: false,
      data: { 
        "action_category": action
      },
      success: function(data) {
        $('#category').html(data);
      }
    });
  }
  /* end */

  /* fetch rent history */
    function fetch_rent_history() {

      var action = "fetch_rent_history";

      $.ajax({
        url: "ajax.php",
        method: "POST",
        data: {
          action_history: action
        },
        success: function(data) {
          console.log("Rent History: "+data);
          $('#history_data').html(data);
           $('#dataTable6').DataTable({ 
              "destroy": true, //use for reinitialize datatable
          });
        }
      });

    }
  /* end */

  function readURL(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function (e) {
        $('#imgProfile').attr('src', e.target.result);
      };
      reader.readAsDataURL(input.files[0]);
    }
  }

   /* display profile image */
    function loadImage() {

      var action = "load image";

      $.ajax({
        url: "ajax.php",
        method: "POST",
        data: {action: action},
        success: function(data) {
          $('#image_data').html(data);
        }
      });
    }
    /* end */

    /* load profile info */
     function loadProfileInfo() {

      var action = "load profile info";

      $.ajax({
        url: "ajax.php",
        method: "POST",
        data: {action: action},
        success: function(data) {
          var data = JSON.parse(data);
          console.log(data);
          $('#fname').val(data.fname);
          $('#lname').val(data.lname);
          $('#main').html(data.fname + " " + data.lname);
          $('#address').val(data.address);
          $('#email').val(data.email);
          $('#contact').val(data.contact);
          $('#username').val(data.username);
          $('#old_image').val(data.image);
        }
      });
    }
    /* end */

  /* fetch renter */
  function fetch_renter() {

    var action = "fetch_renter";
    $.ajax({
      url: "ajax.php",
      method: "POST",
      data: {
        action_renter: action
      },
      success: function(data) {
        $('#renter_data').html(data);
      }
    });
  }
  /* end */

   /* fetch owner */
  function fetch_owner() {

    var action = "fetch_owner";
    $.ajax({
      url: "ajax.php", 
      method: "POST",
      data: {
        action_owner: action
      },
      success: function(data) {
        $('#owner_data').html(data);
      }
    });
  }
  /* end */

  /* fetch feedback */
  function fetch_feedbacks() {

    var action = "fetch_feedbacks";

    $.ajax({
      url: "ajax.php",
      method: "POST",
      data: {
        action_feedback: action
      },
      success: function(data) {
        $('#feedbacks_data').html(data);
        $('#dataTable7').DataTable({ 
              "destroy": true, //use for reinitialize datatable
          });
      }
    });
  }
  /* end */

  /* fetch owner notification */
  function fetch_owner_notification() {
    $.ajax({
      url: "ajax.php",
      method: "POST",
      async: false,
      data: {
        "notify-owner": 1
      },
      success: function(data) {
        $("#owner_notification").html(data);
      }
    });
  }
  /* end */

   /* fetch owner notification */
  function fetch_admin_notification() {
    $.ajax({
      url: "ajax.php",
      method: "POST",
      async: false,
      data: {
        "notify-admin": 1
      },
      success: function(data) {
        $("#admin_notification").html(data);
      }
    });
  }
  /* end */

  function loadOwnerDashboards() {
    $.ajax({
      url: "ajax.php",
      method: "POST",
      async: false,
      data: {
        "owner-dashboard": 1
      },
      success: function(data) {
        console.log("Pending " + data);
        $('#owner_dashboard').html(data);
      }
    });
  }

  function loadAdminDashboards() {
    $.ajax({
      url: "ajax.php",
      method: "POST",
      async: false,
      data: {
        "admin-dashboard": 1
      },
      success: function(data) {
        console.log("Pending " + data);
        $('#admin_dashboard').html(data);
      }
    });
  }

  // Restricts input for the given textbox to the given inputFilter function.
function setInputFilter(textbox, inputFilter) {
  ["input", "keydown", "keyup", "mousedown", "mouseup", "select", "contextmenu", "drop"].forEach(function(event) {
    textbox.addEventListener(event, function() {
      if (inputFilter(this.value)) {
        this.oldValue = this.value;
        this.oldSelectionStart = this.selectionStart;
        this.oldSelectionEnd = this.selectionEnd;
      } else if (this.hasOwnProperty("oldValue")) {
        this.value = this.oldValue;
        this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
      } else {
        this.value = "";
      }
    });
  });
}
</script>
</body>
</html>