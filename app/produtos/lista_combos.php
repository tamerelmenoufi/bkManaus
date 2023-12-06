<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    if($_POST['categoria']){
        $_SESSION['categoria'] = $_POST['categoria'];
    }

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
    .barra_topo h2{
        
    }

    .home_corpo{
        position: absolute;
        top:100px;
        bottom:80px;
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
    .produto_painel{
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: row;
        padding:5px;
        margin-bottom:20px;
    }
    .produto_painel img{
        height:140px;
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
        height:20px;
        font-family:FlameBold; 
        font-size:16px; 
        display: -webkit-box; 
        -webkit-box-orient: vertical; 
        -webkit-line-clamp: 1;
    }
</style>

<div class="barra_topo">
    <h2><?=$c->categoria?></h2>
</div>

<div class="home_corpo">
<?php
$query = "select *, produtos->>'$[*].produto' as cod_prod, produtos->>'$[*].quantidade' as qtd_prod from produtos where categoria = '{$c->codigo}' and deletado != '1' and situacao = '1'";
$result = mysqli_query($con, $query);
while($d = mysqli_fetch_object($result)){

    if(is_file("../../src/combos/icon/{$d->icon}")){
        $icon = "{$urlPainel}src/combos/icon/{$d->icon}";
    }else{
        $icon = "img/imagem_produto.png";
    }

    $lista_produtos = json_decode($d->cod_prod);
    if($lista_produtos){
        $cods = implode(", ",$lista_produtos);
        $q = "select * from produtos where codigo in ($cods) limit 3";
        $r = mysqli_query($con, $q);
        $prd = [];
        while($d1 = mysqli_fetch_object($r)){
            $prd[] = $d1->produto;
        }

        $prd = implode("</div><div>- ", $prd);
    }
?>
    <div class="produto_painel" codigo = "<?=$d->codigo?>">
        <img src="<?=$icon?>" />
        <div class="w-100">
            <div class="produto_dados">
                <h4 style="color:#f12a2a"><?=$d->produto?></h4>
            </div>
            <div class="produto_dados" style="height:70px;">
                <div>- <?=$prd?></div>
            </div>
            <div class="produto_dados">
                <h2 style="color:#f12a2a">
                    <i class="fa-solid fa-circle-play me-3"></i>
                    R$ <?=number_format(CalculaValorCombo($d->codigo),2,",",false)?>
                </h2>
            </div>            
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

    $.ajax({
        url:"topo/topo.php",
        success:function(dados){
            $(".barra_topo").append(dados);
        }
    });

    $(".produto_painel").click(function(){
        Carregando();
        codigo = $(this).attr("codigo");
        idUnico = localStorage.getItem("idUnico");
        $.ajax({
            url:"produtos/detalhes_combo.php",
            type:"POST",
            data:{
                codigo,
                categoria:'<?=$_SESSION['categoria']?>',
                idUnico,
                historico:'.CorpoApp'
            },
            success:function(dados){
                $(".CorpoApp").html(dados);
                Carregando('none')
            }
        });        

    })


})

	

</script>