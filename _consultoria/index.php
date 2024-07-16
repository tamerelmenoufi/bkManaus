<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    $campos = " a.device, c.nome, b.detalhes ";

    echo $query = "
    
        SELECT {$campos} FROM app_acessos a left join vendas_tmp b on a.device = b.id_unico left join clientes c on a.cliente = c.codigo where a.data BETWEEN '2024-06-13 00:00:00' and '2024-06-15 23:59:59' and a.device != '' group by a.device UNION
        SELECT {$campos} FROM app_acessos a left join vendas_tmp b on a.device = b.id_unico left join clientes c on a.cliente = c.codigo where a.data BETWEEN '2024-06-20 00:00:00' and '2024-06-22 23:59:59' and a.device != '' group by a.device UNION
        SELECT {$campos} FROM app_acessos a left join vendas_tmp b on a.device = b.id_unico left join clientes c on a.cliente = c.codigo where a.data BETWEEN '2024-06-27 00:00:00' and '2024-06-29 23:59:59' and a.device != '' group by a.device UNION 
        SELECT {$campos} FROM app_acessos a left join vendas_tmp b on a.device = b.id_unico left join clientes c on a.cliente = c.codigo where a.data BETWEEN '2024-07-04 00:00:00' and '2024-07-06 23:59:59' and a.device != '' group by a.device UNION
        SELECT {$campos} FROM app_acessos a left join vendas_tmp b on a.device = b.id_unico left join clientes c on a.cliente = c.codigo where a.data BETWEEN '2024-07-11 00:00:00' and '2024-07-13 23:59:59' and a.device != '' group by a.device

    ";

    $result = mysqli_connect($con, $query);

    while($d = mysqli_fetch_object($result)){

        echo "{$d->device}<br>";

    }