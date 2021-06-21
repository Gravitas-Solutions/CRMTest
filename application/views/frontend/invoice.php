<?php $this->load->view('frontend/includes/header_start'); ?>
<?php $this->load->view('frontend/includes/header_end_demo'); ?>
	<div class="content">
				<div class="page-inner">
					<div class="row">
						<div class="col-md-12">
							<div class="card">
								<div class="card-header">
									<h4 class="card-title"><i class="fas fa-file-invoice-dollar"></i> Invoices</h4>
								</div>
								<div class="card-body">
									<div class="table-responsive">
										<table id="basic-datatables" class="table-condensed display table table-striped table-bordered table-hover table-head-bg-dark" >
											<thead>
													<th><i class="fa fa-download"></i> Details</th>
						                            <th>Invoice Date</th>
						                            <th>Paid Date</th>
						                            <th>Toll Amount</th>
						                            <th>Type of Fees</th>
						                            <th>Fees Amount</th>
						                            <th>Invoice Amount</th>
						                            <th>Invoice Status</th>
						                            <th>Department</th>
											</thead>
											<tbody>
												<?php $i = 0; foreach ($invoices as $invoice) {?>
						                            <tr>
						                                <td><button href="<?php echo base_url();?>uploads/client_invoices/<?php echo $invoice->pdf;?>" type="button" class="btn btn-icon btn-round btn-danger" data-toggle="tooltip" data-placement="bottom" title="Download PDF">
														<i class="fas fa-file-pdf"></i> 
														</button> | <button href="<?php echo base_url();?>uploads/client_invoices/<?php echo $invoice->excel;?>" type="button" class="btn btn-icon btn-round btn-success" data-toggle="tooltip" data-placement="bottom" title="Download Excel"><i class="fas fa-file-excel"></i></button>
						                                </td>
						                                <td><?php echo nice_date($invoice->invoice_date, 'm/d/Y');?></td>
						                                <td><center><?php echo ($invoice->pay_date == '0000-00-00 00:00:00') ? '<center>-</center> ' : nice_date($invoice->pay_date, 'm/d/Y');?></center></td>
						                                <td><?php echo '$'.number_format($invoice->toll_amount, 2);?></td>
						                                <td><?php  echo ucwords(str_replace('_', ' ', $invoice->fee_type));?></td>
						                                <td><?php echo '$'.number_format($invoice->toll_fee, 2);?></td>
						                                <td><?php echo '$'.number_format($invoice->invoice_amount, 2);?></td>
						                                <td><?php echo ($invoice->invoice_status) ? 'Open': 'Closed';?></td>
						                                <td><?php echo $invoice->dept_name;?></td>
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
<script >

</script>