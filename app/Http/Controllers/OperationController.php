<?php

namespace App\Http\Controllers;

use App\Http\Controllers\TradingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class OperationController extends TradingController
{
   
    public function bullish()
	{
		$days = DB::table('data_us30')->orderBy('Date','Asc')->get();
		
		$long = count($days);
		
	
		$maxmins = $this->bucle($days, $long);


		
		$maxminC = $this->corrective($maxmins);
		$long = count($maxminC);

		$maxminF = $this->Vol_FR_FE_bullish($maxminC,$long);
		

	
		return view('bullish', compact('maxminF'));
	}


	public function bearish()
	{
		$days = DB::table('data_us30')->orderBy('Date','Asc')->get();
		
		$long = count($days);
		
	
		$maxmins = $this->bucle($days, $long);
		
		$maxminC = $this->corrective($maxmins);
		$long = count($maxminC);

		$maxminF = $this->Vol_FR_FE_bearish($maxminC,$long);
		

	
		return view('bearish', compact('maxminF'));
	}

	public function sab()
	{
		$days = DB::table('data_us30')->orderBy('Date','Asc')->get();
		
		$long = count($days);
		
	
		$maxmins = $this->bucle($days, $long);
		
		$maxminC = $this->corrective($maxmins);

		$long = count($days);
		
		$sabs = $this->getsab($maxminC,$long);
		
		return view('sabs', compact('sabs'));
	}

	public function getdata()
	{
		$data = Http::get('https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&symbol=AAPL&outputsize=full&apikey=TCYRO0UHXCBCIHWU');
	
		$maxmin = $this->saveddbb($data['Time Series (Daily)']);
	
	}

	public function saveddbb($days)
	{
		
		$minmax = [];

		$i = 0;

		foreach (array_reverse($days) as $key => $day) {
			
			
			
			DB::table('data_aapl')->insert([	'id' => $i, 
											'Date' => $key,
											'Open' => $day['1. open'],
											'High' => $day['2. high'],
											'Low' => $day['3. low'],
											'Close' => $day['4. close'],
											'Volume' => $day['5. volume']

											]);
			
			$i = $i + 1;
		}

		
	}
	
}
