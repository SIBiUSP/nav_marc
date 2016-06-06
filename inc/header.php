<!DOCTYPE html>
<html lang="pt_BR">
<?php session_start(); ?>
<head>
    
    <!-- Metadados OG -->
    <meta http-equiv="content-type" content="text/html; charset=utf-8"></meta>
    <meta name="viewport" content="width=device-width, initial-scale=1"></meta>
    <meta property="og:url"           content="" /></meta>
    <meta property="og:type"          content="website" /></meta>
    <meta property="og:title"         content="<?php echo $tpTitle ?>" /></meta>
    <meta property="og:description"   content="" /></meta>
    <meta property="og:image"         content="" /></meta>
    <title><?php echo $tpTitle ?></title>

    <!-- Favicon -->
    <link type="image/x-icon" href="<?php echo "http://" . $_SERVER['SERVER_NAME'] ."/".$SERVER_DIRECTORY."/"; ?>inc/images/faviconUSP.ico" rel="icon" />
    <link type="image/x-icon" href="<?php echo "http://" . $_SERVER['SERVER_NAME'] ."/".$SERVER_DIRECTORY."/"; ?>inc/images/faviconUSP.ico" rel="shortcut icon" />

    <!-- Scripts -->
    <script src="<?php echo "http://" . $_SERVER['SERVER_NAME'] ."/".$SERVER_DIRECTORY."/"; ?>inc/semantic-ui/semantic.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="http://bdpife2.sibi.usp.br/vocab21/common/bootstrap/js/bootstrap.min.js"></script>
    <script src="http://semantic-ui.com/dist/semantic.min.js"></script>

    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="<?php echo "http://" . $_SERVER['SERVER_NAME'] ."/".$SERVER_DIRECTORY."/"; ?>inc/semantic-ui/semantic.min.css">
    <link rel="stylesheet" href="<?php echo "http://" . $_SERVER['SERVER_NAME'] ."/".$SERVER_DIRECTORY."/"; ?>inc/css/vcusp-theme.css">
    <link rel="stylesheet" href="<?php echo "http://" . $_SERVER['SERVER_NAME'] ."/".$SERVER_DIRECTORY."/"; ?>inc/css/style.css">

    <!-- Citation Style - Session - Default: ABNT -->
    <?php
    if (empty($_SESSION["citation_style"])) {
        $_SESSION["citation_style"]="abnt";
    }
    if (isset($_POST["citation_style"])) {
        $_SESSION["citation_style"] = $_POST['citation_style'];
    }
    ?>