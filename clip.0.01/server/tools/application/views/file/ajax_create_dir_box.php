<script type="text/javascript">
$(function(){
	$('#create_dir_button').click(function(){
		var dir_name = $('#dir_name_box').val();
		if(dir_name=='') {
			$('#dir_name_box').css('border', '1px solid #D54E21');
			alert('文件名不能为空!');
			$('#dir_name_box').focus();
			return false;
		}
		var file_dir = $('#file_name_box').val();
		$.post('<?php echo $this->getActionUrl('ajax_handle_create_dir'); ?>', {dir_name:dir_name, file_dir:file_dir}, function(data){if(data=='101'){alert('恭喜!文件创建成功');location.reload();}else{alert(data);}});
	});
});
</script>
<table border="0" cellpadding="0" cellspacing="1" bgcolor="#C3D9FF" style="margin-top:10px;">
  <tr>
    <td width="100" align="center" bgcolor="#FFFFFF">工作目录:</td>
    <td bgcolor="#FFFFFF"><span style="color:#3399FE"><?php echo $path; if($writabe_status == false) {echo '<span style="color:#F10000;">对不起!此目录没有写权限</span>'; }?></span></td>
  </tr>
  <tr>
    <td width="100" align="center" bgcolor="#FFFFFF">目录名:<input type="hidden" name="dir_path" value="<?php echo $path; ?>" id="file_name_box"/></td>
    <td bgcolor="#FFFFFF"><input type="text" name="dir_name" class="text" style="width:180px;" id="dir_name_box"/></td>
  </tr>
  <tr>
    <td width="100" bgcolor="#FFFFFF">&nbsp;</td>
    <td bgcolor="#FFFFFF"><input type="button" name="submit_button" value="保存"<?php if($writabe_status == false) { ?> disabled="disabled"<?php } ?> id="create_dir_button"/></td>
  </tr>
</table>