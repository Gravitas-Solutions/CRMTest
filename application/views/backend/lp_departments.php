<?php $this->load->view('templates/includes/auth_header_start'); ?>
<?php $this->load->view('templates/includes/auth_header_end'); ?>
<div class="content">
        <div class="page-inner">
            <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-head-row">
                                    <div class="card-title"><i class="fa fa-file-excel"></i> Match Transaction LPs to Departments</div>
                                    <div class="card-tools">
                                    	<a href="<?php echo base_url()?>uploads/templates/general.xlsx" class="btn btn-xs btn-default"><i class="fa fa-file-excel-o"></i> General template <i class="fa fa-download"></i></a> |
                                    	<a href="<?php echo base_url()?>uploads/templates/amazon.xlsx" class="btn btn-xs btn-default"><i class="fa fa-file-excel-o"></i> Amazon template <i class="fa fa-download"></i></a>
                                        <a onclick="javascript:history.go(-1);"><i class="fas fa-step-backward fa-1x"></i> </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
					<?php echo $this->session->flashdata('message'); ?>					
					<?php echo form_open_multipart(base_url('admin/lp_dept_matcher'), ['class' => 'form-horizontal']); ?>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<div class="col-md-2"><label class="control-label"><i class="fa fa-user"></i> Client:</label></div>
								<div class="col-md-3">
									<select name="client" class="form-control input-sm">
										<option value="" selected="selected"  disabled="disabled">--Client--</option>
										<?php foreach ($clients as $client) {?>
										<option value="<?php echo $client->organization;?>" <?php echo set_select('client', "$client->organization");?>><?php echo ucwords(str_replace('_', ' ', $client->organization));?></option>
										<?php }?>
									</select>
								</div>
								<div class="col-md-1"><label class="control-label"><i class="fa fa-file-excel-o"></i> File:</label></div>
								<div class="col-md-4">
									<input type="file" name="transactions_file" />
								</div>
								<div class="col-md-2">
									<button type="submit" name="upload" class="btn btn-sm btn-block btn-secondary"><strong><i class="fa fa-cloud-upload"></i> Upload</strong></button>
								</div>
							</div>
						</div>
					</div>
					</form>				
					<?php if (isset($general_transactions) && !empty($general_transactions)) { ?>
						<hr>
						<div class="table-responsive">
						<table id="tbl_id" class="table table-hover table-condensed">
							<caption class="text-info" style="padding: 20px 0; ">
								<?php $i = 0;  foreach ($general_transactions as $transaction) { 
									$i += ($transaction->dept_id == '-1') ? 1 : 0;
								?>
								<?php } //End foreach
									echo ($i) ? "<span class='text-danger'><strong>$i LP(s) doesn't exist in the system... Get administrator advisement to proceed to dump</strong></span>" : "<span class='text-success'>All good sparky! Proceed to transactions dump</span>";
								 ?>
							</caption>
							<thead>
								<tr>
									<th class="noExport">Row #</th>
									<th>LP</th>
									<th>State</th>
									<th>Agency</th>
									<th>Exit Datetime</th>
									<th>Exit Lane</th>
									<th>Exit Location</th>
									<th>Dept ID</th>
									<th>Toll</th>
								</tr>
							</thead>
							<tbody>
								<?php $i = 0; foreach ($general_transactions as $transaction) { ?>
									<?php if($transaction->dept_id != '-1'){continue;} ?>
									<tr>
									<td><?php echo ++$i; ?></td>
									<td><?php echo $transaction->license_plate ?></td>
									<td><?php echo $transaction->state_code ?></td>
									<td><?php echo $transaction->agency_name ?></td>
									<td><?php echo $transaction->exit_date_time ?></td>
									<td><?php echo $transaction->exit_lane ?></td>
									<td><?php echo $transaction->exit_location ?></td>
									<td><?php echo $transaction->dept_id ?></td>
									<td><?php echo '$'.$transaction->toll ?></td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
					<?php } ?>
					<?php if (isset($special_transactions) && !empty($special_transactions)) { ?>
						<hr>
						<div class="table-responsive">
						<table id="tbl_id" class="table table-hover table-condensed">
							<caption class="text-info" style="padding: 20px 0; ">
								<?php $i = 0;  foreach ($special_transactions as $transaction) { 
									$i += ($transaction->dept_id == '-1') ? 1 : 0;
								?>
								<?php } //End foreach
									echo ($i) ? "<span class='text-danger'><strong>$i LP(s) doesn't exist in the system... Get administrator advisement to proceed to dump</strong></span>" : "<span class='text-success'>All good sparky! Proceed to transactions dump</span>";
								 ?>
							</caption>
							<thead>
								<tr>
									<th class="noExport">Row #</th>
									<th>LP</th>
									<th>State</th>
									<th>Agency</th>
									<th>Exit Datetime</th>
									<th>Exit Name</th>
									<th>Class</th>
									<th>Dept ID</th>
									<th>Toll</th>
								</tr>
							</thead>
							<tbody>
								<?php $i = 0; foreach ($special_transactions as $transaction) { ?>
									<?php if($transaction->dept_id != '-1'){continue;} ?>
									<tr>
									<td><?php echo ++$i; ?></td>
									<td><?php echo $transaction->license_plate ?></td>
									<td><?php echo $transaction->state_code ?></td>
									<td><?php echo $transaction->agency_name ?></td>
									<td><?php echo $transaction->exit_date_time ?></td>
									<td><?php echo $transaction->exit_name ?></td>
									<td><?php echo $transaction->class ?></td>
									<td><?php echo ($transaction->dept_id != '-1') ?? 'LP not found' ?></td>
									<td><?php echo '$'.$transaction->toll ?></td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
					<?php } ?>
				</div> <!-- End of div panel body -->
			</div> <!-- End of panel panel default -->
		</div>
	</div>
</div>
<?php $this->load->view('templates/includes/footer_start'); ?>

<script type="text/javascript">
	$(document).ready( function () {
        $('#tbl_id').DataTable({
            "aaSorting": [[7, "asc"]],
            "paging":   true,
	        "ordering": false,
	        "info":     false,
            "bFilter": false,
            "lengthChange": false,
            "rowCallback": function( row, data, index ) {
			    if ( data[7] == "-1" ){
			        $('td', row).css({'background-color' : 'brown', 'color' : '#fff'});
			    }
			}
        });
})
</script>
<?php $this->load->view('templates/includes/footer_end'); ?>