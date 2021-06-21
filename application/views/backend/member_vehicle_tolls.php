<?php $this->load->view('templates/includes/auth_header_start'); ?>
<?php $this->load->view('templates/includes/auth_header_end'); ?>
<div class="content">
        <div class="page-inner">
            <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-head-row">
                                    <div class="card-title"><i class="fas fa-car"></i> Vehicle-wise Toll for &laquo;<a href="mailto:<?php echo $member_mail;?>"><?php echo $member_mail;?></div>
                                    <div class="card-tools">
                                        <a onclick="javascript:history.go(-1);"><i class="fas fa-step-backward fa-1x"></i> </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
				<div class="table-responsive">
					<table id="table_id" class="table table-condensed table-hover table-bordered">
						<caption>
							<h4 class="text-muted">Showing results for: [<?php echo $breadcrumb; ?>]</h4>
						</caption>
						<thead>
							<th>#</th>
							<th>Vehicle License Plate</th>
							<th>Today's Toll Amount</th>
						</thead>
						<tbody>
							<?php $i = 0; $total = 0; 
							foreach ($vehicles as $vehicle) { 
								$i++; 
								$total += $vehicle->toll_amount; ?>
							<tr>
								<td><?php echo $i; ?></td>
								<td><?php echo $vehicle->license_plate;?></td>
								<td><?php echo '$'.number_format($vehicle->toll_amount, 2);?></td>
							</tr>
							<?php } ?>
						</tbody>
						<tfoot>
							<th>#</th>
							<th>Exit Location</th>
							<th>Today's Toll Amount<span class="pull-right text-muted">Total value: $<?php echo number_format($total, 2);?></span></th>					
						</tfoot>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<?php $this->load->view('templates/includes/footer_start'); ?>
<?php $this->load->view('templates/includes/footer_end'); ?>