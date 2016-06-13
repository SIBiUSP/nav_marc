<?php
$tpTitle = 'Login - BDPI USP';
?>
<?php
  include ('inc/config.php');
  include ('inc/meta-header.php');
  include_once 'inc/functions.php';
?>
<title><?php echo gettext("branch");?> - Login</title>
  <script>
  $(document)
    .ready(function() {
      $('.ui.form')
        .form({
          fields: {
            email: {
              identifier  : 'email',
              rules: [
                {
                  type   : 'empty',
                  prompt : 'Digite seu e-mail'
                },
                {
                  type   : 'email',
                  prompt : 'Digite um e-mail válido'
                }
              ]
            },
            password: {
              identifier  : 'password',
              rules: [
                {
                  type   : 'empty',
                  prompt : 'Digite sua senha'
                },
                {
                  type   : 'length[6]',
                  prompt : 'Sua senha precisa ter ao menos 6 caracteres'
                }
              ]
            }
          }
        })
      ;
    })
  ;
  </script>
</head>
<body>
   <?php include 'inc/barrausp.php'; ?>    
    <div class="ui container">
        <?php include 'inc/header.php'; ?>
        <?php include 'inc/navbar.php'; ?> 
<div class="ui middle aligned center aligned grid">
  <div class="column1">
    <h2 class="ui teal image header">
      <div class="content">
        Login em sua conta
      </div>
    </h2>
    <form class="ui large form" role="form" action="admin.php" method="post">
      <div class="ui stacked segment">
        <div class="field">
          <div class="ui left icon input">
            <i class="user icon"></i>
            <input type="text" name="username" placeholder="E-mail">
          </div>
        </div>
        <div class="field">
          <div class="ui left icon input">
            <i class="lock icon"></i>
            <input type="password" name="password" placeholder="Senha">
          </div>
        </div>
        <button type="submit" id="login" class="ui fluid large teal submit button">Login</button>
      </div>

      <div class="ui error message"></div>

    </form>

    <div class="ui message">
      Não tem conta ainda? <a href="#">Crie sua conta</a>
    </div>
  </div>
</div>
    </div>

<?php
  include "inc/footer.php";
?>
</body>
</html>
