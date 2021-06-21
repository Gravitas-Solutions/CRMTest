<?php $this->load->view('templates/includes/auth_header_start'); ?>
<?php $this->load->view('templates/includes/auth_header_end'); ?>
<div class="content">
    <div class="page-inner">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-head-row">
                            <div class="card-title"><i class="fa fa-map-marker"></i> <?php echo ucwords(str_replace('_', ' ', $client)); ?>'s</em> Tollway Spending per Agency for <?php echo $breadcrumb; ?></div>
                            <div class="card-tools">
                                <button class="btn btn-outline btn-primary btn-xs" title="Month" data-toggle="modal" data-target="#dateModal"><i class="fa fa-calendar" style="cursor: pointer;"></i> Month</button> | <button class="btn btn-outline btn-info btn-xs" title="Department" data-toggle="modal" data-target="#deptModal"><i class="fa fa-sitemap" style="cursor: pointer;"></i> Agency</button> <!-- <a href="<?php echo base_url()?>admin/client_toll_spending"><i class="fa fa-arrow-circle-left  fa-2x"></i> </a> --><a onclick="javascript:history.go(-1);"><i class="fas fa-step-backward fa-1x"></i> </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
        							<div class="table-responsive">
        								<table id="basic-datatables" class="display table table-striped table-bordered table-hover table-head-bg-primary" >
        									<thead>
        										<th>#</th>
        										<th>Agency</th>
        										<th>Tollway Spending</th>
        									</thead>
        									<tbody>
        										<?php $i = 0; $total = 0; foreach ($monthly_road_tolls as $mrt) {
        											$i++; $total += $mrt->toll; ?>
        											<tr>
        												<td><?php echo $i; ?></td>
        												<td><?php echo $mrt->agency_name; ?></td>
        												<td>$<?php echo $mrt->toll; ?></td>
        											</tr>
        										<?php }?>
        										</tr>
        									</tbody>
        								</table>
        							</div>
				            </div>
			          </div>
		          </div>
	        </div>
        </div>	
      </div>
<?php $this->load->view('templates/includes/footer_start'); ?>
<!-- By Month Modal-->
<div class="modal fade" id="dateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLabel">Agency Toll by Date</h4>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body form">
      	<?php echo form_open('backend/admin/client_agency_tolls_by_date', ['id' => 'agency_form', 'method' => 'POST']);?>
      		<input type="hidden" name="client" value="<?php echo $client; ?>">
	        <div class="form-group">
	        	<div class="col-md-8">
	        		<div class='input-group date' id='road_day'>
                        <input type='text' name="road_agency" class="form-control input-sm"  id="agency_data" placeholder="yyyyy-mm" />
                        <span class="input-group-addon">
                            <span class="fa fa-calendar"></span>
                        </span>
              </div>
	        	</div>
	        <div class="col-md-2">
	        	<button class="btn btn-success btn-sm" type="submit"><i class="fa fa-list-alt"></i> Show Toll</button>
	        </div>
	    </div>
	    </form>
      </div>
      <div class="clearfix"></div>
      <div class="modal-footer"></div>
    </div>
  </div>
</div>

<!-- By Dept Modal-->
<div class="modal fade" id="deptModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLabel">Roads Toll by Department</h4>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body form">
      	<?php echo form_open('backend/admin/client_agency_tolls_by_dept', ['id' => 'dept_form', 'method' => 'POST']);?>
      		<input type="hidden" name="client" value="<?php echo $client; ?>">
	        <div class="form-group">
	        	<div class="col-md-8">
	        		<select name="client_dept" id="client_dept" class="form-control input-sm">
	        			<option value="">-- Select department --</option>
	        			<?php foreach ($client_depts as $cd) { ?>
	        				<option value="<?php echo $cd->dept_id ?>"><?php echo ucwords($cd->dept_name) ?></option>
	        			<?php } ?>
	        		</select>
	        	</div>
	        <div class="col-md-2">
	        	<button class="btn btn-success btn-sm" type="submit"><i class="fa fa-list-alt"></i> Show Departmental Toll</button>
	        </div>
	    </div>
	    </form>
      </div>
      <div class="clearfix"></div>
      <div class="modal-footer"></div>
    </div>
  </div>
</div>

<script type="text/javascript">

    $('#agency_form').on('submit', function(){
  if ($('#agency_data').val() == '') {
    alert('Select date to filter');
    return false;
  }
    }); 

    $('#dept_form').on('submit', function(){
  if ($('#client_dept').val() == '') {
    alert('Select department to filter');
    return false;
  }
    }); 

</script>
<?php $this->load->view('templates/includes/footer_end'); ?>