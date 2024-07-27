<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    if($_POST['empresa']) $_SESSION['estoque']['empresa'] = $_POST['empresa'];
?>

<div class="list-group">
<?php

    $query = "select * from empresas where tipo = 'g' order by nome asc";
    $result = mysqli_query($con, $query);
    while($d = mysqli_fetch_object($result)){
?>
  <button destinataria="<?=$d->codigo?>" type="button" class="list-group-item list-group-item-action"><?=$d->nome?> - <?=$d->cnpj?></button>
<?php
    }
?>
</div>

<script>
    $(function(){
        Carregando('none')

        $("button[destinataria]").click(function(){
            destinataria = $(this).attr("destinataria");
            Carregando()
            $.ajax({
                url:"src/estoque/empresas/index.php",
                type:"POST",
                data:{
                    destinataria,
                    empresa:'<?=$_SESSION['estoque']['empresa']?>'
                },
                success:function(dados){
                    $("#paginaHome").html(dados);
                    listaEmpresas.close();
                }
            })
        })

    })
</script>