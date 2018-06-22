<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>
      AcquaSpare
    </title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
      <!-- FontAwesome -->
    <link rel="stylesheet" href="../font-awesome-4.6.3/font-awesome-4.6.3/css/font-awesome.min.css">
      <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

      <!-- Bootstrap -->
      <link href="bootstrap-3.3.6-dist/css/bootstrap.min.css" rel="stylesheet">
      <link href="css/style.css" rel="stylesheet">

      <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
      <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
      <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
      <![endif]-->
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
      <script src="bootstrap-3.3.6-dist/js/bootstrap.min.js"></script>
      <script src="js/ajaxupload.js" type="text/javascript"></script><script src="//code.jquery.com/jquery-1.10.2.js"></script>
      <script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
      <script src="js/custom.js"></script>
  </head>
  <body>
    <!--Navbar -->
    <nav class="navbar navbar-default">
      <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="index.php">
          <?php
            session_start();
            require 'config.php';
            require 'connect.php';
            list($id, $nome, $prezzo, $descrizione, $disponibili, $codice)=mysql_fetch_array(mysql_query("SELECT id AS id, nome, prezzo, descrizione, disponibili, codice FROM prodotti WHERE id='".$_SESSION['prodid']."'"));

            if(isset($_SESSION['id'])){
              $row = mysql_fetch_assoc(mysql_query("SELECT * FROM utenti WHERE id='".$_SESSION['id']."'"));
              echo $row['username'];
            }else{
              echo 'Acquaspare';
            }
          ?>
          </a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav">
            <li><a href="index.php"><i class="fa fa-home" aria-hidden="true"></i> Home</a></li>
            <?php
            if(isset($_SESSION['id'])){
            ?>
              <li>
                <a href="carrello.php">
                  <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                    Carrello
                      <?php
                        $prod =   mysql_query("SELECT * FROM carrello WHERE iduser='".$_SESSION['id']."'");
                        $i    =   0;
                        while($prod_num = mysql_fetch_assoc($prod)){
                          $i  +=  $prod_num['howmany'];
                        }
                        if($i!=0){
                          echo '<span class="badge">'.$i.'</span>';
                        }
                      ?>
                </a>
              </li>
            <?php
            }
            ?>
            <li><a href="lista_prodotti.php"><i class="fa fa-archive" aria-hidden="true"></i> Prodotti</a></li>
          </ul>
          <form class="navbar-form navbar-left" method="post" role="search" action="lista_prodotti.php">
            <div class="input-group">
                <input type="text" name="ricerca" class="form-control" placeholder="Ricerca Prodotto">
              <div class="input-group-btn">
                <button type="submit" class="btn btn-default">
                  <i class="fa fa-search" aria-hidden="true"></i>
                </button>
              </div>
            </div>
          </form>
          <ul class="nav navbar-nav navbar-right">
            <li><a href="contact_us.php"><i class="fa fa-envelope" aria-hidden="true"></i> Contattaci</a></li>
              <?php
                if($_SESSION['id']==1){
                  echo '<li><a href="pannello_controllo.php"><i class="fa fa-wrench" aria-hidden="true"></i> Pannello di Controllo</a></li>';
                }
              ?>
              <?php
                if(isset($_SESSION['id'])){
                  echo '<li><a href="close_session.php"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a></li>';
                }else{
                  echo '<li><a href="login.php"><i class="fa fa-sign-in" aria-hidden="true"></i> Accedi</a></li>';
                  echo '<li><a href="registrazione.php"><i class="fa fa-user" aria-hidden="true"></i> Registrati</a></li>';
                }
                ?>
            </li>
          </ul>
        </div><!-- /.navbar-collapse -->
      </div><!-- /.container-fluid -->
    </nav><!--Of navbar -->



<?php
  require 'config.php';
  require 'connect.php';

  if(isset($_GET['email']) && !empty($_GET['email']) AND isset($_GET['hash']) && !empty($_GET['hash'])){
    // Verify data
    $email = mysql_escape_string($_GET['email']); // Set email variable
    $hash = mysql_escape_string($_GET['hash']); // Set hash variable

    $search = mysql_query("SELECT email, hash, active FROM utenti WHERE email='".$email."' AND hash='".$hash."' AND active='0'") or die(mysql_error());
    $match  = mysql_num_rows($search);


    if($match != 0){
        // We have a match, activate the account
        mysql_query("UPDATE utenti SET active='1' WHERE email='".$email."' AND hash='".$hash."' AND active='0'") or die(mysql_error());

        echo '<div class="alert alert-success" id="alert" style="margin-left:auto; margin-right:auto;"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Il tuo account &egrave stato confermato, procedi con il <strong><a href="login.php">Login</a></strong></div>
          <center><a href="index.php" class="btn btn-primary">Vai alla Home</a></center><style>@media screen and (min-width: 100px) and (max-width: 420px){
          #alert{
            width:80%;
            }
        }
        @media screen and (min-width: 420px) and (max-width: 1024px){
          #alert{
            width:50%;
          }
        }
        @media screen and (min-width: 1024px){
          #alert{
            width:40%;
          }</style>';
    }else{
        // No match -> invalid url or account has already been activated.
        echo '<div class="alert alert-warning" id="alert" style="margin-left:auto; margin-right:auto;"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>L\'Url pu&ograve essere non valido oppure potresti aver gi&agrave attivato il tuo account</div>
          <center><a href="index.php" class="btn btn-primary">Vai alla Home</a></center><style>@media screen and (min-width: 100px) and (max-width: 420px){
          #alert{
            width:80%;
            }
        }
        @media screen and (min-width: 420px) and (max-width: 1024px){
          #alert{
            width:50%;
          }
        }
        @media screen and (min-width: 1024px){
          #alert{
            width:40%;
          }</style>';
    }

    }else{
        // Invalid approach
        echo '<center><div class="alert alert-danger" id="alert" style="margin-left:auto; margin-right:auto;"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Url non valido, per favore usa il link che ti &egrave stato inviato per email.</div>
        <br><a href="index.php" class="btn btn-primary">Vai alla Home</a></center><style>@media screen and (min-width: 100px) and (max-width: 420px){
          #alert{
            width:80%;
            }
        }
        @media screen and (min-width: 420px) and (max-width: 1024px){
          #alert{
            width:50%;
          }
        }
        @media screen and (min-width: 1024px){
          #alert{
            width:40%;
          }</style>';
    }

?>
</body>
</html>