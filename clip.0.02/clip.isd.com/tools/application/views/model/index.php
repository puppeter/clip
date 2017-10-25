<script type="text/javascript">if(typeof tb_pathToImage!='string'){var tb_pathToImage="<?php echo $baseUrl; ?>thickbox/loading.gif"}</script>
<script type="text/javascript" src="<?php echo $baseUrl; ?>thickbox/thickbox.min.js?version=3.1"></script>
<link href="<?php echo $baseUrl; ?>thickbox/thickbox.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
<!--
$(function(){
	$('#model_create_single_button').click(function(){
		var model_name=$('#model_name_single_box').val();
		var fields_state=$('#model_field_single_box').attr('checked');
		var table_state = $('#model_tabname_single_box').attr('checked');
		if(model_name==''){
			$('#model_name_single_box').css('border', '1px solid #D54E21');
			alert('所要创建的Model名称不能为空!');
			$('#model_name_single_box').focus();
			return false;
		}
		$.post('<?php echo $this->getActionUrl('ajax_create_single_model'); ?>', {model_name:model_name, fields_state:fields_state, table_state:table_state}, function(data){
			alert(data);
		});
	});

	$('#model_create_all_button').click(function(){
		var fields_state=$('#model_field_all_box').attr('checked');
		var table_state = $('#model_tabname_all_box').attr('checked');
		$.post('<?php echo $this->getActionUrl('ajax_create_all_model'); ?>', {fields_state:fields_state, table_state:table_state}, function(data){
			alert(data);
		});
	});

	$('#clear_model_single_button').click(function(){
		var model_cache_name=$('#model_name_clear_box').val();
		if(model_cache_name==''){
			$('#model_name_clear_box').css('border', '1px solid #D54E21');
			alert('所要清空缓存的Model名称不能为空!');
			$('#model_name_clear_box').focus();
			return false;
		}
		if(confirm('您确认要进行删除'+model_cache_name+' Model缓存?')){
			$.post('<?php echo $this->getActionUrl('ajax_clear_single_model'); ?>', {model_cache_name:model_cache_name}, function(data){
				alert(data);
			});
		}else{
			return false;
		}
	});

	$('#clear_model_all_button').click(function(){
		if(confirm('您确认要删除全部的Model缓存?')){
			$.post('<?php echo $this->getActionUrl('ajax_clear_all_model'); ?>', {}, function(data){
				alert(data);
			});
		}else{
			return false
		}
	});
});
//-->
</script>
<?php $passed_state = is_dir(WEBAPP_ROOT . 'application') ? true : false; ?>
<!-- create single model -->
<fieldset>
<legend>创建Model:</legend>
<label>Model Name:</label>&nbsp;&nbsp;<input type="text" name="model_name_single_box" id="model_name_single_box" class="text" style="width:150px;" <?php echo ($passed_state === false) ? 'disabled="disabled"' : ''; ?>/>&nbsp;&nbsp;&nbsp;&nbsp;<input id="model_field_single_box" type="checkbox" name="model_field_single_box">字段信息(仅限Mysql)&nbsp;&nbsp;&nbsp;&nbsp;<input id="model_tabname_single_box" type="checkbox" name="model_tabname_single_box">数据表名(仅限Mysql)&nbsp;&nbsp;&nbsp;&nbsp;<input id="model_create_single_button" type="button" style="width:80px; height:24px; text-align:center; border:1px solid #333; cursor:pointer" value="创建Model" name="model_create_single_button" <?php echo ($passed_state === false) ? 'disabled="disabled"' : ''; ?>/><?php if($passed_state == true) {?>&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo $this->getActionUrl('ajax_model_list'); ?>/?time=<?php echo time()?>" class="thickbox" name="Model文件列表">文件列表</a>&nbsp;&nbsp;&nbsp;&nbsp;<?php } ?><a href="<?php echo $this->getActionUrl('advanced_model'); ?>" target="_self">高级操作</a><br /><?php if($passed_state == true) {echo html::image($this->getAssetUrl('images') . 'check_right.gif'), ' WebApp目录已创建,可以操作!';} else {echo html::image($this->getAssetUrl('images') . 'check_error.gif'), ' WebApp目录还没有创建,不可以操作!';}?>
</fieldset>
<!-- /create single model -->

<!-- create all models -->
<fieldset>
<legend>创建全部Model:</legend>
<input id="model_field_all_box" type="checkbox" name="model_field_all_box" style="margin-left:270px;">字段信息(仅限Mysql)&nbsp;&nbsp;&nbsp;&nbsp;<input id="model_tabname_all_box" type="checkbox" name="model_tabname_all_box">数据表名(仅限Mysql)&nbsp;&nbsp;&nbsp;&nbsp;<input id="model_create_all_button" type="button" style="width:100px; height:24px; text-align:center; border:1px solid #333; cursor:pointer" value="创建全部Model" name="model_create_all_button" <?php echo ($passed_state === false) ? 'disabled="disabled"' : ''; ?>/><br /><?php if($passed_state == true) {echo html::image($this->getAssetUrl('images') . 'check_right.gif'), ' WebApp目录已创建,可以操作!';} else {echo html::image($this->getAssetUrl('images') . 'check_error.gif'), ' WebApp目录还没有创建,不可以操作!';}?>
</fieldset>
<!-- /create all models -->

<!-- clear single model cache -->
<fieldset>
<legend>清空Model缓存:</legend>
<label>Model Name:</label>&nbsp;&nbsp;<input type="text" name="model_name_clear_box" id="model_name_clear_box" class="text" style="width:150px;" <?php echo ($passed_state === false) ? 'disabled="disabled"' : ''; ?>/>&nbsp;&nbsp;&nbsp;&nbsp;<input id="clear_model_single_button" type="button" style="width:110px; height:24px; text-align:center; border:1px solid #333; cursor:pointer" value="清空Model缓存" name="clear_model_single_button" <?php echo ($passed_state === false) ? 'disabled="disabled"' : ''; ?>/><br /><?php if($passed_state == true) {echo html::image($this->getAssetUrl('images') . 'check_right.gif'), ' WebApp目录已创建,可以操作!';} else {echo html::image($this->getAssetUrl('images') . 'check_error.gif'), ' WebApp目录还没有创建,不可以操作!';}?>
</fieldset>
<!-- /clear single model cache -->

<!-- clear all model cache -->
<fieldset>
<legend>清空全部Model缓存:</legend>
<input id="clear_model_all_button" type="button" style="margin-left:270px; width:130px; height:24px; text-align:center; border:1px solid #333; cursor:pointer" value="清空全部Model缓存" name="clear_model_all_button" <?php echo ($passed_state === false) ? 'disabled="disabled"' : ''; ?>/><br /><?php if($passed_state == true) {echo html::image($this->getAssetUrl('images') . 'check_right.gif'), ' WebApp目录已创建,可以操作!';} else {echo html::image($this->getAssetUrl('images') . 'check_error.gif'), ' WebApp目录还没有创建,不可以操作!';}?>
</fieldset>
<!-- /clear all model cache -->