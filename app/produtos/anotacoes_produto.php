<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    $c = mysqli_fetch_object(mysqli_query($con, "select * from categorias where codigo = '{$_SESSION['categoria']}'"));  
    
    
    $acoes = json_decode($c->acoes_itens);

?>
<style>
    .barra_topo{
        position:absolute;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        flex-direction: column;
        top:0;
        width:100%;
        height:100px;
        background-color:#ffc63a;
        color:#c45018;
        border-bottom-right-radius:40px;
        border-bottom-left-radius:40px;
        font-family:FlameBold;
    }


    .home_corpo{
        position: absolute;
        top:100px;
        bottom:150px;
        overflow:auto;
        background-color:#fff;
        width:100%;
    }

    .home_rodape{
        position: absolute;
        background-color:#fff;
        width:100%;
        bottom:0;
        height:90px;
    }

    .produto_botoes{
        position:absolute;
        bottom:90px;
        left:0;
        right:0;
        padding:15px;
        height:60px;
        font-size:30px;
    }

    .produto_painel{
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        padding:15px;

    }

    .produto_titulo{
        color:#c45018;
        font-family:FlameBold;
        text-align:center;
    }
    .produto_img{
        height:270px;
        margin:5px;
    }
    .produto_descricao{
        position:relative;
        font-family:Uniform;
        width:100%;
    }
    .produto_detalhes{
        padding:2px;
        border:solid 1px #ccc;
        border-radius:5px;
        font-family:Uniform;
        margin-bottom:10px;
        margin-top:10px;
    }

    
</style>

<div class="barra_topo">
    <h2><?=$c->categoria?></h2>
</div>

<?php
    $query = "select *, itens->>'$[*].item' as lista_itens from produtos where codigo = '{$_POST['codigo']}'";
    $result = mysqli_query($con, $query);
    $d = mysqli_fetch_object($result);
?>
<div class="home_corpo">
    <div class="produto_painel" codigo="<?=$d->codigo?>">
        <h1 class="produto_titulo"><?=$d->produto?></h1>

        <?php

        echo $d->lista_itens;

        $itens = json_decode($d->lista_itens);
        $categorias_itens = json_decode($d->categorias_itens);

        print_r($itens);

        if($acoes->remocao == 'true' and $itens){



        ?>

        <div class="card w-100 mb-3">
        <div class="card-header">
            Retirar algum Item?
        </div>
        <ul class="list-group list-group-flush">
            <?php
            $q = "select * from itens where codigo in ('".implode("', '", $itens)."')";
            $r = mysqli_query($con, $q);
            while($i = mysqli_fetch_object($r)){
            ?>
            <li class="list-group-item">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="remocao<?=$i->codigo?>">
                    <label class="form-check-label" for="remocao<?=$i->codigo?>"><?=$i->item?></label>
                </div>
            </li>
            <?php
            }
            ?>
        </ul>
        </div>

        <?php
        }

        if($acoes->inclusao == 'true'){
        ?>
    
            <div class="card w-100 mb-3">
            <div class="card-header">
                Incluir algum Item?
            </div>
            <ul class="list-group list-group-flush">
                <?php
                $q = "select * from itens where categoria in ('".implode("', '", $categorias_itens)."')";
                $r = mysqli_query($con, $q);
                while($i = mysqli_fetch_object($r)){
                ?>
                <li class="list-group-item d-flex justify-content-between">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="remocao<?=$i->codigo?>">
                        <label class="form-check-label" for="remocao<?=$i->codigo?>"><?=$i->item?></label>
                    </div>
                    <div>
                        xxx
                    </div>
                </li>
                <?php
                }
                ?>
            </ul>
            </div>
    
        <?php
        }

        if($acoes->substituicao == 'true'){
        ?>
    
            <div class="card w-100 mb-3">
            <div class="card-header">
                Substituir algum Item?
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">An item</li>
                <li class="list-group-item">A second item</li>
                <li class="list-group-item">A third item</li>
            </ul>
            </div>
    
        <?php
        }

        ?>

        <!-- <img src="img/logo.png" class="produto_img" />
        <div class="produto_detalhes d-flex justify-content-between align-items-center w-100">
            <div style="cursor:pointer">
                <i class="fa-regular fa-message fa-flip-horizontal"></i>
                Observações aqui
            </div>
            <button type="button" class="btn btn-outline-secondary btn-sm">Anotações</button>
        </div>   
        <div class="produto_descricao"><?=$d->descricao?></div> -->
          
    </div>
</div>
<div class="produto_botoes d-flex justify-content-between">
    <button type="button" class="btn btn-warning" style="font-family:Uniform; margin-top:-20px; margin-right:20px;">Cancelar</button>
    <button type="button" class="btn btn-danger w-100" style="font-family:Uniform; margin-top:-20px;">Incluir</button>
</div>   
<div class="home_rodape"></div>

<script>

$(function(){

    $.ajax({
        url:"rodape/rodape.php",
        success:function(dados){
            $(".home_rodape").html(dados);
        }
    });

    $(".barra_topo").click(function(){

        $.ajax({
            url:"produtos/detalhes_produto.php",
            type:"POST",
            data:{
                codigo:'<?=$d->codigo?>'
            },
            success:function(dados){
                $(".CorpoApp").html(dados);
            }
        });        

    })

})

	

</script>