<script type="text/javascript">
    function createGoogleRecaptcha() {
        grecaptcha.render('google_recaptcha{$captcha_id}', {
            theme:    "{$config.rpc_theme}",
            sitekey:  "{$config.rpc_public_key}",
            type:     "{$config.rpc_type}",
            callback: reCaptchaCallBack{$captcha_id}
        } );
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
    
    function reCaptchaCallBack{$captcha_id} (response) {
        console.log(response);
        $("input[name=captcha_code{$captcha_id}]").val(response);
    }
    
    setTimeoutRecaptcha();
</script>

<div class="google-recaptcha">
    <div id="google_recaptcha{$captcha_id}"></div>
    <input type="hidden" name="captcha_code{$captcha_id}" value="" />
    <input type="hidden" name="captcha_id" value="{$captcha_id}" />
</div>