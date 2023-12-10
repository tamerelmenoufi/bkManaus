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
        <h4 class="w-100 text-center">Endereço(s) para entrega</h4>

        <?php
        $query = "select * from enderecos where cliente = '{$_SESSION['codUsr']}' order by codigo desc";
        $result = mysqli_query($con, $query);
        while($c = mysqli_fetch_object($result)){
        ?>
        <div class="d-flex justify-content-between">
            <div class="enderecoLabel" codigo="<?=$c->codigo?>">
                <i class="fa-solid fa-location-dot"></i>
                <?="{$c->logradouro}, {$c->numero}, {$c->bairro}"?>
            </div>
            <div class="d-flex justify-content-between">
            <span class="padraoRotulo" style="padding-right:5px; padding-left:5px; color:#a1a1a1; font-size:14px; white-space:nowrap; display:<?=(($c->padrao == '1')?'block':'none')?>" valor_taxa="12.50">R$ 12,50</span>
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

        cep = '<?=$d->cep?>';
        numero = '<?=$d->numero?>';
        ponto_referencia = '<?=$d->ponto_referencia?>';
        bairro = '<?=$d->bairro?>';
        localidade = '<?=$d->localidade?>';
        uf = '<?=$d->uf?>';



        if(!cep || !numero || !ponto_referencia || !bairro || !localidade || !uf){
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
            $(this).parent("div").parent("div").children("span").css("display","block");

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
                        success:function(dados){
                            $(".barra_topo").append(dados);
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