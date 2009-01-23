<?php
/**
 * phpVMS - Virtual Airline Administration Software
 * Copyright (c) 2008 Nabeel Shahzad
 * For more information, visit www.phpvms.net
 *	Forums: http://www.phpvms.net/forum
 *	Documentation: http://www.phpvms.net/docs
 *
 * phpVMS is licenced under the following license:
 *   Creative Commons Attribution Non-commercial Share Alike (by-nc-sa)
 *   View license.txt in the root, or visit http://creativecommons.org/licenses/by-nc-sa/3.0/
 *
 * @author Nabeel Shahzad
 * @copyright Copyright (c) 2008, Nabeel Shahzad
 * @link http://www.phpvms.net
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/
 */
 
 
 /**
  * Graphing class, so I don't have to kill myself every
  * time I have to create a new graph
  */
  
  
# Include the pChart library
include_once SITE_ROOT.'/core/lib/pchart/pCache.class.php';
include_once SITE_ROOT.'/core/lib/pchart/pChart.class.php';
include_once SITE_ROOT.'/core/lib/pchart/pData.class.php';
 
 class ChartGraph
 {
	
	# Options
	public $source = 'gchart';
	public $type;
	public $orig_type;
	protected $pInvalid;
	
	# Chart
	public $pChart;
	public $pCache;
	public $data = array();
	public $labels = array();
	
	# Titles
	public $x_title;
	public $y_title;
	public $chart_title;
	
	# Sizing
	public $x;
	public $y;
	
	public function __construct($source, $type, $x, $y)
	{
		$this->data = array();
		$this->x = $x;
		$this->y = $y;		
		
		if(function_exists('gd_info'))
		{
			$this->pChart = new pChart($this->x, $this->y);
			$this->pChart->setFontProperties(SITE_ROOT.'/lib/fonts/tahoma.ttf', 8);
			$this->pChart->loadColorPalette(SITE_ROOT.'/core/lib/pchart/tones-2.txt');
			#$this->pChart->reportWarnings(); 
			
			$this->setType($source, $type); 
		}
		else
		
			$this->pInvalid = true;
	}
		
	/**
	 * Set the type of graph
	 * 
	 * @var $source gchart (google chart) or pchart (php chart)
	 * @var $type - barx, bary, pie, pie3d
	 */
	public function setType($source='gchart', $type='barx')
	{
		
		if($this->pInvalid == true && $source == 'pchart')
		{
			$this->lastError = 'Cannot use pChart, GD is not installed';
			$source = 'gchart';
		}
		
		# Blank source type uses default
		if($source != '')
		{
			$this->source = $source;
		}
		
		$this->orig_type = $type;
		
		if($this->source == 'gchart')
		{
			# Translate the graph types to the proper
			#	types for the Google Chart
			if($type == 'barx')
				$this->type = 'barx';			
			elseif($type == 'bary')			
				$this->type = 'bary';			
			elseif($type == 'pie')			
				$this->type = 'p';		
			elseif($type == 'pie3d')
				$this->type = 'p3';
			else			
				$this->type = 'barx';
		}
		else
		{
			$this->type = $type;
		}
				
	}
	
	public function setTitles($chart_title, $x_title='', $y_title='')
	{
		$this->chart_title = $chart_title;
		$this->x_title = $x_title;
		$this->y_title = $y_title;
	}
	
	public function setSize($width, $height)
	{
		$this->x = $width;
		$this->y = $height;
	}
	
	public function AddData($data, $x_labels, $y_labels='')
	{
		//$data = array('data'=>$data, 'x_labels'=>$x_labels, 'y_labels'=>$y_labels);
		$this->data = $data;
		$this->labels = $x_labels;
	}
	
	public function GenerateGraph($filename='')
	{
		
		# Check if GD is installed
		#	If not, then default to gchart, otherwise use pchart
		#	
		if($this->pInvalid == true)
		{
			$this->setType('gchart', $this->orig_type);
		}		
			
		
		if($this->source == 'pchart')
		{
			return $this->pChart($filename);
		}
		elseif($this->source == 'gchart')
		{
			return $this->GoogleChart();
		}
	}
	
	
	protected function pChart($filename)
	{
		$pData = new pData;
		$count = 1;
			
		$pData->AddPoint($this->data, 'dataset');
		$pData->AddPoint($this->labels, 'labels', 'labels');
		$pData->AddSerie('dataset');
		$pData->SetAbsciseLabelSerie('labels');
		
		$pData->SetYAxisName($this->y_title);
		$pData->SetXAxisName($this->x_title);  
		
		/*
			Check the cache
		 */
		# Set the file name:
		if($filename == '')
			$filename = md5(implode('',$this->data));
			
		//$this->pCache = new pCache(SITE_ROOT.'/core/cache'); 
		//$this->pCache->GetFromCache($filename,$pData->GetData());
		
	  	# Create a "frame"
		$this->pChart->drawFilledRoundedRectangle(0,0,$this->x,$this->y,5,240,240,240);  
		$this->pChart->drawRoundedRectangle(5,5,$this->x-5,$this->y-5,5,230,230,230);
		
		if($this->type == 'pie')
		{
			$this->pChart->drawBasicPieGraph($pData->GetData(), $pData->GetDataDescription(),150,90,70,PIE_PERCENTAGE);  
			$this->pChart->drawPieLegend($this->x-200,30,$pData->GetData(),$pData->GetDataDescription(),250,250,250);
		}
		elseif($this->type == 'pie3d')
		{			
			$this->pChart->drawPieGraph($pData->GetData(), $pData->GetDataDescription(),150,90,70,PIE_PERCENTAGE,TRUE,50,20,5);
			$this->pChart->drawPieLegend($this->x-200,30,$pData->GetData(),$pData->GetDataDescription(),250,250,250);
		}
		elseif($this->type == 'line')
		{  		
			$this->pChart->setGraphArea(90, 30, $this->x-30, $this->y-50);  
			$this->pChart->drawScale($pData->GetData(), $pData->GetDataDescription(),SCALE_START0,0,0,0);  
			$this->pChart->drawTreshold(0,143,55,72,TRUE,TRUE);  
			$this->pChart->drawGrid(4,TRUE);
			
			#$this->pChart->drawLegend(90,35,$pData->GetDataDescription(),255,255,255); 
			$this->pChart->drawLineGraph($pData->GetData(), $pData->GetDataDescription()); 
			$this->pChart->drawPlotGraph($pData->GetData(),$pData->GetDataDescription()); 
		}
		elseif($this->type = 'bar')
		{
			$this->pChart->drawBarGraph($pData->GetData(),$pData->GetDataDescription(),TRUE);
		}
			
		$w = strlen($this->chart_title)*1.5;
		@$this->pChart->drawTitle(0,20,$this->chart_title,0,0,0, $this->x);
		
		
		//$this->pCache->WriteToCache($filename,$pData->GetData(),$this->pChart); 
					
		$this->pChart->Render(SITE_ROOT.'/core/cache/'.$filename.'.png'); 
		return SITE_URL.'/core/cache/'.$filename.'.png';
	}
		
	
	/**
	 * Create a Google Chart chart
	 */
	protected function GoogleChart()
	{
		$chart = new googleChart(null, $this->type);
			
		# Loop through every set data
		foreach($this->data as $set)
		{
			$values = implode(',', $set['data']);
			$labels = implode('|', $set['x_labels']);		
	
			$chart->loadData($values);
			$chart->setLabels($labels, 'bottom');
		}
			
		$chart->dimensions = $this->x.'x'.$this->y;
		
		return $chart->draw(false);
	}
}