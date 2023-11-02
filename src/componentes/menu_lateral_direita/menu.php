<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");
?>
<div
    class="offcanvas offcanvas-end"
    data-bs-backdrop="static"
    tabindex="-1"
    id="offcanvasDireita"
    aria-labelledby="offcanvasDireitaLabel"
    style="--bs-offcanvas-width:500px;"
  >
  <div class="offcanvas-header">
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body LateralDireita"></div>
</div>

<!--
class="btn btn-primary"
data-bs-toggle="offcanvas"
href="#offcanvasDireita"
role="button"
aria-controls="offcanvasDireita" -->

<script>


 if( navigator.userAgent.match(/Android/i)
 || navigator.userAgent.match(/webOS/i)
 || navigator.userAgent.match(/iPhone/i)
 || navigator.userAgent.match(/iPad/i)
 || navigator.userAgent.match(/iPod/i)
 || navigator.userAgent.match(/BlackBerry/i)
 || navigator.userAgent.match(/Windows Phone/i)
 ){
    $("#offcanvasDireita").css("--bs-offcanvas-width","100%")
  }
 else {
    $("#offcanvasDireita").css("--bs-offcanvas-width","600px")
  }


</script>