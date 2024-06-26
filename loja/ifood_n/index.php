<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    if($_POST['acao'] == 'salvar'){
        if($_POST['codigo']){
            $query = "update ifood set ifood = '{$_POST['ifood']}', data = NOW(), entregador = '{$_POST['entregador']}', situacao = '{$_POST['situacao']}' where codigo = '{$_POST['codigo']}'";
            mysqli_query($con, $query);
        }else{
            $query = "insert into ifood set ifood = '{$_POST['ifood']}', data = NOW(), entregador = '{$_POST['entregador']}', situacao = '{$_POST['situacao']}'";
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
            <label for="telefone" class="form-label">Número do Pedido ifood*</label>
            <input type="text" class="form-control" id="ifood" value="<?=$d->ifood?>" >
        </div>   
    </div>
    <div class="p-2">
        <div class="mb-3">
            <label for="telefone" class="form-label">Entregador*</label>
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
            <label for="telefone" class="form-label">Situação*</label>
            <select name="situacao" id="situacao" class="form-select">
                <option value="0" <?=(($d->situacao == '0')?'selected':false)?>>Pendente</option>
                <option value="1" <?=(($d->situacao == '1')?'selected':false)?>>Entregue</option>
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
        $(".salvar").click(function(){

            codigo = $("#codigo").val();
            ifood = $("#ifood").val();
            entregador = $("#entregador").val();
            situacao = $("#situacao").val();

            if(!ifood || !entregador || !situacao){
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
                    ifood,
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