<?php $this->load->view('templates/includes/auth_header_start'); ?>
<?php $this->load->view('templates/includes/auth_header_end'); ?>
	<div class="content">
		<div class="page-inner">
			<div class="row">
					<div class="col-md-12">
						<div class="card">
							<div class="card-header">
								<div class="card-head-row">
									<div class="card-title"><i class="fas fa-car"></i> Manage Vehicles</div>
									<div class="card-tools">
                                        <a href="<?php echo base_url()?>backend/admin/vehicle_dump" class="btn btn-secondary btn-xs"><i class="fa fa-upload"></i> Upload Vehicles</a>
										<a class="btn btn-info btn-border btn-sm mr-2" onclick="add_vehicle()"><i class="fa fa-plus-circle"></i> Add Vehicle</a>
										<a class="btn btn-warning btn-outline btn-xs" href="<?php echo base_url()?>backend/admin/client_vehicle_uploads"><i class="fa fa-car"></i> Client Uploads</a>
									</div>
								</div>
							</div>
							<div class="card-body">                                   
                                    <div class="card">
                                            <div class="card-header">
                                                <div class="card-head-row">
                                                    <!-- <div class="card-title"><h5> <?php echo 'Vehicles for: &laquo;<em>'.$breadcrumb; ?></em>&raquo;</h5></div> -->
                                                    <div class="card-tools">
                                                         <a title="Filter by Status" data-toggle="modal" data-target="#statusModal" class="btn btn-primary btn-border btn-xs"> By status</a> | <a title="Filter by Client" data-toggle="modal" data-target="#clientModal" class="btn btn-info btn-border btn-xs"> By client</a>
                                                    </div>
                                                </div>
                                            </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table id="vehicle-datatables" class="display table table-striped table-bordered table-hover table-head-bg-primary" >
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>License Plate</th>
                                                            <th>Color</th>
                                                            <th>Make</th>
                                                            <th>Model</th>
                                                            <th>Unit #</th>
                                                            <th>DNT #</th>
                                                            <th>VIN #</th>
                                                            <th>Store</th>
                                                            <th>State</th>
                                                            <th>Reg. Year</th>
                                                            <th>Start Date</th>
                                                            <th>End Date</th>
                                                            <th>Owner</th>
                                                            <th>Department</th>
                                                            <th>Status</th>
                                                            <th class="noExport">Action</th>
                                                        </tr>
                                                    </thead>
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

