<div class="modal fade" id="tplCfgModal" tabindex="-1" role="dialog" aria-labelledby="tplCfgModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="tplCfgModalLabel">{$LANG.AD_TEMPLATE_CONFIG}</h4>
            </div>
            <div class="modal-body" style="padding-left:50px;padding-right:50px;">
                {foreach from=$data item=dat}
                    {include file='special/rudiFormGen_field.tpl' field=$dat}
                {/foreach}
            </div>
            <div class="modal-footer">
                <input type="submit" class="btn btn-primary" value="{$LANG.SAVE}" />
            </div>
        </div>
    </div>
</div>