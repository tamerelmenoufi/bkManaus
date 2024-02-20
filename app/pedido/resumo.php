<?php
    $app = true;
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");


    if($_POST['acao'] == 'salvar'){

        $q = "update vendas_tmp set detalhes = JSON_SET(detalhes, 
                                                '$.item{$_POST['codigo']}.quantidade', '{$_POST['quantidade']}')
                            where id_unico = '{$_POST['idUnico']}'";

        mysqli_query($con, $q);

        exit();
    }

    if($_POST['acao'] == 'remove'){

        $q = "update vendas_tmp set detalhes = JSON_REMOVE(detalhes, '$.item{$_POST['codigo']}')
                            where id_unico = '{$_POST['idUnico']}'";

        mysqli_query($con, $q);

        exit();
    }

    $query = "select * from vendas_tmp where id_unico = '{$_POST['idUnico']}'";

    $result = mysqli_query($con, $query);

    $d = mysqli_fetch_object($result);

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
        background-color:#f4000a;
        color:#670600;
        border-bottom-right-radius:40px;
        border-bottom-left-radius:40px;
        font-family:FlameBold;
    }
    .barra_topo h2{
        color:#f6e13a;
    }

    .topo > .voltar{
        color:#f6e13a!important;
    }
    

    .home_corpo{
        position: absolute;
        top:100px;
        bottom:160px;
        overflow:auto;
        background-color:#fff;
        left:0;
        right:0;
    }

    .home_rodape{
        position: absolute;
        background-color:#fff;
        width:100%;
        bottom:0;
        height:80px;
    }

    .home_valores{
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: row;
        padding:5px;
        position: absolute;
        background-color:#fafafa;
        width:100%;
        bottom:80px;
        height:60px;
        font-family:FlameBold;
    }

    .produto_painel{
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: row;
        padding:5px;
        margin-bottom:20px;
    }
    .produto_painel img{
        height:120px;
        margin:5px;
    }
    .produto_dados{
        position:relative;
        width:100%;
        height:30px;
    }
    .produto_dados h4, .produto_dados h2{
        position:absolute;
        left:0;
        right:0;
        padding:0;
        margin:0;
        font-family:FlameBold;
        display: inline-block;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        direction: ltr;
    }
    .produto_dados div{
        color:#670600; 
        overflow: hidden; 
        font-family:FlameBold; 
        font-size:16px; 
        display: -webkit-box; 
        -webkit-box-orient: vertical; 
        -webkit-line-clamp: 2;
    }
    .produto_botoes{
        font-size:20px;
        margin-top:15px;
    }
</style>

<div class="barra_topo">
    <h2>Pedido</h2>
</div>
<div class="home_corpo">
<?php
    foreach(json_decode($d->detalhes) as $i => $dados){
        // echo "Codigo: ".$dados->codigo."<br>";
        $pd = mysqli_fetch_object(mysqli_query($con, "select * from produtos where codigo = '{$dados->codigo}'"));
        if($dados->status){

            if(is_file("../../src/{$dados->tipo}s/icon/{$pd->icon}")){
                $icon = "{$urlPainel}src/{$dados->tipo}s/icon/{$pd->icon}";
            }else{
                $icon = "img/imagem_produto.png";
            }
?>
    <div class="produto_painel" codigo="<?=$dados->codigo?>">
        <img src="<?=$icon?>" />
        <div class="w-100">
            <div class="produto_dados">
                <h4 style="color:#f12a2a"><?=$pd->produto?></h4>
            </div>
            <div class="produto_dados" editar="<?=$dados->tipo?>" categoria="<?=$pd->categoria?>" codigo="<?=$dados->codigo?>" style="color:#a1a1a1; padding-left:15px; margin-top:5px; cursor:pointer;">
                <i class="fa fa-edit"></i>
                Editar
            </div>
            <div class="produto_botoes d-flex justify-content-between">
                <div class="d-flex justify-content-between">
                    <i class="fa-solid <?=(($dados->quantidade == 1)?'fa-trash-can':'fa-circle-minus')?> menos" style="color:red; margin-left:10px;"></i>
                    <div class="qt" style="margin-top:-8px; text-align:center; width:30px; font-family:UniformBold;"><?=$dados->quantidade?></div>
                    <i class="fa-solid fa-circle-plus mais" style="color:green"></i>
                </div>
                <div valor>
                    <h2 class="adicionar" valor="<?=$dados->total?>" total="<?=($dados->total*$dados->quantidade)?>" style="color:#f12a2a; font-size:18px; padding-right:10px; font-family:FlameBold; ">
                        R$ <?=number_format(($dados->total*$dados->quantidade),2,",",false)?>
                    </h2>
                </div>
            </div>   
        </div>
    </div>
    
<?php
        }
    }
?>
</div>

<div class="home_valores">
    <button type="button" class="btn btn-outline-danger pagar"><span style="padding-right:30px;">Finalizar Compra</span> <span class="totalCompra" total=""></span></button>
