<?php

require "validation.php";
require "main.php";

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

class Request
{
    public function getData()
    {  
        $data = json_decode(file_get_contents("php://input"),true);
        $email = $data['email'];
        $token = $data['token'];
        $to = $data['to'];
        $cc = $data['cc'];
        $bcc = $data['bcc'];
        $subject = $data['subject'];
        $body = $data['body'];
        $headers = $data['headers'];
        $parameter = array($email,$token,$to,$cc,$bcc,$subject,$body,$headers);

        if($parameter[0]=="")
        {
            echo json_encode(array('Message'=>'Please Enter Email!','status'=>'409'));
            die();
        }
        if($parameter[1]=="")
        {
            echo json_encode(array('Message'=>'Please Enter token for this email!','status'=>'409'));
            die();
        }
        if($parameter[2]=="")
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
        if(!$validate->email_validate($parameter[0]))  { $flag=false; }
        return $flag;
    }
    public function checkSignin($parameter)
    {
        $email = "'$parameter[0]'";
        $token = "'$parameter[1]'";
        $conn = new mysqli("localhost","root","","mail_service");
        if($conn->connect_error)
        {
            echo "Connection Error!";
            die();
        }
        $sql = "select * from merchants where email = $email AND token = $token";
        $result = $conn->query($sql);
        if($result->num_rows > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    public function mailing($parameter)
    {   
        $email = $parameter[0];
        $to = $parameter[2];
        $cc = $parameter[3];
        $bcc = $parameter[4];
        $subject = $parameter[5];
        $body = $parameter[6];
        $header = $parameter[7];

        if (mail($to, $subject, $body, $header)) 
        {
            echo "Email successfully sent to $to";
            $main = new Mainn();
            $main->insertMail($to,$subject,$body,$header,$email,$cc,$bcc);
            $main->deduction($email);
        }
        else 
        {
            echo "Email sending failed...";
        }
    }
}

$request = new Request();
$result = $request->getData();
$check = $request->checkSignin($result);
if($check==false)
{
    echo"Enter correct email and token!";
    die();
}
$val = $request->parametersValidation($result);
if($val==true)
{
    $request->mailing($result);
}
else
{
    echo"Validation failed";
}
?>