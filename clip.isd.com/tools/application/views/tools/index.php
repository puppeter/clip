<script type="text/javascript">
$(document).ready(function(){
	//求字符串长度
    $('#strlen_button').click(function(){
    	var str_content=$('#string_content_box').val();
    	if(str_content==''){
        	$('#string_content_box').css('border', '1px solid #D54E21');
        	alert('字符串内容不能为空!');
        	$('#string_content_box').focus();
        	return false;
    	}
    	$.post('<?php echo $this->getActionUrl('ajax_count_strlen'); ?>', {str_content:str_content}, function(data){
        	$('#strlen_result').text(data);
        });
    });
    //字符串转化ASCII码
    $('#ascii_encode_submit_button').click(function(){
		var ascii_content = $('#ascii_content_box').val();
		if(ascii_content==''){
			$('#ascii_content_box').css('border', '1px solid #D54E21');
        	alert('所要转化的字符串不能为空!');
        	$('#ascii_content_box').focus();
        	return false;
		}
		$.post('<?php echo $this->getActionUrl('ajax_string_2_ascii'); ?>', {ascii_content:ascii_content}, function(data){
        	$('#ascii_result_box').text(data);
        });
    });
});
</script>
<!-- strlen -->
<fieldset>
<legend>计算字符串长度:</legend>
<input id="string_content_box" type="text" class="text" name="string_content"><label><input id="strlen_button" type="button" style="font-size:14px; line-height:24px; height:24px; width:60px; border:1px solid #666; margin-left:20px; cursor:pointer" value="计算" name="count_str_button"><span id="strlen_result" style="margin-left:10px; color:#F00;"></span></span>
</fieldset>
<!-- /strlen -->

<!-- ascII -->
<fieldset>
<legend>字符串转ASCII码:</legend>
<textarea id="ascii_content_box" style="width:840px; height:120px;" name="regexp_content"></textarea>
<input id="ascii_encode_submit_button" type="button" name="regexp_submit" value="字符串转化ASCII" style="width:120px; height:24px; text-align:center; border:1px solid #333; cursor:pointer">
<div id="ascii_result_box" style="margin-top:10px; padding:5px; width:840px; height:150px; overflow:auto; font-size:12px; line-height:20px; border: 1px solid #BBBBBB;"></div>
</fieldset>
<!-- /ascII -->

<!-- javascript lib link-->
<fieldset>
<legend>常用JavaScript库网址:</legend>
<div class="font14"><a href="http://jquery.com/" target="_blank">Jquery</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="http://www.sencha.com/" target="_blank">Ext JS</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="http://www.mootools.net/" target="_blank">Mootools</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="http://www.prototypejs.org/" target="_blank">Prototype</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="http://developer.yahoo.com/yui/" target="_blank">YUI</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="http://flowplayer.org/tools/demos/index.html" target="_blank">Jquery Toos</a></div>
</fieldset>
<!-- /javascript lib link-->