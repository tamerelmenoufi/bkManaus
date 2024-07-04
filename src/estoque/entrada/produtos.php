<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    if($_POST['nota']) $_SESSION['nota'] = $_POST['nota'];

    $query = "select * from notas where codigo = '{$_SESSION['nota']}'";
    $result = mysqli_query($con, $query);
    $d = mysqli_fetch_object($result);

    $n = json_decode($d->dados);
    // print_r($n);

    function produtoMapa($p){
?>
<div class="card mb-3">
  <h6 class="card-header"><?=$p->xProd?></h6>
  <div class="card-body">
    <table class="table">
        <tbody>

            <tr>
                <td>Código do produto</td>
                <td><?=$p->cProd?></td>
            </tr>
            <tr>
                <td>Código de barras do produto</td>
                <td><?=$p->cEAN?></td>
            </tr>
            <tr>
                <td>Descrição do produto</td>
                <td><?=$p->xProd?></td>
            </tr>
            <tr>
                <td>NCM</td>
                <td><?=$p->NCM?></td>
            </tr>
            <tr>
                <td>CFOP</td>
                <td><?=$p->CFOP?></td>
            </tr>
            <tr>
                <td>Unidade</td>
                <td><?=$p->uCom?></td>
            </tr>
            <tr>
                <td>Quantidade</td>
                <td><?=$p->qCom?></td>
            </tr>
            <tr>
                <td>Valor unitário</td>
                <td><?=$p->vUnCom?></td>
            </tr>
            <tr>
                <td>Valor total bruto</td>
                <td><?=$p->vProd?></td>
            </tr>
            <tr>
                <td>Código de barras tributável</td>
                <td><?=$p->cEANTrib?></td>
            </tr>
            <tr>
                <td>Unidade tributável</td>
                <td><?=$p->uTrib?></td>
            </tr>
            <tr>
                <td>Quantidade tributáve</td>
                <td><?=$p->qTrib?></td>
            </tr>
            <tr>
                <td>Valor unitário de tributação</td>
                <td><?=$p->vUnTrib?></td>
            </tr>
            <tr>
                <td>Indicador de totalização</td>
                <td><?=(($p->indTot)?'Sim':'Não')?></td>
            </tr>
        </tbody>
    </table>
    </div>
</div>    
<?php
    }

    if(is_array($n->NFe->infNFe->det)){
        foreach($n->NFe->infNFe->det as $i => $val){
            // echo $val->prod->xProd."<br>";
            produtoMapa($val->prod);
        }
    }else{
        // echo $n->NFe->infNFe->det->prod->xProd."<br>";
        produtoMapa($n->NFe->infNFe->det->prod);
    }
?>
