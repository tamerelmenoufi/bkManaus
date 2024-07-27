<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

?>


<div class="card m-3">
  <h5 class="card-header">Estoque</h5>
  <div class="card-body">
    <h5 class="card-title">Consulta de Estoque por empresa</h5>
    <p class="card-text">Selecione uma empresa para verificação do estque de produtos.</p>
    <select name="empresa" id="empresa" class="select-form">
        <option value="">:: Selecione uma empresa ::</option>
        <?php
            $q = "select * from empresas where tipo = 'g' order by nome asc";
            $r = mysqli_query($con, $q);
            while($e = mysqli_fetch_object($r)){
        ?>        
        <option value="<?=$e->codigo?>"><?=$e->nome?></option>
        <?php
            }
        ?>  
    </select>
    <a href="#" class="btn btn-primary">Go somewhere</a>
  </div>
</div>



<script>
  $(function(){
      Carregando('none');
  })
</script>