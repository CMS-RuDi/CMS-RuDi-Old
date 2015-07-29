<a class="usr_bb_button" href="javascript:addTag('{$field_id}', '[b]', '[/b]')" title="{$LANG.BB_CODE.B}">
    <img src="/includes/bbcode/images/b.png" />
 </a>
 <a class="usr_bb_button" href="javascript:addTag('{$field_id}', '[i]', '[/i]')" title="{$LANG.BB_CODE.I}">
    <img src="/includes/bbcode/images/i.png" />
 </a>
 <a class="usr_bb_button" href="javascript:addTag('{$field_id}', '[u]', '[/u]')"  title="{$LANG.BB_CODE.U}">
    <img src="/includes/bbcode/images/u.png" />
 </a>
 <a class="usr_bb_button" href="javascript:addTag('{$field_id}', '[s]', '[/s]')"  title="{$LANG.BB_CODE.S}">
    <img src="/includes/bbcode/images/s.png" />
 </a>
 <a class="usr_bb_button" href="javascript:addTag('{$field_id}', '[align=left]', '[/align]')" title="{$LANG.BB_CODE.AL}">
    <img src="/includes/bbcode/images/align_left.png" />
 </a>
 <a class="usr_bb_button" href="javascript:addTag('{$field_id}', '[align=center]', '[/align]')" title="{$LANG.BB_CODE.AC}">
    <img src="/includes/bbcode/images/align_center.png" />
 </a>
 <a class="usr_bb_button" href="javascript:addTag('{$field_id}', '[align=right]', '[/align]')" title="{$LANG.BB_CODE.AR}">
    <img src="/includes/bbcode/images/align_right.png" />
 </a>
 <a class="usr_bb_button" href="javascript:addTag('{$field_id}', '[h2]', '[/h2]')" title="{$LANG.BB_CODE.H2}">
    <img src="/includes/bbcode/images/h2.png" />
 </a>
 <a class="usr_bb_button" href="javascript:addTag('{$field_id}', '[h3]', '[/h3]')" title="{$LANG.BB_CODE.H3}">
    <img src="/includes/bbcode/images/h3.png" />
 </a>
 <a class="usr_bb_button" href="javascript:addTagQuote('{$field_id}')" title="{$LANG.BB_CODE.Q}">
    <img src="/includes/bbcode/images/quote.png" />
 </a>
 <a class="usr_bb_button" href="javascript:addTagUrl('{$field_id}')" title="{$LANG.BB_CODE.IL}">
    <img src="/includes/bbcode/images/url.png" />
 </a>
 <a class="usr_bb_button" href="javascript:addTagEmail('{$field_id}')" title="{$LANG.BB_CODE.IE}">
    <img src="/includes/bbcode/images/email.png" />
 </a>
 <a class="usr_bb_button" href="javascript:addTag('{$field_id}', '[code=php]', '[/code]')" title="{$LANG.BB_CODE.IC}">
    <img src="/includes/bbcode/images/code.png" />
 </a>
 <a class="usr_bb_button" href="javascript:addTag('{$field_id}', '[hide]', '[/hide]')" title="{$LANG.BB_CODE.IH}">
    <img src="/includes/bbcode/images/hide.png" />
 </a>
 <a class="usr_bb_button" href="javascript:addTag('{$field_id}', '[spoiler={$LANG.SPOILER}', '[/spoiler]')" title="{$LANG.BB_CODE.IS}">
    <img src="/includes/bbcode/images/spoiler.png" />
 </a>
 <a class="usr_bb_button" href="javascript:void(0)" onclick="$('#smilespanel-{$field_id}').slideToggle('fast')" title="{$LANG.BB_CODE.ISM}">
    <img src="/includes/bbcode/images/smiles.png" />
 </a>

{if $component == 'blogs'}
    <a class="usr_bb_button" href="javascript:addTagCut('{$field_id}')" title="{$LANG.BB_CODE.ICUT}">
        <img src="/includes/bbcode/images/cut.png" />
    </a>
{/if}

{if $images}
    <a class="usr_bb_button" href="javascript:addTagVideo('{$field_id}')" title="{$LANG.BB_CODE.IV}">
        <img src="/includes/bbcode/images/video.png" />
    </a>
    <a class="usr_bb_button" href="javascript:addTagAudio('{$field_id}')" title="{$LANG.BB_CODE.IMP3}">
        <img src="/includes/bbcode/images/audio.png" />
    </a>
    <a class="usr_bb_button" href="javascript:addTagImage('{$field_id}')" title="{$LANG.BB_CODE.II}">
        <img src="/includes/bbcode/images/image_link.png" />
    </a>

    {if $user_id}
        <a class="usr_bb_button" href="javascript:addImage('{$field_id}')" title="{$LANG.BB_CODE.U_AND_I}">
            <img src="/includes/bbcode/images/image.png" border="0" alt="{$LANG.BB_CODE.U_AND_I}" />
        </a>
        <div class="bb_add_photo" id="imginsert" style="display:none;">
            <strong>{$LANG.UPLOAD_IMG}:</strong> <span id="fileInputContainer"><input type="file" id="attach_img" name="attach_img" /></span>
            <input type="button" name="goinsert" value="{$LANG.INSERT}" onclick="loadImage('{$field_id}', '{$component}', '{$target}', '{$target_id}')" />
        </div>
        <span class="ajax-loader" style="display:none">&nbsp;</span>
    {/if}
{/if}

{display_js_lang langs='ERROR,BB_URL,BB_URL_TITLE,BB_IMG_URL,BB_MP3_LINK,BB_VIDEO_CODE,BB_IMG_ADDED,BB_Q_TEXT,BB_Q_AUTHOR,BB_CUT_TITLE,BB_CUT_DEMO'}