<?php

    $rede = new Rede;
    echo    $rede->ConsultaRefundId('
                                    {
                                        "tid":"'.$_POST['tid'].'",
                                        "refundId":"'.$_POST['refundId'].'"
                                    }
                                    ');