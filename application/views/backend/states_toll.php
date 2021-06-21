<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div id="wrapper">
	<div id="page-wrapper">
		<div class="row">
		<div class="panel panel-default">
			<div class="panel-heading">
                <small class="pull-left">
                    <a href="<?php echo base_url()?>admin/dashboard"><i class="fa fa-arrow-circle-left  fa-2x"></i> </a>
                </small>&nbsp;&nbsp;
				<i class="fa fa-money"></i> States: Today's [<?php echo date('Y-m-d') ?>] Toll
			</div>
			<div class="panel-body">
				<div class="table-responsive">
				<table id="table_id" class="table table-condensed table-hover table-bordered">
					<thead>
						<th>#</th>
						<th>State</th>
						<th>Toll Amount</th>
					</thead>
					<tbody>
						<?php $i = 0; $total = 0; foreach ($states_toll as $state) {
							$total += $state->state_toll;
							?>
						<tr>
							<td><?php echo ++$i;?></td>
							<td><?php echo $state->state_name.' &raquo; '.$state->state_code;?></td>
							<td><?php echo '$'.number_format($state->state_toll, 2);?></td>
						</tr>
						<?php } ?>
					</tbody>
					<tfoot>
						<th>#</th>
						<th></th>
						<th><span class="pull-right text-muted"><strong>Total Toll: <?php echo '$'.number_format($total, 2); ?></strong></span></th>					
					</tfoot>
				</table>
				</div>
			</div><!-- panel-body -->
		</div><!-- panel panel-primary -->
		</div>
	</div>
</div>