<!DOCTYPE html>
<html lang="pt_BR">
<?php session_start(); ?>
<head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8"></meta>
  <meta name="viewport" content="width=device-width, initial-scale=1"></meta>
  <!-- CSS -->
  <link rel="stylesheet" href="<?php echo "http://" . $_SERVER['SERVER_NAME'] ."/".$SERVER_DIRECTORY."/"; ?>inc/css/vcusp-theme.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="http://bdpife2.sibi.usp.br/vocab21/common/bootstrap/js/bootstrap.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo "http://" . $_SERVER['SERVER_NAME'] ."/".$SERVER_DIRECTORY."/"; ?>inc/semantic-ui/semantic.min.css">
<script src="<?php echo "http://" . $_SERVER['SERVER_NAME'] ."/".$SERVER_DIRECTORY."/"; ?>inc/semantic-ui/semantic.min.js"></script>
<meta property="og:url"           content="" /></meta>
<meta property="og:type"          content="website" /></meta>
<meta property="og:title"         content="<?php echo $tpTitle ?>" /></meta>
<meta property="og:description"   content="" /></meta>
<meta property="og:image"         content="" /></meta>
<link type="image/x-icon" href="<?php echo "http://" . $_SERVER['SERVER_NAME'] ."/".$SERVER_DIRECTORY."/"; ?>inc/images/faviconUSP.ico" rel="icon" />
<link type="image/x-icon" href="<?php echo "http://" . $_SERVER['SERVER_NAME'] ."/".$SERVER_DIRECTORY."/"; ?>inc/images/faviconUSP.ico" rel="shortcut icon" />

<title><?php echo $tpTitle ?></title>

<script src="http://semantic-ui.com/dist/semantic.min.js"></script>

  <style type="text/css">

  .main.container {
    margin-top: 2em;
  }

  .main.menu {
    margin-top: 4em;
    margin-bottom: 2em;
    border-radius: 0;
    border: none;
    box-shadow: none;
    transition:
      box-shadow 0.5s ease,
      padding 0.5s ease
    ;
  }
  .main.menu .item img.logo {
    margin-right: 1.5em;
  }

.ui.inverted.menu .item,
.ui.inverted.menu .item>a:not(.ui) {
  color: #333;
}

  .ui.menu .item {
    padding: 0.65em;
  }

  .text.container {
    margin-top: 2em;
  }

  .main.menu.fixed {
    border: 1px solid #DDD;
    box-shadow: 0px 3px 5px rgba(0, 0, 0, 0.2);
  }
  .overlay.fixed .menu {
    left: 1150px;
  }

  .ui.menu.fixed {
    width: 70%;
  }

  .text.container .left.floated.image {
    margin: 2em 2em 2em -4em;
  }
  .text.container .right.floated.image {
    margin: 2em -4em 2em 2em;
  }

  .ui.footer.segment {
    margin: 5em 0em 0em;
    padding: 5em 0em;
  }
  </style>

