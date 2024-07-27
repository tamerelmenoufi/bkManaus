<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    if($_POST['empresa']) $_SESSION['estoque']['empresa'] = $_POST['empresa'];

?>


<div class="card m-3">
  <h5 class="card-header">Estoque</h5>
  <div class="card-body">
    <h5 class="card-title">Consulta de Estoque por empresa</h5>
    <p class="card-text">Selecione uma empresa para verificação do estque de produtos.</p>
    <select id="empresa" class="form-select mb-2">
        <option value="">:: Selecione uma empresa ::</option>
        <?php
            $q = "select * from empresas where tipo = 'g' order by nome asc";
            $r = mysqli_query($con, $q);
            while($e = mysqli_fetch_object($r)){
        ?>        
        <option value="<?=$e->codigo?>" <?=(($_SESSION['estoque']['empresa'] == $e->codigo)?'selected':false)?> ><?=$e->nome?> - <?=$e->cnpj?></option>
        <?php
            }
        ?>  
    </select>
    <button class="btn btn-primary" listaEstoque>Listar Produtos</button>
  </div>
</div>



<script>
  $(function(){
      Carregando('none');

      $("button[listaEstoque]").click(function(){
        empresa = $("#empresa").val();
        if(!empresa){
            $.alert({
                type:"red",
                title:"Alerta de Pendências",
                content:"Selecione uma empresa para listar o estoque disponível"
            })
            return false
        }
        Carregando();
        $.ajax({
            url:"src/estoque/empresas/index.php",
            type:"POST",
            data:{
                empresa
            },
            success:function(dados){
                $("#paginaHome").html(dados);
            }
        })
      })
  })
</script>