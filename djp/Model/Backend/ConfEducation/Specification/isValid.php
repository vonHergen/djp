<?php

/**
 * The inputs of the html formular from the "ProfileForm.tpl.phtml"
 * file will be validated.
 * 
 * @author Michael Mandt <michael.mandt@logic-works.de>
 */

namespace DJP\Model\Backend\ConfEducation\Specification;

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
    private $add = false;

    /**
     * It's important to set the $this->allowedKeys with
     * the elements of the formular, because this keys are
     * functionnames for the validation of the specific element.
     */
    public function __construct()
    {
        $this->errors = array();

        $this->allowedKeys = array(
            "name",
            "beschreibung"
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

	/**
	* Gueltigkeit des Wertes "Name" ueberpruefen
	**/
    private function nameValidate($value)
    {
        return $this->defaultValidation("name", $value, 255, true);
    }
    
	/**
	* Gueltigkeit des Wertes "Beschreibung" ueberpruefen
	**/
    private function beschreibungValidate($value)
    {
        return $this->defaultValidation("beschreibung", $value, 3000, true);
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