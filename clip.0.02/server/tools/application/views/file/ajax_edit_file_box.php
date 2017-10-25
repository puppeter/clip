<script type="text/javascript">
<!--
$(function(){
	$('#edit_file_button').click(function(){
		var file_name = $('#file_name_box').val();
		var file_content = $('#file_content_box').val();
		$.post('<?php echo $this->getActionUrl('ajax_handle_edit_file'); ?>', {file_name:file_name, file_content:file_content}, function(data){if(data=='101'){alert('恭喜!文件创建成功');location.reload();}else{alert(data);}});
	});
});
//-->
</script>
<table border="0" cellpadding="0" cellspacing="1" bgcolor="#C3D9FF" style="margin-top:10px;">
  <tr>
    <td width="100" align="center" bgcolor="#FFFFFF">当前文件:</td>
    <td bgcolor="#FFFFFF"><span style="color:#3399FE"><?php echo $fileName; if($writabe_status == false) {echo '<span style="color:#F10000;">对不起!此目录没有写权限</span>'; }?></span></td>
  </tr> 
  <tr>
    <td width="100" align="center" bgcolor="#FFFFFF">文件内容:<input type="hidden" name="file_name" value="<?php echo $fileName; ?>" id="file_name_box" /></td>
    <td bgcolor="#FFFFFF"><textarea name="file_content" id="file_content_box"><?php echo $file_content; ?></textarea></td>
  </tr>
  <tr>
    <td width="100" bgcolor="#FFFFFF">&nbsp;</td>
    <td bgcolor="#FFFFFF"><input type="button" name="submit_button" value="保存"<?php if($writabe_status == false) { ?> disabled="disabled"<?php } ?> id="edit_file_button"/></td>
  </tr>
</table>