<?php 
require_once('config.php');
require_once($fichiersInclude.'head.php') ?>

<body>

  <div id="container" class="container mt-5">

    <div class="row mt-2">
      <div class="col-md-3"></div>
      <div class="col-md-6">
        <h1>Se connecter</h1>
        <hr>
        <?php 

          if (isset($_GET['erreur'])) {
            afficherErreur("<strong>Identifient</strong> ou <strong>mot de passe</strong> incorrect !");
          }

        ?>
      </div>
    </div>

    <form class="form-group" action="login.php" method="post">

      <div class="row">

        <div class="col-md-3"></div>
        <div class="col-md-6">
          <label for="id">Identifients :</label>
          <input type="id" class="form-control" id="id" name="id" placeholder="Identifient" required>
        </div>
      </div>

      <div class="row mt-2">
        <div class="col-md-3"></div>
        <div class="col-md-6">
          <label for="pwd">Mot de passe :</label>
          <input type="password" class="form-control" id="pwd" name="pwd" placeholder="Mot de passe" required>
        </div>
      </div>

      <div class="row mt-2">
        <div class="col-md-3"></div>
        <div class="col-md-6">
          <button type="submit" class="btn btn-success"> <i class="fa fa-sign-in"></i> Se connecter</button>
        </div>
      </div>

    </form>

  </div>

</body>

<?php require_once($fichiersInclude.'footer.php'); ?>

</html>