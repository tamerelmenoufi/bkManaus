<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");
?>
<style>
    
</style>
<h4>Pedido do ifood</h4>
<div style="position:absolute; left:0; right:0; top:70px; bottom:0; overflow:auto; padding:10px;">
    <div class="p-2">
        <div class="mb-3">
            <label for="telefone" class="form-label">Número do Pedido ifood*</label>
            <input type="text" class="form-control" id="codigo" >
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
            <button class="btn btn-success w-100">SALVAR DADOS</button>
        </div>
    </div>
</div>

<script>
    $(function(){

    })
</script>