<?php
include('../sulata/includes/config.php');
include('../sulata/includes/functions.php');
include('../sulata/includes/get-settings.php');
include('../sulata/includes/db-structure.php');
checkLogin();

$id = $_SESSION[SESSION_PREFIX . 'user__ID'];

$sql = "SELECT user__ID,user__Notes FROM sulata_users WHERE user__dbState='Live' AND user__ID='" . $id . "'";
$result = suQuery($sql);
$row = $result['result'][0];
if ($result['num_rows'] == 0) {
    suExit(INVALID_RECORD);
}

$pageName = 'Update Notes';
$pageTitle = 'Update Notes';
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
                            <form class="form-horizontal" action="<?php echo ADMIN_URL; ?>notes-remote<?php echo PHP_EXTENSION; ?>/update/" accept-charset="utf-8" name="suForm" id="suForm" method="post" target="<?php echo $form_target;?>" >
                                <div class="gallery clearfix">

                                    <div class="row">
                                        <div class="col-12">                
                                            <label><?php echo $dbs_sulata_users['user__Notes_req']; ?><?php echo NOTES_LABEL; ?></label>
                                            <?php
                                            $arg = array('type' => $dbs_sulata_users['user__Notes_html5_type'], 'name' => 'user__Notes', 'id' => 'user__Notes', $dbs_sulata_users['user__Notes_html5_req'] => $dbs_sulata_users['user__Notes_html5_req'], 'class' => 'form-control', 'style' => 'height:300px;');
                                            echo suInput('textarea', $arg, suUnstrip($row['user__Notes']), TRUE);
                                            ?>
                                        </div>
                                    </div>


                                    <p class="text-right mt-2">
                                        <?php
                                        $arg = array('type' => 'submit', 'name' => 'Submit', 'id' => 'Submit', 'value' => 'Submit', 'class' => 'btn btn-dark');
                                        echo suInput('input', $arg);
                                        ?>                              
                                    </p>
                                    <?php
                                    //Id field
                                    $arg = array('type' => 'hidden', 'name' => 'user__ID', 'id' => 'user__ID', 'value' => $id);
                                    echo suInput('input', $arg);
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
    
       
    <?php suIframe(); ?>
</html>