<?php $this->load->view('templates/includes/auth_header_start'); ?>
<?php $this->load->view('templates/includes/auth_header_end'); ?>
<div class="content">
    <div class="page-inner">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-head-row">
                            <div class="card-title"> <i class="fas fa-chart-bar"></i> Dump Statistics</div>
                            <div class="card-tools">
                            	<a href="<?php echo base_url()?>backend/admin/excel_listing" class="btn btn-primary btn-outline btn-xs"><i class="fa fa-chevron-circle-left"></i> Transactions Dumps</a>
                               <a onclick="javascript:history.go(-1);"><i class="fas fa-step-backward fa-1x"></i> </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                    	<div class="row">
			                <div class="col-md-8">
			                	<div class="table-responsive">
				               <table id="basic-datatables" class="display table table-striped table-bordered table-hover table-head-bg-primary" >
				                	<caption class="text-muted">Daily Transactions dump statistics for <strong>[<?php echo date('F, Y'); ?>]</strong></caption>
				                    <thead>
				                        <th>#</th>
				                        <th>Agent</th>
				                        <th>Upload Date </th>
				                         <th>Dumps</th>
				                    </thead>
				                    <tbody>
				                        <?php $i = 0; foreach ($dump_stats as $ds) {?>
				                        <tr>
				                            <td><?php echo ++$i; ?></td>
				                            <td><a href="mailto:<?php echo $ds->email; ?>"><?php echo $ds->email; ?></a></td>
				                            <td><?php echo nice_date($ds->uploaded_date, 'l, F j, Y'); ?></td>
				                            <td><?php echo number_format($ds->count, 0) ?></td>
				                        </tr>
				                        <?php } ?>
				                    </tbody>
				                </table>
			                </div>
			                </div>
			                <div class="col-md-4">
			                    <div class="panel panel-default">
			                        <div class="panel-heading text-center" style="font-weight: 800; font-size: 1.2em; color: ">Summary</div>
			                        <div class="panel-body">
			                            <div id="dump-stats-chart" style="height: 260px"></div>
			                        </div>
			                    </div>
			                </div>
			               </div>
			           </div>
            		</div>
        		</div><!-- card -->
        	</div>
    </div>
</div>
<?php $this->load->view('templates/includes/footer_start'); ?>
<?php $this->load->view('templates/includes/footer_end'); ?>