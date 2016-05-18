<?php echo script::add('form'); ?>
<?php echo script::ajaxForm('#create_advanced_model_form', 'request', 'response'); ?>
<script type="text/javascript">
<!--
function request() {
	var model_name = $('#model_name_box').val();
	if(model_name == '') {
		$('#model_name_box').css('border', '1px solid #D54E21');
		alert('所要创建的model名称不能为空!');
		$('#model_name_box').focus();
		return false;
	}
}
function response(data) {
	alert(data);
}
$(function(){
	$('#model_name_box').blur(function(){
		var model_name = $('#model_name_box').val();
		if(model_name != '') {
			$.post('<?php echo $this->getActionUrl('ajax_parse_repeat'); ?>', {model_name:model_name}, function(data){
				if(data == 101) {
					$('#model_name_box').css('border', '1px solid #D54E21');
					$('#result_box').text('对不起,你输入的Model文件已存在!').show();
					$('#model_name_box').focus();
				}
			});
		}
	});
});
//-->
</script>
<?php $passed_state = is_dir(WEBAPP_ROOT . 'application') ? true : false; ?>
<!-- 导航 -->
<table border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="30" align="left">当前位置: <a href="<?php echo $this->getActionUrl('index'); ?>">Model管理</a> > <a href="<?php echo $this->getActionUrl('index'); ?>">创建Model</a> > 高级操作</td>
    <td width="210" align="right"><a href="<?php echo $this->getActionUrl('index'); ?>">返回</a></td>
    <td width="20" align="left">&nbsp;</td>
  </tr>
</table>
<!-- /导航 -->

<!-- create model file -->
<fieldset>
<legend>创建Model(高级):</legend>
<div class="alert hide" id="result_box"></div>
<form action="<?php echo $this->getActionUrl('ajax_advanced_create_model'); ?>" name="form_box" id="create_advanced_model_form" method="post">
<table border="0" cellspacing="0" cellpadding="0">
<caption>基本信息</caption>
  <tr>
    <td width="120" align="center">Model Name:</td>
    <td align="left"><input type="text" class="text" name="model_name_box" id="model_name_box" style="width:180px;" <?php echo ($passed_state === false) ? 'disabled="disabled"' : ''; ?>/>&nbsp;&nbsp;&nbsp;&nbsp;<input id="model_field_single_box" type="checkbox" name="model_field_single_box">字段信息(仅限Mysql)&nbsp;&nbsp;&nbsp;&nbsp;<input id="model_tabname_single_box" type="checkbox" name="model_tabname_single_box">数据表名(仅限Mysql)</td>
  </tr>
  <tr>
    <td align="center" valign="top">Method Name: </td>
    <td align="left" valign="top"><textarea name="method_name_box" id="method_name_box" style="width:470px; height:100px;" <?php echo ($passed_state === false) ? 'disabled="disabled"' : ''; ?>></textarea>&nbsp;&nbsp;&nbsp;&nbsp;<input name="method_note_state" type="checkbox" id="method_note_state" checked="checked"/>
    注释</td>
  </tr>
  <tr>
    <td height="20" align="center" style="height:20px;">&nbsp;</td>
    <td align="left" style="height:20px;">&nbsp;&nbsp;注: <span class="red">多个Method Name 需用分号(;)隔开</span></td>
  </tr>
</table>
<table border="0" cellspacing="0" cellpadding="0">
<caption>附加信息 ( 注释 )</caption>
  <tr>
    <td width="100" align="right">功能:</td>
    <td width="20">&nbsp;</td>
    <td><input type="text" name="note_description_box" id="note_description_box" class="text" <?php echo ($passed_state === false) ? 'disabled="disabled"' : ''; ?>/></td>
  </tr>
  <tr>
    <td width="100" align="right">作者:</td>
    <td width="20">&nbsp;</td>
    <td><input type="text" name="note_author_box" id="note_author_box" class="text" style="width:150px;" <?php echo ($passed_state === false) ? 'disabled="disabled"' : ''; ?>/></td>
  </tr>
  <tr>
    <td width="100" align="right">版权信息:</td>
    <td width="20">&nbsp;</td>
    <td><input type="text" name="note_copyright_box" id="note_copyright_box" class="text" <?php echo ($passed_state === false) ? 'disabled="disabled"' : ''; ?>/></td>
  </tr>
</table>
<table border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="40" align="center"><input id="model_create_button" type="submit" style="width:110px; height:24px; text-align:center; border:1px solid #333; cursor:pointer" value="创建Model" name="model_create_button" <?php echo ($passed_state === false) ? 'disabled="disabled"' : ''; ?>></td>
  </tr>
</table>
</form>
<div style="width:100%; height:30px;"><?php if($passed_state == true) {echo html::image($this->getAssetUrl('images') . 'check_right.gif'), ' WebApp目录已创建,可以操作!';} else {echo html::image($this->getAssetUrl('images') . 'check_error.gif'), ' WebApp目录还没有创建,不可以操作!';}?></div>
</fieldset>
<!-- /create model file -->