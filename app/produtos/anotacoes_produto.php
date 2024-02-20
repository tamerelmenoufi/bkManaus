<?php
    $app = true;
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
        color:#670600;
        border-bottom-right-radius:40px;
        border-bottom-left-radius:40px;
        font-family:FlameBold;
    }


    .home_corpo{
        position: absolute;
        top:100px;
        bottom:160px;
        overflow:auto;
        background-color:#fff;
        width:100%;
    }

    .home_rodape{
        position: absolute;
        background-color:#fff;
        width:100%;
        bottom:0;
        height:80px;
    }

    .produto_botoes{
        position:absolute;
        bottom:80px;
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
        color:#670600;
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

    $tmp = mysqli_fetch_object(mysqli_query($con, "select detalhes->>'$.item{$_POST['codigo']}' as produto from vendas_tmp where id_unico = '{$_POST['idUnico']}'"));


    $dc = json_decode($tmp->produto);

    if($dc->regras->inclusao){
        foreach($dc->regras->inclusao as $i => $v){
            $inclusao[$v] = $v;
            $qt = $dc->regras->inclusao_quantidade;
            $inclusao_quantidade[$v] = $qt[$i];
        }
    }

    if($dc->regras->remocao){
        foreach($dc->regras->remocao as $i => $v){
            $remocao[$v] = $v;
        }
    }

    $anotacoes = $dc->anotacoes;


    $itens = json_decode($d->lista_itens);
    $categorias_itens = json_decode($d->categorias_itens);    

?>
<div class="home_corpo">
    <div class="produto_painel" codigo="<?=$d->codigo?>">
        <h1 class="produto_titulo"><?=$d->produto?></h1>

        <?php

        if($acoes->remocao == 'true' and $itens and $itens != 'null'){

        ?>

        <div class="card w-100 mb-3">
        <div class="card-header">
            Retirar algum Item?
        </div>
        <ul class="list-group list-group-flush">
            <?php
            $q = "select * from itens where situacao = '1' and deletado != '1' and codigo in ('".implode("', '", $itens)."')";
            $r = mysqli_query($con, $q);
            while($i = mysqli_fetch_object($r)){
            ?>
            <li class="list-group-item">
                <div class="form-check">
                    <input type="checkbox" <?=(($remocao[$i->codigo] == $i->codigo)?'checked':false)?> class="form-check-input remocao" codigo="<?=$i->codigo?>" id="remocao<?=$i->codigo?>">
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

        if($acoes->inclusao == 'true' and $categorias_itens and $categorias_itens != 'null'){
        ?>
    
            <div class="card w-100 mb-3">
            <div class="card-header">
                Incluir algum Item?
            </div>
            <ul class="list-group list-group-flush">
                <?php
                $q = "select * from itens where situacao = '1' and deletado != '1' and categoria in ('".implode("', '", $categorias_itens)."')";
                $r = mysqli_query($con, $q);
                while($i = mysqli_fetch_object($r)){
                ?>
                <li class="list-group-item d-flex justify-content-between flex-column">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input inclusao" <?=(($inclusao[$i->codigo] == $i->codigo)?'checked':false)?> valor="<?=$i->valor?>" codigo="<?=$i->codigo?>" id="inclusao<?=$i->codigo?>">
                        <label class="form-check-label" for="inclusao<?=$i->codigo?>"><?=$i->item?></label>
                    </div>
                    <div class="d-flex justify-content-end w-100">
                        <div class="input-group" style="width:150px;">
                            <select class="form-select form-select-sm" id="inclusao_quantidade<?=$i->codigo?>">
                                <?php
                                for($j=1;$j<=10;$j++){
                                ?>
                                <option value="<?=$j?>" <?=(($inclusao_quantidade[$i->codigo] == $j)?'selected':false)?>><?=$j?></option>
                                <?php
                                }
                                ?>
                            </select>
                            <label class="input-group-text" style="width:85px; text-align:right;" for="inclusao_valor<?=$i->codigo?>">R$ <?=number_format($i->valor, 2, ",", false)?></label>
                        </div>                        
                    </div>
                </li>
                <?php
                }
                ?>
            </ul>
            </div>
    
        <?php
        }

        if($acoes->substituicao == 'true' and $categorias_itens and $categorias_itens != 'null'){
        ?>
    
            <div class="card w-100 mb-3">
            <div class="card-header">
                Substituir algum Item?
            </div>
            <ul class="list-group list-group-flush">
                <?php
                $q = "select * from itens where situacao = '1' and deletado != '1' and categoria in ('".implode("', '", $categorias_itens)."')";
                $r = mysqli_query($con, $q);
                while($i = mysqli_fetch_object($r)){
                ?>
                <li class="list-group-item d-flex justify-content-between">
                    <div class="form-check">
                        <input type="radio" class="form-check-input substituicao" name="substituicao" codigo="<?=$i->codigo?>" valor="<?=$i->valor?>" id="substituicao<?=$i->codigo?>">
                        <label class="form-check-label" for="substituicao<?=$i->codigo?>"><?=$i->item?></label>
                    </div>
                    <div>
                        R$ <?=number_format($i->valor, 2, ",", false)?>
                    </div>
                </li>

                <?php
                }
                ?>
            </ul>
            </div>
    
        <?php
        }

        ?>

        <div class="mb-3 w-100">
        <label for="anotacoes" class="form-label">
            <i class="fa-regular fa-message fa-flip-horizontal"></i>
            Anotações do pedido
        </label>
        <textarea class="form-control" id="anotacoes" rows="3"><?=$anotacoes?></textarea>
        </div>

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
    <button type="button" class="btn btn-warning cancelar" style="font-family:Uniform; margin-top:-20px; margin-right:20px;">Cancelar</button>
    <button type="button" class="btn btn-danger w-100 incluir" style="font-family:Uniform; margin-top:-20px;">Incluir</button>
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

    $(".cancelar").click(function(){
        Carregando();
        $.ajax({
            url:"produtos/detalhes_produto.php",
            type:"POST",
            data:{
                codigo:'<?=$d->codigo?>',
                idUnico:'<?=$_POST['idUnico']?>',
                quantidade:'<?=$_POST['quantidade']?>',
                historico:'.CorpoApp'
            },
            success:function(dados){
                $(".CorpoApp").html(dados);
                Carregando('none');
            }
        });        

    })

    $(".incluir").click(function(){

        remocao = [];
        inclusao = [];
        inclusao_valor = [];
        inclusao_quantidade = [];
        substituicao = [];
        substituicao_valor = [];
        anotacoes = $("#anotacoes").val();
        idUnico = localStorage.getItem("idUnico");

        $(".remocao").each(function(){
            codigo = $(this).attr("codigo");
            if($(this).prop("checked") == true){
                remocao.push(codigo)
            }
        })

        $(".inclusao").each(function(){
            codigo = $(this).attr("codigo");
            valor = $(this).attr("valor");
            quantidade = $(`#inclusao_quantidade${codigo}`).val();
            if($(this).prop("checked") == true){
                inclusao.push(codigo)
                inclusao_valor.push(valor);
                inclusao_quantidade.push(quantidade);
            }
        })

        $(".substituicao").each(function(){
            codigo = $(this).attr("codigo");
            valor = $(this).attr("valor");
            if($(this).prop("checked") == true){
                substituicao.push(codigo)
                substituicao_valor.push(valor);
            }
        })
        Carregando();
        $.ajax({
            url:"produtos/detalhes_produto.php",
            type:"POST",
            data:{
                codigo:'<?=$d->codigo?>',
                quantidade:'<?=$_POST['quantidade']?>',
                valor:'<?=$d->valor?>',
                remocao,
                inclusao,
                inclusao_valor,
                inclusao_quantidade,
                substituicao,
                substituicao_valor,
                anotacoes,
                idUnico,
                acao:'anotacoes',
                historico:'.CorpoApp'
            },
            success:function(dados){
                $(".CorpoApp").html(dados);
                Carregando('none');
            }
        });        

    })

})

	

</script>