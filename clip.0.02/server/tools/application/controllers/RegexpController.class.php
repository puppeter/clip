<?php
class RegexpController extends CommonController {

	public function indexAction() {

	    //parse login status
	    $this->parse_login();

		//assign params
		$this->assign(array(
		));

		//display page
		$this->display();
	}

	/**
	 * AJAX处理正则表达式的匹配
	 */
	public function ajax_handle_regexpAction() {

	    //parse login status
	    $this->parse_login(true);

        //get params
		$regexp_mode 	= trim($_POST['regexp_mode']);
		$is_s_state		= $this->post('is_s_state');
		$is_i_state		= $this->post('is_i_state');
		$regexp_content	= trim($_POST['regexp_content']);

		//parse params
		if (empty($regexp_mode) || empty($regexp_content)) {
			exit();
		}

		$extension_mode = '';
		if ($is_i_state == 'checked') {
			$extension_mode .= 'i';
		}
		if ($is_s_state == 'checked') {
			$extension_mode .= 's';
		}

		$regexp_mode = str_replace('\\\\', '\\', $regexp_mode);
		$regexp_mode = '#' . $regexp_mode . '#' . $extension_mode;

		if (preg_match_all($regexp_mode, $regexp_content, $output)) {
			echo '<span style="color:#D54E21;">匹配成功!</span><br/>';
			echo '匹配模式为: <span style="color:#2583AD;">', $regexp_mode, '</span><br/>';
			echo '匹配的字符为: ', htmlspecialchars($output[0][0]);
		} else {
			echo '<span style="color:#D54E21;">匹配失败!</span><br/>';
			echo '匹配模式为: <span style="color:#2583AD;">', $regexp_mode, '</span>';
		}
	}
}