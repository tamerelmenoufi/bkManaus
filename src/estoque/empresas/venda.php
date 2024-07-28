<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    if($_POST['empresa']) $_SESSION['estoque']['empresa'] = $_POST['empresa'];
    if($_POST['destinataria']) $_SESSION['estoque']['destinataria'] = $_POST['destinataria'];

?>

<div class="d-flex justify-content-end mb-2">
    <button iniciar_venda class="btn btn-success btn-sm"><i class="fa-solid fa-bag-shopping"></i> Iniciar uma Venda</button>
</div>

<?php
    if($_SESSION['estoque']['destinataria']){


        $query = "select a.*, b.nome, b.cnpj from vendas a left join empresas b on a.destinatario = b.codigo where a.emitente = '{$_SESSION['estoque']['empresa']}' and a.destinatario = '{$_SESSION['estoque']['destinataria']}' and a.situacao = '0'";
        $result = mysqli_query($con, $query);
        if(!mysqli_num_rows($result)){
            mysqli_query($con, "insert into vendas set emitente = '{$_SESSION['estoque']['empresa']}', destinatario = '{$_SESSION['estoque']['destinataria']}', situacao = '0'");
            $result = mysqli_query($con, $query);
        }
        $d = mysqli_fetch_object($result);

?>

<div class="card">
  <h5 class="card-header">Venda (<?=$d->nome?> - <?=$d->cnpj?>)</h5>
  <div class="card-body">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Código</th>
                <th>Produto</th>
                <th>Unidade</th>
                <th>Quantidade</th>
                <th>Preço Unitário</th>
            </tr>
        </thead>
        <tbody>
    <?php
        $q = "select * from movimentacao where cod_nota = '{$d->codigo}' and tipo = 's' order by codigo asc";
        $r = mysqli_query($con, $q);
        while($e = mysqli_fetch_object($r)){
    ?>
            <tr>
                <td><?=$e->cProd?></td>
                <td><?=$e->xProd?></td>
                <td><?=$e->uCom?></td>
                <td><?=$e->qCom?></td>
                <td><?=$e->vUnCom?></td>
                <td>
            </tr>     
    <?php
        }
    ?>
        </tbody>
    </table>    
  </div>
</div>

<?php
    }
?>


<script>
    $(function(){

        $("button[iniciar_venda]").click(function(){

            Carregando();
            $.ajax({
                url:"src/estoque/empresas/empresas.php",
                type:"POST",
                data:{
                    empresa:'<?=$_SESSION['estoque']['empresa']?>',
                },
                success:function(dados){
                    listaEmpresas = $.dialog({
                        title:"Empresas Cadastradas",
                        content:dados,
                        type:"blue"
                    })
                }
            })

        })

    })
</script>