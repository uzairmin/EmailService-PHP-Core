<?php

require "validation.php";
require "main.php";

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST"); //header used to insert data
header("Content-Type: application/json");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

class Card
{
    private $card_number;
    private $credit;
    private $cvc_number;
    private $valid_from;
    private $valid_till;

    public function cardInsert($parameter)
    {
        $table = "cards";
        $cardno = "card_number";
        $mainn = new Mainn();
        $check = $mainn->checkExist($table,$cardno,$parameter[3]);
        if($check==true)
        {  
            $mainn->insertion($table, $parameter);
            echo"Card data is inserted";
        }   
        else
        {
            echo"Card number is already registered!";
            die();
        }
    }
    public function getId($card_number)
    {
        $conn = new mysqli("localhost","root","","mail_service");
        if($conn->connect_error)
        {
            echo "Connection Error!";
            die();
        }
        $card_no = "'$card_number'";
        $sql = "select id from cards where card_number = $card_no";
        $idd = $conn->query($sql);
        $row = $idd->fetch_assoc();
        $id = $row["id"];
        return $id;
    }
}
?>