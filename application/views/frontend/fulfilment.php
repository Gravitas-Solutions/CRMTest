<?php $this->load->view('frontend/includes/header_start'); ?>
<?php $this->load->view('frontend/includes/header_end_demo'); ?>
	<div class="content">
		<div class="page-inner">
			<div class="row">
				<div class="col-md-12">
					<div class="card">
						<div class="card-header">
							<div class="card-head-row">
								<h4 class="card-title"><i class="fas fa-truck-pickup"></i> Transponder Fulfilment</h4>
								<div class="card-tools">                                      
									<a type="button" class="btn btn-info btn-xs" href="<?php echo base_url('transponder') ?>" ><i class="fa fa-plus-circle"></i> Order Transponders </a>
                                </div>
                            </div>
							</div>
							<div class="card-body">
								<div class="col-md-12">
								<div class="card">
									<div class="card-body">
										<?php echo $this->session->flashdata('message'); ?> 
										<ul class="nav nav-pills nav-primary" id="pills-tab" role="tablist">
											<li class="nav-item">
												<a class="nav-link active" id="pills-home-tab-nobd" data-toggle="pill" href="#pills-home-nobd" role="tab" aria-controls="pills-home-nobd" aria-selected="true">Order Received</a>
											</li>
											<li class="nav-item">
												<a class="nav-link" id="pills-profile-tab-nobd" data-toggle="pill" href="#pills-profile-nobd" role="tab" aria-controls="pills-profile-nobd" aria-selected="false">Processing order</a>
											</li>
											<li class="nav-item">
												<a class="nav-link" id="pills-contact-tab-nobd" data-toggle="pill" href="#pills-contact-nobd" role="tab" aria-controls="pills-contact-nobd" aria-selected="false">Ready for shipping</a>
											</li>
											<li class="nav-item">
												<a class="nav-link" id="pills-contact-tab-nobd" data-toggle="pill" href="#pills-ship-nobd" role="tab" aria-controls="pills-contact-nobd" aria-selected="false">Order shipped</a>
											</li>
											<li class="nav-item">
												<a class="nav-link" id="pills-contact-tab-nobd" data-toggle="pill" href="#pills-receive-nobd" role="tab" aria-controls="pills-contact-nobd" aria-selected="false">Order Received</a>
											</li>
											<li class="nav-item">
												<a class="nav-link" id="pills-draft-tab-nobd" data-toggle="pill" href="#pills-draft-nobd" role="tab" aria-controls="pills-draft-nobd" aria-selected="true">My drafts</a>
											</li>
										</ul>
										<hr>
										<div class="tab-content mt-2 mb-3" id="pills-without-border-tabContent">
											<div class="tab-pane fade show active" id="pills-home-nobd" role="tabpanel" aria-labelledby="pills-home-tab-nobd">
												<div class="table-responsive">
														<table id="" class="display table table-striped table-bordered table-hover table-head-bg-dark" >
															<thead>
																<tr>
																	<th>#</th>
																	<th>Quantity</th>
																	<th>Assets</th>
																	<th>Domicile Terminal</th>
																	<th>Velcro</th>
																	<th>Shipping address</th>
																	<th>Instruction</th>
																	<th>Action</th>
																</tr>
															</thead>
															<tbody>
																<?php $i=0; foreach($orders as $k => $order): ?>
																<?php if (in_array($order->status, ['1', '2', '3', '4', '-1'])) { continue; }?>
																<tr>
																	<td><?php echo ++$i ?></td>
																	<td><?php echo $order->quantity ?></td>
																	<td><?php echo ucwords($order->assets) ?> Owned</td>
																	<td><?php echo $order->domicile_terminal ?></td>
																	<td><?php echo $order->velcro ?></td>
																	<td><?php echo $order->shipping_address ?></td>
																	<td><?php echo ($order->instructions) ? ($order->instructions) : '<center>-</center>';?></td>
																	<td><a href="<?php echo base_url();?>uploads/fulfiment/<?php echo $order->asset_file;?>/"><i data-toggle="tooltip" data-placement="bottom" title="Download Asset File" class="fas fa-file-excel fa-2x text-success"></i></a> | <a href="<?php echo base_url();?>uploads/fulfiment/<?php echo $order->shipping_list;?>/"><i data-toggle="tooltip"  data-placement="bottom" title="Download Shipping list" class="fas fa-file-excel fa-2x text-success"></i></a>
							                                		</td>
																</tr>
																<?php endforeach ?>											
															</tbody>
														</table>
													</div>
											</div>
											<div class="tab-pane fade" id="pills-profile-nobd" role="tabpanel" aria-labelledby="pills-profile-tab-nobd">
												<div class="table-responsive">
														<table id="" class="display table table-striped table-bordered table-hover table-head-bg-dark" >
															<thead>
																<tr>
																	<th>#</th>
																	<th>Quantity</th>
																	<th>Assets</th>
																	<th>Domicile Terminal</th>
																	<th>Velcro</th>
																	<th>Shipping address</th>
																	<th>Instruction</th>
																	<th>Action</th>
																</tr>
															</thead>
															<tbody>
																<?php $i=0; foreach($orders as $k => $order): ?>
																<?php if (in_array($order->status, ['0', '2', '3', '4', '-1'])) { continue; }?>
																<tr>
																	<td><?php echo ++$i ?></td>
																	<td><?php echo $order->quantity ?></td>
																	<td><?php echo ucwords($order->assets) ?> Owned</td>
																	<td><?php echo $order->domicile_terminal ?></td>
																	<td><?php echo $order->velcro ?></td>
																	<td><?php echo $order->shipping_address ?></td>
																	<td><?php echo ($order->instructions) ? ($order->instructions) : '<center>-</center>';?></td>
																	<td><a href="<?php echo base_url();?>uploads/fulfiment/<?php echo $order->asset_file;?>"><i data-toggle="tooltip" data-placement="bottom" title="Download Asset File" class="fas fa-file-excel fa-2x text-success"></i></a> | <a href="<?php echo base_url();?>uploads/fulfiment/<?php echo $order->shipping_list;?>"><i data-toggle="tooltip"  data-placement="bottom" title="Download Shipping list" class="fas fa-file-excel fa-2x text-success"></i></a>
							                                		</td>
																</tr>
																<?php endforeach ?>											
															</tbody>
														</table>
													</div>
											</div>
											<div class="tab-pane fade" id="pills-contact-nobd" role="tabpanel" aria-labelledby="pills-contact-tab-nobd">
												<div class="table-responsive">
														<table id="" class="display table table-striped table-bordered table-hover table-head-bg-dark" >
															<thead>
																<tr>
																	<th>#</th>
																	<th>Quantity</th>
																	<th>Assets</th>
																	<th>Domicile Terminal</th>
																	<th>Velcro</th>
																	<th>Shipping address</th>
																	<th>Instruction</th>
																	<th>Action</th>
																</tr>
															</thead>
															<tbody>
																<?php $i=0; foreach($orders as $k => $order): ?>
																<?php if (in_array($order->status, ['0', '1', '3', '4', '-1'])) { continue; }?>
																<tr>
																	<td><?php echo ++$i ?></td>
																	<td><?php echo $order->quantity ?></td>
																	<td><?php echo ucwords($order->assets) ?> Owned</td>
																	<td><?php echo $order->domicile_terminal ?></td>
																	<td><?php echo $order->velcro ?></td>
																	<td><?php echo $order->shipping_address ?></td>
																	<td><?php echo ($order->instructions) ? ($order->instructions) : '<center>-</center>';?></td>
																	<td><a href="<?php echo base_url();?>uploads/fulfiment/<?php echo $order->asset_file;?>"><i data-toggle="tooltip" data-placement="bottom" title="Download Asset File" class="fas fa-file-excel fa-2x text-success"></i></a> | <a href="<?php echo base_url();?>uploads/fulfiment/<?php echo $order->shipping_list;?>"><i data-toggle="tooltip"  data-placement="bottom" title="Download Shipping list" class="fas fa-file-excel fa-2x text-success"></i></a>
							                                		</td>
																</tr>
																<?php endforeach ?>											
															</tbody>
														</table>
													</div>
											</div>
											<div class="tab-pane fade" id="pills-ship-nobd" role="tabpanel" aria-labelledby="pills-contact-tab-nobd">
												<div class="table-responsive">
														<table id="" class="display table table-striped table-bordered table-hover table-head-bg-dark" >
															<thead>
																<tr>
																	<th>#</th>
																	<th>Quantity</th>
																	<th>Assets</th>
																	<th>Domicile Terminal</th>
																	<th>Velcro</th>
																	<th>Shipping address</th>
																	<th>Instruction</th>
																	<th>Action</th>
																</tr>
															</thead>
															<tbody>
																<?php $i=0; foreach($orders as $k => $order): ?>
																<?php if (in_array($order->status, ['0', '1', '2', '4', '-1'])) { continue; }?>
																<tr>
																	<td><?php echo ++$i ?></td>
																	<td><?php echo $order->quantity ?></td>
																	<td><?php echo ucwords($order->assets) ?> Owned</td>
																	<td><?php echo $order->domicile_terminal ?></td>
																	<td><?php echo $order->velcro ?></td>
																	<td><?php echo $order->shipping_address ?></td>
																	<td><?php echo ($order->instructions) ? ($order->instructions) : '<center>-</center>';?></td>
																	<td><a href="<?php echo base_url();?>uploads/fulfiment/<?php echo $order->asset_file;?>"><i data-toggle="tooltip" data-placement="bottom" title="Download Asset File" class="fas fa-file-excel fa-2x text-success"></i></a> | <a href="<?php echo base_url();?>uploads/fulfiment/<?php echo $order->shipping_list;?>"><i data-toggle="tooltip"  data-placement="bottom" title="Download Shipping list" class="fas fa-file-excel fa-2x text-success"></i></a>
							                                		</td>
																</tr>
																<?php endforeach ?>											
															</tbody>
														</table>
													</div>
											</div>
											<div class="tab-pane fade" id="pills-receive-nobd" role="tabpanel" aria-labelledby="pills-receive-tab-nobd">
												<div class="table-responsive">
														<table id="" class="display table table-striped table-bordered table-hover table-head-bg-dark" >
															<thead>
																<tr>
																	<th>#</th>
																	<th>Quantity</th>
																	<th>Assets</th>
																	<th>Domicile Terminal</th>
																	<th>Velcro</th>
																	<th>Shipping address</th>
																	<th>Instruction</th>
																	<th>Action</th>
																</tr>
															</thead>
															<tbody>
																<?php $i=0; foreach($orders as $k => $order): ?>
																<?php if (in_array($order->status, ['0', '1', '2', '3', '-1'])) { continue; }?>
																<tr>
																	<td><?php echo ++$i ?></td>
																	<td><?php echo $order->quantity ?></td>
																	<td><?php echo ucwords($order->assets) ?> Owned</td>
																	<td><?php echo $order->domicile_terminal ?></td>
																	<td><?php echo $order->velcro ?></td>
																	<td><?php echo $order->shipping_address ?></td>
																	<td><?php echo ($order->instructions) ? ($order->instructions) : '<center>-</center>';?></td>
																	<td><a href="<?php echo base_url();?>uploads/fulfiment/<?php echo $order->asset_file;?>"><i data-toggle="tooltip" data-placement="bottom" title="Download Asset File" class="fas fa-file-excel fa-2x text-success"></i></a> | <a href="<?php echo base_url();?>uploads/fulfiment/<?php echo $order->shipping_list;?>"><i data-toggle="tooltip"  data-placement="bottom" title="Download Shipping list" class="fas fa-file-excel fa-2x text-success"></i></a>
							                                		</td>
																</tr>
																<?php endforeach ?>											
															</tbody>
														</table>
													</div>
											</div>
											<div class="tab-pane fade" id="pills-draft-nobd" role="tabpanel" aria-labelledby="pills-draft-tab-nobd">
												<div class="table-responsive">
														<table id="" class="display table table-striped table-bordered table-hover table-head-bg-dark" >
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
																<?php if (in_array($order->status, ['0', '1', '2', '3',  '4'])) { continue; }?>
																<tr>
																	<td><?php echo ++$i ?></td>
																	<td><?php echo $order->quantity ?></td>
																	<td><?php echo ucwords($order->assets) ?> Owned</td>
																	<td><?php echo $order->domicile_terminal ?></td>
																	<td><?php echo $order->velcro ?></td>
																	<td><?php echo $order->shipping_address ?></td>
																	<td><?php echo ($order->instructions) ? ($order->instructions) : '<center>-</center>';?></td>
																	<td><a href="<?php echo base_url();?>uploads/fulfiment/<?php echo $order->asset_file;?>"><i data-toggle="tooltip" data-placement="bottom" title="Download Asset File" class="fas fa-file-excel fa-2x text-success"></i></a> | <a href="<?php echo base_url();?>uploads/fulfiment/<?php echo $order->shipping_list;?>"><i data-toggle="tooltip"  data-placement="bottom" title="Download Shipping list" class="fas fa-file-excel fa-2x text-success"></i></a>
							                                		</td>
							                                		<td><a onclick="edit_transponder(<?php echo $order->fulfilment_id;?>)" data-toggle="tooltip" data-placement="bottom" title="Edit order"><i class="flaticon-pen fa-2x text-info"></i>
                                                            		</a>| <a onclick="undo(<?php echo $order->fulfilment_id;?>)" title = "Delete order"><span><i class="fa fa-trash fa-2x text-danger"></i></span></a>
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
</div>
<?php $this->load->view('frontend/includes/footer_start'); ?>

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
 
  var url="<?php echo base_url();?>";
    function undo(id, client, dept){
       var r=confirm("Are you sure you want to delete the order?")
        if (r==true)
          window.location = url+"frontend/member/delete_fulfilment/"+id;
        else
          return false;
        } 
 function edit_transponder(id){
          window.location = url+"frontend/member/edit_transponder/"+id;
        } 
</script>
<?php $this->load->view('frontend/includes/footer_end'); ?>