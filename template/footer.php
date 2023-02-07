<!-- &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& FOOTER &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& -->
<!-- &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& FOOTER &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& -->

</main>

<footer id="footer-info" class="page-footer dark">
<div class="container">
<div class="row">
<div class="col">
<h5>Sobre el PANI</h5>
<ul>
<li><a href="http://www.pani.gob.hn/" target="_blank">Pagina oficial</a></li>
<li><a href="https://portalunico.iaip.gob.hn/portal/index.php?portal=359" target="_blank">Portal de transparencia</a></li>
</ul>
</div>
<div class="col">
<h5>Soporte</h5>
<ul>
<li><a href="#">Informatica</a></li>
<li><a href="#">Ticket de soporte</a></li>
<?php
if(isset($_SESSION['logged'])){
?>
<li><a  href="" data-toggle="modal" data-target="#modal-manuales">Manual de usuario</a></li>
<?php
}
?>

</ul>
</div>
<div class="col">
<h5>Legal</h5>
<ul>
<li><a href="#">Terminos y servicios&nbsp;</a></li>
<li><a href="#">Politicas de uso</a></li>
<li><a href="#">Politicas de privacidad</a></li>
</ul>
</div>
</div>
</div>
<div class="footer-copyright">
<p>© 2018 Copyright SGLP Unidad de Informatica PANI</p>
</div>
</footer>

<!-- &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& FOOTER &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& -->
<!-- &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& FOOTER &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& -->




</body>




<!-- &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&$$$$$$$$$$$$$$$$$$$&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& -->
<!-- &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&$$$$$$$$$$$$$$$$$$$&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& -->
<!-- &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& SECCION MANUALES &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& -->
<!-- &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& SECCION MANUALES &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& -->


<section id="section-manuales">
<div class="modal fade" role="dialog" tabindex="-1" id="modal-manuales">
<div class="modal-dialog" role="document">
<div class="modal-content">
<div class="modal-header bg-secondary text-white" >
<h4 class="text-center modal-title" style="width:100%;">MANUALES DE USUARIO</h4><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></div>
<div class="modal-body" style="padding-bottom:0px;">


<ul class="list-group">

<?php 
if (isset($_SESSION['gerencia_loteria'])) {
echo '  <li class="list-group-item"><a target="_blank" href="template/manuales/MANUAL_GL.pdf">GERENCIA DE COMERCIALIZACION</a> </li>';
echo '  <li class="list-group-item"><a target="_blank" href="template/manuales/MANUAL_VENTAS.pdf">REGISTRO DE VENTAS</a> </li>';
}

if (isset($_SESSION['gerencia_imprenta'])) {
echo '  <li class="list-group-item"><a target="_blank" href="template/manuales/MANUAL_CC.pdf">CONTROL DE CALIDAD</a> </li>
  <li class="list-group-item"><a target="_blank" href="template/manuales/MANUAL_CP.pdf">CONTROL DE PRODUCCION</a> </li>';
}


?>


</ul>

</div>
<div class="modal-footer">
<div class="container" id="respuesta-consulta-premio"></div>
</div>
</div>
</div>
</div>
</section>


<!-- &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& SECCION MANUALES &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& -->
<!-- &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& SECCION MANUALES &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& -->
<!-- &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&$$$$$$$$$$$$$$$$$$$&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& -->
<!-- &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&$$$$$$$$$$$$$$$$$$$&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& -->


</html>