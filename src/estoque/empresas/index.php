<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    if($_POST['empresa']) $_SESSION['estoque']['empresa'] = $_POST['empresa'];
    if($_POST['destinataria']) $_SESSION['estoque']['destinataria'] = $_POST['destinataria'];

    
    if($_POST['busca']) $_SESSION['estoque']['busca'] = $_POST['busca'];
    if($_POST['busca'] == 'limpar') $_SESSION['estoque']['busca'] = false;

    if($_SESSION['estoque']['busca']){

        $where = " and (cProd like '%{$_SESSION['estoque']['busca']}%' or xProd like '%{$_SESSION['estoque']['busca']}%') ";

    }

?>

<div class="card m-3">
  <h5 class="card-header">Estoque</h5>
  <div class="card-body">
    <h5 class="card-title">Consulta de Estoque por empresa</h5>
    <p class="card-text">Selecione uma empresa para verificação do estque de produtos.</p>
    <select id="empresa" class="form-select mb-2">
        <option value="">:: Selecione uma empresa ::</option>
        <?php
            $q = "select * from empresas where tipo = 'g' order by nome asc";
            $r = mysqli_query($con, $q);
            while($e = mysqli_fetch_object($r)){
        ?>        
        <option value="<?=$e->codigo?>" <?=(($_SESSION['estoque']['empresa'] == $e->codigo)?'selected':false)?> ><?=$e->nome?> - <?=$e->cnpj?></option>
        <?php
            }
        ?>  
    </select>
    <button class="btn btn-primary" listaEstoque>Listar Produtos</button>
  </div>
</div>


<?php
if($_SESSION['estoque']['empresa']){
?>

<div venda class="m-3"></div>

<?php

    $query = "select * from empresas where codigo = '{$_SESSION['estoque']['empresa']}'";
    $result = mysqli_query($con, $query);
    while($d = mysqli_fetch_object($result)){

?>
<div class="card m-3">
  <h5 class="card-header"><?=$d->nome?> - <?=$d->cnpj?></h5>
  <div class="card-body">

    <div class="input-group mb-3">
        <input filtro type="text" value="<?=$_SESSION['estoque']['busca']?>" class="form-control" placeholder="Buscar Produtos por código ou descrição" aria-label="Buscar Produtos por código ou descrição" aria-describedby="button-addon2">
        <button filtrar class="btn btn-outline-secondary" type="button" id="button-addon2"><i class="fa-solid fa-magnifying-glass"></i></button>
        <button limpar class="btn btn-outline-danger" type="button" id="button-addon2"><i class="fa-solid fa-trash"></i></button>
    </div>


    <table class="table table-hover">
        <thead>
            <tr>
                <th>Código</th>
                <th>Produto</th>
                <th>Unidade</th>
                <th>Quantidade</th>
                <th>Preço Unitário</th>
                <?php
                if($_SESSION['estoque']['destinataria']){
                ?>
                <th>Venda</th>
                <?php
                }
                ?>
            </tr>
        </thead>
        <tbody>
    <?php
        $q = "select * from estoque_{$d->codigo} where 1 {$where} order by xProd asc";
        $r = mysqli_query($con, $q);
        while($e = mysqli_fetch_object($r)){
    ?>
            <tr>
                <td><?=$e->cProd?></td>
                <td><?=$e->xProd?></td>
                <td><?=$e->uCom?></td>
                <td><?=$e->qCom?></td>
                <td><?=$e->vUnCom?></td>
                <?php
                if($_SESSION['estoque']['destinataria']){
                ?>
                <td>
                    <div class="input-group mb-3">
                        <input quantidade_<?=$e->codigo?> type="text" class="form-control" placeholder="00000" aria-describedby="button-addon3">
                        <button adicionar="<?=$e->codigo?>" class="btn btn-outline-secondary" type="button" id="button-addon3"><i class="fa-solid fa-cart-plus"></i></button>
                    </div>
                </td>
                <?php
                }
                ?>
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
}
?>



<script>
  $(function(){
    
    Carregando('none');

    $.ajax({
        url:"src/estoque/empresas/venda.php",
        type:"POST",
        data:{
            empresa:'<?=$_SESSION['estoque']['empresa']?>',
            destinataria:'<?=$_SESSION['estoque']['destinataria']?>'
        },
        success:function(dados){
            $("div[venda]").html(dados)
        }
    })


      $("button[listaEstoque]").click(function(){
        empresa = $("#empresa").val();
        if(!empresa){
            $.alert({
                type:"red",
                title:"Alerta de Pendências",
                content:"Selecione uma empresa para listar o estoque disponível"
            })
            return false
        }
        Carregando();
        $.ajax({
            url:"src/estoque/empresas/index.php",
            type:"POST",
            data:{
                empresa
            },
            success:function(dados){
                $("#paginaHome").html(dados);
            }
        })
      })

      $("button[filtrar]").click(function(){
        busca = $("input[filtro]").val();

        if(!busca){
            $.alert({
                type:"red",
                title:"Ausência de informações",
                content:"Informe o código ou produto que deseja buscar no campo de busca!"
            })
            return false
        }
        Carregando();
        $.ajax({
            url:"src/estoque/empresas/index.php",
            type:"POST",
            data:{
                busca
            },
            success:function(dados){
                $("#paginaHome").html(dados);
            }
        })
    
      })


      $("button[limpar]").click(function(){

        Carregando();
        $.ajax({
            url:"src/estoque/empresas/index.php",
            type:"POST",
            data:{
                busca:'limpar'
            },
            success:function(dados){
                $("#paginaHome").html(dados);
            }
        })
    
      })



  })
</script>