<?php $this->load->view('templates/includes/footer_start'); ?>
<script type="text/javascript">

	$(document).ready(function(){
        $('select[name="member"]').on('change', function() {
        $('select[name="department"]').parent().parent().css('display', 'none');
        var member = $(this).val();

        if(member) {
            $.ajax({
                url: "<?php echo base_url('backend/admin/client_sub_depts')?>/"+member,
                type: "GET",
                dataType: "json",
                success:function(data) {
                   if (data) {
                        $.ajax({
                            url: "<?php echo base_url('backend/admin/vehicle_departments')?>/"+member,
                            type: "GET",
                            dataType: "json",
                            success:function(data) {
                               if (data.length > 0) {
                                    $('select[name="department"]').empty();
                                    $.each(data, function(key, value) {
                                        $('select[name="department"]').append('<option value="'+ value.dept_id +'">'+ value.dept_name +'</option>');  
                                        $('select[name="department"]').parent().parent().css('display', 'block');                      
                                    });
                                    if(data.length > 1){
                                        $("select[name='department'] option:contains('overview')").remove();
                                    }
                                }else{
                                    $('select[name="department"]').empty();
                                    $('select[name="department"]').append('<option value="">No department(s) found for selected client </option>');
                                }
                            }
                        });
                    }else{

                    }
                }
            });
        }
    });

        $('select[name="client"]').on('change', function() {
        var client = $(this).val();
        $('select[name="dept"]').empty().hide(); 
        if(client) {
            $.ajax({
                url: "<?php echo base_url('backend/admin/vehicle_departments')?>/"+client,
                type: "GET",
                dataType: "json",
                success:function(data) {
                        $.each(data, function(key, value) {
                            $('select[name="dept"]').append('<option value="'+ value.dept_id +'">'+ value.dept_name +'</option>'); 
                            $('select[name="dept"]').show();                   
                        });
                        if(data.length > 1){
                            $("select[name='dept'] option:contains('overview')").remove();
                        }
                }
            });
        }
    });

    $('select[name="dept"]').on('change', function() {
        $('select[name="sub_dept"]').empty().hide(); 
        var dept_id = $(this).val(); 
        var url = "<?php echo base_url('backend/admin/dept_sub_departments')?>/"+dept_id;
        if(dept_id) {
            $.ajax({
                url: url,
                type: "GET",
                dataType: "json",
                success:function(data) {
                    console.log(data);
                   if (data.length > 0) {
                        $('select[name="sub_dept"]').append('<option value="">-- Select sub-dept --</option>');
                        $.each(data, function(key, value) {
                            $('select[name="sub_dept"]').append('<option value="'+ value.sub_dept_id +'">'+ value.sub_dept_name +'</option>');
                            $('select[name="sub_dept"]').show();
                        });
                        if(data.length > 1){
                            $("select[name='sub_dept'] option:contains('overview')").remove();
                        }
                    }else{
                        $('select[name="sub_dept"]').empty();
                        $('select[name="sub_dept"]').append('<option value="">No sub-department(s) found for selected department </option>');
                    }
                }
            });
        }
    }); 
     var dataTable = $('#vehicle-datatables').DataTable({
            'responsive': true,
            columnDefs: [
                { responsivePriority: 1, targets: 0 },
                { responsivePriority: 10001, targets: 4 },
                { responsivePriority: 2, targets: -2 }
            ],
            dom: 'Blfrtip',
                buttons: [
                    {
                        extend:    'excelHtml5',
                        text:      '<i class="fa fa-file-excel"></i>',
                        titleAttr: 'Export to Excel',
                        exportOptions: {
                              columns: "thead th:not(.noExport)"
                          },
                        footer: true
                    },
                    {
                        extend:    'pdfHtml5',
                        text:      '<i class="fa fa-file-pdf"></i>',
                        titleAttr: 'Export to PDF',
                        orientation: 'portrait', 
                        pageSize: 'A4',
                        exportOptions: {
                            columns: "thead th:not(.noExport)"
                          },
                        footer: true
                    },
                    {
                      extend:    'print', 
                      text:      '<i class="fa fa-print"></i>',
                      titleAttr: 'Print',
                      orientation: 'portrait', 
                      pageSize: 'A4',
                      exportOptions: {
                          columns: "thead th:not(.noExport)"
                        },
                      footer: true
                    }
                ],
            lengthMenu: [10, 50, 100, 150, 200],
            pageLength: 10,
            "pagingType": "full",
            "processing": true,
            "serverSide": true,
            "ajax":{
             "url": "<?php echo base_url('backend/admin/posts') ?>",
             "dataType": "json",
             "type": "POST",
             "data": function(data){  
                            data.status = $('#status').val();
                            data.member = $('#member').val();
                            data.department = $('#department').val();
                            data.<?php echo $this->security->get_csrf_token_name(); ?> = "<?php echo $this->security->get_csrf_hash(); ?>"; }
                           },
            "columns": [
                      { "data": "vehicle_id" },
                      { "data": "license_plate" },
                      { "data": "color" },
                      { "data": "make" },
                      { "data": "model" },
                      { "data": "unit" },
                      { "data": "tolltag" },
                      { "data": "vin_no" },
                      { "data": "store" },
                      { "data": "location" },
                      { "data": "year" },
                      { "data": "start_date" },
                      { "data": "end_date" },
                      { "data": "organization" },
                      { "data": "dept_name" },
                      { "data": "status" },
                      { "data": "action" },
                   ]


        });
      $('#search').on('click change', function (event) {
        event.preventDefault();

        if($('#status').val()=="")
        {
            $('#status').focus();
        }
        else
        {
            $('#statusModal').modal('hide');
            dataTable.draw();
        }

    } );

    $('#member_show').on('click change', function (event) {
        event.preventDefault();

        if($('#member').val()=="")
        {
            $('#member').focus();
        }
        else
        {
            $('#clientModal').modal('hide');
            dataTable.draw();
        }

    } );
 
});

