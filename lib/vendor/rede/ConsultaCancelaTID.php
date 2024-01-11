<?php

    $rede = new Rede;
    echo    $rede->ConsultaCancelaTID('
                                    {
                                        "tid":"'.$_POST['tid'].'"
                                    }
                                    ');