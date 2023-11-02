<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/app/projectSocioEconomico/lib/includes.php");
?>
<option value="">::Todos as metas::</option>
<?php
$q = "select a.*, b.municipio as municipio_nome, c.descricao as bairro_nome from metas a left join municipios b on a.municipio = b.codigo left join bairros_comunidades c on a.bairro_comunidade = c.codigo where a.usuario = '{$_POST['usuario']}' and a.usuario > 0 order by municipio_nome, bairro_nome";
$r = mysqli_query($con, $q);
while($d = mysqli_fetch_object($r)){
?>
<option value="<?=$d->codigo?>"><?="{$d->municipio_nome} - {$d->bairro_nome}"?></option>
<?php
}
?>
