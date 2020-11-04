<div id="launchpad" class="launchpad-hide">
    <div class="container">
        <div class="text-right mb-3 mt-3"><a href="javascript:;" onclick="doToggleLaunchpad(false);" class="launchpad-close"></a></div>
        <div class="launchpad-scroll">
            <div class="row">
                <!-- STATIC LINKS -->
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="<?php echo ADMIN_URL; ?>"><i class="fa fa-home"></i></a>
                    <p><a href="<?php echo ADMIN_URL; ?>">Home</a></p>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="<?php echo ADMIN_URL; ?>themes<?php echo PHP_EXTENSION; ?>/"><i class="fa fa-images"></i></a>
                    <p><a href="<?php echo ADMIN_URL; ?>themes<?php echo PHP_EXTENSION; ?>/">Themes</a></p>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="<?php echo ADMIN_URL; ?>users-update<?php echo PHP_EXTENSION; ?>/"><i class="fa fa-user"></i></a>
                    <p><a href="<?php echo ADMIN_URL; ?>users-update<?php echo PHP_EXTENSION; ?>/">Update Profile</a></p>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="<?php echo ADMIN_URL; ?>settings<?php echo PHP_EXTENSION; ?>/"><i class="fa fa-cogs"></i></a>
                    <p><a href="<?php echo ADMIN_URL; ?>settings<?php echo PHP_EXTENSION; ?>/">Settings</a></p>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="<?php echo ADMIN_URL; ?>notes<?php echo PHP_EXTENSION; ?>/"><i class="fa fa-edit"></i></a>
                    <p><a href="<?php echo ADMIN_URL; ?>notes<?php echo PHP_EXTENSION; ?>/">Notes</a></p>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="<?php echo ADMIN_URL; ?>login<?php echo PHP_EXTENSION; ?>/?do=logout" target="remote"><i class="fa fa-power-off"></i></a>
                    <p><a href="<?php echo ADMIN_URL; ?>login<?php echo PHP_EXTENSION; ?>/?do=logout" target="remote">Log Out</a></p>
                </div>

                <div class="col-12 mb-4">
                    <hr/>
                </div>
                <!-- DYNAMIC LINKS -->
                <?php
                if ($_SESSION[SESSION_PREFIX . 'user__ID'] != '') {
                    ?>
                    <?php
                    $module_access = suGetModuleAccess();
                    $dir = suGetLinks();
                    $launchpad_icons = $dir['icons'];
                    $dir = $dir['links'];

                    //$sidebarExclude comes from config.php
                    //get menu count
                    //If Public User
                    if ($_SESSION[SESSION_PREFIX . 'user__Type'] == 'Public') {

                        //Build menus
                        $inc = 0;
                        foreach ($dir as $file) {
                            if (((!in_array($file, $sidebarExclude)) ) && (in_array($file, $module_access))) {
                                $fileNameActual = str_replace('.php', '', $file);
                                $fileName = str_replace('-', ' ', $fileNameActual);
                                //Check if icon exists
                                if (strlen($launchpad_icons[$inc]) != 0) {
                                    $launchpad_icon = $launchpad_icons[$inc];
                                } else {
                                    $launchpad_icon = 'fa fa-star';
                                }
                                $fileNameShow = str_replace('_', ' ', $fileName);
                                if (stristr($fileNameShow, 'faqs')) {
                                    $fileNameShow = 'FAQs';
                                }
                                $fileLink = str_replace('.php', PHP_EXTENSION . '/', $file);
                                ?>

                                <div class="col-6 col-md-4 col-lg-2">
                                    <a href="<?php echo ADMIN_URL . $fileLink; ?>"><i class="<?php echo $launchpad_icon; ?>"></i></a>
                                    <p><a href="<?php echo ADMIN_URL . $fileLink; ?>"><?php echo ucwords($fileNameShow); ?></a></p>
                                </div>

                                <?php
                            }
                            $inc++;
                        }
                    }
                    //==
                    //If Private User
                    if ($_SESSION[SESSION_PREFIX . 'user__Type'] == 'Private') {
                        $inc = 0;
                        //Build menus
                        foreach ($dir as $file) {
                            if (((!in_array($file, $sidebarExclude)))) {
                                $fileNameActual = str_replace('.php', '', $file);
                                $fileName = str_replace('-', ' ', $fileNameActual);
                                //Check if icon exists
                                if (strlen($launchpad_icons[$inc]) != 0) {
                                    $launchpad_icon = $launchpad_icons[$inc];
                                } else {
                                    $launchpad_icon = 'fa fa-star';
                                }
                                $fileNameShow = str_replace('_', ' ', $fileName);
                                if (stristr($fileNameShow, 'faqs')) {
                                    $fileNameShow = 'FAQs';
                                }
                                $fileLink = str_replace('.php', PHP_EXTENSION . '/', $file);
                                ?>

                                <div class="col-6 col-md-4 col-lg-2">
                                    <a href="<?php echo ADMIN_URL . $fileLink; ?>"><i class="<?php echo $launchpad_icon; ?>"></i></a>
                                    <p><a href="<?php echo ADMIN_URL . $fileLink; ?>"><?php echo ucwords($fileNameShow); ?></a></p>
                                </div>
                                <?php
                            }
                            $inc++;
                        }
                    }
                    //==
                    ?>

                    <?php
                }
                ?>

            </div>
        </div>

    </div>
</div>
