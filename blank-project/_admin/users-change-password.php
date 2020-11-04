<?php
include('../sulata/includes/config.php');
include('../sulata/includes/functions.php');
include('../sulata/includes/get-settings.php');
include('../sulata/includes/db-structure.php');

checkLogin(TRUE);

$id = suSegment(1);
if (!is_numeric($id)) {
    $id = $_SESSION[SESSION_PREFIX . 'user__ID'];
}
$sql = "SELECT user__ID,user__Name,user__Email,user__Password FROM sulata_users WHERE user__dbState='Live' AND user__ID='" . $id . "'";
$result = suQuery($sql);
$row = $result['result'][0];
if ($result['num_rows'] == 0) {
    suExit(INVALID_RECORD);
}

$pageName = 'Change Password';
$pageTitle = 'Change Password';

if ($_SESSION[SESSION_PREFIX . 'user__Type'] == 'Private') {
    $readonly = 'noreadonly';
    $first_login_message = FIRST_LOGIN_MESSAGE;
} else {
    $readonly = 'readonly';
    $first_login_message = RESET_LOGIN_MESSAGE;
}
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
                            <h2><?php echo $pageTitle;?></h2>
                            <?php
                            if ($_SESSION[SESSION_PREFIX . 'user__Password_Reset'] == 'Yes') {
                                echo suInfo($first_login_message);
                            }
                            ?>
                            <form class="form-horizontal" action="<?php echo ADMIN_URL; ?>users-remote<?php echo PHP_EXTENSION; ?>/update-password/" accept-charset="utf-8" name="suForm" id="suForm" method="post" target="remote" enctype="multipart/form-data">
                                <div class="gallery clearfix">
                                    <div class="row">

                                        <div class="col-12 col-md-6">                
                                            <label><?php echo $dbs_sulata_users['user__Name_req']; ?><?php echo $dbs_sulata_users['user__Name_title']; ?>:</label>
                                            <?php
                                            $arg = array('type' => $dbs_sulata_users['user__Name_html5_type'], 'name' => 'user__Name', 'id' => 'user__Name', 'autocomplete' => 'off', 'maxlength' => $dbs_sulata_users['user__Name_max'], 'value' => suUnstrip($row['user__Name']), $dbs_sulata_users['user__Name_html5_req'] => $dbs_sulata_users['user__Name_html5_req'], 'class' => 'form-control', 'readonly' => 'readonly');
                                            echo suInput('input', $arg);
                                            ?>
                                        </div>


                                        <div class="col-12 col-md-6">                
                                            <label><?php echo $dbs_sulata_users['user__Email_req']; ?><?php echo $dbs_sulata_users['user__Email_title']; ?>:</label>
                                            <?php
                                            $arg = array('type' => $dbs_sulata_users['user__Email_html5_type'], 'name' => 'user__Email', 'id' => 'user__Email', 'autocomplete' => 'off', 'maxlength' => $dbs_sulata_users['user__Email_max'], 'value' => suUnstrip($row['user__Email']), $dbs_sulata_users['user__Email_html5_req'] => $dbs_sulata_users['user__Email_html5_req'], 'class' => 'form-control', $readonly => $readonly);
                                            echo suInput('input', $arg);
                                            ?>
                                        </div>

                                        <div class="col-12 col-md-6">            
                                            <label><?php echo $dbs_sulata_users['user__Password_req']; ?><?php echo $dbs_sulata_users['user__Password_title']; ?>: 
                                                <?php if ($getSettings['show_password'] == 'Yes') { ?>
                                                    <a href="javascript:;" onclick="doShowPassword();"><i class="fa fa-eye"></i></a>
                                                <?php } ?>

                                            </label>
                                            <?php
                                            $arg = array('type' => $dbs_sulata_users['user__Password_html5_type'], 'name' => 'user__Password', 'id' => 'user__Password', 'maxlength' => $dbs_sulata_users['user__Password_max'], $dbs_sulata_users['user__Password_html5_req'] => $dbs_sulata_users['user__Password_html5_req'], 'class' => 'form-control');
                                            echo suInput('input', $arg);
                                            ?>
                                        </div>
                                        <div class="col-12 col-md-6">                                            
                                            <label><?php echo $dbs_sulata_users['user__Password_req']; ?><?php echo CONFIRM; ?> <?php echo $dbs_sulata_users['user__Password_title']; ?>:</label>
                                            <?php
                                            $arg = array('type' => $dbs_sulata_users['user__Password_html5_type'], 'name' => 'user__Password2', 'id' => 'user__Password2', 'maxlength' => $dbs_sulata_users['user__Password_max'], $dbs_sulata_users['user__Password_html5_req'] => $dbs_sulata_users['user__Password_html5_req'], 'class' => 'form-control');
                                            echo suInput('input', $arg);
                                            ?>
                                        </div>                                

                                    </div>
                                    <p class="text-right mt-2">
                                    <?php
                                    $arg = array('type' => 'submit', 'name' => 'Submit', 'id' => 'Submit', 'value' => 'Submit', 'class' => 'btn btn-dark');
                                    echo suInput('input', $arg);
                                    ?>                              
                                    </p>
                                </div>
                                <?php
                                //Referrer field
                                $arg = array('type' => 'hidden', 'name' => 'referrer', 'id' => 'referrer', 'value' => $_SERVER['HTTP_REFERER']);
                                echo suInput('input', $arg);
                                //Id field
                                $arg = array('type' => 'hidden', 'name' => 'user__ID', 'id' => 'user__ID', 'value' => $id);
                                echo suInput('input', $arg);
                                //If Duplicate
                                if ($do == 'add') {
                                    $arg = array('type' => 'hidden', 'name' => 'duplicate', 'id' => 'duplicate', 'value' => '1');
                                }
                                echo suInput('input', $arg);
                                ?>
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
    <!--PRETTY PHOTO-->
    <?php include('includes/pretty-photo.php'); ?>   
    <?php suIframe(); ?>
</html>