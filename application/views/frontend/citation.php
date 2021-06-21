<?php $this->load->view('frontend/includes/header_start'); ?>
<?php $this->load->view('frontend/includes/header_end_demo'); ?>
	<div class="content">
				<div class="page-inner">
					<div class="row">
						<div class="col-md-12">
							<div class="card">
								<div class="card-header">
									<h4 class="card-title"><i class="fas fa-ticket-alt"></i> Citations</h4>
								</div>
								<div class="card-body">
									<div class="table-responsive">
										<table id="basic-datatables" class="display table table-striped table-bordered table-hover table-head-bg-dark" >
											<thead>
												<tr>
													<th>#</th>
													<th>Vehicle LP</th>
							                        <th>LP State</th>
							                        <th>Violation Category</th>
							                        <th>Violation No.</th>
							                        <th>Violation Date</th>
							                        <th>Violation State</th>
							                        <th>Pay Date</th>
							                        <th>Amount</th>
												</tr>
											</thead>
											<tbody> <?php $i = 0; foreach ($citations as $citation) {?>
						                        <tr>
						                            <td><?php echo ++$i; ?></td>
						                            <td><?php echo $citation->license_plate;?></td>
						                            <td><?php echo $citation->license_plate_state;?></td>
						                            <td><?php echo ($citation->citation_type == 'rl') ? 'Red Light' : 'Parking';?></td>
						                            <td><?php echo $citation->violation_no;?></td>
						                            <td><?php echo ($citation->violation_date == '0000-00-00 00:00:00') ? '-' : nice_date($citation->violation_date, 'm/d/Y');?></td>
						                            <td><?php echo $citation->violation_state;?></td>
						                            <td><?php echo ($citation->paid_date == '0000-00-00 00:00:00') ? '-' : nice_date($citation->paid_date, 'Y-m-d h:i:s');?></td>
						                            <td><?php echo '$'.number_format($citation->citation_amount, 2);?></td>
						                        </tr>
						                        <?php } ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
<?php $this->load->view('frontend/includes/footer_start'); ?>
<?php $this->load->view('frontend/includes/footer_end'); ?>