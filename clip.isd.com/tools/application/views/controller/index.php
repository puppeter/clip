<script type="text/javascript">if(typeof tb_pathToImage!='string'){var tb_pathToImage="<?php echo $baseUrl; ?>thickbox/loading.gif"}</script>
<script type="text/javascript" src="<?php echo $baseUrl; ?>thickbox/thickbox.min.js?version=3.1"></script>
<link href="<?php echo $baseUrl; ?>thickbox/thickbox.css" rel="stylesheet" type="text/css" />
<?php
$passed_state = is_dir(WEBAPP_ROOT . 'application') ? true : false;
?>
<script type="text/javascript">
<!--
$(function(){
	$('#controller_create_button').click(function(){
		var controller_name=$('#controller_name_box').val();
		var controller_view_dir_state=$('#controller_view_dir_box').attr('checked');
		var controller_view_file_state=$('#controller_view_file_box').attr('checked');
		var controller_view_file_type=$('input[name=controller_view_file_type]:checked').val();
		if(controller_name==''){
			$('#controller_name_box').css('border', '1px solid #D54E21');
			alert('所要创建的controller名称不能为空!');
			$('#controller_name_box').focus();
			return false;
		}
		$.post('<?php echo $this->getActionUrl('ajax_create_controller'); ?>', {controller_name:controller_name, controller_view_dir_state:controller_view_dir_state, controller_view_file_state:controller_view_file_state, controller_view_file_type:controller_view_file_type}, function(data){
			alert(data);
		});
	});

	$('#widget_create_button').click(function(){
		var widget_name=$('#widget_name_box').val();
		var widget_view_file_state=$('#widget_view_file_box').attr('checked');
		if(widget_name==''){
			$('#widget_name_box').css('border', '1px solid #D54E21');
			alert('所要创建的widget名称不能为空!');
			$('#widget_name_box').focus();
			return false;
		}
		$.post('<?php echo $this->getActionUrl('ajax_create_widget'); ?>', {widget_name:widget_name, widget_view_file_state:widget_view_file_state}, function(data){
			alert(data);
		});
	});

	$('#module_create_button').click(function(){
		var module_name=$('#module_name_box').val();
		var module_view_dir_state=$('#module_view_dir_box').attr('checked');
		var module_view_file_state=$('#module_view_file_box').attr('checked');
		if(module_name==''){
			$('#module_name_box').css('border', '1px solid #D54E21');
			alert('所要创建的module名称不能为空!');
			$('#module_name_box').focus();
			return false;
		}
		$.post('<?php  echo $this->getActionUrl('ajax_create_module'); ?>', {module_name:module_name, module_view_dir_state:module_view_dir_state, module_view_file_state:module_view_file_state}, function(data){
			alert(data);
		})
	});
});
//-->
</script>
<!-- create controller -->
<fieldset>
<legend>创建Controller:</legend>
<label>Controller Name:</label>&nbsp;&nbsp;<input type="text" class="text" name="controller_name_box" style="width:150px;" id="controller_name_box" <?php echo ($passed_state === false) ? 'disabled="disabled"' : ''; ?>/>&nbsp;&nbsp;&nbsp;&nbsp;<input name="controller_view_state" type="checkbox" checked="checked" id="controller_view_dir_box"/>视图目录&nbsp;&nbsp;&nbsp;&nbsp;<input name="controller_view_file_state" type="checkbox" checked="checked" id="controller_view_file_box"/>视图文件( 格式:<input name="controller_view_file_type" type="radio" value="1" checked="checked"/>PHP <input type="radio" name="controller_view_file_type" value="0"/>HTML )&nbsp;&nbsp;<input id="controller_create_button" type="button" style="width:110px; height:24px; text-align:center; border:1px solid #333; cursor:pointer" value="创建Controller" name="controller_create_button" <?php echo ($passed_state === false) ? 'disabled="disabled"' : ''; ?>>&nbsp;&nbsp;&nbsp;&nbsp;<?php if($passed_state == true) { ?><a href="<?php echo $this->getActionUrl('ajax_controller_list'); ?>/time=<?php echo time(); ?>" title="Controller文件列表" class="thickbox">文件列表</a>&nbsp;&nbsp;&nbsp;&nbsp;<?php } ?><a href="<?php echo $this->getActionUrl('advanced_controller'); ?>" target="_self">高级操作</a><br /><?php if($passed_state == true) {echo html::image($this->getAssetUrl('images') . 'check_right.gif'), ' WebApp目录已创建,可以操作!';} else {echo html::image($this->getAssetUrl('images') . 'check_error.gif'), ' WebApp目录还没有创建,不可以操作!';}?>
</fieldset>
<!-- /create controller -->

<!-- create widget -->
<fieldset>
<legend>创建Widget:</legend>
<label>Widget Name:</label>&nbsp;&nbsp;<input type="text" class="text" name="widget_name_box" id="widget_name_box" style="width:150px;" <?php echo ($passed_state === false) ? 'disabled="disabled"' : ''; ?>/>&nbsp;&nbsp;&nbsp;&nbsp;<input name="widget_view_file_state" type="checkbox" checked="checked" id="widget_view_file_box"/>视图文件&nbsp;&nbsp;&nbsp;&nbsp;<input id="widget_create_button" type="button" style="width:90px; height:24px; text-align:center; border:1px solid #333; cursor:pointer" value="创建Widget" name="widget_create_button" <?php echo ($passed_state === false) ? 'disabled="disabled"' : ''; ?>><?php if($passed_state == true) { ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo $this->getActionUrl('ajax_widget_list'); ?>/time=<?php echo time(); ?>"  class="thickbox" title="Widget文件列表">文件列表</a><?php } ?><br /><?php if($passed_state == true) {echo html::image($this->getAssetUrl('images') . 'check_right.gif'), ' WebApp目录已创建,可以操作!';} else {echo html::image($this->getAssetUrl('images') . 'check_error.gif'), ' WebApp目录还没有创建,不可以操作!';}?>
</fieldset>
<!-- /create widget -->

<!-- create module -->
<fieldset>
<legend>创建Module:</legend>
<label>Module Name:</label>&nbsp;&nbsp;<input type="text" name="module_name_box" class="text" id="module_name_box" style="width:150px;" <?php echo ($passed_state === false) ? 'disabled="disabled"' : ''; ?>/>&nbsp;&nbsp;&nbsp;&nbsp;<input name="module_view_dir" type="checkbox" id="module_view_dir_box"/>视图目录&nbsp;&nbsp;&nbsp;&nbsp;<input name="module_view_file_state" type="checkbox" id="module_view_file_box"/>视图文件&nbsp;&nbsp;&nbsp;&nbsp;<input id="module_create_button" type="button" style="width:90px; height:24px; text-align:center; border:1px solid #333; cursor:pointer" value="创建Module" name="module_create_button" <?php echo ($passed_state === false) ? 'disabled="disabled"' : ''; ?>><?php if($passed_state == true) { ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo $this->getActionUrl('ajax_module_list'); ?>/time=<?php echo time(); ?>" title="Module文件列表" class="thickbox">文件列表</a><?php } ?><br /><?php if($passed_state == true) {echo html::image($this->getAssetUrl('images') . 'check_right.gif'), ' WebApp目录已创建,可以操作!';} else {echo html::image($this->getAssetUrl('images') . 'check_error.gif'), ' WebApp目录还没有创建,不可以操作!';}?>
</fieldset>
<!-- /create module -->