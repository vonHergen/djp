<?php

/**************************************************************************
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
***************************************************************************/

/**
 * Die "lw_security" Klasse ist f�r Security zust�ndig ...
 * 
 * @package  Framework
 * @author   Andreas Eckhoff
 * @since    PHP 5.0
 */
class lw_security
{
	public static function checkSession()
	{
		$unique = $_SERVER['REMOTE_ADDR'];
		$check  = md5($unique);
		if ($_SESSION['lw_security']['checkSession'] != $check) {
			return false;
		}
		return true;
	}
	
	public static function setSessionSecurity()
	{
		$unique = $_SERVER['REMOTE_ADDR'];
		$check  = md5($unique);
		$_SESSION['lw_security']['checkSession'] = $check;
	}
	
	public static function getHashForValue($value)
	{
		if (strlen($_SESSION['lw_security']['random_seed']) < 40) {
			$_SESSION['lw_security']['random_seed'] = sha1(uniqid(md5(microtime()),true));
		}
		$seed = $_SESSION['lw_security']['random_seed'];
		return hash('sha256',$seed.$value); 
	}
}
