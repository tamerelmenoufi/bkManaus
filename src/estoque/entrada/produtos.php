<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    if($_POST['nota']) $_SESSION['nota'] = $_POST['nota'];

    // reg,
    // cProd
    // uCom,
    // qCom,
    // vUnCom,
    // acao:'conversao'

    if($_POST['acao'] == 'conversao'){
        
        // $query = "update estoque set 
        //                             uCom = '{$_POST['uCom']}',
        //                             /*qCom = '{$_POST['qCom']}',*/
        //                             vUnCom = '{$_POST['vUnCom']}'
        //         where cProd = '{$_POST['cProd']}'
        // ";
        // mysqli_query($con, $query);

        $query = "update movimentacao set 
                                    uConv = '{$_POST['uCom']}',
                                    qConv = '{$_POST['qCom']}',
                                    vUnConv = '{$_POST['vUnCom']}'
                where codigo = '{$_POST['reg']}'
        ";
        mysqli_query($con, $query);

        // exit();

    }


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
                <td>CEST</td>
                <td><?=$p->CEST?></td>
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


            <tr <?=(strtolower($p->uCom))?> <?=(strtolower($p->uConv))?> class="table-<?=((strtolower($p->uCom) != strtolower($p->uConv))?'success':'primary')?>">
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
                        <option <?=((strtolower($p->uConv) == 'gr')?'selected':false)?> value="gr">gr</option>
                        <option <?=((strtolower($p->uConv) == 'fd')?'selected':false)?> value="fd">fd</option>
                    </select>
                </td>
            </tr>
            <tr class="table-<?=((strtolower($p->uCom) != strtolower($p->uConv))?'success':'primary')?>">
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
                        <button converter = '<?=$p->codigo?>' 
                                class="btn btn-primary" 
                                type="button" 
                                id="button-addon2"
                                cProd="<?=$p->cProd?>"
                        ><i class="fa-regular fa-floppy-disk"></i></button>
                    </div>
                </td>
            </tr>
            <tr class="table-<?=((strtolower($p->uCom) != strtolower($p->uConv))?'success':'primary')?>">
                <td>Valor Unitário Convertido</td>
                <td vUnConv = '<?=$p->codigo?>'><?=((strtolower($p->uCom) != strtolower($p->uConv))?number_format($p->vUnCom/$p->qConv,10,'.',false):$p->vUnCom)?></td>
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

        Carregando('none')

        $("input").click(function(){
            $(this).select();
        })

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
            cProd = $(this).attr("cProd");
            uCom = $(`select[reg="${reg}"]`).val();
            qCom = $(`input[reg="${reg}"]`).val();
            vUnCom = $(`td[vUnConv="${reg}"]`).text();

            if(
                uCom &&
                cProd &&
                qCom*1 > 0 &&
                vUnCom*1 > 0
            ){
                Carregando()

                $.ajax({
                    url:"src/estoque/entrada/produtos.php",
                    type:"POST",
                    data:{
                        reg,
                        cProd,
                        uCom,
                        qCom,
                        vUnCom,
                        acao:'conversao'
                    },
                    success:function(dados){
                        $(".LateralDireita").html(dados);
                    }
                })


            }else{
                $.alert('Erro nos dados cadastrados!')
            }


        })


    })
</script>