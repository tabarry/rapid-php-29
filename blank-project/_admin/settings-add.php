<?php
include('../sulata/includes/config.php');
include('../sulata/includes/functions.php');
include('../sulata/includes/get-settings.php');
include('../sulata/includes/db-structure.php');
checkLogin();
$pageName = 'Add settings';
$pageTitle = 'Add settings';
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
                                    <a href="<?php echo ADMIN_URL; ?>settings<?php echo PHP_EXTENSION; ?>/?overlay=<?php echo $_GET['overlay']; ?>"><i class="fa fa-table"></i></a>
                                </div>
                            </div>
                            <form class="form-horizontal" action="<?php echo ADMIN_URL; ?>settings-remote<?php echo PHP_EXTENSION; ?>/add/" accept-charset="utf-8" name="suForm" id="suForm" method="post" target="<?php echo $form_target;?>" >

                                <div class="gallery clearfix">
                                    <div class="row">
                                        <div class="col-12 col-md-4 col-lg-4">        
                                            <label><?php echo $dbs_sulata_settings['setting__Scope_req']; ?><?php echo $dbs_sulata_settings['setting__Scope_title']; ?>:</label>
                                            <?php
                                            $options = $dbs_sulata_settings['setting__Scope_array'];
                                            $js = "class='form-control'";
                                            echo suDropdown('setting__Scope', $options, 'Public', $js)
                                            ?>
                                        </div>

                                        <div class="col-12 col-md-4 col-lg-4">        
                                            <label><?php echo $dbs_sulata_settings['setting__Type_req']; ?><?php echo $dbs_sulata_settings['setting__Type_title']; ?>:</label>
                                            <?php
                                            $options = $dbs_sulata_settings['setting__Type_array'];
                                            $js = "class='form-control'";
                                            $js .= " onchange='doSettings();' ";
                                            echo suDropdown('setting__Type', $options, '', $js)
                                            ?>
                                        </div>


                                        <div class="col-12 col-md-4 col-lg-4">                
                                            <label><?php echo $dbs_sulata_settings['setting__Options_req']; ?><?php echo $dbs_sulata_settings['setting__Options_title']; ?>:</label>
                                            <?php
                                            $arg = array('type' => $dbs_sulata_settings['setting__Options_html5_type'], 'name' => 'setting__Options', 'id' => 'setting__Options', 'autocomplete' => 'off', 'maxlength' => $dbs_sulata_settings['setting__Options_max'], 'value' => '', $dbs_sulata_settings['setting__Options_html5_req'] => $dbs_sulata_settings['setting__Options_html5_req'], 'class' => 'form-control', 'readonly' => 'readonly', 'placeholder' => 'Type comma separated values', 'onblur' => 'doSettings();');
                                            echo suInput('input', $arg);
                                            ?>
                                        </div>


                                        <div class="col-12 col-md-4 col-lg-4">                
                                            <label><?php echo $dbs_sulata_settings['setting__Setting_req']; ?><?php echo $dbs_sulata_settings['setting__Setting_title']; ?>:</label>
                                            <?php
                                            $js = "return $('#setting__Key').val(doSlugify(this.value,'_'));";
                                            $arg = array('type' => $dbs_sulata_settings['setting__Setting_html5_type'], 'name' => 'setting__Setting', 'id' => 'setting__Setting', 'autocomplete' => 'off', 'maxlength' => $dbs_sulata_settings['setting__Setting_max'], 'value' => '', $dbs_sulata_settings['setting__Setting_html5_req'] => $dbs_sulata_settings['setting__Setting_html5_req'], 'class' => 'form-control', 'onkeyup' => $js);
                                            echo suInput('input', $arg);
                                            ?>
                                        </div>


                                        <div class="col-12 col-md-4 col-lg-4">                
                                            <label><?php echo $dbs_sulata_settings['setting__Key_req']; ?><?php echo $dbs_sulata_settings['setting__Key_title']; ?>:</label>
                                            <?php
                                            $arg = array('type' => $dbs_sulata_settings['setting__Key_html5_type'], 'name' => 'setting__Key', 'id' => 'setting__Key', 'autocomplete' => 'off', 'maxlength' => $dbs_sulata_settings['setting__Key_max'], 'value' => '', $dbs_sulata_settings['setting__Key_html5_req'] => $dbs_sulata_settings['setting__Key_html5_req'], 'class' => 'form-control');
                                            echo suInput('input', $arg);
                                            ?>
                                        </div>


                                        <div class="col-12 col-md-4 col-lg-4">                
                                            <label><?php echo $dbs_sulata_settings['setting__Value_req']; ?><?php echo $dbs_sulata_settings['setting__Value_title']; ?>:</label>
                                            <div id="option-wrapper">
                                                <?php
                                                $arg = array('type' => $dbs_sulata_settings['setting__Value_html5_type'], 'name' => 'setting__Value', 'id' => 'setting__Value', 'autocomplete' => 'off', 'maxlength' => $dbs_sulata_settings['setting__Value_max'], 'value' => '', $dbs_sulata_settings['setting__Value_html5_req'] => $dbs_sulata_settings['setting__Value_html5_req'], 'class' => 'form-control');
                                                echo suInput('input', $arg);
                                                ?>
                                            </div>


                                        </div>
                                    </div>

                                </div>
                                <p class="text-right mt-2">
                                    <?php
                                    $arg = array('type' => 'submit', 'name' => 'Submit', 'id' => 'Submit', 'value' => 'Submit', 'class' => 'btn btn-dark');
                                    echo suInput('input', $arg);
                                    ?>                              
                                </p>
                                <p>&nbsp;</p>
                            </form>
                            <script>
                                var optTextbox = $('#option-wrapper').html();
                                var optSelect = '<select required="required" name="setting__Value" id="setting__Value" class="form-control"></select>';

                            </script>
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