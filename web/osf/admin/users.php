<?php include('header.php'); ?>

<!-- content -->
   <div class="container">
      <section id="tabs" class="project-tab">
                <div class="row">
                    <div class="col-md-12">
                        <nav>
                            <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                                <a class="nav-item nav-link active" id="nav-renter-tab" data-toggle="tab" href="#nav-renter" role="tab" aria-controls="nav-renter" aria-selected="true">Renters</a>
                                <a class="nav-item nav-link" id="nav-owner-tab" data-toggle="tab" href="#nav-owner" role="tab" aria-controls="nav-owner" aria-selected="false">Space Owners</a>
                            </div>
                        </nav>
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-renter" role="tabpanel" aria-labelledby="nav-renter-tab">
                              <div class="card">
                                <div class="card-body">
                                  <div id="renter_data"></div>
                                </div>
                              </div>
                            </div>
                            <div class="tab-pane fade" id="nav-owner" role="tabpanel" aria-labelledby="nav-owner-tab">
                               <div class="card">
                                <div class="card-body">
                                 <!--  <div class="py-3">
                                   <button id="btnAddNewAccount" class="btn btn-success"><i class="fa fa-plus"></i> Add New</button>
                                 </div> -->
                                  <div id="owner_data"></div>
                                </div>
                              </div>
                            </div>
                        </div>
                    </div>
                </div>
        </section>
    </div>

    <!-- Account Owner Account -->
  <div class="modal fade" id="owner_modal">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Add Account</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
          <form id="owner_form" class="form-horizontal" method="post" enctype="multipart/form-data">
          <!-- upload image -->
          <input type="hidden" name="owner_new" id="owner_new" value="new_owner" />
          <div class="input-group mb-3">
            <input type="text" class="form-control" id="xfname" name="fname" placeholder="Firstname">
            <input type="text" class="form-control" id="xlname" name="lname" placeholder="Lastname">
          </div>
          <div class="input-group mb-3">
            <input type="text" class="form-control" id="xaddress" name="address" placeholder="Address">
          </div>
           <div class="input-group mb-3">
            <input type="email" class="form-control" id="xemail" name="email" placeholder="Email">
            <input type="phone" class="form-control" id="xcontact" name="contact" placeholder="Contact">
          </div>
          <div class="input-group mb-3">
            <input type="text" class="form-control" id="xusername" name="username" placeholder="Username">
            <input type="password" class="form-control" id="xpassword" name="password" placeholder="Password">
          </div>     
          <div class="form-group">
            <button id="btnNew" name="btnNew" class="btn btn-primary">Submit</button>
          </div>
        </form>

        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
        
      </div>
    </div>
  </div>

  <!-- User Details -->
  <div class="modal fade" id="user_details_modal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title" id="title"></h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
         <form class="form-horizontal" role="form" method="POST" action="/register">
           <div class="image-container">
            <img id="cimage" src="" style="width: 150px; height: 150px" class="img-thumbnail" />
          </div>

          <hr>
        <div class="row">
            <div class="col-md-3 field-label-responsive">
                <label for="cfname">First name</label>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <div class="input-group mb-2 mr-sm-2 mb-sm-0">
                        <input type="text" class="form-control" id="cfname" readonly>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 field-label-responsive">
                <label for="clname">Last name</label>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <div class="input-group mb-2 mr-sm-2 mb-sm-0">
                        <input type="text" class="form-control" id="clname" readonly>
                    </div>
                </div>
            </div>
        </div>
         <div class="row">
            <div class="col-md-3 field-label-responsive">
                <label for="caddress">Address</label>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <div class="input-group mb-2 mr-sm-2 mb-sm-0">
                        <input type="text" class="form-control" id="caddress" readonly>
                    </div>
                </div>
            </div>
        </div>
         <div class="row">
            <div class="col-md-3 field-label-responsive">
                <label for="cemail">Email</label>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <div class="input-group mb-2 mr-sm-2 mb-sm-0">
                        <input type="text" class="form-control" id="cemail" readonly>
                    </div>
                </div>
            </div>
        </div>
         <div class="row">
            <div class="col-md-3 field-label-responsive">
                <label for="ccontact">Contact</label>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <div class="input-group mb-2 mr-sm-2 mb-sm-0">
                        <input type="text" class="form-control" id="ccontact" readonly>
                    </div>
                </div>
            </div>
        </div>
         <div class="row">
            <div class="col-md-3 field-label-responsive">
                <label for="cusername">Username</label>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <div class="input-group mb-2 mr-sm-2 mb-sm-0">
                        <input type="text" class="form-control" id="cusername" readonly>
                    </div>
                </div>
            </div>
        </div>
         <div class="row">
            <div class="col-md-3 field-label-responsive">
                <label for="cdate">Date Created</label>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <div class="input-group mb-2 mr-sm-2 mb-sm-0">
                        <input type="text" class="form-control" id="cdate" readonly>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 field-label-responsive">
                <label for="cactive">Account Status</label>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <div class="input-group mb-2 mr-sm-2 mb-sm-0">
                        <input type="text" class="form-control" id="cactive" readonly>
                    </div>
                </div>
            </div>
        </div>
    </form>

        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
        
      </div>
    </div>
  </div>
  
<!-- end -->
<?php include('footer.php'); ?>