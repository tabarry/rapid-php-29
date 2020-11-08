<!-- Javascript files -->
<!-- jQuery UI -->
<script src="<?php echo BASE_URL; ?>sulata/js/jquery-ui.min.js"></script>
<!-- jQuery slim scroll -->
<script src="<?php echo BASE_URL; ?>sulata/js/jquery.slimscroll.min.js"></script>
<!-- Respond JS for IE8 -->
<script src="<?php echo BASE_URL; ?>sulata/js/respond.min.js"></script>
<!-- HTML5 Support for IE -->
<script src="<?php echo BASE_URL; ?>sulata/js/html5shiv.js"></script>
<!-- Framework JS -->
<script type="text/javascript" src="<?php echo BASE_URL; ?>sulata/js/framework.js"></script>
<!-- This Site JS -->
<script type="text/javascript" src="<?php echo BASE_URL; ?>site-assets/js/site.js"></script>
<!-- JQUI -->
<script src="<?php echo BASE_URL; ?>sulata/jquery-ui/js/jquery-ui-1.9.2.custom.min.js" type="text/javascript"></script>
<!-- CK Editor -->
<script src="<?php echo BASE_URL; ?>sulata/ckeditor/ckeditor.js"></script>

<!-- Javascript for this page -->
<?php if ($sortable == TRUE) { ?>
    <script src="<?php echo BASE_URL; ?>sulata/jquery-ui/js/jquery-ui-1.12.1.js" type="text/javascript"></script>
    <script>
        $(function () {
            if ($("#sortable")) {
                $("#sortable").sortable();
                $("#sortable").disableSelection();
            }
        });
    </script>
<?php } ?>
