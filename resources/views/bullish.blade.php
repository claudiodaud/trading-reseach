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


              @foreach(array_reverse($maxminF) as $maxmin)
                <h6>
                  {{$loop->index}} - {{$maxmin['Date']}} - {{ isset($maxmin['High']) ? 'Maximo '.$maxmin['High'] : 'Minimo '.$maxmin['Low'] }} 
                </h6>
                

              @endforeach
          </div>
          <div class="col ">
              <h6>volumen del Movimiento + % de Retroceso Fibonacci</h6>
              @foreach(array_reverse($maxminF) as $maxmin)
                <h6 class="">
                  {{isset($maxmin[0]['SumVolume']) ? $maxmin[0]['SumVolume'] : ' '}} - {{isset($maxmin[0]['FiboR']) ? $maxmin[0]['FiboR'] : ' '}} - 
                  {{isset($maxmin[0]['FiboE']) ? $maxmin[0]['FiboE'] : ' '}}
                </h6>

              @endforeach
          </div>
          
        </div>
          </h6>
      </div>
             


    </body>
</html>
