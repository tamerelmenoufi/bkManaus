<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    if($_POST['empresa']) $_SESSION['estoque']['empresa'] = $_POST['empresa'];

    
?>

<div class="d-flex justify-content-end">
    <button iniciar_venda class="btn btn-success btn-sm"><i class="fa-solid fa-bag-shopping"></i> Iniciar uma Venda</button>
</div>