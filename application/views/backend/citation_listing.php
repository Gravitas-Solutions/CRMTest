<?php $this->load->view('templates/includes/auth_header_start'); ?>
<?php $this->load->view('templates/includes/auth_header_end'); ?>
<div class="content">
    <div class="page-inner">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-head-row">
                            <div class="card-title"><i class="fas fa-exclamation-triangle"></i> Citation Management</div>
                            <div class="card-tools">
                                <button type="button" class="btn btn-primary btn-xs" onclick="add_citation()" style="margin-left: 20px;"><i class="fa fa-plus-circle"></i> Add Citation </button>
                                <button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#import_citations_modal"><i class="fa fa-file-excel-o"></i> Import Citations </button>
                                <a onclick="javascript:history.go(-1);"><i class="fas fa-step-backward fa-1x"></i> </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                        <table id="basic-datatables" class="display table table-striped table-bordered table-hover table-head-bg-primary" >
                            <thead>
                                <th>Violation #</th>
                                <th>Client</th>
                                <th>Department</th>
                                <th>Vehicle LP</th>
                                <th>LP State</th>
                                <th>Violation State</th>
                                <th>Payable To</th>
                                <th>Type</th>
                                <th>Citation Date</th>
                                <th>Citation Amount</th>
                                <th>Citation Fee</th>
                                <th>Paid Amount</th>
                                <th>Pay Date</th>
                                <th>Status</th>
                                <th  class="noExport">Edit</th>
                            </thead>
                            <tbody>
                                <?php foreach ($citations as $citation) {?>
                                <tr>
                                    <td><?php echo $citation->violation_no ?></td>
                                    <td><?php echo ucwords(str_replace('_', ' ', $citation->organization));?></td>
                                    <td><?php echo ($citation->dept_name) ? ucwords(str_replace('_', ' ', $citation->dept_name)) : "<center>--</center>";?></td>
                                    <td><?php echo strtoupper($citation->license_plate);?></td>
                                    <td><?php echo strtoupper($citation->license_plate_state);?></td>
                                    <td><?php echo strtoupper($citation->violation_state);?></td>
                                    <td><?php echo ucwords($citation->payable_to);?></td>
                                    <td>
                                    <?php switch ($citation->citation_type) {
                                        case 'ST': echo 'Speed Ticket'; break;
                                        case 'RL': echo 'Red Light'; break;                                    
                                        default: echo 'Parking'; break;
                                    } ?>                                    
                                    </td>
                                    <td><?php echo $citation->violation_date;?></td>
                                    <td><?php echo '$'.$citation->citation_amount;?></td>
                                    <td><?php echo '$'.$citation->citation_fee;?></td>
                                    <td><?php echo (($citation->paid_amount == 0.00) ? '-' : '$'.$citation->paid_amount);?></td>
                                    <td><?php echo ($citation->paid_date == '0000-00-00 00:00:00') ? '-' : nice_date($citation->paid_date, 'Y-m-d h:i:s');?></td>
                                    <td><?php echo ($citation->citation_status) ? 'Closed' : 'Open'; ?></td>
                                    <td><button class="btn btn-warning btn-xs" onclick="edit_citation(<?php echo $citation->citation_id ?>)" title = "Edit citation details"><i class="fa fa-edit"></i></button>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        </div>
                    </div><!-- panel-body -->
                </div><!-- panel panel-primary -->
            </div>
        </div>
    </div>
</div>

<!-- Add citation modal-->
<div class="modal fade" id="add_citation_modal"  role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Add citation</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="citation_msg" style="padding-bottom: 10px"></div>
        <form action="" id="add_citation_form" class="form-horizontal">
            <input type="hidden" name="citation_id" value="" />
            <div class="form-group">
                <div class="row">
                <div class="col-md-2"><label class="control-label">Client: </label></div>
                <div class="col-md-4">
                    <select name="client" class="form-control input-sm" style="width: 100%">
                        <option selected="selected" disabled="disabled" value="">--Select client--</option>
                        <?php foreach ($clients as $client) {?>
                        <option value="<?php echo $client->organization;?>"><?php echo ucwords(str_replace('_', ' ', $client->organization));?></option>
                        <?php }?>
                    </select>
                </div>
                <div class="col-md-2"><label class="control-label">Dept: </label></div>
                <div class="col-md-4">
                    <select name="dept" class="form-control input-sm" style="width: 100%">
                        <option value="" selected="selected"  disabled="disabled">--Select client first--</option>
                    </select>
                </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-2"><label class="control-label"><abbr title="License Plate">LP</abbr>: </label></div>
                    <div class="col-md-4">
                        <select name="vehicle" class="form-control input-sm" style="width: 100%">
                            <option value="">--Select client/dept first--</option>
                        </select>
                    </div>
                    <div class="col-md-2"><label class="control-label"><abbr title="License Plate">LP</abbr> State: </label></div>
                    <div class="col-md-4">
                        <select name="license_plate_state" class="form-control input-sm" style="width: 100%">
                            <option value="" selected disabled>--License Plate state--</option>
                            <?php foreach ($states as $state) {?>
                            <option value="<?php echo $state->state_code;?>"><?php echo ucwords($state->state_name);?></option>
                            <?php }?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                <div class="col-md-2"><label class="control-label">Violation State: </label></div>
                <div class="col-md-4">
                    <select name="violation_state" class="form-control input-sm" style="width: 100%">
                        <option value="" selected disabled>--Select violation state--</option>
                        <?php foreach ($states as $state) {?>
                        <option value="<?php echo $state->state_code;?>"><?php echo ucwords($state->state_name);?></option>
                        <?php }?>
                    </select>
                </div>
                <div class="col-md-2"><label class="control-label">Violation N<sup>o</sup>: </label></div>
                <div class="col-md-4">
                    <input name="violation_no" placeholder="Violation number" class="form-control input-sm" type="text" />
                </div>
            </div>
            </div>
            <div class="form-group">
                <div class="row">
                <div class="col-md-2"><label class="control-label">Citation Type: </label></div>
                <div class="col-md-4">
                    <select name="type" class="form-control input-sm">
                        <option value="" selected disabled>--Select citation type--</option>
                        <option value="ST">Speed Ticket</option>
                        <option value="RL">Red Light</option>
                        <option value="PK">Parking</option>
                    </select>
                </div>
                <div class="col-md-2"><label class="control-label">Citation Status: </label></div>
                <div class="col-md-4">
                    <select name="citation_status" class="form-control input-sm">
                        <option value="">-- Select citation status</option>
                        <option value="0">Open</option>
                        <option value="1">Closed</option>
                    </select>
                </div>
            </div>
            </div>
            <div class="form-group">
                <div class="row">
                <div class="col-md-2"><label class="control-label">Citation Date: </label></div>
                <div class="col-md-4">
                    <div class='input-group date' id="citation_date">
                        <input type='text' name="violation_date" class="form-control input-sm" placeholder="Violation date" />
                        <div class="input-group-append">
                            <span class="input-group-text" id="basic-addon2"><i class="fa fa-calendar"></i></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-2"><label class="control-label">Citation Amount: </label></div>
                <div class="col-md-4">
                    <input name="citation_amount" placeholder="Citation amount" class="form-control input-sm" type="number" step="0.01" />
                </div>
            </div>
            </div>
            <div class="form-group">
                <div class="row">
                <div class="col-md-2"><label class="control-label">Fees Amount: </label></div>
                <div class="col-md-4">
                    <input name="fees_amount" placeholder="Fees amount" class="form-control input-sm" type="number" step="0.01" />
                </div>
                <div class="col-md-2"><label class="control-label">Paid Amount: </label></div>
                <div class="col-md-4">
                    <input name="paid_amount" placeholder="Paid amount" class="form-control input-sm" type="number" step="0.01" />
                </div>
            </div>
            </div>
            <div class="form-group">
                <div class="row">
                <div class="col-md-2"><label class="control-label">Payee: </label></div>
                <div class="col-md-4">
                    <input name="payable_to" placeholder="Payable to" class="form-control input-sm" type="text" />
                </div>
                <div class="col-md-2"><label class="control-label">Pay Date: </label></div>
                <div class="col-md-4">
                    <div class='input-group date' id="paid_date">
                        <input type='text' name="paid_date" class="form-control input-sm" placeholder="Paid date" />
                        <span class="input-group-addon">
                            <span class="fa fa-calendar"></span>
                        </span>
                    </div>
                </div>
            </div>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button class="btn btn-warning btn-sm" data-dismiss="modal"><i class="fa fa-remove"></i> Cancel</button>
        <button class="btn btn-success btn-sm" onclick="save()"><i class="fa fa-save"></i> Save Citation</button>
      </div>
    </div>
  </div>
</div>

<!-- Import citations modal-->
<div class="modal fade" id="import_citations_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Import citations</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="feedback" class="text-center" style="font-weight: 600; padding-bottom: 10px"></div>
        <form action="" id="citations_import_form" class="form-horizontal" enctype="multipart/form-data">
            <div class="form-group">
                <div class="row">
                <div class="col-md-4"><label class="control-label">Client:</label></div>
                <div class="col-md-6">
                    <select name="client_import" class="form-control input-sm">
                        <option selected="selected" disabled="disabled" value="">--Select client--</option>
                        <?php foreach ($clients as $client) {?>
                        <option value="<?php echo $client->organization;?>"><?php echo ucwords(str_replace('_', ' ', $client->organization));?></option>
                        <?php }?>
                    </select>
                </div>
            </div>
            </div>
            <div class="form-group">
                <div class="row">
                <div class="col-md-4"><label class="control-label">Citations data file:</label></div>
                <div class="col-md-6">
                    <input type="file" name="citations_file" />
                    <span class="help-block">Allowed file type: .xls or .xlsx</span>
                </div>
            </div>
        </div>
        </form>
      </div>
      <div class="modal-footer">
        <button class="btn btn-warning btn-sm" type="button" data-dismiss="modal"><i class="fa fa-remove"></i> Cancel</button>
        <button class="btn btn-success btn-sm" id="upload_citations"><i class="fa fa-upload"></i> Upload Citations</button>
      </div>
    </div>
  </div>
</div>

<?php $this->load->view('templates/includes/footer_start'); ?>
<script type="text/javascript">
$(document).ready(function() {

    // Change vehicles on client change
    $('select[name="dept"]').on('change', function() {
        $('select[name="vehicle"]').empty().append('<option value="">--Select department--</option>');
        var dept = $(this).val();
        var url = "<?php echo base_url('backend/admin/dept_citation_vehicles')?>/"+dept;
        if(dept) {
            $.ajax({
                url: url,
                type: "GET",
                dataType: "json",
                success:function(data) {
                    console.log(data);
                   if (data.length > 0) {
                     $('select[name="vehicle"]').empty();
                    $.each(data, function(key, value) {
                        $('select[name="vehicle"]').append('<option value="'+ value.license_plate +'">'+ value.license_plate +'</option>');
                    });
                    }else{
                        $('select[name="vehicle"]').empty();
                        $('select[name="vehicle"]').append('<option value="">No vehicle(s) found for selected dept </option>');
                    }
                }
            });
        }
    });

    $('select[name="client"]').on('change', function() {
        $('select[name="dept"]').empty().append('<option value="">Selected client does not have department(s)</option>');
        $('select[name="vehicle"]').empty().append('<option value="">--Select department--</option>');
        var client = $(this).val();

        if(client) {
            $.ajax({
                url: "<?php echo base_url('backend/admin/org_departments')?>/"+client,
                type: "GET",
                dataType: "json",
                success:function(data) {
                   if (data.length > 0) {
                        $('select[name="dept"]').empty();
                        $.each(data, function(key, value) {
                            $('select[name="dept"]').append('<option value="'+ value.dept_id +'">'+ value.dept_name +'</option>');                      
                        });
                        /*if(data.length > 1){
                            $("select[name='dept'] option:contains('overview')").remove();
                        }*/
                    }else{
                        $('select[name="dept"]').empty().append('<option value="">Selected client does not have department(s)</option>');
                    }
                }
            });
        }
    });
});

// Single citation
function add_citation(){
    save_method = 'add';
    $('#add_citation_form')[0].reset();
    $('#citation_msg').html(''); 
    $('#add_citation_modal').modal('show'); 
    $('.modal-title').text('Add Citation'); 
}

function edit_citation(id){
    save_method = 'update';
    $('#add_citation_form')[0].reset(); 
    $('#citation_msg').html('');

    $.ajax({
        url : "<?php echo site_url('backend/admin/edit_citation')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data){
            if(data.status){
                $('[name="citation_id"]').val(data.msg.citation_id);
                $('[name="client"]').val(data.msg.organization);
                $('select[name="vehicle"]').empty().append('<option value="'+ data.msg.license_plate +'">'+ data.msg.license_plate +'</option>');
                $('select[name="license_plate_state"] option').prop('selected', false).filter(function(){
                    return $(this).val() == data.msg.license_plate_state;
                }).prop('selected', true);
                $('select[name="license_plate_state"]').append('<option value="'+ data.msg.license_plate_state +'">'+ data.msg.license_plate_state +'</option>');
                $('[name="violation_no"]').val(data.msg.violation_no);
                $('[name="violation_date"]').val(data.msg.violation_date);
                $('[name="paid_date"]').val(data.msg.paid_date);
                $('[name="citation_amount"]').val(data.msg.citation_amount);
                $('[name="fees_amount"]').val(data.msg.citation_fee);
                $('[name="paid_amount"]').val(data.msg.paid_amount);
                $('select[name="type"] option').prop('selected', false).filter(function(){
                    return $(this).val() == data.msg.citation_type;
                }).prop('selected', true);
                $('[name="citation_status"]').val(data.msg.citation_status);
                $('[name="payable_to"]').val(data.msg.payable_to);
                $('select[name="violation_state"] option').prop('selected', false).filter(function(){
                    return $(this).val() == data.msg.violation_state;
                }).prop('selected', true);

                $('.modal-title').text('Edit Citation');
                $('#add_citation_modal').modal('show');
            }else{
                alert(data.msg);
            }
        }
    });
}

