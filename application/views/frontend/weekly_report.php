<?php $this->load->view('frontend/includes/header_start'); ?>
<?php $this->load->view('frontend/includes/header_end_demo'); ?>
	<div class="content">
				<div class="page-inner">
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
<?php $this->load->view('frontend/includes/footer_start'); ?>
<script >

</script>