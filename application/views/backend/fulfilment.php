<?php $this->load->view('templates/includes/auth_header_start'); ?>
<?php $this->load->view('templates/includes/auth_header_end'); ?>
	<div class="content">
				<div class="page-inner">
					<div class="row">
						<div class="col-md-12">
							<div class="card">
								<div class="card-header">
									<div class="card-head-row">
										<h4 class="card-title"><i class="fas fa-truck-pickup"></i> Transponder Fulfilment</h4>
										<div class="card-tools">                                      
											
	                                    </div>
	                                </div>
								</div>
								<div class="card-body">
									<div class="row">
										<div class="col-3 col-md-3">
											<div class="nav flex-column nav-pills nav-primary nav-pills-no-bd" id="v-pills-tab-without-border" role="tablist" aria-orientation="vertical">
												<a class="nav-link active" id="v-pills-home-tab-nobd" data-toggle="pill" href="#v-pills-home-nobd" role="tab" aria-controls="v-pills-home-nobd" aria-selected="true">Order Received</a>
												<a class="nav-link" id="v-pills-first-tab-nobd" data-toggle="pill" href="#v-pills-first-nobd" role="tab" aria-controls="v-pills-first-nobd" aria-selected="true">Processing order</a>
												<a class="nav-link" id="v-pills-profile-tab-nobd" data-toggle="pill" href="#v-pills-profile-nobd" role="tab" aria-controls="v-pills-profile-nobd" aria-selected="false">Ready for shipping</a>
												<a class="nav-link" id="v-pills-messages-tab-nobd" data-toggle="pill" href="#v-pills-messages-nobd" role="tab" aria-controls="v-pills-messages-nobd" aria-selected="false">Order shipped</a>
												<a class="nav-link" id="v-pills-order-tab-nobd" data-toggle="pill" href="#v-pills-order-nobd" role="tab" aria-controls="v-pills-order-nobd" aria-selected="false">Order delivered</a>
											</div>
										</div>
										<div class="col-9 col-md-9">
											<div class="tab-content" id="v-pills-without-border-tabContent">
												<div class="tab-pane fade show active" id="v-pills-home-nobd" role="tabpanel" aria-labelledby="v-pills-home-tab-nobd">
													<div class="table-responsive">
													<table id="" class="display table table-striped table-bordered table-hover table-head-bg-primary" >
														<thead>
															<tr>
																<th>#</th>
																<th>Quantity</th>
																<th>Assets</th>
																<th>Domicile Terminal</th>
																<th>Velcro</th>
																<th>Shipping address</th>
																<th>Instruction</th>
																<th>Files</th>
																<th>Action</th>
															</tr>
														</thead>
														<tbody>
															<?php $i=0; foreach($orders as $k => $order): ?>
															<?php if (in_array($order->status, ['1', '2', '3', '4'])) { continue; }?>
															<tr>
																<td><?php echo ++$i ?></td>
																<td><?php echo $order->quantity ?></td>
																<td><?php echo ucwords($order->assets) ?> Owned</td>
																<td><?php echo $order->domicile_terminal ?></td>
																<td><?php echo $order->velcro ?></td>
																<td><?php echo $order->shipping_address ?></td>
																<td><?php echo ($order->instructions) ? ($order->instructions) : '<center>-</center>';?></td>
																<td><button href="<?php echo base_url();?>uploads/fulfiment/<?php echo $order->asset_file;?>" type="button" class="btn btn-icon btn-round btn-success" data-toggle="tooltip" data-placement="bottom" title="Download Asset File"><i class="fas fa-file-excel"></i> </button>| <button href="<?php echo base_url();?>uploads/fulfiment/<?php echo $order->shipping_list;?>" type="button" class="btn btn-icon btn-round btn-info" data-toggle="tooltip" data-placement="bottom" title="Download Shipping list"><i class="fas fa-file-excel"></i></button>
						                                		</td>
						                                		<td><button type="button" onclick="edit_status(<?php echo $order->fulfilment_id;?>, 1)" class="btn btn-icon btn-round btn-info" data-toggle="tooltip" data-placement="bottom" title="Process Order"><i class="fas fa-cogs"></i>
                                                            </button></td>
															</tr>
															<?php endforeach ?>											
														</tbody>
													</table>
												</div>
												</div>
												<div class="tab-pane fade" id="v-pills-first-nobd" role="tabpanel" aria-labelledby="v-pills-first-tab-nobd">
													<div class="table-responsive">
													<table id="" class="display table table-striped table-bordered table-hover table-head-bg-primary" >
														<thead>
															<tr>
																<th>#</th>
																<th>Quantity</th>
																<th>Assets</th>
																<th>Domicile Terminal</th>
																<th>Velcro</th>
																<th>Shipping address</th>
																<th>Instruction</th>
																<th>Files</th>
																<th>Action</th>
															</tr>
														</thead>
														<tbody>
															<?php $i=0; foreach($orders as $k => $order): ?>
															<?php if (in_array($order->status, ['0', '2', '3', '4'])) { continue; }?>
															<tr>
																<td><?php echo ++$i ?></td>
																<td><?php echo $order->quantity ?></td>
																<td><?php echo ucwords($order->assets) ?> Owned</td>
																<td><?php echo $order->domicile_terminal ?></td>
																<td><?php echo $order->velcro ?></td>
																<td><?php echo $order->shipping_address ?></td>
																<td><?php echo ($order->instructions) ? ($order->instructions) : '<center>-</center>';?></td>
																<td><button href="<?php echo base_url();?>uploads/fulfiment/<?php echo $order->asset_file;?>" type="button" class="btn btn-icon btn-round btn-success" data-toggle="tooltip" data-placement="bottom" title="Download Asset File"><i class="fas fa-file-excel"></i> </button>| <button href="<?php echo base_url();?>uploads/fulfiment/<?php echo $order->shipping_list;?>" type="button" class="btn btn-icon btn-round btn-info" data-toggle="tooltip" data-placement="bottom" title="Download Shipping list"><i class="fas fa-file-excel"></i></button>
						                                		</td>
						                                		<td><button type="button" onclick="edit_status(<?php echo $order->fulfilment_id;?>, 2)" class="btn btn-icon btn-round btn-info" data-toggle="tooltip" data-placement="bottom" title="Ready to ship"><i class="fas fa-ship"></i>
                                                            	</button></td>
															</tr>
															<?php endforeach ?>											
														</tbody>
													</table>
												</div>
												</div>
												<div class="tab-pane fade" id="v-pills-profile-nobd" role="tabpanel" aria-labelledby="v-pills-profile-tab-nobd">
													<div class="table-responsive">
													<table id="" class="display table table-striped table-bordered table-hover table-head-bg-primary" >
														<thead>
															<tr>
																<th>#</th>
																<th>Quantity</th>
																<th>Assets</th>
																<th>Domicile Terminal</th>
																<th>Velcro</th>
																<th>Shipping address</th>
																<th>Instruction</th>
																<th>Files</th>
																<th>Action</th>
															</tr>
														</thead>
														<tbody>
															<?php $i=0; foreach($orders as $k => $order): ?>
															<?php if (in_array($order->status, ['0', '1', '3', '4'])) { continue; }?>
															<tr>
																<td><?php echo ++$i ?></td>
																<td><?php echo $order->quantity ?></td>
																<td><?php echo ucwords($order->assets) ?> Owned</td>
																<td><?php echo $order->domicile_terminal ?></td>
																<td><?php echo $order->velcro ?></td>
																<td><?php echo $order->shipping_address ?></td>
																<td><?php echo ($order->instructions) ? ($order->instructions) : '<center>-</center>';?></td>
																<td><button href="<?php echo base_url();?>uploads/fulfiment/<?php echo $order->asset_file;?>" type="button" class="btn btn-icon btn-round btn-success" data-toggle="tooltip" data-placement="bottom" title="Download Asset File"><i class="fas fa-file-excel"></i> </button>| <button href="<?php echo base_url();?>uploads/fulfiment/<?php echo $order->shipping_list;?>" type="button" class="btn btn-icon btn-round btn-info" data-toggle="tooltip" data-placement="bottom" title="Download Shipping list"><i class="fas fa-file-excel"></i></button>
						                                		</td>
						                                		<td><button type="button" onclick="edit_status(<?php echo $order->fulfilment_id;?>, 3)" class="btn btn-icon btn-round btn-info" data-toggle="tooltip" data-placement="bottom" title="Order shipped"><i class="fas fa-shipping-fast"></i>
                                                            	</button></td>
															</tr>
															<?php endforeach ?>											
														</tbody>
													</table>
												</div>
												</div>
												<div class="tab-pane fade" id="v-pills-messages-nobd" role="tabpanel" aria-labelledby="v-pills-messages-tab-nobd">
													<div class="table-responsive">
													<table id="" class="display table table-striped table-bordered table-hover table-head-bg-primary" >
														<thead>
															<tr>
																<th>#</th>
																<th>Quantity</th>
																<th>Assets</th>
																<th>Domicile Terminal</th>
																<th>Velcro</th>
																<th>Shipping address</th>
																<th>Instruction</th>
																<th>Files</th>
																<th>Action</th>
															</tr>
														</thead>
														<tbody>
															<?php $i=0; foreach($orders as $k => $order): ?>
															<?php if (in_array($order->status, ['0', '1', '2', '4'])) { continue; }?>
															<tr>
																<td><?php echo ++$i ?></td>
																<td><?php echo $order->quantity ?></td>
																<td><?php echo ucwords($order->assets) ?> Owned</td>
																<td><?php echo $order->domicile_terminal ?></td>
																<td><?php echo $order->velcro ?></td>
																<td><?php echo $order->shipping_address ?></td>
																<td><?php echo ($order->instructions) ? ($order->instructions) : '<center>-</center>';?></td>
																<td><button href="<?php echo base_url();?>uploads/fulfiment/<?php echo $order->asset_file;?>" type="button" class="btn btn-icon btn-round btn-success" data-toggle="tooltip" data-placement="bottom" title="Download Asset File"><i class="fas fa-file-excel"></i> </button>| <button href="<?php echo base_url();?>uploads/fulfiment/<?php echo $order->shipping_list;?>" type="button" class="btn btn-icon btn-round btn-info" data-toggle="tooltip" data-placement="bottom" title="Download Shipping list"><i class="fas fa-file-excel"></i></button>
						                                		</td>
						                                		<td><button type="button" onclick="edit_status(<?php echo $order->fulfilment_id;?>, 4)" class="btn btn-icon btn-round btn-info" data-toggle="tooltip" data-placement="bottom" title="Order delivered"><i class="fas fa-truck-loading"></i>
                                                            	</button></td>
															</tr>
															<?php endforeach ?>											
														</tbody>
													</table>
												</div>
												</div>
												<div class="tab-pane fade" id="v-pills-order-nobd" role="tabpanel" aria-labelledby="v-pills-order-tab-nobd">
													<div class="table-responsive">
													<table id="" class="display table table-striped table-bordered table-hover table-head-bg-primary" >
														<thead>
															<tr>
																<th>#</th>
																<th>Quantity</th>
																<th>Assets</th>
																<th>Domicile Terminal</th>
																<th>Velcro</th>
																<th>Shipping address</th>
																<th>Instruction</th>
																<th>Files</th>
															</tr>
														</thead>
														<tbody>
															<?php $i=0; foreach($orders as $k => $order): ?>
															<?php if (in_array($order->status, ['1', '2', '3', '0'])) { continue; }?>
															<tr>
																<td><?php echo ++$i ?></td>
																<td><?php echo $order->quantity ?></td>
																<td><?php echo ucwords($order->assets) ?> Owned</td>
																<td><?php echo $order->domicile_terminal ?></td>
																<td><?php echo $order->velcro ?></td>
																<td><?php echo $order->shipping_address ?></td>
																<td><?php echo ($order->instructions) ? ($order->instructions) : '<center>-</center>';?></td>
																<td><button href="<?php echo base_url();?>uploads/fulfiment/<?php echo $order->asset_file;?>" type="button" class="btn btn-icon btn-round btn-success" data-toggle="tooltip" data-placement="bottom" title="Download Asset File"><i class="fas fa-file-excel"></i> </button>| <button href="<?php echo base_url();?>uploads/fulfiment/<?php echo $order->shipping_list;?>" type="button" class="btn btn-icon btn-round btn-info" data-toggle="tooltip" data-placement="bottom" title="Download Shipping list"><i class="fas fa-file-excel"></i></button>
						                                		</td>
															</tr>
															<?php endforeach ?>											
														</tbody>
													</table>
												</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
