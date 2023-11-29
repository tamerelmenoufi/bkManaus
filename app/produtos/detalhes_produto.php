<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    $c = mysqli_fetch_object(mysqli_query($con, "select * from categorias where codigo = '{$_SESSION['categoria']}'"));    

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
        width:100%;
    }

    
</style>

<div class="barra_topo">
    <h2><?=$c->categoria?></h2>
</div>

<?php
    $query = "select * from produtos where codigo = '{$_POST['codigo']}'";
    $result = mysqli_query($con, $query);
    $d = mysqli_fetch_object($result);
?>
<div class="home_corpo">
    <div class="produto_painel" codigo="<?=$d->codigo?>">
        <h1 class="produto_titulo"><?=$d->produto?></h1>
        <img src="img/logo.png" class="produto_img" />
        <div class="produto_descricao"><?=$d->descricao?></div>       
    </div>
</div>
<div class="produto_botoes d-flex justify-content-between">
    <div class="d-flex justify-content-between">
        <i class="fa-solid fa-circle-plus" style="color:red"></i>
        <div style="margin-top:-8px; text-align:center; width:100px;">1</div>
        <i class="fa-solid fa-circle-minus" style="color:green"></i>
    </div>
    <div>R$ <?=number_format($d->valor,2,",",false)?></div>
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
            url:"produtos/lista_produtos.php",
            success:function(dados){
                $(".CorpoApp").html(dados);
            }
        });        

    })

})

	

</script>