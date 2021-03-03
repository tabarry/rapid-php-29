<!-- Title here -->
<title><?php echo strip_tags($pageTitle) .' - '.$getSettings['site_name']; ?></title>
<noscript>
<meta http-equiv="refresh" content="0;url=<?php echo NOSCRIPT_URL; ?>"/>
</noscript>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- Styles -->
<!-- Bootstrap CSS -->
<link href="<?php echo BASE_URL; ?>default-assets/css/bootstrap/bootstrap.min.css" rel="stylesheet">
<link href="<?php echo BASE_URL; ?>sulata/css/framework.css" rel="stylesheet" type="text/css"/>
<!-- This Site -->
<link href="<?php echo BASE_URL; ?>site-assets/css/site.css" rel="stylesheet" type="text/css"/>
<?php

if ($_COOKIE['ck_theme'] == '') {
    $_SESSION[SESSION_PREFIX . 'user__Theme'] = 'default';
}else{
    $_SESSION[SESSION_PREFIX . 'user__Theme']=$_COOKIE['ck_theme'];
}
?>
<link id="themeCss" href="<?php echo BASE_URL; ?>sulata/css/themes/<?php echo $_SESSION[SESSION_PREFIX . 'user__Theme']; ?>/theme.css" rel="stylesheet">
<!-- BOOTSTRAP -->
<script src="<?php echo BASE_URL; ?>default-assets/js/jquery.js" type="text/javascript"></script>
<script src="<?php echo BASE_URL; ?>default-assets/js/bootstrap/bootstrap.min.js" type="text/javascript"></script>

<!-- Chosen CSS -->
<link href="<?php echo BASE_URL; ?>sulata/chosen/bootstrap-chosen.css" rel="stylesheet" type="text/css"/>
<!-- JQUI -->
<link href="<?php echo BASE_URL; ?>sulata/jquery-ui/css/smoothness/jquery-ui-1.9.2.custom.min.css" rel="stylesheet" type="text/css" />
<!-- Font Awesome -->
<link rel="stylesheet" href="<?php echo BASE_URL; ?>default-assets/css/font-awesome/css/all.min.css" type="text/css" media="screen" />
<!-- CK Editor -->
<script src="<?php echo BASE_URL; ?>sulata/ckeditor/ckeditor.js"></script>
<!-- jQuery -->
<script src="<?php echo BASE_URL; ?>sulata/jquery-ui/js/jquery-1.8.3.js" type="text/javascript"></script>
<!-- Chosen JQuery -->
<script src="<?php echo BASE_URL; ?>sulata/chosen/chosen.jquery.js" type="text/javascript"></script>
<!-- Other JS files go in the footer -->