<?php

class DbConnection{
    public $db_host = "localhost";
    public $db_user = "root";
    public $db_pass = "";
    public $db_name = "gevme";
    public $db_connection = null;

    public function __construct() {
        $this->db_connection = mysqli_connect($this->db_host,$this->db_user,$this->db_pass, $this->db_name);
        if($this->db_connection->connect_error){
            echo 'connection error';
        }
    }
    
}


class PerfromCrud extends DbConnection {
    
    public function save($param){
        if(isset($param['action']) ){
            unset($param['action']);
        }

        $update_string = [];
        $is_updateable = false;

        if(isset($param['in_contact_id']) && !empty($param['in_contact_id']) ){
            echo __LINE__;
            $is_updateable = true;
        }

        foreach ($param as $ps_key => $ps_value) {
            $param[$ps_key] = mysqli_real_escape_string($this->db_connection,$ps_value);
            if(is_array($update_string)){
                $update_string[] = " ".$ps_key." = '".mysqli_real_escape_string($this->db_connection,$ps_value). "'";
            }
        }

        $dataExists = $this->getCountForExisting($param['st_email']);

        if($is_updateable === FALSE && isset($dataExists['in_contact_id']) && !empty($dataExists['in_contact_id']) && isset($dataExists['in_contact_id']) && is_numeric($dataExists['in_revisitCount']) ){
            $is_updateable = false;
            $param['in_contact_id'] = $dataExists['in_contact_id'];
            $in_revisitCount = $dataExists['in_revisitCount']+1;
            $update_string[] = " in_revisitCount = '".mysqli_real_escape_string($this->db_connection,$in_revisitCount). "'";
        }

        if($is_updateable === FALSE && ($dataExists == 0 || $dataExists == false)){
            $query_string = 'Insert INTO gevme_table ('.implode(', ',array_keys($param)).') values ("'.implode('", "',$param).'"); ';
        }else if(is_array($update_string) && isset($param['in_contact_id'])){
            $query_string = "Update gevme_table Set ".implode(", ",$update_string).", dt_modified = '".date('y-m-d h:i:s')."' where in_contact_id = ".$param['in_contact_id']."; ";
        }

        return $this->db_connection->query($query_string);
    }

    public function getContactList(){
        $query_string = "Select * from gevme_table where is_deleted = 0 Order By in_contact_id Desc";
        $result = $this->db_connection->query($query_string);
        return $result -> fetch_all(MYSQLI_ASSOC);
    }

    public function delete($param){
        $query_string = "Update gevme_table Set is_deleted = 1 where in_contact_id = ".$param['in_contact_id']."; ";
        return $this->db_connection->query($query_string);
    }

    public function isNotEmpty($param){
        if(isset($param) && !empty($param) ){
            return true;
        }
        return false;
    }

    public function getCountForExisting($st_email = ""){
        $return_data = false;

        if(isset($st_email) && !empty($st_email) ){
            $query_string = "Select in_contact_id,in_revisitCount from gevme_table where is_deleted = 0 and st_email = '".$st_email."' ";
            $result = $this->db_connection->query($query_string);
            $query_data = $result -> fetch_all(MYSQLI_ASSOC);
            if(isset($query_data[0]) && isset($query_data[0]['in_contact_id']) ){
                $return_data = $query_data[0];
            }
        }
        return $return_data;
    }


    public function validateForInsert($param = []){
        $required_data = [];
        $required_data["st_full_name"]= "st_full_name";
        $required_data["st_email"]= "st_email";
        $required_data["in_contact_number"]= "in_contact_number";

        return $this->validateInDepth($required_data, $param);
    }

    public function validateForUpdate($param = []){
        $required_data = [];
        $required_data["in_contact_id"]= "in_contact_id";
        $required_data["st_full_name"]= "st_full_name";
        $required_data["st_email"]= "st_email";
        $required_data["in_contact_number"]= "in_contact_number";

        return $this->validateInDepth($required_data, $param);
    }

    public function validateForContactId($param = []){
        $required_data = [];
        $required_data["in_contact_id"]= "in_contact_id";

        return $this->validateInDepth($required_data, $param);
    }

