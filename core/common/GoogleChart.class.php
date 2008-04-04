<?php
class utility{
	public static function count_r($mixed){
		$totalCount = 0;
		
		foreach($mixed as $temp){
			if(is_array($temp)){
				$totalCount += utility::count_r($temp);
			}
			else{
				$totalCount += 1;
			}
		}
		return $totalCount;
	}
	
	public static function addArrays($mixed){
		$summedArray = array();
		
		foreach($mixed as $temp){
			$a=0;
			if(is_array($temp)){
				foreach($temp as $tempSubArray){
					$summedArray[$a] += $tempSubArray;
					$a++;
				}
			}
			else{
				$summedArray[$a] += $temp;
			}
		}
		return $summedArray;
	}
	public static function getScaledArray($unscaledArray, $scalar){
		$scaledArray = array();
		
		foreach($unscaledArray as $temp){
			if(is_array($temp)){
				array_push($scaledArray, utility::getScaledArray($temp, $scalar));
			}
			else{
				array_push($scaledArray, round($temp * $scalar, 2));
			}
		}
		return $scaledArray;
	}
	
	public static function getMaxCountOfArray($ArrayToCheck){
		$maxValue = count($ArrayToCheck);
		
		foreach($ArrayToCheck as $temp){
			if(is_array($temp)){
				$maxValue = max($maxValue, utility::getMaxCountOfArray($temp));
			}
		}
		return $maxValue;
		
	}

