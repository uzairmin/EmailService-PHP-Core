<?php

require "validation.php";
require "main.php";

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST"); //header used to insert data
header("Content-Type: application/json");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

class UserLogout
{
    private $email;
    public function __construct()
    {
        $email="";
    }
    public function getData()
    {
        
        $data = json_decode(file_get_contents("php://input"),true);
        $email = $data['email'];

        if($email=="")
        {
            echo json_encode(array('Message'=>'Please Enter email!','status'=>'409'));
            die();
        }
        else
        {
             return $email; 
        }
    }
    public function parametersValidation($email)
    {
        $flag=true;
        $validate = new Validate();                                                                                                           
        if(!$validate->email_validate($email))  { $flag=false; }
        return $flag;
    }
    public function signOut($email)
    {
        $table = "users";
        $main = new Mainn();
        $main->logout($table,$email);
    }
}

$logout = new UserLogout();
$result = $logout->getData();
$logout->signOut($result);
?>