<?php $this->load->view('frontend/includes/header_start'); ?>
<?php $this->load->view('frontend/includes/header_end_demo'); ?>
	<div class="content">
			<div class="page-inner">
				<div class="row">
				<div class="col-md-12">
					<div class="card">
							<div class="card-header">
								<div class="card-head-row">
									<div class="card-title"><h5><caption class="text-muted">
										Weekly Report
										<?php echo isset($start_date) ? ' Filtered for:  <strong>Dates:</strong> '. nice_date($start_date, 'F j, Y') : '' ?><?php echo isset($end_date) ? "<strong> to </strong>". nice_date($end_date, 'F j, Y') : '' ?>
										</caption></h5>
									</div>
									<div class="card-tools">
	                                	<button data-toggle="modal" href='#date_range_modal' class="btn btn-outline btn-xs btn-info"><i class="fa fa-calendar"></i> Filter</button>
	                            	</div>
	                        	</div>
							</div>
						</div>
					</div>					
				</div>
					<div class="row">
						<div class="col-md-12">
							<div class="card">
								<div class="card-header">
									<h4 class="card-title"><i class="fas fa-file-invoice-dollar"></i> Weekly Report</h4>
								</div>
								<div class="card-body">
			        				<div class="table-responsive">
			            				<table id="basic-datatables" class="display table table-striped table-bordered table-hover table-head-bg-primary" >
			            					<thead>
			            						<th>#</th>
			                                    <th>Client</th>
			            						<th>Type</th>
			            						<th>Week</th>
			                                    <th>Posted Date</th>
			                                    <th>Records</th>
			                                    <th>Amount</th>
			            						<th class="noExport">Actions</th>
			            					</thead>
			            					<tbody>
			            						<?php $i = 0; foreach ($reports as $report) {?>
			            						<tr>
			            							<td><?php echo ++$i; ?></td>
			            							<td><?php echo strtoupper(ucwords(str_replace('_', ' ', $report->organization))) ?></td>
			                                        <td><?php echo strtoupper(ucwords( $report->report_type)) ?></td>
			                                        <td><?php echo $report->week_name ?></td>
			                                        <td><?php echo nice_date($report->end_week_date, 'm-d-Y');?></td>
			                                        <td><?php echo $report->records ?></td>
			                                        <td><?php echo '$'.number_format($report->amount, 2);?></td>
			            							<td>
			            								<a href="<?php echo base_url()?>uploads/weekly_report/<?php echo $report->file_name ?>" class="btn btn-lg"><i class="fa fa-file-excel text-success" title = "Download Weekly Report"></i></a>
			            							</td>
			            						</tr>
			            						<?php } ?>
			            					</tbody>
			            				</table>
			        				</div>
					             </div><!-- panel-body -->
				            </div><!-- card body end -->
							</div>
						</div>
					</div>
				</div>
	</div>

	<!-- Date range modal selector -->
<div class="modal fade" id="date_range_modal">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Filter Reports</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
        		</button>
			</div>
			<div class="modal-body">
				<?php echo form_open('frontend/member/invoices', ['class' => 'form-horizontal', 'id' => 'date_range_modal_form', 'method' => 'POST']);?>
	           		<div class="form-group" style="padding: 10px">
	           			<div class="row">			        
	           			<div class="col-md-2"><label class="control-label">From: </label></div>
	           			<div class="col-md-4">
		                    <div class='input-group date' id="transaction_start">
		                        <input type='text' name="start_date" class="form-control input-sm" placeholder="Start date" />
		                         <div class="input-group-append">
		                            <span class="input-group-text" id="basic-addon2"><i class="fa fa-calendar"></i></span>
		                        </div>
		                    </div>
		                </div>
	           			<div class="col-md-2"><label class="control-label">To: </label></div>
	           			<div class="col-md-4">
		                    <div class='input-group date' id="transaction_end">
		                        <input type='text' name="end_date" class="form-control input-sm" placeholder="End date" />
		                        <div class="input-group-append">
		                            <span class="input-group-text" id="basic-addon2"><i class="fa fa-calendar"></i></span>
		                        </div>
		                    </div>
		                </div>
		                </div>
	           		</div>
	           </form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning btn-outline btn-xs" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
				<button type="button" id="submit_btn" class="btn btn-primary btn-outline btn-xs"><i class="fa fa-eye"></i> View</button>
			</div>
		</div>
	</div>
</div>
<?php $this->load->view('frontend/includes/footer_start'); ?>
<script >
	$('#submit_btn').on('click', function(){
		if ($('input[name="start_date"]').val() == '' || $('input[name="end_date"]').val() == '') {
			alert('Select start, end dates and specify type of search');
			return false;
		}else{
			if (Date.parse($('input[name="start_date"]').val()) > Date.parse($('input[name="end_date"]').val())) {
				alert('End date should be future of Start Date');
				return false;

			} else {
				$('#date_range_modal_form').submit();

			}
		}
	});
</script>