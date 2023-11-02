<script>

    address =  `<?=$_POST['local']?>, Amazonas, Brasil`
    geocoder<?=$_POST['md5'].$_POST['cod']?> = new google.maps.Geocoder();
    geocoder<?=$_POST['md5'].$_POST['cod']?>.geocode({ 'address':address, 'region': 'BR' }, (results, status) => {

        // console.log(address)
        // console.log(status)
        // console.log(google.maps.GeocoderStatus.OK)

        // if (status == google.maps.GeocoderStatus.OK) {
            // if (results[0] && !coordenadas<?=$_POST['md5'].$_POST['cod']?>) {

                var latitude<?=$_POST['md5'].$_POST['cod']?> = results[0].geometry.location.lat();
                var longitude<?=$_POST['md5'].$_POST['cod']?> = results[0].geometry.location.lng();
                // console.log('Coordenadas:', local)
                // console.log('Lat:'+latitude<?=$_POST['md5'].$_POST['cod']?>)
                // console.log('Lng:'+longitude<?=$_POST['md5'].$_POST['cod']?>)

                // var location<?=$_POST['md5'].$_POST['cod']?> = new google.maps.LatLng(latitude<?=$_POST['md5'].$_POST['cod']?>, longitude<?=$_POST['md5'].$_POST['cod']?>);
                // marker<?=$_POST['md5'].$_POST['cod']?>.setPosition(location<?=$_POST['md5'].$_POST['cod']?>);
                // map<?=$_POST['md5'].$_POST['cod']?>.setCenter(location<?=$_POST['md5'].$_POST['cod']?>);
                // map<?=$_POST['md5'].$_POST['cod']?>.setZoom(18);

                marker<?=$_POST['md5'].$_POST['cod']?> = new google.maps.Marker({
                    position: { lat: latitude<?=$_POST['md5'].$_POST['cod']?>, lng: longitude<?=$_POST['md5'].$_POST['cod']?> },
                    map:map<?=$_POST['md5']?>,
                    title: "<?=$_POST['qt']?> Beneficiários em <?=$_POST['local']?>",
                    draggable:false,
                });



            // }
    //     }else{
    //         // console.log('Não encontrado:', local)
    //         // console.log('Quantidade:', qt)
    //     }
    });

</script>