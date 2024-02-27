<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    if($_POST['loja']){
        $_SESSION['bkLoja'] = $_POST['loja'];
    }

    if($_POST['acao'] == 'insert'){
        $valor_compra = 0;
        $json = '{';
        foreach($_POST['pedido'] as $ind => $val){
            if($val['tipo'] == 'combo'){
                $js[] = "\"item{$val['codigo']}\": {
                            \"tipo\": \"combo\",
                            \"total\": {$val['valor']},
                            \"valor\": {$val['valor']},
                            \"codigo\": {$val['codigo']},
                            \"regras\": {
                                \"combo\": {
                                    \"remocao\": [],
                                    \"inclusao\": [],
                                    \"substituicao\": [],
                                    \"inclusao_valor\": [],
                                    \"substituicao_valor\": [],
                                    \"inclusao_quantidade\": []
                                }
                            },
                            \"status\": \"true\",
                            \"adicional\": 0,
                            \"anotacoes\": \"\",
                            \"quantidade\": {$val['quantidade']}
                        }";
                $valor_compra = $valor_compra + ($val['valor']*$val['quantidade']);
            }else{
                $js[] = "\"item{$val['codigo']}\": {
                        \"tipo\": \"produto\",
                        \"total\": {$val['valor']},
                        \"valor\": {$val['valor']},
                        \"codigo\": {$val['codigo']},
                        \"regras\": {
                            \"categoria\": \"{$val['categoria']}\"
                        },
                        \"status\": \"true\",
                        \"adicional\": 0,
                        \"anotacoes\": \"\",
                        \"quantidade\": {$val['quantidade']}
                    }";
                $valor_compra = $valor_compra + ($val['valor']*$val['quantidade']);
            }
        }
        $json .= implode(",", $js);
        $json .= '}';

        $valor_entrega = 10;
        $valor_total = ($valor_compra + $valor_entrega);

        echo $json;

        $query = "INSERT INTO vendas set 
                                        detalhes = '{$json}', 
                                        ifood = '".json_encode($_POST, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)."', 
                                        loja = '{$_SESSION['bkLoja']}', 
                                        pagamento = 'ifood', 
                                        data=NOW(),
                                        valor_compra = '{$valor_compra}',
                                        valor_entrega = '{$valor_entrega}',
                                        valor_total = '{$valor_total}',
                                        producao = 'pendente',
                                        situacao = 'pago'
                ";
        mysqli_query($con, $query);
        // print_r($_POST);
        exit();
    }


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

<div class="p-2">
    <div class="mb-3">
        <label for="telefone" class="form-label">Número do Pedido ifood*</label>
        <input type="text" class="form-control" id="codigo" >
    </div>   
