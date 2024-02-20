<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");
?>
<style>
    .slider{
        position:relative;
        background:#fff;
        width:100%;        
    }
    .slider-for{
        position:relative;
        width:100%;
    }
    .slider-for img{
        margin:0;
        padding:0;
        width:100%;
        height:auto;
    }
    .barra_banner{
        position: absolute;
        margin-top:-36px;
        height:30px;
        width:100%;
    }
    .barra_banner div{
        background-color:#fff;
        width:100%;
    } 
</style>

<div class="slider">
    <div class="slider-for">

        <?php
        $query = "select * from produtos where promocao = '1' and situacao = '1' and deletado != '1'";
        $result = mysqli_query($con, $query);
        while($d = mysqli_fetch_object($result)){


            if(is_file("../../src/".(($d->categoria == 8)?'combos':'produtos')."/icon/{$d->capa}")){
                $icon = "{$urlPainel}src/".(($d->categoria == 8)?'combos':'produtos')."/icon/{$d->icon}";
                $capa = "{$urlPainel}src/".(($d->categoria == 8)?'combos':'produtos')."/icon/{$d->capa}";
            }else{
                $icon = "img/transparente.png";
                $capa = "img/transparente.png";
            }

            $vl = explode(".", $d->valor_promocao);



        ?>
        <div style="position:relative; background:orange;">
            <img src="<?=$capa?>" style="width:100%; position:relative;" />
            <!-- <div style="position:absolute; left:0, right:0; bottom:30px; top:15px; z-index:10; width:100%;">
                <div class="d-flex justify-content-center"><img src="<?=$icon?>" style="width:70%;" /></div>
                <div class="d-flex justify-content-center" style="color:#fff; font-size:23px; text-align:right; font-family:FlameBold; margin-top:0px;"><?=$d->produto?></div>
                <div class="d-flex justify-content-center align-items-end">
                    <div style="color:#fff; font-size:25px; text-align:right; font-family:FlameBold; padding:20px;">R$</div>
                    <div class="d-flex justify-content-center align-items-start" style="font-size:70px; color:#fff; font-family:FlameBold; margin-top:-25px;"><?=$vl[0]?><p style="font-size:25px; color:#fff; font-family:FlameBold; padding-top:20px;"><?=$vl[1]?></p></div>
                </div>
            </div> -->
        </div>
        <?php
        }
        ?>

    </div>
    <div class="d-flex justify-content-center barra_banner">
        <div></div>
        <img src="img/banner_seta.png" />
        <div></div>
    </div>
</div>



<script>

$(function(){

    $('.slider-for').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        fade: true,
        autoplay: true,
        autoplaySpeed: 5000,
    });

})

	

</script>