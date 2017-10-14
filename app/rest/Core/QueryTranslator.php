<?php
/**
 * Created by PhpStorm.
 * User: renne
 * Date: 23.05.16
 * Time: 16:11
 */

namespace LwRest\Core;

class QueryTranslator{

		private $queryString;
		private $error;
		const FLD_START = '-=FldStart=-';
		const FLD_END = '-=FldEnd=-';

		private $orderString;
		private $filterString;
		private $limitString;

		private $fieldTranslation;
		private $orderCommandTranslation;


		/**
		 *  {} 				// Filterangaben
		 * 	(a=1) | (a=2)   // a==1 or 	a==2
		 *  (a=1) ; (b=3)	// a==1 and b==3
		 *   =,>,<,!		// gleich, größer, kleiner, nicht
		 * 	^*test*			// like %test%
		 * 	!^*test*		// not like %test%
		 *  "xxx"			// value "xxx"
		 *
		 *	/user/user/{BEZ1^"*ene*"}
		 *	BEZ1 like "%ene%"
		 *
		 * /user/user/{(BEZ1^"*ene*"|BEZ1^"Hag*");(AKTIV="1"|GR_ID="-9")}
		 * (BEZ1 like "%ene%" or BEZ1 like "Hag%") and (AKTIV="1" or GR_ID="-9" )
		 *
		 *  alle Nutzer die in der BEZ_1 ein %ene% odr Hag% haben udn aktiv oder in der -9. Gruppe sind
		 *
		 *  []				// Orderangaben
		 *  field,direcrion //  direcrion: a = ASC, d = DESC, r = RAND()
		 *  bez1,ASC;bez2,DESC
		 *
		 *
		 */
		public function parseString($string){
			/** ORDER-STRING auslesen */
			$orderStr = '';
			preg_match_all("/\[(.*)\]/", $string, $help);
			@reset($help[1]);
			while(list($key,$helpStr)=@each($help[1])){
				$orderStr .= $helpStr;
			}
			$this->orderString = $this->parseOrderString($orderStr);

			/** LIMIT-STRING auslesen */
			$limitStr = '';
			preg_match_all("/~(.*)~/", $string, $help);
			@reset($help[1]);
			while(list($key,$helpStr)=@each($help[1])){
				$help = explode(',',$helpStr);
				if (trim($help[0])==''){ $help[0] = 0; }
				if (trim($help[1])==''){ $help[1] = 50; }
				$this->limitString = intval($help[0]).','.intval($help[1]);
			}


			/** FILTER-STRING auslesen */
			$this->queryString = '{';
			preg_match_all("/\{(.*)\}/", $string, $help);
			@reset($help[1]);
			while(list($key,$helpStr)=@each($help[1])){
				$this->queryString  .= $helpStr;
			}
			$this->queryString .= '}';

			$this->filterString = $this->_parseString(null);
			return $this->filterString;
		}


		private function parseOrderString($orderStr){
			$return = '';
			$help = explode(';',$orderStr);
			@reset($help);
			while (list($key,$val)=@each($help)){
					$subhelp 	= explode(',',$val);
					$field 		= $this->fieldTranslation[ $subhelp[0] ];
					$direction 	= $this->orderCommandTranslation[ $subhelp[1] ];
					if (trim($field)!=''){
						$return .= $field.' ';
						if (trim($direction)!=''){
							$return .= $direction.' ';
						}
						$return .= ', ';
					}
			}
			return substr($return,0,-2);
		}

