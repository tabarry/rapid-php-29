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
$sql = "SELECT page__ID,page__Name,page__Permalink,page__Position,page__Title,page__Keyword,page__Description,page__Header,page__Content FROM sulata_pages WHERE page__dbState='Live' AND page__ID='" . $id . "'";
$result = suQuery($sql);
$row = $result['result'][0];
if ($result['num_rows'] == 0) {
    suExit(INVALID_RECORD);
}



//Check if action is duplicate
if (suSegment(2) == 'duplicate') {
    $do = 'add';
    $pageName = 'Duplicate Pages';
    $pageTitle = 'Duplicate Pages';
    $mode = 'edit';
} else {
    $do = 'update';
    $pageName = 'Update Pages';
    $pageTitle = '<span id="page-title">Update</span> Pages';
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
                                <div class="col-6 text-right"><a href="<?php echo ADMIN_URL; ?>pages<?php echo PHP_EXTENSION; ?>/?overlay=<?php echo $_GET['overlay']; ?>"><i class="fa fa-table"></i></a></div>
                            </div>
                            <form class="form-horizontal" action="<?php echo ADMIN_URL; ?>pages-remote<?php echo PHP_EXTENSION; ?>/<?php echo $do; ?>/" accept-charset="utf-8" name="suForm" id="suForm" method="post" target="<?php echo $form_target;?>" >
                                <div class="gallery clearfix">
                                    <div class="row">

                                        <div class="col-12 col-md-4">
                                            <label><?php echo $dbs_sulata_pages['page__Name_req']; ?><?php echo $dbs_sulata_pages['page__Name_title']; ?>:</label>
                                            <?php
                                            $js = "return $('#page__Permalink').val(doSlugify(this.value,'-'));";
                                            $arg = array('type' => $dbs_sulata_pages['page__Name_html5_type'], 'name' => 'page__Name', 'id' => 'page__Name', 'autocomplete' => 'off', 'maxlength' => $dbs_sulata_pages['page__Name_max'], 'value' => suUnstrip($row['page__Name']), $dbs_sulata_pages['page__Name_html5_req'] => $dbs_sulata_pages['page__Name_html5_req'], 'class' => 'form-control', 'onkeyup' => $js);
                                            echo suInput('input', $arg);
                                            ?>
                                        </div>


                                        <div class="col-12 col-md-4">
                                            <label><?php echo $dbs_sulata_pages['page__Permalink_req']; ?><?php echo $dbs_sulata_pages['page__Permalink_title']; ?>:</label>
                                            <?php
                                            $arg = array('type' => $dbs_sulata_pages['page__Permalink_html5_type'], 'name' => 'page__Permalink', 'id' => 'page__Permalink', 'autocomplete' => 'off', 'maxlength' => $dbs_sulata_pages['page__Permalink_max'], 'value' => suUnstrip($row['page__Permalink']), $dbs_sulata_pages['page__Permalink_html5_req'] => $dbs_sulata_pages['page__Permalink_html5_req'], 'class' => 'form-control');
                                            echo suInput('input', $arg);
                                            ?>
                                        </div>

                                        <div class="col-12 col-md-4">
                                            <label><?php echo $dbs_sulata_pages['page__Position_req']; ?><?php echo $dbs_sulata_pages['page__Position_title']; ?>:</label>
                                            <?php
                                            $options = $dbs_sulata_pages['page__Position_array'];
                                            $js = "class='form-control'";
                                            echo suDropdown('page__Position', $options, suUnstrip($row['page__Position']), $js)
                                            ?>
                                        </div>

                                        <div class="col-12 col-md-4">
                                            <label><?php echo $dbs_sulata_pages['page__Title_req']; ?><?php echo $dbs_sulata_pages['page__Title_title']; ?>:</label>
                                            <?php
                                            $arg = array('type' => $dbs_sulata_pages['page__Title_html5_type'], 'name' => 'page__Title', 'id' => 'page__Title', 'autocomplete' => 'off', 'maxlength' => $dbs_sulata_pages['page__Title_max'], 'value' => suUnstrip($row['page__Title']), $dbs_sulata_pages['page__Title_html5_req'] => $dbs_sulata_pages['page__Title_html5_req'], 'class' => 'form-control');
                                            echo suInput('input', $arg);
                                            ?>
                                        </div>


                                        <div class="col-12 col-md-4">
                                            <label><?php echo $dbs_sulata_pages['page__Keyword_req']; ?><?php echo $dbs_sulata_pages['page__Keyword_title']; ?>:</label>
                                            <?php
                                            $arg = array('type' => $dbs_sulata_pages['page__Keyword_html5_type'], 'name' => 'page__Keyword', 'id' => 'page__Keyword', 'autocomplete' => 'off', 'maxlength' => $dbs_sulata_pages['page__Keyword_max'], 'value' => suUnstrip($row['page__Keyword']), $dbs_sulata_pages['page__Keyword_html5_req'] => $dbs_sulata_pages['page__Keyword_html5_req'], 'class' => 'form-control');
                                            echo suInput('input', $arg);
                                            ?>
                                        </div>


                                        <div class="col-12 col-md-4">
                                            <label><?php echo $dbs_sulata_pages['page__Description_req']; ?><?php echo $dbs_sulata_pages['page__Description_title']; ?>:</label>
                                            <?php
                                            $arg = array('type' => $dbs_sulata_pages['page__Description_html5_type'], 'name' => 'page__Description', 'id' => 'page__Description', 'autocomplete' => 'off', 'maxlength' => $dbs_sulata_pages['page__Description_max'], 'value' => suUnstrip($row['page__Description']), $dbs_sulata_pages['page__Description_html5_req'] => $dbs_sulata_pages['page__Description_html5_req'], 'class' => 'form-control');
                                            echo suInput('input', $arg);
                                            ?>
                                        </div>

                                        <div class="col-12">
                                            <label><?php echo $dbs_sulata_pages['page__Header_req']; ?><?php echo $dbs_sulata_pages['page__Header_title']; ?>:
                                                <?php if ($addAccess == 'true') { ?>
                                                  <!-- MODAL WINDOW -->
                                                  <a title="Add new.." href="javascript:;" data-toggle="modal" data-target="#headers-add"><i class="fa fa-plus"></i></a>
                                                  <?php suModalWindow('headers-add', ADMIN_URL . 'headers-add'.PHP_EXTENSION.'/?overlay=yes'); ?>

                                                    <a onclick="suReload('page__Header', '<?php echo ADMIN_URL; ?>', '<?php echo suCrypt('sulata_headers'); ?>', '<?php echo suCrypt('header__ID'); ?>', '<?php echo suCrypt('header__Title'); ?>');" href="javascript:;"><i class="fa fa-undo"></i></a>
                                                <?php } ?>
                                            </label>
                                            <?php
                                            $sql = "SELECT header__ID AS f1, header__Title AS f2 FROM sulata_headers where header__dbState='Live' ORDER BY f2";
                                            $options = suFillDropdown($sql);
                                            $js = "class='form-control'";
                                            echo suDropdown('page__Header', $options, suUnstrip($row['page__Header']), $js)
                                            ?>
                                        </div>



                                        <div class="col-12">
                                            <label><?php echo $dbs_sulata_pages['page__Content_req']; ?><?php echo $dbs_sulata_pages['page__Content_title']; ?>:</label>
                                            <?php
                                            $arg = array('type' => $dbs_sulata_pages['page__Content_html5_type'], 'name' => 'page__Content', 'id' => 'page__Content');
                                            echo suInput('textarea', $arg, urldecode($row['page__Content']), TRUE);
                                            suCKEditor('page__Content');
                                            ?>
                                        </div>

                                    </div>

                                    <p class="text-right mt-1">
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
                                $arg = array('type' => 'hidden', 'name' => 'page__ID', 'id' => 'page__ID', 'value' => $id);
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
