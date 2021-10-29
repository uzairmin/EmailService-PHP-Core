<?php

require "card.php";

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
        $card_number = $data['card_number'];
        $credit = "50";
        $cvc_number = $data['cvc_number'];
        $valid_from = $data['valid_from'];
        $valid_till = $data['valid_till'];
        $parameter = array($name,$email,$password,$card_number,$credit,$cvc_number,$valid_from,$valid_till);

        if($parameter[0]=="" || $parameter[1]=="" || $parameter[2]=="")
        {
            echo json_encode(array('Message'=>'Please Fill All the Fields','status'=>'409'));
            die();
        }
        if($parameter[3]=="")
        {
            echo json_encode(array('Message'=>'Please Enter card number!','status'=>'409'));
            die();
        }
        if($parameter[5]=="")
        {
            echo json_encode(array('Message'=>'Please Enter 3 digit CVC number!','status'=>'409'));
            die();
        }
        if($parameter[6]=="")
        {
            echo json_encode(array('Message'=>'Please Enter card valid from!','status'=>'409'));
            die();
        }
        if($parameter[7]=="")
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
        $flag=true;
        $validate = new Validate();             
        
        //$name,$email,$password,$card_number,$credit,$cvc_number,$valid_from,$valid_till

        if(!$validate->name_validate($parameter[0]))  { $flag=false; }   // validating name                                                            
        if(!$validate->email_validate($parameter[1]))  { $flag=false; }   // validating email                                           
        if(!$validate->password_validate($parameter[2]))  { $flag=false; }  // validating password
        if(!$validate->card_validate($parameter[3]))  { $flag=false; }                                         
        if(!$validate->cvc_validate($parameter[5]))  { $flag=false; }
        if(!$validate->validfrom_validate($parameter[6]))  { $flag=false; }
        if(!$validate->validtill_validate($parameter[7]))  { $flag=false; }
        return $flag;
    }
    public function insert($parameter)
    {
        $email = "email";
        $card = new Card();
        $card->cardInsert($parameter);
        $id = $card->getId($parameter[3]);
        $table="merchants";
        $mainn = new Mainn();
        $mainn->addCardId($id);
        $check = $mainn->checkExist($table,$email,$parameter[1]);
        if($check==true)
        {  
            $mainn->insertMerchant($parameter,$id);
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
        echo"Validation faileddd";
    }
?>