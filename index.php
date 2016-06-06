<?php
$tpTitle = 'BDPI USP - Biblioteca Digital da Produção Intelectual da Universidade de São Paulo';
?>
<?php
  include 'inc/config.php';
  include 'inc/header.php';
  include_once 'inc/functions.php';
?>
<div class="ui main container">
<div class="ui two column stackable grid">
  <div class="ten wide column">
    <p>A Biblioteca Digital da Produção Intelectual da Universidade de São Paulo (BDPI)
        é um sistema de gestão e disseminação da produção científica,
        acadêmica, técnica e artística gerada pelas pesquisas desenvolvidas na USP.</p>
    <div class="ui vertical stripe segment" id="search">
      <div class="ui main container">
        <h3 class="ui header">Buscar</h3>
        <form class="ui form" role="form" action="result.php" method="get">
          <div class="inline fields">
            <div class="eight wide field">
              <input name="q" type="text" placeholder="Digite os termos de busca">
            </div>
            <div class="six wide field">
              <select class="ui fluid dropdown" name="category">
                <option value="buscaindice">Título, autores e assuntos</option>
              </select>
              </div>
            <button type="submit" id="s" class="ui large button">Buscar</button>
        </div>
        </form>
        </div>
    </div>
    <?php $total_registros = countRecords($c); ?>
    <div class="ui vertical stripe segment">
    <div class="ui text container">
    <h3 class="ui header">Alguns números</h3><br/><br/>
    <div class="ui one statistics">
      <div class="statistic">
        <div class="value">
          <i class="file icon"></i> <?php echo $total_registros; ?>
        </div>
        <div class="label">
          Quantidade de registros
        </div>
      </div>
    </div>
    </div>
    </div>
    <?php get_last_records($c, 15); ?>
  </div>
  <div class="six wide column">
    <?php
    if (!empty($m)) {
          generateUnidadeUSPInit($c, '$unidadeUSPtrabalhos', '_id', 1, 'Unidades USP', 100, '#');
    };
    ?>
  </div>
</div>
</div>
<?php
  include 'inc/footer.php';
?>
<script>
$('.activating.element')
  .popup()
;
</script>
<script>
$(document).ready(function()
{
  $('div#logosusp').attr("style", "z-index:0;");
});
</script>

</body>
</html>
