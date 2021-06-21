<?php $this->load->view('templates/includes/auth_header_start'); ?>
<?php $this->load->view('templates/includes/auth_header_end'); ?>
<div class="content">
    <div class="page-inner">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-head-row">
                            <div class="card-title"><i class="fa fa-user"></i> Administrative Duties</div>
                            <div class="card-tools">
                                <a onclick="javascript:history.go(-1);"><i class="fa fa-arrow-circle-left  fa-2x"></i> </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row" style="margin: 0 auto;">
                            <div class="col-md-4">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <div class="row">
                                            <div class="col-xs-3">
                                                <i class="fa fa-map-marker fa-2x"></i>
                                            </div>
                                            <div class="col-xs-9 text-right">
                                                <div class="huge"><?php echo $states;?></div>
                                                <div>States</div>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="<?php echo base_url('backend/admin/states');?>">
                                        <div class="panel-footer">
                                            <span class="pull-left">View Details</span>
                                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                            <div class="clearfix"></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-4 round">
                                <div class="panel panel-green">
                                    <div class="panel-heading">
                                        <div class="row">
                                            <div class="col-xs-3">
                                                <i class="fa fa-building fa-2x"></i>
                                            </div>
                                            <div class="col-xs-9 text-right">
                                                <div class="huge"><?php echo $agencies;?></div>
                                                <div>Agencies</div>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="<?php echo base_url('backend/admin/agencies');?>">
                                        <div class="panel-footer">
                                            <span class="pull-left">View Details</span>
                                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                            <div class="clearfix"></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-4 round">
                                <div class="panel panel-red">
                                    <div class="panel-heading">
                                        <div class="row">
                                            <div class="col-xs-3">
                                                <i class="fa fa-tags fa-2x"></i>
                                            </div>
                                            <div class="col-xs-9 text-right">
                                                <div class="huge"><?php echo $tagtypes;?></div>
                                                <div>Tag Types</div>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="<?php echo base_url('backend/tagtypes');?>">
                                        <div class="panel-footer">
                                            <span class="pull-left">View Details</span>
                                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                            <div class="clearfix"></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        <!-- /.row -->
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

