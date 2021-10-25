<?php

require "validation.php";
require "main.php";

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST"); //header used to insert data
header("Content-Type: application/json");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

class UserLogin
{
    private $email;
    private $password;
    private $parameter;
    public function __construct()
    {
        $email="";
        $password="";
    }
    public function getData()
    {
        
        $data = json_decode(file_get_contents("php://input"),true);
        $email = $data['email'];
        $password = $data['password'];
        $parameter = array($email,$password);

        if($parameter[0]=="")
        {
            echo json_encode(array('Message'=>'Please Enter email!','status'=>'409'));
            die();
        }
        if($parameter[1]=="")
        {
            echo json_encode(array('Message'=>'Please Enter password!','status'=>'409'));
            die();
        }
        else
        {
             return $parameter; 
        }
    }
    public function parametersValidation($parameter)
    {
        $flag=true;
        $validate = new Validate();                                                                                                           
        if(!$validate->email_validate($parameter[0]))  { $flag=false; }   // validating email                                           
        if(!$validate->password_validate($parameter[1]))  { $flag=false; }  // validating password
        return $flag;
    }
    public function signIn($parameter)
    {
        $table = "users";
        $email = $parameter[0];
        $main = new Mainn();
        $checking = $main->checkLogin($table,$parameter);
        if($checking==true)
        {
            $main->insertLogin($email);
            echo"Logged In successfully...";
        }
        else
        {
            echo "No user found!";
        }
    }
}

$login = new UserLogin();
$result = $login->getData();
$val = $login->parametersValidation($result);
if($val==true)
{
    $login->signIn($result);
}
else
{
    echo"Validation failed";
}
?>