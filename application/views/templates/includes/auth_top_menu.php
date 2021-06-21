<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<nav class="navbar navbar-header navbar-expand-lg" data-background-color="blue2">
                
    <div class="container-fluid">
        <ul class="navbar-nav topbar-nav ml-md-auto align-items-center">
            <li class="nav-item dropdown hidden-caret">
                <a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#" aria-expanded="false">
                                <div class="avatar-sm">
                                    <img src="<?php echo base_url() ?>assets/images/profile.jpg" alt="..." class="avatar-img rounded-circle">
                                </div>
                            </a>
                <ul class="dropdown-menu dropdown-user animated fadeIn">
                    <div class="dropdown-user-scroll scrollbar-outer">
                        <li>
                            <div class="user-box">
                                <div class="avatar-lg"><img src="<?php echo base_url() ?>assets/img/profile.jpg" alt="image profile" class="avatar-img rounded"></div>
                                <div class="u-text">
                                    <h4>Patric Mwendwa</h4>
                                    <p class="text-muted">admin@innovativetoll.com</p><a href="<?php echo base_url('profile') ?>" class="btn btn-xs btn-secondary btn-sm">View profile</a>
                                </div>
                            </div>
                        </li>
                        <li>
                            
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="<?php echo base_url('logout') ?>">Logout</a>
                        </li>
                    </div>
                </ul>
            </li>
        </ul>
    </div>
</nav>