</head>
<body>
  <!--uspbarra - ínicio -->
   <div id="uspbarra" style="background-color:transparent;border-style:none">
     <div class="uspLogo"  style="background-color:transparent;border-style:none">
       <img class="img-responsive" onclick="javascript:window.open('http://www.usp.br');" alt="USP" style="cursor:pointer;position: absolute;bottom: 0px;" src="inc/images/Logo_usp_composto.jpg" />
     </div>
     <div class="panel-group" id="accordion"  style="background-color:transparent;border-style:none">
       <div class="panel" style="background-color:transparent;border-style:none">
                                      <div id="collapseThree" class="panel-collapse collapse" style="background-color:transparent">
                                                         <div class="panel-body usppanel" style="background-color:#b3b3bc">
                                                             <div class="row" style="background-color:transparent">
                                                                 <div class="col-md-3 text-center">
                                                                     <a href=http://www.usp.br/sibi/><img src="http://www.producao.usp.br/a/barrausp/images/sibi.png" title="SIBi - Sistema Integrado de Bibliotecas da USP" width=150 height=69 border=0 /></a>
                                                                     <div class="uspmenu_top_usp">
                                                                         <ul>
                                                                             <li><a href="http://www.producao.usp.br/a/barrausp/barra/creditos.html" target=_blank>Créditos</a></li>
                                                                             <li><a href="http://www.producao.usp.br/a/barrausp/barra/contato.html" target=_blank>Fale com o SIBi</a></li>
                                                                             <div><img src="http://www.producao.usp.br/a/barrausp/images/spacer.gif" width=10 height=10 /></div>
                                                                             <div class="panel-heading">PORTAL DE BUSCA INTEGRADA</div>
                                                                             <div class="panel-body">Um único ponto de acesso a todos os conteúdos informacionais disponíveis para a comunidade USP.</div>
                                                                             <br />
                                                                             <form class="form-inline" role="form" method="get" name="busca" action="http://www.buscaintegrada.usp.br/primo_library/libweb/action/search.do" onsubmit="if (document.getElementById(\'mySearch\').value==\'Busca geral...\'||document.getElementById(\'mySearch\').value==\'\'){alert(\'Preencha o campo de busca!\ return false;} else {return true;}" >
                                                                                 <input type hidden name="dscnt" value="0">
                                                                                 <input type hidden name="frbg" value="">
                                                                                 <input type hidden name="scp.scps" value=\'scope:("USP"),primo_central_multiple_fe\'>
                                                                                 <input type hidden name="tab" value="default_tab" >
                                                                                 <input type hidden name="dstmp" value="1330609813304" >
                                                                                 <input type hidden name="srt" value="rank" >
                                                                                 <input type hidden name="ct" value="search" >
                                                                                 <input type hidden name="mode" value="Basic" >
                                                                                 <input type hidden name="dum" value="true" >
                                                                                 <input type hidden name="indx" value="1" >
                                                                                 <input type hidden name="tb" value="t" >
                         <input type hidden name="fn" value="search" >
                             <input type hidden name="vid" value="USP" >
                                 <div class="form-group">
                                     <input type="text" name="vl(freeText0)" id="mySearch" size=22  value="Busca geral..."  onfocus="this.value = ''" tabindex=1 />
                                 </div>
                                 <div class="form-group">
                                     <input type="submit" value="Buscar" tabindex=2 >
                                 </div>

                                 </form>
                                 </ul>
                                 </div>
                                 </div>
                                 <div class="col-md-3">
                                     <ul class="uspmenu_top_usp">
                                         <div class="panel-heading">BIBLIOTECAS USP</div>
                                         <li><a href="http://www.bibliotecas.usp.br/lista.htm" target="_blank">Lista alfabética</a></li>
                                         <li><a href="http://www.sibi.usp.br/30anos" target="_blank">SIBiUSP 30 Anos</a></li>
                                     </ul>
                                     <ul class="uspmenu_top_usp">
                                         <ul class="uspmenu_top_usp">
                                             <div class="panel-heading">PRODUTOS E SERVIÇOS</div>
                                             <li><a href="http://www.acessoaberto.usp.br/" target="_blank">Acesso Aberto</a></li>
                                             <li><a href="http://dedalus.usp.br/" target="_blank">Acesso ao catálogo Dedalus</a></li>
                                             <li><a href="http://www.buscaintegrada.usp.br" target="_blank">Portal de Busca Integrada</a></li>
                                             <li><a href="http://www.sibi.usp.br/Vocab/" target="_blank">Vocabulário Controlado</a></li>
                                             <li><a href="http://workshop.sibi.usp.br/index.php"  target="_blank">Writing Center - WorkShops</a></li>
                                         </ul>
                                     </ul>
                                 </div>
                                 <div class="col-md-3">
                                     <ul class="uspmenu_top_usp">
                                         <div class="panel-heading">BIBLIOTECAS DIGITAIS</div>
                                         <li><a href="http://bore.usp.br" target="_blank">Obras Raras e Especiais</a></li>
                                         <li><a href="http://revistas.usp.br" target="_blank">Portal de Revistas</a></li>
                                         <li><a href="http://www.producao.usp.br" target="_blank">Biblioteca Digital da Produção Intelectual (BDPI)</a></li>
                                     </ul>
                                     <ul class="uspmenu_top_usp">
                                         <div class="panel-heading">PARCERIAS INTERNAS</div>
                                         <li><a href="http://repositorio.iau.usp.br" target="_blank">Repositório Digital IAU</a></li>
                                         <li><a href="http://www.brasiliana.usp.br" target="_blank">Biblioteca Digital Brasiliana</a></li>
                                         <li><a href="http://www.ieb.usp.br/catalogo_eletronico" target="_blank">Biblioteca Digital do IEB</a></li>
                                         <li><a href="http://www.mapashistoricos.usp.br" target="_blank">Cartografia Histórica</a></li>
                                         <li><a href="http://www.teses.usp.br" target="_blank">Teses/Dissertações</a></li>

                                     </ul>
                                 </div>
                                 <div class="col-md-3">
                                     <ul class="uspmenu_top_usp">
                                         <div class="panel-heading">PARCERIAS EXTERNAS</div>
                                         <li><a href="http://regional.bvsalud.org/php/index.php" target="_blank">BVS em Saúde</a></li>
                                         <li><a href="http://enfermagem.bvs.br/php/index.php" target="_blank">BVS em Enfermagem</a></li>
                                         <li><a href="http://odontologia.bvs.br" target="_blank">BVS em Odontologia</a></li>
                                         <li><a href="http://www.bvs-psi.org.br"  target="_blank">BVS Psicologia Brasil</a></li>
                                         <li><a href="http://www.saudepublica.bvs.br/php/index.php" target="_blank">BVS em Saúde Pública</a></li>
                                         <li><a href="http://www.bvmemorial.fapesp.br" target="_blank">Biblioteca Virtual da América Latina</a></li>
                                         <li><a href="http://www.bv.fapesp.br" target="_blank">Biblioteca Virtual da FAPESP</a></li>
                                         <li><a href="http://ppegeo.igc.usp.br/scielo.php" target="_blank">PaGEO (Geociências)</a></li>
                                         <li><a href="http://www.periodicos.capes.gov.br" target="_blank">Portal CAPES</a></li>
                                         <li><a href="http://www.scielo.org/php/index.php?lang=pt" target="_blank">SciELO</a></li>
                                     </ul>
                                 </div>
                                 </div>
                                 </div>
                                 </div>
                 <div class="usptab" style="border-style:none;background-color:transparent;" style="position:relative;">
                     <ul class="usplogin" style="border-style:none;" >
                         <li class="uspleft" style="position:relative; z-index:0"></li>
                         <li id="usptoggle" style="position:relative; z-index:0">
                             <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree" id="uspopen" class="uspopen" border="0" style="display: block;">
                                 <img src="http://www.producao.usp.br/a/barrausp/images/seta_down.jpg" border="0">
                                     <img src="http://www.producao.usp.br/a/barrausp/images/barrinha.png" alt="SIBi - Abrir o painel" width="35" height="16" border="0" title="SIBi - Abrir o painel">
                                         </a>
                                         <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree" id="uspclose" style="display: none;" class="uspclose" border="0">
                                             <img src="http://www.producao.usp.br/a/barrausp/images/seta_up.jpg" border="0">
                                                 <img src="http://www.producao.usp.br/a/barrausp/images/barrinha.png" width="35" height="16" border="0" title="SIBi - Fechar painel" alt="SIBi - Fechar painel">
                                                     </a>
                         </li>
                         <li class="uspright" style="background-color:transparent" style="position:relative; z-index:0; display:visible"></li>
                                                     </ul>
                                                     </div> </div>
         </div>
   </div>
  <!-- uspbarra - fim -->
  <div  class="ui main container">
    <div class="ui two column stackable grid">
      <div class="ten wide column">
      <h1 class="ui header"><a href="index.php"><img class="ui fluid image" src="inc/images/producao.usp.png"></a></h1>
    </div>
    <div class="six wide column">
      <p>
        <b>Departamento Técnico do Sistema Integrado de Bibliotecas</b><br/>
        Rua da Biblioteca, S/N - Complexo Brasiliana<br/>
        05508-050 - Cidade Universitária, São Paulo, SP - Brasil<br/>
        Tel: (0xx11) 2648-0948<br/>
        E-mail: atendimento@sibi.usp.br
      </p>
    </div>
  </div>

      <div class="ui inverted yellow borderless main menu">

        <div href="#" class="header item">
          <a href="index.php" class="item">Início</a>
        </div>
        <a href="advanced_search.php" class="item">Busca avançada</a>
        <a href="#" class="ui left floated dropdown item">
          Menu <i class="dropdown icon"></i>
          <div class="menu">
            <div class="item">Login</div>
            <div class="item">Link Item</div>
            <div class="divider"></div>
            <div class="header">Header Item</div>
            <div class="item">
              <i class="dropdown icon"></i>
              Sub Menu
              <div class="menu">
                <div class="item">Link Item</div>
                <div class="item">Link Item</div>
              </div>
            </div>
            <div class="item">Link Item</div>
          </div>
        </a>
        <div class="right menu">
          <div class="item">
          <div class="ui transparent inverted icon input">
            <i class="search icon"></i>
            <input type="text" placeholder="Pesquisar">
          </div>
        </div>
          <div class="item">
            <a href="login.php">Login</a>
          </div>
        </div>
      </div>
    </div>
