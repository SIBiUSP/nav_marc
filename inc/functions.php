<?php

/* Cria as consultas para o aggregate */

function generateFacetInit($c, $facet_name, $sort_name, $sort_value, $facet_display_name, $limit, $link)
{
    $aggregate_facet_init = array(
    array(
      '$unwind' => $facet_name,
    ),
    array(
      '$group' => array(
        '_id' => $facet_name,
        'count' => array('$sum' => 1),
        ),
    ),
    array(
      '$sort' => array($sort_name => $sort_value),
    ),
  );

    $facet_init = $c->aggregate($aggregate_facet_init);

    echo '<h3><a href="'.$link.'">'.$facet_display_name.'</a></h3>';
    echo '<div class="ui horizontal list">';
    $i = 0;
    foreach ($facet_init['result'] as $facets) {
        echo '<div class="item">
        <div class="content">
        <div class="ui labeled button" tabindex="0">
        <div class="header">
          <a href="result.php?'.substr($facet_name, 1).'='.$facets['_id'].'">'.$facets['_id'].'</a>
        </div>
        ('.$facets['count'].')
        </div></div>
        </div>';
        if (++$i > $limit) {
            break;
        }
    };
    echo '</div>';
};

/* Cria as consultas para o aggregate de Unidade USP*/

function generateUnidadeUSPInit($c, $facet_name, $sort_name, $sort_value, $facet_display_name, $limit, $link)
{
    $aggregate_facet_init = array(
    array(
      '$unwind' => $facet_name,
    ),
    array(
      '$group' => array(
        '_id' => $facet_name,
        'count' => array('$sum' => 1),
        ),
    ),
    array(
      '$sort' => array($sort_name => $sort_value),
    ),
  );

    $facet_init = $c->aggregate($aggregate_facet_init);

    echo '<h3><a href="'.$link.'">'.$facet_display_name.'</a></h3>';
    echo '<div class="ui relaxed horizontal list">
    <div class="ui four column doubling grid" style="padding:15px;">';
    $i = 0;
    foreach ($facet_init['result'] as $facets) {
        echo '<div class="column"><div class="item">
              <div class="ui fluid image">
                <a href="result.php?'.substr($facet_name, 1).'='.$facets['_id'].'">
                <div id="imagelogo" class="floating ui mini teal label" style="z-index:0;" data-title="'.trim($facets['_id']).'">
                '.$facets['count'].'
                </div>';
                $file = 'inc/images/logosusp/'.$facets['_id'].'.jpg';
                if (file_exists($file)) {
                echo '<img src="inc/images/logosusp/'.$facets['_id'].'.jpg"></a>';
                } else {
                  echo ''.$facets['_id'].'</a>';
              };
              echo'</div></div>
            </div>';
        if (++$i > $limit) {
            break;
        }
    };
    echo '</div></div>';



};




/* Function to generate facets */
function generateFacet($url, $c, $query, $facet_name, $sort_name, $sort_value, $facet_display_name, $limit)
{
    $aggregate_facet = array(
    array(
      '$match' => $query,
    ),
    array(
      '$unwind' => $facet_name,
    ),
    array(
      '$group' => array(
        '_id' => $facet_name,
        'count' => array('$sum' => 1),
        ),
    ),
    array(
      '$sort' => array($sort_name => $sort_value),
    ),
  );
    $options = array('allowDiskUse' => true);
    $facet = $c->aggregate($aggregate_facet, $options);

    echo '<div class="item">';
    echo '<a class="active title"><i class="dropdown icon"></i>'.$facet_display_name.'</a>';
    echo '<div class="content">';
    echo '<div class="ui list">';
    $i = 0;
    foreach ($facet['result'] as $facets) {
        echo '<div class="item">';
        echo '<a href="'.$url.'&'.substr($facet_name, 1).'='.$facets['_id'].'">'.$facets['_id'].'</a><span> ('.$facets['count'].')</span>';
        echo '</div>';
        if (++$i > $limit) {
            break;
        }
    };
    echo   '</div>
      </div>
  </div>';
};


/* Pegar o tipo de material */
function get_type($material_type){
  switch ($material_type) {
  case "ARTIGO DE PERIODICO":
      return "article-journal";
      break;
  case "PARTE DE MONOGRAFIA/LIVRO":
      return "chapter";
      break;
  case "APRESENTACAO SONORA/CENICA/ENTREVISTA":
      return "interview";
      break;
  case "TRABALHO DE EVENTO-RESUMO":
      return "paper-conference";
      break;
  case "TEXTO NA WEB":
      return "post-weblog";
      break;
  }
}

