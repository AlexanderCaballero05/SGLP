<?php


function consulta_registro($_sorteo, $numero, $serie)
{
  require("../../conexion.php");


  $query_registro= mysqli_query($conn,"SELECT b.registro_inicial+$serie as registro FROM archivo_pagos_menor a, sorteos_menores_registros b where a.sorteo=$_sorteo and a.sorteo=b.id_sorteo and a.numero=b.numero and b.numero=$numero and a.serie= $serie");

        if (mysqli_num_rows($query_registro)>0)
        {
          while ($row_registro= mysqli_fetch_array($query_registro))
          {
                $registro=$row_registro['registro'];
          }
        }
        else
        {
          $registro=0;
        }
  return $registro;

}

function consulta_valor_premio($sorteo, $numero, $serie)
{

  require("../../conexion.php");

  $tipo_serie_numero='';
  $tipo_serie_serie='';
  $detalle_venta='';

  $monto_numero=0;
  $monto_numero_serie=0;
////// SELECCIONAR EL MONTO DE LAS SERIES
      $query_premios_numero=mysqli_query($conn,"SELECT a.numero_premiado_menor, a.monto, b.tipo_serie, a.numero_premiado_menor ganador from sorteos_menores_premios a, premios_menores b where a.numero_premiado_menor=$numero and a.sorteos_menores_id=$sorteo and a.premios_menores_id=b.id  and b.clasificacion = 'NUMERO'");
     if ($query_premios_numero === false){echo mysqli_error();}

    while ($fila_validacion_numero = mysqli_fetch_array($query_premios_numero))
    {
        $monto_numero=$fila_validacion_numero['monto'];
        $tipo_serie_numero=$fila_validacion_numero['tipo_serie'];
        $_ganador=$fila_validacion_numero['ganador'];
    }

    $query_numero_derecho=mysqli_query($conn,"SELECT numero_premiado_menor from sorteos_menores_premios where sorteos_menores_id=$sorteo and premios_menores_id=1");
    if (mysqli_num_rows($query_numero_derecho)>0)
    {
     while ( $row_derecho= mysqli_fetch_array($query_numero_derecho) )
     {
       $numero_derecho=$row_derecho['numero_premiado_menor'];
     }
    }
    else
    {
      echo "error en el numero de derecho".mysqli_error();
    }

    $query_numero_reves=mysqli_query($conn,"SELECT numero_premiado_menor from sorteos_menores_premios where sorteos_menores_id=$sorteo and premios_menores_id=3");
    if (mysqli_num_rows($query_numero_reves)>0)
    {
     while ( $row_reves= mysqli_fetch_array($query_numero_reves) )
     {
       $numero_reves=$row_reves['numero_premiado_menor'];
     }
    }
    else
    {
      echo "error en el numero de derecho".mysqli_error();
    }


  $query_premios_serie=mysqli_query($conn,"SELECT a.numero_premiado_menor, a.monto, b.tipo_serie from sorteos_menores_premios a, premios_menores b where a.numero_premiado_menor=$serie and a.sorteos_menores_id=$sorteo and a.premios_menores_id=b.id  and b.clasificacion = 'SERIE' ");
  if ($query_premios_serie === false) {echo mysqli_error(); }

  while ($fila_validacion_serie = mysqli_fetch_array($query_premios_serie))
  {
    $monto_numero_serie=$fila_validacion_serie['monto'];
    $tipo_serie_serie=$fila_validacion_serie['tipo_serie'];
  }

      if ($numero_derecho===$numero_reves)
      {
          $monto_numero=1100;

                if ($tipo_serie_serie=='GANADOR' and $tipo_serie_numero=='GANADOR' or  $tipo_serie_numero=='REVES' )
                  {
                    // echo "entra aqui en condicion 1";
                     $monto_total=$monto_numero+$monto_numero_serie;
                  }
                  else if ($tipo_serie_numero=='REVES' and $tipo_serie_numero=='GANADOR' or  $tipo_serie_numero=='REVES' )
                  {
                    // echo "entra aqui en condicion 3";
                     $monto_total=$monto_numero+$monto_numero_serie;
                  }
                   else if ($tipo_serie_numero=='REVES' and $tipo_serie_serie<>'GANADOR' and $tipo_serie_serie<>'REVES'  )
                  {
                     // echo "entra aqui en condicion 5";
                      $monto_total=$monto_numero;
                  }
                  else  if ($tipo_serie_numero=='GANADOR' and $tipo_serie_serie<>'GANADOR' and $tipo_serie_serie<>'REVES'  )
                  {
                    // echo "entra aqui en condicion 6";
                      $monto_total=$monto_numero;
                  }
                   else  if ($tipo_serie_numero<>'GANADOR' and $tipo_serie_numero<>'REVES' and $tipo_serie_serie=='GANADOR' or  $tipo_serie_serie=='REVES' )
                  {
                    //echo "estra aqui en condicion 7";
                      $monto_total=100;
                  }
                  else
                  {
                    $monto_total=0;
                  }

      }
       else
      {
            if ($tipo_serie_numero=='GANADOR' and $tipo_serie_serie=='GANADOR')
              {
                 $monto_total=$monto_numero+$monto_numero_serie;
              }
              else if ($tipo_serie_numero=='GANADOR' and $tipo_serie_serie=='REVES')
              {
                 $monto_total=$monto_numero+100;
              }
              else if ($tipo_serie_numero=='REVES' and $tipo_serie_serie=='REVES')
              {
                 $monto_total=$monto_numero+$monto_numero_serie;
              }
              else if ($tipo_serie_numero=='REVES' and $tipo_serie_serie=='GANADOR')
              {
                 $monto_total=$monto_numero+100;;
              }
              else if ($tipo_serie_numero=='REVES' and $tipo_serie_serie<>'GANADOR' and $tipo_serie_serie<>'REVES'  )
              {
                  $monto_total=$monto_numero;
              }
              else  if ($tipo_serie_numero=='GANADOR' and $tipo_serie_serie<>'GANADOR' and $tipo_serie_serie<>'REVES'  )
              {
                  $monto_total=$monto_numero;
              }
               else  if ($tipo_serie_numero<>'GANADOR' and $tipo_serie_numero<>'REVES' and $tipo_serie_serie=='GANADOR' or  $tipo_serie_serie=='REVES' )
              {
                  $monto_total=100;
              }
              else
              {
                $monto_total=0;
              }
      }
                if ($monto_total>30000)
              {
                $impto=$monto_total*0.10;
              }
              else
              {
                $impto=0;
              }

              $neto=$monto_total-$impto;

      return $monto_total;


}
?>
