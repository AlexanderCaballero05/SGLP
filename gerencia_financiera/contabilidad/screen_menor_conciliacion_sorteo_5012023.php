<?php 
require('../../template/header.php'); 
$usuario_id=$_SESSION['usuario'] ;

    function diferenciaDias($inicio, $fin)
    {
        $inicio = strtotime($inicio);
        $fin = strtotime($fin);
        $dif = $fin - $inicio;
        $diasFalt = (( ( $dif / 60 ) / 60 ) / 24);
        return ceil($diasFalt);
    }

$conn2 = oci_connect('cide', 'pani2017', '(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(HOST=192.168.15.102)(PORT=1521)))(CONNECT_DATA=(SID=dbpani)(SERVER = DEDICATED)(SERVICE_NAME = DBPANITG)))');

if ($conn2==FALSE) 
{
            $e = oci_error();
            echo $e['message']."<br>";
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
            exit;
}

?>
<style type="text/css" media="print">

@page {    size:  landscape;  } 
th, td { padding-bottom: 0px;   border-spacing: 0; font-family: Arial; font-size: 09pt; } 
  
</style> 
<style type="text/css">

.div_wait 
{
  display: none;
  position: fixed;
  left: 0px;
  top: 0px;
  width: 100%;
  height: 100%;
  z-index: 9999;
  background-color: black;
  opacity:0.5;
  background: url(../../template/images/wait.gif) center no-repeat #fff;
}

@media print {
        #no_print { display: none; }  
        #printOnly {
       display : block;
    }       
 }
@media screen {
        #printOnly { display: none; } 
    }       
 }
</style>
<form method="post" id="_revision_premios"  class="" name="_revision_premios">
<div id='div_wait'></div>

<section id='no_print'>
	<ul class="nav nav-tabs" id="no_print">
		 <li class="nav-item">
		    <a class="nav-link" href="./screen_mayor_conciliacion_sorteo.php">Pagos de Premios de Lotería Mayor</a>		    
		  </li>
		  <li class="nav-item">
		    <a style="background-color:#ededed;" class="nav-link active" href="#">Pagos de Premios de Lotería Menor</a>
		  </li>
	</ul>	
</section>

<section style="text-align: center; background-color:#ededed; padding-top: 10px;"><h3>Conciliación de Sorteos de Lotería Menor </h3> <br></section>

