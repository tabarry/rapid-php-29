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
$sql = "SELECT group__ID,group__Name,group__Status,group__Permissions FROM sulata_groups WHERE group__dbState='Live' AND group__ID='" . $id . "'";
$result = suQuery($sql);
$row = $result['result'][0];
if ($result['num_rows'] == 0) {
    suExit(INVALID_RECORD);
}

//Get already set permissions
$permissions = $row['group__Permissions'];
$permissions = json_decode($permissions, 1);
//==
//Check if action is duplicate
if (suSegment(2) == 'duplicate') {
    $do = 'add';
    $pageName = 'Duplicate Groups';
    $pageTitle = 'Duplicate Groups';
    $mode = 'edit';
} else {
    $do = 'update';
    $pageName = 'Groups People';
    $pageTitle = '<span id="page-title">Update</span> Groups';
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
                                <div class="col-6 text-right"><a href="<?php echo ADMIN_URL; ?>groups<?php echo PHP_EXTENSION; ?>/?overlay=<?php echo $_GET['overlay']; ?>"><i class="fa fa-table"></i></a></div>
                            </div>
                            <form class="form-horizontal" action="<?php echo ADMIN_URL; ?>groups-remote<?php echo PHP_EXTENSION; ?>/<?php echo $do; ?>/" accept-charset="utf-8" name="suForm" id="suForm" method="post" target="<?php echo $form_target;?>" onsubmit="return doCheckboxCheck();" >
                                <input type="hidden" name="is_checked" id="is_checked" value="0"/>
                                <div class="gallery clearfix">
                                    <div class="row">

                                        <div class="col-12 col-md-6">
                                            <label><?php echo $dbs_sulata_groups['group__Name_req']; ?><?php echo $dbs_sulata_groups['group__Name_title']; ?>:</label>
                                            <?php
                                            $arg = array('type' => $dbs_sulata_groups['group__Name_html5_type'], 'name' => 'group__Name', 'id' => 'group__Name', 'autocomplete' => 'off', 'maxlength' => $dbs_sulata_groups['group__Name_max'], 'value' => suUnstrip($row['group__Name']), $dbs_sulata_groups['group__Name_html5_req'] => $dbs_sulata_groups['group__Name_html5_req'], 'class' => 'form-control');
                                            echo suInput('input', $arg);
                                            ?>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label><?php echo $dbs_sulata_groups['group__Status_req']; ?><?php echo $dbs_sulata_groups['group__Status_title']; ?>:</label>
                                            <?php
                                            $options = $dbs_sulata_groups['group__Status_array'];
                                            $js = "class='form-control'";
                                            echo suDropdown('group__Status', $options, suUnstrip($row['group__Status']), $js)
                                            ?>
                                        </div>

                                    </div>
                                    <div><label>
                                            <?php
                                            if ($permissions['check_all'] != '') {
                                                $chk_all = 'checked="checked"';
                                            } else {
                                                $chk_all = '';
                                            }
                                            ?>
                                            <input <?php echo $chk_all; ?> type="checkbox" name="check_all" id="check_all" onclick="doCheckUncheck(this, 'module_', 'check_all');"/> Toggle select.</label></div>

                                    <table width="100%" class="table table-hover table-bordered tbl">
                                        <tbody>
                                            <?php
                                            $dir = './';
                                            $dir = scandir($dir);
                                            sort($dir);
                                            //$sidebarExclude comes from config.php
                                            $sr = 0;
                                            foreach ($dir as $file) {
                                                if ((!in_array($file, $sidebarExclude)) && ($file[0] != '.')) {
                                                    if ((!stristr($file, '-add')) && (!stristr($file, '-remote')) && (!stristr($file, '-update')) && (!stristr($file, '-sort')) && (!stristr($file, 'inc-'))) {
                                                        if ($file == 'settings.php' || $file == 'links.php') {
                                                            $settingsClass = 'class="su-hide"';
                                                        } else {
                                                            $settingsClass = '';
                                                        }
                                                        $fileNameActual = str_replace('.php', '', $file);
                                                        $fileName = str_replace('-', ' ', $fileNameActual);

                                                        $fileNameShow = str_replace('_', ' ', $fileName);
                                                        if (stristr($fileNameShow, 'faqs')) {
                                                            $fileNameShow = 'FAQs';
                                                        }
                                                        $fileLink = str_replace('.php', PHP_EXTENSION . '/', $file);
                                                        $fileNameShow1 = $fileNameShow;
                                                        $fileNameShow = $fileNameShow . '_x';
                                                        ?>
                                                        <tr>
                                                            <td style="width:5%"><?php echo $sr = $sr + 1; ?>. </td>
                                                            <td><?php echo ucwords($fileNameShow1); ?></td>
                                                            <!-- Check all row -->
                                                            <td>
                                                                <?php
                                                                if ($permissions['module_all_' . suSlugifyName($fileNameShow) . '_row'] != '') {
                                                                    ${'chk_row' . suSlugifyName($fileNameShow)} = 'checked="checked"';
                                                                } else {
                                                                    ${'chk_row' . suSlugifyName($fileNameShow)} = '';
                                                                }
                                                                ?>

                                                                <label><input <?php echo ${'chk_row' . suSlugifyName($fileNameShow)}; ?> type="checkbox" name="module_all_<?php echo suSlugifyName($fileNameShow); ?>_row" id="module_all_<?php echo suSlugifyName($fileNameShow); ?>_row" onclick="doCheckUncheck(this, 'module_<?php echo suSlugifyName($fileNameShow); ?>', 'check_all')"/> <i class="far fa-hand-point-right"></i></label></td>

                                                            <!-- View -->
                                                            <td>
                                                                <?php
                                                                if (in_array(suSlugifyName($fileNameShow1) . '-view', $permissions)) {
                                                                    $chk_view = 'checked="checked"';
                                                                } else {
                                                                    $chk_view = '';
                                                                }
                                                                ?>
                                                                <label><input <?php echo $chk_view; ?> type="checkbox" name="module_<?php echo suSlugifyName($fileNameShow); ?>_view" id="module_<?php echo suSlugifyName($fileNameShow); ?>_view" value="<?php echo $module_prefix; ?><?php echo suSlugifyName($fileNameShow1); ?>-view<?php echo $module_postfix; ?>" onclick="doCheckUncheck(this, 'module_<?php echo suSlugifyName($fileNameShow); ?>_row', 'module_all_<?php echo suSlugifyName($fileNameShow); ?>_row')"/> View</label></td>

                                                            <!-- Search -->
                                                            <td>
                                                                <?php
                                                                if (in_array(suSlugifyName($fileNameShow1) . '-search', $permissions)) {
                                                                    $chk_search = 'checked="checked"';
                                                                } else {
                                                                    $chk_search = '';
                                                                }
                                                                ?>
                                                                <label <?php echo $settingsClass; ?>><input <?php echo $chk_search; ?> type="checkbox" name="module_<?php echo suSlugifyName($fileNameShow); ?>_search" value="<?php echo $module_prefix; ?><?php echo suSlugifyName($fileNameShow1); ?>-search<?php echo $module_postfix; ?>" id="module_<?php echo suSlugifyName($fileNameShow); ?>_search" onclick="doCheckUncheck(this, 'module_<?php echo suSlugifyName($fileNameShow); ?>_row', 'module_all_<?php echo suSlugifyName($fileNameShow); ?>_row')"/> Search</label></td>


                                                            <!-- Add -->
                                                            <td>
                                                                <?php
                                                                if (in_array(suSlugifyName($fileNameShow1) . '-add', $permissions)) {
                                                                    $chk_add = 'checked="checked"';
                                                                } else {
                                                                    $chk_add = '';
                                                                }
                                                                ?>

                                                                <label <?php echo $settingsClass; ?>><input <?php echo $chk_add; ?> type="checkbox" name="module_<?php echo suSlugifyName($fileNameShow); ?>_add" value="<?php echo $module_prefix; ?><?php echo suSlugifyName($fileNameShow1); ?>-add<?php echo $module_postfix; ?>" id="module_<?php echo suSlugifyName($fileNameShow); ?>_add" onclick="doCheckUncheck(this, 'module_<?php echo suSlugifyName($fileNameShow); ?>_row', 'module_all_<?php echo suSlugifyName($fileNameShow); ?>_row')"/> Add</label></td>

                                                            <!-- Update/Edit -->
                                                            <td>
                                                                <?php
                                                                if (in_array(suSlugifyName($fileNameShow1) . '-update', $permissions)) {
                                                                    $chk_update = 'checked="checked"';
                                                                } else {
                                                                    $chk_update = '';
                                                                }
                                                                ?>

                                                                <label <?php echo $settingsClass; ?>><input <?php echo $chk_update; ?> type="checkbox" name="module_<?php echo suSlugifyName($fileNameShow); ?>_update" value="<?php echo $module_prefix; ?><?php echo suSlugifyName($fileNameShow1); ?>-update<?php echo $module_postfix; ?>" id="module_<?php echo suSlugifyName($fileNameShow); ?>_update" onclick="doCheckUncheck(this, 'module_<?php echo suSlugifyName($fileNameShow); ?>_row', 'module_all_<?php echo suSlugifyName($fileNameShow); ?>_row')"/> Update</label></td>

                                                            <!-- Inline Edit -->
                                                            <td>
                                                                <?php
                                                                if (in_array(suSlugifyName($fileNameShow1) . '-inlineedit', $permissions)) {
                                                                    $chk_inlineedit = 'checked="checked"';
                                                                } else {
                                                                    $chk_inlineedit = '';
                                                                }
                                                                ?>

                                                                <label <?php echo $settingsClass; ?>><input <?php echo $chk_inlineedit; ?> type="checkbox" name="module_<?php echo suSlugifyName($fileNameShow); ?>_inlineedit" value="<?php echo $module_prefix; ?><?php echo suSlugifyName($fileNameShow1); ?>-inlineedit<?php echo $module_postfix; ?>" id="module_<?php echo suSlugifyName($fileNameShow); ?>_update" onclick="doCheckUncheck(this, 'module_<?php echo suSlugifyName($fileNameShow); ?>_row', 'module_all_<?php echo suSlugifyName($fileNameShow); ?>_row')"/> Inline Edit</label></td>

                                                            <!-- Delete -->
                                                            <td>
                                                                <?php
                                                                if (in_array(suSlugifyName($fileNameShow1) . '-delete', $permissions)) {
                                                                    $chk_delete = 'checked="checked"';
                                                                } else {
                                                                    $chk_delete = '';
                                                                }
                                                                ?>

                                                                <label <?php echo $settingsClass; ?>><input <?php echo $chk_delete; ?> type="checkbox" name="module_<?php echo suSlugifyName($fileNameShow); ?>_delete" value="<?php echo $module_prefix; ?><?php echo suSlugifyName($fileNameShow1); ?>-delete<?php echo $module_postfix; ?>" id="module_<?php echo suSlugifyName($fileNameShow); ?>_delete" onclick="doCheckUncheck(this, 'module_<?php echo suSlugifyName($fileNameShow); ?>_row', 'module_all_<?php echo suSlugifyName($fileNameShow); ?>_row')"/> Delete</label></td>

                                                            <!-- Restore -->
                                                            <td>
                                                                <?php
                                                                if (in_array(suSlugifyName($fileNameShow1) . '-restore', $permissions)) {
                                                                    $chk_restore = 'checked="checked"';
                                                                } else {
                                                                    $chk_restore = '';
                                                                }
                                                                ?>
                                                                <label <?php echo $settingsClass; ?>><input <?php echo $chk_restore; ?> type="checkbox" name="module_<?php echo suSlugifyName($fileNameShow); ?>_restore" value="<?php echo $module_prefix; ?><?php echo suSlugifyName($fileNameShow1); ?>-restore<?php echo $module_postfix; ?>" id="module_<?php echo suSlugifyName($fileNameShow); ?>_restore" onclick="doCheckUncheck(this, 'module_<?php echo suSlugifyName($fileNameShow); ?>_row', 'module_all_<?php echo suSlugifyName($fileNameShow); ?>_row')"/> Restore</label></td>
                                                            <!-- Duplicate -->
                                                            <td>
                                                                <?php
                                                                if (in_array(suSlugifyName($fileNameShow1) . '-duplicate', $permissions)) {
                                                                    $chk_duplicate = 'checked="checked"';
                                                                } else {
                                                                    $chk_duplicate = '';
                                                                }
                                                                ?>
                                                                <label <?php echo $settingsClass; ?>><input <?php echo $chk_duplicate; ?> type="checkbox" name="module_<?php echo suSlugifyName($fileNameShow); ?>_duplicate" value="<?php echo $module_prefix; ?><?php echo suSlugifyName($fileNameShow1); ?>-duplicate<?php echo $module_post; ?>" id="module_<?php echo suSlugifyName($fileNameShow); ?>_duplicate" onclick="doCheckUncheck(this, 'module_<?php echo suSlugifyName($fileNameShow); ?>_row', 'module_all_<?php echo suSlugifyName($fileNameShow); ?>_row')"/> Duplicate</label></td>

                                                            <!-- Sort -->
                                                            <td class="bgColor-lightGray">
                                                                <?php
                                                                if (in_array(suSlugifyName($fileNameShow1) . '-sort', $permissions)) {
                                                                    $chk_sort = 'checked="checked"';
                                                                } else {
                                                                    $chk_sort = '';
                                                                }
                                                                ?>

                                                                <label <?php echo $settingsClass; ?>><input <?php echo $chk_sort; ?> type="checkbox" name="module_<?php echo suSlugifyName($fileNameShow); ?>_sort" value="<?php echo $module_prefix; ?><?php echo suSlugifyName($fileNameShow1); ?>-sort<?php echo $module_postfix; ?>" id="module_<?php echo suSlugifyName($fileNameShow); ?>_sort" onclick="doCheckUncheck(this, 'module_<?php echo suSlugifyName($fileNameShow); ?>_row', 'module_all_<?php echo suSlugifyName($fileNameShow); ?>_row')"/> Sort</label></td>

                                                            <!-- Download CSV -->
                                                            <td class="bgColor-lightGray">
                                                                <?php
                                                                if (in_array(suSlugifyName($fileNameShow1) . '-downloadcsv', $permissions)) {
                                                                    $chk_downloadcsv = 'checked="checked"';
                                                                } else {
                                                                    $chk_downloadcsv = '';
                                                                }
                                                                ?>
                                                                <label <?php echo $settingsClass; ?>><input <?php echo $chk_downloadcsv; ?> type="checkbox" name="module_<?php echo suSlugifyName($fileNameShow); ?>_downloadcsv" value="<?php echo $module_prefix; ?><?php echo suSlugifyName($fileNameShow1); ?>-downloadcsv<?php echo $module_postfix; ?>" id="module_<?php echo suSlugifyName($fileNameShow); ?>_downloadcsv" onclick="doCheckUncheck(this, 'module_<?php echo suSlugifyName($fileNameShow); ?>_row', 'module_all_<?php echo suSlugifyName($fileNameShow); ?>_row')"/> Download CSV</label></td>

                                                            <!-- Download PDF -->
                                                            <td class="bgColor-lightGray">
                                                                <?php
                                                                if (in_array(suSlugifyName($fileNameShow1) . '-downloadpdf', $permissions)) {
                                                                    $chk_downloadpdf = 'checked="checked"';
                                                                } else {
                                                                    $chk_downloadpdf = '';
                                                                }
                                                                ?>

                                                                <label <?php echo $settingsClass; ?>><input <?php echo $chk_downloadpdf; ?> type="checkbox" name="module_<?php echo suSlugifyName($fileNameShow); ?>_downloadpdf" value="<?php echo $module_prefix; ?><?php echo suSlugifyName($fileNameShow1); ?>-downloadpdf<?php echo $module_postfix; ?>" id="module_<?php echo suSlugifyName($fileNameShow); ?>_downloadpdf" onclick="doCheckUncheck(this, 'module_<?php echo suSlugifyName($fileNameShow); ?>_row', 'module_all_<?php echo suSlugifyName($fileNameShow); ?>_row')"/> Download PDF</label></td>
                                                        </tr>
                                                        <?php
                                                    }
                                                }
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                    <p class="text-right mt-3">
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
                                $arg = array('type' => 'hidden', 'name' => 'group__ID', 'id' => 'group__ID', 'value' => $id);
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
