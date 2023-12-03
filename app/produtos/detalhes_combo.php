<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    $c = mysqli_fetch_object(mysqli_query($con, "select * from categorias where codigo = '{$_SESSION['categoria']}'"));

    $acoes = json_decode($c->acoes_itens);

    if($_POST['acao'] == 'anotacoes'){

        print_r($_POST);

        exit();

        $data = $_POST;
        unset($data['acao']);
        unset($data['codigo']);
        unset($data['idUnico']);
        unset($data['quantidade']);
        unset($data['valor']);
        unset($data['anotacoes']);

        $valor_adicional = 0;
        if($data['inclusao']){
            foreach($data['inclusao'] as $i => $v){
                $valor_adicional = $valor_adicional + ($data['inclusao_valor'][$i]*$data['inclusao_quantidade'][$i]);
            }
        }

        if($data['substituicao']){
            foreach($data['substituicao'] as $i => $v){
                $valor_adicional = $valor_adicional + ($data['substituicao_valor'][$i]*1);
            }
        }

        $update = [
            'regras' => $data,
            'anotacoes' => $_POST['anotacoes'],
            'adicional' => ($valor_adicional*1),
            'valor' => ($_POST['valor']*1),
            'total' => ($valor_adicional + $_POST['valor']),
            'quantidade' => ($_POST['quantidade']*1),
            'codigo' => ($_POST['codigo']*1),
            'status' => false,
        ];

        echo $update = json_encode($update, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        // mysqli_query($con, "UPDATE vendas_tmp set detalhes = JSON_SET(detalhes, '$.item{$_POST['codigo']}', JSON_EXTRACT('{$update}', '$')) where id_unico = '{$_POST['idUnico']}'");

        // echo (($valor_adicional + $_POST['valor'])); //*($_POST['quantidade']*1));
        exit();
        
    }

    if($_POST['acao'] == 'salvar'){

        echo $q = "update vendas_tmp set detalhes = JSON_SET(detalhes, 
                                                '$.item{$_POST['codigo']}.quantidade', '{$_POST['quantidade']}',
                                                '$.item{$_POST['codigo']}.status' , 'true')
                            where id_unico = '{$_POST['idUnico']}'";

        mysqli_query($con, $q);

        exit();
    }

    
    $query = "select *, itens->>'$[*].item' as lista_itens, produtos->>'$[*].produto' as cod_prod, produtos->>'$[*].quantidade' as qtd_prod from produtos where codigo = '{$_POST['codigo']}'";
    $result = mysqli_query($con, $query);
    $d = mysqli_fetch_object($result);

    $lista_produtos = json_decode($d->cod_prod);
    if($lista_produtos){
        $cods = implode(", ",$lista_produtos);
        $q = "select * from produtos where codigo in ($cods)";
        $r = mysqli_query($con, $q);
        $prd = [];
        while($d1 = mysqli_fetch_object($r)){
            $prd[] = $d1->produto;
        }

        $prd = implode(", ", $prd);
    }





    $tmp = mysqli_fetch_object(mysqli_query($con, "select detalhes->>'$.item{$d->codigo}' as produto from vendas_tmp where id_unico = '{$_POST['idUnico']}'"));


    $dc = json_decode($tmp->produto);
    
    if($dc->codigo){
        $valor_calculado = $dc->total;
        $quantidade = $dc->quantidade;
    }else{
        $valor_calculado = $d->valor;
        $quantidade = 1;        
    }
    

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
        margin-bottom:20px;
    }
    .produto_detalhes{
        padding:2px;
        border:solid 1px #ccc;
        border-radius:5px;
        font-family:Uniform;
        margin-bottom:10px;
        margin-top:10px;
    }
    .produtos{
        color:#c45018; 
        /* overflow: hidden; 
        height:20px; */
        font-family:FlameBold; 
        font-size:16px; 
        text-align:center;
        width:100%;
        /* display: -webkit-box; 
        -webkit-box-orient: vertical; 
        -webkit-line-clamp: 1; */
    }

    
</style>

<div class="barra_topo">
    <h2><?=$c->categoria?></h2>
</div>


<div class="home_corpo">
    <div class="produto_painel" codigo="<?=$d->codigo?>">
        <h1 class="produto_titulo"><?=$d->produto?></h1>
        <div class="produtos"><?=$prd?></div>
        <img src="img/logo.png" class="produto_img" />
        <!-- <div class="produto_detalhes d-flex justify-content-between align-items-center w-100">
            <div style="cursor:pointer">
                <i class="fa-regular fa-message fa-flip-horizontal"></i>
                Observações aqui
            </div>
            <button type="button" class="btn btn-outline-secondary btn-sm">Anotações</button>
        </div>    -->
        <div class="produto_descricao"><?=$d->descricao?></div>



