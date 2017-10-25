<?php
/**
 * file: ClipController.class.php
 *
 * todo...
 * @author 
 * @copyright Copyright (C)  All rights reserved.
 * @version $Id: ClipController.class.php 1.0 2015-01-04 11:26:32Z $
 * @package Controller
 * @since 1.0
 */

class ClipController extends Controller {

/**
 * Enter datacription...
 *
 * @access public
 * @return string
 */


const Guest_Key='b5a6992e705d141f216015902fd58f1e';
const Admin_Key='e0d25aa45a6e8fc68f3c075f1495c234';
const Default_Key='e0d25aa45a6e8fc68f3c075f1495c234';

public function indexAction() {
	echo "test ok";

}

public function net_log_dbAction(){
    # input
    $input=array();
    $input['log'] =urldecode($this->get('log'));
    $input['operator'] =$this->get('operator');
    $input['signature'] =$this->get('signature');
    $input['action'] =$this->get('action');

    if($input['operator'] == "guest"){
        $key=self::Guest_Key;
    }elseif($input['operator'] == "admin"){
        $key=self::Admin_Key;
    }else{
        $key=self::Default_Key;
    }

    $signature=md5("signature"."-".$key."-".date('H')); 
    if(empty($input['signature'])|| $input['signature'] != $signature){
        Log::write("line:".__LINE__."| res:premission deny| operator:".$input['operator'],error);
        $output['ret']="1";
        $output['data']="line:".__LINE__."\tres:permission error";
        die(json_encode($output));
    }

    $model=$this->model('Clip');
    if($input['action'] == "true"){
        $res=$model->Get_history($input);
        $tmp=array();
        foreach($res as $key ){
            $tmp[]="[".$key['timestamp']."] ".$key['log'];
        }
        echo implode("|",$tmp);
    }else{
        $res=$model->Insert_history($input);
    }
}

public function api_get_treeAction(){

    # input
    $input=array();
    $input['cstring'] =$this->get('cstring');
    $input['operator'] =$this->get('operator');
    $input['signature'] =$this->get('signature');

    if($input['operator'] == "guest"){
        $key=self::Guest_Key;
    }elseif($input['operator'] == "admin"){
        $key=self::Admin_Key;
    }else{
        $key=self::Default_Key;
    }

    $signature=md5($input['cstring']."-".$key."-".date('H'));
    
    if(empty($input['signature'])|| $input['signature'] != $signature){
        Log::write("line:".__LINE__."| res:premission deny| operator:".$input['operator'],error);
        $output['ret']="1";
        $output['data']="line:".__LINE__."\tres:permission error";
        die(json_encode($output));
    }
    $output['ret']="0";
    $output['data']=$this->getcstring_tree($input);
    echo json_encode($output);

}

public function get_propertyAction(){

    # input
    $input=array();
    $input['cstring'] =$this->get('cstring');
    $input['operator'] =$this->get('operator');
    $input['signature'] =$this->get('signature');

    if($input['operator'] == "guest"){
        $key=self::Guest_Key;
    }elseif($input['operator'] == "admin"){
        $key=self::Admin_Key;
    }else{
        $key=self::Default_Key;
    }

    $signature=md5($input['cstring']."-".$key."-".date('H'));
    
    if(empty($input['signature'])|| $input['signature'] != $signature){
        Log::write("line:".__LINE__."| res:premission deny| operator:".$input['operator'],error);
        $output['ret']="1";
        $output['data']="line:".__LINE__."\tres:permission error";
        die(json_encode($output));
    }

    $arr=explode("-",$input['cstring']);
    $input['idc']=$arr['0'];
    $input['product']=$arr['1'];
    $input['modules']=$arr['2'];
    $input['group']=$arr['3'];
    $input['ext']=$arr['4'];

    $model=$this->model('Clip');
    $result=$model->Get_property($input);
    $tmp['cstring']=$result['0']['cstring'];
    $tmp['docker_id']=$result['0']['docker_id'];
    $tmp['docker_version']=$result['0']['docker_version'];
    $tmp['app_port']=$result['0']['app_port'];
    $tmp['admin_port']=$result['0']['admin_port'];
    $tmp['count_container']=$result['0']['mcount'];

    $output['ret']="0";
    $output['data']=$tmp;
    echo json_encode($output);

}

private function getcstring_tree($input=array()) {

    $arr=explode("-",$input['cstring']);

    if($arr['0'] != "*") $data[]="idc='".$arr['0']."'";

    if(empty($arr['1']) ) {
        die("error");
    }else{
        $data[]="product='".$arr['1']."'";
    }
    if($arr['2'] != "*") $data[]="modules='".$arr['2']."'";
    if($arr['3'] != "*") $data[]="`group`='".$arr['3']."'";
    if(!empty($arr['4']) && $arr['4'] != "*") $data[]="ext='".$arr['4']."'";

    $data['sql']=implode(" and ", $data);

    $model=$this->model('Clip');
    if($arr['1'] == "*"){
        $res=$model->Get_product_tree($data);
            foreach($res as $key){
                if(!empty($key['product'])){
                    $output[]="*-".$key['product']."-*-*";
            }
        }
    }else{
        $res=$model->Get_clip_db_relaction($data);
            foreach($res as $key){
                $output[]=$key['idc']."-".$key['product']."-".$key['modules']."-".$key['group']."-".$key['ext'];
            }	
    }

    return implode("|",array_unique($output));
}

public function api_version1Action() {
    $input=array();
    $input['parameter'] =$this->get('parameter');
    $input['ip'] =$this->get('ip');
    $input['cstring'] =$this->get('cstring');
    $input['operator'] =$this->get('operator');
    $input['group'] =$this->get('group');
    $input['format'] =$this->get('format');
    $input['signature'] =$this->get('signature');
    $input['cacheswitch'] =$this->get('cacheswitch');

    
    $parameter_allow_array=array("getip","getcstring","mgetip","mgetcstring");	

    $output['ret']="0"; # 0 succ

    if(!in_array($input['parameter'],$parameter_allow_array)){
        Log::write("line:".__LINE__." | res:parameter error | operator:".$input['operator'],error);
        $output['ret']="1";
        $output['data']="line:".__LINE__."\tres:parameter error";
        die(json_encode($output));
    }

    $arr=explode("-", $input['cstring']);
    if($input['parameter'] == "getcstring" && (count($arr) != "4" && count($arr) != "5")){
        Log::write("line:".__LINE__." | res:cstring error| operator:".$input['operator'],error);
        $output['ret']="1";
        $output['data']="line:".__LINE__."\tres:cstring error";
        die(json_encode($output));
    }

    $arr=explode(",", $input['cstring']);
    if($input['parameter'] == "mgetcstring" && count($arr) < "2"){
        Log::write("line:".__LINE__." | res:cstring error| operator:".$input['operator'],error);
        $output['ret']="1";
        $output['data']="line:".__LINE__."\tres:cstring error";
        die(json_encode($output));
    }

    if(empty($input['format'])) $input['format']='text';

    $format_allow_array=array("text","json");
    if(!in_array($input['format'],$format_allow_array)){
        Log::write("line:".__LINE__." | res:format error| operator:".$input['operator'],error);
        $output['ret']="1";
        $output['data']="line:".__LINE__."\tres:format error";
        die(json_encode($output));
    }

    if($input['parameter']== "getip" && empty($input['ip'])){
        Log::write("line:".__LINE__." | res:ip error| operator:".$input['operator'],error);
        $output['ret']="1";
        $output['data']="line:".__LINE__."\tres:ip error";
        die(json_encode($output));
    }

    if($input['parameter'] == "cstring" && empty($input['cstring'])){
        Log::write("line:".__LINE__." | res:cstring error| operator:".$input['operator'],error);
        $output['ret']="1";
        $output['data']="line:".__LINE__."\tres:cstring error";
        die(json_encode($output));
    }

    if(empty($input['operator'])){
        Log::write("line:".__LINE__." | res:operator error| operator:".$input['operator'],error);
        $output['ret']="1";
        $output['data']="line:".__LINE__."\tres:operator error";
        die(json_encode($output));
    } 


    if($input['operator'] == "guest"){
        $key=self::Guest_Key;
    }elseif($input['operator'] == "admin"){
        $key=self::Admin_Key;
    }else{
        $key=self::Default_Key;
    }

    if($input['parameter']== "getip") $signature=md5($input['ip']."-".$key."-".date('H'));
    if($input['parameter']== "mgetip") $signature=md5($input['ip']."-".$key."-".date('H'));
    if($input['parameter']== "getcstring") $signature=md5($input['cstring']."-".$key."-".date('H'));
    if($input['parameter']== "mgetcstring") $signature=md5($input['cstring']."-".$key."-".date('H'));
    if(empty($input['signature'])|| $input['signature'] != $signature){
        Log::write("line:".__LINE__."| res:premission deny| operator:".$input['operator'],error);
        $output['ret']="1";
        $output['data']="line:".__LINE__."\tres:permission error";
        die(json_encode($output));
    }

    switch($input['parameter']){
        case 'getcstring':
            $r=$this->model('IpaddressDrivers');
            $sql=$this->getcstring(array('cstring'=>$input['cstring']));
            $output['data']=$r->get_ip(array('sql'=>$sql));
            break;
        case 'mgetcstring':
            $r=$this->model('IpaddressDrivers');
            $arr=explode(',',$input['cstring']);
            foreach($arr as $k){
                $sql=$this->getcstring(array('cstring'=>$k));
                $tmp=$r->get_ip(array('sql'=>$sql));
                foreach($tmp as $ip){
                    $output['data'][]=$ip;
                }
            }
            break;

        case 'getip':
            $output['data']=$this->getip($input['ip']);
            break;
        case 'mgetip':
            $output['data']=$this->mgetip($input['ip']);
            break;
    }
    
    switch($input['format']){
        case 'json':
            echo json_encode($output['data']);
            break;
        case 'array':
            print_R($output['data']);
            break;
        case 'space':
            echo implode(" ",$output['data']);	
            break;
        default:
            $output['ret']="0";
            $output['data']=implode("|",array_unique($output['data']));
            echo json_encode($output);
    }
    exit(0);
}

private function getip($ip){
    $ipaddress_drivers=$this->model('IpaddressDrivers');
    $data=$ipaddress_drivers->query_keys_by_ip($ip);
    return $data;
}

private function mgetip($ip){
    $res_data=array();
    $arr=explode(",",$ip);
    foreach ($arr as $key => $value){
        $tmp[]=$this->getip(trim($value));
    }   

    foreach($tmp as $key => $value){
        foreach($value as $k=>$v){
            $res_data[]=$v;
    }
    }

    for($i=0;$i<count($res_data);$i++) $new_array[]=$res_data[$i]." => ".$arr[$i];
   
    return $new_array;
}
	
private function getcstring($input=array()) {
    $arr=explode("-", $input['cstring']);
    if($arr['0'] != "*") $data[]="idc='".$arr['0']."'";
    if(empty($arr['1']) && $arr['1'] =="*") {
            die("error");
    }else{
            $data[]="product='".$arr['1']."'";
    }
    if($arr['2'] != "*") $data[]="modules='".$arr['2']."'";
    if($arr['3'] != "*") $data[]="`group`='".$arr['3']."'";
    if(!empty($arr['4']) && $arr['4'] != "*") $data[]="ext='".$arr['4']."'";
    $data['sql']=implode(" and ", $data);	
    return $data['sql'];
}

public function clip_registerAction() {
    $input=array();
    $input['idc'] =$this->get('idc');
    $input['product'] =$this->get('product');
    $input['modules'] =$this->get('modules');
    $input['group'] =$this->get('group');
    $input['port'] =$this->get('port');
    $input['k'] =$this->get('k');
    $input['v'] =$this->get('v');
    $input['owner'] =$this->get('owner');
    $input['operator'] =$this->get('operator');
    $input['signature'] =$this->get('signature');
    
    if($input['operator'] == "guest"){
        $key=self::Guest_Key;
    }elseif($input['operator'] == "admin"){
        $key=self::Admin_Key;
    }else{
        $key=self::Default_Key;
    }
	

    $url="idc=".$input['idc']."&product=".$input['product']."&modules=".$input['modules']."&group=".$input['group']
    ."&port=".$input['port']."&v=".$input['v']."&owner=".$input['owner'];
	$signature=md5($url."-".$key."-".date('H'));
	if(empty($input['signature'])|| $input['signature'] != $signature){
	    Log::write("line:".__LINE__."| res:premission deny| operator:".$input['operator'],error);
	    $output['ret']="1";
	    $output['data']="line:".__LINE__."\tres:permission error";
	    die(json_encode($output));
	}

    $model=$this->model('Clip');
    $res=$model->Insert_clip($input);
    if($res){
        $output['ret']="0";
        $tmp['cstring']=$input['idc']."-".$input['product']."-".$input['modules']."-".$input['group']."-".$input['port'];
        $tmp['v']=$input['v'];
        $tmp['operator']=$input['operator'];
        $output['data']=implode("|",$tmp)."| succ";
        echo json_encode($output);
    }else{
        $output['ret']="1";
        $output['data']="db error";
        echo json_encode($output);
    }
}

}
