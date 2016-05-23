<?php
  $tpTitle="BDPI USP - Detalhes do registro";

  include 'inc/config.php';
  include 'inc/header.php';
  include_once 'inc/functions.php';

  if (empty($_SESSION["citation_style"])) {
    $_SESSION["citation_style"]="abnt";
  }
  if (isset($_POST["citation_style"])) {
    $_SESSION["citation_style"] = $_POST['citation_style'];
  }


  /* Pegar a URL atual */
  if (strpos($_SERVER['REQUEST_URI'], '?') !== false) {
      $url = "//{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
  } else {
      $url = "//{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}?";
  }

  /* Citeproc-PHP*/
  include 'inc/citeproc-php/CiteProc.php';
  $csl = file_get_contents('inc/citeproc-php/style/'.$_SESSION["citation_style"].'.csl');
  $lang = "br";
  $citeproc = new citeproc($csl,$lang);
  $mode = "reference";

  #Consultas
  $query = json_decode('[{"$match":{"_id":"'.$_GET["_id"].'"}},{"$lookup":{"from": "producao_bdpi", "localField": "_id", "foreignField": "_id", "as": "files"}}]');
  $cursor = $c->aggregate($query);
?>
</head>
<body>
<div class="ui container">
  <div class="ui main two column stackable grid">
    <div class="four wide column">
      <h3> Escolha o estilo da Citação:</h3>
      <div class="ui compact menu">
        <form method="post" action="http://<?php echo $url; ?>">
          <button  type="submit" name="citation_style" class="ui icon button" value="apa">APA</button>
        </form>
        <form method="post" action="http://<?php echo $url; ?>">
          <button type="submit" name="citation_style" class="ui icon button" value="abnt">ABNT</button>
        </form>
        <form method="post" action="http://<?php echo $url; ?>">
          <button type="submit" name="citation_style" class="ui icon button" value="nlm">NLM</button>
        </form>
        <form method="post" action="http://<?php echo $url; ?>">
          <button type="submit" name="citation_style" class="ui icon button" value="vancouver">Vancouver</button>
        </form>
      </div>

      <div class="extra" style="color:black;">
        <h4>Como citar (<?php echo strtoupper($_SESSION["citation_style"]); ?>)</h4>
        <?php
        $type = get_type($cursor["result"][0]["type"]);
        $author_array = array();
        foreach ($cursor["result"][0]["authors"] as $autor_citation){

          $array_authors = explode(',', $autor_citation);
          $author_array[] = '{"family":"'.$array_authors[0].'","given":"'.$array_authors[1].'"}';
        };
        $authors = implode(",",$author_array);

        if (!empty($cursor["result"][0]["ispartof"])) {
          $container = '"container-title": "'.$cursor["result"][0]["ispartof"].'",';
        } else {
          $container = "";
        };
        if (!empty($cursor["result"][0]["doi"])) {
          $doi = '"DOI": "'.$cursor["result"][0]["doi"][0].'",';
        } else {
          $doi = "";
        };

        if (!empty($cursor["result"][0]["url"])) {
          $url = '"URL": "'.$cursor["result"][0]["url"][0].'",';
        } else {
          $url = "";
        };

        if (!empty($cursor["result"][0]["publisher"])) {
          $publisher = '"publisher": "'.$cursor["result"][0]["publisher"].'",';
        } else {
          $publisher = "";
        };

        if (!empty($cursor["result"][0]["publisher-place"])) {
          $publisher_place = '"publisher-place": "'.$cursor["result"][0]["publisher-place"].'",';
        } else {
          $publisher_place = "";
        };

        $volume = "";
        $issue = "";
        $page_ispartof = "";

        if (!empty($cursor["result"][0]["ispartof_data"])) {
          foreach ($cursor["result"][0]["ispartof_data"] as $ispartof_data) {
            if (strpos($ispartof_data, 'v.') !== false) {
              $volume = '"volume": "'.str_replace("v.","",$ispartof_data).'",';
            } elseif (strpos($ispartof_data, 'n.') !== false) {
              $issue = '"issue": "'.str_replace("n.","",$ispartof_data).'",';
            } elseif (strpos($ispartof_data, 'p.') !== false) {
              $page_ispartof = '"page": "'.str_replace("p.","",$ispartof_data).'",';
            }
          }
        }

        $data = json_decode('{
                    "title": "'.$cursor["result"][0]["title"].'",
                    "type": "'.$type.'",
                    '.$container.'
                    '.$doi.'
                    '.$url.'
                    '.$publisher.'
                    '.$publisher_place.'
                    '.$volume.'
                    '.$issue.'
                    '.$page_ispartof.'
                    "issued": {
                        "date-parts": [
                            [
                                "'.$cursor["result"][0]["year"].'"
                            ]
                        ]
                    },
                    "author": [
                        '.$authors.'
                    ]
                }');
        $output = $citeproc->render($data, $mode);
        print_r($output)
        ?>
      </div>
      <br/><br/><br/><h3>Ver registro no DEDALUS</h3>
      <a class="ui blue label" href="http://dedalus.usp.br/F/?func=direct&doc_number=<?php echo $cursor["result"][0]["_id"];?>">Ver no Dedalus</a>

      <h3>Exportar</h3>
    </div>
    <div class="ten wide column">
      <h2 class="ui center aligned icon header">
        <i class="circular file icon"></i>
        Detalhes do registro / <?php echo ''.$cursor["result"][0]["type"].''; ?>
      </h2>
      <div class="ui top attached tabular menu">
        <a class="item active" data-tab="first">Visualização</a>
        <a class="item" data-tab="second">Registro Completo</a>
      </div>
      <div class="ui bottom attached tab segment active" data-tab="first">
        <h2><?php echo $cursor["result"][0]['title'];?> (<?php echo $cursor["result"][0]['year']; ?>)</h2>
        <!--List authors -->
        <div class="ui middle aligned selection list">
          <?php if (!empty($cursor["result"][0]['authors'])): ?>
            <h4>Autor(es):</h4>
            <?php foreach ($cursor["result"][0]['authors'] as $autores): ?>
              <div class="item">
                <i class="user icon"></i>
                <div class="content">
                  <a href="result.php?autor=<?php echo $autores;?>"><?php echo $autores;?></a>
                  </div>
                </div>
            <?php endforeach;?>
          <?php endif; ?>
        </div>


        <!--Authors USP -->
        <div class="ui middle aligned selection list">
          <?php if (!empty($cursor["result"][0]['authorUSP'])): ?>
            <h4>Autor(es) USP:</h4>
            <?php foreach ($cursor["result"][0]['authorUSP'] as $autoresUSP): ?>
              <div class="item">
                <i class="user icon"></i>
                <div class="content">
                  <a href="result.php?authorUSP=<?php echo $autoresUSP;?>"><?php echo $autoresUSP;?></a>
                  </div>
                </div>
            <?php endforeach;?>
          <?php endif; ?>
        </div>

        <!--Unidades USP -->
        <div class="ui middle aligned selection list">
          <?php if (!empty($cursor["result"][0]['unidadeUSP'])): ?>
            <h4>Unidades USP:</h4>
            <?php foreach ($cursor["result"][0]['unidadeUSP'] as $unidadeUSP): ?>
              <div class="item">
                <i class="user icon"></i>
                <div class="content">
                  <a href="result.php?unidadeUSP=<?php echo $unidadeUSP;?>"><?php echo $unidadeUSP;?></a>
                  </div>
                </div>
            <?php endforeach;?>
          <?php endif; ?>
        </div>
        <!--Assuntos -->
        <div class="ui middle aligned selection list">
          <?php if (!empty($cursor["result"][0]['subject'])): ?>
            <h4>Assuntos:</h4>
            <?php foreach ($cursor["result"][0]['subject'] as $subject): ?>
              <div class="item">
                <i class="user icon"></i>
                <div class="content">
                  <a href="result.php?subject=<?php echo $subject;?>"><?php echo $subject;?></a>
                  </div>
                </div>
            <?php endforeach;?>
          <?php endif; ?>
        </div>
        <!-- Idioma -->
        <div class="ui middle aligned selection list">
          <?php if (!empty($cursor["result"][0]['language'])): ?>
            <h4>Idioma:</h4>
            <?php foreach ($cursor["result"][0]['language'] as $language): ?>
              <div class="item">
                <i class="user icon"></i>
                <div class="content">
                  <a href="result.php?language=<?php echo $language;?>"><?php echo $language;?></a>
                  </div>
                </div>
            <?php endforeach;?>
          <?php endif; ?>
        </div>
        <!-- Imprenta -->
        <div class="ui middle aligned selection list">
          <?php if (!empty($cursor["result"][0]['publisher-place'])): ?>
            <h4>Imprenta:</h4>
              <div class="item">
                <i class="user icon"></i>
                <div class="content">
                  Local: <a href="result.php?publisher-place=<?php echo $cursor["result"][0]['publisher-place'];?>"><?php echo $cursor["result"][0]['publisher-place'];?></a>
                  </div>
                </div>
                <div class="item">
                  <i class="user icon"></i>
                  <div class="content">
                    Data de publicação: <a href="result.php?year=<?php echo $cursor["result"][0]['year'];?>"><?php echo $cursor["result"][0]['year'];?></a>
                    </div>
                  </div>
          <?php endif; ?>
        </div>

        <!-- Fonte -->
        <div class="ui middle aligned selection list">
          <?php if (!empty($cursor["result"][0]['ispartof'])): ?>
            <h4>Fonte:</h4>
              <div class="item">
                <i class="user icon"></i>
                <div class="content">
                  Título: <a href="result.php?ispartof=<?php echo $cursor["result"][0]['ispartof'];?>"><?php echo $cursor["result"][0]['ispartof'];?></a><br/>
                  ISSN: <a href="result.php?issn_part=<?php echo $cursor["result"][0]['issn_part'][0];?>"><?php echo $cursor["result"][0]['issn_part'][0];?></a><br/>
                  Volume: <?php echo $cursor["result"][0]['ispartof_data'][0];?><br/>
                  Número: <?php echo $cursor["result"][0]['ispartof_data'][1];?><br/>
                  Paginação: <?php echo $cursor["result"][0]['ispartof_data'][2];?><br/>
                  DOI: <a href="http://dx.doi.org/<?php echo $cursor["result"][0]['doi'][0];?>"><?php echo $cursor["result"][0]['doi'][0];?></a><br/>
                  </div>
                </div>
          <?php endif; ?>
        </div>

        <?php if (!empty($cursor["result"][0]['doi'])): ?>
          <br/><br/>
          <a href="http://dx.doi.org/<?php echo $cursor["result"][0]['doi'][0];?>">
          <div class="ui right floated primary button">
            Acesso online
            <i class="right chevron icon"></i>
          </div></a>
          <object height="50" data="http://api.elsevier.com/content/abstract/citation-count?doi=<?php echo $cursor["result"][0]['doi'][0];?>&apiKey=c7af0f4beab764ecf68568961c2a21ea&httpAccept=text/html"></object>
        <?php endif; ?>

      </div>
      <div class="ui bottom attached tab segment" data-tab="second">
        <table class="ui celled table">
          <thead>
            <tr>
              <th>Campo</th>
              <th>Ind. 1</th>
              <th>Ind. 2</th>
              <th>Subcampo</th>
              <th>Conteúdo</th>
            </tr>
          </thead>
        <tbody>
        <?php foreach ($cursor["result"][0]["record"] as $fields){
          echo '<tr>';
          echo '<td>'.$fields[0].'';
          echo '<td>'.$fields[1].'';
          echo '<td>'.$fields[2].'';
          echo '<td>'.$fields[3].'';
          echo '<td>'.$fields[4].'';
          echo '</tr>';
          if (!empty($fields[5])){
            echo '<tr>';
            echo '<td>'.$fields[0].'';
            echo '<td>'.$fields[1].'';
            echo '<td>'.$fields[2].'';
            echo '<td>'.$fields[5].'';
            echo '<td>'.$fields[6].'';
            echo '</tr>';
          }
          if (!empty($fields[7])){
            echo '<tr>';
            echo '<td>'.$fields[0].'';
            echo '<td>'.$fields[1].'';
            echo '<td>'.$fields[2].'';
            echo '<td>'.$fields[7].'';
            echo '<td>'.$fields[8].'';
            echo '</tr>';
          }
          if (!empty($fields[9])){
            echo '<tr>';
            echo '<td>'.$fields[0].'';
            echo '<td>'.$fields[1].'';
            echo '<td>'.$fields[2].'';
            echo '<td>'.$fields[9].'';
            echo '<td>'.$fields[10].'';
            echo '</tr>';
          }
        if (!empty($fields[11])){
          echo '<tr>';
          echo '<td>'.$fields[0].'';
          echo '<td>'.$fields[1].'';
          echo '<td>'.$fields[2].'';
          echo '<td>'.$fields[11].'';
          echo '<td>'.$fields[12].'';
          echo '</tr>';
        }
        if (!empty($fields[13])){
          echo '<tr>';
          echo '<td>'.$fields[0].'';
          echo '<td>'.$fields[1].'';
          echo '<td>'.$fields[2].'';
          echo '<td>'.$fields[13].'';
          echo '<td>'.$fields[14].'';
          echo '</tr>';
        }
        if (!empty($fields[15])){
          echo '<tr>';
          echo '<td>'.$fields[0].'';
          echo '<td>'.$fields[1].'';
          echo '<td>'.$fields[2].'';
          echo '<td>'.$fields[15].'';
          echo '<td>'.$fields[16].'';
          echo '</tr>';
        }
        if (!empty($fields[17])){
          echo '<tr>';
          echo '<td>'.$fields[0].'';
          echo '<td>'.$fields[1].'';
          echo '<td>'.$fields[2].'';
          echo '<td>'.$fields[17].'';
          echo '<td>'.$fields[18].'';
          echo '</tr>';
        }
        if (!empty($fields[19])){
          echo '<tr>';
          echo '<td>'.$fields[0].'';
          echo '<td>'.$fields[1].'';
          echo '<td>'.$fields[2].'';
          echo '<td>'.$fields[19].'';
          echo '<td>'.$fields[20].'';
          echo '</tr>';
        }
        if (!empty($fields[21])){
          echo '<tr>';
          echo '<td>'.$fields[0].'';
          echo '<td>'.$fields[1].'';
          echo '<td>'.$fields[2].'';
          echo '<td>'.$fields[21].'';
          echo '<td>'.$fields[22].'';
          echo '</tr>';
        }
        if (!empty($fields[23])){
          echo '<tr>';
          echo '<td>'.$fields[0].'';
          echo '<td>'.$fields[1].'';
          echo '<td>'.$fields[2].'';
          echo '<td>'.$fields[23].'';
          echo '<td>'.$fields[24].'';
          echo '</tr>';
        }
        if (!empty($fields[25])){
          echo '<tr>';
          echo '<td>'.$fields[0].'';
          echo '<td>'.$fields[1].'';
          echo '<td>'.$fields[2].'';
          echo '<td>'.$fields[25].'';
          echo '<td>'.$fields[26].'';
          echo '</tr>';
        }
      };
        ?>
        </tbody>
      </table>
      </div>
    </div>
  </div>
</div>
<?php
  include 'inc/footer.php';
?>
<script>
$('.menu .item')
  .tab()
;
</script>
</body>
</html>
