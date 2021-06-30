<?php $this->load->view('frontend/includes/header_start'); ?>
<?php $this->load->view('frontend/includes/header_end_demo'); ?>
<div class="content">
	<div class="page-inner">
			<div class="row">
				<div class="col-md-12">
					<div class="card">
							<div class="card-header">
								<div class="card-head-row">
									<div class="card-title"><h5><caption class="text-muted">
										Filtered for: <strong>Dept: </strong><?php echo $dept_name; ?>
										<?php echo isset($last_dump_date) ? ' | <strong>Month:</strong> '. nice_date($last_dump_date, 'F (1-j), Y') : '' ?>
										<?php echo isset($start_date) ? ' | <strong>Dates:</strong> '. nice_date($start_date, 'F j, Y') : '' ?><?php echo isset($end_date) ? "<strong> to </strong>". nice_date($end_date, 'F j, Y') : '' ?>
										</caption></h5>
									</div>
									<div class="card-tools">
	                                	<button data-toggle="modal" href='#date_range_modal' class="btn btn-outline btn-xs btn-info"><i class="fa fa-calendar"></i> View by Date range</button> | <a class="btn btn-info btn-xs" href="<?php echo base_url()?>frontend/member/disputes"><i class="fa fa-exclamation-circle"></i> View Disputes</a>
	                            	</div>
	                        	</div>
							</div>
						</div>
					</div>					
				</div>
			<div class="row">
				<div class="col-md-12">
					<div class="card">
						<div class="card-body">
							<div class="table-responsive">
								<table id="basic-datatables" class="display table table-striped table-bordered table-hover table-head-bg-dark" >
									<thead>
										<th><abbr title="License Plate">LP</abbr></th>
										<th>State</th>
										<th>Agency</th>
										<th>Exit Date/Time</th>
										<th>Exit Lane</th>
										<th>Exit Location</th>
										<th>Amount</th>
										<th>Action</th>
									</thead>
									
									<tfoot>
										<th><abbr title="License Plate">LP</abbr></th>
										<th>State</th>
										<th>Agency</th>
										<th>Exit Date/Time</th>
										<th>Exit Lane</th>
										<th>Exit Location</th>
										<th>Amount</th>				
										<th>Action</th>				
									</tfoot>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Date range modal selector -->
<div class="modal fade" id="date_range_modal">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title text-muted">Select department &amp; date range</h4>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			</div>
			<div class="modal-body">	
				<form class="form-inline"  method="post" >
	           		<div class="form-group" style="padding: 10px">
	           			<div class="row">
		           		<div class="col-md-1"><label class="control-label">Dept:</label></div> 
				          <div class="col-md-3">
				            <select name="member_dept" id="client_dept" class="form-control input-sm">
				              <?php if ($default_user) { ?>
				                <option value="0" <?php if($client_dept == 0){echo 'selected';}?>>All departments</option>
				              <?php } ?>
				              <?php foreach ($departments as $dept) {?>
				                 <?php if (strpos($dept->dept_name, 'overview') !== false) {
				                  continue;
				                }?>
				                <option value="<?php echo $dept->dept_id?>" <?php if($client_dept == $dept->dept_id){echo 'selected';}?>><?php echo $dept->dept_name;?></option>
				              <?php } ?>
				            </select>
				          </div>			        
	           			<div class="col-md-1"><label class="control-label" for="fromdate">From: </label></div>
	           			<div class="col-md-3">
		                    <div class='input-group date' id="transaction_start">
		                        <input type='text' id="start_date" name="start_date" value="<?php echo set_value('fromdate'); ?>" class="form-control input-sm" placeholder="Start date" />
		                         <div class="input-group-append">
		                            <span class="input-group-text" id="basic-addon2"><i class="fa fa-calendar"></i></span>
		                        </div>
		                    </div>
		                </div>
	           			<div class="col-md-1"><label class="control-label" for="todate">To: </label></div>
	           			<div class="col-md-3">
		                    <div class='input-group date' id="transaction_end">
		                        <input type='text' name="end_date" id="end_date" value="<?php echo set_value('todate'); ?>" class="form-control input-sm" placeholder="End date" />
		                        <div class="input-group-append">
		                            <span class="input-group-text" id="basic-addon2"><i class="fa fa-calendar"></i></span>
		                        </div>
		                    </div>
		                </div>
		                </div>
	           		</div>
	           		<div class="form-group" style="padding: 10px">
	           			<div class="col-md-12 text-muted">
	           				<i class="fa fa-info-circle"></i> <em>for specific-day transactions, use the same date for both start &amp; end date fields.</em>
	           			</div>
	           		</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning btn-outline btn-xs" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
				<button type="button" id="submit_btn" class="btn btn-primary btn-outline btn-xs"><i class="fa fa-eye"></i> View transactions</button>
			</div>
			</form>
		</div>
	</div>
