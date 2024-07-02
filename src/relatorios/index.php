<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");


?>

<div class="row g-0">
    <div class="col-12">
        <h3>Calendário de Produção</h3>
        <div class="d-flex justify-content-between">
            <?php
                $y = ($_SESSION['buscaY'])?:date("Y");
                $di = mktime(0,0,0, date("m"), 1, $y);
                $df = mktime(0,0,0, date("m")+1, 1 - 1, $y);
                echo "dia 1 :".date("d/m/Y", $di);
                echo " e dia 2 :".date("d/m/Y", $df);
            ?>
        </div>
    </div>
</div>