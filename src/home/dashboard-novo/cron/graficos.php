<?php

    include("{$_SERVER['DOCUMENT_ROOT']}/app/projectSocioEconomico/lib/includes.php");

    $Values = [];

    // RELATÓRIO "graficos/capital"
    $grafico = 'graficos/capital';
    $md5 = md5($grafico.$md5);

    $query = "
        SELECT
            a.*,
            count(*) as quantidade,
            b.descricao as bairro
        FROM se a
        left join bairros_comunidades b on a.bairro_comunidade = b.codigo
        where a.municipio = 66
        group by a.bairro_comunidade
        order by quantidade desc
    ";
    $result = mysqli_query($con, $query);
    $Rotulos = [];
    $Quantidade = [];
    while($d = mysqli_fetch_object($result)){
        set_time_limit(90);
        $Rotulos[] = ($d->bairro);
        $Quantidade[] = $d->quantidade;
    }
    $esquema = json_encode([
                            'Rotulos' => $Rotulos,
                            'Quantidade' => $Quantidade],
                            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
                        );
    $Values[] = "('{$grafico}','{$esquema}')";

    // RELATÓRIO "graficos/municipios"
    $grafico = 'graficos/municipios';
    $md5 = md5($grafico.$md5);
    $query = "
    SELECT
        a.*,
        count(*) as quantidade,
        b.municipio as municipio
    FROM se a
    left join municipios b on a.municipio = b.codigo
    where a.municipio != 66
    group by a.municipio
    order by quantidade desc
    ";
    $result = mysqli_query($con, $query);
    $Rotulos = [];
    $Quantidade = [];
    while($d = mysqli_fetch_object($result)){
        set_time_limit(90);
        $Rotulos[] = ($d->municipio);
        $Quantidade[] = $d->quantidade;
    }
    $esquema = json_encode([
        'Rotulos' => $Rotulos,
        'Quantidade' => $Quantidade],
        JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
    );
    $Values[] = "('{$grafico}','{$esquema}')";



    // RELATÓRIO "graficos/pesquisa"
    $grafico = 'graficos/pesquisa';
    $md5 = md5($grafico.$md5);
    $query = "
                select
                    (select count(*) from se where percentual > 0 and percentual < 100) as iniciadas,
                    (select count(*) from se where percentual = 0 ) as pendentes,
                    (select count(*) from se where percentual = 100) as concluidas
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
    $grafico = 'graficos/rural';
    $md5 = md5($grafico.$md5);
    $query = "
                select
                    (select count(*) from se where percentual > 0 and percentual < 100 and local = 'rural') as iniciadas,
                    (select count(*) from se where percentual = 0 and local = 'rural') as pendentes,
                    (select count(*) from se where percentual = 100 and local = 'rural') as concluidas
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
    $grafico = 'graficos/urbana';
    $md5 = md5($grafico.$md5);
    $query = "
                select
                    (select count(*) from se where percentual > 0 and percentual < 100 and local = 'urbano') as iniciadas,
                    (select count(*) from se where percentual = 0 and local = 'urbano') as pendentes,
                    (select count(*) from se where percentual = 100 and local = 'urbano') as concluidas
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
    $grafico = 'graficos/zona_geral';
    $md5 = md5($grafico.$md5);
    $query = "
        select
              *,
              count(*) as quantidade
        from se group by local order by quantidade desc
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
    $grafico = 'mapas/geral';
    $md5 = md5($grafico.$md5);
    $query = "select
                    a.*,
                    (select count(*) from se where municipio = a.codigo) as qt
                from municipios a where a.coordenadas != '' order by a.codigo desc
            ";
    $result = mysqli_query($con, $query);
    $Rotulos = [];
    $Quantidade = [];
    $Lat = [];
    $Lng = [];
    while($d = mysqli_fetch_object($result)){
        set_time_limit(90);
        $coord = explode(",", $d->coordenadas);
        $Rotulos[] = $d->municipio;
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
    $grafico = 'tabelas/resumo';
    $md5 = md5($grafico.$md5);
    $query = "select
        (select count(*) from se) as total,
        (select count(*) from se where percentual > 0 and percentual < 100) as iniciadas,
        (select count(*) from se where percentual = 0) as pendentes,
        (select count(*) from se where percentual = 100) as concluidas,
        (select count(*) from se where beneficiario_encontrado = 'Não') as nao_encontrado
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