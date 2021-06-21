<?php $this->load->view('frontend/includes/header_start'); ?>
<?php $this->load->view('frontend/includes/header_end_demo'); ?>
<div class="content">
	<div class="page-inner">
			<div class="row">
				<div class="col-md-12">
					<div class="card">
						<div class="card-header">
								<div class="card-head-row">
									<div class="card-title"><i class="fas fa-clipboard-list"></i> Disputed Transaction</div>
										<div class="card-tools">
		                                <a href="<?php echo base_url('transactions') ?>" class="btn btn-info btn-xs"><i class="fas fa-money-check-alt"></i> Transactions</a>
	                            	</div>
	                        	</div>
							</div>
						<div class="card-body">
							<div class="table-responsive">
								<table id="basic-datatables" class="display table table-striped table-bordered table-hover table-head-bg-dark" >
									<thead  style="background-color: #EBEBEB;">
										<th>#</th>
										<th><abbr title="License Plate">LP</abbr></th>
										<?php if(isset($org)){?>
				                        <th>DNT</th>
				                        <th>VIN</th>
				                        <?php }?>
										<th>State</th>
										<th>Agency</th>
										<th>Exit Date/Time</th>
										<th>Exit Lane</th>
										<th>Exit Location</th>
										<th>Amount</th>
										<th>Action</th>
									</thead>
									<tbody>
										<?php $i = 0; $total = 0; foreach (array_unique($transactions, SORT_REGULAR) as $transaction) {
										/*<?php $total = 0; foreach ($transactions as $transaction) {*/
											$total += number_format($transaction->toll, 2); ?>
										<tr>
										<?php if(isset($transaction->exit_name)){?>
											<td><?php echo ++$i; ?></td>
											<td><?php echo $transaction->license_plate;?></td>
											<td><?php echo $transaction->state_code;?></td>
											<td><?php echo ucwords($transaction->agency_name);?></td>
											<td><?php echo nice_date($transaction->exit_date_time, 'Y-m-d H:i');?></td>
											<td><center> - </center></td>
											<td><?php echo ucwords($transaction->exit_name);?></td>
											<td><?php echo '$'.number_format($transaction->toll, 2);?></td>
										<?php }else{?>
											<td><?php echo ++$i; ?></td>
											<td><?php echo $transaction->license_plate;?></td>
											<?php if(isset($org)){?>
					                        <td><?php echo $transaction->tolltag;?></td>
					                        <td><?php echo ($transaction->vin_no) ? ucwords($transaction->vin_no) : '<center>-</center>';?></td>
					                        <?php }?>
											<td><?php echo $transaction->state_code;?></td>
											<td><?php echo ucwords($transaction->agency_name);?></td>
											<td><?php echo nice_date($transaction->exit_date_time, 'Y-m-d H:i');?></td>
											<td><?php echo ucwords($transaction->exit_lane);?></td>
											<td><?php echo ucwords($transaction->exit_location);?></td>
											<td><?php echo '$'.number_format($transaction->toll, 2);?></td>
										<?php } ?>
										<td><button type="button" class="btn btn-icon btn-round btn-info" data-toggle="tooltip" data-placement="bottom" title="Resolve Dispute" onclick="dispute_status(<?php echo $transaction->invoice_id;?>, '<?php echo $client;?>')"><i class="fas fa-check-circle"></i></button></td>
										<!-- <td><button type="button" onclick="dispute_status(<?php echo $transaction->invoice_id;?>)" class="btn btn-icon btn-round btn-info" data-toggle="tooltip" data-placement="bottom" title="Edit Vehicle"><i class="flaticon-pen"></i>
                                                            </button></td> -->
										</tr>
										<?php } ?>
									</tbody>
									<tfoot>
										<th>#</th>
										<th><abbr title="License Plate">LP</abbr></th>
										<?php if(isset($org)){?>
				                        <th>DNT</th>
				                        <th>VIN</th>
				                        <?php }?>
										<th>State</th>
										<th>Agency</th>
										<th>Exit Date/Time</th>
										<th>Exit Lane</th>
										<th>Exit Location</th>
										<th>Amount</th>				
										<th>Action</th>				
									</tfoot>
								</table>
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

function dispute_status(id, client){
	var r=confirm("Are you sure you want to resolve the dispute?")
	 if (r==true)
        $.ajax({
            url : "<?php echo site_url('frontend/member/resolve_dispute')?>/"+id+"/"+client,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                alert('Changed dispute status successfully');
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
<?php $this->load->view('frontend/includes/footer_end'); ?>