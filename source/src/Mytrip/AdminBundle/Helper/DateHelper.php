<?php
namespace Mytrip\AdminBundle\Helper;

use Symfony\Component\Templating\Helper\Helper;

class DateHelper 
{
    protected $options;
    
    public function __construct(array $options)
    {
        $this->options = $options;
    }
    
    public function getName()
    {
        return 'date';
    }
    
    public function format($date, $detailed = false)
    {
		 if (!empty($date)) {
			 if ($detailed === true) {
				 return date('Y-m-d H:i:s',strtotime($date));
			 }else{
			 	return date('Y-m-d',strtotime($date));
			 }			
        }
        
        return null;
    }
	
	public function viewformat($date, $detailed = false)
    {
		 if (!empty($date)) {
			 if ($detailed === true) {
				 return date($this->getOption('detailed_format'),strtotime($date));
			 }else{
			 	return date($this->getOption('default_format'),strtotime($date));
			 }			
        }
        
        return null;
    }
    
	public function dateformat($detailed = false)
    {
		
		 if ($detailed === true) {
			 return str_replace(array('y','Y','m','d'),array('yy','yy','mm','dd'),$this->getOption('detailed_format'));
		 }else{
			return str_replace(array('y','Y','m','d'),array('yy','yy','mm','dd'),$this->getOption('default_format'));
		 }			
        
        
        return null;
    }
	
    public function getOption($name)
    {
        if (array_key_exists($name, $this->options)) {
            return $this->options[$name];
        }
        
        throw new Exception('Options does not exist');
    }
	
	public function array_column($input = null, $columnKey = null, $indexKey = null){
		// Using func_get_args() in order to check for proper number of
		// parameters and trigger errors exactly as the built-in array_column()
		// does in PHP 5.5.
		$argc = func_num_args();
		$params = func_get_args();
		if ($argc < 2) {
			trigger_error("array_column() expects at least 2 parameters, {$argc} given", E_USER_WARNING);
			return null;
		}
		if (!is_array($params[0])) {
			trigger_error('array_column() expects parameter 1 to be array, ' . gettype($params[0]) . ' given', E_USER_WARNING);
			return null;
		}
		if (!is_int($params[1])	&& !is_float($params[1]) && !is_string($params[1]) && $params[1] !== null && !(is_object($params[1]) && method_exists($params[1], '__toString'))) {
			trigger_error('array_column(): The column key should be either a string or an integer', E_USER_WARNING);
			return false;
		}
		if (isset($params[2]) && !is_int($params[2]) && !is_float($params[2]) && !is_string($params[2])	&& !(is_object($params[2]) && method_exists($params[2], '__toString'))) {
			trigger_error('array_column(): The index key should be either a string or an integer', E_USER_WARNING);
			return false;
		}
		$paramsInput = $params[0];
		$paramsColumnKey = ($params[1] !== null) ? (string) $params[1] : null;
		$paramsIndexKey = null;
		if (isset($params[2])) {
			if (is_float($params[2]) || is_int($params[2])) {
				$paramsIndexKey = (int) $params[2];
			} else {
				$paramsIndexKey = (string) $params[2];
			}
		}
		$resultArray = array();
		foreach ($paramsInput as $row) {
			$key = $value = null;
			$keySet = $valueSet = false;
			if ($paramsIndexKey !== null && array_key_exists($paramsIndexKey, $row)) {
				$keySet = true;
				$key = (string) $row[$paramsIndexKey];
			}
			if ($paramsColumnKey === null) {
				$valueSet = true;
				$value = $row;
			} elseif (is_array($row) && array_key_exists($paramsColumnKey, $row)) {
				$valueSet = true;
				$value = $row[$paramsColumnKey];
			}
			if ($valueSet) {
				if ($keySet) {
					$resultArray[$key] = $value;
				} else {
					$resultArray[] = $value;
				}
			}
		}
		return $resultArray;
	}
}


?>