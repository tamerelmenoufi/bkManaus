<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    if($_POST['acao'] == 'salvar'){
        if($_POST['codigo']){
            $query = "update ifood set 
                                        loja = '{$_POST['loja']}',
                                        ifood = '{$_POST['ifood']}',
                                        data = '{$_POST['data']}',
                                        valor = '{$_POST['valor']}',
                                        entregador = '{$_POST['entregador']}', 
                                        situacao = '{$_POST['situacao']}' 
                                where codigo = '{$_POST['codigo']}'";
            mysqli_query($con, $query);
        }else{
            $query = "insert into ifood set 
                                        loja = '{$_POST['loja']}',
                                        ifood = '{$_POST['ifood']}',
                                        data = '{$_POST['data']}',
                                        valor = '{$_POST['valor']}',
                                        entregador = '{$_POST['entregador']}', 
                                        situacao = '{$_POST['situacao']}'
                                        ";
            mysqli_query($con, $query);            
        }

    }


    if($_POST['codigo']){
        $query = "select * from ifood where codigo = '{$_POST['codigo']}'";
        $result = mysqli_query($con, $query);
        $d = mysqli_fetch_object($result);
    }



?>
<style>
    
</style>
<h4>Pedido do ifood</h4>
<div style="position:absolute; left:0; right:0; top:70px; bottom:0; overflow:auto; padding:10px;">
    <div class="p-2">
        <div class="mb-3">
            <label for="ifood" class="form-label">Número do Pedido ifood*</label>
            <input type="text" class="form-control" id="ifood" value="<?=$d->ifood?>" >
        </div>   
    </div>
    <div class="p-2">
        <div class="mb-3">
            <label for="data" class="form-label">Data do pedido*</label>
            <input type="datetime-local" class="form-control" id="data" value="<?=(($d->data)?:date("Y-m-d H:i:s"))?>" >
        </div>   
    </div>
    <div class="p-2">
        <div class="mb-3">
            <label for="valor" class="form-label">Valor da Compra*</label>
            <input type="text" class="form-control" id="valor" value="<?=$d->valor?>" >
        </div>   
    </div>    
    <div class="p-2">
        <div class="mb-3">
            <label for="entregador" class="form-label">Entregador*</label>
            <select name="entregador" id="entregador" class="form-select">
                <?php
                $q = "select * from entregadores where situacao = '1' and deletado != '1' order by nome";
                $r = mysqli_query($con, $q);
                while($s = mysqli_fetch_object($r)){
                ?>
                <option value="<?=$s->codigo?>" <?=(($s->codigo == $d->entregador)?'selected':false)?>><?=$s->nome?></option>                
                <?php
                }
                ?>
            </select>
        </div>
    </div>
    <div class="p-2">
        <div class="mb-3">
            <label for="situacao" class="form-label">Situação*</label>
            <select name="situacao" id="situacao" class="form-select">
                <option value="pendente" <?=(($d->situacao == 'pendente')?'selected':false)?>>Pendente</option>
                <option value="entregue" <?=(($d->situacao == 'entregue')?'selected':false)?>>Entregue</option>
            </select>
        </div>
    </div>
    <div class="p-2">
        <div class="mb-3">
            <button class="btn btn-success w-100 salvar">SALVAR DADOS</button>
            <input type="hidden" id="codigo" value="<?=$d->codigo?>">
        </div>
    </div>
</div>

<script>
    $(function(){

        $("#valor").maskMoney({
                                    prefix:'',
                                    allowNegative: false,
                                    thousands:'', 
                                    decimal:',', 
                                    affixesStay: ''
                                });

        $(".salvar").click(function(){
            loja = localStorage.getItem("loja");
            codigo = $("#codigo").val();
            ifood = $("#ifood").val();
            data = $("#data").val();
            valor = $("#valor").val();
            entregador = $("#entregador").val();
            situacao = $("#situacao").val();

            if(!ifood || !entregador || !situacao || !data || !valor){
                $.alert({
                    title:"Erro",
                    content:"Dados incompletos!",
                    type:"red"
                });

                return false;
            }

            $.ajax({
                url:"ifood_n/index.php",
                type:"POST",
                data:{
                    codigo,
                    loja,
                    ifood,
                    data,
                    valor,
                    entregador,
                    situacao,
                    acao:'salvar'
                },
                success:function(dados){
                    $.alert('Pedido Registrado!');
                    $(".popupPalco").html('');
                    $(".popupArea").css("display","none");
                }
            })

        })
    })
</script>