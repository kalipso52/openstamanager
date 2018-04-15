<?php

include_once __DIR__.'/core.php';

$mail = Mail::get();
$bug_email = 'info@openstamanager.com';

$user = Auth::user();

if (filter('op') == 'send') {
    // Preparazione email
    $mail = new Mail();

    // Destinatario
    $mail->AddAddress($bug_email);

    // Oggetto
    $mail->Subject = 'Segnalazione bug OSM '.$version;

    // Aggiunta dei file di log (facoltativo)
    if (!empty($post['log']) && file_exists($docroot.'/logs/error.log')) {
        $mail->AddAttachment($docroot.'/logs/error.log');
    }

    // Aggiunta della copia del database (facoltativo)
    if (!empty($post['sql'])) {
        $backup_file = $docroot.'/Backup OSM '.date('Y-m-d').' '.date('H_i_s').'.sql';
        Backup::database($backup_file);

        $mail->AddAttachment($backup_file);

        $_SESSION['infos'][] = tr('Backup del database eseguito ed allegato correttamente!');
    }

    // Aggiunta delle informazioni di base sull'installazione
    $infos = [
        'Utente' => $user['username'],
        'IP' => get_client_ip(),
        'Versione OSM' => $version.' ('.(!empty($revision) ? $revision : 'In sviluppo').')',
        'PHP' => phpversion(),
    ];

    // Aggiunta delle informazioni sul sistema (facoltativo)
    if (!empty($post['info'])) {
        $infos['Sistema'] = $_SERVER['HTTP_USER_AGENT'].' - '.getOS();
    }

    // Completamento del body
    $body = $post['body'].'<hr>';
    foreach ($infos as $key => $value) {
        $body .= '<p>'.$key.': '.$value.'</p>';
    }

    $mail->Body = $body;

    $mail->AltBody = 'Questa email arriva dal modulo bug di segnalazione bug di OSM';

    // Invio mail
    if (!$mail->send()) {
        $_SESSION['errors'][] = tr("Errore durante l'invio della segnalazione").': '.$mail->ErrorInfo;
    } else {
        $_SESSION['infos'][] = tr('Email inviata correttamente!');
    }

    // Rimozione del dump del database
    if (!empty($post['sql'])) {
        delete($backup_file);
    }

    redirect($rootdir.'/bug.php');
    exit();
}

$pageTitle = tr('Bug');
$jscript_modules[] = App::getPaths()['js'].'/ckeditor/ckeditor.js';

if (file_exists($docroot.'/include/custom/top.php')) {
    include $docroot.'/include/custom/top.php';
} else {
    include $docroot.'/include/top.php';
}

if (empty($mail['from_address']) || empty($mail['server'])) {
    echo '
<div class="alert alert-warning">
    <i class="fa fa-warning"></i>
    <b>'.tr('Attenzione!').'</b> '.tr('Per utilizzare correttamente il modulo di segnalazione bug devi configurare alcuni parametri riguardanti le impostazione delle email').'.

    '.Modules::link('Account email', $mail['id'], tr('Correggi account'), null, 'class="btn btn-warning pull-right"').'
    <div class="clearfix"></div>
</div>';
}

echo '
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-bug"></i> '.tr('Segnalazione bug').'</h3>
    </div>

    <div class="box-body">
        <form method="post" action="">
            <input type="hidden" name="op" value="send">

            <table class="table table-bordered table-condensed table-striped table-hover">
                <tr>
                    <th width="150" class="text-right">'.tr('Da').':</th>
                    <td>'.$mail['from_address'].'</td>
                </tr>

                <!-- A -->
                <tr>
                    <th class="text-right">'.tr('A').':</th>
                    <td>'.$bug_email.'</td>
                </tr>

                <!-- Versione -->
                <tr>
                    <th class="text-right">'.tr('Versione OSM').':</th>
                    <td>'.$version.' ('.(!empty($revision) ? $revision : tr('In sviluppo')).')</td>
                </tr>
            </table>

            <div class="row">
                <div class="col-md-4">
                    {[ "type": "checkbox", "placeholder": "'.tr('Allega file di log').'", "name": "log", "value": "1" ]}
                </div>

                <div class="col-md-4">
                    {[ "type": "checkbox", "placeholder": "'.tr('Allega copia del database').'", "name": "sql" ]}
                </div>

                <div class="col-md-4">
                    {[ "type": "checkbox", "placeholder": "'.tr('Allega informazioni sul PC').'", "name": "info", "value": "1" ]}
                </div>
            </div>

            <div class="clearfix"></div>
            <br>

            {[ "type": "textarea", "label": "'.tr('Descrizione del bug').'", "name": "body" ]}

            <!-- PULSANTI -->
            <div class="row">
                <div class="col-md-12 text-right">
                    <button type="submit" class="btn btn-primary" id="send" disabled>
                        <i class="fa fa-envelope"></i> '.tr('Invia segnalazione').'
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function(){
        var html = "<p>'.tr('Se hai riscontrato un bug ricordati di specificare').':</p>" +
        "<ul>" +
            "<li>'.tr('Modulo esatto (o pagina relativa) in cui questi si è verificato').';</li>" +
            "<li>'.tr('Dopo quali specifiche operazioni hai notato il malfunzionameto').'.</li>" +
        "</ul>" +
        "<p>'.tr('Assicurati inoltre di controllare che il checkbox relativo ai file di log sia contrassegnato, oppure riporta qui l\'errore visualizzato').'.</p>" +
        "<p>'.tr('Ti ringraziamo per il tuo contributo').',<br>" +
        "'.tr('Lo staff di OSM').'</p>";

        var firstFocus = 1;

        CKEDITOR.replace("body", {
            toolbar: globals.ckeditorToolbar,
            language: globals.locale,
            scayt_autoStartup: true,
            scayt_sLang: globals.full_locale
        });

        CKEDITOR.instances.body.on("key", function() {
            setTimeout(function(){
                if(CKEDITOR.instances.body.getData() == ""){
                    $("#send").prop("disabled", true);
                }
                else $("#send").prop("disabled", false);
            }, 10);
        });

        CKEDITOR.instances.body.setData( html, function() {});

        CKEDITOR.instances.body.on("focus", function() {
            if(firstFocus){
                CKEDITOR.instances.body.setData("", function() {
                    CKEDITOR.instances.body.focus();
                });
                firstFocus = 0;
            }
        });
    });
</script>';

if (file_exists($docroot.'/include/custom/bottom.php')) {
    include $docroot.'/include/custom/bottom.php';
} else {
    include $docroot.'/include/bottom.php';
}