$(document).keypress(function(e){
    if (e.which == 13){
        $("#vehicleSave").click();
        return false;
    }
});

function add_vehicle(){
    save_method = 'add';
    $('#new_vehicle')[0].reset();
    $('#msg').html(''); 
    $('#vehicleModal').modal('show'); 
    $('select[name="sub_dept"]').empty().hide(); 
    $('select[name="dept"]').empty().hide(); 
    $('.modal-title').text('Add Vehicle'); 
}

function edit_vehicle(id){
    save_method = 'update';
    $('#new_vehicle')[0].reset(); 
    $('select[name="sub_dept"]').empty().hide();
    $('#msg').html('');

    $.ajax({
        url : "<?php echo site_url('backend/admin/edit_vehicle')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            if(data.status){
                $('[name="id"]').val(data.vehicle.vehicle_id);
                $('[name="license_plate"]').val(data.vehicle.license_plate);
                $('[name="model"]').val(data.vehicle.model);
                $('[name="make"]').val(data.vehicle.make);
                $('[name="color"]').val(data.vehicle.color);
                $('select[name="axles"] option').prop('selected', false).filter(function(){
                    return $(this).val() == data.vehicle.axles;
                }).prop('selected', true);
                $('[name="store"]').val(data.vehicle.store);
                $('select[name="location"] option').prop('selected', false).filter(function(){
                    return $(this).val() == data.vehicle.location;
                }).prop('selected', true);
                $('[name="tagtype"]').val(data.vehicle.tag_id);
                $('[name="start_date"]').val(data.vehicle.start_date);
                (data.vehicle.end_date == '0000-00-00') ? $('[name="end_date"]').val(''): $('[name="end_date"]').val(data.vehicle.end_date);
                $('[name="unit"]').val(data.vehicle.unit);
                $('[name="year"]').val(data.vehicle.year);                            
                $('[name="tolltag"]').val(data.vehicle.tolltag);
                $('[name="vin_no"]').val(data.vehicle.vin_no);
                 $('input:radio[name="status"][value="' + data.vehicle.vehicle_status + '"]').prop("checked", true);
                $('select[name="client"] option').prop('selected', false).filter(function(){
                    return $(this).val() == data.vehicle.client_id;
                }).prop('selected', true);
                var dept = data.vehicle.dept_id;
                var sub_dept = data.vehicle.sub_dept_id;
                if(dept !== null){
                     $.ajax({
                        url: "<?php echo base_url('backend/admin/vehicle_departments')?>/"+data.vehicle.client_id,
                        type: "GET",
                        dataType: "json",
                        success:function(data) {
                                $.each(data, function(key, value) {
                                    $('select[name="dept"]').append('<option value="'+ value.dept_id +'">'+ value.dept_name +'</option>');                        
                                });
                                if(data.length > 1){
                                    $("select[name='dept'] option:contains('overview')").remove();
                                }
                                $('select[name="dept"]').show(); 
                                $('select[name="dept"] option').prop('selected', false).filter(function(){
                                    return $(this).val() == dept; }).prop('selected', true);
                        }
                    }); 
                }
                if(sub_dept !== null){
                     $.ajax({
                        url: "<?php echo base_url('backend/admin/dept_sub_departments')?>/"+dept,
                        type: "GET",
                        dataType: "json",
                        success:function(data) {
                                $.each(data, function(key, value) {
                                    $('select[name="sub_dept"]').append('<option value="'+ value.sub_dept_id +'">'+ value.sub_dept_name +'</option>');
                                });
                                if(data.length > 1){
                                    $("select[name='sub_dept'] option:contains('overview')").remove();
                                }
                                $('select[name="sub_dept"]').show(); 
                                $('select[name="sub_dept"] option').prop('selected', false).filter(function(){
                                    return $(this).val() == sub_dept; }).prop('selected', true);
                        }
                    });  
                }
                $('#vehicleModal').modal('show'); 
                $('#vehicleSave').text('Update Vehicle');
                $('.modal-title').text('Update Vehicle Details');
            }else{
                alert(data.msg);
            }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}

function save(){
    $('#vehicleSave').text('saving...'); 
    $('#vehicleSave').attr('disabled',true); 
    var url;

    if(save_method == 'add') {
        url = "<?php echo site_url('backend/admin/add_vehicle')?>";
    } else {
        url = "<?php echo site_url('backend/admin/update_vehicle')?>";
    }
    
        $.ajax({
            url : url,
            type: "POST",
            data: $('#new_vehicle').serialize(),
            dataType: "JSON",
            success: function(data)
            {
                if(data.status){
                    $('#vehicleModal').modal('hide');
                    alert(data.msg);
                    location.reload();
                }
                else{
                    $('#vehicle_msg').removeClass('text-success').addClass('text-danger').html(data.msg);
                }
                $('#vehicleSave').text('Save Vehicle'); 
                $('#vehicleSave').attr('disabled',false); 
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error adding / update data');
                $('#vehicleSave').text('Save Vehicle'); 
                $('#vehicleSave').attr('disabled',false);  
            }
        });
}

function activate_vehicle(id){
        $.ajax({
            url : "<?php echo site_url('backend/admin/activate_vehicle')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                if (data.status) {
                    alert(data.msg);
                    location.reload();
                } else{
                    alert(data.msg);
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error changing vehhicle status');
            }
        });
}

