<?php

    $rede = new Rede;
    echo $rede->capture('
                        {
                            "tid":"'.$_POST['tid'].'",
                            "amount":'.$_POST['amount'].'
                        }
                        ');