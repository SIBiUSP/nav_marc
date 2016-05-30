<?php
    $itens = simplexml_load_file('http://dedalus.usp.br:1891/rest-dlf/record/USP01000000191/items');
    print_r($itens);
?>
