<?php $this->load->view('templates/includes/auth_header_start'); ?>
<?php $this->load->view('templates/includes/auth_header_end'); ?>
    <div class="content">
        <div class="page-inner">
            <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                             <div class="card-header">
                                <div class="card-head-row">
                                    <div class="card-title"><i class="fas fa-money-check-alt"></i> Client Tollway Spending &laquo;<?php echo ucwords(str_replace('_', ' ', $org));?></div>
                                    <div class="card-tools">
                                        <a href="<?php echo base_url('add-client') ?>" class="btn btn-info btn-border btn-round btn-sm mr-2">
                                            New Client
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-6 col-md-3">
                                        <div class="card card-stats card-round">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-5">
                                                        <div class="icon-big text-center icon-primary bubble-shadow-small">
                                                            <i class="fa fa-road fa-2x"></i>
                                                        </div>
                                                    </div>
                                                    <div class="col-7 col-stats">
                                                        <div class="numbers">
                                                            <h4 class="card-title">Toll by Roads</h4>
                                                            <div align="center"><a href="<?php echo base_url('backend/admin/client_road_tolls/');?><?php echo $org;?>/all" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-dollar"></i><i class="fa fa-dollar"></i> Details</a></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-3">
                                        <div class="card card-stats card-round">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-5">
                                                        <div class="icon-big text-center icon-primary bubble-shadow-small">
                                                            <i class="fas fa-building fa-2x"></i>
                                                        </div>
                                                    </div>
                                                    <div class="col-7 col-stats">
                                                        <div class="numbers">
                                                            <h4 class="card-title">Toll by Agencies</h4>
                                                            <div align="center"><a href="<?php echo base_url('backend/admin/client_agency_tolls/');?><?php echo $org;?>/all" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-dollar"></i><i class="fa fa-dollar"></i> Details</a></div>
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
            </div>
        </div>
    </div>
<!-- /#page-wrapper -->

<?php $this->load->view('templates/includes/footer_start'); ?>
<?php $this->load->view('templates/includes/footer_end'); ?>