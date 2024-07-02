<?php
    
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");
?>

<h3>Produção por Entregadores</h3>

<div class="row">
    <div class="col-md-3">
        <input type="data" id="data_inicio" class="form-control" value="<?=$_POST['data_inicio']?>">
    </div>
    <div class="col-md-3">
        <input type="data" id="data_fim" class="form-control" value="<?=$_POST['data_fim']?>">
    </div>
    <div class="col-md-3">
        <select id="entregadores"  class="form-select">
            <option value="todos">Todos</option>
            <?php
            $q = "select * from entregadores where situacao = '1' and deletado != '1' order by nome asc";
            $r = mysqli_query($con, $q);
            while($s = mysqli_fetch_object($result)){
            ?>
            <option value="<?=$s->codigo?>"><?=$s->nome?></option>
            <?php
            }
            ?>
        </select>
    </div>
    <div class="col-md-3">
        <button class="btn btn-primary w-100 lista_entregadores">Filtrar Dados</button>
    </div>
</div>

<table class="table table-hover">
    <thead>
        <tr>
            <th>Data</th>
            <th>Pedido</th>
            <th>Valor</th>
            <th>Entregador</th>
            <th>Situação</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $query = "select a.*, b.nome as entregador from ifood a left join entregadores b on a.entregador = b.codigo order by b.nome asc, a.data asc";
        $result = mysqli_query($con, $query);
        while($d = mysqli_fetch_object($result)){
        ?>
        <tr class="table-<?=(($d->producao == 'entregue')?'success':'danger')?>">
            <td><?=dataBr($d->data)?></td>
            <td>#<?=$d->ifood?></td>
            <td>R$ <?=number_format($d->valor,2,',','.')?></td>
            <td><?=$d->entregador?></td>
            <td><?=strtoupper($d->producao)?></td>
        </tr>
        <?php
        }
        ?>
    </tbody>
</table>