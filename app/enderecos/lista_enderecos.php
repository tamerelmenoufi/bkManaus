<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    if($_POST['idUnico']){
        $_SESSION['idUnico'] = $_POST['idUnico'];
    }
    if($_POST['codUsr']){
        $_SESSION['codUsr'] = $_POST['codUsr'];
    }


?>

Meus endereços

<div class="row g-0">
    <div class="card m-2">
        <div class="input-group mb-3">
            <label for="cep" class="form-label">Cadstro de endereço</label>
            <input type="text" class="form-control" id="cep" inputmode="numeric" placeholder="XXXXX-XXX" aria-label="Digite o CEP" aria-describedby="cadastro_cep">
            <button class="btn btn-outline-secondary cep" type="button" id="cadastro_cep">Avançar</button>
        </div>
        <div id="emailHelp" class="form-text">Avançar com o CEP preenchido agiliza o cadastro do seu endereço</div>
  </div>
    </div>
</div>