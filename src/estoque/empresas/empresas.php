<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    if($_POST['empresa']) $_SESSION['estoque']['empresa'] = $_POST['empresa'];
?>

<ul class="list-group">
<?php

    $query = "select * from empresas where tipo = 'g' order by nome asc";
    $result = mysqli_query($con, $query);
    while($d = mysqli_fetch_object($result)){
?>
  <li class="list-group-item"><?=$d->nome?> - <?=$d->cnpj?></li>
<?php
    }
?>
</ul>

<script>
    $(function(){
        Carregando('none')

    })
</script>