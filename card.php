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

    public function __construct()
    {
        $card_number="";
        $credit="";
        $cvc_number="";
        $valid_from="";
        $valid_till="";
    }
    public function getData()
    {
        $data = json_decode(file_get_contents("php://input"),true);
        $card_number = $data['card_number'];
        $credit = "50";
        $cvc_number = $data['cvc_number'];
        $valid_from = $data['valid_from'];
        $valid_till = $data['valid_till'];
        $parameter = array($card_number,$credit,$cvc_number,$valid_from,$valid_till);

        if($parameter[0]=="")
        {
            echo json_encode(array('Message'=>'Please Enter card number!','status'=>'409'));
            die();
        }
        if($parameter[2]=="")
        {
            echo json_encode(array('Message'=>'Please Enter 3 digit CVC number!','status'=>'409'));
            die();
        }
        if($parameter[3]=="")
        {
            echo json_encode(array('Message'=>'Please Enter card valid from!','status'=>'409'));
            die();
        }
        if($parameter[4]=="")
        {
            echo json_encode(array('Message'=>'Please Enter card validity!','status'=>'409'));
            die();
        }
        else
        {
             return $parameter; 
        }
    }
    public function parametersValidation($parameter)
    {
        $flag = true;
        $validate = new Validate();                                                                                                           
        if(!$validate->card_validate($parameter[0]))  { $flag=false; }                                         
        if(!$validate->cvc_validate($parameter[2]))  { $flag=false; }
        if(!$validate->validfrom_validate($parameter[3]))  { $flag=false; }
        if(!$validate->validtill_validate($parameter[4]))  { $flag=false; }
        return $flag;
    }
    public function cardInsert($parameter)
    {
        $table = "cards";
        $cardno = "card_number";
        $mainn = new Mainn();
        $check = $mainn->checkExist($table,$cardno,$parameter[0]);
        if($check==true)
        {  
            $mainn->insertion($table, $parameter);
            echo"Card data is inserted";
        }   
        else
        {
            echo"Card number is already registered!";
        }
    }
}

$card = new Card();
$result = $card->getData();
$val = $card->parametersValidation($result);
if($val==true)
{
    $card->cardInsert($result);
}
else
{
    echo"Validation failed";
}
?>