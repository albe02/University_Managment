<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Università</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="stileLog.css">
  </head>
  <body>

    <div id="navbar">
    <?php include("navbar.php");?>
    </div>
    <div id="containerEsterno" class="container-fluid">
      <div id="banner" class="row">
        <h1>Università degli studi di ...</h1>
      </div>
      <div id="containerInterno" class="container-lg">
        <div id="campiForm" class="col-4">

          <form action="loginFunction.php" method="POST">
            <div id="eMail" class="row">
              <input name="email" type="email" class="form-control custom-bg" id="InputEmail" aria-describedby="emailHelp" placeholder="E-Mail">
            </div>

            <div id="psw" class="row">
              <input name="password" type="password" class="form-control custom-bg" id="InputPsw" aria-describedby="pswHelp" placeholder="Password">
            </div>
            
            <div id="user" class="row">
              <select name="ruolo" class="form-select custom-bg" id="inputGroupSelect01">
                <option selected>Tipo Utente</option>
                <option value="segreteria">Segreteria</option>
                <option value="docente">Docente</option>
                <option value="studente">Studenti</option>
              </select>
              
            </div>

            <div id="login" class="row">
              <button type="submit" class="btn">Log-In</button>
            </div>

          </form>

          <div id="accesPrivacy" class="row">
            <div id="accessibilita" class="col-6">
              <a calss="custom-link" href="####">Dichiarazione di accessibilità</a>
            </div>
            
            <div id="privacy" class="col-6 d-flex">
              <a href="####">Privacy e cookie</a>
            </div>
          </div>
          <div id="fondo" class="row">
            <div id="infoFondo" class="col">
              <p>Università degli studi</p>
              <p>Via di qualcosa 5, 88060 - Davoli</p>
              <a href="####">Assistenza</a>
              <p>C.F. 12345678912 P.I. 12345678912</p>
            </div>
          </div>
        </div>
      </div> 
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
  </body>
</html>