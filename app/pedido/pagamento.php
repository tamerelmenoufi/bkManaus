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

<!-- -------------------------------------------------------------------- -->

<?php
        $mottu = new mottu;
        $q = "select * from lojas where mottu > 0 /*situacao = '1' and deletado != '1'*/";
        $r = mysqli_query($con, $q);
        $vlopc = 0;
        if(mysqli_num_rows($r)){

            $e = mysqli_fetch_object(mysqli_query($con, "select * from enderecos where cliente = '{$_SESSION['codUsr']}' and padrao = '1'"));

            while($v = mysqli_fetch_object($r)){

                $json = "{
                    \"previewDeliveryTime\": true,
                    \"sortByBestRoute\": false,

                    \"deliveries\": [
                        {
                        \"orderRoute\": 111{$_SESSION['AppVenda']},
                        \"address\": {
                            \"street\": \"{$e->logradouro}\",
                            \"number\": \"{$e->numero}\",
                            \"complement\": \"{$e->complemento}\",
                            \"neighborhood\": \"{$e->bairro}\",
                            \"city\": \"Manaus\",
                            \"state\": \"AM\",
                            \"zipCode\": \"".str_replace(array(' ','-'), false, $e->cep)."\"
                        },
                        \"onlinePayment\": true
                        }
                    ]
                    }";

                $valores = json_decode($mottu->calculaFrete($json, $v->mottu));

                if($valores->deliveryFee > 1){
                    if($valores->deliveryFee <= $vlopc || $vlopc == 0) {
                        $vlopc = $valores->deliveryFee;
                        $unidade = $v->nome;
                    }
                }
            }
    ?>
            <div class="d-flex justify-content-between">    
                <div class="enderecoLabel w-100" >
                    <i class="fa-solid fa-location-dot"></i>
                    Taxa de Entrega
                </div>
                <span class="valores" taxa_entraga>R$ <?=number_format($vlopc,2,',',false)?></span> 
            </div>
    <?php
        }
    ?>

<!-- ------------------------------------------------------------------------- -->




            <div class="d-flex justify-content-between">    
                <div class="enderecoLabel w-100">
                    <i class="fa-solid fa-location-dot"></i>
                    Total a Pagar
                </div>
                <span class="valores" pagar>R$ 69,90</span> 
            </div>



            

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