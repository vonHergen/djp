<?php

/**
 * The inputs of the html formular from the "ProfileForm.tpl.phtml"
 * file will be validated.
 * 
 * @author Michael Mandt <michael.mandt@logic-works.de>
 */

namespace DJP\Model\Backend\ConfUser\Specification;

define("REQUIRED", "1");    # array( 1 => array( "error" => 1, "options" => "" ));
define("MAXLENGTH", "2");   # array( 2 => array( "error" => 1, "options" => array( "maxlength" => $maxlength, "actuallength" => $strlen ) ));
define("EMAIL", "3");       # array( 5 => array( "error" => 1, "options" => "" ));
define("DIGITFIELD", "4");  # array( 6 => array( "error" => 1, "options" => "" ));
define("PWREPEAT", "5");   # array( 10 => array( "error" => 1, "options" => "" ));
define("MINLENGTH", "6"); # array( 10 => array( "error" => 1, "options" => "" ));
define("EMAILEXISTING", "7"); # array( 10 => array( "error" => 1, "options" => "" ));

class isValid
{

    private $allowedKeys;
    private $array;
    private $errors;
    private $loggedInEmail;
    private $add = false;

    /**
     * It's important to set the $this->allowedKeys with
     * the elements of the formular, because this keys are
     * functionnames for the validation of the specific element.
     */
    public function __construct($oldEmail = false)
    {
        $this->oldEmail = $oldEmail;
        $this->errors = array();

        $this->allowedKeys = array(
            "vorname",
            "nachname",
            "email",
            "role",
            "password",
            "password_repeat",
            "education"
        );
    }

    /**
     * The values of the submited formular will be set.
     * @param array $array
     */
    public function setValues($array)
    {
        $this->array = $array;
    }

    public function setAdd()
    {
        $this->add = true;
    }

    /**
     * Each value will be passed to his validation function. 
     * @bool boolean
     */
    public function validate()
    {
        $valid = true;
        foreach ($this->allowedKeys as $key) {
            $function = $key . "Validate";
            $result = $this->$function($this->array[$key]);
            if ($result == false) {
                $valid = false;
            }
        }
        return $valid;
    }

    /**
     * If an error occured then this error will be set to this error array.
     * @param string $key
     * @param int $number
     * @param array $array
     */
    private function addError($key, $number, $array = false)
    {
        $this->errors[$key][$number]['error'] = 1;
        $this->errors[$key][$number]['options'] = $array;
    }

    /**
     * All error will be returned.
     * @array type
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * One specific error will be returned.
     * @param string $key
     * @return array
     */
    public function getErrorsByKey($key)
    {
        return $this->errors[$key];
    }

    private function roleValidate($value)
    {
        if (is_int(intval($value))) {
            return true;
        }
        else {
            $this->addError("role", DIGITFIELD);
            return false;
        }
    }
    
    private function educationValidate($value)
    {
        if (is_int(intval($value))) {
            return true;
        }
        else {
            $this->addError("education", DIGITFIELD);
            return false;
        }
    }

    private function vornameValidate($value)
    {
        return $this->defaultValidation("vorname", $value, 255, true);
    }

    private function nachnameValidate($value)
    {
        return $this->defaultValidation("nachname", $value, 255, true);
    }

    private function passwordValidate($value)
    {
        $bool = true;
        if ($this->add) {
            $bool = $this->defaultValidation("password", $value, 255, true);
        }
        else {
            $bool = $this->defaultValidation("password", $value, 255);
        }

        if ($value != "") {
            if (strlen($value) < 5) {
                $this->addError("password", MINLENGTH);
                $bool = false;
            }
        }

        if ($value !== $this->array["password_repeat"]) {
            $this->addError("password", PWREPEAT);
            $bool = false;
        }

        if (!$bool) {
            return false;
        }
        return true;
    }

    private function password_repeatValidate($value)
    {
        return true;
    }

    private function emailValidate($value)
    {
        $bool = true;

        $bool = $this->requiredValidation("email", $value);

        if (filter_var($value, FILTER_VALIDATE_EMAIL) == false) {
            $this->addError("email", EMAIL);
            $bool = false;
        }

        if ($this->add) {
            if ($value != "") {
                $qH = new \DJP\Model\Backend\DataHandler\QueryHandler();
                $result = $qH->getUserByEmail($value);
                if (!empty($result)) {
                    $this->addError("email", EMAILEXISTING);
                    $bool = false;
                }
            }
        }
        else {
            if ($value != $this->oldEmail) {
                $qH = new \DJP\Model\Backend\DataHandler\QueryHandler();
                $result = $qH->getUserByEmail($value);
                if (!empty($result)) {
                    $this->addError("email", EMAILEXISTING);
                    $bool = false;
                }
            }
        }

        if ($bool == false) {
            return false;
        }
        return true;
    }

    private function defaultValidation($key, $value, $length, $required = false)
    {
        $bool = true;

        if ($required === true) {
            $bool = $this->requiredValidation($key, $value);
        }

        if (strlen($value) > $length) {
            $this->addError($key, MAXLENGTH, array("maxlength" => $length, "actuallength" => strlen($value)));
            $bool = false;
        }

        if ($bool == false) {
            return false;
        }
        return true;
    }

    private function requiredValidation($key, $value)
    {
        if ($value == "") {
            $this->addError($key, REQUIRED);
            return false;
        }
        return true;
    }

}