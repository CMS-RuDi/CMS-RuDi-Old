<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>{$LANG.BAN_TITLE}</title>
        <meta http-equiv="refresh" content="60;URL=/">
        <style type="text/css">
            * { font-family: Arial; }
            html, body { height:100%; margin:0px; background: #2F4F7D; }
            h2 { color: red; margin:0px; }
            p { margin:0px; margin-top:10px; font-size:14px; color: #FFF; }
        </style>
    </head>
    <body>
        <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
            <tr>
                <td align="center">
                    <table border="0" cellpadding="0" cellspacing="0" >
                        <tr>
                            <td width="160">
                                <img src="/templates/{$template}/special/images/banned.png" />
                            </td>
                            <td>
                                <h2>{$LANG.BAN_TITLE}</h2>
                                <div style="padding:15px 0;">
                                    <p><strong>{$LANG.BAN_LOCK_DATE}:</strong> {$ban.bandate}</p>
                                    {if $ban.int_num <= 0}
                                        <p><strong>{$LANG.BAN_PERIOD_LOCK}:</strong> {$LANG.BAN_INFINITE}</p>
                                    {else}
                                        <p><strong>{$LANG.BAN_PERIOD_LOCK}:</strong> {$ban.enddate}</p>
                                    {/if}
                                    {if $ban.cause}
                                        <p><strong>{$LANG.BAN_REASON_LOCK}:</strong></p><p>{$ban.cause|nl2br}</p>
                                    {/if}
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>