function delete_vehicle(id){
    if(confirm('Are you sure you want to delete this data?'))
    {
        $.ajax({
            url : "<?php echo site_url('backend/admin/delete_vehicle')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                alert('Vehicle deleted successfully');
                location.reload();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error deleting data');
            }
        });
    }
}
</script>
     <div class="modal fade" id="vehicleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog  modal-lg" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="vehicleModalLongTitle">Add Vehicle</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	       <div class="modal-body form">
            <div id="vehicle_msg"></div>
            <form action="#" class="form-horizontal" id="new_vehicle" method="POST" accept-charset="utf-8">
            <input type="hidden" value="" name="id"/> 
               <div class="form-group">
                <div class="row">
                    <div class="col-6 col-sm-6">
                    	<div class="input-icon">
							<span class="input-icon-addon">
								<i class="fas fa-car-alt"></i>
							</span>
							<input type="text" class="form-control" name="license_plate" placeholder="License Plate" oninput="this.value = this.value.toUpperCase()" />
						</div>
                    </div>
                    <div class="col-6 col-sm-6">
                    	<div class="input-icon">
							<span class="input-icon-addon">
								<i class="fab fa-monero"></i>
							</span>
							<input type="text" class="form-control" name="model" placeholder="Model">
						</div>
                    </div>
                </div>
                </div>
                <div class="form-group">
                    <div class="row">
                    <div class="col-6 col-sm-6">
                    	<div class="input-icon">
							<span class="input-icon-addon">
								<i class="fas fa-brush"></i>
							</span>
							<input type="text" class="form-control"  name="color" placeholder="Color"/>
						</div>
                    </div>
                    <div class="col-6 col-sm-6">
                    	<div class="input-icon">
							<span class="input-icon-addon">
								<i class="fas fa-cogs"></i>
							</span>
							<input type="text" class="form-control" name="make" placeholder="Make"/>
						</div>
                    </div>
                </div>
                </div>
                <div class="form-group">
                   <div class="row">
                    <div class="col-6 col-sm-6">
                        <select name="axles" class="form-control input-sm">
                            <option selected="selected" disabled="disabled" value="">--Select Axles--</option>
                            <option value="2_axles">Two axles</option>
                            <option value="3_axles">Three axles</option>
                            <option value="4_axles">Four axles</option>
                            <option value="5_axles">Five axles</option>
                            <option value="6_axles">Six axles</option>
                            <option value="7_axles">Seven axles</option>
                            <option value="8_axles">Eight axles</option>
                        </select>
                    </div>
                    <div class="col-6 col-sm-6">
                        <select name="tagtype" class="form-control input-sm">
                            <option selected="selected" disabled="disabled" value="">--Select Tag Type--</option>
                            <option value="1">Trailer</option>
                            <option value="2">Dealer</option>
                            <option value="3">Etag</option>
                            <option value="4">Cab</option>
                        </select>
                    </div>
                </div>
            </div>
                <div class="form-group">
                   <div class="row">
                    <div class="col-6 col-sm-6">
                        <div class="input-icon">
							<span class="input-icon-addon">
								<i class="fa fa-calendar"></i>
							</span>
							<input type='text' name="start_date" id='start_date' class="form-control input-sm"  placeholder="Start date"/>
						</div>
                    </div>
                    <div class="col-6 col-sm-6">
                        <div class="input-icon">
							<span class="input-icon-addon">
								<i class="fa fa-calendar"></i>
							</span>
							<input type='text' name="end_date" id='end_date' class="form-control input-sm" placeholder="End date"/>
						</div>
                    </div>
                </div>
                 </div>
                <div class="form-group">
                    <div class="row">
	                    <div class="col-6 col-sm-6">
	                        <input name="store" placeholder="Store" class="form-control input-sm" type="text"/>
	                    </div>
	                   <div class="col-6 col-sm-6">
	                        <select name="location" class="form-control input-sm">
	                           <option value="" selected="selected"  disabled="disabled">--Store Location--</option>
                            <?php foreach ($states as $s) {?>
                            <option value="<?php echo $s->state_code;?>"><?php echo ucwords($s->state_name);?></option>
                            <?php }?>
	                        </select> 
	                    </div>
                 </div>
                </div>
                <div class="form-group">
                    <div class="row">
                    <div class="col-6 col-sm-6">
                        <input name="year" placeholder="Year" class="form-control input-sm" type="text"/>
                    </div> 
                    <div class="col-6 col-sm-6">
                        <input name="unit" placeholder="Unit" class="form-control input-sm" type="text"/>
                    </div>
                </div>
                </div>
                <div class="form-group">
                    <div class="row">
                    <div class="col-6 col-sm-6">
                    	<div class="input-icon">
							<span class="input-icon-addon">
								<i class="fa fa-user"></i>
							</span>
							<input type="text" class="form-control" name="tolltag" placeholder="Tolltag ID"/>
						</div>                      
                    </div>   
                    <div class="col-6 col-sm-6">
                    	<div class="input-icon">
							<span class="input-icon-addon">
								<i class="fa fa-user"></i>
							</span>
							<input type="text" class="form-control" name="vin_no" placeholder="VIN Number"/>
						</div>                      
                    </div>
                </div>
                 </div>
                 <div class="form-group">
                    <div class="row">
                        <div class="col-md-6">
                            <select name="client" class="form-control input-sm">
                                <option value="" selected="selected"  disabled="disabled">--Client--</option>
                                <?php foreach ($clients as $client) {?>
                                <option value="<?php echo $client->id;?>"><?php echo ucwords(str_replace('_', ' ', $client->organization));?></option>
                                <?php }?>
                            </select>                     
                        </div>  
                        <div class="col-md-6">
                            <select name="dept" class="form-control input-sm"></select>
                        </div>
                    </div>
                 </div>
                 <div class="form-group">
                   <div class="row">
                        <div class="col-2 col-sm-2"><label class="control-label">Status: <span class="text-danger">*</span></label></div>
                        <div class="col-md-8">
                                <div class="form-check">
                                    <label class="form-radio-label">
                                        <input class="form-radio-input" type="radio" name="status" value="1" checked="checked">
                                        <span class="form-radio-sign">Active</span>
                                    </label>
                                    <label class="form-radio-label ml-3">
                                        <input class="form-radio-input" type="radio" name="status" value="2">
                                        <span class="form-radio-sign">Start</span>
                                    </label>
                                    <label class="form-radio-label ml-3">
                                        <input class="form-radio-input" type="radio" name="status" value="0">
                                        <span class="form-radio-sign">Maintenance</span>
                                    </label>
                                    <label class="form-radio-label ml-3">
                                        <input class="form-radio-input" type="radio" name="status" value="3">
                                        <span class="form-radio-sign">End</span>
                                    </label>
                                    <label class="form-radio-label ml-3">
                                        <input class="form-radio-input" type="radio" name="status" value="-1">
                                        <span class="form-radio-sign">Inactive</span>
                                    </label>
                                </div>
                            </div>
                    </div>
                </div>
                <div class="form-group">
                   <div class="row">
                    <div class="col-6 col-sm-6">
                        <select name="sub_dept" class="form-control input-sm"></select>
                    </div>
                </div>
                </div>
                
            </form>
          </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
	        <button type="button" class="btn btn-primary"  id="vehicleSave" onclick="save()">Save</button>
	      </div>
	    </div>
	  </div>
	</div>

