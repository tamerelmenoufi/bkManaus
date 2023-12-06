<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    if($_POST['idUnico']){
        $_SESSION['idUnico'] = $_POST['idUnico'];
    }
    if($_POST['codUsr']){
        $_SESSION['codUsr'] = $_POST['codUsr'];
    }
?>

<div class="row g-0 p-2">
    <div class="card p-2">
        <div class="mb-1">
            <label for="nome" class="form-label">Nome Completo</label>
            <input type="text" class="form-control formDados" autocomplete="off" value="<?=$d->nome?>" id="nome">
        </div>
        <div class="mb-1">
            <label for="cpf" class="form-label">CPF</label>
            <input type="text" class="form-control formDados" autocomplete="off" value="<?=$d->cpf?>" id="cpf">
        </div>
        <div class="mb-1">
            <label class="form-label">Telefone</label>
            <div class="form-control is-valid" ><?=$d->telefone?></div>
        </div>
        <div>
            <label for="email" class="form-label">E-mail</label>
            <input type="email" class="form-control formDados" autocomplete="off" value="<?=$d->email?>" id="email">
        </div>        
    </div>
</div>