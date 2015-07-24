<h2>Карта сайта</h2>

<div id="jstree_sitemap" class="jstree jstree-1 jstree-default">
<ul class="jstree-container-ul jstree-children">
    <li class="jstree-node  jstree-open jstree-last">
        <i class="jstree-icon jstree-ocl"></i>
        <a class="jstree-anchor" href="/">
            <i class="jstree-icon jstree-themeicon"></i>Главная
        </a>
        <ul class="jstree-children">
        {foreach item=component from=$components}
            <li class="jstree-node jstree-{if $component.sections}closed{else}open{/if} jstree-last">
                <a class="jstree-icon jstree-ocl" href="/sitemap/{$component.link}.html"></a>
                <a class="jstree-anchor" href="/{$component.link}">
                    <i class="jstree-icon jstree-themeicon folder jstree-themeicon-custom"></i>
                    {$component.title}
                </a>
                {if $component.sections}
                    {if $component.section}
                        <ul class="jstree-children">
                            <li class="jstree-node jstree-open jstree-last">
                                <a class="jstree-icon jstree-ocl" href="/sitemap/{$component.link}_{$component.section.target}_{$component.section.target_id}.html"></a>
                                <a class="jstree-anchor" href="{$component.section.link}">
                                    <i class="jstree-icon jstree-themeicon folder jstree-themeicon-custom"></i>
                                    {$component.section.title}
                                </a>
                    {/if}
                                <ul class="jstree-children">
                                    {foreach item=section from=$component.sections}
                                        {if $section.target_id}
                                            <li class="jstree-node jstree-closed jstree-last">
                                                <a class="jstree-icon jstree-ocl" href="/sitemap/{$component.link}_{$section.target}_{$section.target_id}.html"></a>
                                                <a class="jstree-anchor" href="{$section.link}">
                                                    <i class="jstree-icon jstree-themeicon folder jstree-themeicon-custom"></i>
                                                    {$section.title}
                                                </a>
                                            </li>
                                        {else}
                                            <li class="jstree-node jstree-leaf jstree-last">
                                                <a class="jstree-icon jstree-ocl" href="#"></a>
                                                <a class="jstree-anchor" href="{$section.link}">
                                                    <i class="jstree-icon jstree-themeicon file jstree-themeicon-custom"></i>
                                                    {$section.title}
                                                </a>
                                            </li>
                                        {/if}
                                    {/foreach}
                                </ul>
                    {if $component.section}
                            </li>
                        </ul>
                    {/if}
                {/if}
            </li>
        {/foreach}
        </ul>
    </li>
</ul>
</div>

{if $pagebar}{$pagebar}{/if}

{if $do == 'view'}
<script type="text/javascript">
    $(function () {
        $('#jstree_sitemap').jstree({
            'core' : {
                'data' : {
                    'url' : '/components/sitemap/ajax/tree.php',
                    'data' : function (node) {
                        console.log(node);
                        return { 'id' : node.id };
                    }
                }
            }
        });
    });
</script>
{/if}

<style>
    #jstree_sitemap .folder {
        background: url('/templates/{template}/images/icons/folder.png');
        background-repeat: no-repeat;
        background-position: center;
    }
    #jstree_sitemap .file {
        background: url('/templates/{template}/images/icons/article.png');
        background-repeat: no-repeat;
        background-position: center;
    }
</style>