<table border="0" cellpadding="0" cellspacing="1" bgcolor="#999999" style="margin-top:15px;">
<tr>
<th width="50" align="center" style="width:20px;">No</th>
<th align="center">Widget名称</td>
<th width="160" align="center">创建时间</th>
</tr>
<?php 
if($file_list_array == true) {
	foreach($file_list_array as $key => $lines) {
?>
<tr>
<td align="center" bgcolor="#FFFFFF"><?php echo $key + 1; ?></td>
<td align="left" bgcolor="#FFFFFF"><?php echo $lines['name']; ?></td>
<td align="center" bgcolor="#FFFFFF" style="font-size:12px;"><?php echo date('Y-m-d H:i:s', $lines['time']); ?></td>
</tr>
<?php 
	}
}
?>
</table>