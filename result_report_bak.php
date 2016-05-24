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

<style type="text/css">
    .slice text {
        font-size: 8pt;
        font-family: Arial;
    }
</style>

</head>
<body>
  <div class="ui main container">
    <h3>Relatório com os seguintes parâmetros:
    <?php foreach ($_GET as $filters): ?>
      <?php echo $filters;?>
    <?php endforeach;?>
    </h3><br/><br/>
    <div class="ui equal width grid">
      <div class="column">
      <h3>Tipo de publicação</h3>
      <?php $type_mat = generateDataGraph($url, $c, $query, '$type', 'count', -1, 'Tipo de publicação', 50); ?>
      <div id="chart"></div>
      <script type="text/javascript">
      var w = 400;
      var h = 400;
      var r = h/2;
      var color = d3.scale.category20c();

      var data = [<?= $type_mat; ?>];


      var vis = d3.select('#chart').append("svg:svg").data([data]).attr("width", w).attr("height", h).append("svg:g").attr("transform", "translate(" + r + "," + r + ")");
      var pie = d3.layout.pie().value(function(d){return d.value;});

      // declare an arc generator function
      var arc = d3.svg.arc().outerRadius(r);

      // select paths, use arc generator to draw
      var arcs = vis.selectAll("g.slice").data(pie).enter().append("svg:g").attr("class", "slice");
      arcs.append("svg:path")
          .attr("fill", function(d, i){
              return color(i);
          })
          .attr("d", function (d) {
              // log the result of the arc generator to show how cool it is :)
              console.log(arc(d));
              return arc(d);
          });

      // add the text
      arcs.append("svg:text").attr("transform", function(d){
      			d.innerRadius = 0;
      			d.outerRadius = r;
          return "translate(" + arc.centroid(d) + ")";}).attr("text-anchor", "middle").text( function(d, i) {
          return data[i].label;}
      		);
     </script>
    </div>

    <div class="column">
      <h3>Unidade USP - Participações</h3>
      <?php $unidadeUSP_part = generateDataGraph($url, $c, $query, '$unidadeUSP', 'count', -1, 'Unidade USP - Participações', 10000); ?>
      <div id="chart2"></div>
      <script type="text/javascript">
      var w = 400;
      var h = 400;
      var r = h/2;
      var color = d3.scale.category20c();

      var data = [<?= $unidadeUSP_part; ?>];


      var vis = d3.select('#chart2').append("svg:svg").data([data]).attr("width", w).attr("height", h).append("svg:g").attr("transform", "translate(" + r + "," + r + ")");
      var pie = d3.layout.pie().value(function(d){return d.value;});

      // declare an arc generator function
      var arc = d3.svg.arc().outerRadius(r);

      // select paths, use arc generator to draw
      var arcs = vis.selectAll("g.slice").data(pie).enter().append("svg:g").attr("class", "slice");
      arcs.append("svg:path")
          .attr("fill", function(d, i){
              return color(i);
          })
          .attr("d", function (d) {
              // log the result of the arc generator to show how cool it is :)
              console.log(arc(d));
              return arc(d);
          });

      // add the text
      arcs.append("svg:text").attr("transform", function(d){
            d.innerRadius = 0;
            d.outerRadius = r;
          return "translate(" + arc.centroid(d) + ")";}).attr("text-anchor", "middle").text( function(d, i) {
          return data[i].label;}
          );
     </script>
    </div>
    </div>
    <div class="ui equal width grid">
      <div class="column">
        <h3>Unidade USP - Trabalhos</h3>
        <?php $unidadeUSP_part = generateDataGraph($url, $c, $query, '$unidadeUSPtrabalhos', 'count', -1, 'Unidade USP - Trabalhos', 10000); ?>
        <div id="chart3"></div>
        <script type="text/javascript">
        var w = 400;
        var h = 400;
        var r = h/2;
        var color = d3.scale.category20c();

        var data = [<?= $unidadeUSP_part; ?>];


        var vis = d3.select('#chart3').append("svg:svg").data([data]).attr("width", w).attr("height", h).append("svg:g").attr("transform", "translate(" + r + "," + r + ")");
        var pie = d3.layout.pie().value(function(d){return d.value;});

        // declare an arc generator function
        var arc = d3.svg.arc().outerRadius(r);

        // select paths, use arc generator to draw
        var arcs = vis.selectAll("g.slice").data(pie).enter().append("svg:g").attr("class", "slice");
        arcs.append("svg:path")
            .attr("fill", function(d, i){
                return color(i);
            })
            .attr("d", function (d) {
                // log the result of the arc generator to show how cool it is :)
                console.log(arc(d));
                return arc(d);
            });

        // add the text
        arcs.append("svg:text").attr("transform", function(d){
              d.innerRadius = 0;
              d.outerRadius = r;
            return "translate(" + arc.centroid(d) + ")";}).attr("text-anchor", "middle").text( function(d, i) {
            return data[i].label;}
            );
       </script>
      </div>
      <div class="column">
        <h3>Departamento - Participações</h3>
        <?php $unidadeUSP_part = generateDataGraph($url, $c, $query, '$departamento', 'count', -1, 'Departamento - Participações', 10000); ?>
        <div id="chart4"></div>
        <script type="text/javascript">
        var w = 400;
        var h = 400;
        var r = h/2;
        var color = d3.scale.category20c();

        var data = [<?= $unidadeUSP_part; ?>];


        var vis = d3.select('#chart4').append("svg:svg").data([data]).attr("width", w).attr("height", h).append("svg:g").attr("transform", "translate(" + r + "," + r + ")");
        var pie = d3.layout.pie().value(function(d){return d.value;});

        // declare an arc generator function
        var arc = d3.svg.arc().outerRadius(r);

        // select paths, use arc generator to draw
        var arcs = vis.selectAll("g.slice").data(pie).enter().append("svg:g").attr("class", "slice");
        arcs.append("svg:path")
            .attr("fill", function(d, i){
                return color(i);
            })
            .attr("d", function (d) {
                // log the result of the arc generator to show how cool it is :)
                console.log(arc(d));
                return arc(d);
            });

        // add the text
        arcs.append("svg:text").attr("transform", function(d){
              d.innerRadius = 0;
              d.outerRadius = r;
            return "translate(" + arc.centroid(d) + ")";}).attr("text-anchor", "middle").text( function(d, i) {
            return data[i].label;}
            );
       </script>
      </div>
    </div>

  <div class="ui main two column stackable grid">
    <div class="four wide column">
      <div class="ui fluid vertical accordion menu">
        <div class="item">
          <a class="active title">
            <i class="dropdown icon"></i>
            Filtros ativos
          </a>
          <div class="active content">
            <div class="ui form">
              <div class="grouped fields">
                <?php foreach ($_GET as $filters): ?>
                    <div class="field">
                    <div class="ui checkbox">
                      <input type="checkbox" name="<?php echo $filters;?>">
                    <label><?php echo $filters;?></label>
                    </div>
                </div>
                <?php endforeach;?>
              </div>
            </div>
          </div>
        </div>
      <?php
      /* Gerar facetas */
        generateFacet($url, $c, $query, '$type', 'count', -1, 'Tipo de publicação', 50);
        generateFacet($url, $c, $query, '$unidadeUSP', 'count', -1, 'Unidade USP - Participações', 100);
        generateFacet($url, $c, $query, '$unidadeUSPtrabalhos', 'count', -1, 'Unidade USP - Trabalhos', 100);
        generateFacet($url, $c, $query, '$departamento', 'count', -1, 'Departamento - Participações', 50);
        generateFacet($url, $c, $query, '$departamentotrabalhos', 'count', -1, 'Departamento - Trabalhos', 50);
        generateFacet($url, $c, $query, '$subject', 'count', -1, 'Assuntos', 50);
        if (strpos($_SERVER['REQUEST_URI'], 'unidadeUSPtrabalhos') !== false) {
            generateFacet($url, $c, $query, '$authors', 'count', -1, 'Autores', 50);
        }
        generateFacet($url, $c, $query, '$colab', 'count', -1, 'País dos autores externos à USP', 50);
        generateFacet($url, $c, $query, '$authorUSP', 'count', -1, 'Autores USP', 50);
        generateFacet($url, $c, $query, '$codpesbusca', 'count', -1, 'Número USP', 50);
        generateFacet($url, $c, $query, '$codpes', 'count', -1, 'Número USP / Unidade', 50);
        generateFacet($url, $c, $query, '$ispartof', 'count', -1, 'É parte de', 50);
        generateFacet($url, $c, $query, '$issn_part', 'count', -1, 'ISSN do todo', 50);
        generateFacet($url, $c, $query, '$evento', 'count', -1, 'Nome do evento', 50);
        generateFacet($url, $c, $query, '$year', '_id', -1, 'Ano de publicação', 50);
        generateFacet($url, $c, $query, '$language', 'count', -1, 'Idioma', 50);
        generateFacet($url, $c, $query, '$internacionalizacao', 'count', -1, 'Internacionalização', 50);
        generateFacet($url, $c, $query, '$country', 'count', -1, 'País de publicação', 50);
      ?>
    </div>
  </div>
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
