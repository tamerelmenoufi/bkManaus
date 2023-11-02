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

    coordenadas<?=$md5?> = '<?="{$coordenadas}"?>';
    endereco<?=$md5?> = "<?=$endereco?>";
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
        <?php
        // if($coordenadas){
            // -3.986913, -63.931869
        ?>
        center: { lat: -3.986913, lng: -63.931869 },
        zoom: 5,

        <?php
        // }
        ?>
    }
    /*{
        center: { lat: -34.397, lng: 150.644 },
        zoom: 8,
    }*/
    );

    // marker<?=$md5?> = new google.maps.Marker({
    //     position: { lat: -3.986913, lng: -63.931869 },
    //     map:map<?=$md5?>,
    //     title: "Hello World!",
    //     draggable:false,
    // });

    // google.maps.event.addListener(marker<?=$md5?>, 'dragend', function(marker) {
    //     var latLng = marker.latLng;
    //     alert(`Lat ${latLng.lat()} & Lng ${latLng.lng()}`)
    // });

// async function icones(local, qt){
//         address =  `${local}-AM, Brasil`

//         await geocoder<?=$md5?>.geocode({ 'address':address, 'region': 'BR' }, (results, status) => {
//         console.log(address)
//         console.log(status)
//         console.log(google.maps.GeocoderStatus.OK)

//         if (status == google.maps.GeocoderStatus.OK) {
//             // if (results[0] && !coordenadas<?=$md5?>) {

//                 var latitude<?=$md5?> = results[0].geometry.location.lat();
//                 var longitude<?=$md5?> = results[0].geometry.location.lng();
//                 // console.log('Coordenadas:', local)
//                 // console.log('Lat:'+latitude<?=$md5?>)
//                 // console.log('Lng:'+longitude<?=$md5?>)

//                 // var location<?=$md5?> = new google.maps.LatLng(latitude<?=$md5?>, longitude<?=$md5?>);
//                 // marker<?=$md5?>.setPosition(location<?=$md5?>);
//                 // map<?=$md5?>.setCenter(location<?=$md5?>);
//                 // map<?=$md5?>.setZoom(18);

//                 marker<?=$md5?> = new google.maps.Marker({
//                     position: { lat: latitude<?=$md5?>, lng: longitude<?=$md5?> },
//                     map:map<?=$md5?>,
//                     title: qt + " Beneficiários em "+local,
//                     draggable:false,
//                 });



//             // }
//         }else{
//             // console.log('Não encontrado:', local)
//             // console.log('Quantidade:', qt)
//         }
//     });

// }

function icones(local, qt, md5, cod){

    $.ajax({
        url:"src/home/dashboard/mapas/icons.php",
        type:"POST",
        data:{
            local,
            qt,
            md5,
            cod
        },
        success:function(dados){
            $("#map<?=$md5?>").append(dados);
        }
    });
}

function sleep(milliSeconds) {
  var startTime = new Date().getTime();
  while (new Date().getTime() < startTime + milliSeconds);
}


<?php


    $query = "SELECT * FROM dashboard where grafico = 'mapas/geral'";
    $result = mysqli_query($con, $query);
    $Rotulos = [];
    $Quantidade = [];
    $d = mysqli_fetch_object($result);
    $esquema = json_decode($d->esquema);
    // print_r($esquema);
    $Rotulos = $esquema->Rotulos;
    $Quantidade = $esquema->Quantidade;
    $R = (($Rotulos)?"'".implode("','",$Rotulos)."'":0);
    $Q = (($Quantidade)?implode(",",$Quantidade):0);



?>

var enderecos = [<?=$R?>];
var qt = [<?=$Q?>];

for(i=0;i<10;i++){
    console.log(enderecos[i])
    icones(enderecos[i], qt[i], '<?=$md5?>', i)
}
sleep(2200)
for(i=10;i<20;i++){
    console.log(enderecos[i])
    icones(enderecos[i], qt[i], '<?=$md5?>', i)
}
sleep(2200)
for(i=20;i<30;i++){
    console.log(enderecos[i])
    icones(enderecos[i], qt[i], '<?=$md5?>', i)
}
sleep(2200)
for(i=30;i<40;i++){
    console.log(enderecos[i])
    icones(enderecos[i], qt[i], '<?=$md5?>', i)
}
sleep(2200)
for(i=40;i<50;i++){
    console.log(enderecos[i])
    icones(enderecos[i], qt[i], '<?=$md5?>', i)
}

</script>