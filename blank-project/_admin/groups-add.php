<?php
include('../sulata/includes/config.php');
include('../sulata/includes/functions.php');
include('../sulata/includes/get-settings.php');
include('../sulata/includes/db-structure.php');

checkLogin();
$pageName = 'Add Groups';
$pageTitle = 'Add Groups';
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
                                    <a href="<?php echo ADMIN_URL; ?>groups<?php echo PHP_EXTENSION; ?>/?overlay=<?php echo $_GET['overlay']; ?>"><i class="fa fa-table"></i></a>
                                </div>
                            </div>
                            <form class="form-horizontal" action="<?php echo ADMIN_URL; ?>groups-remote<?php echo PHP_EXTENSION; ?>/add/" accept-charset="utf-8" name="suForm" id="suForm" method="post" target="<?php echo $form_target;?>" onsubmit="return doCheckboxCheck();" >
                                <input type="hidden" name="is_checked" id="is_checked" value="0"/>
                                <div class="gallery clearfix">
                                    <div class="row">

                                        <div class="col-12 col-md-6 col-lg-6">
                                            <label><?php echo $dbs_sulata_groups['group__Name_req']; ?><?php echo $dbs_sulata_groups['group__Name_title']; ?>:</label>
                                            <?php
                                            $arg = array('type' => $dbs_sulata_groups['group__Name_html5_type'], 'name' => 'group__Name', 'id' => 'group__Name', 'autocomplete' => 'off', 'maxlength' => $dbs_sulata_groups['group__Name_max'], 'value' => '', $dbs_sulata_groups['group__Name_html5_req'] => $dbs_sulata_groups['group__Name_html5_req'], 'class' => 'form-control');
                                            echo suInput('input', $arg);
                                            ?>
                                        </div>

                                        <div class="col-12 col-md-6 col-lg-6">
                                            <label><?php echo $dbs_sulata_groups['group__Status_req']; ?><?php echo $dbs_sulata_groups['group__Status_title']; ?>:</label>
                                            <?php
                                            $options = $dbs_sulata_groups['group__Status_array'];
                                            $js = "class='form-control'";
                                            echo suDropdown('group__Status', $options, 'Active', $js)
                                            ?>
                                        </div>


                                    </div>
                                    <div>&nbsp;</div>
                                    <div><label><input type="checkbox" name="check_all" id="check_all" onclick="doCheckUncheck(this, 'module_', 'check_all');"/> Toggle select.</label></div>

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
                                                            <!-- Check the row -->
                                                            <td><label><input type="checkbox" name="module_all_<?php echo suSlugifyName($fileNameShow); ?>_row" id="module_all_<?php echo suSlugifyName($fileNameShow); ?>_row" onclick="doCheckUncheck(this, 'module_<?php echo suSlugifyName($fileNameShow); ?>', 'check_all')"/> <i class="far fa-hand-point-right"></i></label></td>
                                                            <!-- View/Manage -->
                                                            <td><label><input type="checkbox" name="module_<?php echo suSlugifyName($fileNameShow); ?>_view" id="module_<?php echo suSlugifyName($fileNameShow); ?>_view" value="<?php echo $module_prefix; ?><?php echo suSlugifyName($fileNameShow1); ?>-view<?php echo $module_postfix; ?>" onclick="doCheckUncheck(this, 'module_<?php echo suSlugifyName($fileNameShow); ?>_row', 'module_all_<?php echo suSlugifyName($fileNameShow); ?>_row')"/> View</label></td>


                                                            <!-- Search -->
                                                            <td><label <?php echo $settingsClass; ?>><input type="checkbox" name="module_<?php echo suSlugifyName($fileNameShow); ?>_search" value="<?php echo $module_prefix; ?><?php echo suSlugifyName($fileNameShow1); ?>-search<?php echo $module_postfix; ?>" id="module_<?php echo suSlugifyName($fileNameShow); ?>_search" onclick="doCheckUncheck(this, 'module_<?php echo suSlugifyName($fileNameShow); ?>_row', 'module_all_<?php echo suSlugifyName($fileNameShow); ?>_row')"/> Search</label></td>


                                                            <!-- Add -->
                                                            <td><label <?php echo $settingsClass; ?>><input type="checkbox" name="module_<?php echo suSlugifyName($fileNameShow); ?>_add" value="<?php echo $module_prefix; ?><?php echo suSlugifyName($fileNameShow1); ?>-add<?php echo $module_post; ?>" id="module_<?php echo suSlugifyName($fileNameShow); ?>_add" onclick="doCheckUncheck(this, 'module_<?php echo suSlugifyName($fileNameShow); ?>_row', 'module_all_<?php echo suSlugifyName($fileNameShow); ?>_row')"/> Add</label></td>

                                                            <!-- Update/Edit -->
                                                            <td><label <?php echo $settingsClass; ?>><input type="checkbox" name="module_<?php echo suSlugifyName($fileNameShow); ?>_update" value="<?php echo $module_prefix; ?><?php echo suSlugifyName($fileNameShow1); ?>-update<?php echo $module_postfix; ?>" id="module_<?php echo suSlugifyName($fileNameShow); ?>_update" onclick="doCheckUncheck(this, 'module_<?php echo suSlugifyName($fileNameShow); ?>_row', 'module_all_<?php echo suSlugifyName($fileNameShow); ?>_row')"/> Update</label></td>

                                                            <!-- Inline Edit -->
                                                            <td><label <?php echo $settingsClass; ?>><input type="checkbox" name="module_<?php echo suSlugifyName($fileNameShow); ?>_inlineedit" value="<?php echo $module_prefix; ?><?php echo suSlugifyName($fileNameShow1); ?>-inlineedit<?php echo $module_postfix; ?>" id="module_<?php echo suSlugifyName($fileNameShow); ?>_update" onclick="doCheckUncheck(this, 'module_<?php echo suSlugifyName($fileNameShow); ?>_row', 'module_all_<?php echo suSlugifyName($fileNameShow); ?>_row')"/> Inline Edit</label></td>


                                                            <!-- Delete -->
                                                            <td><label <?php echo $settingsClass; ?>><input type="checkbox" name="module_<?php echo suSlugifyName($fileNameShow); ?>_delete" value="<?php echo $module_prefix; ?><?php echo suSlugifyName($fileNameShow1); ?>-delete<?php echo $module_postfix; ?>" id="module_<?php echo suSlugifyName($fileNameShow); ?>_delete" onclick="doCheckUncheck(this, 'module_<?php echo suSlugifyName($fileNameShow); ?>_row', 'module_all_<?php echo suSlugifyName($fileNameShow); ?>_row')"/> Delete</label></td>

                                                            <!-- Restore -->
                                                            <td><label <?php echo $settingsClass; ?>><input type="checkbox" name="module_<?php echo suSlugifyName($fileNameShow); ?>_restore" value="<?php echo $module_prefix; ?><?php echo suSlugifyName($fileNameShow1); ?>-restore<?php echo $module_postfix; ?>" id="module_<?php echo suSlugifyName($fileNameShow); ?>_restore" onclick="doCheckUncheck(this, 'module_<?php echo suSlugifyName($fileNameShow); ?>_row', 'module_all_<?php echo suSlugifyName($fileNameShow); ?>_row')"/> Restore</label></td>

                                                            <!-- Duplicate -->
                                                            <td><label <?php echo $settingsClass; ?>><input type="checkbox" name="module_<?php echo suSlugifyName($fileNameShow); ?>_duplicate" value="<?php echo $module_prefix; ?><?php echo suSlugifyName($fileNameShow1); ?>-duplicate<?php echo $module_postfix; ?>" id="module_<?php echo suSlugifyName($fileNameShow); ?>_duplicate" onclick="doCheckUncheck(this, 'module_<?php echo suSlugifyName($fileNameShow); ?>_row', 'module_all_<?php echo suSlugifyName($fileNameShow); ?>_row')"/> Duplicate</label></td>

                                                            <!-- Sort -->
                                                            <td class="bgColor-lightGray"><label <?php echo $settingsClass; ?>><input type="checkbox" name="module_<?php echo suSlugifyName($fileNameShow); ?>_sort" value="<?php echo $module_prefix; ?><?php echo suSlugifyName($fileNameShow1); ?>-sort<?php echo $module_postfix; ?>" id="module_<?php echo suSlugifyName($fileNameShow); ?>_sort" onclick="doCheckUncheck(this, 'module_<?php echo suSlugifyName($fileNameShow); ?>_row', 'module_all_<?php echo suSlugifyName($fileNameShow); ?>_row')"/> Sort</label></td>

                                                            <!-- Download CSV -->
                                                            <td class="bgColor-lightGray"><label <?php echo $settingsClass; ?>><input type="checkbox" name="module_<?php echo suSlugifyName($fileNameShow); ?>_downloadcsv" value="<?php echo $module_prefix; ?><?php echo suSlugifyName($fileNameShow1); ?>-downloadcsv<?php echo $module_postfix; ?>" id="module_<?php echo suSlugifyName($fileNameShow); ?>_downloadcsv" onclick="doCheckUncheck(this, 'module_<?php echo suSlugifyName($fileNameShow); ?>_row', 'module_all_<?php echo suSlugifyName($fileNameShow); ?>_row')"/> Download CSV</label></td>

                                                            <!-- Download PDF -->
                                                            <td class="bgColor-lightGray"><label <?php echo $settingsClass; ?>><input type="checkbox" name="module_<?php echo suSlugifyName($fileNameShow); ?>_downloadpdf" value="<?php echo $module_prefix; ?><?php echo suSlugifyName($fileNameShow1); ?>-downloadpdf<?php echo $module_postfix; ?>" id="module_<?php echo suSlugifyName($fileNameShow); ?>_downloadpdf" onclick="doCheckUncheck(this, 'module_<?php echo suSlugifyName($fileNameShow); ?>_row', 'module_all_<?php echo suSlugifyName($fileNameShow); ?>_row')"/> Download PDF</label></td>
                                                        </tr>
                                                        <?php
                                                    }
                                                }
                                            }
                                            ?>
                                        </tbody>
                                    </table>
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
