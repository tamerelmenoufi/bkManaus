<?php
    $app = true;
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    if($_POST['categoria']){
        $_SESSION['categoria'] = $_POST['categoria'];
    }

    $c = mysqli_fetch_object(mysqli_query($con, "select * from categorias where codigo = '{$_SESSION['categoria']}'"));   
    
    
    $autorizados = [1,3,5];
    if(in_array($_SESSION['categoria'], $autorizados)){
        $lp = "select codigo from produtos where categoria = '{$_SESSION['categoria']}' and deletado != '1' and situacao = '1'";
        $lpr = mysqli_query($con, $lp);
        $combos = [];
        while($lpd = mysqli_fetch_object($lpr)){
            $lc = "select codigo from produtos where categoria = '8' and deletado != '1' and situacao = '1' and  produtos->>'$[*].produto' like '%\"{$lpd->codigo}\"%'";
            $lcr = mysqli_query($con, $lc);
            while($lcd = mysqli_fetch_object($lcr)){
                $combos[] = $lcd->codigo;
            }
        }
    }


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
        margin:5px;
        border-radius:15px;
        margin-bottom:20px;
    }
    .produto_painel img{
        height:135px;
        margin:5px;
    }

    .combos{
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: row;
        margin:5px;
        border-radius:15px;
        margin-bottom:20px;
        min-height:160px;
    }
    .combos img{
        height:135px;
        margin:5px;
    }
    
    .produto_dados{
        position:relative;
        width:100%;
        height:25px;
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
        overflow: hidden; 
        /* font-family:FlameBold;  */
        font-family:UniformLight;
        font-size:16px; 
        display: -webkit-box; 
        -webkit-box-orient: vertical; 
        -webkit-line-clamp: 2;
    }
    .promocao{
        position:absolute;
        top:-15px;
        left:0px;
        font-family:UniformLight;
        color:#fff;
        font-weight:bold;
    }
</style>

<div class="barra_topo">
    <h2><?=$c->categoria?></h2>
</div>

<div class="home_corpo">



<?php
////////////////////////////////////////INICIO DOS COMBOS//////////////////////////////
if($combos){
$query = "select *, produtos->>'$[*].produto' as cod_prod, produtos->>'$[*].quantidade' as qtd_prod from produtos where codigo in(".implode(",",$combos).") order by promocao desc";
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

        $prd = implode("</div><div class='produto_dados' style='color:".(($d->promocao == '1')?'#ffffff':'#000000')."; height:22px; font-family:Uniform; -webkit-line-clamp: 1; white-space: nowrap;'>- ", $prd);
    }
?>
    <div class="combos" codigo = "<?=$d->codigo?>" style="background-color:<?=(($d->promocao == '1')?'#bd0100':'trasparent')?>">
        <img src="<?=$icon?>" />
        <div class="w-100">
            <div class="produto_dados">
                <h4 style="color:<?=(($d->promocao == '1')?'#fbdb00':'#600f0b')?>"><?=$d->produto?></h4>
            </div>
            <div style="height:90px;">
                <div class="produto_dados" style="color:<?=(($d->promocao == '1')?'#ffffff':'#000000')?>; height:22px; font-family:Uniform; -webkit-line-clamp: 1; white-space: nowrap;">- <?=$prd?></div>
            </div>

            <div class="produto_dados">
                <?php
                if($d->promocao == '1'){
                ?>
                <div class="promocao">DE R$ <?=number_format(CalculaValorCombo($d->codigo),2,",",false)?></div>
                <?php
                }
                ?>
                <div class="d-flex justify-content-between w-100" style="color:<?=(($d->promocao == '1')?'#fbdb00':'#f4352b')?>;">
                    <div><h2 class="produto_dados" style="margin-top:2px;"><span style="color:#fbdb00; font-size:12px;"><?=(($d->promocao == '1')?'POR ':false)?></span>R$ <?=number_format((($d->promocao == '1')?$d->valor_promocao:CalculaValorCombo($d->codigo)),2,",",false)?></h2></div>
                    <i class="fa-solid fa-circle-play me-3" style="font-size:25px;"></i>
                </div>
            </div>         
        </div>
    </div>

<?php
}
}
////////////////////////////////////////FIM DOS COMBOS//////////////////////////////


//////////////// INICIO DOS PRODUTOS INDIVIDUAIS/////////////////////////////////////

$query = "select * from produtos where categoria = '{$c->codigo}' and deletado != '1' and situacao = '1' order by promocao desc";
$result = mysqli_query($con, $query);
while($d = mysqli_fetch_object($result)){

    if(is_file("../../src/produtos/icon/{$d->icon}")){
        $icon = "{$urlPainel}src/produtos/icon/{$d->icon}";
    }else{
        $icon = "img/imagem_produto.png";
    }

?>
    <div class="produto_painel" codigo="<?=$d->codigo?>" style="background-color:<?=(($d->promocao == '1')?'#bd0100':'trasparent')?>">
        <img src="<?=$icon?>" />
        <div class="w-100">
            <div class="produto_dados">
                <h4 style="color:<?=(($d->promocao == '1')?'#fbdb00':'#600f0b')?>"><?=$d->produto?></h4>
            </div>
            <div class="produto_dados" style="height:60px;">
                <div style="color:<?=(($d->promocao == '1')?'#ffffff':'#000000')?>; font-family:Uniform;"><?=$d->descricao?></div>
            </div>
            <div class="produto_dados">
                <?php
                if($d->promocao == '1'){
                ?>
                <div class="promocao">DE R$ <?=number_format($d->valor,2,",",false)?></div>
                <?php
                }
                ?>
                <div class="d-flex justify-content-between w-100" style="color:<?=(($d->promocao == '1')?'#fbdb00':'#f4352b')?>;">
                    <div><h2 class="produto_dados" style="margin-top:2px;"><span style="color:#fbdb00; font-size:12px;"><?=(($d->promocao == '1')?'POR ':false)?></span>R$ <?=number_format((($d->promocao == '1')?$d->valor_promocao:$d->valor),2,",",false)?></h2></div>
                    <i class="fa-solid fa-circle-play me-3" style="font-size:25px;"></i>
                </div>
            </div>            
        </div>
    </div>
<?php
}
////////////////////////////////////////FIM DOS PRODUTOS INDIVIDUAIS//////////////////////////////
?>
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


    $(".produto_painel").click(function(){
        Carregando();
        codigo = $(this).attr("codigo");
        idUnico = localStorage.getItem("idUnico");
        $.ajax({
            url:"produtos/detalhes_produto.php",
            type:"POST",
            data:{
                codigo,
                categoria:'<?=$d->categoria?>',
                idUnico,
                historico:'.CorpoApp'
            },
            success:function(dados){
                $(".CorpoApp").html(dados);
                Carregando('none')
            }
        });        

    })


    $(".combos").click(function(){
        Carregando();
        codigo = $(this).attr("codigo");
        idUnico = localStorage.getItem("idUnico");
        $.ajax({
            url:"produtos/detalhes_combo.php",
            type:"POST",
            data:{
                codigo,
                categoria:'8',
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