/* Últimos cadastramentos */
function get_last_records($c,$number){

  $last_records = $c->find()->sort(array('_id'=>-1))->limit($number);
  $file='';
  echo '<h3>Últimos registros</h3>';
  echo '<div class="ui divided items">';
  foreach ($last_records as $r){
    #print_r($r);
    echo '<div class="item">
            <div class="ui tiny image">';
      if (!empty($r['unidadeUSP'])) {
        $file = 'inc/images/logosusp/'.$r['unidadeUSP'][0].'.jpg';
      }
      if (file_exists($file)) {
            echo '<img src="'.$file.'"></a>';
        } else {
            #echo ''.$r['unidadeUSP'].'</a>';
      };
    echo '</div>';
    echo '<div class="content">';
    if (!empty($r['title'])){
      echo '<a class="header" href="single.php?_id='.$r['_id'].'">'.$r['title'].' ('.$r['year'].')</a>';
    };
    echo '<div class="extra">';
    if (!empty($r['authors'])) {
      foreach ($r['authors'] as $autores) {
        echo '<div class="ui label" style="color:black;"><i class="user icon"></i><a href="result.php?authors='.$autores.'">'.$autores.'</a></div>';
      }
    };
    echo '</div></div>';
    echo '</div>';
  }
    echo '</div>';
}


/* Function to generate facets */
function generateDataGraph($url, $c, $query, $facet_name, $sort_name, $sort_value, $facet_display_name, $limit)
{
    $aggregate_facet = array(
    array(
      '$match' => $query,
    ),
    array(
      '$unwind' => $facet_name,
    ),
    array(
      '$group' => array(
        '_id' => $facet_name,
        'count' => array('$sum' => 1),
        ),
    ),
    array(
      '$sort' => array($sort_name => $sort_value),
    ),
  );
    $options = array('allowDiskUse' => true);
    $facet = $c->aggregate($aggregate_facet, $options);


    $i = 0;
    $data_array= array();
    foreach ($facet['result'] as $facets) {
        array_push($data_array,'{"label":"'.$facets['_id'].'","value":'.$facets['count'].'}');
        if (++$i > $limit) {
            break;
        }
    };
    $comma_separated = implode(",", $data_array);
    return $comma_separated;

};

/* Function to generate Tables */
function generateDataTable($url, $c, $query, $facet_name, $sort_name, $sort_value, $facet_display_name, $limit)
{
    $aggregate_facet = array(
    array(
      '$match' => $query,
    ),
    array(
      '$unwind' => $facet_name,
    ),
    array(
      '$group' => array(
        '_id' => $facet_name,
        'count' => array('$sum' => 1),
        ),
    ),
    array(
      '$sort' => array($sort_name => $sort_value),
    ),
  );
    $options = array('allowDiskUse' => true);
    $facet = $c->aggregate($aggregate_facet, $options);



echo "<table class=\"ui celled table\">
  <thead>
    <tr>
      <th>".$facet_display_name."</th>
      <th>Quantidade</th>
    </tr>
  </thead>
  <tbody>";


    foreach ($facet['result'] as $facets) {
        echo "<tr>
              <td>".$facets['_id']."</td>
              <td>".$facets['count']."</td>
            </tr>";
    };

  echo"</tbody>
    </table>";


};

/* Function to generate Graph Bar */
function generateDataGraphBar($url, $c, $query, $facet_name, $sort_name, $sort_value, $facet_display_name, $limit)
{
    $aggregate_facet = array(
    array(
      '$match' => $query,
    ),
    array(
      '$unwind' => $facet_name,
    ),
    array(
      '$group' => array(
        '_id' => $facet_name,
        'count' => array('$sum' => 1),
        ),
    ),
    array(
      '$sort' => array($sort_name => $sort_value),
    ),
  );
    $options = array('allowDiskUse' => true);
    $facet = $c->aggregate($aggregate_facet, $options);


    $i = 0;
    $data_array= array();
    foreach ($facet['result'] as $facets) {
        array_push($data_array,'{"x":"'.$facets['_id'].'","y":'.$facets['count'].'}');
        if (++$i > $limit) {
            break;
        }
    };
    $comma_separated = implode(",", $data_array);
    return $comma_separated;

};

/* Conta os registros */

function countRecords($c) {
  $query_count = json_decode('[{"$group":{"_id":null,"count":{"$sum": 1}}}]');
  $total_count = $c->aggregate($query_count);
  $total = $total_count['result'][0]['count'];
  return $total;
}

?>
