<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

?>


<div class="card m-3">
  <h5 class="card-header">Featured</h5>
  <div class="card-body">
    <h5 class="card-title">Special title treatment</h5>
    <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
    <a href="#" class="btn btn-primary">Go somewhere</a>
  </div>
</div>


<?php
    $q = "select * from empresas where tipo = 'g' order by nome asc";
    $r = mysqli_query($con, $q);
    while($e = mysqli_fetch_object($r)){
    ?>
    <div class="row mb-1">
      <div class="col">
        <a url="src/estoque/empresas/index.php?cod=<?=$e->codigo?>" class="text-decoration-none" data-bs-dismiss="offcanvas" aria-label="Close">
          <i class="fa-regular fa-user col-1"></i> <span class="col-11"><?=$e->nome?></span>
        </a>
      </div>
    </div>  
    <?php
    }
    ?>



<script>
  $(function(){
      Carregando('none');
  })
</script>