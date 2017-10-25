<script type="text/javascript">
<!--
$(function(){
	$('#create_file_button').click(function(){
		var file_name = $('#file_name_box').val();
		if(file_name=='') {
			$('#file_name_box').css('border', '1px solid #D54E21');
			<?php if($extension_status == true) { ?>alert('Class Name 不能为空!');<?php } else {?>alert('文件名不能为空!');<?php } ?>
			$('#file_name_box').focus();
			return false;
		}
		var file_content = $('#file_content_box').val();
		<?php if($extension_status == true) { ?>
		if(file_content == ''){
			$('#file_content_box').css('border', '1px solid #D54E21');
			alert('Method Name 不能为空!');
			$('#file_content_box').focus();
			return false;
		}
		<?php } ?>
		var file_dir = $('#file_dir_name_box').val();
		$.post('<?php echo $this->getActionUrl('ajax_handle_create_file'); ?>', {file_name:file_name, file_content:file_content, file_dir:file_dir}, function(data){if(data=='101'){alert('恭喜!文件创建成功');location.reload();}else{alert(data);}});
	});
});
//-->
</script>
<table border="0" cellpadding="0" cellspacing="1" bgcolor="#C3D9FF" style="margin-top:10px;">
  <tr>
    <td width="100" align="center" bgcolor="#FFFFFF">工作目录:</td>
    <td bgcolor="#FFFFFF"><span style="color:#3399FE"><?php echo $path; if($writabe_status == false) {echo '<span style="color:#F10000;">对不起!此目录没有写权限</span>'; }?></span></td>
  </tr>
<?php if($extension_status == true) { ?>
  <tr>
    <td width="100" align="center" bgcolor="#FFFFFF">Class Name:<input type="hidden" name="dir_name" id="file_dir_name_box" value="<?php echo $path; ?>"/></td>
    <td bgcolor="#FFFFFF"><input type="text" name="file_name" class="text" style="width:150px;" id="file_name_box"/>.class.php</td>
  </tr>
  <tr>
    <td width="100" align="center" bgcolor="#FFFFFF">Method Name:</td>
    <td bgcolor="#FFFFFF"><textarea name="file_content" id="file_content_box"></textarea></td>
  </tr>
<?php } else { ?>  
  <tr>
    <td width="100" align="center" bgcolor="#FFFFFF">文件名:<input type="hidden" name="dir_name" id="file_dir_name_box" value="<?php echo $path; ?>"/></td>
    <td bgcolor="#FFFFFF"><input type="text" name="file_name" class="text" style="width:180px;" id="file_name_box"/></td>
  </tr>
  <tr>
    <td width="100" align="center" bgcolor="#FFFFFF">文件内容:</td>
    <td bgcolor="#FFFFFF"><textarea name="file_content" id="file_content_box"></textarea></td>
  </tr>
<?php } ?>  
  <tr>
    <td width="100" bgcolor="#FFFFFF">&nbsp;</td>
    <td bgcolor="#FFFFFF"><input type="button" name="submit_button" value="提交"<?php if($writabe_status == false) { ?> disabled="disabled"<?php } ?> id="create_file_button"/></td>
  </tr>
</table>