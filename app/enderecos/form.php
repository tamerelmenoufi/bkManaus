<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    if($_POST['idUnico']){
        $_SESSION['idUnico'] = $_POST['idUnico'];
    }
    if($_POST['codUsr']){
        $_SESSION['codUsr'] = $_POST['codUsr'];
    }

    if($_POST['cep']){
        $cep = str_replace('-',false,$_POST['cep']);
        $d = ConsultaCEP($cep);
    }

print_r($d);

?>

<div class="row g-0 p-2">
    <div class="card p-2">
        <div class="mb-1">
            <label class="form-label">CEP</label>
            <div class="form-control is-valid" ><?=$d->cep?></div>
        </div>
        <div class="mb-1">
            <label for="logradouro" class="form-label">Logradouro</label>
            <input type="text" class="form-control <?=(($d->logradouro)?'is-valid':'is-invalid')?>" autocomplete="off" value="<?=$d->logradouro?>" id="logradouro">
        </div>
        <div class="mb-1">
            <label for="numero" class="form-label">Número</label>
            <input type="text" class="form-control <?=(($d->numero)?'is-valid':'is-invalid')?>" autocomplete="off" value="<?=$d->numero?>" id="numero">
        </div>
        <div>
            <label for="complemento" class="form-label">Complemento</label>
            <input type="text" class="form-control <?=(($d->complemento)?'is-valid':'is-invalid')?>" autocomplete="off" value="<?=$d->complemento?>" id="complemento">
        </div>  
        <div>
            <label for="ponto_referencia" class="form-label">Ponto de Referência</label>
            <input type="text" class="form-control <?=(($d->ponto_referencia)?'is-valid':'is-invalid')?>" autocomplete="off" value="<?=$d->ponto_referencia?>" id="ponto_referencia">
        </div>        
        <div>
            <label for="bairro" class="form-label">Bairro</label>
            <input type="text" class="form-control <?=(($d->bairro)?'is-valid':'is-invalid')?>" autocomplete="off" value="<?=$d->bairro?>" id="bairro">
        </div>  
        <div>
            <label for="localidade" class="form-label">Localidade</label>
            <input type="text" class="form-control <?=(($d->localidade)?'is-valid':'is-invalid')?>" autocomplete="off" value="<?=$d->localidade?>" id="localidade" uf="AM">
        </div>  
 
    </div>
</div>