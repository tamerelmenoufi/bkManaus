<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/app/projectSocioEconomico/lib/includes.php");
?>

<style>
    .Titulo<?=$md5?>{
        position:absolute;
        left:60px;
        top:8px;
        z-index:0;
    }
    .grafico<?=$md5?>{
        margin-right:20px;
        width:30px;
        height:30px;
    }
    .percentual{
        margin-right:20px;
        font-size:12px;
    }

</style>
<h5 class="Titulo<?=$md5?>">Novos Beneficiados</h5>
<ul class="list-group">
<?php

    $query = "select a.*,b.nome as coordenador, (select codigo from se where a.cpf = cpf limit 1) as se from se_novos a left join usuarios b on a.monitor_social = b.codigo";
    $result = mysqli_query($con, $query);
    $t = 0;
    while($s = mysqli_fetch_object($result)){
?>
    <li class="list-group-item d-flex justify-content-between align-items-center">
        <span class="percentual"><?=$s->nome?> (<?=$s->cpf?>)<br><?=$s->coordenador?></span>
        <div class="d-flex justify-content-between align-items-center">
            <span class="percentual"><?=$s->telefone?></span>
            <span class="percentual"><?=dataBr($s->data)?></span>
            <?php
            if($s->se){
            ?>
            <button 
                cod="<?=$s->se?>" 
                class="btn <?=(($s->se)?'btn-success':'btn-warning')?> btn-sm"
                data-bs-toggle="offcanvas"
                href="#offcanvasDireita"
                role="button"
                aria-controls="offcanvasDireita"  
            >
                <i class="fa fa-edit"></i>
            </button>
            <?php
            }
            ?>
        </div>
    </li>
<?php
    }

?>
</ul>
<script>
    $(function(){
        Carregando('none');

        $("button[cod]").off('click').on('click',function(){

            cod = $(this).attr("cod");
            Carregando();
            $.ajax({
                url:"src/se/se.php",
                type:"POST",
                data:{
                    cod,
                    origem:'1'
                },
                success:function(dados){
                    $(".LateralDireita").html(dados);
                }
            })

        })


    })
</script>