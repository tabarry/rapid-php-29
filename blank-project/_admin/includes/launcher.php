<?php if ($_GET['overlay'] != 'yes') { ?>
    <div class="launcher">
        <div class="row">
            <div class="col-4 col-md-4 col-lg-12">
                <a title="Launcher" href="javascript:;" onclick="doToggleLaunchpad(true);"><img src="<?php echo BASE_URL; ?>sulata/icons/launcher.png" alt="Launcher"/></a>
            </div>
            <div class="col-4 col-md-4 col-lg-12">
                <a title="Settings" href="<?php echo ADMIN_URL; ?>settings<?php echo PHP_EXTENSION; ?>/"><img src="<?php echo BASE_URL; ?>sulata/icons/settings.png" alt="Settings"/></a>
            </div>
            <div class="col-4 col-md-4 col-lg-12">
                <a title="Log Out" href="<?php echo ADMIN_URL; ?>login<?php echo PHP_EXTENSION; ?>/?do=logout" target="remote"><img src="<?php echo BASE_URL; ?>sulata/icons/log-out.png" alt="Log Out"/></a>
            </div>
        </div>
    </div>
<?php } ?>
