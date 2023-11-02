<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/app/projectSocioEconomico/lib/includes.php");

    $total = @array_multisum($_SESSION['municipios']['quantidade']['66']);


    // $_SESSION['municipios']
    //          ['quantidade']
    //          [$d->cod_municipio]
    //          [$d->local]
    //          [$d->zona_urbana]
    //          [$d->cod_bairro]
    //          [$d->situacao]

    //VERIFICAR AS QUANTIDADE POR SITUAÇÃO
    $i = 0; $iu = 0; $iuc = 0; $ir = 0; //Inicidos
    $p = 0; $pu = 0; $puc = 0; $pr = 0; //Pendentes
    $c = 0; $cu = 0; $cuc = 0; $cr = 0; //Concluídos
    $n = 0; $nu = 0; $nuc = 0; $nr = 0; //Não Encontrados

    // foreach($_SESSION['municipios']['quantidade'] as $indice => $valores){ //Lista os municipios
        foreach($_SESSION['municipios']['quantidade']['66'] as $indice1 => $valores1){ //Lista as zonas
            foreach($valores1 as $indice2 => $valores2){ //Lista as zonas urbanas
                foreach($valores2 as $indice3 => $valores3){ //Lista os Bairros
                    //Obter as quantidades por situacao
                    $i += ($_SESSION['municipios']['quantidade']['66'][$indice1][$indice2][$indice3]['i']);
                    $p += ($_SESSION['municipios']['quantidade']['66'][$indice1][$indice2][$indice3]['p']);
                    $c += ($_SESSION['municipios']['quantidade']['66'][$indice1][$indice2][$indice3]['c']);
                    $n += ($_SESSION['municipios']['quantidade']['66'][$indice1][$indice2][$indice3]['n']);

                    if($indice1 == 'Urbano'){
                    //Obter as quantidades por situacao por zona Urbana
                    $iu += ($_SESSION['municipios']['quantidade']['66'][$indice1][$indice2][$indice3]['i']);
                    $pu += ($_SESSION['municipios']['quantidade']['66'][$indice1][$indice2][$indice3]['p']);
                    $cu += ($_SESSION['municipios']['quantidade']['66'][$indice1][$indice2][$indice3]['c']);
                    $nu += ($_SESSION['municipios']['quantidade']['66'][$indice1][$indice2][$indice3]['n']);

                    $iuz[$indice2] += ($_SESSION['municipios']['quantidade']['66'][$indice1][$indice2][$indice3]['i']);
                    $puz[$indice2] += ($_SESSION['municipios']['quantidade']['66'][$indice1][$indice2][$indice3]['p']);
                    $cuz[$indice2] += ($_SESSION['municipios']['quantidade']['66'][$indice1][$indice2][$indice3]['c']);
                    $nuz[$indice2] += ($_SESSION['municipios']['quantidade']['66'][$indice1][$indice2][$indice3]['n']);

                    }

                    if($indice1 == 'Rural'){
                    //Obter as quantidades por situacao por zona Rural
                    $ir += ($_SESSION['municipios']['quantidade']['66'][$indice1][$indice2][$indice3]['i']);
                    $pr += ($_SESSION['municipios']['quantidade']['66'][$indice1][$indice2][$indice3]['p']);
                    $cr += ($_SESSION['municipios']['quantidade']['66'][$indice1][$indice2][$indice3]['c']);
                    $nr += ($_SESSION['municipios']['quantidade']['66'][$indice1][$indice2][$indice3]['n']);
                    }

                    ///////////CONDIÇÔES PARA A CAPITAL - CODIGO 66 ////////////////////////
                    if($indice == 66){

                        $iuc += ($_SESSION['municipios']['quantidade']['66'][$indice1][$indice2][$indice3]['i']);
                        $puc += ($_SESSION['municipios']['quantidade']['66'][$indice1][$indice2][$indice3]['p']);
                        $cuc += ($_SESSION['municipios']['quantidade']['66'][$indice1][$indice2][$indice3]['c']);
                        $nuc += ($_SESSION['municipios']['quantidade']['66'][$indice1][$indice2][$indice3]['n']);

                    }




                }
            }
        }
    // }

    // echo "Geral Iniciados: ".$i."<br>";
    // echo "Geral Pendentes: ".$p."<br>";
    // echo "Geral Concluídos: ".$c."<br>";
    // echo "Geral Não Encontrados: ".$n."<br><hr>";

    // echo "Geral Urbano Iniciados: ".$iu."<br>";
    // echo "Geral Urbano Pendentes: ".$pu."<br>";
    // echo "Geral Urbano Concluídos: ".$cu."<br>";
    // echo "Geral Urbano Não Encontrados: ".$nu."<br><hr>";

    // echo "Geral Rural Iniciados: ".$ir."<br>";
    // echo "Geral Rural Pendentes: ".$pr."<br>";
    // echo "Geral Rural Concluídos: ".$cr."<br>";
    // echo "Geral Rural Não Encontrados: ".$nr."<br><hr>";

    // VERIFICANDO AS QUANTIDADE URBANOS E RURAIS
    // $u = 0;
    // $r = 0;
    // foreach($_SESSION['municipios']['quantidade']['66'] as $indice => $valores){
    //     $u += @array_multisum($_SESSION['municipios']['quantidade']['66']['Urbano']);
    //     $r += @array_multisum($_SESSION['municipios']['quantidade']['66']['Rural']);
    // }
    // echo "Geral Urbano: ".$u."<br>";
    // echo "Geral Rural: ".$r."<br><hr>";

