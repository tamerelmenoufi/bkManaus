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
    .tamanho{
        font-size:12px;
    }
    .largura{
        width:70px;
    }
    .categorias{
        margin-top:5px;
        border: solid 1px #ddd; 
        background-color:#ddd;
    }
    .grupo{
        border: solid 1px #ddd; 
    }
    
</style>
<h4>Pedido do ifood</h4>
<div style="position:absolute; left:0; right:0; top:70px; bottom:0; overflow:auto; padding:10px;">
    <?php
        $query = "select * from categorias where situacao = '1' and deletado != '1' order by ordem";
        $result = mysqli_query($con, $query);
        while($c = mysqli_fetch_object($result)){
    ?>
        <div acao="<?=$c->codigo?>">
            <div class="d-flex justify-content-between categorias">
                <div class="p-2"><?=$c->categoria?></div>
                <div class="p-2 icone"><i class="fa-solid fa-chevron-up"></i></div>
            </div>
        </div>
        <div grupo="<?=$c->codigo?>" style="display:none;" class="grupo">
<?php
                $query1 = "select * from produtos where categoria = '{$c->codigo}' and situacao = '1' and deletado != '1' order by produto";
                $result1 = mysqli_query($con, $query1);
                while($p = mysqli_fetch_object($result1)){
?>
            <div class="d-flex bd-highlight">
                <div class="p-1 flex-grow-1 bd-highlight tamanho">
                    <?=$p->produto?>
                </div>
                <div class="p-1 bd-highlight tamanho" style="width:90px;" >
                    <div class="d-flex justify-content-between">
                        <i class="fa-regular fa-square-minus" style="font-size:25px; mrgin-right:5px; color:red; opacity:0.5"></i>
                        <div style="width:40px; height:25px; border:solid 1px #ddd; text-align:center; padding:2px;">1</div>
                        <i class="fa-regular fa-square-plus" style="font-size:25px; mrgin-left:5px; color:green; opacity:0.5"></i>
                    </div>
                </div>
                <div class="p-1 bd-highlight tamanho largura" >
                    R$ <?=(($c->codigo == 8)?number_format(CalculaValorCombo($p->codigo),2,",",false):number_format($p->valor,2,",",false))?>
                </div>
            </div>
<?php
                }
?>
        </div>
<?php
        }
    ?>

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

        $("div[acao]").click(function(){
            $("div[grupo]").css("display","none");
            $("div[acao]").children("div").children("div.icone").children("i").addClass("fa-chevron-up")
            $(this).children("div").children("div.icone").children("i").removeClass("fa-chevron-up")
            $(this).children("div").children("div.icone").children("i").addClass("fa-chevron-down")
            opc = $(this).attr("acao");
            $(`div[grupo="${opc}"]`).css("display","block");
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