<?php
class LoginController extends Controller {

	public function indexAction() {

	    //parse login
		$this->parse_login();

	    //display page
	    $this->display();
	}

	/**
	 * ajax完成登录验证
	 */
	public function handle_loginAction() {

	    //session start
		session_start();

		//get params
		$user_name 	= $this->post('user_name');
		$password	= $this->post('user_password');
		$vdcode		= strtolower($this->post('vd_code'));
		if (!$user_name || !$password) {
		    exit('403 Error');
		}

		//分析检验码
		if (strtolower(session::get('doit_tools_vdcode')) != $vdcode) {
			echo 105;
			exit();
		}

		//load login config file
		if ($user_name == DOITPHP_ADMIN_USER && $password == DOITPHP_ADMIN_PASSWORD) {
		    //cookie设置的周期为8小时
			cookie::set('doit_tools_login_state', true, 28800);
			echo 101;
		} else {
			echo 100;
		}
	}

	/**
	 * 验证码页面
	 */
	public function vdcodeAction() {

		//load image_lib clss
		$image_lib = $this->instance('pincode');

		$image_lib->setSessionName('doit_tools_vdcode')->show();
	}

	/**
	 * 注销（登出）
	 */
	public function logoutAction() {

		cookie::set('doit_tools_login_state', false);

		//跳转至登陆页面
		$this->redirect($this->getActionUrl('index'));
	}

	/**
	 * 分析是否登陆
	 */
	protected function parse_login() {

		$login_state = cookie::get('doit_tools_login_state');

		if ($login_state == true) {
			//跳转向首页
			$this->redirect($this->createUrl('index/index'));
		}

		return true;
	}
}