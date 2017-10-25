<?php
class main_menuWidget extends Widget {

	public function renderContent($params = null){
		$controllerName = doit::getControllerName();

		$passed_state = is_dir(WEBAPP_ROOT . 'application') ? true : false;

		//display widget page
		include $this->getViewFile();
	}
}