<?php

require "main.php";
require "validation.php";

header('Content-Type:application/json');
header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Methods:POST');
header('Access-Control-Allow-Headers:Access-Control-Allow-Headers,Content_Type,Access-Control-Allow-Methods,Authorization,X-Requested-With');
class AddUser
{   
    private $parameter;
    public function get_data()
    {
        $data=json_decode(file_get_contents("php://input"),true);
        $merchant_email = $data['merchant_email'];
        $token = $data['token'];
        $name = $data['name'];
        $user_email = $data['user_email'];
        $password=$data['password'];
        $email_permission=$data['email_permission'];
        $list_view_permission=$data['list_view_permission'];
        $payment_permission=$data['payment_permission'];
        $forget_password_permission=$data['forget_password_permission'];
        $login_permission=$data['login_permission'];
        $email_permission=$data['email_permission'];
        $parameter = array($merchant_email,$token,$name,$user_email,$password,$email_permission,$list_view_permission,$payment_permission,$forget_password_permission,$login_permission,$email_permission);
        return $parameter;
    }
    public function parametersValidation($parameter)
    {
       $flag=true;
       $validate= new Validate();                                                  
       if(!$validate->email_validate($parameter[0]))  { $flag=false; }
       if(!$validate->email_validate($parameter[3]))  { $flag=false; }                                                   
       if(!$validate->name_validate($parameter[2]))  { $flag=false; }
       if(!$validate->password_validate($parameter[4]))  { $flag=false; }
       return $flag;
    }
    public function check_empty($parameter)
    {
        if((empty($parameter[0])) || (empty($parameter[3])) || (empty($parameter[2])) || (empty($parameter[4])))
        {
            echo json_encode(array('Message'=>'Enter into the fields :','status'=>false));
        }
        else
        {
            self::insert_in($parameter);
        }
    }

    public function insert_in($parameter)
    {
        $main= new Mainn();
        if(($parameter))
        { 
            $main->insertion("users",$parameter);
            echo json_encode(array('Message'=>'Updated Successfully :','status'=>true));
        }
        else
        {
            echo json_encode(array('Message'=>'Please Enter valid Data :','status'=>false));
        }
    }
}
$Add= new AddUser();
$vali = $Add->get_data();
if($vali==true)
{
    $Add->check_empty($vali);
}
die();
?>