</div>


<div class="home_rodape"></div>

<script>

$(function(){

    idUnico = localStorage.getItem("idUnico");
    codUsr = localStorage.getItem("codUsr");

    $.ajax({
        url:"rodape/rodape.php",
        success:function(dados){
            $(".home_rodape").html(dados);
        }
    });

    $.ajax({
        url:"topo/topo.php",
        type:"POST",
        data:{
            idUnico,
            codUsr
        },  
        success:function(dados){
            $(".barra_topo").append(dados);
        }
    });

    calculaTotal = ()=>{
        totalCompra = 0;
        $(".adicionar").each(function(){
            total = $(this).attr("total");
            totalCompra = (totalCompra*1 + total*1);
        })     
        
        if((totalCompra*1) == 0){
            $.ajax({
                url:"pedido/pedidos_historico.php",
                success:function(dados){
                    $(".home_corpo").html(dados);                   
                }
            });

            $(".home_valores").remove();
            return;
        }

        $(".totalCompra").attr("total", totalCompra);
        $(".totalCompra").html('R$ ' + totalCompra.toLocaleString('pt-br', {minimumFractionDigits: 2}));        
    }

    calculaTotal();

    $("div[editar]").click(function(){

        Carregando();

        codigo = $(this).attr("codigo");
        categoria = $(this).attr("categoria");
        local = $(this).attr("editar");
        idUnico = localStorage.getItem("idUnico");
       
        $.ajax({
            url:`produtos/detalhes_${local}.php`,
            type:"POST",
            data:{
                codigo,
                categoria,
                idUnico,
                historico:'.CorpoApp'
            },
            success:function(dados){
                // console.log(dados);
                $(".CorpoApp").html(dados);
                Carregando('none');
            }
        });        
    })

    atualizaDados = (cod, qtd)=>{
        idUnico = localStorage.getItem("idUnico");
        $.ajax({
            url:`pedido/resumo.php`,
            type:"POST",
            data:{
                codigo:cod,
                quantidade:qtd,
                idUnico,
                acao:'salvar'
            },
            success:function(dados){
                console.log(dados);
                $.ajax({
                    url:"rodape/rodape.php",
                    success:function(dados){
                        $(".home_rodape").html(dados);
                    }
                }); 
            }
        }); 
    }

    removeProduto = (cod)=>{
        idUnico = localStorage.getItem("idUnico");
        $.ajax({
            url:`pedido/resumo.php`,
            type:"POST",
            data:{
                codigo:cod,
                idUnico,
                acao:'remove'
            },
            success:function(dados){
                // console.log(dados);
                $.ajax({
                    url:"rodape/rodape.php",
                    success:function(dados){
                        $(".home_rodape").html(dados);
                    }
                }); 
            }
        }); 
    }


    $(".mais").click(function(){
        $(this).parent("div").children("i.menos").removeClass("fa-trash-can");
        $(this).parent("div").children("i.menos").addClass("fa-circle-minus");
        cod = $(this).parent("div").parent("div").parent("div").parent("div").attr("codigo");
        objValor = $(this).parent("div").parent("div").children("div[valor]").children("h2");
        objQt = $(this).parent("div").children("div.qt");
        valor = objValor.attr("valor");
        qt = objQt.text();
        qt = (qt*1 + 1);
        objQt.text(qt);
        total = (valor*qt);
        objValor.attr("total", total);
        objValor.html('R$ ' + total.toLocaleString('pt-br', {minimumFractionDigits: 2})); 
        calculaTotal();   
        atualizaDados(cod, qt);  
    })

    $(".menos").click(function(){
        cod = $(this).parent("div").parent("div").parent("div").parent("div").attr("codigo");
        objValor = $(this).parent("div").parent("div").children("div[valor]").children("h2");
        objQt = $(this).parent("div").children("div.qt");
        valor = objValor.attr("valor");
        qt = objQt.text();
        if(qt == 2){
            $(this).parent("div").children("i.menos").addClass("fa-trash-can");
            $(this).parent("div").children("i.menos").removeClass("fa-circle-minus");
        }else if(qt == 1){
            $(this).parent("div").parent("div").parent("div").parent("div").remove();
            removeProduto(cod);
            calculaTotal();
            atualizaDados(cod, qt);

            $.ajax({
                url:"rodape/rodape.php",
                success:function(dados){
                    $(".home_rodape").html(dados);
                }
            }); 

            return false;
        }
        qt = (((qt*1 - 1)>1)?(qt*1 - 1):1);
        // qt = (qt*1 - 1);
        objQt.text(qt);
        total = (valor*qt);
        objValor.attr("total", total);
        objValor.html('R$ ' + total.toLocaleString('pt-br', {minimumFractionDigits: 2}));
        calculaTotal();
        atualizaDados(cod, qt); 

    })


    $(".pagar").click(function(){
        Carregando();
        $.ajax({
            url:"pedido/pagar.php",
            type:"POST",
            data:{
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