<?php $this->load->view('templates/includes/footer_start'); ?>
<script >
   /*DataTables*/
$(document).ready(function() {
    $('table.display').DataTable({
    	dom: 'Blfrtip',
      buttons: [
          {
              extend:    'excelHtml5',
              text:      '<i class="fa fa-file-excel fa-2x"></i>',
              titleAttr: 'Export to Excel',
              exportOptions: {
                            columns: "thead th:not(.noExport)"
                        }
          },
          {
              extend:    'pdfHtml5',
              text:      '<i class="fa fa-file-pdf fa-2x"></i>',
              titleAttr: 'Export to PDF',
              orientation: 'portrait', 
              pageSize: 'A4',
                  /*customize: function() {
                       $('#table_id')
                           .addClass('compact');
                   },*/
                  exportOptions: {
                                columns: "thead th:not(.noExport)"
                            }
          },
          {
            extend:    'print', 
            text:      '<i class="fa fa-print fa-2x"></i>',
            titleAttr: 'Print',
            orientation: 'portrait', 
            pageSize: 'A4',
                /*customize: function(win) {
                     $(win.document.body).find('table')
                         .addClass('compact')
                         .css('font-size', '10pt');
                 },*/
                exportOptions: {
                              columns: "thead th:not(.noExport)"
                          }
          },
          {
            extend:    'colvis', 
            text:      '<i class="fa fa-eye fa-2x"></i>',
            titleAttr: 'Visible columns'
          }
      ]
    });
} );

function edit_status(id, status){
	var r=confirm("Are you sure you want to change the status of the order?")
	 if (r==true)
        $.ajax({
            url : "<?php echo site_url('backend/admin/fulfilment_status')?>/"+id+"/"+status,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                alert('Order status changed successfully');
                location.reload();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error updating status');
            }
        });
        else
          return false;
}

</script>
<?php $this->load->view('templates/includes/footer_end'); ?>