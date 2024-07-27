<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    $query = "select * from notas where codigo = '{$_POST['detalhes']}'";
    $result = mysqli_query($con, $query);
    $d = mysqli_fetch_object($result);


    $x = json_decode($d->dados);

    //var_dump($d);

    $infPrincipal = [
        ['Número', $x->NFe->infNFe->ide->nNF],
        ['Série', $x->NFe->infNFe->ide->serie],
        ['Tipo', $x->NFe->infNFe->ide->tpNF],
        
        ['Emitente CNPJ', $x->NFe->infNFe->emit->CNPJ],
        ['Emitente', $x->NFe->infNFe->emit->xNome],

        ['Destinatário CNPJ', $x->NFe->infNFe->dest->CNPJ],
        ['Destinatário', $x->NFe->infNFe->dest->xNome],

        ['Data da Emissão', $x->NFe->infNFe->ide->dhEmi],
        ['Descrição', $x->NFe->infNFe->ide->natOp],
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
            <div><?=$v[1]?></div>
            <!--<button class="btn btn-danger btn-sm">OK</button>-->
        </div>
        <?php
        }
        ?>
        <div class="d-flex justify-content-between">
            <span>Arquivo XML</span>
            <a href='./src/volumes/notas/xml/<?=$d->xml?>' target="_blank" class="btn btn-link btn-sm"><?=$d->xml?></a>
            <!--<button class="btn btn-danger btn-sm">OK</button>-->
        </div>
    </li>
  </ul>
</div>

<?php


$y = json_decode($d->nf_json);

$infEntrada = [
    ['Número', $y->NFe->infNFe->ide->nNF],
    ['Série', $y->NFe->infNFe->ide->serie],
    ['Tipo', $y->NFe->infNFe->ide->tpNF],
    
    ['Emitente CNPJ', $y->NFe->infNFe->emit->CNPJ],
    ['Emitente', $y->NFe->infNFe->emit->xNome],

    ['Destinatário CNPJ', $y->NFe->infNFe->dest->CNPJ],
    ['Destinatário', $y->NFe->infNFe->dest->xNome],

    ['Data da Emissão', $y->NFe->infNFe->ide->dhEmi],
    ['Descrição', $y->NFe->infNFe->ide->natOp],
];

?>
 
<div class="card mb-3">
  <div class="card-header">
    Registro de Entrada
  </div>
  <ul class="list-group list-group-flush">
        <?php
        foreach($infEntrada as $i => $v){
        ?>
        <div class="d-flex justify-content-between">
            <span><?=$v[0]?></span>
            <div><?=$v[1]?></div>
            <!--<button class="btn btn-danger btn-sm">OK</button>-->
        </div>
        <?php
        }
        ?>
  </ul>
</div>