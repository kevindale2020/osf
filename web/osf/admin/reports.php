<?php include('header.php'); ?>

<!-- content -->
   <div class="container-fluid">
    <div class="row">
      <div class="col">
         <h3>Rent Records</h3>
        <div class="py-3">
          <div class="col-md-4">
            <form id="report_form" method="post">
              <input type="hidden" id="result_filter" name="result_filter" value="filter_result">
              <div class="form-group">
                <input id="start_date" type="text" class="form-control" name="start_date" placeholder="Start Date" onfocus="(this.type='date')">
              </div>
              <div class="form-group">
               <input id="end_date" type="text" class="form-control" name="end_date" placeholder="End Date" onfocus="(this.type='date')">
              </div>
              <button id="btnGo" name="btnGo" class='btn btn-primary btn-sm'>Go</button>
            </form>
          </div>
        </div>
        <div id="report_data"></div>
      </div>
    </div>
  </div>
  
<!-- end -->
<?php include('footer.php'); ?>