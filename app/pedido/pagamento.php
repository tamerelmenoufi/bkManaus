<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    if($_POST['idUnico']){
        $_SESSION['idUnico'] = $_POST['idUnico'];
    }

    if($_POST['codUsr']){
        $_SESSION['codUsr'] = $_POST['codUsr'];
        $where = " where codigo = '{$_SESSION['codUsr']}'";
    }

    $query = "select * from clientes where codigo = '{$_POST['codUsr']}'";

    $result = mysqli_query($con, $query);

    $d = mysqli_fetch_object($result);

?>

<style>
    .enderecoLabel{
        white-space: nowrap;
        overflow: hidden; /* "overflow" value must be different from "visible" */
        text-overflow: ellipsis;
        color:#333;
        font-size:14px;
        cursor:pointer;
    }
    .valores{
        white-space: nowrap;
    }
</style>

<div class="row g-0 p-2">
    <div class="card p-2">
        <h4 class="w-100 text-center">Pagamento</h4>

            <div class="d-flex justify-content-between">    
                <div class="enderecoLabel w-100" >
                    <i class="fa-solid fa-location-dot"></i>
                    Total da compra
                </div>
                <span class="valores" total></span> 
            </div>  
            <div class="d-flex justify-content-between">    
                <div class="enderecoLabel w-100" >
                    <i class="fa-solid fa-location-dot"></i>
                    Taxa de Entrega
                </div>
                <span class="valores" taxa_entraga>R$ 12,00</span> 
            </div>
            <div class="d-flex justify-content-between">    
                <div class="enderecoLabel w-100">
                    <i class="fa-solid fa-location-dot"></i>
                    Total a Pagar
                </div>
                <span class="valores" pagar>R$ 69,90</span> 
            </div>


<!-- -------------------------------------------------------------------- -->






<div id="<?=$md5?>" class="collapseXXX" aria-labelledby="headingOne" data-parent="#accordion">
    <ul class="list-group">
    <?php
        $mottu = new mottu;
        $q = "select * from lojas where mottu > 0 /*situacao = '1' and deletado != '1'*/";
        $r = mysqli_query($con, $q);
        $vlopc = 0;
        if(mysqli_num_rows($r)){
        while($v = mysqli_fetch_object($r)){

            $json = "{
                \"previewDeliveryTime\": true,
                \"sortByBestRoute\": false,

                \"deliveries\": [
                    {
                    \"orderRoute\": 111{$_SESSION['AppVenda']},
                    \"address\": {
                        \"street\": \"{$c->logradouro}\",
                        \"number\": \"{$c->numero}\",
                        \"complement\": \"{$c->complemento}\",
                        \"neighborhood\": \"{$c->bairro}\",
                        \"city\": \"Manaus\",
                        \"state\": \"AM\",
                        \"zipCode\": \"".str_replace(array(' ','-'), false, $c->cep)."\"
                    },
                    \"onlinePayment\": true
                    }
                ]
                }";

            $valores = json_decode($mottu->calculaFrete($json, $v->mottu));

            // var_dump($v);
            if($valores->deliveryFee > 1 or 1 == 1){

            if($valores->deliveryFee <= $vlopc || $vlopc == 0) {
                $vlopc = $valores->deliveryFee;
                $opc = $v->codigo; //Opção mais barata
                // $opc = $d->loja; //Opção de preferência do cliente

            }

    ?>
        <li
            opc="<?=$v->codigo?>"
            LjId="<?=$v->id?>"
            endereco="<?=$v->endereco?>"
            valor="<?=$valores->deliveryFee?>"
            class="opcLoja list-group-item d-flex justify-content-between align-items-center">
            <small><?=$v->nome?></small>
            <span class="badge badge-pill">
                <small>R$ <?=number_format($valores->deliveryFee,2,'.',false)?></small>
            </span>

        </li>
    <?php
            }
        }
        }
    ?>
    </ul>
</div>









<!-- ------------------------------------------------------------------------- -->
            

            <div class="d-flex justify-content-between mt-3">    
                <div class="enderecoLabel w-100 text-center pe-2">
                    <button class="btn btn-success w-100">
                        <i class="fa-brands fa-pix"></i>
                        PIX                        
                    </button>
                </div>
                <div class="enderecoLabel w-100 text-center ps-2">
                    <button class="btn btn-success w-100">
                        <i class="fa-regular fa-credit-card"></i>
                        Crédito                        
                    </button>
                </div>
            </div>

    </div>
</div>


<script>
    $(function(){

        total = ($("div[total]").attr("total"))*1;
        taxa = ($("span[valor_taxa]").attr("valor_taxa"))*1;
        pagar = (total*1+taxa*1);

        $("span[total]").html('R$ ' + total.toLocaleString('pt-br', {minimumFractionDigits: 2}));
        $("span[taxa_entraga]").html('R$ ' + taxa.toLocaleString('pt-br', {minimumFractionDigits: 2}));
        $("span[pagar]").html('R$ ' + pagar.toLocaleString('pt-br', {minimumFractionDigits: 2}));


    })
</script>