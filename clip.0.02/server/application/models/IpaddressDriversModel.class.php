<?php

class IpaddressDriversModel extends Model
{
	public function getConfig() {
		return Controller::getConfig("clip");
	}
	
	public function get_ip($input=array()){
		if(count($input) == 0) return false;
		$sql = "SELECT * FROM ip_data where ".$input['sql'];
		$results = $this->execute($sql);
		return $this->format($results);
	}
	
	
	private function format($input=array()){
		foreach($input as $key => $value){
			$output[]=$value['ipaddress'];
		}
		return $output;
	}
	
	
	public function query_keys_by_ip($input){
		if(count($input) == 0) return false;
		$sql = "SELECT * FROM ip_data where ipaddress='".$input."'";
		$results = $this->execute($sql);
		return $this->format_query_keys_by_ip($results);
	}
	
	private function format_query_keys_by_ip($input=array()){
		foreach($input as $k=>$value){
			$output[$k][]=$value['idc'];
			$output[$k][]=$value['product'];
			$output[$k][]=$value['modules'];
			$output[$k][]=$value['group'];
			$output[$k][]=$value['ext'];
		}

        foreach($output as $v){
            $tmp[] = implode("-", $v);
        }
		
		return $tmp;
	}

}