<!-- By Status Modal -->
    <div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" id="exampleModalLabel">Vehicles by Status</h4>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            <form class="form-inline"  method="post" >
                <div class="form-group">
                    <div class="col-md-8">
                        <select name="status" id="status" class="form-control input-sm">
                            <option value="" selected="selected"  value="-2" disabled="disabled">--Select Client--</option>
                            <option value="1">Active</option>
                            <option value="2">Start</option>
                            <option value="0">Maintenance</option>
                            <option value="3">End</option>
                            <option value="-1">Inactive</option>
                        </select>
                    </div>
                </div>
              </div>
              <div class="clearfix"></div>
              <div class="modal-footer"><button class="btn btn-primary btn-sm" id="search" type="submit"><i class="fa fa-list-alt"></i> Show Vehicles</button></div>
           </form>
        </div>
      </div>
    </div>

    <!-- By Member Modal -->
    <div class="modal fade" id="clientModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" id="exampleModalLabel">Vehicles by Client</h4>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            <form class="form-inline"  method="post" >
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-7">
                            <select name="member" id="member" class="form-control input-sm">
                                <option value="" selected="selected"  value="0" disabled="disabled">--Select Client--</option>
                                <?php foreach ($clients as $client) { ?>
                                    <option value="<?php echo $client->id;?>"><?php echo ucwords(str_replace('_', ' ', $client->organization)).' &raquo; '.$client->email;?></option>
                                <?php }?>
                            </select>
                        </div>
                        <div class="col-md-5" style="display: none">
                            <div class="col-md-12">
                                <select name="department" id="department" class="form-control input-sm">
                                    <option value="" selected="selected"  disabled="disabled">--Select Client First--</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
          </div>
          <div class="clearfix"></div>
          <div class="modal-footer"><button class="btn btn-primary btn-sm" type="submit"  id="member_show" ><i class="fa fa-list-alt"></i> Show Vehicles</button></div>
          </form>
        </div>
      </div>
    </div>
<?php $this->load->view('templates/includes/footer_end'); ?>