<?php
    if($lista_produtos){

        

        $cods = implode(", ",$lista_produtos);
        $qp = "select a.*, a.itens->>'$[*].item' as lista_itens, a.produtos->>'$[*].produto' as cod_prod, a.produtos->>'$[*].quantidade' as qtd_prod, b.acoes_itens from produtos a left join categorias b on a.categoria = b.codigo where a.codigo in ($cods)";
        $rp = mysqli_query($con, $qp);
        $prd = [];
        while($d1 = mysqli_fetch_object($rp)){


            $acoes = json_decode($d1->acoes_itens);

            $itens = json_decode($d1->lista_itens);
            $categorias_itens = json_decode($d1->categorias_itens); 

?>
<!-- NOVO -->

        <?php

        if($acoes->remocao == 'true' and $itens and $itens != 'null'){

        ?>

        <div class="card w-100 mb-3">
        <div class="card-header">
            Retirar algum Item do <?=$d1->produto?>?
        </div>
        <ul class="list-group list-group-flush">
            <?php
            $q = "select * from itens where codigo in ('".implode("', '", $itens)."')";
            $r = mysqli_query($con, $q);
            while($i = mysqli_fetch_object($r)){
            ?>
            <li class="list-group-item">
                <div class="form-check">
                    <input type="checkbox" <?=(($remocao[$i->codigo] == $i->codigo)?'checked':false)?> class="form-check-input remocao" produto="<?=$d1->codigo?>" codigo="<?=$i->codigo?>" id="remocao<?=$i->codigo?>-<?=$d1->codigo?>">
                    <label class="form-check-label" for="remocao<?=$i->codigo?>-<?=$d1->codigo?>"><?=$i->item?></label>
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
                Incluir algum Item no <?=$d1->produto?>?
            </div>
            <ul class="list-group list-group-flush">
                <?php
                $q = "select * from itens where categoria in ('".implode("', '", $categorias_itens)."')";
                $r = mysqli_query($con, $q);
                while($i = mysqli_fetch_object($r)){
                ?>
                <li class="list-group-item">
                    <!-- <div class="form-check">
                        <input type="checkbox" class="form-check-input inclusao" <?=(($inclusao[$i->codigo] == $i->codigo)?'checked':false)?> valor="<?=$i->valor?>" codigo="<?=$i->codigo?>" id="inclusao<?=$i->codigo?>">
                        <label class="form-check-label" for="inclusao<?=$i->codigo?>"><?=$i->item?></label>
                    </div> -->
                    <?=$i->item?>
                    <div class="input-group">
                        <select class="form-select form-select-sm col-3 inclusao" valor="<?=$i->valor?>" produto="<?=$d1->codigo?>" codigo="<?=$i->codigo?>" id="inclusao_quantidade<?=$i->codigo?>-<?=$d1->codigo?>">
                            <?php
                            for($j=0;$j<=10;$j++){
                            ?>
                            <option value="<?=$j?>" <?=(($inclusao_quantidade[$i->codigo] == $j)?'selected':false)?>><?=$j?></option>
                            <?php
                            }
                            ?>
                        </select>
                        <div class="d-flex justify-content-end input-group-text col-9" style="text-align:right;" for="inclusao_valor<?=$i->codigo?>-<?=$d1->codigo?>">R$ <?=number_format($i->valor, 2, ",", false)?></div>
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
                Substituir <?=$d1->produto?>?
            </div>
            <ul class="list-group list-group-flush">
                <?php
                $q = "select * from itens where categoria in ('".implode("', '", $categorias_itens)."')";
                $r = mysqli_query($con, $q);
                while($i = mysqli_fetch_object($r)){
                ?>
                <li class="list-group-item d-flex justify-content-between">
                    <div class="form-check">
                        <input type="radio" class="form-check-input substituicao" name="substituicao" produto="<?=$d1->codigo?>" codigo="<?=$i->codigo?>" valor="<?=$i->valor?>" id="substituicao<?=$i->codigo?>-<?=$d1->codigo?>">
                        <label class="form-check-label" for="substituicao<?=$i->codigo?>-<?=$d1->codigo?>"><?=$i->item?></label>
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

<!-- NOVO -->
<?php
        }
    }
?>


        <div class="mb-3 w-100">
        <label for="anotacoes" class="form-label">
            <i class="fa-regular fa-message fa-flip-horizontal"></i>
            Anotações do pedido
        </label>
        <textarea class="form-control" id="anotacoes" rows="3"><?=$anotacoes?></textarea>
        </div>

          
    </div>
</div>
<div class="produto_botoes d-flex justify-content-between">
    <div class="d-flex justify-content-between">
        <i class="fa-solid fa-circle-minus menos" style="color:red"></i>
        <div class="qt" style="margin-top:-8px; text-align:center; width:60px; font-family:UniformBold;"><?=$quantidade?></div>
        <i class="fa-solid fa-circle-plus mais" style="color:green"></i>
    </div>
    <div>
        <button type="button" class="btn btn-danger adicionar" valor="<?=$valor_calculado?>" style="font-family:FlameBold; font-size:25px; margin-top:-20px;">R$ <?=number_format(($valor_calculado*$quantidade),2,",",false)?></button>
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
        $(".qt").text(qt);
        total = (valor*qt);
        $(".adicionar").html('R$ ' + total.toLocaleString('pt-br', {minimumFractionDigits: 2}));                
    })

    $(".menos").click(function(){
        valor = $(".adicionar").attr("valor");
        qt = $(".qt").text();
        qt = (((qt*1 - 1)>1)?(qt*1 - 1):1);
        $(".qt").text(qt);
        total = (valor*qt);
        $(".adicionar").html('R$ ' + total.toLocaleString('pt-br', {minimumFractionDigits: 2}));                
    })


    $(".barra_topo").click(function(){

        $.ajax({
            url:"produtos/lista_combos.php",
            success:function(dados){
                $(".CorpoApp").html(dados);
            }
        });        

    })

    $(".produto_detalhes").click(function(){

        quantidade = $(".qt").text();
        idUnico = localStorage.getItem("idUnico");
        
        $.ajax({
            url:"produtos/anotacoes_produto.php",
            type:"POST",
            data:{
                codigo:'<?=$d->codigo?>',
                quantidade,
                idUnico,
            },
            success:function(dados){
                $(".CorpoApp").html(dados);
            }
        });           

    })


    definirDetalhes = () => {

        combo = {};
        remocao = [];
        inclusao = [];
        inclusao_valor = [];
        inclusao_quantidade = [];
        substituicao = [];
        substituicao_valor = [];
        anotacoes = $("#anotacoes").val();
        idUnico = localStorage.getItem("idUnico");
        qt = $(".qt").text();

        $(".remocao").each(function(){
            produto = ($(this).attr("produto"))*1;
            codigo = ($(this).attr("codigo"))*1;
            acao = $(this).prop("checked")
            if(!combo.produto) combo.produto = [];
            if(!combo.produto.codigo) combo.produto.codigo = produto;
            if(!combo.produto.remocao) combo.produto.remocao = [];
            if(acao == true){
                combo.produto.remocao.push(codigo);
            }
        })

        $(".inclusao").each(function(){
            produto = ($(this).attr("produto"))*1;
            codigo = ($(this).attr("codigo"))*1;
            valor = ($(this).attr("valor"))*1;
            quantidade = ($(this).val())*1;
            if(!combo.produto) combo.produto = [];
            if(!combo.produto.codigo) combo.produto.codigo = produto;
            if(!combo.produto.inclusao) combo.produto.inclusao = [];
            if(!combo.produto.inclusao_valor) combo.produto.inclusao_valor = [];
            if(!combo.produto.inclusao_quantidade) combo.produto.inclusao_quantidade = [];
            if(quantidade > 0){
                combo.produto.inclusao.push(codigo);
                combo.produto.inclusao_valor.push(valor);
                combo.produto.inclusao_quantidade.push(quantidade);               
            }
        })

        $(".substituicao").each(function(){
            produto = ($(this).attr("produto"))*1;
            codigo = ($(this).attr("codigo"))*1;
            valor = ($(this).attr("valor"))*1;
            if(!combo.produto) combo.produto = [];
            if(!combo.produto.codigo) combo.produto.codigo = produto;
            if(!combo.produto.substituicao) combo.produto.substituicao = [];
            if(!combo.produto.substituicao_valor) combo.produto.substituicao_valor = [];
            if($(this).prop("checked") == true){
                combo.produto.substituicao.push(codigo);
                combo.produto.substituicao_valor.push(valor);
            }
        })

        console.log(combo);
        combo = parseJSON(combo)
        // combo = JSON.stringify(combo)
        console.log(combo);
        // return false;

        // Carregando();
        $.ajax({
            url:"produtos/detalhes_combo.php",
            type:"POST",
            data:{
                codigo:'<?=$d->codigo?>',
                valor:'<?=$d->valor?>',
                quantidade:qt,
                combo,
                // remocao,
                // inclusao,
                // inclusao_valor,
                // inclusao_quantidade,
                // substituicao,
                // substituicao_valor,
                anotacoes,
                idUnico,
                acao:'anotacoes'
            },
            success:function(dados){

                console.log(dados)

                // valor = (dados*1);
                // $(".adicionar").html('R$ ' + (valor*qt).toLocaleString('pt-br', {minimumFractionDigits: 2}));  
                // $(".adicionar").attr("valor", valor);  
            }
        });        

    }

    $(".inclusao, .remocao").change(function(){
        definirDetalhes();
    })

    $(".substituicao").change(function(){
        obj = $(this);
        acao = obj.prop("checked");
        $(".substituicao").prop("checked", false);
        if(acao) obj.prop("checked", true);
        definirDetalhes();
    })    

    $("#anotacoes").blur(function(){
        definirDetalhes();
    })

    $(".adicionar").click(function(){

        Carregando();

        quantidade = $(".qt").text();
        idUnico = localStorage.getItem("idUnico");
        
        $.ajax({
            url:"produtos/detalhes_combo.php",
            type:"POST",
            data:{
                codigo:'<?=$d->codigo?>',
                quantidade,
                idUnico,
                acao:'salvar',
            },
            success:function(dados){
                console.log(dados);
                $.ajax({
                    url:"produtos/lista_combos.php",
                    success:function(dados){  
                        $(".CorpoApp").html(dados);
                        Carregando('none');
                    }
                }); 


            }
        });           

    })

})

	

</script>