<?php

namespace App\Http\Controllers;

use App\Http\Controllers\TradingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TradingController extends Controller 
{
   
	 // starts find the highs and lows in database 
	public function bucle($days, $long)
	{
		$maxmin = [];
		
		foreach ($days as $key => $day) {
			if($key >= $long - 4 ){

			}elseif ($key >=  5){
				$max = $this->findMax($days,$key);
				$min = $this->findMin($days,$key);

				if($max === true){

						array_push($maxmin, ["Date" => "$day->Date","High" => "$day->High","Volume" => "$day->Volume"]);

				}
			
				
				if($min === true){

						array_push($maxmin, ["Date" => "$day->Date","Low" => "$day->Low","Volume" => "$day->Volume"]);

				}


			}else{

			}
			//minMax($maximos, $minimus, $days, $b, $long);
				# code...
		}#
		
		return $maxmin;
	}
	
	public function findMax($days,$b){
		

		if (    $days[$b]->High > $days[$b - 1]->High and
				$days[$b]->High > $days[$b - 2]->High and
	 			$days[$b]->High > $days[$b - 3]->High and
	 			$days[$b]->High > $days[$b - 4]->High and
	 			//$days[$b]->High > $days[$b - 5]->High and
	 			//$days[$b]->High > $days[$b - 6]->High and
	 			//$days[$b]->High > $days[$b - 7]->High and
	 			//$days[$b]->High > $days[$b - 8]->High and 
	 			$days[$b]->High > $days[$b + 1]->High and
	 			$days[$b]->High > $days[$b + 2]->High and
	 			$days[$b]->High > $days[$b + 3]->High and
	 			$days[$b]->High > $days[$b + 4]->High 
	 			//$days[$b]->High > $days[$b + 5]->High 
	 			//$days[$b]->High > $days[$b + 6]->High      //agregar and 
	 			//$days[$b]->High > $days[$b + 7]->High and
	 			//$days[$b]->High > $days[$b + 8]->High
	 			){

				return true;
		}
	 	
	 	return false;			

	}

	public function findMin($days,$b){
		

		if (    $days[$b]->Low < $days[$b - 1]->Low and
				$days[$b]->Low < $days[$b - 2]->Low and
				$days[$b]->Low < $days[$b - 3]->Low and
				$days[$b]->Low < $days[$b - 4]->Low and
				//$days[$b]->Low < $days[$b - 5]->Low and
				//$days[$b]->Low < $days[$b - 6]->Low and
				//$days[$b]->Low < $days[$b - 7]->Low and
				//$days[$b]->Low < $days[$b - 8]->Low and 
				$days[$b]->Low < $days[$b + 1]->Low and
				$days[$b]->Low < $days[$b + 2]->Low and
				$days[$b]->Low < $days[$b + 3]->Low and
				$days[$b]->Low < $days[$b + 4]->Low 
				//$days[$b]->Low < $days[$b + 5]->Low 
				//$days[$b]->Low < $days[$b + 6]->Low       //agregar and
				//$days[$b]->Low < $days[$b + 7]->Low and
				//$days[$b]->Low < $days[$b + 8]->Low
				){

			return true;
		}
	 			
		return false;
	}
	// final find the highs and lows in database 


	// starts the correction of minimums and maximums
	public function corrective($maxmins){

		$maxminC = [];

		foreach ($maxmins as $key => $maxmin) {
					
				if (isset($maxmin['High']) === true) 
				{
					$max = $this->corrMax($maxmins,$key);
					if($max === true){

						array_push($maxminC, ["Date"=>$maxmin['Date'],"High"=>$maxmin['High'],"Volume"=>$maxmin['Volume']]);

					}
		
				}

				if(isset($maxmin['Low']) === true)
				{

					$min = $this->corrMin($maxmins,$key);
					
					if($min === true){

						array_push($maxminC, ["Date"=>$maxmin['Date'],"Low"=>$maxmin['Low'],"Volume"=>$maxmin['Volume']]);

					}
				}	
				
		}

		return $maxminC;
	}

	public function corrMax($maxmins,$b){

			
			if (isset($maxmins[$b + 1]['Low'])) {
				return	true;
			}
					
			return false;
	}

	public function corrMin($maxmins,$b){
			
			if (isset($maxmins[$b + 1]['High'])){
				return	true;
			}
			
			return false;
	}
	// final the correction of minimums and maximums

	// starts bullish cases, add volume summation, retracement, expansion.

	public function Vol_FR_FE_bullish($maxminC,$long)
	{
		$maxminF = $maxminC;

		//resive 2 fechas una inicial y otra final
		foreach ($maxminF as $key => $maxmin) {
			
			if ($key < $long - 1 ) {
				//busca en el array los registros comprendidos entre esas fechas 
				$rangedays = DB::table('data_us30')->whereBetween('Date', [$maxmin['Date'], $maxminF[$key + 1]['Date']])->get();
				
				$count = count($rangedays);
				
				//suma el campo volumen 
				 $sumvolume = 0;

				for ($i=0; $i < $count ; $i++) { 
				 	$sumvolume = $rangedays[$i]->Volume	+ $sumvolume;
				}

				$fibonacciRetracement = $this->fibonacciRetracementbullish($key,$maxminF);
			
				$fibonacciExpansion = $this->fibonacciExpansionbullish($key,$maxminF);
				
				//agrega el valor al array
				array_push($maxminF[$key], ['SumVolume'=>$sumvolume, 'FiboR'=>$fibonacciRetracement, 'FiboE'=>$fibonacciExpansion]);  
				

			}
		}
		
		return $maxminF;
		
	}

	public function fibonacciRetracementbullish($key,$maxminF)
	{
		if ($key > 1 ) {
			
			
			if(isset($maxminF[$key]['Low']) and isset($maxminF[$key - 1]['High']) and isset($maxminF[$key - 2]['Low'])){

			

			$secondLow = $maxminF[$key]['Low'];

			$firstHigh = $maxminF[$key - 1]['High'];
					
			$firstLow = $maxminF[$key - 2]['Low'];
			
			
			$firstExpansion = abs($firstHigh - $firstLow);
			$firstContraction = abs($firstHigh - $secondLow);
			
			
			
			$fibonacciRetracement = $firstContraction * 100 / $firstExpansion;
			
			

			return $fibonacciRetracement ; 
			
				
			}
		}
		
	}

	public function fibonacciExpansionbearish($key,$maxminF)
	{
		if ($key > 1 ) {
			if(isset($maxminF[$key + 1]['Low']) and isset($maxminF[$key]['High']) and isset($maxminF[$key - 1]['Low']) and isset($maxminF[$key - 2]['High'])){

			$secondLow = $maxminF[$key + 1]['Low'];

			$secondHigh = $maxminF[$key]['High'];

			$firstLow = $maxminF[$key - 1]['Low'];
					
			$firstHigh = $maxminF[$key - 2]['High'];
			
			
			$firstExpansion = abs($firstHigh - $firstLow);
			
			$secondExpansion = abs($secondHigh - $secondLow);
			
			
			
			$fibonacciExpansionbullish = $secondExpansion * 100 / $firstExpansion;
			

			return $fibonacciExpansionbullish; 

			}
				
		}
		
	}
	// final bullish cases, add volume summation, retracement, expansion.

	// starts bearish cases, add volume summation, retracement, expansion.

	public function Vol_FR_FE_bearish($maxminC,$long)
	{
		$maxminF = $maxminC;

		//resive 2 fechas una inicial y otra final
		foreach ($maxminF as $key => $maxmin) {
			
			if ($key < $long - 1 ) {
				//busca en el array los registros comprendidos entre esas fechas 
				$rangedays = DB::table('data_us30')->whereBetween('Date', [$maxmin['Date'], $maxminF[$key + 1]['Date']])->get();
				
				$count = count($rangedays);
				
				//suma el campo volumen 
				$sumvolume = 0;

				for ($i=0; $i < $count ; $i++) { 
					$sumvolume = $rangedays[$i]->Volume	+ $sumvolume;
				}

				$fibonacciRetracement = $this->fibonacciRetracementbearish($key,$maxminF);
			
				$fibonacciExpansion = $this->fibonacciExpansionbearish($key,$maxminF);
				
				//agrega el valor al array
				array_push($maxminF[$key], ['SumVolume'=>$sumvolume,'FiboR'=>$fibonacciRetracement, 'FiboE'=>$fibonacciExpansion]);  
				
				

			}
		}
		
		return $maxminF;
		

	}

	
	public function fibonacciRetracementbearish($key,$maxminF)
	{
		if ($key > 1 ) {
			
			if(isset($maxminF[$key]['High']) and isset($maxminF[$key - 1]['Low']) and isset($maxminF[$key - 2]['High'])){

				

				$secondHigh = $maxminF[$key]['High'];

				$firstLow = $maxminF[$key - 1]['Low'];
						
				$firstHigh = $maxminF[$key - 2]['High'];
				
				
				$firstExpansion = abs($firstHigh - $firstLow);
				$firstContraction = abs($secondHigh - $firstLow);
				
				
				
				$fibonacciRetracement = $firstContraction * 100 / $firstExpansion;
				
				

				return $fibonacciRetracement ; 
				
				
			}

		}
		
	}


	public function fibonacciExpansionbullish($key,$maxminF)
	{
		if ($key > 1 ) {
			if(isset($maxminF[$key + 1]['High']) and isset($maxminF[$key]['Low']) and isset($maxminF[$key - 1]['High']) and isset($maxminF[$key - 2]['Low'])){

			$secondHigh = $maxminF[$key + 1]['High'];

			$secondLow = $maxminF[$key]['Low'];

			$firstHigh = $maxminF[$key - 1]['High'];
					
			$firstLow = $maxminF[$key - 2]['Low'];
			
			
			$firstExpansion = abs($firstHigh - $firstLow);
			
			$secondExpansion = abs($secondHigh - $secondLow);
			
			
			
			$fibonacciExpansionbearish = $secondExpansion * 100 / $firstExpansion;
			

			return $fibonacciExpansionbearish; 
				
				
			}
		}
		
	}

		
	// final bearish cases, add volume summation, retracement, expansion.

	// starts find the SAB
	public function getsab($maxminC , $long)
	{
		$sabs = [];
			
		foreach ($maxminC as $key => $maxmin) {
			
			if ($key > 2) {

				if (isset($maxminC[$key]['High']) and isset($maxminC[$key-1]['Low']) and isset($maxminC[$key - 2]['High']) and isset($maxminC[$key-3]['Low'])) {
					
					$a = $maxminC[$key - 3]['Low'];
					$b = $maxminC[$key - 2]['High'];
					$c = $maxminC[$key - 1]['Low'];
					$d = $maxminC[$key]['High'];

						// case ++++ 
						if ( 	$a < $b and
								$b > $c and
								$c > $a and 
								$d > $c and
								$d > $b and
								$d > $a	) {

							$fibonacciRetracement = $this->fibonacciRetracementbullish($key-1,$maxminC);
			
							$fibonacciExpansion = $this->fibonacciExpansionbullish($key-1,$maxminC);

							//busca en el array los registros comprendidos entre esas fechas 
							$rangedays = DB::table('data_us30')->whereBetween('Date',[$maxminC[$key - 3]['Date'] , $maxminC[$key - 2]['Date']])->get();
							
							$count = count($rangedays);
							
							//suma el campo volumen 
							$sumvolume = 0;

							for ($i=0; $i < $count ; $i++) { 
								$sumvolume = $rangedays[$i]->Volume	+ $sumvolume;
							}

							
							array_push($sabs, [	'SumVolume'=>$sumvolume,
												'Low1' => $maxminC[$key - 3]['Low'],
												'High1' => $maxminC[$key - 2]['High'],
												'Low2' => $maxminC[$key - 1]['Low'],
												'High2' => $maxmin['High'],

												'Dlow1' => $maxminC[$key - 3]['Date'],
												'Dhigh1' => $maxminC[$key - 2]['Date'],
												'Dlow2' => $maxminC[$key - 1]['Date'],
												'Dhigh2'=> $maxminC[$key]['Date'],
												'Type' => 'bullish',
												'Sab' => '++++' , 
												'FiboR'=>$fibonacciRetracement,
												'FiboE'=>$fibonacciExpansion
												]);
						}
						// case +++-
						if (	$a < $b and
								$b > $c and
								$c > $a and 
								$d > $c and
								$d < $b and
								$d > $a ) {

							$fibonacciRetracement = $this->fibonacciRetracementbullish($key-1,$maxminC);
			
							$fibonacciExpansion = $this->fibonacciExpansionbullish($key-1,$maxminC);

							//busca en el array los registros comprendidos entre esas fechas 
							$rangedays = DB::table('data_us30')->whereBetween('Date',[$maxminC[$key - 3]['Date'] , $maxminC[$key - 2]['Date']])->get();
							
							$count = count($rangedays);
							
							//suma el campo volumen 
							$sumvolume = 0;

							for ($i=0; $i < $count ; $i++) { 
								$sumvolume = $rangedays[$i]->Volume	+ $sumvolume;
							}

							array_push($sabs, [	'SumVolume'=>$sumvolume,
												'Low1' => $maxminC[$key - 3]['Low'],
												'High1' => $maxminC[$key - 2]['High'],
												'Low2' => $maxminC[$key - 1]['Low'],
												'High2' => $maxmin['High'],

												'Dlow1' => $maxminC[$key - 3]['Date'],
												'Dhigh1' => $maxminC[$key - 2]['Date'],
												'Dlow2' => $maxminC[$key - 1]['Date'],
												'Dhigh2'=> $maxminC[$key]['Date'],
												'Type' => 'bullish',
												'Sab' => '+++-' , 
												'FiboR'=>$fibonacciRetracement,
												'FiboE'=>$fibonacciExpansion
												]);
						}
						// case ++-+
						if (    $a < $b and
								$b > $c and
								$c < $a and 
								$d > $c and
								$d > $b and
								$d > $a ) {

							$fibonacciRetracement = $this->fibonacciRetracementbullish($key-1,$maxminC);
			
							$fibonacciExpansion = $this->fibonacciExpansionbullish($key-1,$maxminC);

							//busca en el array los registros comprendidos entre esas fechas 
							$rangedays = DB::table('data_us30')->whereBetween('Date',[$maxminC[$key - 3]['Date'] , $maxminC[$key - 2]['Date']])->get();
							
							$count = count($rangedays);
							
							//suma el campo volumen 
							$sumvolume = 0;

							for ($i=0; $i < $count ; $i++) { 
								$sumvolume = $rangedays[$i]->Volume	+ $sumvolume;
							}

							array_push($sabs, [	'SumVolume'=>$sumvolume,
												'Low1' => $maxminC[$key - 3]['Low'],
												'High1' => $maxminC[$key - 2]['High'],
												'Low2' => $maxminC[$key - 1]['Low'],
												'High2' => $maxmin['High'],

												'Dlow1' => $maxminC[$key - 3]['Date'],
												'Dhigh1' => $maxminC[$key - 2]['Date'],
												'Dlow2' => $maxminC[$key - 1]['Date'],
												'Dhigh2'=> $maxminC[$key]['Date'],
												'Type' => 'bullish',
												'Sab' => '++-+' , 
												'FiboR'=>$fibonacciRetracement,
												'FiboE'=>$fibonacciExpansion
												]);
						}
						// case ++--
						if (    $a < $b and
								$b > $c and
								$c < $a and 
								$d > $c and
								$d < $b and
								$d > $a ) {

							$fibonacciRetracement = $this->fibonacciRetracementbullish($key-1,$maxminC);
			
							$fibonacciExpansion = $this->fibonacciExpansionbullish($key-1,$maxminC);

							//busca en el array los registros comprendidos entre esas fechas 
							$rangedays = DB::table('data_us30')->whereBetween('Date',[$maxminC[$key - 3]['Date'] , $maxminC[$key - 2]['Date']])->get();
							
							$count = count($rangedays);
							
							//suma el campo volumen 
							$sumvolume = 0;

							for ($i=0; $i < $count ; $i++) { 
								$sumvolume = $rangedays[$i]->Volume	+ $sumvolume;
							}

							array_push($sabs, [	'SumVolume'=>$sumvolume,
												'Low1' => $maxminC[$key - 3]['Low'],
												'High1' => $maxminC[$key - 2]['High'],
												'Low2' => $maxminC[$key - 1]['Low'],
												'High2' => $maxmin['High'],

												'Dlow1' => $maxminC[$key - 3]['Date'],
												'Dhigh1' => $maxminC[$key - 2]['Date'],
												'Dlow2' => $maxminC[$key - 1]['Date'],
												'Dhigh2'=> $maxminC[$key]['Date'],
												'Type' => 'bullish',
												'Sab' => '++--' , 
												'FiboR'=>$fibonacciRetracement,
												'FiboE'=>$fibonacciExpansion
												]);
						}

						// case ++---
						if (    $a < $b and
								$b > $c and
								$c < $a and 
								$d > $c and
								$d < $b and
								$d < $a ) {

							$fibonacciRetracement = $this->fibonacciRetracementbullish($key-1,$maxminC);
			
							$fibonacciExpansion = $this->fibonacciExpansionbullish($key-1,$maxminC);

							//busca en el array los registros comprendidos entre esas fechas 
							$rangedays = DB::table('data_us30')->whereBetween('Date',[$maxminC[$key - 3]['Date'] , $maxminC[$key - 2]['Date']])->get();
							
							$count = count($rangedays);
							
							//suma el campo volumen 
							$sumvolume = 0;

							for ($i=0; $i < $count ; $i++) { 
								$sumvolume = $rangedays[$i]->Volume	+ $sumvolume;
							}

							array_push($sabs, [	'SumVolume'=>$sumvolume,
												'Low1' => $maxminC[$key - 3]['Low'],
												'High1' => $maxminC[$key - 2]['High'],
												'Low2' => $maxminC[$key - 1]['Low'],
												'High2' => $maxmin['High'],

												'Dlow1' => $maxminC[$key - 3]['Date'],
												'Dhigh1' => $maxminC[$key - 2]['Date'],
												'Dlow2' => $maxminC[$key - 1]['Date'],
												'Dhigh2'=> $maxminC[$key]['Date'],
												'Type' => 'bullish',
												'Sab' => '++---' , 
												'FiboR'=>$fibonacciRetracement,
												'FiboE'=>$fibonacciExpansion
												]);
						}

				}

				if (isset($maxminC[$key]['Low']) and isset($maxminC[$key-1]['High']) and isset($maxminC[$key - 2]['Low']) and isset($maxminC[$key-3]['High'])) {

					$a = $maxminC[$key-3]['High'];
					$b = $maxminC[$key-2]['Low'];
					$c = $maxminC[$key-1]['High'];
					$d = $maxminC[$key]['Low'];
						
						// case ----
						if ( 	$a > $b and
								$b < $c and
								$c < $a and 
								$d < $c and
								$d < $b and
								$d < $a	) {

							$fibonacciRetracement = $this->fibonacciRetracementbearish($key-1,$maxminC);
			
							$fibonacciExpansion = $this->fibonacciExpansionbearish($key-1,$maxminC);

							//busca en el array los registros comprendidos entre esas fechas 
							$rangedays = DB::table('data_us30')->whereBetween('Date',[$maxminC[$key - 3]['Date'] , $maxminC[$key - 2]['Date']])->get();
							
							$count = count($rangedays);
							
							//suma el campo volumen 
							$sumvolume = 0;

							for ($i=0; $i < $count ; $i++) { 
								$sumvolume = $rangedays[$i]->Volume	+ $sumvolume;
							}

							array_push($sabs, [	'SumVolume'=>$sumvolume, 
												'High1' => $maxminC[$key - 3]['High'],
												'Low1' => $maxminC[$key - 2]['Low'],
												'High2' => $maxminC[$key - 1]['High'],
												'Low2' => $maxmin['Low'],

												'Dhigh1' => $maxminC[$key - 3]['Date'],
												'Dlow1' => $maxminC[$key - 2]['Date'],
												'Dhigh2' => $maxminC[$key - 1]['Date'],
												'Dlow2'=> $maxminC[$key]['Date'],
												'Type' => 'bearish',
												'Sab' => '----' , 
												'FiboR'=>$fibonacciRetracement,
												'FiboE'=>$fibonacciExpansion
												]);
						}
						// case ---+
						if (	$a > $b and
								$b < $c and
								$c < $a and 
								$d < $c and
								$d > $b and
								$d < $a ) {

							$fibonacciRetracement = $this->fibonacciRetracementbearish($key-1,$maxminC);
			
							$fibonacciExpansion = $this->fibonacciExpansionbearish($key-1,$maxminC);

							//busca en el array los registros comprendidos entre esas fechas 
							$rangedays = DB::table('data_us30')->whereBetween('Date',[$maxminC[$key - 3]['Date'] , $maxminC[$key - 2]['Date']])->get();
							
							$count = count($rangedays);
							
							//suma el campo volumen 
							$sumvolume = 0;

							for ($i=0; $i < $count ; $i++) { 
								$sumvolume = $rangedays[$i]->Volume	+ $sumvolume;
							}

							array_push($sabs, [ 'SumVolume'=>$sumvolume,
												'High1' => $maxminC[$key - 3]['High'],
												'Low1' => $maxminC[$key - 2]['Low'],
												'High2' => $maxminC[$key - 1]['High'],
												'Low2' => $maxmin['Low'],

												'Dhigh1' => $maxminC[$key - 3]['Date'],
												'Dlow1' => $maxminC[$key - 2]['Date'],
												'Dhigh2' => $maxminC[$key - 1]['Date'],
												'Dlow2'=> $maxminC[$key]['Date'],
												'Type' => 'bearish',
												'Sab' => '---+' , 
												'FiboR'=>$fibonacciRetracement,
												'FiboE'=>$fibonacciExpansion
												]);
						}
						// case --+-
						if (    $a > $b and
								$b < $c and
								$c > $a and 
								$d < $c and
								$d < $b and
								$d < $a ) {

							$fibonacciRetracement = $this->fibonacciRetracementbearish($key-1,$maxminC);
			
							$fibonacciExpansion = $this->fibonacciExpansionbearish($key-1,$maxminC);

							//busca en el array los registros comprendidos entre esas fechas 
							$rangedays = DB::table('data_us30')->whereBetween('Date',[$maxminC[$key - 3]['Date'] , $maxminC[$key - 2]['Date']])->get();
							
							$count = count($rangedays);
							
							//suma el campo volumen 
							$sumvolume = 0;

							for ($i=0; $i < $count ; $i++) { 
								$sumvolume = $rangedays[$i]->Volume	+ $sumvolume;
							}

							array_push($sabs, [	'SumVolume'=>$sumvolume,
												'High1' => $maxminC[$key - 3]['High'],
												'Low1' => $maxminC[$key - 2]['Low'],
												'High2' => $maxminC[$key - 1]['High'],
												'Low2' => $maxmin['Low'],

												'Dhigh1' => $maxminC[$key - 3]['Date'],
												'Dlow1' => $maxminC[$key - 2]['Date'],
												'Dhigh2' => $maxminC[$key - 1]['Date'],
												'Dlow2'=> $maxminC[$key]['Date'],
												'Type' => 'bearish',
												'Sab' => '--+-' , 
												'FiboR'=>$fibonacciRetracement,
												'FiboE'=>$fibonacciExpansion
												]);
						}
						// case --++
						if (    $a > $b and
								$b < $c and
								$c > $a and 
								$d < $c and
								$d > $b and
								$d < $a ) {

							$fibonacciRetracement = $this->fibonacciRetracementbearish($key-1,$maxminC);
			
							$fibonacciExpansion = $this->fibonacciExpansionbearish($key-1,$maxminC);

							//busca en el array los registros comprendidos entre esas fechas 
							$rangedays = DB::table('data_us30')->whereBetween('Date',[$maxminC[$key - 3]['Date'] , $maxminC[$key - 2]['Date']])->get();
							
							$count = count($rangedays);
							
							//suma el campo volumen 
							$sumvolume = 0;

							for ($i=0; $i < $count ; $i++) { 
								$sumvolume = $rangedays[$i]->Volume	+ $sumvolume;
							}

							array_push($sabs, [	'SumVolume'=>$sumvolume,
												'High1' => $maxminC[$key - 3]['High'],
												'Low1' => $maxminC[$key - 2]['Low'],
												'High2' => $maxminC[$key - 1]['High'],
												'Low2' => $maxmin['Low'],

												'Dhigh1' => $maxminC[$key - 3]['Date'],
												'Dlow1' => $maxminC[$key - 2]['Date'],
												'Dhigh2' => $maxminC[$key - 1]['Date'],
												'Dlow2'=> $maxminC[$key]['Date'],
												'Type' => 'bearish',
												'Sab' => '--++' , 
												'FiboR'=>$fibonacciRetracement,
												'FiboE'=>$fibonacciExpansion
												]);
						}

						// case --+++
						if (    $a > $b and
								$b < $c and
								$c > $a and 
								$d < $c and
								$d > $b and
								$d > $a ) {

							$fibonacciRetracement = $this->fibonacciRetracementbearish($key-1,$maxminC);
			
							$fibonacciExpansion = $this->fibonacciExpansionbearish($key-1,$maxminC);

							//busca en el array los registros comprendidos entre esas fechas 
							$rangedays = DB::table('data_us30')->whereBetween('Date',[$maxminC[$key - 3]['Date'] , $maxminC[$key - 2]['Date']])->get();
							
							$count = count($rangedays);
							
							//suma el campo volumen 
							$sumvolume = 0;

							for ($i=0; $i < $count ; $i++) { 
								$sumvolume = $rangedays[$i]->Volume	+ $sumvolume;
							}

							array_push($sabs, [	'SumVolume'=>$sumvolume,
												'High1' => $maxminC[$key - 3]['High'],
												'Low1' => $maxminC[$key - 2]['Low'],
												'High2' => $maxminC[$key - 1]['High'],
												'Low2' => $maxmin['Low'],

												'Dhigh1' => $maxminC[$key - 3]['Date'],
												'Dlow1' => $maxminC[$key - 2]['Date'],
												'Dhigh2' => $maxminC[$key - 1]['Date'],
												'Dlow2'=> $maxminC[$key]['Date'],
												'Type' => 'bearish',
												'Sab' => '--+++' , 
												'FiboR'=>$fibonacciRetracement,
												'FiboE'=>$fibonacciExpansion
												]);
						}
					
				}
				// array_push($sabs, [	'Date' => $maxmin['Date'], 
									
				// 					'Type' => 'diferent'
									
									
				// 					]);
			}
	  	
	  	}
	  	return $sabs;
	}


	// final find the SAB 

    
}
