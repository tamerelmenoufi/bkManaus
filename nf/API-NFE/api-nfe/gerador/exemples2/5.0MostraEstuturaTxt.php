<?php
$json = file_get_contents('txtstructure310.json');
$lines = json_decode($json, true);
foreach($lines as $lin) {
    echo $lin.'<br>';
}