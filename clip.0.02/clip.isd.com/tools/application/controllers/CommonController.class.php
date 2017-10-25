<?php
class CommonController extends Controller {


	/**
	 * 检测项目目录是否存在
	 */
	protected function parse_webapp_root() {

		//分析项目目录
		if (!is_dir(WEBAPP_ROOT)) {
			exit('对不起,项目(WebApp)根目录:' . WEBAPP_ROOT . '不存在.请创建项目根目录');
		}

		//分析应用目录
		if (!is_dir(WEBAPP_ROOT . 'application')) {
			exit('对不起!您还没有创建WebApp目录,请进行操作:WebApp管理->创建WebApp目录');
		}
	}

	/**
	 * 分析用户是否登陆
	 */
	public function parse_login($item = false) {

		$login_state = cookie::get('doit_tools_login_state');

		//ajax页面的登陆判断
		if ($item == true && $login_state == false) {
			exit('或许登陆已过期,请重新登陆!');
		}

		if ($login_state == false) {
			$this->redirect($this->createUrl('login/index'));
		}

		return true;
	}

	/**
	 * 前函数
	 */
	 public function init() {

		 $this->setLayout('main');

		 return true;
	 }
}