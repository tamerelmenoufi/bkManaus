<?php

    $rede = new Rede;
    echo    $rede->ConsultaTID('
                            {
                                "tid":"'.$_POST['tid'].'"
                            }
                            ');