    public function validateInDepth($required_data = [], $param = []){
        $required_empty_data = [];
        if($this->isNotEmpty($param) && is_array($param) && empty(array_diff_key($required_data, $param)) ){
            foreach ($param as $pc_key => $pc_value) {
                if(!$this->isNotEmpty($pc_value)){
                    $required_empty_data[$pc_key] = "Invalid input.";
                }
            }
        }

        if($this->isNotEmpty($required_empty_data) && is_array($required_empty_data) ){
            return $required_empty_data;  
        }else{
            return true;
        }
    }

    public function createTr($param = []){
        $return_data = "";
        if($this->isNotEmpty($param) && is_array($param) ){

            foreach ($param as $pc_key => $pc_value) {
                $return_data .= "<tr>";
                $return_data .= "<td>".$pc_value['in_contact_id']."</td>";
                $return_data .= "<td>".$pc_value['st_full_name']."</td>";
                $return_data .= "<td>".$pc_value['st_email']."</td>";
                $return_data .= "<td class='contact_number'>".$pc_value['in_contact_number']."</td>";
                $return_data .= "<td>".$pc_value['in_revisitCount']."</td>";
                $return_data .= "<td> <a class='btn btn-primary edit_user' data-st_full_name='".stripslashes($pc_value['st_full_name'])."' data-st_email='".stripslashes($pc_value['st_email'])."' data-in_contact_id='".$pc_value['in_contact_id']."' data-in_contact_number='".$pc_value['in_contact_number']."'> Edit </a>  <a class='btn btn-warning delete_user' data-delete_id='".$pc_value['in_contact_id']."'> Delete </a></td>";
                $return_data .= "</tr>";
            }
        }

        return $return_data;
    }

    public function getContactDetail($param){
        $query_string = "Select * from gevme_table where in_contact_id = ".$param['in_contact_id'].";";
        $result = $this->db_connection->query($query_string);
        return $result -> fetch_all(MYSQLI_ASSOC);
    }

}

$return_data = array('result'=>'fail');
$PerfromCrud  = new PerfromCrud();
$post_data = $_POST;

if($PerfromCrud->isNotEmpty($post_data['action']) && $post_data['action'] == "list" ){
    $rows_data = $PerfromCrud->getContactList();
    $return_data = [];
    $return_data['result'] = 'success';
    $return_data['message'] = 'Updated Listing Loaded.';
    $return_data['rows_data'] = $PerfromCrud->createTr($rows_data);

}else if($PerfromCrud->isNotEmpty($post_data['action']) && $post_data['action'] == "add" && $validation_check = $PerfromCrud->validateForInsert($post_data)){
    if(is_array($validation_check)){
        $return_data['errors'] = $validation_check;
    }else{
        $PerfromCrud->save($post_data);
        $rows_data = $PerfromCrud->getContactList();
        $return_data = [];
        $return_data['result'] = 'success';
        $return_data['message'] = 'Data updated successfully.';
        $return_data['rows_data'] = $PerfromCrud->createTr($rows_data);
    }
}else if($PerfromCrud->isNotEmpty($post_data['action']) && $post_data['action'] == "edit" && $validation_check = $PerfromCrud->validateForUpdate($post_data)){
    if(is_array($validation_check)){
        $return_data['errors'] = $validation_check;
    }else{
        $PerfromCrud->save($post_data);
        $rows_data = $PerfromCrud->getContactList();
        $return_data = [];
        $return_data['result'] = 'success';
        $return_data['message'] = 'Data updated successfully.';
        $return_data['rows_data'] = $PerfromCrud->createTr($rows_data);
    }
}else if($PerfromCrud->isNotEmpty($post_data['action']) && $post_data['action'] == "delete" && $validation_check = $PerfromCrud->validateForContactId($post_data)){
    if(is_array($validation_check)){
        $return_data['errors'] = $validation_check;
    }else{
        $PerfromCrud->delete($post_data);
        $rows_data = $PerfromCrud->getContactList();
        $return_data = [];
        $return_data['result'] = 'success';
        $return_data['message'] = 'Data deleted successfully.';
        $return_data['rows_data'] = $PerfromCrud->createTr($rows_data);
    }
}else if($PerfromCrud->isNotEmpty($post_data['action']) && $post_data['action'] == "get_particular" && $validation_check = $PerfromCrud->validateForContactId($post_data)){
    if(is_array($validation_check)){
        $return_data['errors'] = $validation_check;
    }else{
        $rows_data = $PerfromCrud->getContactDetail($post_data);
        $return_data = [];
        $return_data['result'] = 'success';
        $return_data['rows_data']= $rows_data;
    }
}

echo json_encode($return_data);
exit;
