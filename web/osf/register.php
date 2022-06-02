<?php include('header.php'); ?>
<div class="container">

<div class="row justify-content-center">
<div class="col-md-6">
<div class="card">
<header class="card-header">
    <a href="" class="float-right btn btn-outline-primary mt-1">Log in</a>
    <h4 class="card-title mt-2">Sign up</h4>
</header>
<article class="card-body">
<form id="register_form" method="post">
    <input type="hidden" name="new_register" id="new_register" value="register_new">
    <div class="form-row">
        <div class="col form-group">
            <label>First name </label>   
            <input type="text" class="form-control" placeholder="" name="fname" id="fname">
        </div> <!-- form-group end.// -->
        <div class="col form-group">
            <label>Last name</label>
            <input type="text" class="form-control" placeholder="" name="lname" id="lname">
        </div> <!-- form-group end.// -->
    </div> <!-- form-row end.// -->
    <div class="form-group">
        <label>Address</label>
        <input type="text" class="form-control" placeholder="" name="address" = id="address">
    </div> <!-- form-group end.// -->
 <!--    <div class="form-group">
            <label class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="gender" value="option1">
          <span class="form-check-label"> Male </span>
        </label>
        <label class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="gender" value="option2">
          <span class="form-check-label"> Female</span>
        </label>
    </div> <!-- form-group end.// --> 
    <div class="form-row">
        <div class="form-group col-md-6">
          <label>Email</label>
          <input type="email" class="form-control" name="email" id="email">
        </div> <!-- form-group end.// -->
        <div class="form-group col-md-6">
          <label>Contact</label>
            <input type="tel" class="form-control" name="contact" id="contact">
        </div> <!-- form-group end.// -->
    </div> <!-- form-row.// -->
    <div class="form-row">
        <div class="form-group col-md-6">
          <label>Username</label>
          <input type="username" class="form-control" name="username" id="username">
        </div> <!-- form-group end.// -->
        <div class="form-group col-md-6">
          <label>Password</label>
            <input type="password" class="form-control" name="pass1" id="pass1">
        </div> <!-- form-group end.// -->
    </div> <!-- form-row.// -->
     <div class="form-row">
        <div class="form-group col-md-6">
          <label>Confirm Password</label>
          <input type="password" class="form-control" name="pass2" id="pass2">
        </div> <!-- form-group end.// -->
    </div> <!-- form-row.// -->
    <div class="form-group">
        <button id="btnRegister" name="btnRegister" class="btn btn-primary btn-block"> Register  </button>
    </div> <!-- form-group// -->                                        
</form>
</article> <!-- card-body end .// -->
<div class="border-top card-body text-center">Have an account? <a href="index.php">Log In</a></div>
</div> <!-- card.// -->
</div> <!-- col.//-->

</div> <!-- row.//-->


</div> 
<!--container end.//-->

<br><br>

<?php include('footer.php'); ?>