<?php include('header.php'); ?>

<!-- content -->
   <div class="container-fluid">
    <div class="row">
      <div class="col">
        <div class="py-3">
          <button id="btnAddNew" class="btn btn-success"><i class="fa fa-plus"></i> Add New</button>
        </div>
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Space For Rent</h5>
            <div class="table-responsive">
            <div id="space_data"></div>
          </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- The Modal -->
  <div class="modal fade" id="space_modal">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Add Space For Rent</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
          <form id="space_form" class="form-horizontal" method="post" enctype="multipart/form-data">
          <!-- upload image -->
          <div class="input-group mb-3">
            <div class="custom-file">
              <input type="file" id="inputGroupFile02" name="image">
              <label class="custom-file-label" for="inputGroupFile02" aria-describedby="inputGroupFileAddon02">Choose file</label>
            </div>
            <input type="hidden" name="space_new" id="space_new" value="new_space" />
          </div>
          <div class="input-group mb-3">
            <select class="form-control" id="type" name="type"></select>
            <select class="form-control" id="category" name="category"></select>
          </div>
          <div class="input-group mb-3">
            <input type="text" class="form-control" id="name" name="name" placeholder="Name">
          </div>
          <div class="input-group mb-3">
            <textarea class="form-control" rows="5" id="desc" name="desc" placeholder="Description"></textarea>
          </div>
          <div class="input-group mb-3">
            <input type="number" class="form-control" id="capacity" name="capacity" placeholder="Capacity">
            <input type="number" class="form-control" id="price" name="price" placeholder="Price">
          </div>
           <div class="input-group mb-3">
            <input type="text" class="form-control" id="address" name="address" placeholder="Address">
            <input type="phone" class="form-control" id="contact" name="contact" placeholder="Contact">
          </div>
          <div class="input-group mb-3">
            <input type="text" class="form-control" id="lat" name="lat" placeholder="Latitude">
            <input type="text" class="form-control" id="lng" name="lng" placeholder="Longitude">
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

<!-- Edit Space Modal -->
  <div class="modal fade" id="edit_space_modal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title" id="title"></h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
         <form id="edit_space_form" class="form-horizontal" role="form" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="sid" id="sid">
          <input type="hidden" name="space_edit" id="space_edit">
          <input type="hidden" name="old_image" id="old_image">
           <div class="image-container">
            <img id="cimage" src="" style="width: 150px; height: 150px" class="img-thumbnail" />
          </div>
          
          <hr>
        <div class="row">
            <div class="col-md-3 field-label-responsive">
                <label for="cname">Choose Image</label>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <div class="custom-file">
                  <input type="file" id="inputGroupFile03" name="image">
                  <label class="custom-file-label" for="inputGroupFile03" aria-describedby="inputGroupFileAddon02">Choose file</label>
                </div>
              </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 field-label-responsive">
                <label for="cname">Type</label>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                  <select class="form-control" id="ctype" name="ctype"></select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 field-label-responsive">
                <label for="cname">Category</label>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                  <select class="form-control" id="ccategory" name="ccategory"></select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 field-label-responsive">
                <label for="cname">Name</label>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <div class="input-group mb-2 mr-sm-2 mb-sm-0">
                        <input type="text" class="form-control" id="cname" name="cname">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 field-label-responsive">
                <label for="clname">Desciption</label>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <div class="input-group mb-2 mr-sm-2 mb-sm-0">
                        <textarea class="form-control" rows="5" name="cdesc" id="cdesc"></textarea>
                    </div>
                </div>
            </div>
        </div>
         <div class="row">
            <div class="col-md-3 field-label-responsive">
                <label for="caddress">Price</label>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <div class="input-group mb-2 mr-sm-2 mb-sm-0">
                        <input type="number" name="cprice" class="form-control" id="cprice">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 field-label-responsive">
                <label for="cname">Capacity</label>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <div class="input-group mb-2 mr-sm-2 mb-sm-0">
                        <input type="number" class="form-control" id="ccapacity" name="ccapacity">
                    </div>
                </div>
            </div>
        </div>
         <div class="row">
            <div class="col-md-3 field-label-responsive">
                <label for="cemail">Address</label>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <div class="input-group mb-2 mr-sm-2 mb-sm-0">
                        <input type="text" class="form-control" id="caddress" name="caddress">
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
                        <input type="text" class="form-control" id="ccontact" name="ccontact">
                    </div>
                </div>
            </div>
        </div>
         <div class="row">
            <div class="col-md-3 field-label-responsive">
                <label for="cusername">Latitude</label>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <div class="input-group mb-2 mr-sm-2 mb-sm-0">
                        <input type="text" class="form-control" id="clat" name="clat">
                    </div>
                </div>
            </div>
        </div>
         <div class="row">
            <div class="col-md-3 field-label-responsive">
                <label for="cdate">Longitude</label>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <div class="input-group mb-2 mr-sm-2 mb-sm-0">
                        <input type="text" class="form-control" id="clng" name="clng">
                    </div>
                </div>
            </div>
        </div>
         <div class="row">
            <div class="col-md-3 field-label-responsive">
                <label for="cdate">Availability</label>
            </div>
            <div class="col-md-6">
                 <select class="form-control" id="availability" name="availability">
                  
                </select>
            </div>
        </div>
        <button id="btnEditSpace" name="btnEditSpace" class="btn btn-primary">Update</button>
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