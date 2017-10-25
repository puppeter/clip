<script type="text/javascript">if(typeof tb_pathToImage!='string'){var tb_pathToImage="<?php echo $baseUrl; ?>thickbox/loading.gif"}</script>
<script type="text/javascript" src="<?php echo $baseUrl; ?>thickbox/thickbox.min.js?version=3.1"></script>
<link href="<?php echo $baseUrl; ?>thickbox/thickbox.css" rel="stylesheet" type="text/css" />
<?php echo script::add('form'), script::ajaxForm('#upload_file_form_box', 'upload_request', 'upload_response'); ?>
<script type="text/javascript">
function delete_file(file, isdir){
	if(!confirm('你确认要进行删除操作!')){
		return false;
	}
	$.post('<?php echo $this->getActionUrl('ajax_delete_file'); ?>', {dir_name:'<?php echo $path; ?>', file_name:file, isdir:isdir}, function(data){
		if(data=='101'){
			alert('操作成功!');
			location.reload();
		}else{
			alert(data);
		}
	});
}
function clear_cache(){
	if(!confirm('你确认要进行删除操作!')){
		return false;
	}
	$.post('<?php echo $this->getActionUrl('ajax_clear_cache'); ?>', {dir_name:'<?php echo $path; ?>', path:'<?php echo $dir; ?>'}, function(data){
		if(data=='101'){
			alert('操作成功!');
			location.reload();
		}else{
			alert(data);
		}
	});
}
function show_upload(){
	$('#upload_form_box').show(200);
}
function remove_upload(){
	$('#upload_form_box').hide(200);
}
function upload_request(){
	var file_box = $('#upload_files_box').val();
	if(file_box==''){
		alert('上传文件不能为空');
		$('#upload_files_box').focus();
		return false;
	}
}
function upload_response(data){
	if(data=='101'){
		alert('恭喜!文件上传成功');
		$('#upload_file_form_box').resetForm();
		location.reload();
	} else {
		alert(data);
	}
}
</script>
<!-- webapp note-->
<div style="margin-left:20px; height:30px;"><span class="blue"><b>WebApp Path</b></span>:&nbsp;&nbsp;<?php if(is_dir(WEBAPP_ROOT)) {echo '<span class="green">' . WEBAPP_ROOT . '</span>'; echo is_writable(WEBAPP_ROOT) ? ' ( Writable )' : ' ( <span class="red">unwriteable</span> )';} else { echo '<span class="red"><b>' . WEBAPP_ROOT . '</b></span> 注：<span class="red">当前目录不存在!</span>'; } ?> </div>
<!-- /webapp note-->