</div>

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
                $k = 0;
                while($p = mysqli_fetch_object($result1)){
                    if($k%2 == 0) $bg = '#fff'; else $bg = '#eee';
?>
            <div l<?=$p->codigo?> class="d-flex bd-highlight">
                <div class="p-1 flex-grow-1 bd-highlight tamanho">
                    <?=$p->produto?>
                </div>
                <div class="p-1 bd-highlight tamanho" style="width:90px; background-color:<?=$bg?>" >
                    <div class="d-flex justify-content-between">
                        <i class="fa-regular fa-square-minus menos" cod="<?=$p->codigo?>" style="font-size:25px; mrgin-right:5px; color:red; opacity:0.5; cursor:pointer;"></i>
                        <div
                            style="width:40px; height:25px; text-align:center; padding:2px;"
                            cod="<?=$p->codigo?>"
                            qt="0"
                            tipo="<?=(($c->codigo == 8)?'combo':'produto')?>"
                            categoria="<?=$c->codigo?>"
                            valor="<?=(($c->codigo == 8)?CalculaValorCombo($p->codigo):$p->valor)?>"
                        >0</div>
                        <i class="fa-regular fa-square-plus mais" cod="<?=$p->codigo?>" style="font-size:25px; mrgin-left:5px; color:green; opacity:0.5; cursor:pointer;"></i>
                    </div>
                </div>
                <div class="p-1 bd-highlight tamanho largura" >
                    R$ <?=(($c->codigo == 8)?number_format(CalculaValorCombo($p->codigo),2,",",false):number_format($p->valor,2,",",false))?>
                </div>
            </div>
<?php
            $k++;
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
            <label for="nome" class="form-label">Nome do Cliente*</label>
            <input type="text" class="form-control" id="nome" >
        </div>

        <h4>Endereço para entrega</h4>
        <div class="mb-3">
            <label for="cep" class="form-label">CEP*</label>
            <input type="text" class="form-control" id="cep" >
        </div>
        <div class="mb-3">
            <label for="logradouro" class="form-label">Rua*</label>
            <input type="text" class="form-control" id="logradouro" >
        </div>
        <div class="mb-3">
            <label for="numero" class="form-label">Número*</label>
            <input type="text" class="form-control" id="numero" >
        </div>
        <div class="mb-3">
            <label for="complemento" class="form-label">Complemento</label>
            <input type="text" class="form-control" id="complemento" >
        </div>
        <div class="mb-3">
            <label for="ponto_referencia" class="form-label">Ponto de Referencia*</label>
            <input type="text" class="form-control" id="ponto_referencia" >
        </div>
        <div class="mb-3">
            <label for="bairro" class="form-label">Bairro*</label>
            <input type="text" class="form-control" id="bairro" >
        </div>
        <div class="mb-3">
            <button incluir type="button" class="btn btn-primary mb-3">Incluir Pedido</button>
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

        $(".mais").click(function(){
            cod = $(this).attr("cod");
            qt = $(`div[cod="${cod}"]`).attr("qt");
            qt = (qt*1 + 1);
            $(`div[cod="${cod}"]`).attr("qt",qt);
            $(`div[cod="${cod}"]`).html(qt);
            $(`div[l${cod}]`).css("font-weight","bold");
        })

        $(".menos").click(function(){
            cod = $(this).attr("cod");
            qt = $(`div[cod="${cod}"]`).attr("qt");
            if(qt > 0){
                qt = (qt*1 - 1);
            }else{
                qt = 0;
            }
            if(qt > 0){
                $(`div[l${cod}]`).css("font-weight","bold");
            }else{
                $(`div[l${cod}]`).css("font-weight","normal");
            }
            $(`div[cod="${cod}"]`).attr("qt",qt);
            $(`div[cod="${cod}"]`).html(qt);
        })


        $("#cep").blur(function(){
            cep = $(this).val();           
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

        $("button[incluir]").click(function(){

            codigo_ifood = $("#codigo").val();
            telefone = $("#telefone").val();
            nome = $("#nome").val();
            cep = $("#cep").val();
            logradouro = $("#logradouro").val();
            numero = $("#numero").val();
            complemento = $("#complemento").val();
            ponto_referencia = $("#ponto_referencia").val();
            bairro = $("#bairro").val();
            localidade = 'Manaus';
            uf = 'AM';

            //Verificação do código do pedido
            if(!codigo_ifood){
                $.alert({
                    content:'Favor informe o número do pedido!',
                    title:"Número do Pedido",
                    type:"red",
                })
                return false;
            }

            //Verificação dos produtos
            produtos = [];
            $("div[cod]").each(function(){
                codigo = $(this).attr("cod");
                categoria = $(this).attr("categoria");
                quantidade = $(this).attr("qt");
                valor = $(this).attr("valor");
                tipo = $(this).attr("tipo");
                if(quantidade > 0){
                    produtos.push({ codigo, quantidade, valor, tipo, categoria});
                }
            })
            if(!produtos.length){
                $.alert({
                    content:'Favor selecione o(s) produto(s) para criar o pedido!',
                    title:"Pedido sem Produtos",
                    type:"red",
                })
                return false;
            }

            //Verificação do cadastro do cliente
            if(!nome){
                $.alert({
                    content:'Favor informe o nome do cliente!',
                    title:"Dados do Cliente",
                    type:"red",
                })
                return false;                
            }

            //Verificação do endereço para entrega
            if(
                !cep || 
                !logradouro || 
                !numero || 
                !ponto_referencia || 
                !bairro
            ){
                $.alert({
                    content:'Preencha o endereço nos campos com * (obrigatórios)!',
                    title:"Endereço para entrega",
                    type:"red",
                })
                return false;                
            }



            geocoder<?=$md5?> = new google.maps.Geocoder();
            geocoder<?=$md5?>.geocode({ 'address': `Rua ${logradouro}, ${numero}, ${bairro}, Manaus, Amazonas, Brasil`, 'region': 'BR' }, (results, status) => {

                if (status == google.maps.GeocoderStatus.OK) {

                    if (results[0]) {

                        var latitude<?=$md5?> = results[0].geometry.location.lat();
                        var longitude<?=$md5?> = results[0].geometry.location.lng();

                        coordenadas = `${latitude<?=$md5?>},${longitude<?=$md5?>}`;

                        Carregando();

                        data = {"cliente":{nome, telefone}, "endereco":{cep, logradouro, numero, complemento, ponto_referencia, bairro, localidade, uf}, "codigo":codigo_ifood, coordenadas, "pedido":produtos, "acao":"insert", "loja":"<?=$_SESSION['bkLoja']?>"};

                        $.ajax({
                            url:"ifood/index.php",
                            type:"POST",
                            data,
                            success:function(dados){
                                console.log(dados);
                                $(".popupPalco").html('');
                                $(".popupArea").css("display","none");
                                Carregando('none');
                            }
                        });                        

                    }
                }
            })










        });

    })
</script>