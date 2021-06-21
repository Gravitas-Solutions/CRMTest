<?php $this->load->view('templates/includes/auth_header_start'); ?>
<?php $this->load->view('templates/includes/auth_header_end'); ?>
<div class="content">
    <div class="page-inner">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-head-row">
                            <div class="card-title"><i class="fas fa-road"></i> <?php echo ucwords(str_replace('_', ' ', $client)); ?>'s</em> Tollway Spending per Road for <?php echo $breadcrumb; ?></div>
                            <div class="card-tools">
                            	Filter by: 
                    		<button class="btn btn-primary btn-outline btn-xs" title="Month" data-toggle="modal" data-target="#dateModal"><i class="fa fa-calendar" style="cursor: pointer;"></i> Month</button> | 
                    		<button class="btn btn-info btn-outline btn-xs" title="Department" data-toggle="modal" data-target="#deptModal"><i class="fa fa-sitemap" style="cursor: pointer;"></i> Dept</button>
                               <a onclick="javascript:history.go(-1);"><i class="fas fa-step-backward fa-1x"></i> </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
						<div class="table-responsive">
							<table id="basic-datatables" class="display table table-striped table-bordered table-hover table-head-bg-primary" >
								<thead>
									<th>#</th>
									<th>Road</th>
									<th>Tollway Spending</th>
								</thead>
								<tbody>
									<?php $i = 0; $total = 0; foreach ($monthly_road_tolls as $mrt) {
										$i++; $total += $mrt->toll; ?>
										<tr>
											<td><?php echo $i; ?></td>
											<td><?php echo (isset($mrt->exit_name)) ? $mrt->exit_name: $mrt->exit_location; ?></td>
											<td>$<?php echo $mrt->toll; ?></td>
										</tr>
									<?php }?>
									</tr>
								</tbody>
								<tfoot>
									<th>#</th>
									<th>Road</th>
									<th>Tollway Spending<span class="text-muted pull-right">Total &raquo; $<?php echo number_format($total, 2);?></span></th>					
								</tfoot>
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
        <h4 class="modal-title" id="exampleModalLabel">Roads Toll by Date</h4>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body form">
      	<?php echo form_open('backend/admin/roads_by_date', ['id' => 'date','method' => 'POST']);?>
      		<input type="hidden" name="client" value="<?php echo $client; ?>">
	        <div class="form-group">
	        	<div class="row">
	        	<div class="col-md-8">
		        		<div class='input-group date' id='road_day'>
	                        <input type='text' name="road_day" class="form-control input-sm" id="date_data" placeholder="yyyyy-mm" />
	                        <div class="input-group-append">
	                            <span class="input-group-text" id="basic-addon2"><i class="fa fa-calendar"></i></span>
	                        </div>
	                    </div>
		        	</div>
		        <div class="col-md-2">
		        	<button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-list-alt"></i> Show Toll</button>
		        </div>
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
      	<?php echo form_open('backend/admin/roads_by_dept', ['id' => 'dept_form', 'method' => 'POST']);?>
      		<input type="hidden" name="client" value="<?php echo $client; ?>">
	        <div class="form-group">
	        	<div class="row">
		        	<div class="col-md-8">
		        		<select name="client_dept" id="client_dept" class="form-control input-sm">
		        			<option value="">-- Select department --</option>
		        			<?php foreach ($client_depts as $cd) { ?>
		        				<option value="<?php echo $cd->dept_id ?>"><?php echo ucwords($cd->dept_name) ?></option>
		        			<?php } ?>
		        		</select>
		        	</div>
		        <div class="col-md-2">
		        	<button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-list-alt"></i> Show Toll</button>
		        </div>
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

   	$('#date').on('submit', function(){
	if ($('#date_data').val() == '') {
		alert('Select date to filter');
		return false;
	}
    }); 

    $('#dept_form').on('submit', function(){
	if ($('#client_dept').val() == '') {
		alert('Select dept to filter');
		return false;
	}
    }); 

</script>

<?php $this->load->view('templates/includes/footer_end'); ?>