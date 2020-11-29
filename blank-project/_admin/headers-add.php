<?php
include('../sulata/includes/config.php');
include('../sulata/includes/functions.php');
include('../sulata/includes/get-settings.php');
include('../sulata/includes/db-structure.php');
checkLogin();
$pageName = 'Add Headers';
$pageTitle = 'Add Headers';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include('includes/head.php'); ?>
        <script>
            $(document).ready(function () {
                //Keep session alive
                $(function () {
                    window.setInterval("suStayAlive('<?php echo PING_URL; ?>')", 300000);
                });
                //Disable submit button
                suToggleButton(1);
            });
        </script>
    </head>
    <body>
        <div class="page">
            <div class="container">
                <!-- LAUNCHPAD -->
                <?php include('includes/launchpad.php'); ?>
                <!-- HEADER -->
                <?php include('includes/header.php'); ?>
                <hr/>
                <div class="row">
                    <!-- LEFT -->
                    <div class="col-1 d-none d-lg-block pr-0">
                        <!-- LAUNCHER -->
                        <?php include('includes/launcher.php'); ?>
                    </div>
                    <!-- CENTRE -->
                    <div class="col-11">
                        <div id="content-area">
                            <div id="error-area" class="bg-danger text-white su-hide pt-2 pb-1">
                                <ul></ul>
                            </div>
                            <div id="message-area" class="bg-success text-white su-hide pt-2 mb-1 mt-1">
                                <p></p>
                            </div>
                            <!--SU STARTS-->
                            <div class="row">
                                <div class="col-6">
                                    <h2><?php echo $pageTitle; ?></h2>
                                </div>
                                <div class="col-6 text-right">
                                    <a href="<?php echo ADMIN_URL; ?>headers<?php echo PHP_EXTENSION; ?>/?overlay=<?php echo $_GET['overlay']; ?>"><i class="fa fa-table"></i></a>
                                </div>
                            </div>
                            <form class="form-horizontal" action="<?php echo ADMIN_URL; ?>headers-remote<?php echo PHP_EXTENSION; ?>/add/" accept-charset="utf-8" name="suForm" id="suForm" method="post" target="<?php echo $form_target;?>" enctype="multipart/form-data">

                                    <div class="gallery clearfix">
                                        <div class="row">

                                            <div class="col-12">
                                                <label><?php echo $dbs_sulata_headers['header__Title_req']; ?><?php echo $dbs_sulata_headers['header__Title_title']; ?>:</label>
                                                <?php
                                                $arg = array('type' => $dbs_sulata_headers['header__Title_html5_type'], 'name' => 'header__Title', 'id' => 'header__Title', 'autocomplete' => 'off', 'maxlength' => $dbs_sulata_headers['header__Title_max'], 'value' => '', $dbs_sulata_headers['header__Title_html5_req'] => $dbs_sulata_headers['header__Title_html5_req'], 'class' => 'form-control');
                                                echo suInput('input', $arg);
                                                ?>
                                            </div>

                                            <div class="col-12 col-md-3">
                                                <label><?php echo $dbs_sulata_headers['header__Picture_req']; ?><?php echo $dbs_sulata_headers['header__Picture_title']; ?>:</label>
                                                <?php
                                                $arg = array('type' => $dbs_sulata_headers['header__Picture_html5_type'], 'name' => 'header__Picture', 'id' => 'header__Picture', $dbs_sulata_headers['header__Picture_html5_req'] => $dbs_sulata_headers['header__Picture_html5_req'], 'class' => 'form-control');
                                                echo suInput('input', $arg);
                                                ?>
                                                <div class="container"><?php echo $getSettings['allowed_image_formats']; ?></div>
                                            </div>

                                        </div>

                                    </div>
                                <p class="text-right mt-3">
                                        <?php
                                        $arg = array('type' => 'submit', 'name' => 'Submit', 'id' => 'Submit', 'value' => 'Submit', 'class' => 'btn btn-dark');
                                        echo suInput('input', $arg);
                                        ?>
                                    </p>
                                    <p>&nbsp;</p>
                                </form>
                            <!--SU ENDS-->
                        </div>
                    </div>
                    <!-- RIGHT -->
                    <div class="col-12 d-block d-sm-block d-md-block d-lg-none">
                        <!-- LAUNCHER -->
                        <?php include('includes/launcher.php'); ?>
                    </div>
                </div>
                <hr/>
                <!-- FOOTER -->
                <?php include('includes/footer.php'); ?>
            </div>
        </div>
        <?php include('includes/footer-js.php'); ?>
    </body>


    <?php suIframe(); ?>
</html>
