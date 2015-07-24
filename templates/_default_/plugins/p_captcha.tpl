<table align="left" cellpadding="2" cellspacing="0">
    <tr>
        <td valign="middle" width="130" style="padding-left:0">
            <img id="captcha{$captcha_id}" class="captcha" src="/plugins/p_captcha/codegen/cms_codegen.php?captcha_id={$captcha_id}" border="0" />
        </td>
        <td valign="middle">
            <div>{$LANG.CAPTCHA_CODE}:</div>
            <div>
                <input type="text" name="captcha_code" style="width:120px" class="text-input" value="" />
                <input type="hidden" name="captcha_id" value="{$captcha_id}" />
            </div>
            <div>
                <a href="javascript:reloadCaptcha('{$captcha_id}')">
                    <small>{$LANG.CAPTCHA_RELOAD}</small>
                </a>
            </div>
        </td>
    </tr>
</table>