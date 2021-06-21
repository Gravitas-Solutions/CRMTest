<?php $this->load->view('frontend/includes/header_start'); ?>
<?php $this->load->view('frontend/includes/header_end_demo'); ?>
	<div class="content">
		<div class="page-inner">
			<div class="row">
				<div class="col-md-12">
					<div class="card">
						<div class="card-header">
							<div class="card-head-row">
								<h4 class="card-title"><i class="fas fa-truck-pickup"></i> Transponder Fulfilment</h4>
								<div class="card-tools">                                      
									<a type="button" href="<?php echo base_url('transponder-fulfilment') ?>" class="btn btn-info btn-xs"><i class="fa fa-eye"></i> View Orders </a>
                                </div>
                            </div>
							</div>
							<div class="card-body">
								<div class="col-md-12">
								<div class="card">
									<div class="card-body">
										<h4>Edit Transponder</h4>
										<hr>
										<div id="transponder_msg"></div>
								        <form action="" id="order_transponder_form" class="form-horizontal" method="POST">
								            <input type="hidden" name="fulfilment_id" value="<?php echo $fulfilment->fulfilment_id?>"  />
								            <div class="form-group">
								                <div class="row">
									                <div class="col-md-2"><label class="control-label">Quantity: </label></div>
									                <div class="col-md-4">
									                    <input name="quantity"  value="<?php echo $fulfilment->quantity?>" placeholder="Transponder Quantity" class="form-control input-sm" type="number" step="1" />
									                </div>
									                <div class="col-md-2"><label class="control-label">Shipping address: </label></div>
									                <div class="col-md-4">
									                    <input name="shipping_address" value="<?php echo $fulfilment->shipping_address?>" placeholder="Specify shipping address" class="form-control input-sm" type="text" />
									                </div>
								                </div>
								            </div>
								            <div class="form-group">
								                <div class="row">
								                    <div class="col-md-2"><label class="control-label">Extra velcro: </label></div>
									                <div class="col-md-4">
									                    <input name="velcro" placeholder="Enter extra velcro" value="<?php echo $fulfilment->velcro?>" class="form-control input-sm" type="number" step="1" />
									                </div>
									                <div class="col-md-2"><label class="control-label">Shipping List:</label></div>
													<div class="col-md-3"><input name="shipping_list" value="<?php echo $fulfilment->shipping_address?>" type="file" /></div>
													<div class="col-md-1"><a href="<?php echo base_url();?>/uploads/fulfiment/<?php echo $fulfilment->shipping_list;?>" ><i class="fa fa-file-excel fa-2x text-success" title="Download Shipping list"></i> </a></div>
							                    </div>
							                </div>
								            <div class="form-group">
								                <div class="row">
									                 <div class="col-md-2"><label class="control-label">Assets: </label></div>
								                    <div class="col-md-4">
								                        <select name="assets" class="form-control input-sm">
								                            <option value="" selected="selected" disabled="disabled">Select Asset type</option>
								                            <option value="afp" <?php if ($fulfilment->assets == 'afp') echo 'selected' ; ?>>AFP Owned</option>
								                            <option value="rental" <?php if ($fulfilment->assets == 'rental') echo 'selected' ; ?>>RENTAL Owned</option>
								                        </select>
								                    </div>
								                    <div class="col-md-2"><label class="control-label">Domicile Terminal: </label></div>
									                <div class="col-md-4">
									                    <input name="domicile_terminal" value="<?php echo $fulfilment->velcro?>" placeholder="Domicile Terminal" class="form-control input-sm" type="text"/>
									                </div>
									            </div>
								            </div>
								             <div class="form-group">
								                <div class="row">
								                <div class="col-md-2"><label class="control-label">Asset file:</label></div>
													<div class="col-md-3"><input name="asset_file" value="<?php echo $fulfilment->asset_file?>" type="file" /></div>
													<div class="col-md-1"><a href="<?php echo base_url();?>/uploads/fulfiment/<?php echo $fulfilment->asset_file;?>" ><i class="fa fa-file-excel fa-2x text-success" title="Download Asset File"></i> </a></div>
													<?php if ($has_sub_depts) {?>
									                   	<div class="col-md-2"><label class="control-label">Cost Center:</label></div>
									                    <div class="col-md-4">
									                        <select name="dept" class="form-control input-sm">
									                            <option value="" selected="selected"  disabled="disabled">--Cost Unit--</option>
									                            <?php foreach ($departments as $cd) {?>
									                   			 <option value="<?php echo $cd->dept_id;?>"  <?php echo  set_select('dept', $cd->dept_id);?> <?php if ($cd->dept_id == $fulfilment->dept_id) echo 'selected' ; ?>><?php echo ucwords($cd->dept_name);?></option>
									                            <?php }?>
									                        </select>                     
									                    </div>
									            <?php }?>
								            	</div>
								            </div>
								            <div class="form-group">
								                <div class="row">
									                <div class="col-md-2"><label class="control-label">Instruction:</label></div>
													<div class="col-md-8"><textarea placeholder="Give aditional instructions here..." value="" name="instructions" rows="4" cols="70"><?php echo $fulfilment->instructions?>
													</textarea></div>
									            </div>
								            </div>
								            <div class="form-group">
								            	<div class="row">
								            		<div class="col-md-3 ml-auto mr-auto">
								            			<button onclick="save(-1)" class="btn btn-sm btn-block btn-secondary"><strong><i class="fa fa-save"></i> Save Order</strong></button> 
								            		</div>
								            		<div class="col-md-3 ml-auto mr-auto">
								            			<button onclick="save(0)" class="btn btn-sm btn-block btn-primary"><strong><i class="fa fa-save"></i> Submit Order</strong></button>
								            		</div>
									        	</div>
								            </div>
								        </form>
									</div>
								</div>
							</div>
						</div>
					</div>
			</div>
		</div>
	</div>