		private function _parseString($breackChar){
			$return = self::FLD_START;
			while ($this->queryString != ''){
				$char = $this->queryString[0];
				$this->queryString = substr($this->queryString,1);
				if ($breackChar == $char){ return $return; }
				switch ($char){
					case '(': $return .= self::FLD_END.$this->parseKlammer().self::FLD_START; break;
					case '^': $return .= self::FLD_END.$this->parseLike().self::FLD_START; break;
					case '"': $return .= self::FLD_END.$this->parseValue().self::FLD_START; break;
					case ';': $return .= self::FLD_END.' and '.self::FLD_START; break;
					case '|': $return .= self::FLD_END.' or '.self::FLD_START; break;
					case '=': $return .= self::FLD_END.' = '.self::FLD_START; break;
					case '>': $return .= self::FLD_END.' > '.self::FLD_START; break;
					case '<': $return .= self::FLD_END.' < '.self::FLD_START; break;
					case '!': $return .= self::FLD_END.' not '.self::FLD_START; break;
					case '{':
					case '}': break;
					default:
						$return .= $char;
					break;
				}
			}

			$return = str_replace(self::FLD_START.self::FLD_END,'' , $return);
			$return = str_replace(self::FLD_END.self::FLD_START,'' , $return);
			$return = str_replace(self::FLD_START.' ',' ' , $return);
			$return = str_replace(' '.self::FLD_END,' ' , $return);

			$alleTranslationsGefunden  = true;
			preg_match_all("/-=FldStart=-(.*)-=FldEnd=-/Uis", $return, $out, PREG_PATTERN_ORDER);
			@reset($out[1]);
			while(list($key,$name)=@each($out[1])){
				if (isset($this->fieldTranslation[$name])){
					$return = str_replace(self::FLD_START.$name.self::FLD_END, $this->fieldTranslation[$name] , $return);
				}else{
					$alleTranslationsGefunden = false;
					$this->error .= $name.',';
				}
			}
			$return = str_replace(self::FLD_START,'' , $return);
			$return = str_replace(self::FLD_END,'' , $return);
			if (!$alleTranslationsGefunden){
				$this->error  = 'Diese Parameter konnte ich nicht verarbeiten '.$this->error ;
				return false;

			}else{
				return $return;
			}
		}

	/**
	 * @param $paramName
	 * @return array 'orginal', 'value', 'operator'
	 */
		public function getParamByBame($paramName){
			$return = null;
			$help = $this->filterString;
			$fieldName = $this->fieldTranslation[$paramName];
			preg_match_all("/$fieldName(.*\".*)\"/Uis", $help, $gefunden, PREG_SET_ORDER);
			@reset($gefunden);
			while (list($key,$val)=@each($gefunden)){
					$gefunden = $val[0];
					$value 	  = substr( $val[1], strpos($val[1],'"') ).'"';
					$operator = trim(substr($val[0],strlen($fieldName), strpos($val[0],'"')-strlen($fieldName)));
					$return[] = array(
						'orginal'  =>$gefunden,
						'value'    =>$value,
						'operator' =>$operator
					);
			}
			return $return;
		}


		// (____)
		public function parseKlammer(){
			return '( '.$this->_parseString(')').' ) ';
		}

		// ^
		public function parseLike(){
			$char = $this->queryString[0];
			if ($char == '"'){  $this->queryString = substr($this->queryString,1); }
			return ' like '.$this->parseValue();
		}

		// "...."
		public function parseValue(){
			$return = '';
			while ($this->queryString != ''){
				$char = $this->queryString[0];
				$this->queryString = substr($this->queryString,1);
				if ($char == '"'){ break; }
				switch ($char){
					case '*': $return .= '%'; break;
					default:
						$return .= $char;
				}
			}
			return '"'.mysql_escape_string($return).'"';
		}

	public function __construct(){
		$this->orderCommandTranslation = array(
				"d"=>"DESC",
				"a"=>"ASC",
				"r"=>"RAND()",
		);
	}


	/**
	 * @return mixed
	 */
	public function getQueryString()
	{
		return $this->queryString;
	}

	/**
	 * @param mixed $queryString
	 */
	public function setQueryString($queryString)
	{
		$this->queryString = $queryString;
	}

	/**
	 * @return mixed
	 */
	public function getFieldTranslation()
	{
		return $this->fieldTranslation;
	}

	/**
	 * @param mixed $fieldTranslation
	 */
	public function setFieldTranslation($fieldTranslation)
	{
		$this->fieldTranslation = $fieldTranslation;
	}
	/**
	 * @param mixed $fieldTranslation
	 */
	public function addFieldTranslation($name,$fieldName)
	{
		$this->fieldTranslation[$name] = $fieldName;
	}

	/**
	 * @return mixed
	 */
	public function getError()
	{
		return $this->error;
	}

	/**
	 * @param mixed $error
	 */
	public function setError($error)
	{
		$this->error = $error;
	}

	/**
	 * @return mixed
	 */
	public function getOrderString()
	{
		return $this->orderString;
	}

	/**
	 * @return mixed
	 */
	public function getFilterString()
	{
		return $this->filterString;
	}

	/**
	 * @return mixed
	 */
	public function getLimitString()
	{
		return $this->limitString;
	}




}

?>