<section>
<a style = "width:100%" id="non-printable"  class="btn btn-secondary" role="button" data-toggle="collapse" href="#collapse1" aria-expanded="false" aria-controls="collapse"> SELECCIÓN DE PARAMETROS  <i class="far fa-hand-point-down fa-lg"></i></a>
    <div class="collapse " id="collapse1" align="center">
      <div class="card">
        <div class="card card-body" id="non-printable" >
          <div class="input-group" style="margin:10px 0px 10px 0px; width: 100%" align="center">
            <div class = "input-group-prepend"><span  class="input-group-text"> <i class="far fa-calendar-alt"></i> &nbsp;  Sorteo Inicial: </span></div>
            <select  id="sorteo_inicial" name="sorteo_inicial" class="form-control" required="true">
            	<option value=""> Seleccione Uno ... </option>
            	<?php 
            		$result_query_sorteo= mysqli_query($conn, "SELECT id, fecha_sorteo ,ADDDATE(fecha_sorteo, INTERVAL 45 DAY) vencimiento, (45 - DATEDIFF(CURRENT_DATE, fecha_sorteo)) dias FROM sorteos_menores where id> 3110 order by id desc");

						if (mysqli_num_rows($result_query_sorteo)>0 ) 
						{
							while($row_sorteos=mysqli_fetch_array($result_query_sorteo,MYSQLI_ASSOC))  
							{
								echo "<option value = '".$row_sorteos['id']."'>No.".$row_sorteos['id']." | Fecha ".$row_sorteos['fecha_sorteo']." | Vence ".$row_sorteos['vencimiento']." | - ".$row_sorteos['dias']." días</option>" ;	
							}
						}
						else
						{
							echo mysqli_error();
						}	
            	 ?>            	
            </select>
            <div class = "input-group-prepend"  style="margin-left: 10px;"><span  class="input-group-text"> <i class="far fa-calendar-alt"></i> &nbsp;  Sorteo Final: </span></div>
            <select  id="sorteo_final" name="sorteo_final" class="form-control" required="true">
            	<option value=""> Seleccione Uno ... </option>
            	<?php 
            		$result_query_sorteo_final= mysqli_query($conn,"SELECT id, fecha_sorteo ,ADDDATE(fecha_sorteo, INTERVAL 45 DAY) vencimiento, (45 - DATEDIFF(CURRENT_DATE, fecha_sorteo)) dias FROM sorteos_menores where id> 3220 order by id desc");

						if (mysqli_num_rows($result_query_sorteo_final)>0 ) 
						{
							while($row_sorteos_final=mysqli_fetch_array($result_query_sorteo_final,MYSQLI_ASSOC))  
							{
								echo "<option value = '".$row_sorteos_final['id']."'>No.".$row_sorteos_final['id']." | Fecha ".$row_sorteos_final['fecha_sorteo']." | Vence ".$row_sorteos_final['vencimiento']." | - ".$row_sorteos_final['dias']." días</option>" ;	
							}
						}
						else
						{
							echo mysqli_error();
						}
            	 ?>            	
            </select>                  
            <button type="submit" name="seleccionar" style="margin-left: 10px;" class="btn btn-primary" value = "Seleccionar">  Seleccionar &nbsp;<i class="fas fa-search fa-lg"></i></button>
          </div>
        </div>
      </div>
    </div> 
 </section>
 <hr>
 <section>
 <?php     
    if (isset($_POST['seleccionar']))
    { 
 		?>
 		<script type="text/javascript">
 			 $(".div_wait").fadeIn("fast");  
 		</script>
 		<?php 
    	 $sorteo_inicial=$_POST['sorteo_inicial'];  
    	 $sorteo_final=$_POST['sorteo_final']; 

    	 if ($sorteo_final >= $sorteo_inicial) 
    	 {
    	  	  	# code...
    	 
 
    	 ?>
    	       	<div class="table-responsive">
    	       		<table  class="table table-hover table-sm table-bordered">
    	       			<thead><tr align="center">
    	       						<th></th>
    	       						<th></th>
    	       						<th></th>
    	       						<th></th>
		    	       				<th colspan="3">Provisión</th>
		    	       				<th id="no_print"></th>
		    	       				<th colspan="3">Pagado</th>  
		    	       				<th id="no_print"></th>  	       				
		    	       				<th colspan="2">Recepcionado</th>
		    	       				<th id="no_print"></th>
		    	       				<th colspan="2">Pendiente Recepcionar</th>
		    	       				<th id="no_print"></th>
		    	       				<th colspan="2">Revisado</th>
		    	       				<th id="no_print"></th>
		    	       				<th colspan="3">Debitos</th>
		    	       				<th id="no_print"></th>		    	       				 
		    	       				<th colspan="3">Pendiente || Caduco</th>	
		    	       				<th id="no_print"></th>	    	       				
		    	       			</tr> 
		    	       			<tr><th>Sorteo</th>
		    	       				<th>Fecha Sorteo</th>
		    	       				<th>Fecha Vencimiento</th>
		    	       				<th>Dias</th>
		    	       				<th>Cant.</th>
		    	       				<th>Neto</th>
		    	       				<th>Neto ERP</th>
		    	       				<th id="no_print"></th>
		    	       				<th>Cant.</th>
		    	       				<th>Neto</th> 
		    	       				<th>Neto ERP</th>
		    	       				<th id="no_print"></th>
		    	       				<th>Cant.</th>
		    	       				<th>Neto</th> 
		    	       				<th id="no_print"></th>
		    	       				<th>Cant.</th>
		    	       				<th>Neto</th>		    	       				
		    	       			    <th id="no_print"></th>
		    	       				<th>Cant.</th>
		    	       				<th>Neto</th>  
		    	       				<th id="no_print"></th>     				
		    	       				<th>Cant.</th>
		    	       				<th>Neto</th>
		    	       				<th>ERP</th>  
		    	       				<th id="no_print"></th>
		    	       				<th>Cant.</th>
		    	       				<th>Neto</th> 
		    	       				<th>ERP</th> 
		    	       				<th id="no_print"></th>
		    	       			</tr>   	       			
    	       			</thead>
    	       			<tbody>    	       		 
			    	    <?php		
			    	    		$billetes_pendientes=0;							        $neto_pendiente                        = 0;
								$acumulado_billetes_compromiso       		= 0;  		$acumulado_neto_compromiso			   = 0; 
								$acumulado_billetes_pagado           		= 0;  		$acumulado_neto_pagado				   = 0;
								$acumulado_billetes_recepcionado     		= 0;  		$acumulado_neto_recepcionado 		   = 0;
								$acumulado_billetes_pendiente_recepcionado  = 0;        $acumulado_neto_pendiente_recepcionado = 0;
								$acumulado_billetes_revisados    			= 0;        $acumulado_neto_revisado 			   = 0;								
								$acumulado_billetes_debitos	    			= 0;		$acumulado_neto_debito	         	   = 0;
								$acumulado_billetes_pendientes    			= 0;        $acumulado_neto_pendiente       	   = 0;
								$acumulado_neto_compromiso_erp				= 0;
								$acumulado_neto_pendiente_erp 				= 0;


			    	  			while ($sorteo_inicial <= $sorteo_final) 
			    	  			{
			    	  				$query_info_sorteo=mysqli_query($conn, "SELECT fecha_sorteo, vencimiento_sorteo, (45 - DATEDIFF(CURRENT_DATE, fecha_sorteo)) dias FROM sorteos_menores WHERE id=$sorteo_inicial");
			    	  				$info_sorteo               = mysqli_fetch_object($query_info_sorteo);
									$fecha_sorteo              = $info_sorteo->fecha_sorteo;
									$vencimiento_sorteo        = $info_sorteo->vencimiento_sorteo;
									$dias        			   = $info_sorteo->dias;

			    	  				$query_compromiso=mysqli_query($conn, "SELECT COUNT(*) conteo, sum(netopayment) neto_compromiso from archivo_pagos_menor where sorteo=$sorteo_inicial");

			    	  				$info_compromiso        = mysqli_fetch_object($query_compromiso);
									$billetes_compromiso    = $info_compromiso->conteo;
									$neto_compromiso        = $info_compromiso->neto_compromiso;

									$query_oracle="SELECT SORTEO, CANTIDAD_BILLETES, TOTAL_PAGAR, IMPTO_PAGAR, NETO_PAGAR FROM LOT_PROVISION_PAGOS WHERE PRODUCTO = 2 AND SORTEO = $sorteo_inicial ORDER BY SORTEO ASC";
		                            
		                               $stid = oci_parse($conn2, $query_oracle );                       
		                               $rc=oci_execute($stid); 

		                               $neto_contabilizado_obligado=0;
		                              if(!$rc)
		                              {
		                                $bandera_erp = 1;
		                                $e=oci_error($stid);
		                                var_dump($e);
		                              }
		                              else
		                              { 
		                                  while ($row_oracle = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) 
		                                  {
		                                    $neto_contabilizado_obligado = $row_oracle['NETO_PAGAR'];                                                
		                                  }
		                              }
		 
									$query_pagado=mysqli_query($conn, "SELECT COUNT(*) conteo_pagado, sum(neto) neto_pagado from menor_pagos_detalle a, menor_pagos_recibos b where a.transactioncode = b.transactioncode and sorteo=$sorteo_inicial and a.transactionstate in (1,3)");

			    	  				$info_pagado        = mysqli_fetch_object($query_pagado);
									$billetes_pagado    = $info_pagado->conteo_pagado;
									$neto_pagado        = $info_pagado->neto_pagado;

									$query_oracle_pagado="SELECT  SUM(CANTIDAD_BILLETES), SUM(TOTAL_PAGADO), SUM(IMPTO_PAGADO) , SUM(NETO_PAGADO) NETO_PAGADO FROM LOT_PAGOS_DIARIOS WHERE PRODUCTO=2 AND SORTEO = $sorteo_inicial ";
		                            
		                               $stid_pagado = oci_parse($conn2, $query_oracle_pagado );                       
		                               $rc_pagado=oci_execute($stid_pagado); 

		                               $neto_contabilizado_pagado=0;
		                              if(!$rc_pagado)
		                              {
		                                $bandera_erp_pagado = 1;
		                                $e_pagado=oci_error($stid_pagado);
		                                var_dump($e_pagado);
		                              }
		                              else
		                              { 
		                                  while ($row_oracle_pagado = oci_fetch_array($stid_pagado, OCI_ASSOC+OCI_RETURN_NULLS)) 
		                                  {
		                                    $neto_contabilizado_pagado = $row_oracle_pagado['NETO_PAGADO'];                                                
		                                  }
		                              }

									$query_recepcionado=mysqli_query($conn, "SELECT COUNT(*) conteo_recepcionado, sum(neto) neto_recepcionado from menor_pagos_detalle a, menor_pagos_recibos b where a.transactioncode = b.transactioncode and sorteo=$sorteo_inicial and a.transactionstate in (1,3) and fecha_recepcion_banco is not null");

			    	  				$info_recepcionado        = mysqli_fetch_object($query_recepcionado);
									$billetes_recepcionado    = $info_recepcionado->conteo_recepcionado;
									$neto_recepcionado        = $info_recepcionado->neto_recepcionado;

									$query_pendiente_recepcionado=mysqli_query($conn, "SELECT COUNT(*) conteo_pendiente_recepcionado, sum(neto) neto_pendiente_recepcionado from menor_pagos_detalle a, menor_pagos_recibos b where a.transactioncode = b.transactioncode and sorteo=$sorteo_inicial and a.transactionstate in (1,3) and fecha_recepcion_banco is null");

			    	  				$info_pendiente_recepcionado  = mysqli_fetch_object($query_pendiente_recepcionado);
									$billetes_pendiente_recepcionado    = $info_pendiente_recepcionado->conteo_pendiente_recepcionado;
									$neto_pendiente_recepcionado        = $info_pendiente_recepcionado->neto_pendiente_recepcionado;



									$query_revisado=mysqli_query($conn, "SELECT COUNT(*) conteo_revisado, sum(neto) neto_revisado from menor_pagos_detalle a, menor_pagos_recibos b where a.transactioncode = b.transactioncode and sorteo=$sorteo_inicial and a.transactionstate in (1,3) and estado_revision=1");

			    	  				$info_revisado         = mysqli_fetch_object($query_revisado);
									$billetes_revisados    = $info_revisado->conteo_revisado;
									$neto_revisado         = $info_revisado->neto_revisado;
			    				

 
									$query_revisado_debitos=mysqli_query($conn, "SELECT count(*) conteo_debitos, sum(neto) neto_debitos FROM rp_notas_credito_debito_menor WHERE sorteo=$sorteo_inicial");

			    	  				$info_debitos  		   = mysqli_fetch_object($query_revisado_debitos);
									$billetes_debitos      = $info_debitos->conteo_debitos;
									$neto_debito           = $info_debitos->neto_debitos;
									 
									$billetes_pendientes=$billetes_compromiso -$billetes_pagado ;
									$neto_pendiente= $neto_compromiso - $neto_pagado ;

									$query_oracle2="SELECT SORTEO, CANTIDAD_BILLETES, TOTAL_CADUCO, IMPTO_CADUCO, NETO_CADUCO FROM LOT_CADUCOS WHERE PRODUCTO = 2  AND SORTEO = $sorteo_inicial ORDER BY SORTEO ASC";		                            	
		                            $stid2 = oci_parse($conn2, $query_oracle2 );                       
		                            $rc2=oci_execute($stid2); 

		                            $neto_contabilizado_caduco=0;
		                            if(!$rc2)
		                            {
		                              $bandera_erp2 = 1;
		                              $e2=oci_error($stid2);
		                              var_dump($e2);
		                            }
		                            else
		                            { 
		                                while ($row_oracle2 = oci_fetch_array($stid2, OCI_ASSOC+OCI_RETURN_NULLS)) 
		                                {
		                                    $neto_contabilizado_caduco = $row_oracle2['NETO_CADUCO'];                                                
		                                }
		                            }

									$acumulado_billetes_compromiso       		   = $acumulado_billetes_compromiso       			+ $billetes_compromiso;
									$acumulado_neto_compromiso           		   = $acumulado_neto_compromiso           			+ $neto_compromiso;
									$acumulado_neto_compromiso_erp         		   = $acumulado_neto_compromiso_erp        			+ $neto_contabilizado_obligado;

									$acumulado_billetes_pagado           		   = $acumulado_billetes_pagado           			+ $billetes_pagado;
									$acumulado_neto_pagado               		   = $acumulado_neto_pagado               			+ $neto_pagado;
									$acumulado_neto_pagado_erp                     = $acumulado_neto_pagado_erp                     + $neto_contabilizado_pagado;

									$acumulado_billetes_recepcionado     		   = $acumulado_billetes_recepcionado     			+ $billetes_recepcionado;
									$acumulado_neto_recepcionado       			   = $acumulado_neto_recepcionado         			+ $neto_recepcionado;

									$acumulado_billetes_pendiente_recepcionado     = $acumulado_billetes_pendiente_recepcionado     + $billetes_pendiente_recepcionado;
									$acumulado_neto_pendiente_recepcionado         = $acumulado_neto_pendiente_recepcionado         + $neto_pendiente_recepcionado;

									$acumulado_billetes_revisados    			   = $acumulado_billetes_revisados        			+ $billetes_revisados;
									$acumulado_neto_revisado         			   = $acumulado_neto_revisado                       + $neto_revisado;

									$acumulado_billetes_debitos	    			   = $acumulado_billetes_debitos        			+ $billetes_debitos;
									$acumulado_neto_debito	         			   = $acumulado_neto_debito                         + $neto_debito;

									$acumulado_billetes_pendientes    			   = $acumulado_billetes_pendientes        			+ $billetes_pendientes;
									$acumulado_neto_pendiente         			   = $acumulado_neto_pendiente                      + $neto_pendiente;
									$acumulado_neto_pendiente_erp         		   = $acumulado_neto_pendiente_erp                  + $neto_contabilizado_caduco;


			    	  				  echo "<tr><td align='center'>".$sorteo_inicial."</td> 
			    	  				  			<td align='center'>".$fecha_sorteo."</td> 
			    	  				  			<td align='center'>".$vencimiento_sorteo."</td> 
			    	  				  			<td align='center'>".$dias."</td> 
					    	       			  	<td align='center'>".$billetes_compromiso."</td> 
					    	       			  	<td align='right'>".number_format($neto_compromiso,2)."</td> 
					    	       			  	<td align='right'>".number_format($neto_contabilizado_obligado,2)."</td> 
					    	       			  	<td id='no_print'>
							    	       		  	 <a role='button' target='_blank' href='../revision_premios/screen_menor_obligacion_sorteo_detalle.php?sorteo=".$sorteo_inicial."' class='btn btn-primary btn-sm text-white'> <i class='far fa-eye'></i>  Ver  <a>
							    	       		 </td>
					    	       			  	<td align='center'>".$billetes_pagado."</td> 
					    	       			  	<td align='right'>".number_format($neto_pagado,2)."</td> 
					    	       			  	<td align='right'>".number_format($neto_contabilizado_pagado,2)."</td> 
					    	       			  	<td id='no_print'>
							    	       		  	 <a role='button' target='_blank' href='../revision_premios/screen_mayor_notas_sorteo_detalle.php?sorteo=".$sorteo_inicial."' class='btn btn-primary btn-sm text-white'> <i class='far fa-eye'></i>  Ver  <a>
							    	       		 </td>
					    	       			  	<td align='center'>".$billetes_recepcionado."</td> 
					    	       			  	<td align='right'>".number_format($neto_recepcionado,2)."</td> 
					    	       			  	<td id='no_print'>
							    	       		  	 <a role='button' target='_blank' href='../revision_premios/_rp_menor_recepcionado_sorteo.php?sorteo=".$sorteo_inicial."' class='btn btn-primary btn-sm text-white'> <i class='far fa-eye'></i>  Ver  <a>
							    	       		 </td>
					    	       			  	<td align='center'>".$billetes_pendiente_recepcionado."</td> 					    	       			  
					    	       			  	<td align='right'>".number_format($neto_pendiente_recepcionado,2)."</td> 
					    	       			  	<td align='center' id='no_print'>
					    	       			  	  <a role='button' target='_blank' href='../revision_premios/_rp_menor_pendiente_recepcionar_sorteo.php?sorteo=".$sorteo_inicial."' class='btn btn-primary btn-sm text-white'> <i class='far fa-eye'></i>  Ver  <a>
					    	       			  	</td>
					    	       			  	<td align='center'>".$billetes_revisados."</td> 
					    	       			  	<td align='right'>".number_format($neto_revisado,2)."</td> 
					    	       			  	<td id='no_print'>
							    	       		  	 <a role='button' target='_blank' href='../revision_premios/screen_mayor_notas_sorteo_detalle.php?sorteo=".$sorteo_inicial."' class='btn btn-primary btn-sm text-white'> <i class='far fa-eye'></i>  Ver  <a>
							    	       		 </td>
					    	       			  	<td align='center'>".$billetes_debitos."</td> 
					    	       			  	<td align='right'>".number_format($neto_debito,2)."</td> 
					    	       			  	<td align='right'>".number_format($neto_debito,2)."</td> 
					    	       			  	<td id='no_print'>
							    	       		  	 <a role='button' target='_blank' href='../revision_premios/screen_mayor_notas_sorteo_detalle.php?sorteo=".$sorteo_inicial."' class='btn btn-primary btn-sm text-white'> <i class='far fa-eye'></i>  Ver  <a>
							    	       		 </td>
					    	       			  	<td align='center'>".$billetes_pendientes."</td> 
					    	       			  	<td align='right'>".number_format($neto_pendiente,2)."</td> 
					    	       			  	<td align='right'>".number_format($neto_contabilizado_caduco,2)."</td> 
					    	       			  	<td id='no_print'>
							    	       		  	 <a role='button' target='_blank' href='../revision_premios/screen_mayor_notas_sorteo_detalle.php?sorteo=".$sorteo_inicial."' class='btn btn-primary btn-sm text-white'> <i class='far fa-eye'></i>  Ver  <a>
							    	       		 </td>
					    	       		</tr>";
			    	  				$sorteo_inicial++;
			    	 			}	
			    	 			echo "<tr class='table-success'><td colspan='4' align='center'> Totales</td>
			    	 					  <td align='center'>".number_format($acumulado_billetes_compromiso)."</td>	
			    	 					  <td align='right'>".number_format($acumulado_neto_compromiso,2)."</td>
			    	 					  <td align='right'>".number_format($acumulado_neto_compromiso_erp,2)."</td>
			    	 					  <td id='no_print'></td>
			    	 					  <td align='center'>".number_format($acumulado_billetes_pagado)."</td>	
			    	 					  <td align='right'>".number_format($acumulado_neto_pagado,2)."</td>
			    	 					  <td align='right'>".number_format($acumulado_neto_pagado_erp,2)."</td>
			    	 					  <td id='no_print'></td>
			    	 					  <td align='center'>".number_format($acumulado_billetes_recepcionado)."</td>	
			    	 					  <td align='right'>".number_format($acumulado_neto_recepcionado,2)."</td>
			    	 					  <td id='no_print'></td>
			    	 					  <td align='center'>".number_format($acumulado_billetes_pendiente_recepcionado)."</td>				    	 					
			    	 					  <td align='right'>".number_format($acumulado_neto_pendiente_recepcionado,2)."</td>
			    	 					  <td align='right' id='no_print'></td>
			    	 					  <td align='center'>".number_format($acumulado_billetes_revisados)."</td> 
					    	       		  <td align='right'>".number_format($acumulado_neto_revisado,2)."</td>
					    	       		  <td id='no_print'></td>
					    	       		  <td align='center'>".number_format($acumulado_billetes_debitos)."</td> 
					    	       		  <td align='right'>".number_format($acumulado_neto_debito,2)."</td>  
					    	       		  <td align='right'>".number_format($acumulado_neto_debito,2)."</td>  
					    	       		  <td id='no_print'></td>
					    	       		  <td align='center'>".number_format($acumulado_billetes_pendientes)."</td> 
					    	       		  <td align='right'>".number_format($acumulado_neto_pendiente,2)."</td>	
					    	       		  <td align='right'>".number_format($acumulado_neto_pendiente_erp,2)."</td>	
					    	       		  <td id='no_print'></td>
			    	 				  </tr>";	
			    	    ?>	
    	       			</tbody>
    	       		</table>    	       			
    	       		</div>

    	       <?php 
    	       	 } 	  
    	       	 else
    	       	{
    	       		echo "<div class='alert alert-danger'> El sorteo final debe ser mayor al sorteo final</div>";
    	       	}
    	        ?>
    	       		<script type="text/javascript">
			 			 $(".div_wait").fadeOut("fast");  
			 		</script>
    	    <?php

    }	
  ?>
 	
 </section>
<script type="text/javascript">
	$(".div_wait").fadeOut("fast");  
</script>
