<script type="text/javascript">
<!--
$(function(){
	$('#create_app_dir_button').click(function(){
		var module_state=$('#module_state_box').attr('checked');
		<?php if($is_apache == true) {?>
		var htaccess_state=$('#htaccess_state_box').attr('checked');
		<?php } ?>
		var theme_state=$('#theme_state_box').attr('checked');
		var webserver_name=$('#webserver_name_box').val();
		var lang_state=$('#lang_state_box').attr('checked');
		$.post('<?php echo $this->getActionUrl('ajax_create_webapp'); ?>', {module_state:module_state,<?php if($is_apache == true) {?>rewrite_state:htaccess_state,<?php } ?>theme_state:theme_state,webserver_name:webserver_name,lang_state:lang_state}, function(data){
			alert(data);
			location.reload();
		});
	});
	$('#create_config_button').click(function(){
		var driver_name=$('#database_driver_box').val();
		var server_name=$('#server_name_box').val();
		var database_name=$('#database_name_box').val();
		var user_name=$('#user_name_box').val();
		var password=$('#database_password_box').val();
		var database_encode=$('#database_encode_box').val();
		var database_prefix=$('#database_prefix_box').val();
		if(driver_name=='mysqli'||driver_name=='mysql'||driver_name=='pdo_mysql'||driver_name=='postgresql'||driver_name=='pdo_postgresql'||driver_name=='mssql'){
			if(server_name==''){
				$('#server_name_box').css('border', '1px solid #D54E21');
				alert('服务器名称不能为空!');
				$('#server_name_box').focus();
				return false;
			}
			if(user_name==''){
				$('#user_name_box').css('border', '1px solid #D54E21');
				alert('用户名不能为空!');
				$('#user_name_box').focus();
				return false;
			}
			if(database_name==''){
				$('#database_name_box').css('border', '1px solid #D54E21');
				alert('数据库名称不能为空!');
				$('#database_name_box').focus();
				return false;
			}
			$.post('<?php echo $this->getActionUrl('ajax_create_config'); ?>', {driver_name:driver_name,server_name:server_name,user_name:user_name,password:password,database_name:database_name,database_encode:database_encode,database_prefix:database_prefix}, function(data){
				alert(data);
				location.reload();
			});
		}
	})
});
//-->
</script>
<!-- create webapp -->
<fieldset>
<legend>创建WebApp目录:</legend>
<?php if($app_dir_status == false) { ?>
<div class="alert" id="result_box">对不起，您还没有创建所要开发的项目目录，请点击“创建WebApp目录”按钮创建项目目录。</div>
<?php } ?>
<label>Server Software:</label>&nbsp;&nbsp;<select id="webserver_name_box" class="text" style="width:auto;" name="webserver_name"><option <?php if ($is_apache == true) {echo 'selected="selected"';}?>value="apache">Apache</option><option value="other" <?php if(DOIT_VERSION=='sae') {echo 'selected="selected"';} ?>>Other</option></select><?php if($is_apache == true) {?>&nbsp;&nbsp;<input id="htaccess_state_box" type="checkbox" name="htaccess_state">开启ReWrite路由(仅限Apache)<?php } ?>&nbsp;&nbsp;<input id="module_state_box" type="checkbox" name="module_state">Module目录&nbsp;&nbsp;<input id="theme_state_box" type="checkbox" name="theme_state">主题目录&nbsp;&nbsp;<input id="lang_state_box" type="checkbox" name="lang_state">多语言&nbsp;&nbsp;&nbsp;&nbsp;<input id="create_app_dir_button" type="button" style="width:110px; height:24px; text-align:center; border:1px solid #333; cursor:pointer" value="创建WebApp目录" name="create_app_dir_button" <?php echo (!is_dir(WEBAPP_ROOT)) ? 'disabled="disabled"' : ''; ?>/><br /><?php if(!is_dir(WEBAPP_ROOT . 'application')) {?>当前的WebApp根目录：<span class="red"><?php echo WEBAPP_ROOT; ?></span><?php if (is_dir(WEBAPP_ROOT)) {echo html::image($this->getAssetUrl('images') . 'check_right.gif'), ' 已存在,可以操作';} else {echo html::image($this->getAssetUrl('images') . 'check_error.gif'), ' <span class="red">不存在,请先创建WebApp根目录</span> ';}} else { echo html::image($this->getAssetUrl('images') . 'check_right.gif'), ' WebApp目录已创建！但可以操作，原文件、目录不受影响!';}?>
</fieldset>
<!-- /create webapp -->
<?php if($app_dir_status == true) { ?>
<?php $config_file_exists = is_file(WEBAPP_ROOT . 'application/config/config.ini.php') ? true : false; ?>
<!-- create config file -->

<fieldset>
<legend>创建Config文件:</legend>
<table width="720" border="0" cellspacing="0" cellpadding="0" style="width:720px;">
	  <tr>
		<td height="30" colspan="4" align="left"><?php if ($config_file_exists === true) {echo html::image($this->getAssetUrl('images') . 'check_right.gif'), ' Config配置文件已在创建项目目录时创建完成！请编辑Config文件内容，设置数据库连接参数。'; } ?></td>
	  </tr>
	</table>
</fieldset>
<?php } ?>
<!-- /create config file -->
