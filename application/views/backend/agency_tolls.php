<?php $this->load->view('templates/includes/auth_header_start'); ?>
<?php $this->load->view('templates/includes/auth_header_end'); ?>
<div class="content">
    <div class="page-inner">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-head-row">
                            <div class="card-title"><i class="fa fa-institution"></i> Agency Toll [<?php echo date('Y-m-d')?>]</div>
                            
                        </div>
                    </div>
                    <div class="card-body">
						<div class="table-responsive">
							<table id="basic-datatables" class="display table table-striped table-bordered table-hover table-head-bg-primary" >
								<thead>
									<th>#</th>
									<th>Agency</th>
									<th>Toll Amount</th>
								</thead>
								<tbody>
									<?php $i = 0; $total = 0; foreach ($agency_tolls as $a_toll) {
										$total += $a_toll->agency_toll;
										?>
									<tr>
										<td><?php echo ++$i; ?></td>
										<td><?php echo ucwords($a_toll->agency_name);?></td>
										<td><?php echo '$'.number_format($a_toll->agency_toll,2);?></td>
									</tr>
									<?php } ?>
								</tbody>
								<tfoot>
									<th></th>
									<th></th>
									<th class="text-muted"><strong>Total Toll: $<?php echo number_format($total, 2)?></strong></th>					
								</tfoot>
							</table>
						</div>
					</div><!-- card body -->
				</div><!-- panel panel-primary -->
			</div>
		</div>
	</div>
</div>
<?php $this->load->view('templates/includes/footer_start'); ?>
<?php $this->load->view('templates/includes/footer_end'); ?>