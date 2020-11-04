<?php
include('../sulata/includes/config.php');
include('../sulata/includes/functions.php');
include('../sulata/includes/get-settings.php');
include('../sulata/includes/db-structure.php');
checkLogin();
$pageName = 'Themes';
$pageTitle = 'Click to select a theme';
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
                            <h2><?php echo $pageTitle; ?></h2>
                            <form class="form-horizontal" action="<?php echo ADMIN_URL; ?>notes-remote<?php echo PHP_EXTENSION; ?>/update/" accept-charset="utf-8" name="suForm" id="suForm" method="post" target="remote" >                                        <div class="row">                
                                    <?php
                                    $dir = '../sulata/css/themes/';
                                    $dir = scandir($dir);
                                    $exclude = array(
                                        '.',
                                        '..'
                                    );
                                    foreach ($dir as $file) {
                                        if ((!in_array($file, $exclude)) && ($file[0] != '.')) {
                                            ?>
                                            <div class="col-4"> 
                                                <a href="<?php echo ADMIN_URL; ?>themes-remote<?php echo PHP_EXTENSION; ?>/change/<?php echo $file; ?>/" target="remote"><img class="imgTheme" src="<?php echo BASE_URL; ?>sulata/css/themes/<?php echo $file; ?>/bg.jpg" alt="<?php echo $file; ?>"/></a>
                                            </div>
                                            <?php
                                        }
                                    }
                                    ?>
                                </div>
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
    <!--PRETTY PHOTO-->
    <?php include('includes/pretty-photo.php'); ?>   
    <?php suIframe(); ?>
</html>