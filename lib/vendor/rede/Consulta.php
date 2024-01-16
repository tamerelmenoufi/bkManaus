<?php

    $rede = new Rede;
    $retorno =  $rede->Consulta('
                                {
                                    "reference":"'.$_POST['reference'].'"
                                }
                                ');
    file_put_contents('cartao.txt', $retorno );