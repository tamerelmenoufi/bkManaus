<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    $campos = " a.device, c.nome, b.detalhes";

    $query = "
    
        SELECT {$campos}, concat('Semana de 13/06 a 15/06') as semana FROM app_acessos a left join vendas_tmp b on a.device = b.id_unico left join clientes c on a.cliente = c.codigo where a.data BETWEEN '2024-06-13 00:00:00' and '2024-06-15 23:59:59' and a.device != '' group by a.device UNION
        SELECT {$campos}, concat('Semana de 20/06 a 22/06') as semana FROM app_acessos a left join vendas_tmp b on a.device = b.id_unico left join clientes c on a.cliente = c.codigo where a.data BETWEEN '2024-06-20 00:00:00' and '2024-06-22 23:59:59' and a.device != '' group by a.device UNION
        SELECT {$campos}, concat('Semana de 20/27 a 29/06') as semana FROM app_acessos a left join vendas_tmp b on a.device = b.id_unico left join clientes c on a.cliente = c.codigo where a.data BETWEEN '2024-06-27 00:00:00' and '2024-06-29 23:59:59' and a.device != '' group by a.device UNION 
        SELECT {$campos}, concat('Semana de 04/07 a 06/07') as semana FROM app_acessos a left join vendas_tmp b on a.device = b.id_unico left join clientes c on a.cliente = c.codigo where a.data BETWEEN '2024-07-04 00:00:00' and '2024-07-06 23:59:59' and a.device != '' group by a.device UNION
        SELECT {$campos}, concat('Semana de 11/07 a 13/07') as semana FROM app_acessos a left join vendas_tmp b on a.device = b.id_unico left join clientes c on a.cliente = c.codigo where a.data BETWEEN '2024-07-11 00:00:00' and '2024-07-13 23:59:59' and a.device != '' group by a.device

    ";

    $result = mysqli_query($con, $query);


    echo "<table>";
    $i = 1;
    $semana = false;
    while($d = mysqli_fetch_object($result)){

        if($d->semana != $semana){
            echo "<tr>
                <td colspan = '3'><b>{$d->semana}</b></td>
             </tr>";
            $semana = $d->semana;
        }

        echo "<tr>
                <td>{$i}</td>
                <td>{$d->device}</td>
                <td>{$d->cliente}</td>
             </tr>";
        $i++;
    }

    echo "</table>";
