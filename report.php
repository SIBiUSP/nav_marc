<?php
  $tpTitle = 'BDPI USP - Relatório gerencial';

  include 'inc/config.php';
  include 'inc/meta-header.php';
  include_once 'inc/functions.php';

  /* Pegar a URL atual */
if (strpos($_SERVER['REQUEST_URI'], '?') !== false) {
      $url = "//{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
} else {
        $url = "//{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}?";
}
    $escaped_url = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
    /* Query */
if (empty($_GET)) {
        $query = json_decode('{}');
} elseif (!empty($_GET['search_index'])) {
        $q = str_replace('"', '\\"', $_GET['search_index']);
        unset($_GET['search_index']);
        $consult = '';
    foreach ($_GET as $key => $value) {
            $consult .= '"'.$key.'":"'.$value.'",';
    }
        $query = json_decode('{'.$consult.'"$text": {"$search":"'.$q.'"}}');
    if ((array_key_exists("date_init", $query))||(array_key_exists("date_end", $query))) {
        if (array_key_exists("date_init", $query)) {
                $query["year"]["\$gte"] = $query["date_init"];
        } else {
                $query["year"]["\$gte"] = "1";
        }
        if (array_key_exists("date_end", $query)) {
                $query["year"]["\$lte"] = $query["date_end"];
        } else {
                $query["year"]["\$lte"] = "20500";
        }
          unset($query["date_init"]);
          unset($query["date_end"]);
    }
} else {
        $query = array();
    foreach ($_GET as $key => $value) {
            $query[$key] = $value;
    }
    if ((array_key_exists("date_init", $query))||(array_key_exists("date_end", $query))) {
        if (array_key_exists("date_init", $query)) {
                $query["year"]["\$gte"] = $query["date_init"];
        } else {
            $query["year"]["\$gte"] = "1";
        }
        if (array_key_exists("date_end", $query)) {
            $query["year"]["\$lte"] = $query["date_end"];
        } else {
            $query["year"]["\$lte"] = "20500";
        }
          unset($query["date_init"]);
          unset($query["date_end"]);
    }
}
  /* Pagination variables */
    $page = isset($_POST['page']) ? (int) $_POST['page'] : 1;
    $limit = 15;
    $skip = ($page - 1) * $limit;
    $next = ($page + 1);
    $prev = ($page - 1);
    $sort = array('year' => -1);
  /* Consultas */
    $query_json = json_encode($query);
    $query_new = json_decode('[{"$match":'.$query_json.'},{"$lookup":{"from": "producao_bdpi", "localField": "_id", "foreignField": "_id", "as": "bdpi"}},{"$sort":{"year":-1}},{"$limit":'.$limit.'}]');
    $query_count = json_decode('[{"$match":'.$query_json.'},{"$group":{"_id":null,"count":{"$sum": 1}}}]');
    $cursor = $c->aggregate($query_new);
    $total_count = $c->aggregate($query_count);
    $total = $total_count['result'][0]['count'];

?>

<!-- D3.js Libraries and CSS -->
<script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/d3/3.2.2/d3.v3.min.js"></script>

<!-- UV Charts -->
<script type="text/javascript" src=inc/uvcharts/uvcharts.full.min.js></script>
<script type="text/javascript" src="http://gabelerner.github.io/canvg/rgbcolor.js"></script> 
<script type="text/javascript" src="http://gabelerner.github.io/canvg/StackBlur.js"></script>
<script type="text/javascript" src="http://gabelerner.github.io/canvg/canvg.js"></script> 
<script type="text/javascript" src="https://blueimp.github.io/JavaScript-Canvas-to-Blob/js/canvas-to-blob.js"></script>

<!-- Save as javascript -->
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
</script>
</head>
<body>
   <?php include 'inc/barrausp.php'; ?>     
  <div id="body" class="ui main container">
      <?php include 'inc/header.php'; ?>
      <?php include 'inc/navbar.php'; ?> 
    <h3>Relatório com os seguintes parâmetros:
    <?php foreach ($_GET as $filters) : ?>
    <?php echo $filters;?>
    <?php endforeach;?>
    </h3><br/><br/>


    <div class="ui vertical stripe segment">
    <div class="ui text container">
    <h3 class="ui header">Total</h3><br/><br/>
    <div class="ui one statistics">
      <div class="statistic">
        <div class="value">
          <i class="file icon"></i> <?php echo $total; ?>
        </div>
        <div class="label">
          Quantidade de registros
        </div>
      </div>
    </div>
    </div>
    </div>


