<?php

$thisVal = "''";
if ($doUpdate == TRUE) {
    $thisVal = " urldecode(\$row['" . $_POST['frmField'][$i] . "'])";
}
$addCode .="
<div class=\"col-12 col-md-" . $_POST['frmColumnCount'][$i] . "\">
                                                <!-- //MEDIA MANAGER -->
                                                <div class=\"pull-right\">

                                                <!-- MODAL WINDOW -->
                                                <a title=\"Media..\" href=\"javascript:;\" data-toggle=\"modal\" data-target=\"#media\"><i class=\"fa fa-images\"></i></a>
                                                <?php suModalWindow('media', ADMIN_URL . 'media'.PHP_EXTENSION.'/?overlay=yes'); ?>

                                                </div>
                                                <!-- MEDIA MANAGER// -->
<label><?php echo \$dbs_" . $_POST['table'] . "['" . $_POST['frmField'][$i] . "_req']; ?><?php echo \$dbs_" . $_POST['table'] . "['" . $_POST['frmField'][$i] . "_title']; ?>:</label>
                                <?php
                                \$arg = array('type' => \$dbs_" . $_POST['table'] . "['" . $_POST['frmField'][$i] . "_html5_type'], 'name' => '" . $_POST['frmField'][$i] . "', 'id' => '" . $_POST['frmField'][$i] . "');
                                echo suInput('textarea', \$arg," . $thisVal . ",TRUE);
                                 suCKEditor('" . $_POST['frmField'][$i] . "');
                                ?>
</div>
";
?>
