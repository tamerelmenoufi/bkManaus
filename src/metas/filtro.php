<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/app/projectSocioEconomico/lib/includes.php");


    if($_POST['acao'] == 'bairro_comunidade'){

        $q = "select * from bairros_comunidades

                where
                        municipio = '{$_POST['municipio']}'
                        and tipo = '{$_POST['zona']}'

                order by descricao";
        $r = mysqli_query($con, $q);
?>
        <option value="">::Selecione a Localização::</option>
<?php
        while($s = mysqli_fetch_object($r)){
?>
        <option value="<?=$s->codigo?>"><?=$s->descricao?> (<?=$s->tipo?>)</option>
<?php
        }
    }
?>