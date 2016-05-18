<script type="text/javascript">
<!--
$(function(){
	$('#regexp_submit_button').click(function(){
		var regexp_mode = $('#regex_mode_content').val();
		var is_s_state = $('#regexp_s_box').attr('checked');
		var is_i_state = $('#regexp_i_box').attr('checked');
		var regexp_content = $('#regexp_content_box').val();
		if (regexp_mode == '') {alert('匹配模式不能为空!');$('#regex_mode_content').css('border', '1px solid #D54E21');$('#regex_mode_content').focus();return false;}
		if (regexp_content == '') {alert('匹配内容不能为空!');$('#regexp_content_box').css('border', '1px solid #D54E21');$('#regexp_content_box').focus();return false;}
		$.post('<?php echo $this->getActionUrl('ajax_handle_regexp'); ?>', {regexp_mode:regexp_mode,is_s_state:is_s_state,is_i_state:is_i_state,regexp_content:regexp_content}, function(data){$('#regexp_result_box').html(data);});
	});
});
//-->
</script>
<!-- 匹配内容 -->
<fieldset>
<legend>匹配内容:</legend>
<textarea id="regexp_content_box" name="regexp_content" style="width:840px; height:120px;"></textarea>
</fieldset>
<!-- /匹配内容 -->

<!-- 匹配模式 -->
<fieldset>
<legend>匹配模式:</legend>
<input id="regex_mode_content" class="text" type="text" name="mode_content">&nbsp;&nbsp;<input id="regexp_s_box" type="checkbox" checked="checked" name="regexp_s">全局&nbsp;&nbsp;<input id="regexp_i_box" type="checkbox" checked="checked" name="regexp_i">忽略字母大小写&nbsp;&nbsp;&nbsp;&nbsp;<input id="regexp_submit_button" type="button" style="width:45px; height:22px; text-align:center; border:1px solid #333; cursor:pointer" value="匹配" name="regexp_submit">
</fieldset>
<!-- /匹配模式 -->

<!-- 匹配结果 -->
<fieldset>
<legend>匹配结果:</legend>
<div id="regexp_result_box" style="padding:5px; width:840px; height:150px; overflow:auto; font-size:12px; line-height:20px;"></div>
</fieldset>
<!-- /匹配结果 -->