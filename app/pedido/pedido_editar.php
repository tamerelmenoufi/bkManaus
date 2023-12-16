<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    if($_POST['idUnico']){
        $_SESSION['idUnico'] = $_POST['idUnico'];
    }
    if($_POST['codUsr']){
        $_SESSION['codUsr'] = $_POST['codUsr'];
    }

    if($_POST['codVenda']){
        $_SESSION['codVenda'] = $_POST['codVenda'];
    }

    $query = "select * from vendas where codigo = '{$_SESSION['codVenda']}'";
    $result = mysqli_query($con, $query);
    $d = mysqli_fetch_object($result);

?>


<style>
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


<div class="row g-0 p-2 mt-3">
    <div class="card p-2">
        <h4 class="w-100 text-center">RESUMO DO PEDIDO</h4>
                <?php

                    foreach(json_decode($d->detalhes) as $i => $dados){

                        $pd = mysqli_fetch_object(mysqli_query($con, "select * from produtos where codigo = '{$dados->codigo}'"));
                        if($dados->status){
                ?>
            <div class="d-flex justify-content-between">    
                <div class="enderecoLabel w-70" codigo="<?=$c->codigo?>">
                    <i class="fa-solid fa-location-dot"></i>
                    <?=$dados->quantidade?> x <?=$pd->produto?>
                </div> 
                <div class="d-flex justify-content-between">
                    <!-- <div>R$ <?=number_format($dados->total,2,',',false)?></div> -->
                    <div class="w-100 text-end">R$ <?=number_format($dados->total*$dados->quantidade,2,',',false)?></div>
                </div>
            </div>    
            <?php
                $total = ($total + ($dados->total*$dados->quantidade));

                        }
                    }
            ?>
            <div class="w-100 text-end" total="<?=$total?>"><b>TOTAL R$ <?=number_format($total,2,',',false)?></b></div>
        
    </div>
</div>


<?php
    foreach(json_decode($d->detalhes) as $i => $dados){
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

<script>

$(function(){

    Tempo = false;

    idUnico = localStorage.getItem("idUnico");
    codUsr = localStorage.getItem("codUsr");

})

</script>