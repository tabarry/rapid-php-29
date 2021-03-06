<?php
include('../sulata/includes/config.php');
include('../sulata/includes/functions.php');
include('../sulata/includes/get-settings.php');
include('../sulata/includes/db-structure.php');
checkLogin();
$pageName = 'Sort FAQs';
$pageTitle = 'Sort FAQs';

$sortable = TRUE;
$getSettings['page_size'] = $getSettings['sorting_page_size'];
//Make select statement. The $SqlFrom is also used in $sqlP below.
$sqlSelect = "SELECT faq__ID,faq__Question ";
$sqlFrom = " FROM sulata_faqs WHERE faq__dbState='Live'";
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
                            <div id="message-area" class="bg-success text-white su-hide">
                                <p></p>
                            </div>
                            <!--SU STARTS-->
                            <div class="row mb-2">
                                <div class="col-6"><h2><?php echo $pageTitle; ?></h2></div>
                                <div class="col-6 text-right"><a href="<?php echo ADMIN_URL; ?>faqs<?php echo PHP_EXTENSION; ?>/?overlay=<?php echo $_GET['overlay']; ?>"><i class="fa fa-table"></i></a></div>
                            </div>
                            <!-- TABLE -->
                            <form class="form-horizontal" action="<?php echo ADMIN_URL; ?>faqs-remote<?php echo PHP_EXTENSION; ?>/sort/" accept-charset="utf-8" name="suForm" id="suForm" method="post" target="<?php echo $form_target;?>" >
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
                                    $sort = " ORDER BY faq__Sort_Order DESC ";
//Get records from database

                                    $sql = "$sql $where $sort LIMIT " . $_GET['start'] . "," . $getSettings['page_size'];

                                    $result = suQuery($sql);
                                    $numRows = $result['num_rows'];
                                    foreach ($result['result'] as $row) {
                                        ?>
                                        <li class="ui-state-default">
                                            <sup><i class="fa fa-sort"></i></sup>
                                            <?php echo $sr = $sr + 1; ?>.
                                            <?php echo suUnstrip($row['faq__Question']); ?>
                                            <input type="hidden" name="faq__ID[]" value="<?php echo $row['faq__ID']; ?>"/>
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
                            $sqlP = "SELECT COUNT(faq__ID) AS totalRecs $sqlFrom $where";
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
