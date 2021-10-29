<?php
require "vendor/stripe/stripe-php/init.php";
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
    function addId($em1,$em2)
    {
        $memail = "'$em1'";
        $uemail = "'$em2'";
        $conn = self::buildConnection();
        $sql1 = "select id from merchants where email=$memail";
        $res1 = $conn->query($sql1);
        $row = $res1->fetch_assoc();
        $id = $row["id"];
        $sql2 = "update users set merchant_id = $id where email=$uemail";
        $res2 = $conn->query($sql2);
        self::closeConnection($conn);
    }
    function addCardId($id)
    {
        $conn = self::buildConnection();
        $sql = "insert into merchants(card_id) values($id)";
        $conn->query($sql);
    }
    function insertAmount($amount,$em)
    {
        $email = "'$em'";
        $conn = self::buildConnection();
        $sql1 = "select c.id,c.credit from cards c join merchants m on c.id = m.merchant_id where m.email = $email";
        $data = $conn->query($sql1);
        $row = $data->fetch_assoc();
        $id = $row["c.id"];
        $credit = $row['c.credit'];
        $credit = $credit + $amount;
        $sql2 = "update table cards set credit = $credit where id = $id";
        $conn->query($sql2);
        self::closeConnection($conn);
    }
    function deduction($em)
    {
        $email = "'$em'";
        $amount = " 0.0489";
        $conn = self::buildConnection();
        $sql1 = "select c.id,c.credit from cards c join merchants m on c.id = m.merchant_id where m.email = $email";
        $data = $conn->query($sql1);
        echo $data;
        $row = $data->fetch_assoc();
        $id = $row["c.id"];
        $credit = $row['c.credit'];
        $credit = $credit - $amount;
        $sql2 = "update table cards set credit = $credit where id = $id";
        $conn->query($sql2);
        self::closeConnection($conn);
    }
    function insertMail($t,$subj,$bd,$head,$fr,$c,$bc)
    {
        $to = "'$t'";
        $subject = "'$subj'";
        $body = "'$bd'";
        $header = "'$head'";
        $from = "'$fr'";
        $cc = "'$c'";
        $bcc = "'$bc'";
        $conn = self::buildConnection();
        $sql1 = "select id from merchants where email = $from";
        $result1 = $conn->query($sql1);
        $row = $result1->fetch_assoc();
        $merchant_id = $row["id"];
        $response_id = rand(1,4);
        $sql2 = "insert into requests(mail_from,mail_to,mail_cc,mail_bcc,subject,body,merchant_id,response_id) values($from,$to,$cc,$bcc,$subject,$body,$merchant_id,$response_id)";
        $result2 = $conn->query($sql2);
    }
    function checkEmail($parameter)
    {
        $conn = self::buildConnection();
        $email = "'$parameter[0]'";
        $token = "'$parameter[1]'";
        $sql = "select * from merchants where email=$email AND token=$token";
        $result = $conn->query($sql);
        if($result->num_rows > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
        self::closeConnection($conn);
    }
    function logout($table,$em)
    {
        $email = "'$em'";
        $conn = self::buildConnection();
        $sql = "update $table set token = NULL, statuss = 0 where email = $email";
        $conn->query($sql);
        self::closeConnection($conn);
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
    function insertMerchant($parameter,$id)
    {
        $conn = self::buildConnection();
        //$id = self::getId();
        $namee = "'$parameter[0]'";
        $email = "'$parameter[1]'";
        $password = "'$parameter[2]'";
        $sql = "UPDATE merchants SET name = $namee, email= $email, merchant_password = $password WHERE card_id = $id";
        $conn->query($sql);
        self::closeConnection($conn);
    }
    function insertion($table,$parameter)
    {
        $sql;
        if($table=="admins"){$innerPara = "name,email";}
       // elseif($table=="merchants"){$innerPara = "name,email,merchant_password";}
        elseif($table=="cards")
        {
            //$innerPara = "card_number,credit,cvc_number,valid_from,valid_till";
            $card_number = "'$parameter[3]'";
            $credit = "'$parameter[4]'";
            $cvc_number = "'$parameter[5]'";
            $valid_from = "'$parameter[6]'";
            $valid_till = "'$parameter[7]'";
            $sql = "insert into $table(card_number,credit,cvc_number,valid_from,valid_till) values($card_number,$credit,$cvc_number,$valid_from,$valid_till)";
        }
        elseif($table=="users"){$innerPara = "name,email,user_password,Email_permission,List_view_permission,Payment_permission,Forget_password_permission,Login_permission";}
        elseif($table=="requests"){$innerPara = "mail_from,mail_to,mail_cc,mail_bcc,subject,body,merchant_id,response_id";}
        elseif($table=="responses"){$innerPara = "status,error,description";}
        else{}
        $imp = implode("','", $parameter);
        $val = "'".$imp."'";
        $conn = self::buildConnection();
        if($table=="merchants" || $table=="cards"){}
        else
        {
            $sql = "insert into $table($innerPara) values($val)";
        }
        $conn->query($sql);
        self::closeConnection($conn);
    }
    function showMerchant($table, $parameter)
    {
        $data = implode(",", $parameter);
        $conn = self::buildConnection();
        $sql = "select $data from $table";
        $conn->query($sql);
        self::closeConnection();
    }
    function fetchAll($table, $data)
    {
        $sql;
        $email = "'$data'";
        $conn = self::buildConnection();
        if($table=="admins")
        {
            $sql = "select * from cards c join merchants m on (c.id = m.card_id) where m.email = $email";            
        }
        elseif($table=="merchants")
        {
            $sql = "select * from cards c join merchants m on (c.id = m.card_id) where m.email = $email";
        }
        else{}
        $data = $conn->query($sql);
        $row = $data->fetch_assoc();
        echo $row;
        self::closeConnection($conn);
    }
    public function getStripeToke($data)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_URL => 'https://api.stripe.com/v1/tokens',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FRESH_CONNECT => true,
            CURLOPT_POSTFIELDS => http_build_query($data),
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer sk_test_51Jp7KZJG52ZsdW8uvuwa3l2tr7GXCLtqv6cPfpPDgx632BLFR6EVFeCR5shBK7mIWRLjVmGDcL82zi5J97ykV6FE00PIukYfPK',
                'Content-type: application/x-www-form-urlencoded',
            ]
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    public function charge($token,$amount)
    {
        $stripe = new \Stripe\StripeClient(
            'sk_test_51Jp7KZJG52ZsdW8uvuwa3l2tr7GXCLtqv6cPfpPDgx632BLFR6EVFeCR5shBK7mIWRLjVmGDcL82zi5J97ykV6FE00PIukYfPK'
        );
        $stripe->charges->create([
            'amount' => $amount,
            'currency' => 'usd',
            'source' => $token,
            'description' => 'balance top up',
        ]);

        return $stripe;
    }
}
?>