	public static function getMaxOfArray($ArrayToCheck){
		$maxValue = 1;
		
		foreach($ArrayToCheck as $temp){
			if(is_array($temp)){
				$maxValue = max($maxValue, utility::getMaxOfArray($temp));
			}
			else{
				$maxValue = max($maxValue, $temp);
			}
		}
		return $maxValue;
	}
	
}
	class gBackground{
		public $colors = array("ffffff");
		public $fillType = 0;
		protected $fillTypes = array ("s", "lg", "ls");
		public $isChart = false;
		public function toArray(){
			$retArray = array();
			if($this->isChart)
				array_push($retArray, "c");
			else
				array_push($retArray, "bg");
			array_push($retArray,$this->fillTypes[$this->fillType]);
			array_push($retArray,$this->colors[0]);
			return $retArray;			
		}
	}
	class gChart{
		private $baseUrl = "http://chart.apis.google.com/chart?";
		protected $scalar = 1;
		
		public $types = array ("lc","lxy","bhs","bvs","bhg","bvg","p","p3","v","s");
		public $type = 1;
		public $dataEncodingType = "t";
		public $values = Array();
		protected $scaledValues = Array();
		public $valueLabels;
		public $xAxisLabels;
		public $dataColors;
		public $width = 200; //default
		public $height = 200; //default
		private $title;
		
		public $backgrounds;
		
		
		public function setTitle($newTitle){
			$this->title = str_replace("\r\n", "|", $newTitle);
			$this->title = str_replace(" ", "+", $this->title);
		}
		
		public function addBackground($gBackground){
			if(!isset($this->backgrounds)){
				$this->backgrounds = array($gBackground);
				return;
			}
			array_push($this->backgrounds, $gBackground);
		}
		
		protected function encodeData($data, $encoding, $separator){
			switch ($this->dataEncodingType){
				case "s":
					return $this->simpleEncodeData();
				case "e":
					return $this->extendedEncodeData();
				default:{
					$retStr = $this->textEncodeData($data, $separator, "|");
					$retStr = trim($retStr, "|");
					return $retStr;					
					}
			}
		}
		
		private function textEncodeData($data, $separator, $datasetSeparator){
			$retStr = "";
			if(!is_array($data))
				return $data;
			foreach($data as $currValue){
				if(is_array($currValue))
					$retStr .= $this->textEncodeData($currValue, $separator, $datasetSeparator);
				else
					$retStr .= $currValue.$separator;
			}
				
			$retStr = trim($retStr, $separator);
			$retStr .= $datasetSeparator;
			return $retStr;
		}
		
		public function addDataSet($dataArray){
			array_push($this->values, $dataArray);
		}
		public function clearDataSets(){
			$this->values = Array();
		}
		
		private function simpleEncodeData(){
			return "";
		}
		
		private function extendedEncodeData(){
			return "";
		}
		
		protected function prepForUrl(){
			$this->scaleValues();
		}
		protected function getDataSetString(){
			return "&chd=".$this->dataEncodingType.":".$this->encodeData($this->scaledValues,"" ,",");
		}
		protected function getAxesString(){
			$retStr = "&chxt=x,y";
			$retStr .= "&chxr=0,1,4|1,1,10";
			return $retStr;
		}
		
		protected function getBackgroundString(){
			if(!isset($this->backgrounds))
				return "";
			$retStr = "&chf=";
			foreach($this->backgrounds as $currBg){
				$retStr .= $this->textEncodeData($currBg->toArray(), ",", "|"); 
			}
			$retStr = trim($retStr, "|");
			return $retStr;
		}
		protected function getAxisLabels(){
			$retStr = "";
			if(isset($this->xAxisLabels))
				$retStr = "&chxl=0:|".$this->encodeData($this->xAxisLabels,"", "|");
			return $retStr;
		}
		protected function concatUrl(){
			$fullUrl .= $this->baseUrl;
			$fullUrl .= "cht=".$this->types[$this->type];
			$fullUrl .= "&chs=".$this->width."x".$this->height;
			
			$fullUrl .= $this->getDataSetString();
			if(isset($this->valueLabels))
				$fullUrl .= "&chdl=".$this->encodeData($this->getApplicableLabels($this->valueLabels),"", "|");
			$fullUrl .= $this->getAxisLabels();
			$fullUrl .= "&chco=".$this->encodeData($this->dataColors,"", ",");
			if(isset($this->title))
				$fullUrl .= "&chtt=".$this->title;
			$fullUrl .= $this->getAxesString();
//			$fullUrl .= $this->getBackgroundString();
			
			return $fullUrl;
		}
		protected function getApplicableLabels($labels){
			$trimmedValueLabels = $labels;
			return array_splice($trimmedValueLabels, 0, count($this->values));
		}
		public function getUrl(){
			$this->prepForUrl();
			return $this->concatUrl();
		}
		
		protected function scaleValues(){
			$this->setScalar();
			$this->scaledValues = utility::getScaledArray($this->values, $this->scalar);
		}


		function setScalar(){
			$maxValue = 100;
			$maxValue = max($maxValue, utility::getMaxOfArray($this->values));
			if($maxValue <100)
				$this->scalar = 1;
			else
				$this->scalar = 100/$maxValue;
		}
	}

	class gPieChart extends gChart{
		function __construct(){
			$this->type = 6;
			$this->width = $this->height * 1.5;
		}
		function setScalar(){
			return 1;
		}

		protected function getAxesString(){
			return "";
		}
		
		public function getUrl(){
			$retStr = parent::getUrl();
			$retStr .= "&chl=".$this->encodeData($this->valueLabels,"", "|");
			return $retStr;
		}
		private function getScaledArray($unscaledArray, $scalar){
			return $unscaledArray;		
		}
		public function set3D($is3d){
			if($is3d){
				$this->type = 7;
				$this->width = $this->height * 2;
			}
			else{
				$this->type = 6;
				$this->width = $this->height * 1.5;
			}
		}
	}

	class gLineChart extends gChart{
		function __construct(){
			$this->type = 0;
		}		
	}
	
	class gBarChart extends gChart{
		public $barWidth;
		private $realBarWidth;
		public $groupSpacerWidth = 1;
		protected $totalBars = 1;
		protected $isHoriz = false;
		public function getUrl(){
			$this->scaleValues();
			$this->setBarWidth();
			$retStr = parent::concatUrl();
			$retStr .= "&chbh=$this->realBarWidth,$this->groupSpacerWidth";
			return $retStr;
		}
		
		function setBarCount(){
			$this->totalBars = utility::count_r($this->values);
		}
		
		protected function getAxisLabels(){
			$retStr = "";
			$xAxis = 0;
			if($this->isHoriz)
				$xAxis = 1;	
			$yAxis = 1 - $xAxis;			
			if(isset($this->xAxisLabels)){
				$retStr = "&chxl=$xAxis:|".$this->encodeData($this->xAxisLabels,"", "|");
//				$retStr = "&$yAxis:|".$this->encodeData($this->yAxisLabels,"", "|");
			}
			return $retStr;
		}
		private function setBarWidth(){
			if(isset($this->barWidth)){
				$this->realBarWidth = $this->barWidth;
				return;
			}
			$this->setBarCount();
			$totalGroups = utility::getMaxCountOfArray($this->values);
			if($this->isHoriz)
				$chartSize = $this->height - 50;
			else
				$chartSize = $this->width - 50;
				
			$chartSize -= $totalGroups * $this->groupSpacerWidth;
			$this->realBarWidth = round($chartSize/$this->totalBars);
		}
		
	}
	class gGroupedBarChart extends gBarChart{
		function __construct(){
			$this->type = 5;
		}
		
		public function setHorizontal($isHorizontal){
			if($isHorizontal){
				$this->type = 4;
			}
			else{
				$this->type = 5;
			}
			$this->isHoriz = $isHorizontal;
		}

	}
	class gStackedBarChart extends gBarChart{
		function __construct(){
			$this->type = 3;
		}

		function setBarCount(){
			$this->totalBars = utility::getMaxCountOfArray($this->values);
		}
		
		public function setHorizontal($isHorizontal){
			if($isHorizontal){
				$this->type = 2;
			}
			else{
				$this->type = 3;
			}
			$this->isHoriz = $isHorizontal;
		}

		protected function scaleValues(){
			$this->setScalar();
			$this->scaledValues = utility::getScaledArray($this->values, $this->scalar);
		}
		
		function setScalar(){
			$maxValue = 100;
			$maxValue = max($maxValue, utility::getMaxOfArray(utility::addArrays($this->values)));
			if($maxValue <100)
				$this->scalar = 1;
			else
				$this->scalar = 100/$maxValue;
		}
		
	}
	
	class gVennDiagram extends gChart{
		private $intersections = array(0,0,0,0);
		public function addIntersections($mixed){
			$this->intersections = $mixed;
		}
		function __construct(){
			$this->type = 8;
		}
		protected function getAxesString(){
			return "";
		}
		
		public function getUrl(){
			$retStr = parent::getUrl();
//			$retStr .= "&chl=".$this->encodeData($this->valueLabels,"", "|");
			return $retStr;
		}
		protected function getDataSetString(){
			$fullDataSet = array_splice($this->scaledValues[0], 0, 3);
			while(count($fullDataSet)<3){
				array_push($fullDataSet, 0);
			}
			
			$scaledIntersections = utility::getScaledArray($this->intersections, $this->scalar);
			foreach($scaledIntersections as $temp){
				array_push($fullDataSet, $temp);
			}
			$fullDataSet = array_splice($fullDataSet, 0, 7);
			while(count($fullDataSet)<7){
				array_push($fullDataSet, 0);
			}
			
			return "&chd=".$this->dataEncodingType.":".$this->encodeData($fullDataSet,"" ,",");
		}
	}

	
?>
