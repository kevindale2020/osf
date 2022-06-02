<?php include('header.php'); ?>
<section class="container-fluid">
            <div class="row justify-content-center  ">

                <div class="col-3 rounded border shadow p-3 mb-5 bg-white " id="col-Login" >
                        <div class="login-logo">
                            <img src="images/online_space_finder_logo.png">
                        </div>
                            <!-- <p class="text-center"><strong>Login Admin</strong></p> -->
                        
                            <form class="login-form"  method="POST">
                                <input type="hidden" id="login_admin" name="login_admin" value="admin_login">
                                <div class="form-group" id="errorLogin"></div>
                                <div class="form-group">
                                    <label >Username</label>
                                    <div id="errorUsername"></div>
                                    <input type="text" id="username" name="username" class="form-control" placeholder="Username">    
                                </div>
                                <div class="form-group">
                                    <label >Password</label>
                                    <div id="errorPassword"></div>
                                    <input type="password" class="form-control"  id="password" name="password"  placeholder="Password">
                                </div>
                                <a href="register.php">Create Account</a>
                                <div class="form-group">
                                    <button id="btnLogin" class="btn btn-primary float-right">Login</button>                              
                                </div>
                            </form>
                        </div>
                </div>
            </section>
<?php include('footer.php'); ?>