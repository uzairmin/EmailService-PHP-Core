<?php

require "validation.php";
require "main.php";

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

class Admin
{
    function getData()
    {
        $data = json_decode(file_get_contents("php://input"),true);
        $table = $data['table'];
        $email = $data['email'];
        $parameter = array($table,$email);

        if($parameter[0]=="")
        {
            echo json_encode(array('Message'=>'Please Enter table','status'=>'409'));
            die();
        }
        if($parameter[1]=="")
        {
            echo json_encode(array('Message'=>'Please Enter email!','status'=>'409'));
            die();
        }
        return $parameter;
    }
    function parametersValidation($parameter)
    {
        $flag = true;
        $validate = new Validate();                                                                                                        
        if(!$validate->email_validate($parameter[1]))  { $flag=false; }
        return $flag;
    }
    function show($parameter)
    {
        $email = $parameter[1];
        $table = $parameter[0];
        $main = new Mainn();
        $main->fetchAll($table,$email);
    }
}

$admin = new Admin();
$data = $admin->getData();
$val = $admin->parametersValidation($data);
$admin->show($data);
?>