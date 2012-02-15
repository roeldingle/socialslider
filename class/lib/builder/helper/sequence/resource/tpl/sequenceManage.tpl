<!-- main -->
<div id="sdk_message_box"></div>
<!-- table horizontal -->
<table border="1" cellpadding="0" cellspacing="0" class="table_hor_02">
<colgroup>
    {$sHtmlColRows}
</colgroup>

<thead>
<tr>
    {$sHtmlHeadRows}
</tr>
</thead>

<tbody>
    {$sHtmlBodyRows}
</tbody>
</table>
<!-- // table horizontal -->

<div class="table_display_set">
<?php if(count($aSequenceList)) { ?>
    <a href="#none" class="btn_nor_01 btn_width_st1" title="Delete selected {$sModuleName}" id="seq_btn_delete">Delete</a>
    <a href="/admin/sub/?module=ExtensionPageMyextensions" class="add_link" title="Return to My Extensions">Return to My Extensions</a>
<?php } ?>
</div>
<div class="tbl_btom_rgt"><a id="sdk_seq_btn_add" href="#none" class="btn_nor_01 {$sAddButtonClass}" title="Add New {$sModuleName}">Add New {$sModuleName}</a></div>
<!-- // layer1 -->
<div id="layer_01_contents" style="display:none">
    <p class="require"><span class="neccesary">*</span> Required</p>
    <div class="s_title">
        <label for="module_label">Module Label<span class="neccesary">*</span></label>
        <input type="text" id="module_label" fw-filter="isFill&isMax[250]" maxlength="250" />
    </div>
    <div class="ly_cnt_btn"><a href="#none" class="btn_ly" title="Save changes" id="buttonSequenceSave">Save</a></div>
</div>
<!-- // layer1 -->

<!-- layer2 -->
<div id="layer_02_contents" style="display:none">
    <p>Selected {$sModuleName} and its contents<br />

    will be deleted.</p>

    <p>Are you sure<br />

        you want to delete?</p>
    <div class="ly_cnt_btn"><a href="#none" class="btn_ly" title="Delete" id="buttonSequenceDelete">Delete</a></div>
</div>
<!-- // layer2 -->
<!-- //main -->