<?php

    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");


    echo $query = "updapte produtos set 
                                    valor = '2.44'
                            where categoria = 2
    ";
    sisLog($query);
    
?>
<style>

</style>
<div class="row mb-3 mt-3">
    <div class="col-md-12">
        DashBoard
    </div>
</div>


<script>
    $(function(){
        Carregando('none')
        
    })
</script>