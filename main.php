<?php

require "jwt.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Mainn
{
    private function buildConnection()
    {
        $conn = new mysqli("localhost","root","","mail_service");
        if($conn->connect_error)
        {
            echo "Connection Error!";
            die();
        }
        else{return $conn;}
    } 
    private function closeConnection($conn)
    {
        $conn->close();
    }
    function checkExist($table,$para,$data)
    {
        $conn = self::buildConnection();
        $sql = "select * from ".$table ." WHERE ".$para."='{$data}'";
        $result = $conn->query($sql);
        self::closeConnection($conn);
        if($result->num_rows > 0)
        {
            return false;
        }
        else
        {
            return true;
        }
    }
    function checkLogin($table,$parameter)
    {
        $email = "email";
        $password;
        $em;
        $pass;
        $conn = self::buildConnection();
        if($table=="merchants")
        { 
            $password = "merchant_password";
            $em = $parameter[0];
            $pass = $parameter[1];
        }
        elseif($table=="users")
        {
            $password = "user_password";
            $em = $parameter[0];
            $pass = $parameter[1];
        }
        $sql = "SELECT * FROM $table WHERE ".$password."= '{$pass}' AND ".$email."='{$em}'";
        $result = $conn->query($sql);
        self::closeConnection($conn);
        if($result->num_rows > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    function insertLogin($table,$parr)
    {
        $sql;
        $conn = self::buildConnection();   
        $jwt = new JWT($parr);
        $tok = $jwt->Generate_jwt();
        $token = "'$tok'";
        $para = "'$parr'";
        if($table=="merchants")
        {
            $sql = "UPDATE merchants SET statuss = 1, token = $token WHERE email = $para";
        }
        elseif($table=="users")
        {
            $sql = "UPDATE users SET statuss = 1, token = $token WHERE email = $para";
        }
        $conn->query($sql);
        self::closeConnection($conn);
    }
    function insertion($table,$parameter)
    {
        if($table=="admins"){$innerPara = "name,email";}
        elseif($table=="merchants"){$innerPara = "name,email,merchant_password";}
        elseif($table=="cards"){$innerPara = "card_number,credit,cvc_number,valid_from,valid_till";}
        elseif($table=="users"){$innerPara = "name,email,user_password";}
        elseif($table=="requests"){$innerPara = "mail_from,mail_to,mail_cc,mail_bcc,subject,body,merchant_id,response_id";}
        elseif($table=="responses"){$innerPara = "status,error,description";}
        else{}
        $imp = implode("','", $parameter);
        $val = "'".$imp."'";
        $conn = self::buildConnection();   
        $sql = "insert into $table($innerPara) values($val)";
        $conn->query($sql);
        self::closeConnection($conn);
    }
    function show($table, $parameter)
    {
        $data = implode(",", $parameter);
        $conn = self::buildConnection();
        $sql = "select $data from $table";
        $conn->query($sql);
        self::closeConnection();
    }
}
?>