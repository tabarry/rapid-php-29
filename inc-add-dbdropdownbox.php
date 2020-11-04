<?php

$thisVal = "''";
if ($doUpdate == TRUE) {
    $thisVal = " suUnstrip(\$row['" . $_POST['frmField'][$i] . "'])";
}
//Get dropdown text
$tableFieldText = explode(".", $_POST['frmForeignkeytext'][$i]);
$table = $tableFieldText[0];
$fieldText = $tableFieldText[1];
//Get dropdown value
$tableFieldId = explode(".", $_POST['frmForeignkeyvalue'][$i]);
$table = $tableFieldId[0];
$fieldId = $tableFieldId[1];
//add page name
$addpage = explode('_', $table);
$addpage = $addpage[1];
//Get felf prefix
$fieldPrefix1 = explode('__', $tableFieldText[1]);
$fieldPrefix1 = $fieldPrefix1[0];


$addCode .= "
<div class=\"col-12 col-md-" . $_POST['frmColumnCount'][$i] . "\">        
<label><?php echo \$dbs_" . $_POST['table'] . "['" . $_POST['frmField'][$i] . "_req']; ?><?php echo \$dbs_" . $_POST['table'] . "['" . $_POST['frmField'][$i] . "_title']; ?>:
<?php if (\$addAccess == 'true') { ?>    


<!-- MODAL WINDOW -->
                                                    <button type=\"button\" class=\"btn btn-dark btn-sm\" data-toggle=\"modal\" data-target=\"#{$addpage}-add\">
                                                        <i class=\"fa fa-plus\"></i>
                                                    </button>
                                                    <?php suModalWindow('" . $addpage . "-add', ADMIN_URL . '" . $addpage . "-add'.PHP_EXTENSION.'/?overlay=yes'); ?>

<a onclick=\"suReload('" . $_POST['frmField'][$i] . "','<?php echo ADMIN_URL; ?>','<?php echo suCrypt('" . $table . "',TRUE);?>','<?php echo suCrypt('" . $fieldId . "',TRUE);?>','<?php echo suCrypt('" . $fieldText . "',TRUE);?>');\" href=\"javascript:;\" class=\"btn btn-dark btn-sm\"><i class=\"fa fa-undo\"></i></a>    
<?php } ?>    
</label>
                                <?php
                                
                                \$sql = \"SELECT " . $fieldId . " AS f1, " . $fieldText . " AS f2 FROM " . $table . " where " . $fieldPrefix1 . "__dbState='Live' ORDER BY f2\";
                                \$options = suFillDropdown(\$sql);
                               \$js = \"class='form-control'\";
                                echo suDropdown('" . $_POST['frmField'][$i] . "', \$options, " . $thisVal . ",\$js)
                                ?>
</div>
";
?>