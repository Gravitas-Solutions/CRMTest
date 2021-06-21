
<?php $this->load->view('templates/includes/auth_header_start'); ?>
<?php $this->load->view('templates/includes/auth_header_end'); ?>
<div class="content">
        <div class="page-inner">
            <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-head-row">
                                    <div class="card-title"><i class="fa fa-car"></i> Vehicle Details</div>
                                    <div class="card-tools">
                                        <a onclick="javascript:history.go(-1);"><i class="fas fa-step-backward fa-1x"></i> </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="col-md-10 col-md-offset-1">
                                	<table id="basic-datatables" class="display table table-striped table-bordered table-hover table-head-bg-primary" >
                                		<thead>
                                			<th>Vehicle Parameter</th>
                                			<th>Value</th>
                                		</thead>
                                		<tbody>
                                			<tr><td id="fa">Client:</td><td><?php echo ucwords($vehicle->organization);?></td></tr>
                                			<tr><td id="fa">License Plate:</td><td><?php echo $vehicle->license_plate;?></td></tr>
                                			<tr><td id="fa">Model:</td><td><?php echo ucwords($vehicle->model);?></td></tr>
                                			<tr><td id="fa">Make:</td><td><?php echo ucwords($vehicle->make);?></td></tr>
                                			<tr><td id="fa">Color:</td><td><?php echo ucwords($vehicle->color);?></td></tr>
                                			<tr><td id="fa">Number of Axles:</td><td><?php echo $vehicle->axles;?></td></tr>
                                			<tr><td id="fa">Tag Type:</td><td><?php echo ucwords($vehicle->tag_type);?></td></tr>
                                			<tr><td id="fa">Unit #:</td><td><?php echo $vehicle->unit;?></td></tr>
                                			<tr><td id="fa">Store:</td><td><?php echo ucwords($vehicle->store);?></td></tr>
                                			<tr><td id="fa">Location:</td><td><?php echo ucwords($vehicle->location);?></td></tr>
                                			<tr><td id="fa">Start Date:</td><td><?php echo nice_date($vehicle->start_date, 'Y-m-d h:i:s');?></td></tr>
                                			<tr><td id="fa">End Date:</td><td><?php echo ($vehicle->end_date == '0000-00-00 00:00:00') ? ' - ' : nice_date($vehicle->end_date, 'Y-m-d h:i:s');?></td></tr>
                                			<tr><td id="fa">Status</td><td><button class="<?php echo ($vehicle->vehicle_status) ? 'btn btn-success btn-xs':'btn btn-info btn-xs';?>" onclick="activate_vehicle(<?php echo $vehicle->vehicle_id;?>)" title = "Change status"><span><i class="fa fa-refresh"></i>  <?php echo ($vehicle->vehicle_status) ? 'Active':'Inactive';?></span></button></td></tr>
                                		</tbody>
                                		<tfoot>
                                			<th>Vehicle Parameter</th>
                                			<th>Value</th>                			
                                		</tfoot>
                                	</table>
                                </div>
                            </div><!-- .panel-body -->
                        </div><!-- .panel-default -->
                    </div>
                </div>
	</div><!-- #page-wrapper -->
</div><!-- #wrapper -->
<?php $this->load->view('templates/includes/footer_start'); ?>
<script type="text/javascript">
function activate_vehicle(id){
    $.ajax({
        url : "<?php echo site_url('backend/admin/activate_vehicle')?>/"+id,
        type: "POST",
        dataType: "JSON",
        success: function(data)
        {
            alert('Status change success');
            location.reload();
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error changing vehicle status');
        }
    });
}
</script>
<?php $this->load->view('templates/includes/footer_end'); ?>