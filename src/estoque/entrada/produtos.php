<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    if($_POST['nota']) $_SESSION['nota'] = $_POST['nota'];

    $query = "select * from movimentacao where cod_nota = '{$_SESSION['nota']}'";
    $result = mysqli_query($con, $query);
    while($p = mysqli_fetch_object($result)){

?>
<div class="card mb-3">
  <h6 class="card-header"><?=$p->xProd?></h6>
    <table class="table table-hover">
        <tbody>

            <tr>
                <td>Código do produto</td>
                <td><?=$p->cProd?></td>
            </tr>
            <tr>
                <td>Código de barras do produto</td>
                <td><?=$p->cEAN?></td>
            </tr>
            <tr>
                <td>Descrição do produto</td>
                <td><?=$p->xProd?></td>
            </tr>
            <tr>
                <td>NCM</td>
                <td><?=$p->NCM?></td>
            </tr>
            <tr>
                <td>CFOP</td>
                <td><?=$p->CFOP?></td>
            </tr>
            <tr>
                <td>Unidade</td>
                <td><?=$p->uCom?></td>
            </tr>
            <tr>
                <td>Quantidade</td>
                <td><?=$p->qCom?></td>
            </tr>
            <tr>
                <td>Valor unitário</td>
                <td><?=$p->vUnCom?></td>
            </tr>
            <tr>
                <td>Valor total bruto</td>
                <td><?=$p->vProd?></td>
            </tr>


            <tr class="table-primary">
                <td>Unidade Convertida</td>
                <td>
                    <select class="form-select form-select-sm uConv" 
                            quantidade="<?=$p->qCom?>" 
                            reg="<?=$p->codigo?>" 
                            vUnCom='<?=$p->vUnCom?>' 
                    >
                        <option <?=((strtolower($p->uConv) == 'un')?'selected':false)?> value="un">un</option>
                        <option <?=((strtolower($p->uConv) == 'cx')?'selected':false)?> value="cx">cx</option>
                        <option <?=((strtolower($p->uConv) == 'pct')?'selected':false)?> value="pct">pct</option>
                        <option <?=((strtolower($p->uConv) == 'kg')?'selected':false)?> value="kg">kg</option>
                    </select>
                </td>
            </tr>
            <tr class="table-primary">
                <td>Quantidade Convertida</td>
                <td>
                    <div class="input-group mb-3">
                        <input reg="<?=$p->codigo?>" 
                               class="form-control form-control-sm qConv" 
                               type="text" 
                               placeholder="0.0000" 
                               value="<?=$p->qConv?>"
                               quantidade="<?=$p->qCom?>" 
                               vUnCom='<?=$p->vUnCom?>' 
                        >
                        <button converter = '<?=$p->codigo?>' class="btn btn-primary" type="button" id="button-addon2"><i class="fa-regular fa-floppy-disk"></i></button>
                    </div>
                </td>
            </tr>
            <tr class="table-primary">
                <td>Valor Unitário Convertido</td>
                <td vUnConv = '<?=$p->codigo?>'><?=$p->vUnConv?></td>
            </tr>


            <tr>
                <td>Código de barras tributável</td>
                <td><?=$p->cEANTrib?></td>
            </tr>
            <tr>
                <td>Unidade tributável</td>
                <td><?=$p->uTrib?></td>
            </tr>
            <tr>
                <td>Quantidade tributáve</td>
                <td><?=$p->qTrib?></td>
            </tr>
            <tr>
                <td>Valor unitário de tributação</td>
                <td><?=$p->vUnTrib?></td>
            </tr>
            <tr>
                <td>Indicador de totalização</td>
                <td><?=(($p->indTot)?'Sim':'Não')?></td>
            </tr>
        </tbody>
    </table>
</div>    
<?php
    }
?>

<script>
    $(function(){


        $('.qConv').mask("#0.0000", {reverse: true});

        $('.qConv').keyup(function(){
            qConv = $(this).val();
            q = $(this).attr("quantidade");
            vUnCom = $(this).attr("vUnCom");
            reg = $(this).attr("reg");

            total = (vUnCom/qConv)

            $("td[vUnConv]").html(total.toFixed(10))

        })

        $("button[converter]").click(function(){
            reg = $(this).attr("converter");

            uCom = $(`select[reg="${reg}"]`).val();
            vCom = $(`input[reg="${reg}"]`).val();
            vUnCom = $(`td[vUnConv="${reg}"]`).text();

            if(
                uCom &&
                vCom*1 > 0 &&
                vUnCom*1 > 0
            ){
                console.log(uCom)
                console.log(vCom)
                console.log(vUnCom)
            }else{
                $.alert('Erro nos dados cadastrados!')
            }


        })


    })
</script>