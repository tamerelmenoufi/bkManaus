<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");


    if($_POST['cep']){
        $cep = str_replace('-',false,$_POST['cep']);
        $d = ConsultaCEP($cep);
        echo json_encode($d);
        exit();
    }
?>
<style>
    th{
        font-size:13px;
    }
    td{
        font-size:12px;
    }

    tr{
        border: solid 1px green;
    }
    
</style>
<h4>Pedido do ifood</h4>
<div style="position:absolute; left:0; right:0; top:70px; bottom:0; overflow:auto;">
    <table class="table table-hover">
    <?php
        $query = "select * from categorias where situacao = '1' and deletado != '1' order by ordem";
        $result = mysqli_query($con, $query);
        while($c = mysqli_fetch_object($result)){
    ?>
        <thead acao="<?=$c->codigo?>">
            <tr>
                <th colspan="4"><div style="margin-top:20px;"><?=$c->categoria?></div></th>
            </tr>
        </thead>
        <tbody grupo="<?=$c->codigo?>" style="display:none;">
    <?php
            $query1 = "select * from produtos where categoria = '{$c->codigo}' and situacao = '1' and deletado != '1' order by produto";
            $result1 = mysqli_query($con, $query1);
            while($p = mysqli_fetch_object($result1)){
    ?>
            <tr>
                <td>
                    <div class="form-check">
                        <input class="form-check-input" id="produto<?=$p->codigo?>" type="checkbox" value="<?=$p->codigo?>" valor="<?=(($c->codigo == 8)?CalculaValorCombo($p->codigo):$p->valor)?>" >
                        <label class="form-check-label" for="produto<?=$p->codigo?>">
                            <?=$p->produto?>
                        </label>
                    </div>
                </td>
                <td style="width:70px;">
                <input type="number" class="form-control form-control-sm" v<?=$p->codigo?>></td>
                <td>R$ <?=(($c->codigo == 8)?number_format(CalculaValorCombo($p->codigo),2,",",false):number_format($p->valor,2,",",false))?></td>
            </tr>
    <?php
            }
    ?>
        </tbody>
    <?php
        }
    ?>
    </table>

    <div class="p-2">
        <h4>Cliente</h4>
        <div class="mb-3">
            <label for="telefone" class="form-label">Telefone</label>
            <input type="text" class="form-control" id="telefone" >
        </div>        
        <div class="mb-3">
            <label for="nome" class="form-label">Nome do Cliente</label>
            <input type="text" class="form-control" id="nome" >
        </div>

        <h4>Endereço para entrega</h4>
        <div class="mb-3">
            <label for="cep" class="form-label">CEP</label>
            <input type="text" class="form-control" id="cep" >
        </div>
        <div class="mb-3">
            <label for="logradouro" class="form-label">Rua</label>
            <input type="text" class="form-control" id="logradouro" >
        </div>
        <div class="mb-3">
            <label for="numero" class="form-label">Número</label>
            <input type="text" class="form-control" id="numero" >
        </div>
        <div class="mb-3">
            <label for="complemento" class="form-label">Complemento</label>
            <input type="text" class="form-control" id="complemento" >
        </div>
        <div class="mb-3">
            <label for="ponto_referencia" class="form-label">Ponto de Referencia</label>
            <input type="text" class="form-control" id="ponto_referencia" >
        </div>
        <div class="mb-3">
            <label for="bairro" class="form-label">Bairro</label>
            <input type="text" class="form-control" id="bairro" >
        </div>
        <div class="mb-3">
            <button type="submit" class="btn btn-primary mb-3">Incluir Pedido</button>
        </div>
    </div>
</div>


<script>
    $(function(){

        $("#telefone").mask("(92) 99188-6570");
        $("#cep").mask("99999-999");

        $("thead[acao]").click(function(){
            $("tbody[grupo]").css("display","none");
            opc = $(this).attr("acao");
            $(`tbody[grupo="${opc}"]`).css("display","block");
        })


        $("#cep").blur(function(){
            cep = $(this).val();
            // if(!cep || (cep.length == 9 && cep.substring(0,2) == 69)){
            //     idUnico = localStorage.getItem("idUnico");
            //     codUsr = localStorage.getItem("codUsr");
            //     $.ajax({
            //         url:"enderecos/form.php",
            //         type:"POST",
            //         data:{
            //             idUnico,
            //             codUsr,
            //             cep
            //         },
            //         success:function(dados){
            //             $(".dados_enderecos").html(dados);                     
            //         }
            //     });

            // }else 
            
            if( cep.length > 0 && (cep.substring(0,2) != 69 || cep.length != 9)){
                $.alert({
                    title:"Erro",
                    content:"CEP inválido ou fora da área de atendimento",
                    type:"red"
                })
            }else{
                $.ajax({
                    url:"ifood/index.php",
                    type:"POST",
                    dataType:"JSON",
                    data:{
                        cep,
                    },
                    success:function(dados){
                        // $(".dados_enderecos").html(dados);   
                        
                        //cep = $("#cep").val(dados.cep);
                        logradouro = $("#logradouro").val(dados.logradouro);
                        complemento = $("#complemento").val();
                        bairro = $("#bairro").val(dados.bairro);
                        //localidade = $("#localidade").val(dados.localidade);

                    }
                });
            }
        })

    })
</script>