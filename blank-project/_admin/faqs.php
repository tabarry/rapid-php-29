<?php
include('../sulata/includes/config.php');
include('../sulata/includes/functions.php');
include('../sulata/includes/get-settings.php');
include('../sulata/includes/db-structure.php');
checkLogin();
$pageName = 'Manage FAQs';
$pageTitle = 'Manage FAQs';


//Make select statement. The $SqlFrom is also used in $sqlP below.
$sqlSelect = "SELECT faq__ID,faq__Question,faq__Status ";
$sqlFrom = " FROM sulata_faqs WHERE faq__dbState='Live'";
$sql = $sqlSelect . $sqlFrom;

//Download CSV
if (suSegment(1) == 'stream-csv' && $downloadAccessCSV == TRUE) {
    $outputFileName = 'faqs.csv';
    $headerArray = array('Question', 'Status');
    suSqlToCSV($sql, $headerArray, $outputFileName);
    exit;
}
//Download PDF
if (suSegment(1) == 'stream-pdf' && $downloadAccessPDF == TRUE) {
    $outputFileName = 'faqs.pdf';
    $fieldsArray = array('faq__Question', 'faq__Status');
    $headerArray = array('Question', 'Status');
    suSqlToPDF($sql, $headerArray, $fieldsArray, $outputFileName);
    exit;
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
                            <div id="message-area" class="bg-success text-white su-hide p-2 mb-1 mt-1">
                                <p></p>
                            </div>
                            <!--SU STARTS-->
                            <h2><?php echo $pageTitle; ?></h2>

                            <?php if ($searchAccess == TRUE) { ?>
                            <div id="search-collection" class="su-hide">
                                    <form class="form-horizontal" name="searchForm0" id="searchForm0" method="get" action="">
                                        <?php
                                        //Fill search field
                                        if ($_GET['search_field'] == 'faq__Question') {
                                            $faqs__Search = $_GET['q'];
                                        } else {
                                            $faqs__Search = '';
                                        }
                                        ?>
                                        <fieldset class="mt-3 mb-3">
                                            <div class="row">
                                                <div class="col-6">
                                                    <input id="q" type="search" value="<?php echo $faqs__Search; ?>" name="q" class="form-control" autocomplete="off" autofocus="autofocus" placeholder="Search by Question">
                                                    <input type="hidden" name="overlay" value="<?php echo $_GET['overlay']; ?>]"/>
                                                    <input type="hidden" name="search_field" value="faq__Question"/>
                                                </div>
                                                <div class="col-6">
                                                    <button type="submit" class="btn btn-dark"><i class="fa fa-search"></i></button>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </form>
                                </div>
                                <?php if ($_GET['q']) { ?>
                                    <div class="lineSpacer clear"></div>
                                    <div class="text-right"><a href="<?php echo ADMIN_URL; ?>faqs<?php echo PHP_EXTENSION; ?>/?overlay=<?php echo $_GET['overlay']; ?>">Clear search.</a></div>
                                <?php } ?>

                                <div id="search-expand" class="row su-hide">
                                    <div class="col-12 col-md-6">
                                        <input type="search" name="search" id="search" autocomplete="off" class="form-control" placeholder="Search.." onclick="$('#search-expand').hide('slow'); $('#search-collection').show('slow');doFocusField('q');"/>
                                    </div>
                                </div>
                            <?php } ?>
                            
                            <div id="table-area">
                                <?php if ($addAccess == TRUE) { ?>
                                    <a href="<?php echo ADMIN_URL; ?>faqs-add<?php echo PHP_EXTENSION; ?>/?overlay=<?php echo $_GET['overlay']; ?>" class="btn btn-dark"><i class="fa fa-plus"></i></a>
                                <?php } ?>
                                <?php if ($sortable == TRUE) { ?>
                                    <a href="<?php echo ADMIN_URL; ?>faqs-sort<?php echo PHP_EXTENSION; ?>/?overlay=<?php echo $_GET['overlay']; ?>" class="btn btn-dark"><i class="fa fa-sort-alpha-down-alt"></i></a>
                                <?php } ?>
                            </div>
                            <div class="lineSpacer clear"></div>
                            <?php
                            $fieldsArray = array('faq__Question', 'faq__Status');

                            //faq__Question
                            if (in_array('faq__Question', $fieldsArray)) {
                                $faq__Question_th = '<a href="' . ADMIN_URL . 'faqs' . PHP_EXTENSION . '/?overlay=' . $_GET['overlay'] . '&f=faq__Question&sort=asc&q=' . $_GET['q'] . '&search_field=' . $_GET['search_field'] . '">Question</a>';
                                if ($_GET['f'] == 'faq__Question' && $_GET['sort'] == 'desc') {
                                    $faq__Question_th = '<a href="' . ADMIN_URL . 'faqs' . PHP_EXTENSION . '/?overlay=' . $_GET['overlay'] . '&f=faq__Question&sort=asc&q=' . $_GET['q'] . '&search_field=' . $_GET['search_field'] . '">Question <i class="fa fa-caret-down"></i></a>';
                                } else if ($_GET['f'] == 'faq__Question' && $_GET['sort'] == 'asc') {
                                    $faq__Question_th = '<a href="' . ADMIN_URL . 'faqs' . PHP_EXTENSION . '/?overlay=' . $_GET['overlay'] . '&f=faq__Question&sort=desc&q=' . $_GET['q'] . '&search_field=' . $_GET['search_field'] . '">Question <i class="fa fa-caret-up"></i></a>';
                                }
                            } else {
                                $faq__Question_th = 'Question';
                            }

                            //faq__Status
                            if (in_array('faq__Status', $fieldsArray)) {
                                $faq__Status_th = '<a href="' . ADMIN_URL . 'faqs' . PHP_EXTENSION . '/?overlay=' . $_GET['overlay'] . '&f=faq__Status&sort=asc&q=' . $_GET['q'] . '&search_field=' . $_GET['search_field'] . '">Status</a>';
                                if ($_GET['f'] == 'faq__Status' && $_GET['sort'] == 'desc') {
                                    $faq__Status_th = '<a href="' . ADMIN_URL . 'faqs' . PHP_EXTENSION . '/?overlay=' . $_GET['overlay'] . '&f=faq__Status&sort=asc&q=' . $_GET['q'] . '&search_field=' . $_GET['search_field'] . '">Status <i class="fa fa-caret-down"></i></a>';
                                } else if ($_GET['f'] == 'faq__Status' && $_GET['sort'] == 'asc') {
                                    $faq__Status_th = '<a href="' . ADMIN_URL . 'faqs' . PHP_EXTENSION . '/?overlay=' . $_GET['overlay'] . '&f=faq__Status&sort=desc&q=' . $_GET['q'] . '&search_field=' . $_GET['search_field'] . '">Status <i class="fa fa-caret-up"></i></a>';
                                }
                            } else {
                                $faq__Status_th = 'Status';
                            }
                            ?>
                            <!-- TABLE -->

                            <table width="100%" class="table table-hover table-bordered tbl">
                                <thead class="thead-dark">
                                    <tr>
                                        <th style="width:5%">
                                            <?php echo SERIAL; ?>
                                        </th>

                                        <th style="width:43%"><?php echo $faq__Question_th; ?></th>
                                        <th style="width:43%"><?php echo $faq__Status_th; ?></th>
                                        <?php if (($editAccess == TRUE) || ($deleteAccess == TRUE) || ($duplicateAccess == TRUE) || ($restoreAccess == TRUE)) { ?>
                                            <th style="width:10%">&nbsp;</th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($_GET['q'] != '') {
                                        $where .= " AND " . $_GET['search_field'] . " LIKE '%" . suStrip($_GET['q']) . "%' ";
                                    }

                                    if (!$_GET['start']) {
                                        $_GET['start'] = 0;
                                    }
                                    if (!$_GET['sr']) {
                                        $sr = 0;
                                    } else {
                                        $sr = $_GET['sr'];
                                    }
                                    if (!$_GET['sort']) {
                                        $sort = " ORDER BY faq__Question";
                                    } else {
                                        $sort = " ORDER BY " . $_GET['f'] . " " . $_GET['sort'];
                                    }
//Get records from database

                                    $sql = "$sql $where $sort LIMIT " . $_GET['start'] . "," . $getSettings['page_size'];

                                    $result = suQuery($sql);
                                    $numRows = $result['num_rows'];
                                    foreach ($result['result'] as $row) {
                                        //Make inline edit false
                                        if ($inlineEditAccess == TRUE) {
                                            $inlineCssClass = 'inline-field';
                                        } else {
                                            $inlineCssClass = '';
                                        }
                                        ?>
                                        <tr id="card_<?php echo $row['faq__ID']; ?>">
                                            <td>
                                                <?php echo $sr = $sr + 1; ?>.
                                            </td>
                                            <td><?php
                                                if ($inlineEditAccess == TRUE) {
                                                    $onDblClick = "ondblclick=\"doToggleInlineFields('inlineForm_faq__Question_" . $row['faq__ID'] . "', 'faq__Question_" . $row['faq__ID'] . "', 'show_form')\"";
                                                } else {
                                                    $onDblClick = '';
                                                }
                                                ?><span class="<?php echo $inlineCssClass; ?>" id="_____wrapper_____faq__Question_<?php echo $row['faq__ID']; ?>" <?php echo $onDblClick; ?>><?php echo suUnstrip($row['faq__Question']); ?></span><?php suMakeInlineEdit('faq__Question', 'faqs', suUnstrip($row['faq__Question']), 'faq__ID', $row['faq__ID']); ?></td>
                                            <td><?php echo suUnstrip($row['faq__Status']); ?></td>


                                            <?php if (($editAccess == TRUE) || ($deleteAccess == TRUE) || ($duplicateAccess == TRUE) || ($restoreAccess == TRUE) ) { ?>

                                                <td class="text-center">
                                                    <!-- EDIT -->
                                                    <?php if ($editAccess == TRUE) { ?>
                                                        <a title="<?php echo EDIT; ?>" id="card_<?php echo $row['faq__ID']; ?>_edit" href="<?php echo ADMIN_URL; ?>faqs-update<?php echo PHP_EXTENSION; ?>/<?php echo $row['faq__ID']; ?>/?overlay=<?php echo $_GET['overlay']; ?>"><i class="fa fa-edit"></i></a>

                                                    <?php } ?>
                                                    <!-- DUPLICATE -->
                                                    <?php if ($duplicateAccess == TRUE) { ?>
                                                        <a title="<?php echo DUPLICATE; ?>" id="card_<?php echo $row['faq__ID']; ?>_duplicate" href="<?php echo ADMIN_URL; ?>faqs-update<?php echo PHP_EXTENSION; ?>/<?php echo $row['faq__ID']; ?>/duplicate/?overlay=<?php echo $_GET['overlay']; ?>"><i class="fa fa-copy"></i></a>
                                                    <?php } ?>
                                                    <!-- DELETE -->
                                                    <?php if ($deleteAccess == TRUE) { ?>
                                                        <a title="<?php echo DELETE; ?>" id="card_<?php echo $row['faq__ID']; ?>_del" onclick="return delById('card_<?php echo $row['faq__ID']; ?>', '<?php echo CONFIRM_DELETE_RESTORE; ?>')" href="<?php echo ADMIN_URL; ?>faqs-remote<?php echo PHP_EXTENSION; ?>/delete/<?php echo $row['faq__ID']; ?>/" target="remote"><i class="fa fa-times text-danger"></i></a>
                                                    <?php } ?>

                                                    <!-- RESTORE -->
                                                    <?php if ($restoreAccess == TRUE) { ?>
                                                        <a title="<?php echo RESTORE; ?>" id="card_<?php echo $row['faq__ID']; ?>_restore" href="<?php echo ADMIN_URL; ?>faqs-remote<?php echo PHP_EXTENSION; ?>/restore/<?php echo $row['faq__ID']; ?>/" target="remote" style="display:none"><i class="fa fa-undo"></i></a>
                                                    <?php } ?>


                                                </td>
                                            <?php } ?>

                                        </tr>
                                    <?php } ?>

                                </tbody>
                            </table>
                            <!-- /TABLE -->
                            <?php
                            $sqlP = "SELECT COUNT(faq__ID) AS totalRecs $sqlFrom $where";
                            suPaginate($sqlP);
                            ?>
                            <!-- DOWNLOAD CSV -->
                            <?php if ($downloadAccessCSV == TRUE && $numRows > 0) { ?>
                                <p class="text-right mb-2"><a target="remote" href="<?php echo ADMIN_URL; ?>faqs<?php echo PHP_EXTENSION; ?>/stream-csv/" class="btn btn-dark nounderline"><i class="fa fa-download"></i> <?php echo DOWNLOAD_CSV; ?></a></p>
                            <?php } ?>
                            <!-- DOWNLOAD PDF -->
                            <?php if ($downloadAccessPDF == TRUE && $numRows > 0) { ?>
                            <p class="text-right mb-2"><a target="remote" href="<?php echo ADMIN_URL; ?>faqs<?php echo PHP_EXTENSION; ?>/stream-pdf/" class="btn btn-dark"><i class="fa fa-download"></i> <?php echo DOWNLOAD_PDF; ?></a></p>
                                <p>&nbsp;</p>
                            <?php } ?>
                            <?php if ($searchAccess == TRUE) { ?>                                
                                <script>
                                    $(document).ready(function () {
                                        doFocusField('search');
    <?php if ($_GET['q']) { ?>
                                            doSearchExpand('more');
    <?php } else { ?>
                                            doSearchExpand('less');
    <?php } ?>
                                        //If only 1 search form, just show more option
                                        c = doSearchForm('searchForm');
                                        if (c == 1) {
                                            doSearchExpand('more');
                                        }
                                    });
                                </script>
                            <?php } ?>

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