<!-- file list -->
<fieldset>
<legend>文件列表:</legend>
<table border="0" cellspacing="0" cellpadding="0">
<caption>
<?php if($dir == true) {?>
<img src="<?php echo $this->getAssetUrl('images'); ?>file_topdir.gif"  />
<a href="<?php echo $return_url; ?>">返回上级目录</a>&nbsp;&nbsp;&nbsp;&nbsp;
<?php } ?>
<img src="<?php echo $this->getAssetUrl('images'); ?>tree_folderopen.gif"  /> 当前目录: <?php echo $path; ?>
</caption>
  <tr>
    <td height="50" colspan="5" align="left">&nbsp;&nbsp;[<a href="<?php echo $this->getSelfUrl(); ?>" target="_self">根目录</a>]<?php if($is_system == false) { if($file_status == true) {?>   [<a href="<?php echo $this->getActionUrl('ajax_create_file_box'); ?>/?path=<?php echo $dir; ?>&amp;width=580&amp;height=460&amp;time=<?php echo time(); ?>" class="thickbox" title="新建文件">新建文件</a>]<?php } if($dir_status == true){?>   [<a href="<?php echo $this->getActionUrl('ajax_create_dir_box'); ?>/?path=<?php echo $dir; ?>&width=580&height=180&time=<?php echo time(); ?>" class="thickbox" title="新建目录">新建目录</a>]<?php } ?> [<a href="javascript:void(0);" onclick="show_upload();">上传文件</a>] <?php if($cache_status == true) {?>  [<a href="javascript:void(0);" onclick="clear_cache();">清空缓存</a>]<?php } } ?></td>
  </tr>
  <tr id="upload_form_box" class="even" style="display:none;">
  <td height="40" colspan="5" align="left"><form action="<?php echo $this->getActionUrl('ajax_upload_file'); ?>" method="post" enctype="multipart/form-data" name="upload_file_form_box" id="upload_file_form_box">文件上传:&nbsp;&nbsp;
    <input type="hidden" name="upload_dir_name" id="upload_dir" value="<?php echo $path; ?>" /><input type="file" name="upload_file" id="upload_files_box"/>&nbsp;<input type="submit" value="上传" name="upload_button"/>&nbsp;&nbsp;<a href="javascript:void(0);" onclick="remove_upload();">取消上传</a></form></td>
  </tr>
  <tr>
    <th width="320" align="center">文件名称</th>
    <th width="120" align="center">大小</th>
    <th width="150" align="center">修改时间</th>
    <th width="60" align="center">权限</th>
    <th align="center">操作</th>
  </tr>
 <?php if($file_data == true) {
 foreach($file_data as $key=>$lines) {
 if($lines['isdir'] == 1) {
 ?>
  <tr <?php echo ($key%2 ==1) ? 'class="even"' : ''; ?>>
    <td width="320" align="left"><img src="<?php echo $this->getAssetUrl('images'); ?>tree_folder.gif"  /> <a href="<?php echo $this->getSelfUrl(); ?>/?path=<?php echo $dir, '/', $lines['name']; ?>" target="_self"><?php echo $lines['name']; ?></a></td>
    <td width="120" align="center">&nbsp;</td>
    <td width="150" align="center"><?php echo $lines['time']; ?></td>
    <td width="60" align="center"><?php echo $lines['mod']; ?></td>
    <td align="center">
	<?php if($is_system == false) {
	if(!in_array($lines['name'], $protect_array) && $rename_status == true){
	?>
	<a href="<?php echo $this->getActionUrl('ajax_rename_box'); ?>/?path=<?php echo $dir; ?>&amp;isdir=1&amp;file_name=<?php echo $lines['name']; ?>&amp;width=580&amp;height=180&amp;time=<?php echo time(); ?>" class="thickbox" title="更改目录名">改名</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" onclick="delete_file('<?php echo $lines['name']; ?>', 1);">删除</a>
	<?php } } ?>	</td>
  </tr>
  <?php } else {?>
  <tr <?php echo ($key%2 ==1) ? 'class="even"' : ''; ?>>
    <td width="320" align="left"><?php if($lines['ico'] == true) { ?><img src="<?php echo $this->getAssetUrl('images'), $lines['ico']; ?>"  /> <?php } echo $lines['name']; ?></td>
    <td width="120" align="center"><?php echo $lines['size']; ?></td>
    <td width="150" align="center"><?php echo $lines['time']; ?></td>
    <td width="60" align="center"><?php echo $lines['mod']; ?></td>
    <td align="center">
	<?php if($is_system == false) { if(!in_array($lines['ext'], array('gif', 'png', 'jpg', 'jpeg'))){?><a href="<?php echo $this->getActionUrl('ajax_edit_file_box'); ?>/?file=<?php echo $path, '/', $lines['name']; ?>&width=580&height=410&time=<?php echo time(); ?>" class="thickbox" title="文件编辑">编辑</a>&nbsp;&nbsp;<?Php } ?> <a href="<?php echo $this->getActionUrl('ajax_rename_box'); ?>/?path=<?php echo $dir; ?>&amp;isdir=0&amp;file_name=<?php echo $lines['name']; ?>&amp;width=580&amp;height=180&amp;time=<?php echo time(); ?>" class="thickbox" title="更改文件名">改名</a>&nbsp;&nbsp;<a href="javascript:void(0);" onclick="delete_file('<?php echo $lines['name']; ?>', 0);">删除</a><?php } ?></td>
  </tr>
<?php }}} else { ?>
<tr>
    <td colspan="5" align="center" style="font-size:14px;">亲, 暂时没有找到所要显示的文件哦!</td>
  </tr>
<?php } ?>
</table>
</fieldset>
<!-- /file list -->