</div>
<?php $this->load->view('frontend/includes/footer_start'); ?>
<script >
   /*DataTables*/
$(document).ready(function() {
    $('table.display').DataTable({
    	dom: 'Blfrtip',
      buttons: [
          {
              extend:    'excelHtml5',
              text:      '<i class="fa fa-file-excel fa-2x"></i>',
              titleAttr: 'Export to Excel',
              exportOptions: {
                            columns: "thead th:not(.noExport)"
                        }
          },
          {
              extend:    'pdfHtml5',
              text:      '<i class="fa fa-file-pdf fa-2x"></i>',
              titleAttr: 'Export to PDF',
              orientation: 'portrait', 
              pageSize: 'A4',
                  /*customize: function() {
                       $('#table_id')
                           .addClass('compact');
                   },*/
                  exportOptions: {
                                columns: "thead th:not(.noExport)"
                            }
          },
          {
            extend:    'print', 
            text:      '<i class="fa fa-print fa-2x"></i>',
            titleAttr: 'Print',
            orientation: 'portrait', 
            pageSize: 'A4',
                /*customize: function(win) {
                     $(win.document.body).find('table')
                         .addClass('compact')
                         .css('font-size', '10pt');
                 },*/
                exportOptions: {
                              columns: "thead th:not(.noExport)"
                          }
          },
          {
            extend:    'colvis', 
            text:      '<i class="fa fa-eye fa-2x"></i>',
            titleAttr: 'Visible columns'
          }
      ]
    });
} );

function save(status){
	var fulfilment_data = new FormData($('#order_transponder_form')[0]);
    var url;
    var url2="<?php echo base_url();?>";
    url = "<?php echo site_url('frontend/member/update_transponder')?>/"+status;
    $.ajax({
        url : url,
        type: 'POST',
        dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: fulfilment_data,
        success: function(data){
            if(data.status){
                $('#transponder_msg').removeClass('text-danger').addClass('text-success').html(data.msg);
                setTimeout(function(){
                    window.location = url2+"frontend/member/transponders/";
                }, 3000);
            }
            else{
                $('#transponder_msg').removeClass('text-success').addClass('text-danger').html(data.msg);
            }
        }
    });
}
$('#order_transponder_form').on('submit', function(e) {
    e.preventDefault();
    e.stopPropagation(); // only neccessary if something above is listening to the (default-)event too
});
</script>
<?php $this->load->view('frontend/includes/footer_end'); ?>