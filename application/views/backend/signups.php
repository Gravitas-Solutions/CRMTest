<?php $this->load->view('templates/includes/auth_header_start'); ?>
<?php $this->load->view('templates/includes/auth_header_end'); ?>
<div class="content">
        <div class="page-inner">
            <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-head-row">
                                    <div class="card-title"><i class="fa fa-question-circle "></i> Signups Management</div>
                                    <div class="card-tools">
                                        <a onclick="javascript:history.go(-1);"><i class="fas fa-step-backward fa-1x"></i> </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                <table id="basic-datatables" class="display table table-striped table-bordered table-hover table-head-bg-primary" >
									<thead>
										<th>#</th>
										<th>Name</th>
										<th>Phone</th>
										<th>Email</th>
										<th>Company</th>
										<th>Title</th>
										<th>Status</th>
									</thead>
									<tbody>
										<?php $i = 0; foreach ($signups as $signup) {?>
										<tr>
											<td><?php echo ++$i; ?></td>
											<td><?php echo ucwords($signup->fname).' '.ucwords($signup->lname);?></td>
											<td><?php echo $signup->phone;?></td>
											<td><?php echo $signup->email;?></td>
											<td><?php echo ucwords($signup->company);?></td>
											<td><?php echo ucwords($signup->title);?></td>
											<td>
												<button class="<?php echo ($signup->signup_status) ? 'btn btn-success btn-xs':'btn btn-info btn-xs';?>" onclick="approve(<?php echo $signup->signup_id;?>)" title = "Change status"><span><i class="fa fa-refresh"></i> <?php echo ($signup->signup_status) ? 'Approved':'Pending';?></span></button>
											</td>
										</tr>
										<?php } ?>
									</tbody>
								</table>
								</div>
							</div><!-- card-body -->
						</div><!-- card -primary -->
					</div>
			</div>
		</div>
</div>

<?php $this->load->view('templates/includes/footer_start'); ?>
<script type="text/javascript">
	function approve(id){
        $.ajax({
            url : "<?php echo site_url('backend/admin/approve')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                alert('Status change success');
                location.reload();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error changing signup status');
            }
        });
}
</script>
<?php $this->load->view('templates/includes/footer_end'); ?>