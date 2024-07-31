<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    if($_POST['empresa']) $_SESSION['estoque']['empresa'] = $_POST['empresa'];
    if($_POST['destinataria']) $_SESSION['estoque']['destinataria'] = $_POST['destinataria'];

    if($_POST['acao'] == 'devolver'){

        echo $query = "update estoque_{$_SESSION['estoque']['empresa']} set qCom = (qCom + (select qCom from movimentacao where codigo = '{$_POST['item']}')) where cProd = (select cProd from movimentacao where codigo = '{$_POST['item']}')";
        if(mysqli_query($con, $query)){
            mysqli_query($con, "delete from movimentacao where codigo = '{$_POST['item']}'");
        }
        exit();
    }

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
                <th>Ações</th>
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
                    <button devolverItem="<?=$e->codigo?>" produto="<?=$e->xProd?>" class="btn btn-danger btn-sm">
                        <i class="fa-solid fa-trash-can"></i>
                    </button>
                </td>
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

        $("button[devolverItem]").click(function(){

            item = $(this).attr("devolverItem");
            produto = $(this).attr("produto");

            $.confirm({
                title:"Excluir Item",
                type:'red',
                content:`Deseja realmente excluir o item <b>${produto}</b> da nota?`,
                buttons:{
                    'Sim':function(){

                        Carregando();
                        $.ajax({
                            url:"src/estoque/empresas/venda.php",
                            type:"POST",
                            data:{
                                empresa:'<?=$_SESSION['estoque']['empresa']?>',
                                item,
                                acao:'devolver'
                            },
                            success:function(dados){
                                console.log(dados);

                                $.ajax({
                                    url:"src/estoque/empresas/index.php",
                                    type:"POST",
                                    data:{
                                        empresa:'<?=$_SESSION['estoque']['empresa']?>',
                                        destinataria:'<?=$_SESSION['estoque']['destinataria']?>'
                                    },
                                    success:function(dados){
                                        $("#paginaHome").html(dados);
                                    }
                                })

                            }
                        })

                    },
                    'não':function(){

                    }
                }
            })

        })

    })
</script>