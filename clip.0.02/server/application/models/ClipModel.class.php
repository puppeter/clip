<?php
/**
 * file: ClipModel.class.php
 *
 * Enter description here ...
 * @author 
 * @copyright Copyright (C)  All rights reserved.
 * @version $Id: ClipModel.class.php 1.0 2015-01-04 11:26:37Z $
 * @package Model
 * @since 1.0
 */

class ClipModel extends Model {
	public function getConfig() {
		return Controller::getConfig("clip");
	}

	# get tree
	public function Get_product_tree($input=array()){
	    $sql = "SELECT product FROM clip group by product";
	    $results = $this->execute($sql);
	    return $results;
	}

	public function Get_clip_db_relaction($input=array()){
        if(count($input) == 0) false;
        $sql = "SELECT * FROM clip where ".$input['sql']." where flag=1";
        $results = $this->execute($sql);
        return $results;
    }

	# for history 
	public function Get_history($input=array()){
        if(count($input) == 0) return false;
        $sql = "select * from history";
        $results = $this->execute($sql);
        return $results;
    }

    # insert history
    public function Insert_history($input=array()){
        if(count($input) == 0) return false;
        $sql = "replace into history(log) values('".$input['log']."');";
        $results = $this->execute($sql);
        return $results;
    }
    
    # insert clip
    public function Insert_clip($input=array()){
        $clip_sql="replace into clip (idc,product,modules,`group`,ext,s_k,s_v,operator)values('"
        .$input['idc']."','"
        .$input['product']."','"
        .$input['modules']."','"
        .$input['group']."','"
        .$input['port']."',"
        ."'ip','"
        .$input['v']."','"
        .$input['owner']."'"
        .")";
        $results = $this->execute($clip_sql);
        
        $ip_sql="replace into  ip_data(idc,product,modules,`group`,ext,ipaddress)values('"
        .$input['idc']."','"
        .$input['product']."','"
        .$input['modules']."','"
        .$input['group']."','"
        .$input['port']."','"
        .$input['v']."'"
        .")";
        $results = $this->execute($ip_sql);
        return true;
    }
    
    # update clip
    public function Update_clip($input=array()){
       $tmp['sql']="s_v='".$input['ip']."'";

       $arr=$this->Get_clip_db_relaction($tmp);
       if(empty($arr)) return false;
       $clip_sql='update clip set flag='.$input['flag']." where s_v='".$input['ip']."' limit 1";
       $results = $this->execute($clip_sql);
       $ip_data_sql='update ip_data set flag='.$input['flag']." where ipaddress='".$input['ip']."' limit 1";
       $results = $this->execute($ip_data_sql);
        return true;
    }
    
    # delete clip
    public function Delete_clip($input=array()){
       $tmp['sql']="s_v='".$input['ip']."'";
       $arr=$this->Get_clip_db_relaction($tmp);
       if(empty($arr)) return false;
       $clip_sql='delete from  clip where s_v="'.$input['ip'].'" limit 1';
       $results = $this->execute($clip_sql);
       $ip_data_sql='delete from  ip_data where ipaddress="'.$input['ip'].'" limit 1';
       $results = $this->execute($ip_data_sql);
        return true;
    }
}
