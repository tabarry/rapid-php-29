<?php
include('../sulata/includes/config.php');
include('../sulata/includes/functions.php');
include('../sulata/includes/get-settings.php');
include('../sulata/includes/db-structure.php');
checkLogin();

$id = suSegment(1);
if (!is_numeric($id)) {
    suExit(INVALID_RECORD);
}
$sql = "SELECT faq__ID,faq__Question,faq__Answer,faq__Status FROM sulata_faqs WHERE faq__dbState='Live' AND faq__ID='" . $id . "'";
$result = suQuery($sql);
$row = $result['result'][0];
if ($result['num_rows'] == 0) {
    suExit(INVALID_RECORD);
}



//Check if action is duplicate
if (suSegment(2) == 'duplicate') {
    $do = 'add';
    $pageName = 'Duplicate FAQs';
    $pageTitle = 'Duplicate FAQs';
    $mode = 'edit';
} else {
    $do = 'update';
    $pageName = 'Update FAQs';
    $pageTitle = '<span id="page-title">Update</span> Faqs';
    $mode = 'edit';
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
                            <div class="row">
                                    <div class="col-6"><h2><?php echo $pageTitle; ?></h2></div>
                                    <div class="col-6 text-right"><a href="<?php echo ADMIN_URL; ?>faqs<?php echo PHP_EXTENSION; ?>/?overlay=<?php echo $_GET['overlay']; ?>"><i class="fa fa-table"></i></a></div>
                                </div>
                            <form class="form-horizontal" action="<?php echo ADMIN_URL; ?>faqs-remote<?php echo PHP_EXTENSION; ?>/<?php echo $do; ?>/" accept-charset="utf-8" name="suForm" id="suForm" method="post" target="remote" >
                                <div class="gallery clearfix">
                                    <div class="row">

                                        <div class="col-12">                
                                            <label><?php echo $dbs_sulata_faqs['faq__Question_req']; ?><?php echo $dbs_sulata_faqs['faq__Question_title']; ?>:</label>
                                            <?php
                                            $arg = array('type' => $dbs_sulata_faqs['faq__Question_html5_type'], 'name' => 'faq__Question', 'id' => 'faq__Question', 'autocomplete' => 'off', 'maxlength' => $dbs_sulata_faqs['faq__Question_max'], 'value' => suUnstrip($row['faq__Question']), $dbs_sulata_faqs['faq__Question_html5_req'] => $dbs_sulata_faqs['faq__Question_html5_req'], 'class' => 'form-control');
                                            echo suInput('input', $arg);
                                            ?>
                                        </div>

                                        <div class="col-12">
                                            <!-- //MEDIA MANAGER -->
                                            <div class="text-right">
                                                <a title="Media.." rel="prettyPhoto[iframes]" href="<?php echo ADMIN_URL; ?>media<?php echo PHP_EXTENSION; ?>/?overlay=yes&iframe=true&width=80%&height=100%"><i class="fa fa-images"></i></a>
                                            </div>
                                            <!-- MEDIA MANAGER// -->
                                            <label><?php echo $dbs_sulata_faqs['faq__Answer_req']; ?><?php echo $dbs_sulata_faqs['faq__Answer_title']; ?>:</label>
                                            <?php
                                            $arg = array('type' => $dbs_sulata_faqs['faq__Answer_html5_type'], 'name' => 'faq__Answer', 'id' => 'faq__Answer');
                                            echo suInput('textarea', $arg, urldecode($row['faq__Answer']), TRUE);
                                            suCKEditor('faq__Answer');
                                            ?>
                                        </div>

                                        <div class="col-12 col-md-3">        
                                            <label><?php echo $dbs_sulata_faqs['faq__Status_req']; ?><?php echo $dbs_sulata_faqs['faq__Status_title']; ?>:</label>
                                            <?php
                                            $options = $dbs_sulata_faqs['faq__Status_array'];
                                            $js = "class='form-control'";
                                            echo suDropdown('faq__Status', $options, suUnstrip($row['faq__Status']), $js)
                                            ?>
                                        </div>

                                    </div>

                                    <p>
                                    <div class="text-right mt-1">
                                        <?php
                                        $arg = array('type' => 'submit', 'name' => 'Submit', 'id' => 'Submit', 'value' => 'Submit', 'class' => 'btn btn-dark');
                                        echo suInput('input', $arg);
                                        ?>
                                    </div>
                                    </p>
                                </div>
                                <?php
                                //Referrer field
                                if (isset($_GET['referrer'])) {//This is the case when page comes from preview page
                                    $_SERVER['HTTP_REFERER'] = $_GET['referrer'];
                                }
                                $arg = array('type' => 'hidden', 'name' => 'referrer', 'id' => 'referrer', 'value' => $_SERVER['HTTP_REFERER']);
                                echo suInput('input', $arg);
                                //Id field
                                $arg = array('type' => 'hidden', 'name' => 'faq__ID', 'id' => 'faq__ID', 'value' => $id);
                                echo suInput('input', $arg);
                                //If Duplicate
                                if ($do == 'add') {
                                    $arg = array('type' => 'hidden', 'name' => 'duplicate', 'id' => 'duplicate', 'value' => '1');
                                }
                                echo suInput('input', $arg);
                                ?>
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