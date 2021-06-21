<?php $this->load->view('templates/includes/auth_header_start'); ?>
<?php $this->load->view('templates/includes/auth_header_end'); ?>
<div class="content">
        <div class="page-inner">
            <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-head-row">
                                    <div class="card-title"><i class="fa fa-road "></i> Roads <em class="text-muted">[<?php echo ucwords(str_replace('_', ' ', $client)); ?>]</em> for [<?php echo $breadcrumb; ?>]</div>
                                    <div class="card-tools">
                                    	View by: 
                    					<i class="fa fa-calendar btn-primary btn-xs" title="Date" data-toggle="modal" data-target="#dateModal" style="cursor: pointer;"></i>
                                        <a onclick="javascript:history.go(-1);"><i class="fas fa-step-backward fa-1x"></i> </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
								<div class="table-responsive">
									<table id="table_id" class="table table-condensed table-hover table-bordered">
										<thead>
											<th>#</th>
											<th>Exit Location</th>
											<th  class="noExport">Toll Amount</th>
										</thead>
										<tbody>
											<?php $i = 0; $total = 0; 
											foreach ($roads as $road) { 
												$i++; 
												$total += $road->toll; ?>
											<tr>
												<td><?php echo $i; ?></td>
												<td><?php echo $road->exit_name;?></td>
												<td><?php echo '$'.$road->toll;?></td>
											</tr>
											<?php } ?>
										</tbody>
										<tfoot>
											<th>#</th>
											<th>Exit Location</th>
											<th>Toll Amount<span class="pull-right text-muted">Total value: $ <?php echo number_format($total, 2);?></span></th>					
										</tfoot>
									</table>
								</div>
							</div>
						</div>
					</div>
			</div>
		</div>
</div>
<?php $this->load->view('templates/includes/footer_start'); ?>

<!-- By Date Modal-->
    <div class="modal fade" id="dateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" id="exampleModalLabel">Roads Toll by Date</h4>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body form">
          	<?php echo form_open('admin/roads_by_date', ['method' => 'POST']);?>
          		<input type="hidden" name="client" value="<?php echo $client; ?>">
		        <div class="form-group">
		        	<div class="col-md-8">
		        		<div class='input-group date' id='road_day'>
                            <input type='text' name="road_day" class="form-control input-sm"  placeholder="yyyyy-mm-dd" />
                            <span class="input-group-addon">
                                <span class="fa fa-calendar"></span>
                            </span>
                        </div>
		        	</div>
		        <div class="col-md-2">
		        	<button class="btn btn-success btn-sm" type="submit"><i class="fa fa-list-alt"></i> Show Toll</button>
		        </div>
		    </div>
		    </form>
          </div>
          <div class="clearfix"></div>
          <div class="modal-footer"></div>
        </div>
      </div>
    </div>
    <?php $this->load->view('templates/includes/footer_end'); ?>