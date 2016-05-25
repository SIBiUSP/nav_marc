<?php
include 'inc/config.php';
#Consultas
$query = json_decode('[{"$match":{"_id":"'.$_GET["_id"].'"}},{"$lookup":{"from": "producao_bdpi", "localField": "_id", "foreignField": "_id", "as": "files"}}]');
$cursor = $c->aggregate($query);

$record = [];

switch ($cursor["result"][0]["type"]) {
case "ARTIGO DE PERIODICO":
    $record[] = "TY  - JOUR";
    break;
case "PARTE DE MONOGRAFIA/LIVRO":
    $record[] = "TY  - CHAP";
    break;
case "TRABALHO DE EVENTO-RESUMO":
    $record[] = "TY  - CPAPER";
    break;
case "TEXTO NA WEB":
    $record[] = "TY  - ICOMM";
    break;
}

$record[] = "TI  - ".$cursor["result"][0]['title']."";

if (!empty($cursor["result"][0]['year'])) {
$record[] = "PY  - ".$cursor["result"][0]['year']."";
}

foreach ($cursor["result"][0]['authors'] as $autores) {
  $record[] = "AU  - ".$autores."";
}

if (!empty($cursor["result"][0]['ispartof'])) {
$record[] = "T2  - ".$cursor["result"][0]['ispartof']."";
}

if (!empty($cursor["result"][0]['issn_part'][0])) {
$record[] = "SN  - ".$cursor["result"][0]['issn_part'][0]."";
}

if (!empty($cursor["result"][0]["doi"])) {
$record[] = "DO  - ".$cursor["result"][0]["doi"][0]."";
}

if (!empty($cursor["result"][0]["ispartof_data"])) {
  foreach ($cursor["result"][0]["ispartof_data"] as $ispartof_data) {
    if (strpos($ispartof_data, 'v.') !== false) {
      $record[] = "VL  - ".str_replace("v.","",$ispartof_data)."";
    } elseif (strpos($ispartof_data, 'n.') !== false) {
      $record[] = "IS  - ".str_replace("n.","",$ispartof_data)."";
    } elseif (strpos($ispartof_data, 'p.') !== false) {
      $record[] = "SP  - ".str_replace("p.","",$ispartof_data)."";
    }
  }
}


$record_blob = implode("\\n", $record);

?>


<script src="http://cdn.jsdelivr.net/g/filesaver.js"></script>
<script>
      function SaveAsFile(t,f,m) {
            try {
                var b = new Blob([t],{type:m});
                saveAs(b, f);
            } catch (e) {
                window.open("data:"+m+"," + encodeURIComponent(t), '_blank','');
            }
        }

SaveAsFile("<?= $record_blob; ?>","record.ris","text/plain;charset=utf-8");

</script>