//////////////////////////////////////////////////////////////////////////////////////////
///////////CONDIÇÔES PARA A CAPITAL - CODIGO 66 ////////////////////////

$u = @array_multisum($_SESSION['municipios']['quantidade'][66]['Urbano']);
$r = @array_multisum($_SESSION['municipios']['quantidade'][66]['Rural']);

// echo "Total Urbano na Capital:".$total_capital_urbano."<br>";
// echo "Total Rural na Capital:".$total_capital_rural."<br><hr>";

// echo "Geral Urbano na capital Iniciados: ".$iuc."<br>";
// echo "Geral Urbano na capital Pendentes: ".$puc."<br>";
// echo "Geral Urbano na capital Concluídos: ".$cuc."<br>";
// echo "Geral Urbano na capital Não Encontrados: ".$nuc."<br><hr>";

foreach($iuz as $ind => $val){
    // echo "<h5>{$ind}</h5>";
    // echo "Iniciados: ".$iuz[$ind]."<br>";
    // echo "Pendentes: ".$puz[$ind]."<br>";
    // echo "Concluídos: ".$cuz[$ind]."<br>";
    // echo "Não Encontrados: ".$nuz[$ind]."<br><hr>";
}
?>
<style>
    .AreaDashboard{
        position:absolute;
        left:20px;
        right:20px;
    }
    .cartao{
        position:relative;
        width:99%;
        height:100%;
        background-color:#459adb;
        border-radius:10px;
        color:#fff;
    }
    .cartao span{
        font-size:10px;
        margin-left:10px;
    }
    .cartao p{
        font-size:25px;
        font-weight:bold;
        text-align:center;
        padding-top:0px;
        padding-bottom:15px;
    }
    .cartao div{
        position:absolute;
        right:10px;
        bottom:5px;
        width:auto;
        opacity:0;
    }
    .cartao div i{
        cursor: pointer;
        margin-left:10px;
    }
    .card-body{
        padding:0;
    }
