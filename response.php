<?php

require "validation.php";
require "main.php";

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST"); //header used to insert data
header("Content-Type: application/json");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

class Response
{
    public function getData()
    {  
        $data = json_decode(file_get_contents("php://input"),true);
        $status = $data['status'];
        $error = $data['error'];
        $description = $data['description'];
        $parameter = array($status,$error,$description);

        if($parameter[0]<=1 && $parameter[0]>=2)
        {
            echo json_encode(array('Message'=>'Please Enter Status!','status'=>'409'));
            die();
        }
        if($parameter[1]=="")
        {
            echo json_encode(array('Message'=>'Please Enter error!','status'=>'409'));
            die();
        }
        if($parameter[2]=="")
        {
            echo json_encode(array('Message'=>'Please Enter description!','status'=>'409'));
            die();
        }
        else
        {
             return $parameter; 
        }
    }
    public function insertResponse($parameter)
    {
        $status = "'$parameter[0]'";
        $error = "'$parameter[1]'";
        $description = "'$parameter[2]'";

        $conn = new mysqli("localhost","root","","mail_service");
        $sql = "insert into responses(status,error,description) values($status,$error,$description)";
        echo"Inserted...";
        $result = $conn->query($sql);
    }
}

$response = new Response();
$result = $response->getData();
$response->insertResponse($result);
?>