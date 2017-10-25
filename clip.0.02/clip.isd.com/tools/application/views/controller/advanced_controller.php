<?php echo script::add('form'); ?>
<?php echo script::ajaxForm('#create_advanced_controller_form', 'request', 'response'); ?>
<script type="text/javascript">
<!--
function request() {
	var controller_name = $('#controller_name_box').val();
	if(controller_name == '') {
		$('#controller_name_box').css('border', '1px solid #D54E21');
		alert('所要创建的controller名称不能为空!');
		$('#controller_name_box').focus();
		return false;
	}
}
function response(data) {
	alert(data);
}
$(function(){
	$('#controller_name_box').blur(function(){
		var controller_name = $('#controller_name_box').val();
		if(controller_name != '') {
			$.post('<?php echo $this->getActionUrl('ajax_parse_repeat'); ?>', {controller_name:controller_name}, function(data){
				if(data == 101) {
					$('#controller_name_box').css('border', '1px solid #D54E21');
					$('#result_box').text('对不起,你输入的Controller文件已存在!').show();
					$('#controller_name_box').focus();
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
    <td height="30" align="left">当前位置: <a href="<?php echo $this->getActionUrl('index'); ?>">Controller管理</a> > <a href="<?php echo $this->getActionUrl('index'); ?>">创建Controller</a> > 高级操作</td>
    <td width="210" align="right"><a href="<?php echo $this->getActionUrl('index'); ?>">返回</a></td>
    <td width="20" align="left">&nbsp;</td>
  </tr>
</table>
<!-- /导航 -->

<!-- create controller file -->
<fieldset>
<legend>创建Controller(高级):</legend>
<div class="alert hide" id="result_box"></div>
<form action="<?php echo $this->getActionUrl('ajax_advanced_create_controller'); ?>" name="form_box" id="create_advanced_controller_form" method="post">
<table border="0" cellspacing="0" cellpadding="0">
<caption>基本信息</caption>
  <tr>
    <td width="120" align="center">Controller Name:</td>
    <td align="left"><input type="text" class="text" name="controller_name_box" id="controller_name_box" style="width:180px;" <?php echo ($passed_state === false) ? 'disabled="disabled"' : ''; ?>/>&nbsp;&nbsp;&nbsp;&nbsp;<input name="controller_view_state" type="checkbox" checked="checked" id="controller_view_dir_box"/>视图目录&nbsp;&nbsp;&nbsp;&nbsp;<input name="controller_view_file_state" type="checkbox" checked="checked" id="controller_view_file_box"/>视图文件 &nbsp;( 视图文件格式: <input name="controller_view_file_ex" type="radio" value="1" checked="checked"/> PHP <input type="radio" name="controller_view_file_ex" value="0"/>HTML )</td>
  </tr>
  <tr>
    <td width="120" align="center">Action Name:</td>
    <td align="left"><input type="text" class="text" name="action_name_box" id="action_name_box" style="width:320px;" <?php echo ($passed_state === false) ? 'disabled="disabled"' : ''; ?> value="index"/>&nbsp;&nbsp;&nbsp;&nbsp;<input name="action_note_state" type="checkbox" id="action_note_state" checked="checked"/>
    注释</td>
  </tr>
  <tr>
    <td height="20" align="center" style="height:20px;">&nbsp;</td>
    <td align="left" style="height:20px;">&nbsp;&nbsp;注: <span class="red">多个Action Name 需用分号(;)隔开</span></td>
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
    <td height="40" align="center"><input id="controller_create_button" type="submit" style="width:110px; height:24px; text-align:center; border:1px solid #333; cursor:pointer" value="创建Controller" name="controller_create_button" <?php echo ($passed_state === false) ? 'disabled="disabled"' : ''; ?>></td>
  </tr>
</table>
</form>
<div style="width:100%; height:30px;"><?php if($passed_state == true) {echo html::image($this->getAssetUrl('images') . 'check_right.gif'), ' WebApp目录已创建,可以操作!';} else {echo html::image($this->getAssetUrl('images') . 'check_error.gif'), ' WebApp目录还没有创建,不可以操作!';}?></div>
</fieldset>
<!-- /create controller file -->