</style>
<div class="AreaDashboard">
    <div class="row mb-3 mt-3">
        <div class="col-md-1"></div>
        <div class="col-md-10"><h3 style="color:#a1a1a1">Relatório Geral de Manaus</h3></div>
        <div class="col-md-1"></div>
    </div>
    <div class="row">
        <div class="col-md-1"></div>


        <div class="col-md-2 mb-3">
            <div class="cartao">
                <span>Toatl Geral</span>
                <p><?=$total?></p>
                <div>
                    <i acao='editar' filtro='' class="fa-solid fa-up-right-from-square"></i>
                    <i acao='download' filtro='' class="fa-solid fa-file-arrow-down"></i>
                </div>
            </div>
        </div>

        <div class="col-md-2 mb-3">
            <div class="cartao">
                <span>Pesquisas Iniciadas</span>
                <p><?=$i?></p>
                <div>
                    <i acao='editar' filtro='i' class="fa-solid fa-up-right-from-square"></i>
                    <i acao='download' filtro='i' class="fa-solid fa-file-arrow-down"></i>
                </div>
            </div>
        </div>

        <div class="col-md-2 mb-3">
            <div class="cartao">
                <span>Pesquisas Pendentes</span>
                <p><?=$p?></p>
                <div>
                    <i acao='editar' filtro='p' class="fa-solid fa-up-right-from-square"></i>
                    <i acao='download' filtro='p' class="fa-solid fa-file-arrow-down"></i>
                </div>
            </div>
        </div>

        <div class="col-md-2 mb-3">
            <div class="cartao">
                <span>Pesquisas Concluídas</span>
                <p><?=$c?></p>
                <div>
                    <i acao='editar' filtro='c' class="fa-solid fa-up-right-from-square"></i>
                    <i acao='download' filtro='c' class="fa-solid fa-file-arrow-down"></i>
                </div>
            </div>
        </div>

        <div class="col-md-2 mb-3">
            <div class="cartao">
                <span>Beneficiários não encontrados</span>
                <p><?=$n?></p>
                <div>
                    <i acao='editar' filtro='n' class="fa-solid fa-up-right-from-square"></i>
                    <i acao='download' filtro='n' class="fa-solid fa-file-arrow-down"></i>
                </div>
            </div>
        </div>

        <div class="col-md-1"></div>
    </div>

    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <div mapa="capital" style="width:100%;"></div>
        </div>
        <div class="col-md-1"></div>
    </div>


    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <div class="card h-100">
                        <h6 class="card-header">Geral por Zona</h6>
                        <div class="card-body">
                            <div grafico="geral_zona" style="width:100%;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <div class="card h-100">
                        <h6 class="card-header">Por Zona Urbana</h6>
                        <div class="card-body">
                            <div grafico="zona_urbana" style="width:100%;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <div class="card h-100">
                        <h6 class="card-header">Por Zona Rural</h6>
                        <div class="card-body">
                            <div grafico="zona_rural" style="width:100%;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <div class="card h-100">
                        <h6 class="card-header">Evolução Geral da Pesquisa</h6>
                        <div class="card-body">
                            <div grafico="geral_pesquisa" style="width:100%;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-1"></div>
    </div>



    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <div class="card h-100">
                        <h6 class="card-header">Geral por Zona Urbana</h6>
                        <div class="card-body">
                            <div grafico="geral_zona_urbana" style="width:100%;"></div>
                        </div>
                    </div>
                </div>

                <?php
                foreach($iuz as $ind => $val){
                ?>
                <div class="col-md-3 mb-3">
                    <div class="card h-100">
                        <h6 class="card-header"><?=$ind?></h6>
                        <div class="card-body">
                            <div grafico="zonas_urbanas" cod="<?=$ind?>" style="width:100%;"></div>
                        </div>
                    </div>
                </div>
                <?php
                }
                ?>


            </div>
        </div>
        <div class="col-md-1"></div>
    </div>




</div>


<script>
    $(function(){

        $(".cartao").mouseover(function(){
            $(this).children(".cartao div").css('opacity',1)
        }).mouseout(function(){
            $(this).children(".cartao div").css('opacity',0)
        })

        const AbrirMapa = (opc, url)=>{
            $.ajax({
                url,
                type:"POST",
                data:{
                    rotulo:opc,
                },
                success:function(dados){
                    $(`div[mapa="${opc}"]`).html(dados);
                }
            })
        }

        $("div[mapa]").each(function(){
            opc = $(this).attr("mapa");
            url = `src/dashboard/capital/mapa/${opc}.php`;
            AbrirMapa(opc, url);
        })


        const AbrirGrafico = (opc, url, cod)=>{
            $.ajax({
                url,
                type:"POST",
                data:{
                    rotulo:opc,
                    cod,
                },
                success:function(dados){
                    if(cod){
                        $(`div[cod="${cod}"]`).html(dados);
                    }else{
                        $(`div[grafico="${opc}"]`).html(dados);
                    }

                }
            })
        }

        $("div[grafico]").each(function(){
            cod = false;
            opc = $(this).attr("grafico");
            url = `src/dashboard/capital/grafico/${opc}.php`;
            if(opc == 'zonas_urbanas'){
                cod = $(this).attr("cod");
            }
            AbrirGrafico(opc, url, cod);
        })


    })
</script>