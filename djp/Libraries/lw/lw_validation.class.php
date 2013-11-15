<?php

/* * ************************************************************************
 *  Copyright notice
 *
 *  Copyright 1998-2009 Logic Works GmbH
 *
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *  you may not use this file except in compliance with the License.
 *  You may obtain a copy of the License at
 *
 *  http://www.apache.org/licenses/LICENSE-2.0
 *  
 *  Unless required by applicable law or agreed to in writing, software
 *  distributed under the License is distributed on an "AS IS" BASIS,
 *  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *  See the License for the specific language governing permissions and
 *  limitations under the License.
 *  
 * ************************************************************************* */


class lw_validation 
{

    static function hasMaxlength($value, $options) 
    {
        if (strlen(trim($value)) > intval($options['value']))
            return false;
        return true;
    }

    static function hasMinlength($value, $options) 
    {
        if (strlen(trim($value)) < intval($options['value']))
            return false;
        return true;
    }

    static function isRequired($value) 
    {
        if (strlen(trim($value)) < 1)
            return false;
        return true;
    }

    static function isEmail($value) 
    {
        if ($value == "") {
            return True;
        } elseif (filter_var($value, FILTER_VALIDATE_EMAIL) == TRUE) {
            return TRUE;
        }
        return FALSE;
        #if (!$value) return true;
        #if (eregi("^[a-z0-9\._-]+@+[a-z0-9\._-]+\.+[a-z]{2,4}$", $value)) return true;
        #return false;
    }

    static function isDate($value, $options = 'de') 
    {
        if (!$value)
            return true;
        if ($options == false)
            $options = 'de';
        if ($options == 'de') {
            if (eregi("^([0-9]{2})+\.+([0-9]{2})+\.+([0-9]{4})$", $value)) {
                return true;
            }
        }
        return false;
    }

    static function minDate($value, $date, $options = false) 
    {
        if (!$value)
            return true;
        if (!self::isDate($date))
            return true;
        if (!self::isDate($value))
            return true;

        $d1 = substr($date, 0, 2);
        $m1 = substr($date, 3, 2);
        $y1 = substr($date, 6, 4);
        $date1 = $y1 . $m1 . $d1;

        $d2 = substr($value, 0, 2);
        $m2 = substr($value, 3, 2);
        $y2 = substr($value, 6, 4);
        $date2 = $y2 . $m2 . $d2;

        if ($date2 >= $date1)
            return true;
        return false;
    }

    static function maxDate($value, $date, $options = false) 
    {
        if (!$value)
            return true;
        if (!self::isDate($date))
            return true;
        if (!self::isDate($value))
            return true;

        $d1 = substr($date, 0, 2);
        $m1 = substr($date, 3, 2);
        $y1 = substr($date, 6, 4);
        $date1 = $y1 . $m1 . $d1;

        $d2 = substr($value, 0, 2);
        $m2 = substr($value, 3, 2);
        $y2 = substr($value, 6, 4);
        $date2 = $y2 . $m2 . $d2;
        if ($date2 <= $date1)
            return true;
        return false;
    }

    static function checkFileExtensions($value, $extensions) 
    {
        $value = trim($value);
        if (strlen($value) == 0)
            return true;
        $extensions = str_replace(';', ',', $extensions);
        $extensions = strtolower($extensions);
        $exts = explode(',', $extensions);

        $filename = strtolower($value);
        $ext = substr($filename, strrpos($filename, '.') + 1, strlen($filename));

        if (strlen($ext) == 0)
            return false;
        if (in_array($ext, $exts))
            return true;
        return false;
    }

    static function isAlnum($value) 
    {
        $test = preg_replace('/[^a-zA-Z0-9\s]/', '', (string) $value);
        if (strlen(trim($value)) > 0 && ($value != $test))
            return false;
        return true;
    }

    static function isBetween($value, $options) 
    {
        if (strlen(trim($value)) > 0 && ($value < strval($options["value1"]) || $value > strval($options["value2"])))
            return false;
        return true;
    }

    static function isDigits($value) 
    {
        $test = preg_replace('/[^0-9]/', '', (string) $value);
        if (strlen(trim($value)) > 0 && ($value != $test))
            return false;
        return true;
    }

    static function isGreaterThan($value, $options) 
    {
        if (strlen(trim($value)) > 0 && ($value < strval($options["value"])))
            return false;
        return true;
    }

    static function isLessThan($value, $options) 
    {
        if (strlen(trim($value)) > 0 && ($value > strval($options["value"])))
            return false;
        return true;
    }

    static function isInt($value) 
    {
        if (strlen(trim($value)) > 0 && (!is_int($value)))
            return false;
        return true;
    }

    static function isRegex($value, $options) 
    {
        if (strlen(trim($value)) > 0 && (!eregi(strval($options["value"]), $value)))
            return false;
        return true;
    }

    static function isFiletype($value, $options) 
    {
        if (strlen(trim($value)) > 0) {
            $ext = strtolower(lw_io::getFileExtension($value));
            if (!strstr($options["value"], ":" . $ext . ":"))
                return false;
            return true;
        }
        return true;
    }

    static function isImage($value, $options) 
    {
        if (strlen(trim($value)) > 0) {
            $ext = strtolower(lw_io::getFileExtension($value));
            if (!strstr(':jpg:jpeg:png:gif:', ":" . $ext . ":"))
                return false;
            return true;
        }
        return true;
    }

    static function isCustom($value, $options) 
    {
        $function = strval($options["function"]);
        if (method_exists($options["delegate"], $function)) {
            if (!call_user_func(array($options["delegate"], $function), $value))
                return false;
            return true;
        }
    }
/*
 * bsp zu  isCustom()
 * 
class object {
    
    function method()
    {}
}    
    
$object = new object();    

$object->method();

   
call_user_func(array($object, 'method'));
*/    
}
?>