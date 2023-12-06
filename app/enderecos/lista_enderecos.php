<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    if($_POST['idUnico']){
        $_SESSION['idUnico'] = $_POST['idUnico'];
    }
    if($_POST['codUsr']){
        $_SESSION['codUsr'] = $_POST['codUsr'];
    }


?>

<div class="row g-0 p-2 mt-3">
    <div class="card p-2">
        <h4 class="w-100 text-center">Endereços</h4>
        <label for="cep" class="form-label">Cadstro de endereço</label>
        <div class="input-group">
            <input type="text" class="form-control" id="cep" inputmode="numeric" placeholder="XXXXX-XXX" aria-label="Digite o CEP" aria-describedby="cadastro_cep">
            <button class="btn btn-outline-secondary cep" type="button" id="cadastro_cep">Avançar</button>
        </div>
        <div id="emailHelp" class="form-text">Avançar com o CEP preenchido agiliza o cadastro do seu endereço</div>
  </div>
    </div>
</div>