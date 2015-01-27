<!doctype html>
<html class="no-js" lang="it">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

  <title>Social</title>
  <link rel="stylesheet" type="text/css" href="./asset/stile.css" />
  <link rel="stylesheet" type="text/css" href="./asset/font-awesome/css/font-awesome.min.css" />
</head>
<body>
  <div id="container">
    <div id="header">
      <h1>Social</h1>
      <?php if(isset($_SESSION['id']) && isset($_GET['a'])){ ?>

      <div id="dyn_status">
        <div id="status"><?php echo $stato ?></div>
        <div id="form">
          <input id="newstatus" type="text"/>
          <button id="update_btn">Aggiorna stato</button>
        </div>
      </div>
    <?php } ?>
    </div>
    <div id="navigation">
      <ul>
        <?php if (isset($_GET['a'])) { ?>
        <li><a href="./?a=home"><i class="fa fa-home"></i> Home</a></li>
        <li><a href="./?a=profilo"><i class="fa fa-user"></i> Modifica profilo</a></li>
        <li><a href="./?a=amici"><i class="fa fa-users"></i> Amici</a></li>
        <li><a href="./?a=richiesta_amicizia"><i class="fa fa-bell-o"></i> Richieste di amicizia</a></li>
        <li><a href="./?a=notizie"><i class="fa fa-newspaper-o"></i> Notizie</a></li>
        <li><a href="./?a=search"><i class="fa fa-search"></i> Cerca</a></li>
        <li><a href="./?a=statistiche"><i class="fa fa-bar-chart"></i> Statistiche</a></li>
        <?php if(isset($_SESSION['id'])) { ?>
        <li> <a href="./?a=logout"><i class="fa fa-sign-out"></i>Logout</a></li>
        <?php } ?>
        <?php } else { ?>
          <li><a href="./admin.php?p=list"><i class="fa fa-users"></i>Lista utenti</a></li>
          <li><a href="./admin.php?p=notizie"><i class="fa fa-newspaper-o"></i>Ultime notizie</a></li>
          <li> <a href="./admin.php?p=logout"><i class="fa fa-sign-out"></i>Logout</a></li>
        <?php } ?>
      </ul>
    </div>
    <div id="content">
        <?php echo $content ?>
    </div>
    <div class="clear"></div>
    <div id="footer">
      <a href="#">Informazioni</a> <a href="#">Supporto</a> <a href="#">Aiuto</a>
    </div>
  </div>
  <script type="text/javascript" src="https://www.google.com/jsapi"></script>
  <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
  <?php if(isset($_GET['a'])){ ?>
  <script src="./asset/script.js" type="text/javascript"></script>
  <?php } else { ?>
  <script src="./asset/admin.js" type="text/javascript"></script>
  <?php } ?>
</body>
</html>
