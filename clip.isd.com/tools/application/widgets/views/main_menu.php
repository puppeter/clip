<div class="nav">
	<ul>
	<?php if ($controllerName == 'index') { ?>
	<li class="current">首页</li>
	<?php } else {?>
	<li><a href="<?php echo $this->createUrl('index/index'); ?>">首页</a></li>
	<?php
		}
	?>
<?php if ($passed_state == true) {?>
	<?php
		if ($controllerName == 'controller') {
	?>
	<li class="current">Controller管理</li>
	<?php
		} else {
	?>
	<li><a href="<?php echo $this->createUrl('controller/index'); ?>">Controller管理</a></li>
	<?php
		}
	?>

	<?php
		if ($controllerName == 'model') {
	?>
	<li class="current">Model管理</li>
	<?php
		} else {
	?>
	<li><a href="<?php echo $this->createUrl('model/index'); ?>">Model管理</a></li>
	<?php
		}
	?>

	<?php
		if ($controllerName == 'file') {
	?>
	<li class="current">文件管理</li>
	<?php
		} else {
	?>
	<li><a href="<?php echo $this->createUrl('file/index'); ?>">文件管理</a></li>
	<?php
		}
	?>
<?php } ?>
	<?php
		if ($controllerName == 'webapp') {
	?>
	<li class="current">WebApp管理</li>
	<?php
		} else {
	?>
	<li><a href="<?php echo $this->createUrl('webapp/index'); ?>">WebApp管理</a></li>
	<?php
		}
	?>
<?php if ($passed_state == true) {?>
	<?php
		if ($controllerName == 'regexp') {
	?>
	<li class="current">正则表达式</li>
	<?php
		} else {
	?>
	<li><a href="<?php echo $this->createUrl('regexp/index'); ?>">正则表达式</a></li>
	<?php
		}
	?>

	<?php
		if ($controllerName == 'tools') {
	?>
	<li class="current">其它工具</li>
	<?php
		} else {
	?>
	<li><a href="<?php echo $this->createUrl('tools/index'); ?>">其它工具</a></li>
	<?php } ?>
<?php } ?>
	</ul>
</div>