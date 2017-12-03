<?php

@session_start();
$token = md5(uniqid(rand(), true));
$_SESSION['token'] = $token;


?><div class="modal-header bg-light">
    <h4 class="modal-title" id="dialoglabel">Kontaktformular</h4>
    <button class="close" type="button" data-dismiss="modal" aria-label="Schließen">
        <span aria-hidden="true">×</span>
    </button>
</div>
<div class="modal-body px-5 py-3">
    <p>Wenn Du Pascal eine Nachricht senden möchtest, fülle bitte das folgende Formular aus:</p>
    <form method="post" action="./">
        <input type="hidden" name="token" value="<?php echo $token; ?>">
        <div class="form-group row">
            <label for="inputemail" class="col-sm-3 col-form-label">Deine E-Mail:</label>
            <div class="col-sm-9">
                <input type="email" class="form-control" id="inputemail" name="email" placeholder="Deine E-Mail-Adresse" required>
            </div>
        </div>
        <div class="form-group row">
            <label for="nachricht" class="col-sm-3 col-form-label">Deine Nachricht:</label>
            <div class="col-sm-9">
                <textarea class="form-control" id="nachricht" name="nachricht" rows="5"  placeholder="Deine Nachricht an Pascal" required></textarea>
            </div>
        </div>
        <div class="form-group row">
            <div class="col text-right">
                <button class="btn btn-secondary m-2" data-dismiss="modal">Abbrechen</button>
                <button type="submit" class="btn btn-primary m-2">Absenden</button>
            </div>
        </div>
    </form>
</div>