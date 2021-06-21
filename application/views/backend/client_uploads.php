<?php $this->load->view('templates/includes/auth_header_start'); ?>
<?php $this->load->view('templates/includes/auth_header_end'); ?>
	<div class="content">
		<div class="page-inner">
			<div class="row">
					<div class="col-md-12">
						<div class="card">
							<div class="card-header">
								<div class="card-head-row">
									<div class="card-title"><i class="fa fa-car"></i> Client Vehicle Uploads</div>
									<div class="card-tools">
										<a href="<?php echo base_url('vehicles-managment') ?>"><i class="fas fa-step-backward fa-1x"></i> </a>
									</div>
								</div>
							</div>
							<div class="card-body">
								<div class="table-responsive">
									<table id="basic-datatables" class="display table table-striped table-bordered table-hover table-head-bg-primary" >
										<thead>
											<tr>
												<th>N<sup>o</sup></th>
												<th>Uploader</th>
												<th>Client</th>
												<th>Department</th>
												<th>Datetime</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
											<?php $i = 0; foreach ($uploads as $upload) { ?>
												<tr>
													<td><?php echo ++$i ?></td>
													<td><?php echo ucwords(str_replace('_', ' ', $upload['uploader'])) ?></td>
													<td><?php echo ucwords(str_replace('_', ' ', $upload['client'])) ?></td>
													<td><?php echo ucwords(str_replace('_', ' ', $upload['dept'])) ?></td>
													<td><?php echo date('Y-m-d h:m:s', $upload['time']) ?></td>
													<td>
														<a href="<?php echo base_url()?>uploads/client_uploads/<?php echo $upload['filename'] ?>" class="btn btn-xs"><i class="fa fa-download text-primary"></i></a> | 
														<button onclick="delete_upload(<?php echo "'".$upload['time']."'" ?>)" class="btn btn-xs"><i class="fa fa-trash text-danger"></i></a></button>
													</td>
												</tr>
											<?php } ?>
										</tbody>
									</table>
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
	 var dataTable = $('#basic-datatables').DataTable({
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
            lengthMenu: [10, 50, 100, 150, 200],
            pageLength: 10,
            "pagingType": "full",
            "aaSorting": [[ 4, 'desc' ]]
            });


        });
	function delete_upload(time){
		if (confirm('You sure deleting this client\'s vehicles excel file?' )) {
			$.ajax({
				url: '<?php echo base_url()?>backend/admin/delete_uploaded_file/'+time,
				type: 'POST',
				dataType: 'json',
				success: function(data){
					if (data.status) {
						alert(data.msg);
						location.reload();
					} else{
						alert(data.msg);
					}
				}
			});
		}		
	}

	    
</script>
<?php $this->load->view('templates/includes/footer_end'); ?>