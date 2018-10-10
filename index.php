<?php session_start(); ?>

<!doctype html>

<html lang="fr">

<head>

  <meta charset="utf-8">

  <title>Evaluation des enseignements - Connexion</title>
  <meta name="description" content="Mon Super Site">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="style.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

</head>

<body>

  <div id="container" class="container mt-5">

    <div class="row mt-2">
      <div class="col-md-3"></div>
      <div class="col-md-6">
        <h1>Se connecter</h1>

        <hr>
      </div>
    </div>

    <form class="form-group" action="connect.php" method="post">

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

</html>