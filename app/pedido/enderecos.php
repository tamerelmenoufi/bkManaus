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
        <h4 class="w-100 text-center">Dados do Cliente</h4>

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
            <span class="padraoRotulo" style="padding-right:5px; padding-left:5px; color:#a1a1a1; font-size:14px; display:<?=(($c->padrao == '1')?'block':'none')?>">Padrão</span>
            <div class="form-check form-switch">
                <input class="form-check-input padrao" type="radio" name="padrao" role="switch" value="<?=$c->codigo?>" <?=(($c->padrao == '1')?'checked':false)?> id="flexSwitchCheckDefault<?=$c->codigo?>">
            </div>
            </div>
        </div>
        <?php
        }
        ?>

        
    </div>
</div>


<script>
    $(function(){


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