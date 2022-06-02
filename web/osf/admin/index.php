<?php include('header.php'); ?>

<!-- content -->

<?php
if($roleid==1) {
  ?>
  <div class="container-fluid">

        <div id="owner_dashboard"></div>
        <br/>

        <!-- Pending -->
        <div class="row">
          <div class="col">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Pending Reservations</h5>
                <div id="pending_data"></div>
              </div>
            </div>
          </div>
        </div>
        <!-- end -->

         <!-- Approved -->
        <div class="row">
          <div class="col">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Approved Reservations</h5>
                <div id="approved_data"></div>
              </div>
            </div>
          </div>
        </div>
        <!-- end -->

         <!-- Cancelled -->
        <div class="row">
          <div class="col">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Cancelled Reservations</h5>
                <div id="cancelled_data"></div>
              </div>
            </div>
          </div>
        </div>
        <!-- end -->

         <!-- Rejected -->
       <div class="row">
          <div class="col">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Rejected Reservations</h5>
                <div id="rejected_data"></div>
              </div>
            </div>
          </div>
        </div>
        <!-- end -->

         <!-- Closed -->
        <div class="row">
          <div class="col">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Pending Reservations</h5>
                <div id="closed_data"></div>
              </div>
            </div>
          </div>
        </div>
        <!-- end -->
    </div>
    <?php
} else {
  ?>
  <div class="container-fluid">
    <div id="admin_dashboard"></div>
  </div>
  <?php
}
?>

<!-- end -->
<?php include('footer.php'); ?>