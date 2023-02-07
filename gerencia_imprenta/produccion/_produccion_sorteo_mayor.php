 
<?php
  	 
	 $_loteria=$_GET['loteria'];
 

	 if ($_loteria==1) 
	 {	 
	 $requete = "SELECT id, no_sorteo_may as no_sorteo FROM sorteos_mayores order by no_sorteo_may desc limit 5"; 	 
	 }
	 else if ($_loteria==2) 
	 {
	 $requete = "SELECT id, no_sorteo_men as no_sorteo FROM sorteos_menores order by no_sorteo_men desc limit 5"; 	
	 }
	

	// 
	 
	 try {
	 $bdd = new PDO('mysql:host=localhost;dbname=pani', 'root', 'softlotpani**');
	// echo "<script>alert('conectado');</script>";
	 }
	  catch(Exception $e) 
	 {
	  exit('Unable to connect to database.');
	 }
	 // Execute the query
	 $resultat = $bdd->query($requete) or die(print_r($bdd->errorInfo()));
	 //print_r($resultat);
    //print_r($resultat->fetchAll(PDO::FETCH_ASSOC));
	 $returned_results = $resultat->fetchAll(PDO::FETCH_ASSOC);
	 // var_dump($returned_results);
	 echo utf8_encode('<option value="">Seleccione Uno</option>');
	 foreach($returned_results as $key=>$result) {
			 //echo "<pre>"; var_dump($result); echo "</pre>";
			 echo utf8_encode('<option value="'.$result['id'].'">'.$result['no_sorteo'].'</option>');
			// echo "<script>alert(".$result['id'].");</script>";
			 //echo $result['nombre_completo']."<br/>";
		}
 
?>
 
