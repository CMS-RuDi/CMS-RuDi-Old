<script type="text/javascript">
    function createGoogleRecaptcha() {
        grecaptcha.render('google_recaptcha<?php echo $captcha_id; ?>', {
            theme:    "<?php echo $config['rpc_theme']; ?>",
            sitekey:  "<?php echo $config['rpc_public_key']; ?>",
            type:     "<?php echo $config['rpc_type']; ?>",
            callback: reCaptchaCallBack<?php echo $captcha_id; ?>
        });
    }
    
    function setTimeoutRecaptcha() {
        setTimeout(function () {
            try {
                createGoogleRecaptcha();
            } catch(e) {
                setTimeoutRecaptcha();
            }
        }, 300);
    }
    
    function reCaptchaCallBack<?php echo $captcha_id; ?> (response) {
        console.log(response);
        $("input[name=captcha_code<?php echo $captcha_id; ?>]").val(response);
    }
    
    setTimeoutRecaptcha();
</script>

<div class="google-recaptcha">
    <div id="google_recaptcha<?php echo $captcha_id; ?>"></div>
    <input type="hidden" name="captcha_code<?php echo $captcha_id; ?>" value="" />
    <input type="hidden" name="captcha_id" value="<?php echo $captcha_id; ?>" />
</div>