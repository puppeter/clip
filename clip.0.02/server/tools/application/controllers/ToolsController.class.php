<?php
class ToolsController extends CommonController {

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
	 * AJAX完成字符的strlen()
	 */
	public function ajax_count_strlenAction() {

	    //parse login status
	    $this->parse_login(true);

	    //get params
		$str_content = trim($_POST['str_content']);
		if (!$str_content) {
			exit('403 Error!');
		}


		//handle strlen
		echo '字符串长度:', strlen($str_content);
	}

	/**
	 * AJAX完成字符串转化为ASCII
	 */
	public function ajax_string_2_asciiAction() {

	    //parse login status
	    $this->parse_login(true);

	    //get params
		$str_content = trim($_POST['ascii_content']);
	    if (!$str_content) {
			exit('403 Error!');
		}

        echo $this->encode($str_content);
	}

    /**
     * 将字符串转化为ASCII码
     *
     * @access protected
     * @param string $c 字符串
     * @return integer
     */
    protected function encode($c) {
        $len = strlen($c);
        $a = 0;
         while ($a < $len) {
            $ud = 0;
             if (ord($c{$a}) >=0 && ord($c{$a})<=127) {
                $ud = ord($c{$a});
                $a += 1;
             } else if (ord($c{$a}) >=192 && ord($c{$a})<=223) {
                $ud = (ord($c{$a})-192)*64 + (ord($c{$a+1})-128);
                $a += 2;
             } else if (ord($c{$a}) >=224 && ord($c{$a})<=239) {
                $ud = (ord($c{$a})-224)*4096 + (ord($c{$a+1})-128)*64 + (ord($c{$a+2})-128);
                $a += 3;
             } else if (ord($c{$a}) >=240 && ord($c{$a})<=247){
                $ud = (ord($c{$a})-240)*262144 + (ord($c{$a+1})-128)*4096 + (ord($c{$a+2})-128)*64 + (ord($c{$a+3})-128);
                $a += 4;
             }else if (ord($c{$a}) >=248 && ord($c{$a})<=251){
                $ud = (ord($c{$a})-248)*16777216 + (ord($c{$a+1})-128)*262144 + (ord($c{$a+2})-128)*4096 + (ord($c{$a+3})-128)*64 + (ord($c{$a+4})-128);
                $a += 5;
             }else if (ord($c{$a}) >=252 && ord($c{$a})<=253){
                $ud = (ord($c{$a})-252)*1073741824 + (ord($c{$a+1})-128)*16777216 + (ord($c{$a+2})-128)*262144 + (ord($c{$a+3})-128)*4096 + (ord($c{$a+4})-128)*64 + (ord($c{$a+5})-128);
                $a += 6;
             }else if (ord($c{$a}) >=254 && ord($c{$a})<=255) {
                $ud = false;
             }
            $scill .= "&#$ud;";
         }
         return $scill;
    }
}