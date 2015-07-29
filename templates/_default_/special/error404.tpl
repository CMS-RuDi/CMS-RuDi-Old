<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>{$LANG.404}</title>
        <style type="text/css">
            * { font-family: Arial; }
            html, body { height:100%; margin:0px; }
            h2, p { margin:0px; }
            .ajaxlink{ text-decoration:none; border-bottom:dashed 1px #AAA; color:#AAA; }
            ul { list-style: none; margin: 10px; padding: 0; }
        </style>
    </head>
    <body>
        <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
            <tr>
                <td align="center">
                    <table border="0" cellpadding="0" cellspacing="0" >
                        <tr>
                            <td width="140">
                                <img src="/templates/{$template}/special/images/error404.png" />
                            </td>
                            <td>
                                <h2>{$LANG.404}</h2>
                                <p>{$LANG.404_INFO}.</p>
                                {if $debug}
                                    <p><a href="#trace_stack" class="ajaxlink trace_stack">{$LANG.TRACE_STACK}</a></p>
                                {/if}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        
        {if $debug}
            <div style="display: none">
                <ul id="trace_stack">
                    {foreach from=$backtrace item=row}
                        <li>
                            <b>{$row.function}()</b>
                            {if $row.file}
                                <span>@ {$row.file}</span> => <span>{$row.line}</span>
                            {/if}
                        </li>
                    {/foreach}
                </ul>
            </div>
        
            <script type="text/javascript" src="/includes/jquery/jquery.js"></script>
            <script type="text/javascript" src="/includes/jquery/colorbox/jquery.colorbox.js"></script>
            <link href="/includes/jquery/colorbox/colorbox.css" rel="stylesheet" type="text/css" />
            <script>
                $(function() {
                    $('.trace_stack').colorbox({inline:true, width:"50%", maxHeight: "100%", transition:"none"});
                });
            </script> 
 	{/if}
    </body>
</html>
