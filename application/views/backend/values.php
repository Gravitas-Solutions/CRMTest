<?php $this->load->view('templates/includes/auth_header_start'); ?>
<?php $this->load->view('templates/includes/auth_header_end'); ?>
<div class="content">
        <div class="page-inner">
            <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-head-row">
                                    <div class="card-title"><i class="fab fa-vaadin"></i> Values</div>
                                    <div class="card-tools">
                                       View by: 
                            			<a title="Date" data-toggle="modal" data-target="#valueModal" class="btn btn-info btn-xs"><i class="fa fa-clock-o"></i> Specific Day
                                        <a onclick="javascript:history.go(-1);"><i class="fas fa-step-backward fa-1x"></i> </a>
                                    </div>
                                </div>
                            </div>
				<div class="table-responsive">
				<table id="basic-datatables" class="display table table-striped table-bordered table-hover table-head-bg-primary" >
                    <caption><h4 class="text-muted">Showing values for: [<?php echo $breadcrumb; ?>]</h4></caption>
					<thead>
						<th>Date</th>
						<th  class="noExport">Value</th>
					</thead>
					<tbody>
						<?php foreach ($values as $v) {?>
						<tr>
							<td><?php echo date('l, F jS, Y', strtotime($v->create_date));?></td>
							<td><?php echo '$ '.number_format($v->toll_amount, 2);?></td>
						</tr>
						<?php } ?>
					</tbody>
					<tfoot>
						<th>Date</th>
						<th>Value</th>
					</tfoot>
				</table>
				</div>
			</div>
		</div>
		</div>
	</div>
</div>
<?php $this->load->view('templates/includes/footer_start'); ?>
<!-- By Date Modal-->
    <div class="modal fade" id="valueModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" id="exampleModalLabel">Toll Amount by Date</h4>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body form">
          	<?php echo form_open('admin/values', ['method' => 'POST']);?>
		        <div class="form-group">
		        	<div class="col-md-8">
		        		<input type="text" placeholder="Select date" name="value_day" class="form-control datepicker input-sm" />
		        	</div>
		        <div class="col-md-2">
		        	<button class="btn btn-success btn-sm" type="submit"><i class="fa fa-list-alt"></i> Show Values</button>
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