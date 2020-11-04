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
$sql = "SELECT header__ID,header__Title,header__Picture FROM sulata_headers WHERE header__dbState='Live' AND header__ID='" . $id . "'";
$result = suQuery($sql);
$row = $result['result'][0];
if ($result['num_rows'] == 0) {
    suExit(INVALID_RECORD);
}



//Check if action is duplicate
if (suSegment(2) == 'duplicate') {
    $do = 'add';
    $pageName = 'Duplicate Headers';
    $pageTitle = 'Duplicate Headers';
    $mode = 'edit';
} else {
    $do = 'update';
    $pageName = 'Update Headers';
    $pageTitle = '<span id="page-title">Update</span> Headers';
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
                                    <div class="col-6 text-right"><a href="<?php echo ADMIN_URL; ?>headers<?php echo PHP_EXTENSION; ?>/?overlay=<?php echo $_GET['overlay']; ?>"><i class="fa fa-table"></i></a></div>
                                </div>
                            <form class="form-horizontal" action="<?php echo ADMIN_URL; ?>headers-remote<?php echo PHP_EXTENSION; ?>/<?php echo $do; ?>/" accept-charset="utf-8" name="suForm" id="suForm" method="post" target="remote" enctype="multipart/form-data">
                                    <div class="gallery clearfix">
                                        <div class="row">

                                            <div class="col-12">                
                                                <label><?php echo $dbs_sulata_headers['header__Title_req']; ?><?php echo $dbs_sulata_headers['header__Title_title']; ?>:</label>
                                                <?php
                                                $arg = array('type' => $dbs_sulata_headers['header__Title_html5_type'], 'name' => 'header__Title', 'id' => 'header__Title', 'autocomplete' => 'off', 'maxlength' => $dbs_sulata_headers['header__Title_max'], 'value' => suUnstrip($row['header__Title']), $dbs_sulata_headers['header__Title_html5_req'] => $dbs_sulata_headers['header__Title_html5_req'], 'class' => 'form-control');
                                                echo suInput('input', $arg);
                                                ?>
                                            </div>

                                            <div class="col-12 col-md-3">  

                                                <label><?php echo $dbs_sulata_headers['header__Picture_req']; ?><?php echo $dbs_sulata_headers['header__Picture_title']; ?>:</label>
                                                <?php
                                                $ext = suGetExtension(suUnstrip($row['header__Picture']));
                                                $allowed_image_format = explode(',', $getSettings['allowed_image_formats']);
                                                if (in_array($ext, $allowed_image_format)) {
                                                    $thumbnail = suMakeThumbnailName(suUnstrip($row['header__Picture']));

                                                    if (suCheckFileAtURL(BASE_URL . 'files/' . $thumbnail) && suUnstrip($row['header__Picture']) != '') {
                                                        $defaultImage = BASE_URL . 'files/' . $thumbnail;
                                                        echo '<img src="' . $defaultImage . '" class="imgThumb"/>';
                                                    }
                                                }
                                                ?>


                                                <?php
                                                $arg = array('type' => $dbs_sulata_headers['header__Picture_html5_type'], 'name' => 'header__Picture', 'id' => 'header__Picture', 'class' => 'form-control');
                                                echo suInput('input', $arg);
                                                ?>

                                                <?php if ((file_exists(ADMIN_UPLOAD_PATH . $row['header__Picture'])) && ($row['header__Picture'] != '')) { ?>
                                                    <div class="container"><a class="underline" href="<?php echo BASE_URL . 'files/' . $row['header__Picture']; ?>" target="_blank"><?php echo VIEW_FILE; ?></a></div>
                                                    <div class="container"><?php echo $getSettings['allowed_image_formats']; ?></div>
                                                <?php } ?>  
                                            </div>

                                        </div>

                                        
                                            <p class="text-right">
                                            <?php
                                            $arg = array('type' => 'submit', 'name' => 'Submit', 'id' => 'Submit', 'value' => 'Submit', 'class' => 'btn btn-dark');
                                            echo suInput('input', $arg);
                                            ?>
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
                                    $arg = array('type' => 'hidden', 'name' => 'header__ID', 'id' => 'header__ID', 'value' => $id);
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
    <!--PRETTY PHOTO-->
    <?php include('includes/pretty-photo.php'); ?>   
    <?php suIframe(); ?>
</html>