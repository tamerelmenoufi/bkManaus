<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    $query = "select * from notas where codigo = '{$_POST['detalhes']}'";
    $result = mysqli_query($con, $query);
    $d = mysqli_fetch_object($result);


    $x = json_decode($d->dados);

    //var_dump($d);

    $infPrincipal = [
        ['Número', 'nNF'],
        ['Série', 'serie'],
        ['Tipo', 'tpNF'],
        ['Data da Emissão', 'dhEmi'],
        ['Descrição', 'natOp'],
    ];

 ?>
 
 <div class="card mb-3">
  <div class="card-header">
    Nota Principal
  </div>
  <ul class="list-group list-group-flush">
    <li class="list-group-item">
        <?php
        foreach($infPrincipal as $i => $v){
        ?>
        <div class="d-flex justify-content-between">
            <span><?=$v[0]?></span>
            <div><?=$x->NFe->infNFe->ide->$v[1]?></div>
            <!--<button class="btn btn-danger btn-sm">OK</button>-->
        </div>
        <?php
        }
        ?>
        <div class="d-flex justify-content-between">
            <span>Arquivo XML</span>
            <a href='../volumes/notas/xml/<?=$d->xml?>' target="_blank" class="btn btn-link"><?=$d->xml?></a>
            <!--<button class="btn btn-danger btn-sm">OK</button>-->
        </div>
    </li>
  </ul>
</div>


 
<div class="card mb-3">
  <div class="card-header">
    Registro de Entrada
  </div>
  <ul class="list-group list-group-flush">
    <li class="list-group-item">An item</li>
    <li class="list-group-item">A second item</li>
    <li class="list-group-item">A third item</li>
  </ul>
</div>