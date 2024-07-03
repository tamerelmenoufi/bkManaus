<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    if($_POST['idUnico']){
        $_SESSION['idUnico'] = $_POST['idUnico'];
    }

    if($_POST['codUsr']){
        $_SESSION['codUsr'] = $_POST['codUsr'];
        $where = " where codigo = '{$_SESSION['codUsr']}'";
    }

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
</style>

<div class="row g-0 p-2">
    <div class="card p-2">
        <h4 class="w-100 text-center">ENDEREÇO PARA ENTREGA</h4>

        <?php
        $query = "select * from enderecos where cliente = '{$_SESSION['codUsr']}' order by codigo desc";
        $result = mysqli_query($con, $query);
        while($c = mysqli_fetch_object($result)){

            if($c->padrao == '1'){
                $cep = $c->cep;
                $numero = $c->numero;
                $ponto_referencia = $c->ponto_referencia;
                $bairro = $c->bairro;
                $localidade = $c->localidade;
                $uf = $c->uf;
                $coo = $c->coordenadas;
            }
        ?>


<!-- -------------------------------------------------------------------- -->

<?php
        // $mottu = new mottu;
        $q = "select * from lojas where situacao = '1' and deletado != '1' and ('".date("H:i:s")."' between hora_ini and hora_fim)";
        $r = mysqli_query($con, $q);
        $vlopc = 0;
        if(mysqli_num_rows($r)){

            while($v = mysqli_fetch_object($r)){

                ////////// SOLUÇÃO MOTTU ////////////////////////////

                // $json = "{
                //     \"previewDeliveryTime\": true,
                //     \"sortByBestRoute\": false,

                //     \"deliveries\": [
                //         {
                //         \"orderRoute\": 111{$_SESSION['AppVenda']},
                //         \"address\": {
                //             \"street\": \"{$c->logradouro}\",
                //             \"number\": \"{$c->numero}\",
                //             \"complement\": \"{$c->complemento}\",
                //             \"neighborhood\": \"{$c->bairro}\",
                //             \"city\": \"Manaus\",
                //             \"state\": \"AM\",
                //             \"zipCode\": \"".str_replace(array(' ','-'), false, $c->cep)."\"
                //         },
                //         \"onlinePayment\": true
                //         }
                //     ]
                //     }";

                // $valores = json_decode($mottu->calculaFrete($json, $v->mottu));

                // if($valores->deliveryFee > 1){
                //     if($valores->deliveryFee <= $vlopc || $vlopc == 0) {
                //         // $vlopc = $valores->deliveryFee;
                //         $vlopc = 0.1;
                //         $codTaxa = $v->mottu;
                //         $unidade = $v->nome;
                //     }
                // }

                ////////// SOLUÇÃO MOTTU ////////////////////////////



                ////////// SOLUÇÃO PRÓPRIA ////////////////////////////
                

                $local = file_get_contents("https://maps.googleapis.com/maps/api/directions/json?destination={$c->coordenadas}&origin={$v->coordenadas}&key=AIzaSyBSnblPMOwEdteX5UPYXf7XUtJYcbypx6w");

                $local = json_decode($local);

                // echo $local->status."<br>".$local->routes[0]->legs[0]->distance->value."<br>".$v->coordenadas."<br><hr>";

                if($local->status == 'OK'){

                    $vl = $local->routes[0]->legs[0]->distance->value;
                    $distancia = $vl;
                    $vl = number_format($vl/1000,1,"-",false);
                    list($int, $dec) = explode("-", $vl);
                    $vl = ($int + (($dec > 0)?1:0) + 7); 
                    if($vl <= $vlopc || $vlopc == 0) {
                        $vlopc = $vl;
                        // $vlopc = 0.1;
                        $codTaxa = $v->mottu;
                        $loja = $v->codigo;
                        $unidade = $v->nome;
                    }
                }
                ////////// SOLUÇÃO PRÓPRIA ////////////////////////////

            }
    ?>

    <?php
        }
    ?>

<!-- ------------------------------------------------------------------------- -->



        <div class="d-flex justify-content-between">
            <div class="enderecoLabel" codigo="<?=$c->codigo?>">
                <i class="fa-solid fa-location-dot"></i> 
                <?="{$c->logradouro}, {$c->numero}, {$c->bairro}"?>
            </div>
            <div class="d-flex justify-content-between">
            <span class="padraoRotulo <?=(($c->padrao == '1')?'ativo':false)?>" style="padding-right:5px; padding-left:5px; color:#a1a1a1; font-size:14px; white-space:nowrap; display:<?=(($c->padrao == '1')?'block':'none')?>" distancia="<?=$distancia?>" valor_taxa="<?=$vlopc?>" codigo_taxa="<?=$codTaxa?>" loja="<?=$loja?>">R$ <?=number_format($vlopc,2,',',false)?></span>
            <div class="form-check form-switch">
                <input class="form-check-input padrao" type="radio" name="padrao" role="switch" value="<?=$c->codigo?>" <?=(($c->padrao == '1')?'checked':false)?> id="flexSwitchCheckDefault<?=$c->codigo?>">
            </div>
            </div>
        </div>
        <?php
        }
        ?>

        <div class="d-flex justify-content-between mt-3 atualizar1" style="display:none!important">    
            <div class="w-100 text-center">
                Para concluir a sua compra, necessário completar o seu cadastro.
                <button class="btn btn-danger w-100">
                    <i class="fa-solid fa-user-pen"></i>
                    Atualizar Cadastro aqui!       
                </button>
            </div>            
        </div>   
    </div>
</div>


<script>
    $(function(){

        cep = '<?=$cep?>';
        numero = '<?=$numero?>';
        ponto_referencia = '<?=$ponto_referencia?>';
        bairro = '<?=$bairro?>';
        localidade = '<?=$localidade?>';
        uf = '<?=$uf?>';



        if(/*!cep || */!numero || !ponto_referencia || !bairro || !localidade || !uf){
            // $(".dados_enderecos").remove()
            $(".dados_pagamento").remove()
            $(".atualizar1").css("display","block");
        }

        $(".atualizar1").click(function(){

            Carregando();
            url = $(this).attr("navegacao");
            idUnico = localStorage.getItem("idUnico");
            codUsr = localStorage.getItem("codUsr");
            $.ajax({
                url:"usuarios/perfil.php",
                type:"POST",
                data:{
                    idUnico,
                    codUsr,
                    historico:'.CorpoApp'
                },
                success:function(dados){
                    Carregando('none');
                    $(".CorpoApp").html(dados);
                }
            })

        });


        $(".padrao").change(function(){
            cod = $(this).val();
            $(".padraoRotulo").css("display","none");
            $(".padraoRotulo").removeClass("ativo");
            $(this).parent("div").parent("div").children("span").css("display","block");
            $(this).parent("div").parent("div").children("span").addClass("ativo");

            $(".dados_pagamento").html('');
            Carregando();
            idUnico = localStorage.getItem("idUnico");
            codUsr = localStorage.getItem("codUsr");
            $.ajax({
                url:"enderecos/lista_enderecos.php",
                type:"POST",
                data:{
                    idUnico,
                    codUsr,
                    cod,
                    acao:'padrao'
                },
                success:function(dados){
                    $(".barra_topo").html("<h2>Pagar</h2>");
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
                    $.ajax({
                        url:"pedido/pagamento.php",
                        success:function(dados){
                            $(".dados_pagamento").html(dados);
                            Carregando('none');
                        }
                    });

                },
                error:function(){
                    console.log('erro')
                }
            });
        })




    })
</script>