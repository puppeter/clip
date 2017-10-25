<script type="text/javascript">
$(function(){
	$('#rename_file_button').click(function(){
		var file_name = $('#file_name_box').val();
		if(file_name=='') {
			$('#file_name_box').css('border', '1px solid #D54E21');
			alert('文件名不能为空!');
			$('#file_name_box').focus();
			return false;
		}
		var old_file_name = $('#old_file_name_box').val();
		var dir_name = $('#dir_name_box').val();		
		var isdir = $('#isdir_box').val();
		$.post('<?php echo $this->getActionUrl('ajax_handle_rename_file'); ?>', {dir_name:dir_name, file_name:file_name, isdir:isdir, old_file_name:old_file_name}, function(data){
			if(data=='101'){
				alert('恭喜!文件创建成功');
				location.reload();
			}else{
				alert(data);
			}
		});
	});
});
</script>
<table border="0" cellpadding="0" cellspacing="1" bgcolor="#C3D9FF" style="margin-top:10px;">
  <tr>
    <td width="100" align="center" bgcolor="#FFFFFF">工作目录:</td>
    <td bgcolor="#FFFFFF"><span style="color:#3399FE"><?php echo $path; if($writabe_status == false) {echo '<span style="color:#F10000;">对不起!此目录没有写权限</span>'; }?></span></td>
  </tr>
  <tr>
    <td width="100" align="center" bgcolor="#FFFFFF"><?php if($isdir==1) {echo '目录名';} else {echo '文件名';}?>:<input type="hidden" name="dir_path" value="<?php echo $path; ?>" id="dir_name_box"/><input type="hidden" name="id_dir" id="isdir_box" value="<?php echo $isdir; ?>" /><input type="hidden" name="old_file_name" id="old_file_name_box" value="<?php echo $fileName; ?>" /></td>
    <td bgcolor="#FFFFFF"><input type="text" name="dir_name" class="text" style="width:180px;" id="file_name_box" value="<?php echo $fileName; ?>"/></td>
  </tr>
  <tr>
    <td width="100" bgcolor="#FFFFFF">&nbsp;</td>
    <td bgcolor="#FFFFFF"><input type="button" name="submit_button" value="保存"<?php if($writabe_status == false) { ?> disabled="disabled"<?php } ?> id="rename_file_button"/></td>
  </tr>
</table>