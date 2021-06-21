<?php $this->load->view('templates/includes/auth_header_start'); ?>
<?php $this->load->view('templates/includes/auth_header_end'); ?>
<div class="content">
        <div class="page-inner">
            <div class="row">
            <div class="col-md-12">
               <div class="card">
                    <div class="card-header">
                        <div class="card-head-row">
                            <div class="card-title"><i class="fas fa-credit-card"></i> <?php echo ucwords(str_replace('_', ' ', $client)); ?>'s</em> Tollway Spending per Road for <?php echo $breadcrumb; ?>
                            <div class="card-tools">
                                View by: 
                    		<button class="btn btn-outline btn-primary btn-xs" title="Month" data-toggle="modal" data-target="#dateModal"><i class="fa fa-calendar"></i> Month</button>
                    		<a onclick="javascript:history.go(-1);"><i class="fas fa-step-backward fa-1x"></i> </a>
                            </div>
                        </div>
                    </div>
                <div class="card-body">
					<div class="table-responsive">
						 <table id="basic-datatables" class="display table table-striped table-bordered table-hover table-head-bg-primary" >
								<thead>
									<th>#</th>
									<th>State</th>
									<th>Tollway Spending</th>
								</thead>
								<tbody>
									<?php $i = 0; $total = 0; foreach ($monthly_road_tolls as $mrt) {
										$i++; $total += $mrt->toll; ?>
										<tr>
											<td><?php echo $i; ?></td>
											<td><?php echo $mrt->state_code; ?></td>
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
        <h4 class="modal-title" id="exampleModalLabel">State Toll by Date</h4>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body form">
      	<?php echo form_open('admin/client_state_tolls_by_date', ['method' => 'POST']);?>
      		<input type="hidden" name="client" value="<?php echo $client; ?>">
		        <div class="form-group">
		        	<div class="row">
			        	<div class="col-md-8">
			        		<div class='input-group date' id='road_day'>
		                        <input type='text' name="toll_date" class="form-control input-sm"  placeholder="yyyyy-mm" />
		                        <span class="input-group-addon">
		                            <span class="fa fa-calendar"></span>
		                        </span>
		                    </div>
			        	</div>
				        <div class="col-md-2">
				        	<button class="btn btn-success btn-sm" type="submit"><i class="fa fa-list-alt"></i> Show Toll</button>
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
<?php $this->load->view('templates/includes/footer_end'); ?>