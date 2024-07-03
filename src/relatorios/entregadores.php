<?php
    
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    if($_POST['data_inicio'] and $_POST['data_fim']){
        $where = " and a.data between '{$_POST['data_inicio']} 00:00:00' and '{$_POST['data_fim']} 23:59:59' ";
    }else if($_POST['data_inicio']){
        $where = " and a.data like '%{$_POST['data_inicio']}%' ";
    }

    // if($_POST['entregador']){
    //     $where .= " and a.entregador = '{$_POST['entregador']}' ";
    // }



?>

<h3>Produção por Entregadores</h3>

<div class="row">
    <div class="col-md-3 mb-2">
        <input type="date" id="data_inicio" class="form-control" value="<?=$_POST['data_inicio']?>">
    </div>
    <div class="col-md-3 mb-2">
        <input type="date" id="data_fim" class="form-control" value="<?=$_POST['data_fim']?>">
    </div>
    <!-- <div class="col-md-3 mb-2">
        <select id="entregador"  class="form-select">
            <option value="">Todos</option>
            <?php
            $q = "select * from entregadores where situacao = '1' and deletado != '1' order by nome asc";
            $r = mysqli_query($con, $q);
            while($s = mysqli_fetch_object($r)){
            ?>
            <option value="<?=$s->codigo?>" <?=(($_POST['entregador'] == $s->codigo)?'selected':false)?>><?=$s->nome?></option>
            <?php
            }
            ?>
        </select>
    </div> -->
    <div class="col-md-3 mb-2">
        <button class="btn btn-primary w-100 lista_entregadores">Filtrar Dados</button>
    </div>
</div>

<table class="table table-hover">
    <thead>
        <tr>
            <th>#</th>
            <th>Loja</th>
            <th>Data</th>
            <th>Pedido</th>
            <th>Valor</th>
            <th>Valor Entrega</th>
            <th>Situação</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $query = "select 
                        a.*, 
                        c.nome as loja 
                    from vendas a 
                         left join lojas c on a.loja = c.codigo 
                    where 1 {$where} order by a.data asc";
        $result = mysqli_query($con, $query);
        $i=1;
        $valor_total = $valor_entrega_total = 0;
        while($d = mysqli_fetch_object($result)){
        ?>
        <tr class="table-<?=(($d->producao == 'entregue')?'success':'danger')?>">
            <td><?=$i?></td>
            <td><?=$d->loja?></td>
            <td><?=dataBr($d->data)?></td>
            <td>#<?=$d->codigo?></td>
            <td>R$ <?=number_format($d->valor_total,2,',','.')?></td>
            <td>R$ <?=number_format($d->valor_entrega,2,',','.')?></td>
            <td><?=strtoupper($d->producao)?></td>
        </tr>
        <?php
        $valor_total = $valor_total + $d->valor_total;
        $valor_entrega_total = $valor_entrega_total + $d->valor_entrega;
        $i++;
        }
        ?>
        <tr>
            <th colspan="4" style="text-align:right">TOTAL DAS VENDAS</th>
            <th>R$ <?=number_format($valor_total,2,',','.')?></th>
            <th>R$ <?=number_format($valor_entrega_total,2,',','.')?></th>
            <th colspan="2"></th>
        </tr>
    </tbody>
</table>

<script>
    $(function(){

        Carregando('none')

        $(".lista_entregadores").click(function(){

            data_inicio = $("#data_inicio").val()
            data_fim = $("#data_fim").val()

            Carregando()

            $.ajax({
                url:"src/relatorios/entregadores.php",
                type:"POST",
                data:{
                    data_inicio,
                    data_fim
                },
                success:function(dados){
                    $(".area_entregadores").html(dados);
                }
            })

        })
    })
</script>