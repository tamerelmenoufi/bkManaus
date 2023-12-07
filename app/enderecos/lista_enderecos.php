<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    if($_POST['idUnico']){
        $_SESSION['idUnico'] = $_POST['idUnico'];
    }
    if($_POST['codUsr']){
        $_SESSION['codUsr'] = $_POST['codUsr'];
    }

    if($_POST['acao'] == 'padrao'){
        mysqli_query($con, "update enderecos set padrao = '0' where cliente = '{$_SESSION['codUsr']}'");
        mysqli_query($con, "update enderecos set padrao = '1' where codigo = '{$_POST['cod']}'");
        echo "update enderecos set padrao = '1' where codigo = '{$_POST['cod']}'";
        exit();
    }


?>
<style>
    .enderecoLabel{
        white-space: nowrap;
        width: 100%;
        overflow: hidden; /* "overflow" value must be different from "visible" */
        text-overflow: ellipsis;
        color:#333;
        font-size:14px;
    }
</style>
<div class="row g-0 p-2 mt-3">
    <div class="card p-2">
        <h4 class="w-100 text-center">Endereços</h4>

        <?php
        $query = "select * from enderecos where cliente = '{$_SESSION['codUsr']}'";
        $result = mysqli_query($con, $query);
        while($c = mysqli_fetch_object($result)){
        ?>
        <div class="d-flex justify-content-between">
            <div class="enderecoLabel">
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
        <p></p>
        <?php
        }
        ?>
        <hr>
        <label for="cep" class="form-label">Cadstro de endereço</label>
        <div class="input-group">
            <input type="text" class="form-control" autocomplete="off" id="cep" inputmode="numeric" placeholder="XXXXX-XXX" aria-label="Digite o CEP" aria-describedby="cadastro_cep">
            <button class="btn btn-outline-secondary cep" type="button" id="cadastro_cep">Avançar</button>
        </div>
        <div id="emailHelp" class="form-text">Avançar com o CEP preenchido agiliza o cadastro do seu endereço</div>
  </div>
    </div>
</div>


<script>
    $(function(){
        $("#cep").mask("99999-999");

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
                    console.log(dados)
                },
                error:function(){
                    console.log('erro')
                }
            });
        })


        $("#cadastro_cep").click(function(){
            cep = $("#cep").val();
            if(!cep || (cep.length == 9 && cep.substring(0,2) == 69)){
                idUnico = localStorage.getItem("idUnico");
                codUsr = localStorage.getItem("codUsr");
                $.ajax({
                    url:"enderecos/form.php",
                    type:"POST",
                    data:{
                        idUnico,
                        codUsr,
                        cep
                    },
                    success:function(dados){
                        JanelaForm = $.dialog({
                            title:"Endereço",
                            type:"green",
                            content:dados,
                            columnClass:'col-12'
                        })                        
                    }
                });

            }else if(cep.substring(0,2) != 69 || cep.length != 9){
                $.alert({
                    title:"Erro",
                    content:"CEP inválido ou fora da área de atendimento",
                    type:"red"
                })
            }
        })
    })
</script>