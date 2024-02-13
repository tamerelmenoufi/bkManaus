<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");
?>
<style>
    .combo{
        margin:5px;
        background-size:cover;
        background-image:url('img/bg_botao_combo.png');
        border-radius:20px;
        cursor:pointer;
    }
    .combo img{
        width:100%;
    }
    .combo span{
        margin:10px;
        /* word-break: break-word; */
        color:#fff;
        font-size:20px;
        margin-right:40px;
        font-family:FlameBold;
        word-break: break-all;
        overflow: hidden; // Removendo barra de rolagem
        text-overflow: ellipsis; // Adicionando "..." ao final
        display:-webkit-box;
        -webkit-line-clamp: 2; // Quantidade de linhas
        -webkit-box-orient: vertical;
    }

    .categorias{
        margin:5px;
        background-size:cover;
        border-radius:20px;
        cursor:pointer;
    }
    .categorias img{
        width:100%;
    }
    .categorias span{
        margin:10px;
        /* word-break: break-word; */
        color:#52231b;
        font-family:FlameBold;
        word-break: break-all;
        overflow: hidden; // Removendo barra de rolagem
        text-overflow: ellipsis; // Adicionando "..." ao final
        display:-webkit-box;
        -webkit-line-clamp: 2; // Quantidade de linhas
        -webkit-box-orient: vertical;
    }
</style>
<div class="row g-0">
    <div class="col-12">
        <div class="d-flex justify-content-center align-items-center combo" local="produtos/lista_promocoes.php">
            <img src="img/promocoes.png?20240213" alt="">
            <!-- <span>PROMOÇÕES</span>-->
        </div>        
    </div>
    <div class="col-6">
    <?php
    $query = "select * from categorias where codigo = 8";
    $result = mysqli_query($con, $query);
    $d = mysqli_fetch_object($result);

    if(is_file("../../src/categorias/icon/{$d->icon}")){
        $icon = "{$urlPainel}src/categorias/icon/{$d->icon}";
    }else{
        $icon = "img/imagem_produto.png";
    }
    /*
    ?>
        <div class="d-flex justify-content-center align-items-center combo" codigo="8" local="produtos/lista_combos.php">
            <!-- <span><?=$d->categoria?></span> -->
            <img src="<?=$icon?>" alt="">
        </div>
    <?php
    //*/
    ?>
        <div class="d-flex justify-content-start align-items-center combo" codigo="<?=$d->codigo?>" local="produtos/lista_combos.php">
            <img src="<?=$icon?>" alt="">
            <!-- <span><?=$d->categoria?></span> -->
        </div>    
    </div>
    <?php
    $query = "select * from categorias where tipo = 'prd' and deletado != '1' and situacao = '1' order by ordem";
    $result = mysqli_query($con, $query);
    while($d = mysqli_fetch_object($result)){

    if(is_file("../../src/categorias/icon/{$d->icon}")){
        $icon = "{$urlPainel}src/categorias/icon/{$d->icon}";
    }else{
        $icon = "img/imagem_produto.png";
    }

    ?>
    <div class="col-6">
        <div class="d-flex justify-content-start align-items-center categorias" codigo="<?=$d->codigo?>" local="produtos/lista_produtos.php">
            <img src="<?=$icon?>" alt="">
            <!-- <span><?=$d->categoria?></span> -->
        </div>
    </div>
    <?php
    }
    ?>
</div>

<script>

    $(function(){

        $(".combo, .categorias").click(function(){

            Carregando();
            local = $(this).attr("local")
            categoria = $(this).attr("codigo")

            $.ajax({
                url:local,
                type:"POST",
                data:{
                    categoria,
                    historico:'.CorpoApp'
                },
                success:function(dados){
                    Carregando('none');
                    $(".CorpoApp").html(dados);
                }
            })

        });

    })

</script>