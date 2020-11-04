<?php
include('../sulata/includes/config.php');
include('../sulata/includes/functions.php');
include('../sulata/includes/get-settings.php');
include('../sulata/includes/db-structure.php');
checkLogin();
$pageName = 'Sort Links';
$pageTitle = 'Sort Links';

//Populate modules
$dir = './';
$dir = scandir($dir);
sort($dir);
$del = '';
foreach ($dir as $file) {
    if ((!in_array($file, $sidebarExclude)) && ($file[0] != '.')) {
        if ((!stristr($file, '-add')) && (!stristr($file, '-remote')) && (!stristr($file, '-update')) && (!stristr($file, '-sort')) && (!stristr($file, 'inc-'))) {
            $fileNameActual = str_replace('.php', '', $file);
            $fileName = str_replace('-', ' ', $fileNameActual);
            $fileNameShow = str_replace('_', ' ', $fileName);
            if (stristr($fileNameShow, 'faqs')) {
                $fileNameShow = 'FAQs';
            }
            $fileNameShow = ucwords($fileName);
            $del .= "AND link__Link !='{$fileNameShow}' "; //DO NOT adjust spaces in this line
            $sql = "INSERT IGNORE INTO sulata_links SET link__Link='" . suStrip($fileNameShow) . "',link__File='" . $file . "',link__Last_Action_On='" . date('Y-m-d H:i:s') . "',link__Last_Action_By='" . $_SESSION[SESSION_PREFIX . 'user__Name'] . "',link__dbState='Live'";
            suQuery($sql);
        }
    }
}
//==
//Delete the removed modules from DB
$del = substr($del, 3);
$sql = "DELETE FROM sulata_links WHERE ({$del}) ";
suQuery($sql);
//==
$sortable = TRUE;
$getSettings['page_size'] = $getSettings['sorting_page_size'];
//Make select statement. The $SqlFrom is also used in $sqlP below.
$sqlSelect = "SELECT link__ID,link__Link,link__Icon ";
$sqlFrom = " FROM sulata_links WHERE link__dbState='Live'";
$sql = $sqlSelect . $sqlFrom;
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
                            <form class="form-horizontal" action="<?php echo ADMIN_URL; ?>links-remote<?php echo PHP_EXTENSION; ?>/sort/" accept-charset="utf-8" name="suForm" id="suForm" method="post" target="remote" >
                                <ul id="sortable">

                                    <?php
                                    if (!$_GET['start']) {
                                        $_GET['start'] = 0;
                                    }
                                    if (!$_GET['sr']) {
                                        $sr = 0;
                                    } else {
                                        $sr = $_GET['sr'];
                                    }
                                    $sort = " ORDER BY link__Sort_Order DESC ";
//Get records from database

                                    $sql = "$sql $where $sort LIMIT " . $_GET['start'] . "," . $getSettings['page_size'];

                                    $result = suQuery($sql);
                                    $numRows = $result['num_rows'];
                                    foreach ($result['result'] as $row) {
                                        ?>
                                        <li class="ui-state-default">
                                            <div class="row">
                                                <div class="col-8">
                                                    <sup><i class="fa fa-sort"></i></sup>
                                                    <?php echo $sr = $sr + 1; ?>.
                                                    <?php echo suUnstrip($row['link__Link']); ?>
                                                    <input type="hidden" name="link__ID[]" value="<?php echo $row['link__ID']; ?>"/>
                                                </div>
                                                <div class="col-4">
                                                    <input name="link__Icon[]" type="text" class="form-control" value="<?php echo suUnstrip($row['link__Icon']); ?>" maxlength="<?php echo $dbs_sulata_links['link__Icon_max'];?>"/>
                                                </div>
                                            </div>
                                        </li>

                                    <?php } ?>

                                </ul>
                                <p class="text-right mt-2">
                                    <?php
                                    $arg = array('type' => 'submit', 'name' => 'Submit', 'id' => 'Submit', 'value' => 'Submit', 'class' => 'btn btn-dark');
                                    echo suInput('input', $arg);
                                    ?>                              
                                </p>
                                <p>&nbsp;</p>
                            </form>
                            <!-- /TABLE -->
                            <?php
                            $sqlP = "SELECT COUNT(link__ID) AS totalRecs $sqlFrom $where";
                            suPaginate($sqlP);
                            ?>

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