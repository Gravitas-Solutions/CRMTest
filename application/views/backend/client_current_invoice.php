<?php $this->load->view('templates/includes/auth_header_start'); ?>
<?php $this->load->view('templates/includes/auth_header_end'); ?>
<div class="content">
    <div class="page-inner">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-head-row">
                            <div class="card-title"><i class="fa fa-file-excel"></i> Current Total Month Invoice</div>
                            <div class="card-tools">
                                <a href="<?php echo base_url()?>admin/invoice_listing" class="btn btn-primary btn-xs"><i class="fa fa-file"></i> View Invoice</a>
                                <a href="<?php echo base_url()?>admin/dashboard"><i class="fa fa-arrow-circle-left"></i> </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
					<?php echo $this->session->flashdata('message'); ?>					
					<?php echo form_open_multipart(base_url('admin/import'), ['id' => 'excel_form']); ?>
						<div class="form-group">
							<div class="col-md-1">
								<label class="control-label">Client:</label>
							</div>
							<div class="col-md-2">
								<select name="client" class="form-control input-sm">
									<option value="" selected="selected"  disabled="disabled">--Client--</option>
									<?php foreach ($clients as $client) {?>
									<option value="<?php echo $client->organization;?>"><?php echo ucwords(str_replace('_', ' ', $client->organization));?></option>
									<?php }?>
								</select>
							</div>
						</div>
					<div class="form-group">
						<div class="col-md-1">
							<label class="control-label">Dept:</label>
						</div>
						<div class="col-md-2">
							<select name="dept" class="form-control input-sm">
								<option value="" selected="selected"  disabled="disabled">--Select Client First--</option>
							</select>
						</div>
					</div>

						<div class="form-group">
							<div class="col-md-1">
								<label class="control-label">File:</label>
							</div>
							<div class="col-md-1">
								<input type="file" name="excel_data" id="excel_data" />
							</div>
							<div class="col-md-3">
								<input type="submit" name="upload" class="btn btn-xs btn-default pull-right" value="Upload" />
							</div>
						</div>
					</form>
					<br><hr>
					<a href="<?php echo base_url()?>uploads/templates/general.xlsx" class="btn btn-xs btn-default"><i class="fa fa-file-excel"></i> General template <i class="fa fa-download"></i></a>
					<a href="<?php echo base_url()?>uploads/templates/amazon.xlsx" class="btn btn-xs btn-default"><i class="fa fa-file-excel"></i> Amazon template <i class="fa fa-download"></i></a>
					</div>
				</div> <!-- End of div panel body -->
			</div> <!-- End of panel panel default -->
		</div>
	</div>
</div>
<?php $this->load->view('templates/includes/footer_start'); ?>
<script type="text/javascript">
	$('select[name="client"]').on('change', function() {
        var client = $(this).val();
        var url = "<?php echo base_url('backend/admin/departments')?>/"+client;

        if(client) {
            $.ajax({
                url: url,
                type: "GET",
                dataType: "json",
                success:function(data) {
                   if (data.length > 0) {
                     $('select[name="dept"]').empty();
                    $.each(data, function(key, value) {
                        $('select[name="dept"]').append('<option value="'+ value.dept_id +'">'+ value.dept_name +'</option>');
                    });
                    }else{
                        $('select[name="dept"]').empty();
                        $('select[name="dept"]').append('<option value="">No department(s) found for selected client </option>');
                    }
                }
            });
        }
    });

    $('#excel_form').on('submit', function(){
    	if ($('#excel_data').val() == '') {
    		alert('Select file to upload');
    		return false;
    	}
    });
</script>
<?php $this->load->view('templates/includes/footer_end'); ?>