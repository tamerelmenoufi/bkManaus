<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    if($_POST['empresa']) $_SESSION['estoque']['empresa'] = $_POST['empresa'];


?>

<div class="d-flex justify-content-end">
    <button iniciar_venda class="btn btn-success btn-sm"><i class="fa-solid fa-bag-shopping"></i> Iniciar uma Venda</button>
</div>


<script>
    $(function(){

        $("button[iniciar_venda]").click(function(){

            Carregando();
            $.ajax({
                url:"src/estoque/empresas/empresas.php",
                type:"POST",
                data:{
                    empresa:'<?=$_SESSION['estoque']['empresa']?>'
                },
                success:function(dados){
                    $.dialog({
                        title:"Empresas Cadastradas",
                        content:dados,
                        type:"blue"
                    })
                }
            })

        })

    })
</script>