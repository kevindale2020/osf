<?php include('header.php'); ?>

<!-- content -->
   <div class="container">
    <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-body">
                        <div class="card-title mb-4">
                            <div class="d-flex justify-content-start">
                                <div class="image-container">
                                  <div id="image_data"></div>
                                    <div class="middle">
                                      <form id="image_form" method="post" enctype="multipart/form-data">
                                        <input type="file" style="display: none;" id="profilePicture" name="image" />
                                        <input type="hidden" name="image_new" id="image_new" value="new_image">
                                        <input type="hidden" name="old_image" id="old_image">
                                        <input type="button" class="btn btn-secondary" id="btnChangePicture" value="Change" />
                                      </form>
                                    </div>
                                </div>
                                <div class="userData ml-3">
                                    <h2 class="d-block" style="font-size: 1.5rem; font-weight: bold"><a id="main" href="javascript:void(0);"></a></h2>
                                  <!--   <h6 class="d-block"><a href="javascript:void(0)">1,500</a> Spaces</h6>
                                    <h6 class="d-block"><a href="javascript:void(0)">300</a> Feedbacks</h6> -->
                                </div>
                                <div class="ml-auto">
                                    <input type="button" class="btn btn-primary d-none" id="btnDiscard" value="Discard Changes" />
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="basicInfo-tab" data-toggle="tab" href="#basicInfo" role="tab" aria-controls="basicInfo" aria-selected="true">Basic Info</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="password-tab" data-toggle="tab" href="#password" role="tab" aria-controls="password" aria-selected="false">Change Password</a>
                                    </li>
                                   <!--  <li class="nav-item">
                                        <a class="nav-link" id="connectedServices-tab" data-toggle="tab" href="#connectedServices" role="tab" aria-controls="connectedServices" aria-selected="false">Feedbacks</a>
                                    </li> -->
                                </ul>
                                <div class="tab-content ml-1" id="myTabContent">
                                    <div class="tab-pane fade show active" id="basicInfo" role="tabpanel" aria-labelledby="basicInfo-tab">
                                        
                                      <form id="profile_form">
                                        <input type="hidden" name="info_edit" id="info_edit" value="edit_info">
                                        <div class="row">
                                            <div class="col-sm-3 col-md-2 col-5">
                                                <label style="font-weight:bold;">First name</label>
                                            </div>
                                            <div class="col-md-4">
                                              <div class="form-group">
                                                <input type="text" id="fname" name="fname" class="form-control">
                                              </div>
                                            </div>
                                        </div>
                                        <hr />

                                       <div class="row">
                                            <div class="col-sm-3 col-md-2 col-5">
                                                <label style="font-weight:bold;">Last name</label>
                                            </div>
                                            <div class="col-md-4">
                                              <div class="form-group">
                                                <input type="text" id="lname" name="lname" class="form-control">
                                              </div>
                                            </div>
                                        </div>
                                        <hr />
                                        
                                        
                                        <div class="row">
                                            <div class="col-sm-3 col-md-2 col-5">
                                                <label style="font-weight:bold;">Address</label>
                                            </div>
                                            <div class="col-md-4">
                                              <div class="form-group">
                                                <input type="text" id="address" name="address" class="form-control">
                                              </div>
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="row">
                                            <div class="col-sm-3 col-md-2 col-5">
                                                <label style="font-weight:bold;">Email</label>
                                            </div>
                                            <div class="col-md-4">
                                              <div class="form-group">
                                                <input type="text" id="email" name="email" class="form-control">
                                              </div>
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="row">
                                            <div class="col-sm-3 col-md-2 col-5">
                                                <label style="font-weight:bold;">Contact</label>
                                            </div>
                                            <div class="col-md-4">
                                              <div class="form-group">
                                                <input type="text" id="contact" name="contact" class="form-control">
                                              </div>
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="row">
                                            <div class="col-sm-3 col-md-2 col-5">
                                                <label style="font-weight:bold;">Username</label>
                                            </div>
                                            <div class="col-md-4">
                                              <div class="form-group">
                                                <input type="text" id="username" name="username" class="form-control">
                                              </div>
                                            </div>
                                        </div>
                                        <hr />
                                        <button id="btnProfile" name="btnProfile" class="btn btn-primary">Update</button>
                                      </form>
                                    </div>
                                    <div class="tab-pane fade" id="password" role="tabpanel" aria-labelledby="password-tab">
                                    <form id="password_form" method="post">
                                        <input type="hidden" name="password_edit" id="password_edit" value="edit_password">
                                        <div class="row">
                                            <div class="col-sm-3 col-md-2 col-5">
                                                <label style="font-weight:bold;">Current Password</label>
                                            </div>
                                            <div class="col-md-4">
                                              <div class="form-group">
                                                <input type="password" id="old_password" name="old_password" class="form-control">
                                              </div>
                                            </div>
                                        </div>
                                        <hr />

                                       <div class="row">
                                            <div class="col-sm-3 col-md-2 col-5">
                                                <label style="font-weight:bold;">New Password</label>
                                            </div>
                                            <div class="col-md-4">
                                              <div class="form-group">
                                                <input type="password" id="new_password" name="new_password" class="form-control">
                                              </div>
                                            </div>
                                        </div>
                                        <hr />
                                         <div class="row">
                                            <div class="col-sm-3 col-md-2 col-5">
                                                <label style="font-weight:bold;">Confirm Password</label>
                                            </div>
                                            <div class="col-md-4">
                                              <div class="form-group">
                                                <input type="password" id="confirm_password" name="confirm_password" class="form-control">
                                              </div>
                                            </div>
                                        </div>
                                        <hr />
                                        <button id="btnPassword" name="btnPassword" class="btn btn-primary">Update</button>
                                      </form>
                                    </div>
                                   <!--  <div class="tab-pane fade" id="connectedServices" role="tabpanel" aria-labelledby="ConnectedServices-tab">
                                       Feedbacks here
                                    </div> -->
                                </div>
                            </div>
                        </div>


                    </div>

                </div>
            </div>
        </div>
   </div>
<!-- end -->
<?php include('footer.php'); ?>