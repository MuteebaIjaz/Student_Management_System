 <header class="nxl-header">
        <div class="header-wrapper">
            <div class="header-left d-flex align-items-center gap-4">
                <a href="javascript:void(0);" class="nxl-head-mobile-toggler" id="mobile-collapse">
                    <div class="hamburger hamburger--arrowturn">
                        <div class="hamburger-box">
                            <div class="hamburger-inner"></div>
                        </div>
                    </div>
                </a>
                <div class="nxl-navigation-toggle">
                    <a href="javascript:void(0);" id="menu-mini-button">
                        <i class="feather-align-left"></i>
                    </a>
                    <a href="javascript:void(0);" id="menu-expend-button" style="display: none">
                        <i class="feather-arrow-right"></i>
                    </a>
                </div>
            </div>
            <div class="header-right ms-auto">
                <div class="d-flex align-items-center">





          
            <div class="dropdown nxl-h-item">
                <a href="javascript:void(0);" data-bs-toggle="dropdown" role="button" data-bs-auto-close="outside">
                    <img src="assets/images/avatar/1.png" alt="user-image" class="img-fluid user-avtar me-0" />
                </a>
                <div class="dropdown-menu dropdown-menu-end nxl-h-dropdown nxl-user-dropdown">
                    <div class="dropdown-header">
                        <div class="d-flex align-items-center">
                            <div>
                                <h6 class="text-dark mb-0"> <?php echo $_SESSION['user_name'] ?></h6>
                            </div>
                        </div>
                    </div>
                    
                  
                    <a href="javascript:void(0);" class="dropdown-item">
                        <i class="feather-settings"></i>
                        <span>Account Settings</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="../Logout.php" class="dropdown-item">
                        <i class="feather-log-out"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </div>
        </div>
        </div>
        </div>
    </header>