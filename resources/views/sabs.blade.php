<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

       
    </head>
    <body>
        
    
      <div class="container-fluid">
        <div class="row">
          <div class="col ">
              <h6>Max Min Corregidos</h6>


              @foreach(array_reverse($sabs) as $sab)
                
                <h6>
                  {{$loop->index}} -
                  {{isset($sab['FiboR']) ? $sab['FiboR'] : ' '}} - 
                  {{isset($sab['FiboE']) ? $sab['FiboE'] : ' '}} - 
                  {{isset($sab['Type']) ? $sab['Type'] : ' '}} - 
                  {{isset($sab['Sab']) ? $sab['Sab'] : ' '}} -
                  {{isset($sab['SumVolume']) ? $sab['SumVolume'] : ' '}}
                </h6>
                {{-- <h6>alto 1: {{$sab['Dhigh1']}} - {{$sab['High1']}} <br>
                    alto 2: {{$sab['Dhigh2']}} - {{$sab['High2']}} <br>
                    bajo 1: {{$sab['Dlow1']}} - {{$sab['Low1']}} <br>
                    bajo 2: {{$sab['Dlow2']}} - {{$sab['Low2']}} <br>  
                </h6>   --}}
                <hr>
               
                

              @endforeach
          </div>
          <div class="col ">
             
          </div>
          
        </div>
          </h6>
      </div>
             


    </body>
</html>
