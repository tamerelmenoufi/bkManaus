<?php

    $rede = new Rede;
    echo    $rede->Cancelar('
                            {
                                "tid":"'.$_POST['tid'].'",
                                "amount":'.$_POST['amount'].',
                                "url":"'.$_POST['url'].'"
                            }
                            ');