<?php

require "validation.php";
require "main.php";

header('Content-Type:application/json');
header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Methods:POST');
header('Access-Control-Allow-Headers:Access-Control-Allow-Headers,Content_Type,Access-Control-Allow-Methods,Authorization,X-Requested-With');

class MerchantSignup extends Mainn
{
    private $data;
    private $name;
    private $email;
    private $password;
    private $parameter;

    public function __construct()
    {
        $name="";
        $email="";
        $password="";
    }
    public function getData()
    {
        $data = json_decode(file_get_contents("php://input"),true);
        $name = $data['name'];
        $email = $data['email'];
        $password = $data['password'];
        $parameter = array($name,$email,$password);

        if($parameter[0]=="" || $parameter[1]=="" || $parameter[2]=="")
        {
            echo json_encode(array('Message'=>'Please Fill All the Fields','status'=>'409'));
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
        if(!$validate->name_validate($parameter[0]))  { $flag=false; }   // validating name                                                            
        if(!$validate->email_validate($parameter[1]))  { $flag=false; }   // validating email                                           
        if(!$validate->password_validate($parameter[2]))  { $flag=false; }  // validating password
        return $flag;
    }
    public function insert($parameter)
    {
        $table="merchants";
        $mainn = new Mainn();
        $check = $mainn->checkExist($table,$email,$parameter[1]);
        if($check==true)
        {  
            $mainn->insertion($table, $parameter);
            echo"Signup completed";
        }   
        else
        {
            echo"Email is already registered!";
        }
    }
}
    $signup = new MerchantSignup();
    $result = $signup->getData();
    $res = $signup->parametersValidation($result);
    if($res==true)
    {
        $signup->insert($result);
    }
    else
    {
        echo"Validation failed";
    }
?>