<h3>Tipo de publicação (Somente os primeiros)</h3>
<?php $type_mat_bar = generateDataGraphBar($url, $c, $query, '$type', 'count', -1, 'Tipo de publicação', 4); ?>
      <div id="type_chart" style="font-size:10px"></div>
        <script type="application/javascript">
        var graphdef = {
            categories : ['Tipo'],
            dataset : {
                'Tipo' : [<?= $type_mat_bar; ?>]
            }
        }
        var chart = uv.chart ('Bar', graphdef, {
            meta : {
                position: '#type_chart',
                caption : 'Tipo de trabalho',
                hlabel : 'Tipo',
                vlabel : 'Registros',
                isDownloadable: true,
                downloadLabel: 'Baixar'
            },
            graph : {
                orientation : "Vertical"
            },
            dimension : {
                width: 900,
                height: 300
            }
        })
        </script> 
<?php generateDataTable($url, $c, $query, '$type', 'count', -1, 'Tipo de publicação', 9); ?>

<?php $csv_type = generateCSV($url, $c, $query, '$type', 'count', -1, 'Tipo de publicação', 500); ?>
<button class="ui blue label" onclick="SaveAsFile('<?php echo $csv_type; ?>','tipo_de_material.csv','text/plain;charset=utf-8')">
    Exportar todos os tipos de publicação em csv
</button>

      
<h3>Unidade USP - Trabalhos (10 primeiros)</h3>
<?php $unidadeUSP_trab_bar = generateDataGraphBar($url, $c, $query, '$unidadeUSPtrabalhos', 'count', -1, 'Unidade USP - Trabalhos', 9); ?>
      

      <div id="unidadeUSP_chart"></div>
        <script type="application/javascript">
        var graphdef = {
            categories : ['Unidade USP'],
            dataset : {
                'Unidade USP' : [<?= $unidadeUSP_trab_bar; ?>]
            }
        }
        var chart = uv.chart ('Bar', graphdef, {
            meta : {
                position: '#unidadeUSP_chart',
                caption : 'Unidade USP',
                hlabel : 'Unidade USP',
                vlabel : 'Registros',
                isDownloadable: true,
                downloadLabel: 'Baixar'
            },
            graph : {
                orientation : "Vertical"
            },
            dimension : {
                width: 900,
                height: 300
            }
        })
        </script> 

<?php generateDataTable($url, $c, $query, '$unidadeUSPtrabalhos', 'count', -1, 'Unidade USP - Trabalhos', 9); ?>
<?php $csv_unidadeUSPtrabalhos = generateCSV($url, $c, $query, '$unidadeUSPtrabalhos', 'count', -1, 'Unidade USP - Trabalhos', 10000); ?>
<button  class="ui blue label" onclick="SaveAsFile('<?php echo $csv_unidadeUSPtrabalhos; ?>','unidadeUSP_trabalhos.csv','text/plain;charset=utf-8')">Exportar todas os trabalhos por unidades em csv</button>      

<h3>Unidade USP - Participações (10 primeiros)</h3>
<?php generateDataTable($url, $c, $query, '$unidadeUSP', 'count', -1, 'Unidade USP - Participações', 9); ?>
<?php $csv_unidadeUSP = generateCSV($url, $c, $query, '$unidadeUSP', 'count', -1, 'Unidade USP - Participações', 10000); ?>
<button  class="ui blue label" onclick="SaveAsFile('<?php echo $csv_unidadeUSP; ?>','unidadeUSP_participacoes.csv','text/plain;charset=utf-8')">Exportar todas participações por Unidade em csv</button>


 

<h3>Departamento - Participações</h3>
<?php generateDataTable($url, $c, $query, '$departamento', 'count', -1, 'Departamento - Participações', 9); ?>
<?php $csv_departamento = generateCSV($url, $c, $query, '$departamento', 'count', -1, 'Departamento - Participações', 10000); ?>
<button  class="ui blue label" onclick="SaveAsFile('<?php echo str_replace("'", "", $csv_departamento); ?>','departamento_part.csv','text/plain;charset=utf-8')">
    Exportar todos as participações dos departamentos em csv
</button>



<h3>Autores USP (10 primeiros)</h3>
<?php generateDataTable($url, $c, $query, '$authorUSP', 'count', -1, 'Autores USP', 9); ?>
<?php $csv_authorUSP = generateCSV($url, $c, $query, '$authorUSP', 'count', -1, 'Autores USP', 10000); ?>
<button  class="ui blue label" onclick="SaveAsFile('<?php echo str_replace("'", "", $csv_authorUSP); ?>','autoresUSP.csv','text/plain;charset=utf-8')">Exportar todos os autores em csv</button>
      
      
<h3>Obra da qual a produção faz parte (10 primeiros)</h3>      
<?php generateDataTable($url, $c, $query, '$ispartof', 'count', -1, 'Obra da qual a produção faz parte', 9); ?>
<?php $csv_ispartof = generateCSV($url, $c, $query, '$ispartof', 'count', -1, 'Obra da qual a produção faz parte', 20000); ?>
<?php $csv_ispartof = str_replace('"', '', $csv_ispartof); ?>
<button  class="ui blue label" onclick="SaveAsFile('<?php echo str_replace("'", "", $csv_ispartof); ?>','obras.csv','text/plain;charset=utf-8')">Exportar todos as obras em csv</button>
      

