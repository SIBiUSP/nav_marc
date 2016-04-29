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
    <div class="overlay">
      <div class="ui labeled icon vertical menu">
        <a class="item" href="https://twitter.com/home?status=<?php echo 'http://'.$_SERVER['HTTP_HOST'].''.$_SERVER['REQUEST_URI'].''; ?>"><i class="twitter icon"></i> Tweet</a>
        <a class="item" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo 'http://'.$_SERVER['HTTP_HOST'].''.$_SERVER['REQUEST_URI'].''; ?>"><i class="facebook icon"></i> Share</a>
      </div>
    </div>
    <p>A Biblioteca Digital da Produção Intelectual da Universidade de São Paulo (BDPI) é um sistema de gestão e disseminação da produção científica, acadêmica, técnica e artística gerada pelas pesquisas desenvolvidas na USP.</p>
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
    <?php get_last_records($c,15); ?>
  </div>
  <div class="six wide column">
    <?php
      if (!empty($m)) {
          generateUnidadeUSPInit($c, '$unidadeUSPtrabalhos', '_id', 1, 'Unidade USP', 100, '#');
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
