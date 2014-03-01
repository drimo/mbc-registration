<?php

/*
// FormValidator.class.inc
// class to perform form validation
// James Hayes
*/

class FormValidator
{
	//
	// private variables
	// 
	
	var $_errorList;

	//
	// methods (private)
	// 
	
	// function to get the value of a variable (field)
	function _getValue($field)
	{
		global ${$field};
		return ${$field};
	}

	//
	// methods (public)
	// 

	// constructor
	// reset error list
	function FormValidator()
	{
		$this->resetErrorList();
	}
	
	// check whether input is empty
	function isEmpty($field, $msg)
	{
		$value = $this->_getValue($field);
		if (trim($value) == "")
		{
			$this->_errorList[] = array("field" => $field, "value" => $value, "msg" => $msg);
			return false;
		}
		else
		{
			return true;
		}
	}

	// check whether input is a string
	function isString($field, $msg)
	{
		$value = $this->_getValue($field);
		if(!is_string($value))
		{
			$this->_errorList[] = array("field" => $field, "value" => $value, "msg" => $msg);
			return false;
		}
		else
		{
			return true;
		}
	}

	// check whether input is a number
	function isNumber($field, $msg)
	{
		$value = $this->_getValue($field);
		if(!is_numeric($value))
		{
			$this->_errorList[] = array("field" => $field, "value" => $value, "msg" => $msg);
			return false;
		}
		else
		{
			return true;
		}
	}

	// check whether input is an integer
	function isInteger($field, $msg)
	{
		$value = $this->_getValue($field);
		if(!is_integer($value))
		{
			$this->_errorList[] = array("field" => $field, "value" => $value, "msg" => $msg);
			return false;
		}
		else
		{
			return true;
		}
	}

	// check whether input is a float
	function isFloat($field, $msg)
	{
		$value = $this->_getValue($field);
		if(!is_float($value))
		{
			$this->_errorList[] = array("field" => $field, "value" => $value, "msg" => $msg);
			return false;
		}
		else
		{
			return true;
		}
	}
	
	// check whether input is alphabetic
	function isAlpha($field, $msg)
	{
		$value = $this->_getValue($field);
		$pattern = "/^[a-zA-Z]+$/";
		if(preg_match($pattern, $value))
		{
			return true;
		}
		else
		{
			$this->_errorList[] = array("field" => $field, "value" => $value, "msg" => $msg);
			return false;
		}
	}

	// check whether input is within a valid numeric range
	function isWithinRange($field, $msg, $min, $max)
	{
		$value = $this->_getValue($field);
		if(!is_numeric($value) || $value < $min || $value > $max)
		{
			$this->_errorList[] = array("field" => $field, "value" => $value, "msg" => $msg);
			return false;
		}
		else
		{
			return true;
		}
	}
	
	// check whether input is a valid email address
	function isEmailAddress($field, $msg)
	{
		$value = $this->_getValue($field);
		$pattern = "/^([a-zA-Z0-9])+([\.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)+/";
		if(preg_match($pattern, $value))
		{
			return true;
		}
		else
		{
			$this->_errorList[] = array("field" => $field, "value" => $value, "msg" => $msg);
			return false;
		}
	}

	// check whether input is a valid social security number
	function isSSN($field, $msg)
	{
		$value = $this->_getValue($field);
		$pattern = "/^(\d{3})-(\d{2})-(\d{3})/";
		if(preg_match($pattern, $value))
		{
			return true;
		}
		else
		{
			$this->_errorList[] = array("field" => $field, "value" => $value, "msg" => $msg);
			return false;
		}
	}

	// check whether input is a valid phone number
	function isPhone($field, $msg)
	{
		$value = $this->_getValue($field);
		$pattern = "/^\(\d{3}\)(-|\s)\d{3}-\d{4}/";
		if(preg_match($pattern, $value))
		{
			return true;
		}
		else
		{
			$this->_errorList[] = array("field" => $field, "value" => $value, "msg" => $msg);
			return false;
		}
	}


	function iszip($field,$msg)
	{
		$value = $this->_getValue($field);
		$pattern = "/^(\d{5})(-\d{4})?/";
		if(preg_match($pattern, $value))
		{
			return true;
		}
		else
		{
			$this->_errorList[] = array("field" => $field, "value" => $value, "msg" => $msg);
			return false;
		}
	}

	
	// return the current list of errors
	function getErrorList()
	{
		return $this->_errorList;
	}
	
	// check whether any errors have occurred in validation
	// returns Boolean
	function isError()
	{
		if (sizeof($this->_errorList) > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	// reset the error list
	function resetErrorList()
	{
		$this->_errorList = array();
	}
	
// end 
}

?>
