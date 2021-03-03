<?php
include('../sulata/includes/config.php');
include('../sulata/includes/functions.php');
include('../sulata/includes/get-settings.php');
include('../sulata/includes/db-structure.php');

$qr_session = session_id();
if (!isset($_GET['do']) && $_GET['do'] == '') {

//Unset login sessions
    $_SESSION[SESSION_PREFIX . 'user__ID'] = '';
    $_SESSION[SESSION_PREFIX . 'user__Name'] = '';
    $_SESSION[SESSION_PREFIX . 'user__Email'] = '';
    $_SESSION[SESSION_PREFIX . 'user__Picture'] = '';
    $_SESSION[SESSION_PREFIX . 'user__Type'] = '';
    $_SESSION[SESSION_PREFIX . 'user__Status'] = '';
    $_SESSION[SESSION_PREFIX . 'user__Theme'] = '';
}
//--
//Validation array
$validateAsArray = array('user__Name_validateas' => 'required', 'user__Phone_validateas' => 'required', 'user__Email_validateas' => 'email', 'user__Password_validateas' => 'required', 'user__Status_validateas' => 'enum', 'user__Picture_validateas' => 'image',);
//---------

/* login */
if ($_GET['do'] == 'login') {
    $sql = "SELECT user__ID, user__Name,user__UID, user__Email, user__Picture,user__Status,user__Theme,user__Type,user__Password_Reset FROM sulata_users WHERE user__Email='" . suStrip($_POST['user__Email']) . "' AND (user__Password='" . crypt(suStrip($_POST['user__Password']), API_KEY) . "' OR user__Temp_Password='" . crypt(suStrip($_POST['user__Password']), API_KEY) . "') AND user__dbState='Live'";
    $result = suQuery($sql);

    if ($result['num_rows'] == 1) {

//Set sessions
        $_SESSION[SESSION_PREFIX . 'user__ID'] = $result['result'][0]['user__ID'];
        $_SESSION[SESSION_PREFIX . 'user__Name'] = suUnstrip($result['result'][0]['user__Name']);
        $_SESSION[SESSION_PREFIX . 'user__UID'] = $result['result'][0]['user__UID'];
        $_SESSION[SESSION_PREFIX . 'user__Email'] = suUnstrip($result['result'][0]['user__Email']);
        $_SESSION[SESSION_PREFIX . 'user__Picture'] = $result['result'][0]['user__Picture'];
        $_SESSION[SESSION_PREFIX . 'user__Status'] = $result['result'][0]['user__Status'];
        $_SESSION[SESSION_PREFIX . 'user__Type'] = $result['result'][0]['user__Type'];
        $_SESSION[SESSION_PREFIX . 'user__Theme'] = $result['result'][0]['user__Theme'];
        $_SESSION[SESSION_PREFIX . 'user__Password_Reset'] = $result['result'][0]['user__Password_Reset'];
        $_SESSION[SESSION_PREFIX . 'user__IP'] = suGetRealIpAddr();

        //Set theme in cookie
        setcookie('ck_theme', $_SESSION[SESSION_PREFIX . 'user__Theme'], time() + (COOKIE_EXPIRY_DAYS * 86400), '/');
        //Update password and new password
        $sql = "UPDATE sulata_users SET user__Password='" . crypt(suStrip($_POST['user__Password']), API_KEY) . "' AND user__Temp_Password='" . crypt(suStrip($_POST['user__Password']), API_KEY) . "' WHERE user__ID='" . $_SESSION[SESSION_PREFIX . 'user__ID'] . "'";
        //Update user IP
        $sql = "UPDATE sulata_users SET user__IP='" . $_SESSION[SESSION_PREFIX . 'user__IP'] . "' WHERE user__ID='" . $_SESSION[SESSION_PREFIX . 'user__ID'] . "'";
        suQuery($sql, 'update');
//Redirect
        suPrintJS("parent.suRedirect('" . ADMIN_URL . "');");
    } else {
        $vError = array();
//Validate entire form in one go using the DB Structure
//To skip validation set '*' to '' like: $dbs_sulata_users['user__ID_req']=''               
        suProcessForm($dbs_sulata_users, $validateAsArray);
        $dbs_sulata_users['user__Email_req'] = '';
//Print validation errors on parent
        $vError[] = INVALID_LOGIN;
        suValdationErrors($vError);
    }
    exit();
}
/* logout */
if ($_GET['do'] == 'logout') {
    $_SESSION[SESSION_PREFIX . 'user__ID'] = '';
    $_SESSION[SESSION_PREFIX . 'user__Name'] = '';
    $_SESSION[SESSION_PREFIX . 'user__Email'] = '';
    $_SESSION[SESSION_PREFIX . 'user__Picture'] = '';
    $_SESSION[SESSION_PREFIX . 'user__Status'] = '';
    $_SESSION[SESSION_PREFIX . 'user__Type'] = '';

    session_unset();
//Redirect
    suPrintJS("top.suRedirect('" . ADMIN_URL . "login" . PHP_EXTENSION . "/');");
    exit();
}
/* retrieve */
if ($_GET['do'] == 'retrieve') {
    $sql = "SELECT user__ID,user__Name FROM sulata_users WHERE user__Email='" . suStrip($_POST['user__Email']) . "' AND user__dbState='Live'";
    $result = suQuery($sql);
    $row = $result['result'][0];
    if ($result['num_rows'] == 1) {
        $temp_password = suGeneratePassword();
        //Update password
        $sql2 = "UPDATE sulata_users SET user__Temp_Password='" . crypt($temp_password, API_KEY) . "',user__Password_Reset='Yes' WHERE user__ID='" . $row['user__ID'] . "'";
        suQuery($sql2);
        $email = file_get_contents('../sulata/mails/lost-password.html');
        $email = str_replace('#NAME#', suUnstrip($row['user__Name']), $email);
        $email = str_replace('#SITE_NAME#', $getSettings['site_name'], $email);
        $email = str_replace('#EMAIL#', $_POST['user__Email'], $email);
        $email = str_replace('#URL#', BASE_URL, $email);
        $email = str_replace('#PASSWORD#', $temp_password, $email);
        $subject = sprintf(LOST_PASSWORD_SUBJECT, $getSettings['site_name']);
        //Send mails
        suMail($_POST['user__Email'], $subject, $email, $getSettings['site_name'], $getSettings['site_email'], TRUE);
//Redirect
        suPrintJS("alert('" . LOST_PASSWORD_DATA_SENT . "');parent.suRedirect('" . ADMIN_URL . "login" . PHP_EXTENSION . "/');");
    } else {
        $vError = array();
        $vError[] = NO_LOST_PASSWORD_DATA;
        suValdationErrors($vError);
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php include('includes/head.php'); ?>
        <script>
            $(document).ready(function () {
                //Disable submit button
                suToggleButton(1);
                //SET LOGIN FORM HEIGHT
                doSetLoginHeight();

                //Submit login form on enter
                $('#suForm input').keypress(function (e) {
                    if (e.which == 13) {
                        $('form#suForm').submit();
                        return false;    //<---- Add this line
                    }
                });
                //Submit reset form on enter
                $('#suForm2 input').keypress(function (e) {
                    if (e.which == 13) {
                        $('form#suForm2').submit();
                        return false;    //<---- Add this line
                    }
                });
            });

        </script> 
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-1 col-md-2 col-lg-4"></div>
                <div class="col-10 col-md-8 col-lg-4">
                    <div class="row" id="message-wrapper">
                        <div class="col-9 col-md-10">
                            <div id="error-area" class="bg-danger text-white su-hide pt-2 pb-1">
                                <ul></ul>
                            </div>    
                            <div id="message-area" class="bg-success text-white su-hide pt-2 mb-1 mt-1">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-3 col-md-2">
                            &nbsp;
                        </div>
                    </div>
                    <div id="login">
                        <!-- LOGIN -->

                        <form action="<?php echo ADMIN_URL; ?>login<?php echo PHP_EXTENSION; ?>/?do=login" accept-charset="utf-8" name="suForm" id="suForm" method="post" target="<?php echo $form_target; ?>" >
                            <div class="row" id="form-login">
                                <div class="col-9 col-md-10">
                                    
                                    <div>
                                        <?php
                                        $arg = array('type' => 'email', 'name' => 'user__Email', 'id' => 'user__Email', 'autocomplete' => 'off', 'maxlength' => $dbs_sulata_users['user__Email_max'], 'class' => 'form-control', 'placeholder' => $dbs_sulata_users['user__Email_req'] . $dbs_sulata_users['user__Email_title'], 'required' => 'required');
                                        echo suInput('input', $arg);
                                        ?>
                                    </div>
                                    <div>
                                        <?php
                                        $arg = array('type' => 'password', 'name' => 'user__Password', 'id' => 'user__Password', 'autocomplete' => 'off', 'maxlength' => $dbs_sulata_users['user__Password_max'], 'class' => 'form-control', 'placeholder' => $dbs_sulata_users['user__Password_req'] . $dbs_sulata_users['user__Password_title']);
                                        echo suInput('input', $arg);

                                        //Login button
                                        $arg = array('type' => 'submit', 'name' => 'Submit', 'id' => 'Submit', 'value' => 'Submit', 'class' => 'su-hide');
                                        echo suInput('button', $arg, 'Login', TRUE);
                                        ?>

                                    </div>
                                </div>
                                <div class="col-3 col-md-2">
                                    <a href="javascript:;" onclick="$('#suForm').submit();"><i id="login-button" class="fa fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6 col-sm-10 text-right">
                                    <a href="javascript:;" onclick="$('#suForm').hide();
                                            $('#suForm2').show();
                                            $('#error-area').hide();
                                            $('#success-area').hide();
                                            doSetResetHeight();"><i class="fa fa-question-circle size-200"></i></a>
                                </div>
                                <div class="col-6 col-sm-2">
                                    &nbsp;
                                </div>
                            </div>

                        </form>
                        <!-- LOST PASSWORD -->
                        <form action="<?php echo ADMIN_URL; ?>login<?php echo PHP_EXTENSION; ?>/?do=retrieve" accept-charset="utf-8" name="suForm2" id="suForm2" method="post" target="<?php echo $form_target; ?>" class="su-hide">
                            <!-- RESET FORM -->
                            <div class="row" id="form-reset">
                                <div class="col-8 col-sm-10">
                                    <div>
                                        <?php
                                        $arg = array('type' => 'email', 'name' => 'user__Email', 'id' => 'user__Email', 'autocomplete' => 'off', 'maxlength' => $dbs_sulata_users['user__Email_max'], 'class' => 'form-control', 'placeholder' => $dbs_sulata_faqs['user__Email_req'] . $dbs_sulata_faqs['user__Email_title'], 'placeholder' => 'Type email to reset password.', 'required' => 'required');
                                        echo suInput('input', $arg);

                                        //Login button
                                        $arg = array('type' => 'submit', 'name' => 'Submit', 'id' => 'Submit', 'value' => 'Submit', 'class' => 'su-hide');
                                        echo suInput('button', $arg, 'Login', TRUE);
                                        ?>
                                    </div>
                                </div>
                                <div class="col-4 col-sm-2">
                                    <a href="javascript:;" onclick="$('#suForm2').submit();"><i id="login-button2" class="fa fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <!-- BACK -->
                            <div class="row">
                                <div class="col-8 col-sm-10 text-right">
                                    <a href="javascript:;" onclick="$('#suForm').show();
                                            $('#suForm2').hide();
                                            $('#error-area').hide();
                                            $('#success-area').hide();"><i class="fa fa-arrow-circle-left size-200"></i></a>
                                </div>
                                <div class="col-4 col-sm-2">
                                    &nbsp;
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
                <div class="col-1 col-md-2 col-lg-4"></div>
            </div>
        </div>
        <?php include('includes/footer-js.php'); ?>
    </body>
    <?php suIframe(); ?>
</html>