function save(){
    var url;
    if(save_method == 'add') {
        url = "<?php echo site_url('backend/admin/save_citation')?>";
    } else {
        url = "<?php echo site_url('backend/admin/update_citation')?>";
    }
    $.ajax({
        url : url,
        type: "POST",
        data: $('#add_citation_form').serialize(),
        dataType: "JSON",
        success: function(data){
            if(data.status){
                $('#citation_msg').removeClass('text-danger').addClass('text-success').html(data.msg);
                setTimeout(function(){
                    $('#add_citation_modal').modal('hide');
                    location.reload();
                }, 3000);
            }
            else{
                $('#citation_msg').removeClass('text-success').addClass('text-danger').html(data.msg);
            }
        }
    });
}

// Citations dump
$('#upload_citations').on('click', function(){
    if ($('#client').val() == '') {
        $('#feedback').removeClass('text-success').addClass('text-danger').html('Select client for citations');
        return false;
    } else{
        var citations = new FormData($('#citations_import_form')[0]);
        $.ajax({
            url: "<?php echo site_url()?>backend/admin/import_citations/",
            type: 'POST',
            dataType: 'json',
            data: citations,
            cache: false,
            contentType: false,
            processData: false,
            success: function(data){
                if (data.status) {
                    $('#feedback').removeClass('text-danger').addClass('text-success').html(data.msg);
                    setTimeout(function(){
                        $('#import_citations_modal').modal('hide');
                        location.reload();
                    }, 2000);
                } else{
                    $('#feedback').removeClass('text-success').addClass('text-danger').html(data.msg);
                };
            }
        }); 
    }   
});
</script>
<?php $this->load->view('templates/includes/footer_end'); ?>