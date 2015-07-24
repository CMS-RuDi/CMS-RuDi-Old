{*Получаем количество модулей на нужные позиции*}
{countModules pos='top,topmenu,sidebar'}

{*Подключаем стили шаблона*}
{add_css file='templates/_default_/css/reset.min.css'}
{add_css file='templates/_default_/css/text.min.css'}
{add_css file='templates/_default_/css/960.min.css'}
{add_css file='templates/_default_/css/styles.min.css'}

{*Подключаем colorbox (просмотр фото)*}
{add_js file='includes/jquery/colorbox/jquery.colorbox.js'}
{add_css file='includes/jquery/colorbox/colorbox.css'}
{add_js file='includes/jquery/colorbox/init_colorbox.js'}

{*LANG фразы для colorbox*}
{add_js_lang langs='CBOX_IMAGE,CBOX_FROM,CBOX_PREVIOUS,CBOX_NEXT,CBOX_CLOSE,CBOX_XHR_ERROR,CBOX_IMG_ERROR,CBOX_SLIDESHOWSTOP,CBOX_SLIDESHOWSTART'}

{if $is_admin}
    {add_js file='admin/js/modconfig.js'}
    {add_css file='templates/_default_/css/modconfig.min.css'}
{/if}

{*подключаем jQuery и js ядра в самое начало*}
{add_js file='core/js/common.js' prepend='1'}
{add_js file='includes/jquery/jquery.js' prepend='1'}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru" xmlns:og="http://ogp.me/ns#" prefix="og: http://ogp.me/ns# video: http://ogp.me/ns/video# music: http://ogp.me/ns/music# ya: http://webmaster.yandex.ru/vocabularies/">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {printHead}
</head>

<body>
    {if $inConf.siteoff && $is_admin}
        <div style="margin:4px; padding:5px; border:solid 1px red; background:#FFF; position: fixed;opacity: 0.8; z-index:999">{$LANG.SITE_IS_DISABLE}</div>
    {/if}
    <div id="wrapper">
        <div id="header">
            <div class="container_12">
                <div class="grid_2">
                    <div id="sitename"><a href="/"></a></div>
                </div>
                <div class="grid_10">
                    {if $inConf.is_change_lang}
                        <div onclick="$('#langs-select').toggle().toggleClass('active_lang'); $(this).toggleClass('active_lang'); return false;" title="{$LANG.TEMPLATE_INTERFACE_LANG}" id="langs" style="background-image:  url(/templates/{$template}/images/icons/langs/{$inConf.lang}.png);">
                            <span>&#9660;</span>
                            <ul id="langs-select">
                                {foreach from=$langs item=lng}
                                <li onclick="setLang('{$lng}'); return false;" style="background-image: url(/templates/{$template}/images/icons/langs/{$lng}.png);">{$lng}</li>
                                {/foreach}
                            </ul>
                        </div>
                    {/if}
                    {printModules pos='header'}
                </div>
            </div>
        </div>

        <div id="page">
            {if $mod_count.topmenu}
            <div class="container_12" id="topmenu">
                <div class="grid_12">
                    {printModules pos='topmenu'}
                </div>
            </div>
            {/if}

            {if $mod_count.top}
            <div class="clear"></div>

            <div id="topwide" class="container_12">
                <div class="grid_12" id="topmod">{printModules pos='top'}</div>
            </div>
            {/if}

            <div id="pathway" class="container_12">
                <div class="grid_12">{printPathway sep='&rarr;'}</div>
            </div>

            <div class="clear"></div>

            <div id="mainbody" class="container_12">
                <div id="main" class="{if $mod_count.sidebar}grid_8{else}grid_12{/if}">
                    {printModules pos='maintop'}

                    {session_messages}
                    
                    {printBody}
                    
                    {printModules pos='mainbottom'}
                </div>
                {if $mod_count.sidebar}
                    <div class="grid_4" id="sidebar">{printModules pos='sidebar'}</div>
                {/if}
            </div>
        </div>
    </div>

    <div id="footer">
        <div class="container_12">
            <div class="grid_8">
                <div id="copyright">{$inConf.sitename} &copy; {$year}</div>
            </div>
            <div class="grid_4 foot_right">
                <a href="http://cmsrudi.ru/" title="{$LANG.POWERED_BY_INSTANTCMS}" target="_blank">
                    {$LANG.POWERED_BY_INSTANTCMS}
                </a>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(function() {
            $('#sess_messages').hide().fadeIn();
            $('#topmenu .menu li, #usermenu li').hover(
                function() {
                    $(this).find('ul:first').fadeIn('fast');
                    $(this).find('a:first').addClass('hover');
                },
                function() {
                    $(this).find('ul:first').hide();
                    $(this).find('a:first').removeClass('hover');
                }
            );
        });
    </script>

    {debug_info}
</body>
</html>