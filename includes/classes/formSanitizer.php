<?php 
    class formSanitizer
    {
        public static function sanitizeFormName($inputText){
            $inputText = strip_tags($inputText);
            $inputText = trim($inputText);
            $inputText = strtolower($inputText);
            $separetedInput = explode(" ",$inputText);
            for ($i=0; $i < count($separetedInput); $i++) { 
                $separetedInput[$i] = ucfirst($separetedInput[$i]);
            }
            $inputText=implode(" ",$separetedInput);
            return $inputText;
        }
        public static function sanitizeFormUsername($inputText){
            $inputText = strip_tags($inputText);
            $inputText = str_replace(" ","",$inputText);
            return $inputText;
        }
        public static function sanitizeFormPassword($inputText){
            $inputText = strip_tags($inputText);
            return $inputText;
        }
        public static function sanitizeFormEmail($inputText){
            $inputText = strip_tags($inputText);
            $inputText = str_replace(" ","",$inputText);
            return $inputText;
        }
    }
    
?>