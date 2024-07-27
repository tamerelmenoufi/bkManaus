<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    if($_POST['empresa']) $_SESSION['estoque']['empresa'] = $_POST['empresa'];
?>

<div class="list-group">
<?php

    $query = "select * from empresas where tipo = 'g' order by nome asc";
    $result = mysqli_query($con, $query);
    while($d = mysqli_fetch_object($result)){
?>
  <button type="button" class="list-group-item list-group-item-action"><?=$d->nome?> - <?=$d->cnpj?></button>
<?php
    }
?>
</div>

<script>
    $(function(){
        Carregando('none')

    })
</script>