<h3>Nome do evento (10 primeiros)</h3>        
<?php generateDataTable($url, $c, $query, '$evento', 'count', -1, 'Nome do evento', 9); ?>
<?php $csv_evento = generateCSV($url, $c, $query, '$evento', 'count', -1, 'Nome do evento', 10000); ?>
<?php $csv_evento = str_replace('"', '', $csv_evento); ?>
<button  class="ui blue label" onclick="SaveAsFile('<?php echo str_replace("'", "", $csv_evento); ?>','evento.csv','text/plain;charset=utf-8')">Exportar todos os eventos em csv</button>
      
      
<h3>Ano de publicação</h3>  
<?php $ano_bar = generateDataGraphBar($url, $c, $query, '$year', '_id', -1, 'Ano', 19); ?>
      

      <div id="ano_chart"></div>
        <script type="application/javascript">
        var graphdef = {
            categories : ['Ano'],
            dataset : {
                'Ano' : [<?= $ano_bar; ?>]
            }
        }
        var chart = uv.chart ('Bar', graphdef, {
            meta : {
                position: '#ano_chart',
                caption : 'Ano de publicação',
                hlabel : 'Ano',
                vlabel : 'Registros',
                isDownloadable: true,
                downloadLabel: 'Baixar'
            },
            graph : {
                orientation : "Vertical"
            },
            dimension : {
                width: 900,
                height: 300
            }
        })
        </script>       
      
<?php generateDataTable($url, $c, $query, '$year', '_id', -1, 'Ano de publicação', 200); ?>
<?php $csv_year = generateCSV($url, $c, $query, '$year', '_id', -1, 'Ano de publicação', 10000); ?>
<button  class="ui blue label" onclick="SaveAsFile('<?php echo $csv_year; ?>','ano.csv','text/plain;charset=utf-8')">Exportar todos os anos em csv</button>
      
<h3>Idioma</h3>       
<?php generateDataTable($url, $c, $query, '$language', 'count', -1, 'Idioma', 10); ?>
<?php $csv_language = generateCSV($url, $c, $query, '$language', 'count', -1, 'Idioma', 10000); ?>
<button  class="ui blue label" onclick="SaveAsFile('<?php echo $csv_language; ?>','idioma.csv','text/plain;charset=utf-8')">Exportar todos os idiomas em csv</button>
      
<h3>Internacionalização</h3>  
      
    
        <div id="internacionalização_chart"></div>
      <?php $internacionalizacao_bar = generateDataGraphBar($url, $c, $query, '$internacionalizacao', 'count', -1, 'Internacionalização', 10); ?>
        <script type="application/javascript">
        var graphdef = {
            categories : ['Internacionalização'],
            dataset : {
                'Internacionalização' : [<?= $internacionalizacao_bar; ?>]
            }
        }
        var chart = uv.chart ('Pie', graphdef, {
            meta : {
                position: '#internacionalização_chart',
                caption : 'Internacionalização',
                subcaption : 'Trabalhos publicados em publicações internacionais',
                hlabel : 'Registros',
                vlabel : 'Local',
                isDownloadable: true,
                downloadLabel: 'Baixar'
            },
            dimension : {
                width: document.getElementById("body").offsetWidth,
                height: 600
            }
        })
        </script>      
      
<?php generateDataTable($url, $c, $query, '$internacionalizacao', 'count', -1, 'Internacionalização', 10); ?>
<?php $csv_internacionalizacao = generateCSV($url, $c, $query, '$internacionalizacao', 'count', -1, 'Internacionalização', 10000); ?>
<button  class="ui blue label" onclick="SaveAsFile('<?php echo $csv_internacionalizacao; ?>','internacionalizacao.csv','text/plain;charset=utf-8')">Exportar em csv</button>
      
<h3>País de publicação</h3>
<?php generateDataTable($url, $c, $query, '$country', 'count', -1, 'País de publicação', 10); ?>
<?php $csv_country = generateCSV($url, $c, $query, '$country', 'count', -1, 'País de publicação', 10000); ?>
<button  class="ui blue label" onclick="SaveAsFile('<?php echo str_replace("'", "", $csv_country); ?>','pais.csv','text/plain;charset=utf-8')">Exportar todos em csv</button>

    </div>

</div>
<?php
  include 'inc/footer.php';
?>
<script>
$('.ui.accordion')
  .accordion()
;
</script>
<script>
$('.menu .item')
  .tab()
;
</script>
</body>
</html>
