<?php
  $tpTitle = 'BDPI USP - Relatório gerencial';

  include 'inc/config.php';
  include 'inc/header.php';
  include_once 'inc/functions.php';

  /* Citeproc-PHP*/
  include 'inc/citeproc-php/CiteProc.php';
  $csl = file_get_contents('inc/citeproc-php/style/abnt.csl');
  $lang = 'br';
  $citeproc = new citeproc($csl, $lang);
  $mode = 'reference';

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
} elseif (!empty($_GET['category'])) {
        unset($_GET['category']);
        $q = str_replace('"', '\\"', $_GET['q']);
        unset($_GET['q']);
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
<script type="text/javascript" src="http://mbostock.github.com/d3/d3.js?2.1.3"></script>
<script type="text/javascript" src="http://mbostock.github.com/d3/d3.geom.js?2.1.3"></script>
<script type="text/javascript" src="http://mbostock.github.com/d3/d3.layout.js?2.1.3"></script>

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


<style type="text/css">
    .slice text {
        font-size: 8pt;
        font-family: Arial;
    }
</style>

<style type="text/css">
.axis path, .axis line
{
    fill: none;
    stroke: #777;
    shape-rendering: crispEdges;
}

.axis text
{
    font-family: 'Arial';
    font-size: 8px;
}
.tick
{
    stroke-dasharray: 1, 2;
}
.bar
{
    fill: FireBrick;
}

</style>

</head>
<body>
  <div class="ui main container">
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


<h3>Tipo de publicação (10 primeiros)</h3>
<svg id="mat_type" width="1000" height="500"></svg>
<?php $type_mat_bar = generateDataGraphBar($url, $c, $query, '$type', 'count', -1, 'Tipo de publicação', 10); ?>

<script>
InitChart();

function InitChart() {

  var barData = [<?= $type_mat_bar; ?>];

  var vis = d3.select('#mat_type'),
    WIDTH = 1000,
    HEIGHT = 500,
    MARGINS = {
      top: 20,
      right: 20,
      bottom: 20,
      left: 50
    },
    xRange = d3.scale.ordinal().rangeRoundBands([MARGINS.left, WIDTH - MARGINS.right], 0.1).domain(barData.map(function (d) {
      return d.x;
    })),


    yRange = d3.scale.linear().range([HEIGHT - MARGINS.top, MARGINS.bottom]).domain([0,
      d3.max(barData, function (d) {
        return d.y;
      })
    ]),

    xAxis = d3.svg.axis()
      .scale(xRange)
      .tickSize(5)
      .tickSubdivide(true),

    yAxis = d3.svg.axis()
      .scale(yRange)
      .tickSize(5)
      .orient("left")
      .tickSubdivide(true);


  vis.append('svg:g')
    .attr('class', 'x axis')
    .attr('transform', 'translate(0,' + (HEIGHT - MARGINS.bottom) + ')')
    .call(xAxis);

  vis.append('svg:g')
    .attr('class', 'y axis')
    .attr('transform', 'translate(' + (MARGINS.left) + ',0)')
    .call(yAxis);

  vis.selectAll('rect')
    .data(barData)
    .enter()
    .append('rect')
    .attr('x', function (d) {
      return xRange(d.x);
    })
    .attr('y', function (d) {
      return yRange(d.y);
    })
    .attr('width', xRange.rangeBand())
    .attr('height', function (d) {
      return ((HEIGHT - MARGINS.bottom) - yRange(d.y));
    })
    .attr('fill', 'grey')
    .on('mouseover',function(d){
      d3.select(this)
        .attr('fill','blue');
    })
    .on('mouseout',function(d){
      d3.select(this)
        .attr('fill','grey');
    });

}
</script>

<?php generateDataTable($url, $c, $query, '$type', 'count', -1, 'Tipo de publicação', 10); ?>

<?php $csv_type = generateCSV($url, $c, $query, '$type', 'count', -1, 'Tipo de publicação', 500); ?>
<button class="ui blue label" onclick="SaveAsFile('<?php echo $csv_type; ?>','tipo_de_material.csv','text/plain;charset=utf-8')">
    Exportar todos os tipos de publicação em csv
</button>

<h3>Unidade USP - Trabalhos (10 primeiros)</h3>
<svg id="unidadeUSP_trab_bar" width="1000" height="500"></svg>
<?php $unidadeUSP_trab_bar = generateDataGraphBar($url, $c, $query, '$unidadeUSPtrabalhos', 'count', -1, 'Unidade USP - Trabalhos', 10); ?>

<script>
InitChart();

function InitChart() {

  var barData = [<?= $unidadeUSP_trab_bar; ?>];

  var vis = d3.select('#unidadeUSP_trab_bar'),
    WIDTH = 1000,
    HEIGHT = 500,
    MARGINS = {
      top: 20,
      right: 20,
      bottom: 20,
      left: 50
    },
    xRange = d3.scale.ordinal().rangeRoundBands([MARGINS.left, WIDTH - MARGINS.right], 0.1).domain(barData.map(function (d) {
      return d.x;
    })),


    yRange = d3.scale.linear().range([HEIGHT - MARGINS.top, MARGINS.bottom]).domain([0,
      d3.max(barData, function (d) {
        return d.y;
      })
    ]),

    xAxis = d3.svg.axis()
      .scale(xRange)
      .tickSize(5)
      .tickSubdivide(true),

    yAxis = d3.svg.axis()
      .scale(yRange)
      .tickSize(5)
      .orient("left")
      .tickSubdivide(true);


  vis.append('svg:g')
    .attr('class', 'x axis')
    .attr('transform', 'translate(0,' + (HEIGHT - MARGINS.bottom) + ')')
    .call(xAxis);

  vis.append('svg:g')
    .attr('class', 'y axis')
    .attr('transform', 'translate(' + (MARGINS.left) + ',0)')
    .call(yAxis);

  vis.selectAll('rect')
    .data(barData)
    .enter()
    .append('rect')
    .attr('x', function (d) {
      return xRange(d.x);
    })
    .attr('y', function (d) {
      return yRange(d.y);
    })
    .attr('width', xRange.rangeBand())
    .attr('height', function (d) {
      return ((HEIGHT - MARGINS.bottom) - yRange(d.y));
    })
    .attr('fill', 'grey')
    .on('mouseover',function(d){
      d3.select(this)
        .attr('fill','blue');
    })
    .on('mouseout',function(d){
      d3.select(this)
        .attr('fill','grey');
    });

}
</script>

<?php generateDataTable($url, $c, $query, '$unidadeUSPtrabalhos', 'count', -1, 'Unidade USP - Trabalhos', 10); ?>
<?php $csv_unidadeUSPtrabalhos = generateCSV($url, $c, $query, '$unidadeUSPtrabalhos', 'count', -1, 'Unidade USP - Trabalhos', 10000); ?>
<button  class="ui blue label" onclick="SaveAsFile('<?php echo $csv_unidadeUSPtrabalhos; ?>','unidadeUSP_trabalhos.csv','text/plain;charset=utf-8')">Exportar todas os trabalhos por unidades em csv</button>      

<h3>Unidade USP - Participações (10 primeiros)</h3>
<svg id="unidadeUSP_part_bar" width="1000" height="500"></svg>
<?php $unidadeUSP_part_bar = generateDataGraphBar($url, $c, $query, '$unidadeUSP', 'count', -1, 'Unidade USP - Participações', 10); ?>

<script>
InitChart();

function InitChart() {

  var barData = [<?= $unidadeUSP_part_bar; ?>];

  var vis = d3.select('#unidadeUSP_part_bar'),
    WIDTH = 1000,
    HEIGHT = 500,
    MARGINS = {
      top: 20,
      right: 20,
      bottom: 20,
      left: 50
    },
    xRange = d3.scale.ordinal().rangeRoundBands([MARGINS.left, WIDTH - MARGINS.right], 0.1).domain(barData.map(function (d) {
      return d.x;
    })),


    yRange = d3.scale.linear().range([HEIGHT - MARGINS.top, MARGINS.bottom]).domain([0,
      d3.max(barData, function (d) {
        return d.y;
      })
    ]),

    xAxis = d3.svg.axis()
      .scale(xRange)
      .tickSize(5)
      .tickSubdivide(true),

    yAxis = d3.svg.axis()
      .scale(yRange)
      .tickSize(5)
      .orient("left")
      .tickSubdivide(true);


  vis.append('svg:g')
    .attr('class', 'x axis')
    .attr('transform', 'translate(0,' + (HEIGHT - MARGINS.bottom) + ')')
    .call(xAxis);

  vis.append('svg:g')
    .attr('class', 'y axis')
    .attr('transform', 'translate(' + (MARGINS.left) + ',0)')
    .call(yAxis);

  vis.selectAll('rect')
    .data(barData)
    .enter()
    .append('rect')
    .attr('x', function (d) {
      return xRange(d.x);
    })
    .attr('y', function (d) {
      return yRange(d.y);
    })
    .attr('width', xRange.rangeBand())
    .attr('height', function (d) {
      return ((HEIGHT - MARGINS.bottom) - yRange(d.y));
    })
    .attr('fill', 'grey')
    .on('mouseover',function(d){
      d3.select(this)
        .attr('fill','blue');
    })
    .on('mouseout',function(d){
      d3.select(this)
        .attr('fill','grey');
    });

}
</script>

<?php generateDataTable($url, $c, $query, '$unidadeUSP', 'count', -1, 'Unidade USP - Participações', 10); ?>
<?php $csv_unidadeUSP = generateCSV($url, $c, $query, '$unidadeUSP', 'count', -1, 'Unidade USP - Participações', 10000); ?>
<button  class="ui blue label" onclick="SaveAsFile('<?php echo $csv_unidadeUSP; ?>','unidadeUSP_participacoes.csv','text/plain;charset=utf-8')">Exportar todas participações por Unidade em csv</button>


 

<h3>Departamento - Participações</h3>
<svg id="departamento_part_bar" width="1000" height="500"></svg>
<?php $departamento_part_bar = generateDataGraphBar($url, $c, $query, '$departamento', 'count', -1, 'Departamento - Participações', 10); ?>

<script>
InitChart();

function InitChart() {

  var barData = [<?= $departamento_part_bar; ?>];

  var vis = d3.select('#departamento_part_bar'),
    WIDTH = 1000,
    HEIGHT = 500,
    MARGINS = {
      top: 20,
      right: 20,
      bottom: 20,
      left: 50
    },
    xRange = d3.scale.ordinal().rangeRoundBands([MARGINS.left, WIDTH - MARGINS.right], 0.1).domain(barData.map(function (d) {
      return d.x;
    })),


    yRange = d3.scale.linear().range([HEIGHT - MARGINS.top, MARGINS.bottom]).domain([0,
      d3.max(barData, function (d) {
        return d.y;
      })
    ]),

    xAxis = d3.svg.axis()
      .scale(xRange)
      .tickSize(5)
      .tickSubdivide(true),

    yAxis = d3.svg.axis()
      .scale(yRange)
      .tickSize(5)
      .orient("left")
      .tickSubdivide(true);


  vis.append('svg:g')
    .attr('class', 'x axis')
    .attr('transform', 'translate(0,' + (HEIGHT - MARGINS.bottom) + ')')
    .call(xAxis);

  vis.append('svg:g')
    .attr('class', 'y axis')
    .attr('transform', 'translate(' + (MARGINS.left) + ',0)')
    .call(yAxis);

  vis.selectAll('rect')
    .data(barData)
    .enter()
    .append('rect')
    .attr('x', function (d) {
      return xRange(d.x);
    })
    .attr('y', function (d) {
      return yRange(d.y);
    })
    .attr('width', xRange.rangeBand())
    .attr('height', function (d) {
      return ((HEIGHT - MARGINS.bottom) - yRange(d.y));
    })
    .attr('fill', 'grey')
    .on('mouseover',function(d){
      d3.select(this)
        .attr('fill','blue');
    })
    .on('mouseout',function(d){
      d3.select(this)
        .attr('fill','grey');
    });

}
</script>

<?php generateDataTable($url, $c, $query, '$departamento', 'count', -1, 'Departamento - Participações', 10); ?>
<?php $csv_departamento = generateCSV($url, $c, $query, '$departamento', 'count', -1, 'Departamento - Participações', 10000); ?>
<button  class="ui blue label" onclick="SaveAsFile('<?php echo $csv_departamento; ?>','departamento_part.csv','text/plain;charset=utf-8')">
    Exportar todos as participações dos departamentos em csv
</button>



<h3>Autores USP (10 primeiros)</h3>
<?php generateDataTable($url, $c, $query, '$authorUSP', 'count', -1, 'Autores USP', 10); ?>
<?php $csv_authorUSP = generateCSV($url, $c, $query, '$authorUSP', 'count', -1, 'Autores USP', 10000); ?>
<button  class="ui blue label" onclick="SaveAsFile('<?php echo $csv_authorUSP; ?>','autoresUSP.csv','text/plain;charset=utf-8')">Exportar todos os autores em csv</button>
      
      
<h3>Obra da qual a produção faz parte (10 primeiros)</h3>      
<?php generateDataTable($url, $c, $query, '$ispartof', 'count', -1, 'Obra da qual a produção faz parte', 10); ?>
<?php $csv_ispartof = generateCSV($url, $c, $query, '$ispartof', 'count', -1, 'Obra da qual a produção faz parte', 20000); ?>
<button  class="ui blue label" onclick="SaveAsFile('<?php echo $csv_ispartof; ?>','obras.csv','text/plain;charset=utf-8')">Exportar todos as obras em csv</button>
      

<h3>Nome do evento (10 primeiros)</h3>        
<?php generateDataTable($url, $c, $query, '$evento', 'count', -1, 'Nome do evento', 10); ?>
<?php $csv_evento = generateCSV($url, $c, $query, '$evento', 'count', -1, 'Nome do evento', 10000); ?>
<button  class="ui blue label" onclick="SaveAsFile('<?php echo $csv_evento; ?>','evento.csv','text/plain;charset=utf-8')">Exportar todos os eventos em csv</button>
      
      
<h3>Ano de publicação</h3>  
<?php generateDataTable($url, $c, $query, '$year', '_id', -1, 'Ano de publicação', 200); ?>
<?php $csv_year = generateCSV($url, $c, $query, '$year', '_id', -1, 'Ano de publicação', 10000); ?>
<button  class="ui blue label" onclick="SaveAsFile('<?php echo $csv_year; ?>','ano.csv','text/plain;charset=utf-8')">Exportar todos os anos em csv</button>
      
<h3>Idioma</h3>       
<?php generateDataTable($url, $c, $query, '$language', 'count', -1, 'Idioma', 10); ?>
<?php $csv_language = generateCSV($url, $c, $query, '$language', 'count', -1, 'Idioma', 10000); ?>
<button  class="ui blue label" onclick="SaveAsFile('<?php echo $csv_language; ?>','idioma.csv','text/plain;charset=utf-8')">Exportar todos os idiomas em csv</button>
      
<h3>Internacionalização</h3>      
<?php generateDataTable($url, $c, $query, '$internacionalizacao', 'count', -1, 'Internacionalização', 10); ?>
<?php $csv_internacionalizacao = generateCSV($url, $c, $query, '$internacionalizacao', 'count', -1, 'Internacionalização', 10000); ?>
<button  class="ui blue label" onclick="SaveAsFile('<?php echo $csv_internacionalizacao; ?>','internacionalizacao.csv','text/plain;charset=utf-8')">Exportar em csv</button>
      
<h3>País de publicação</h3>
<?php generateDataTable($url, $c, $query, '$country', 'count', -1, 'País de publicação', 10); ?>
<?php $csv_country = generateCSV($url, $c, $query, '$country', 'count', -1, 'País de publicação', 10000); ?>
<button  class="ui blue label" onclick="SaveAsFile('<?php echo $csv_country; ?>','pais.csv','text/plain;charset=utf-8')">Exportar todos em csv</button>

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
