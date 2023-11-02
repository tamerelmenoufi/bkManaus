<?php

    include("{$_SERVER['DOCUMENT_ROOT']}/app/projectSocioEconomico/lib/includes.php");

    $query_geral = "select * from municipios where acao = '0' and coordenadas != '' limit 1";
    $result_geral = mysqli_query($con, $query_geral);
    while($d_geral = mysqli_fetch_object($result_geral)){

        set_time_limit(100);

        $Values = [];

        // RELATÓRIO "graficos/pesquisa"
        $grafico = 'graficos/pesquisa/'.$d_geral->codigo;
        $md5 = md5($grafico.$md5);
        $query = "
                    select
                        (select count(*) from se where municipio = '{$d_geral->codigo}' and percentual > 0 and percentual < 100) as iniciadas,
                        (select count(*) from se where municipio = '{$d_geral->codigo}' and percentual = 0 ) as pendentes,
                        (select count(*) from se where municipio = '{$d_geral->codigo}' and percentual = 100) as concluidas
        ";
        $result = mysqli_query($con, $query);
        $Rotulos = [];
        $Quantidade = [];
        while($d = mysqli_fetch_object($result)){
            set_time_limit(90);
            $Rotulos = ['Pendentes','Iniciadas','Concluídas'];
            $Quantidade = [$d->pendentes, $d->iniciadas,$d->concluidas];
        }
        $esquema = json_encode([
            'Rotulos' => $Rotulos,
            'Quantidade' => $Quantidade],
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
        );
        $Values[] = "('{$grafico}','{$esquema}')";



        // RELATÓRIO "graficos/rural"
        $grafico = 'graficos/rural/'.$d_geral->codigo;
        $md5 = md5($grafico.$md5);
        $query = "
                    select
                        (select count(*) from se where municipio = '{$d_geral->codigo}' and percentual > 0 and percentual < 100 and local = 'rural') as iniciadas,
                        (select count(*) from se where municipio = '{$d_geral->codigo}' and percentual = 0 and local = 'rural') as pendentes,
                        (select count(*) from se where municipio = '{$d_geral->codigo}' and percentual = 100 and local = 'rural') as concluidas
        ";
        $result = mysqli_query($con, $query);
        $Rotulos = [];
        $Quantidade = [];
        while($d = mysqli_fetch_object($result)){
            set_time_limit(90);
            $Rotulos = ['Pendentes','Iniciadas','Concluídas'];
            $Quantidade = [$d->pendentes, $d->iniciadas,$d->concluidas];
        }
        $esquema = json_encode([
            'Rotulos' => $Rotulos,
            'Quantidade' => $Quantidade],
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE

        );
        $Values[] = "('{$grafico}','{$esquema}')";




        // RELATÓRIO "graficos/urbana"
        $grafico = 'graficos/urbana/'.$d_geral->codigo;
        $md5 = md5($grafico.$md5);
        $query = "
                    select
                        (select count(*) from se where municipio = '{$d_geral->codigo}' and percentual > 0 and percentual < 100 and local = 'urbano') as iniciadas,
                        (select count(*) from se where municipio = '{$d_geral->codigo}' and percentual = 0 and local = 'urbano') as pendentes,
                        (select count(*) from se where municipio = '{$d_geral->codigo}' and percentual = 100 and local = 'urbano') as concluidas
        ";
        $result = mysqli_query($con, $query);
        $Rotulos = [];
        $Quantidade = [];
        while($d = mysqli_fetch_object($result)){
            set_time_limit(90);
            $Rotulos = ['Pendentes','Iniciadas','Concluídas'];
            $Quantidade = [$d->pendentes, $d->iniciadas,$d->concluidas];
        }
        $esquema = json_encode([
            'Rotulos' => $Rotulos,
            'Quantidade' => $Quantidade],
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
        );
        $Values[] = "('{$grafico}','{$esquema}')";



        // RELATÓRIO "graficos/zona_geral"
        $grafico = 'graficos/zona_geral/'.$d_geral->codigo;
        $md5 = md5($grafico.$md5);
        $query = "
            select
                *,
                count(*) as quantidade
            from se where municipio = '{$d_geral->codigo}' group by local order by quantidade desc
        ";
        $result = mysqli_query($con, $query);
        $Rotulos = [];
        $Quantidade = [];
        while($d = mysqli_fetch_object($result)){
            set_time_limit(90);
            $Rotulos[] = $d->local;
            $Quantidade[] = $d->quantidade;
        }
        $esquema = json_encode([
            'Rotulos' => $Rotulos,
            'Quantidade' => $Quantidade],
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
        );
        $Values[] = "('{$grafico}','{$esquema}')";




        // RELATÓRIO "mapas/geral"
        $grafico = 'mapas/geral/'.$d_geral->codigo;
        $md5 = md5($grafico.$md5);


        $query = "select
                        count(*) as qt,
                        concat(b.descricao,' - ',b.tipo) as descricao,
                        b.coordenadas from se a
                    left join bairros_comunidades b on a.bairro_comunidade = b.codigo
                    where b.coordenadas != '' and a.municipio='{$d_geral->codigo}'
                    group by a.municipio, a.bairro_comunidade";
        $result = mysqli_query($con, $query);
        $Rotulos = [];
        $Quantidade = [];
        $Lat = [];
        $Lng = [];
        while($d = mysqli_fetch_object($result)){
            set_time_limit(90);
            $coord = explode(",", $d->coordenadas);
            $Rotulos[] = $d->descricao;
            $Quantidade[] = $d->qt;
            $Lat[] = trim($coord[0]);
            $Lng[] = trim($coord[1]);
        }
        $esquema = json_encode([
            'Rotulos' => $Rotulos,
            'Quantidade' => $Quantidade,
            'Lat' => $Lat,
            'Lng' => $Lng,
            ],
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
        );
        $Values[] = "('{$grafico}','{$esquema}')";



        // RELATÓRIO "tabelas/resumo"
        $grafico = 'tabelas/resumo/'.$d_geral->codigo;
        $md5 = md5($grafico.$md5);
        $query = "select
            (select count(*) from se where municipio = '{$d_geral->codigo}') as total,
            (select count(*) from se where municipio = '{$d_geral->codigo}' and percentual > 0 and percentual < 100) as iniciadas,
            (select count(*) from se where municipio = '{$d_geral->codigo}' and percentual = 0) as pendentes,
            (select count(*) from se where municipio = '{$d_geral->codigo}' and percentual = 100) as concluidas,
            (select count(*) from se where municipio = '{$d_geral->codigo}' and beneficiario_encontrado = 'Não') as nao_encontrado
        ";
        $result = mysqli_query($con, $query);
        $d = mysqli_fetch_object($result);

        $Rotulos = ['Total','Iniciadas','Pendentes','Concluídas','Não Encontrado'];
        $Quantidade = [$d->total, $d->iniciadas, $d->pendentes, $d->concluidas, $d->nao_encontrado];

        $esquema = json_encode([
            'Rotulos' => $Rotulos,
            'Quantidade' => $Quantidade
            ],
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
        );
        $Values[] = "('{$grafico}','{$esquema}')";



        echo $query = "REPLACE INTO dashboard (grafico, esquema) VALUES ".implode(', ',$Values);
        mysqli_query($con, $query);

        mysqli_query($con, "update municipios set acao = '1' where codigo = '{$d_geral->codigo}'");
        // echo "<script>window.location.href='./graficos_municipios.php'</script>";
    }