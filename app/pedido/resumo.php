<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

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
        background-color:#ffc63a;
        color:#c45018;
        border-bottom-right-radius:40px;
        border-bottom-left-radius:40px;
        font-family:FlameBold;
    }
    .barra_topo h2{
        
    }

    .home_corpo{
        position: absolute;
        top:100px;
        bottom:90px;
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
        color:#c45018; 
        overflow: hidden; 
        font-family:FlameBold; 
        font-size:16px; 
        display: -webkit-box; 
        -webkit-box-orient: vertical; 
        -webkit-line-clamp: 2;
    }
    .produto_botoes{
        font-size:30px;
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
?>
    <div class="produto_painel" codigo="<?=$dados->codigo?>">
        <img src="img/logo.png" />
        <div class="w-100">
            <div class="produto_dados">
                <h4 style="color:#f12a2a"><?=$pd->produto?></h4>
            </div>
            <div class="produto_dados" editar="<?=$dados->tipo?>" categoria="<?=$pd->categoria?>" codigo="<?=$dados->codigo?>" style="color:#a1a1a1; margin:15px; cursor:pointer;">
                <i class="fa fa-edit"></i>
                Editar
            </div>
            <div class="produto_botoes d-flex justify-content-between">
                <div class="d-flex justify-content-between">
                    <i class="fa-solid <?=(($dados->quantidade == 1)?'fa-trash-can':'fa-circle-minus')?> menos" style="color:red; margin-left:10px;"></i>
                    <div class="qt" style="margin-top:-8px; text-align:center; width:60px; font-family:UniformBold;"><?=$dados->quantidade?></div>
                    <i class="fa-solid fa-circle-plus mais" style="color:green"></i>
                </div>
                <div valor>
                    <h2 class="adicionar" valor="<?=$dados->total?>" style="color:#f12a2a; font-family:FlameBold; ">
                        R$ <?=number_format(($dados->total*$dados->quantidade),2,",",false)?>
                    </h2>
                </div>
            </div>   
            <!-- <div class="produto_dados" style="height:60px;">
                <div><?=$dados->codigo?></div>
            </div>
            <div class="produto_dados">
                <h2 style="color:#f12a2a">
                    <i class="fa-solid fa-circle-play me-3"></i>
                    R$ <?=number_format($dados->total,2,",",false)?>
                </h2>
            </div>             -->
        </div>

    </div>
    
<?php
    }
?>
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

    $("div[editar]").click(function(){
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
                idUnico
            },
            success:function(dados){
                $(".CorpoApp").html(dados);
            }
        });        
    })


    $(".mais").click(function(){
        $(this).parent("div").children("i.menos").removeClass("fa-trash-can");
        $(this).parent("div").children("i.menos").addClass("fa-circle-minus");

        objValor = $(this).parent("div").parent("div").children("div[valor]").children("h2");
        objQt = $(this).parent("div").children("div.qt");
        valor = objValor.attr("valor");
        qt = objQt.text();
        qt = (qt*1 + 1);
        objQt.text(qt);
        total = (valor*qt);
        objValor.html('R$ ' + total.toLocaleString('pt-br', {minimumFractionDigits: 2}));                
    })

    $(".menos").click(function(){
        objValor = $(this).parent("div").parent("div").children("div[valor]").children("h2");
        objQt = $(this).parent("div").children("div.qt");
        valor = objValor.attr("valor");
        qt = objQt.text();
        if(qt == 2){
            $(this).parent("div").children("i.menos").addClass("fa-trash-can");
            $(this).parent("div").children("i.menos").removeClass("fa-circle-minus");
        }else if(qt == 1){
            console.log('Passou pelo click');
            $(this).parent("div").parent("div").parent("div").parent("div").remove();
        }
        qt = (((qt*1 - 1)>1)?(qt*1 - 1):1);
        // qt = (qt*1 - 1);
        objQt.text(qt);
        total = (valor*qt);
        objValor.html('R$ ' + total.toLocaleString('pt-br', {minimumFractionDigits: 2}));
     
    })

})

</script>