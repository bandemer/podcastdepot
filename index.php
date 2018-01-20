<?php
/*
 * Startseite mit Funktion zum Versenden von Nachrichten
 *
 */
@session_start();

include 'config.php';

//Nachricht versenden
$output = '';

if (isset($_POST['nachricht']) AND isset($_POST['email']))
{
    $error = array();

    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $nachricht = htmlentities(trim($_POST['nachricht']));

    //Token überprüfen
    if (!isset($_SESSION['token']) OR $_POST['token'] != $_SESSION['token'])
    {
        $error[] = 'Ein Fehler ist aufgetreten. Bitte versuche es erneut.';
    }

    //E-Mail-Adresse überprüfen
    if ($email == false) {
        $error[] = 'Die angegebene E-Mail-Adresse ist nicht gültig.';
    }

    //Fehlermeldung ausgeben
    if (count($error) > 0) {
        $output = '<div class="alert alert-danger alert-dismissible fade show mx-lg-5 my-lg-3" role="alert">
<strong>Fehler!</strong> '.implode(' ', $error).
'<button type="button" class="close" data-dismiss="alert" aria-label="Schließen">
<span aria-hidden="true">&times;</span>
</button>
</div>';
    //Wenn alles in Ordnung, Nachricht versenden
    } else {
        $message =
            "Folgende Nachricht ist über das Kontaktformular eingegangen:\n\n".
            "Absender: ".$email."\n\n".
            "Nachricht:\n".$nachricht;

        $headers = "From: ".$email."\nReply-To: ".$email;
        mail($config['recipient'], $config['subject'], $message, $headers);

        $output = '<div class="alert alert-success alert-dismissible mx-lg-5 my-lg-3 fade show" role="alert">
<strong>Danke!</strong> Deine Nachricht wurde erfolgreich versendet.
<button type="button" class="close" data-dismiss="alert" aria-label="Schließen">
<span aria-hidden="true">&times;</span>
</button>
</div>';
        //Token löschen
        $_SESSION['token'] = '';
    }
}

?><!DOCTYPE html>
<html lang="de">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>PodcastDepot - Podcasts von und mit Pascal Dupré</title>
<meta name="description" content="Unter dem Namen PodcastDepot produziert und veröffentlicht Pascal Dupré - besser bekannt als KleinesP -  unterschiedliche Podcast-Formate. Dabei sind alle Episoden frei und kostenlos im Internet verfügbar.">
<link rel="stylesheet" href="assets/font-awesome.min.css">
<link rel="stylesheet" href="assets/bootstrap.css?date=<?php echo date('YmdHis', filemtime('assets/bootstrap.css')); ?>">
</head>
<body>
<div class="container">
    <div class="jumbotron">
        <h1 class="text-hide">PodcastDepot</h1>
        <p class="lead">Unter dem Namen PodcastDepot produziert und veröffentlicht Pascal Dupré - besser bekannt als KleinesP -  unterschiedliche Podcast-Formate. Dabei sind alle Episoden frei und kostenlos im Internet verfügbar.</p>
        <?php echo $output; ?>
        <p>
            <a class="btn btn-lg btn-warning m-2" href="https://kleinesp.de" role="button">Mehr über Pascal</a>
            <a class="btn btn-lg btn-warning m-2" href="#" role="button" data-toggle="modal" data-target="#dialog" data-remote="modals/contact.php"><i class="fa fa-envelope" aria-hidden="true"></i> Kontakt</a>
        </p>
    </div>
    <div class="row">
        <div class="col-12">
            <h2>Podcast-Formate von und mit Pascal Dupré</h2>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4 my-2">
            <div class="card">
                <img class="card-img-top" src="assets/kleinesgespraech-logo.png" alt="KleinesGespräch Logo">
                <div class="card-body text-center">
                    <h3 class="card-title">KleinesGespräch</h3>
                    <p>“KleinesGespräch” ist eine lockere und zwanglose Unterhaltung. Dabei kommt man oft vom Hölzchen aufs Stöckchen und verquatscht sich ein wenig – “KleinesGespräch” liegt damit fernab vom Standard-Interviewformat.</p>
                    <a class="btn btn-secondary my-3" href="https://kleinesgespraech.de" role="button"><i class="fa fa-podcast"></i> kleinesgespraech.de</a>
                </div>
            </div>
        </div>

        <div class="col-lg-4 my-2">
            <div class="card">
                <img class="card-img-top" src="assets/thematischfrisch-logo.png" alt="ThematischFrisch Logo">
                <div class="card-body text-center">
                    <h3 class="card-title">ThematischFrisch</h3>
                    <p>Ursprünglich im Jahr 2016 als “KleinesGespräch Thema” gestartet, hat sich dieser Podcast inzwischen zu einem eigenen Format entwickelt. Pascal bespricht dabei regelmäßig mit fachkundigen Gästen ein aktuelles Thema. </p>
                    <a class="btn btn-secondary my-3" href="https://thematischfrisch.de" role="button"><i class="fa fa-podcast"></i> thematischfrisch.de</a>
                </div>
            </div>
        </div>

        <div class="col-lg-4 my-2">
            <div class="card">
                <img class="card-img-top" src="assets/kleinermonolog-logo.png" alt="KleinerMonolog Logo">
                <div class="card-body text-center">
                    <h3 class="card-title">KleinerMonolog</h3>
                    <p>In diesem Podcast spricht Pascal über seine aktuellen Erlebnisse und persönlichen Gefühle und Gedanken. Neue Episoden entstehen dabei eher spontan, Gäste und regelmäßige Veröffentlichung gibt es hier nicht.</p>
                    <a class="btn btn-secondary my-3" href="https://kleinermonolog.de" role="button"><i class="fa fa-podcast"></i> kleinermonolog.de</a>
                </div>
            </div>
        </div>
    </div>
    <footer class="footer">
        <div class="row">
            <div class="col-8">&copy; <?php echo date('Y'); ?> Pascal Dupré</div>
            <div class="col-4 text-right"><a href="#" data-toggle="modal" data-target="#dialog" data-remote="modals/impressum.php">Impressum</a></div>
        </div>
    </footer>
</div>

<!-- Modaler Dialog -->
<div class="modal fade" id="dialog" tabindex="-1" role="dialog" aria-labelledby="dialoglabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content"></div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script type="text/javascript">
$('body').on('click', '[data-toggle="modal"]', function(){
    $($(this).data("target")+' .modal-content').load($(this).data("remote"));
});
$('body').on('click', '#kontaktlink', function(){
    $('#dialog .modal-content').load('modals/contact.php');
});
</script>
</body>
</html>