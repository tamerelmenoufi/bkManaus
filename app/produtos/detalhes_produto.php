<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    $c = mysqli_fetch_object(mysqli_query($con, "select * from categorias where codigo = '{$_SESSION['categoria']}'"));

    if($_POST['acao'] == 'anotacoes'){

        $data = $_POST;
        unset($data['acao']);
        unset($data['codigo']);
        unset($data['idUnico']);
        unset($data['quantidade']);
        unset($data['valor']);

        $update = [
            'regras' => $data,
            'anotacoes' => $_POST['anotacoes'],
            'adicional' => $valor_adicional,
            'valor' => $_POST['valor'],
            'total' => ($valor_adicional + $_POST['valor']),
            'quantidade' => $_POST['quantidade'],
            'codigo' => $_POST['codigo']
        ];

        $update = json_encode($update);

        $anota = print_r($data, true);

        $anota = "UPDATE vendas_tmp set detalhes = JSON_SET(detalhes, 'item{$_POST['codigo']}', '{$update}') where id_unico = '{$_POST['idUnico']}'";

        mysqli_query($con, "UPDATE vendas_tmp set detalhes = JSON_SET(detalhes, 'item{$_POST['codigo']}', '{$update}') where id_unico = '{$_POST['idUnico']}'");

        
    }

    if($_POST['acao'] == 'salvar'){

        $tmp = mysqli_fetch_object(mysqli_query($con, "select detalhes.'$.produto{$_POST['codigo']}' as produto from vendas_tmp where id_unico = '{$_POST['idUnico']}'"));

        
    }

    
    $query = "select * from produtos where codigo = '{$_POST['codigo']}'";
    $result = mysqli_query($con, $query);
    $d = mysqli_fetch_object($result);

    $tmp = mysqli_fetch_object(mysqli_query($con, "select detalhes.'$.produto{$d->codigo}' as produto from vendas_tmp where id_unico = '{$idUnico}'"));

    

    $valor_calculado = $d->valor;

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


<div class="home_corpo">
    <?=$anota?>
    <div class="produto_painel" codigo="<?=$d->codigo?>">
        <h1 class="produto_titulo"><?=$d->produto?></h1>
        <img src="img/logo.png" class="produto_img" />
        <div class="produto_detalhes d-flex justify-content-between align-items-center w-100">
            <div style="cursor:pointer">
                <i class="fa-regular fa-message fa-flip-horizontal"></i>
                Observações aqui
            </div>
            <button type="button" class="btn btn-outline-secondary btn-sm">Anotações</button>
        </div>   
        <div class="produto_descricao"><?=$d->descricao?></div>
          
    </div>
</div>
<div class="produto_botoes d-flex justify-content-between">
    <div class="d-flex justify-content-between">
        <i class="fa-solid fa-circle-minus menos" style="color:red"></i>
        <div class="qt" style="margin-top:-8px; text-align:center; width:60px; font-family:UniformBold;">1</div>
        <i class="fa-solid fa-circle-plus mais" style="color:green"></i>
    </div>
    <div>
        <button type="button" class="btn btn-danger adicionar" valor="<?=$valor_calculado?>" style="font-family:FlameBold; font-size:25px; margin-top:-20px;">R$ <?=number_format($valor_calculado,2,",",false)?></button>
    </div>
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


    $(".mais").click(function(){
        valor = $(".adicionar").attr("valor");
        qt = $(".qt").text();
        qt = (qt*1 + 1);
        valor = valor 
    })


    $(".barra_topo").click(function(){

        $.ajax({
            url:"produtos/lista_produtos.php",
            success:function(dados){
                $(".CorpoApp").html(dados);
            }
        });        

    })

    $(".produto_detalhes").click(function(){

        quantidade = $(".qt").text();

        $.ajax({
            url:"produtos/anotacoes_produto.php",
            type:"POST",
            data:{
                codigo:'<?=$d->codigo?>',
                quantidade,
            },
            success:function(dados){
                $(".CorpoApp").html(dados);
            }
        });           

    })

})

	

</script>