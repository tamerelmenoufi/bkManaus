<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    if($_POST['acao'] == 'salvar'){
        $query = "insert into ifood set ifood = '', data = NOW(), entregador = '{$_POST['entregador']}', situacao = '{$_POST['situacao']}'";
        mysqli_query($con, $query);
    }

?>
<style>
    
</style>
<h4>Pedido do ifood</h4>
<div style="position:absolute; left:0; right:0; top:70px; bottom:0; overflow:auto; padding:10px;">
    <div class="p-2">
        <div class="mb-3">
            <label for="telefone" class="form-label">Número do Pedido ifood*</label>
            <input type="text" class="form-control" id="ifood" >
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
                <option value="<?=$s->codigo?>"><?=$s->nome?></option>                
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
                <option value="0">Pendente</option>
                <option value="1">Entregue</option>
            </select>
        </div>
    </div>
    <div class="p-2">
        <div class="mb-3">
            <button class="btn btn-success w-100 salvar">SALVAR DADOS</button>
        </div>
    </div>
</div>

<script>
    $(function(){
        $(".salvar").click(function(){

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