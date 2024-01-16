<?php

    $rede = new Rede;
    $retorno =  $rede->Consulta('
                                {
                                    "reference":"'.$_POST['reference'].'"
                                }
                                ');
    