<h2>Карта сайта</h2>

<div id="jstree_sitemap" class="jstree jstree-1 jstree-default">
<ul class="jstree-container-ul jstree-children">
    <li class="jstree-node  jstree-open jstree-last">
        <i class="jstree-icon jstree-ocl"></i>
        <a class="jstree-anchor" href="/">
            <i class="jstree-icon jstree-themeicon"></i>Главная
        </a>
        <ul class="jstree-children">
        <?php foreach ($components as $component) { ?>
            <li class="jstree-node jstree-<?php if (!isset($component['sections'])) { echo 'closed'; } else { echo 'open'; } ?> jstree-last">
                <a class="jstree-icon jstree-ocl" href="/sitemap/<?php echo $component['link']; ?>.html"></a>
                <a class="jstree-anchor" href="/<?php echo $component['link']; ?>">
                    <i class="jstree-icon jstree-themeicon folder jstree-themeicon-custom"></i>
                    <?php echo $component['title']; ?>
                </a>
                <?php if (isset($component['sections'])) { ?>
                    <?php if (isset($component['section'])) { ?>
                        <ul class="jstree-children">
                            <li class="jstree-node jstree-open jstree-last">
                                <a class="jstree-icon jstree-ocl" href="/sitemap/<?php echo $component['link'] .'_'. $component['section']['target'] .'_'. $component['section']['target_id']; ?>.html"></a>
                                <a class="jstree-anchor" href="<?php echo $component['section']['link']; ?>">
                                    <i class="jstree-icon jstree-themeicon folder jstree-themeicon-custom"></i>
                                    <?php echo $component['section']['title']; ?>
                                </a>
                    <?php } ?>
                                <ul class="jstree-children">
                                    <?php foreach ($component['sections'] as $section) { ?>

                                            <?php if (isset($section['target_id'])) { ?>
                                                <li class="jstree-node jstree-closed jstree-last">
                                                    <a class="jstree-icon jstree-ocl" href="/sitemap/<?php echo $component['link'] .'_'. $section['target'] .'_'. $section['target_id']; ?>.html"></a>
                                                    <a class="jstree-anchor" href="<?php echo $section['link']; ?>">
                                                        <i class="jstree-icon jstree-themeicon folder jstree-themeicon-custom"></i>
                                                        <?php echo $section['title']; ?>
                                                    </a>
                                                </li>
                                            <?php } else { ?>
                                                <li class="jstree-node jstree-leaf jstree-last">
                                                    <a class="jstree-icon jstree-ocl" href="#"></a>
                                                    <a class="jstree-anchor" href="<?php echo $section['link']; ?>">
                                                        <i class="jstree-icon jstree-themeicon file jstree-themeicon-custom"></i>
                                                        <?php echo $section['title']; ?>
                                                    </a>
                                                </li>
                                            <?php } ?>
                                    <?php } ?>
                                </ul>
                    <?php if (isset($component['section'])) { ?>
                            </li>
                        </ul>
                    <?php } ?>
                <?php } ?>
            </li>
        <?php } ?>
        </ul>
    </li>
</ul>
</div>

<?php if (!empty($pagebar)) { echo $pagebar; } ?>

<?php if ($do == 'view') { ?>
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
<?php } ?>

<style>
    #jstree_sitemap .folder {
        background: url('/templates/<?php echo cmsCore::c('config')->template ?>/images/icons/folder.png');
        background-repeat: no-repeat;
        background-position: center;
    }
    #jstree_sitemap .file {
        background: url('/templates/<?php echo cmsCore::c('config')->template ?>/images/icons/article.png');
        background-repeat: no-repeat;
        background-position: center;
    }
</style>