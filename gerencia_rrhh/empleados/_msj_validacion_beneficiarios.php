 
<?php 
 
 ?> 
 <?php if ($_GET['op'] ==1 ): ?>
 	<div class="alert alert-warning">
 		<?php $porcentaje_faltante = 100-$_GET['pbe'];  ?>
		<strong> Atención</strong> Tiene pendiente <strong> <?php echo  $porcentaje_faltante  ?> %  </strong>la distribucion del  de beneficiarios .
	</div>
	<?php else: ?>
			<p></p>
 <?php endif ?>
