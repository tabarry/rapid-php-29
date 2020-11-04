<?php if ($_GET['overlay'] != 'yes') { ?>
    <div class="row">
        <div class="col-12 col-md-6">
            <h1>
                <a href="<?php echo ADMIN_URL; ?>"><?php echo $getSettings['site_name']; ?></a>
                <small><?php echo $_SESSION[SESSION_PREFIX . 'user__Name']; ?></small>
            </h1> 
        </div>
        <div class="col-12 col-md-6 text-right">
            <h3><?php echo $pageTitle; ?></h3>    
        </div>
    </div>
<?php } ?>
