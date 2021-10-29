<?php
    class Validate      //Create validation class to check all the input in correct methord :
    {
        /**
         *email_validate function get one parmeter and check email pattern if pattern match return true else false
         */
        public function email_validate($email)                                            
        {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo"Email validation failed";
                return false; 
            }
            else{
                return true;
            } 
        }
        /**
         * password_validate function get one parmeter and check password pattern if pattern match return true else false
         */
        public function password_validate($password)        
        {
            $password_pattern='/^(?=.*[A-Z]).{8,20}$/';     //password length > 8 and also 1 uppercase charecter
            if(!preg_match($password_pattern, $password)){  //check patteren match
                echo"Password validation failed";
                return false;
            }
            else{
                return true;
            } 
        }
    
        public function name_validate($name)
        {
            $name_pattern="/^[a-zA-Z ]*$/";     //Not Accept Special character and digit
            if(!preg_match($name_pattern, $name)){      //check patteren match
                echo"Name validation failed";
                return false;
            }
            else{
                return true;
            } 
        }
        public function card_validate($card)
        {
            $card_pattern="/^([0-9]{16})$/";
            if(!preg_match($card_pattern, $card)){      //check patteren match
                return false;
            }
            else{
                return true;
            } 
        }
        /**
         * dep_validate function get one parmeter and Depement password pattern if pattern match return true else false
         */
        public function cvc_validate($cvc)
        {
            $cvc_pattern="/^[0-9]{3}$/";  //enter only number and also alphabet and number not start number and end alphabet
            if(!preg_match($cvc_pattern, $cvc)){     //check patteren match
                return false;
            }
            else{
                return true;
            }
        }
        public function validfrom_validate($valid_from)
        {
            $valid_from_pattern="/^([0-9]{2})[-]([0-9]{2})$/";
            if(!preg_match($valid_from_pattern, $valid_from)){
                return false;
            }
            else{
                return true;
            }
        }
        public function validtill_validate($valid_till)
        {
            $valid_till_pattern="/^([0-9]{2})[-]([0-9]{2})$/";
            if(!preg_match($valid_till_pattern, $valid_till)){
                return false;
            }
            else{
                return true;
            }
        }
    }
?>