</div>
<?php $this->load->view('frontend/includes/footer_start'); ?>
<script >
$(document).ready(function() {
	$('[data-toggle="tooltip"]').tooltip({trigger: 'hover'});

	$('#submit_btn').on('click', function(){
		if ($('input[name="start_date"]').val() == '' || $('input[name="end_date"]').val() == '') {
			alert('Select start and end dates');
			return false;
		}else{
			$('form').submit();
		}
	});


	$(function() {
		var dt_table = $('#basic-datatables').DataTable({
			'destroy': true,
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
			lengthMenu: [50, 100, 150, 200],
			pageLength: 10,
			"pagingType": "full",
			"processing": true,
	        "serverSide": true,
	        "ajax":{
			     "url": "<?php echo base_url('frontend/member/transaction_server_side') ?>",
			     "dataType": "json",
			     "type": "POST",
			     "data":function(data) {
					data.member_dept = $('#member_dept').val();
					data.client_dept = $('#client_dept').val();
		            data.from = $('#start_date').val();
		            data.to = $('#end_date').val();
				    data.<?php echo $this->security->get_csrf_token_name(); ?> = "<?php echo $this->security->get_csrf_hash(); ?>"; 
				}
	        },
		    "columns": [
			          { "data": "license_plate" },
			          { "data": "state_code" },
			          { "data": "agency_name" },
			          { "data": "exit_date_time" },
			          { "data": "exit_lane" },
			          { "data": "exit_location" },
			          { "data": "toll" },
			          { "data": "action" },
			       ],
			footerCallback: function ( row, data, start, end, display ) {
				var api = this.api(), data;
	 
				// Remove the '$' formatting to get integer data for summation
				var intVal = function ( i ) {
					return typeof i === 'string' ?
						i.replace(/[\$,]/g, '')*1 :
						typeof i === 'number' ?
							i : 0;
				};
	 
				var amountColumnIndex = $('th').filter(function(i){return $(this).text() == 'Amount';}).first().index();
		        var totalData= api.column(amountColumnIndex).data();
		        var total = totalData.reduce(function(a ,b){ return intVal(a) + intVal(b); }, 0) .toFixed(2);
		        var pageTotalData = api.column(amountColumnIndex, {page: 'current'}).data();
		        var pageTotal = pageTotalData.reduce(function(a,b){return intVal(a) + intVal(b);}, 0).toFixed(2);
		        var searchTotalData = api.column(amountColumnIndex, {'filter': 'applied'}).data();
		        var searchTotal = searchTotalData.reduce(function(a,b){return intVal(a) + intVal(b);}, 0).toFixed(2);
		        if (searchTotalData.length == totalData.length) {
		        	$( api.column(amountColumnIndex).footer() ).html('$'+ pageTotal +' of $'+ total);
		        } else{
		        	$( api.column(amountColumnIndex).footer() ).html('$'+ pageTotal +' of $'+ searchTotal);
		        }
			}
		});
		
		$('#submit_btn').on('click change', function (event) {
			event.preventDefault();

			if($('#start_date').val()=="")
			{
				$('#start_date').focus();
			}
			else if($('#end_date').val()=="")
			{
				$('#end_date').focus();
			}
			else
			{
				dt_table.ajax.reload();
			}

		});
	});

});

function dispute_status(id, client){
		swal({
			title: 'Dispute - Transaction ID:' +id,
			text: "Give Reason for dispute",
			html: '<br><input class="form-control" placeholder="Reason For dispute" id="reason">',
			content: {
				element: "input",
				attributes: {
					placeholder: "Reason",
					type: "text",
					id: "reason",
					className: "form-control"
				},
			},
			buttons: {
				cancel: {
					visible: true,
					className: 'btn btn-danger'
				},        			
				confirm: {
					className : 'btn btn-success'
				}
			},
		}).then((Dispute) => {
			if (Dispute) {
				 $.ajax({
		            url : "<?php echo site_url('frontend/member/dispute_status')?>",
		            type: "POST",
		            data: {reason: $('#reason').val(), id: id, client: client},
		            dataType: "JSON",
		            success: function(data)
		            {
		                swal({
							title: 'Disputed!',
							text: data.msg,
							type: 'success',
							buttons : {
								confirm: {
									className : 'btn btn-success'
								}
							},
							timer: 6000
							});

		                location.reload();
		            },
		            error: function (jqXHR, textStatus, errorThrown)
		            {
		                 swal({
							title: 'Error!',
							text: data.msg,
							type: 'danger',
							buttons : {
								confirm: {
									className : 'btn btn-danger'
								}
							},
							timer: 6000,
							});
		            }
		        });
			} else {
				swal.close();
			}
		});
}
</script>
<?php $this->load->view('frontend/includes/footer_end'); ?>