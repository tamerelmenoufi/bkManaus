<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/app/projectSocioEconomico/lib/includes.php");

    $endereco = 'centro';
?>

<style>

#map<?=$md5?> {
    position:relative;
    height: 100%;
    width:100%;
    opacity:1;
    z-index:0;
}

</style>

<div id="map<?=$md5?>"></div>

<script>

    map<?=$md5?> = new google.maps.Map(document.getElementById("map<?=$md5?>"), {
        zoomControl: true,
        mapTypeControl: true, //
        draggable: true,
        scaleControl: true,
        scrollwheel: true,
        navigationControl: true,//
        streetViewControl: true,//
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        fullscreenControl: true,
        center: { lat: -3.986913, lng: -63.931869 },
        zoom: 5,
    });

    // marker<?=$md5?> = new google.maps.Marker({
    //     position: { lat: -3.986913, lng: -63.931869 },
    //     map:map<?=$md5?>,
    //     title: "Hello World!",
    //     draggable:false,
    // });



async function icones(local, qt, lat, lng){

    marker<?=$md5?> = new google.maps.Marker({
        position: { lat: lat, lng: lng },
        map:map<?=$md5?>,
        icon:"img/pino.png",
        title: qt + " Beneficiários em "+local,
        draggable:false,
    });

}


function sleep(milliSeconds) {
  var startTime = new Date().getTime();
  while (new Date().getTime() < startTime + milliSeconds);
}


<?php

    $query = "SELECT * FROM dashboard where grafico = 'mapas/geral/{$_SESSION['filtro_relatorio_municipio']}'";
    $result = mysqli_query($con, $query);
    $Rotulos = [];
    $Quantidade = [];
    $Lat = [];
    $Lng = [];

    $d = mysqli_fetch_object($result);
    $esquema = json_decode($d->esquema);

    $Rotulos = $esquema->Rotulos;
    $Quantidade = $esquema->Quantidade;
    $Lat = $esquema->Lat;
    $Lng = $esquema->Lng;
    $R = (($Rotulos)?"'".implode("','",$Rotulos)."'":0);
    $Q = (($Quantidade)?implode(",",$Quantidade):0);
    $Lat = (($Lat)?implode(",",$Lat):0);
    $Lng = (($Lng)?implode(",",$Lng):0);

?>

var locais = [<?=$R?>];
var qt = [<?=$Q?>];
var lat = [<?=$Lat?>];
var lng = [<?=$Lng?>];

for(i=0;i<locais.length;i++){
    console.log(locais[i])
    icones(locais[i], qt[i], lat[i], lng[i])
}

</script>