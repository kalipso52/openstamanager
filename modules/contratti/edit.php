<?php

$block_edit = $record['is_completato'];

?><script src="<?php echo $rootdir; ?>/modules/contratti/js/contratti_helper.js"></script>

<form action="" method="post" id="edit-form">
	<input type="hidden" name="backto" value="record-edit">
	<input type="hidden" name="op" value="update">
	<input type="hidden" name="id_record" value="<?php echo $id_record; ?>">

	<!-- DATI INTESTAZIONE -->
	<div class="card card-primary">
		<div class="card-header">
			<h3 class="card-title"><?php echo tr('Intestazione'); ?></h3>
		</div>

		<div class="card-body">
			<div class="row">
				<div class="col-md-3">
					{[ "type": "text", "label": "<?php echo tr('Numero'); ?>", "name": "numero", "required": 1, "class": "text-center", "value": "$numero$" ]}
				</div>

                <div class="col-md-3">
                    {[ "type": "date", "label": "<?php echo tr('Data bozza'); ?>", "name": "data_bozza", "value": "$data_bozza$" ]}
                </div>

                <div class="col-md-2">
                    {[ "type": "date", "label": "<?php echo tr('Data accettazione'); ?>", "name": "data_accettazione", "value": "$data_accettazione$" ]}
                </div>

                <div class="col-md-2">
                    {[ "type": "date", "label": "<?php echo tr('Data conclusione'); ?>", "name": "data_conclusione", "value": "$data_conclusione$" ]}
                </div>

                <div class="col-md-2">
                    {[ "type": "date", "label": "<?php echo tr('Data rifiuto'); ?>", "name": "data_rifiuto", "value": "$data_rifiuto$" ]}
                </div>
            </div>

			<div class="row">
                <div class="col-md-3">
                    <?php
                    echo Modules::link('Anagrafiche', $record['idanagrafica'], null, null, 'class="float-right"');
                    ?>

                    {[ "type": "select", "label": "<?php echo tr('Cliente'); ?>", "name": "idanagrafica", "id": "idanagrafica_c", "required": 1, "value": "$idanagrafica$", "ajax-source": "clienti" ]}
                </div>

                <div class="col-md-3">
                    {[ "type": "select", "label": "<?php echo tr('Sede'); ?>", "name": "idsede", "value": "$idsede$", "ajax-source": "sedi", "placeholder": "Sede legale" ]}
                </div>

				<div class="col-md-3">
                    <?php
                    echo Plugins::link('Referenti', $record['idanagrafica'], null, null, 'class="float-right"');
                    ?>

					{[ "type": "select", "label": "<?php echo tr('Referente'); ?>", "name": "idreferente", "value": "$idreferente$", "ajax-source": "referenti", "ajax-info": "idanagrafica=$idanagrafica$" ]}
				</div>

				<div class="col-md-3">
                    <?php
                        if ($record['idagente'] != 0) {
                            echo Modules::link('Anagrafiche', $record['idagente'], null, null, 'class="float-right"');
                        }
                    ?>
					{[ "type": "select", "label": "<?php echo tr('Agente'); ?>", "name": "idagente", "values": "query=SELECT an_anagrafiche.idanagrafica AS id, ragione_sociale AS descrizione FROM an_anagrafiche INNER JOIN (an_tipianagrafiche_anagrafiche INNER JOIN an_tipianagrafiche ON an_tipianagrafiche_anagrafiche.id_tipo_anagrafica=an_tipianagrafiche.id) ON an_anagrafiche.idanagrafica=an_tipianagrafiche_anagrafiche.idanagrafica WHERE descrizione='Agente' AND deleted_at IS NULL ORDER BY ragione_sociale", "value": "$idagente$" ]}
				</div>

			</div>

            <div class="row">
                <div class="col-md-6">
                    {[ "type": "text", "label": "<?php echo tr('Nome'); ?>", "name": "nome", "required": 1, "value": "$nome$" ]}
                </div>

                <div class="col-md-3">
                    {[ "type": "select", "label": "<?php echo tr('Pagamento'); ?>", "name": "idpagamento", "values": "query=SELECT id, descrizione FROM co_pagamenti GROUP BY descrizione ORDER BY descrizione", "value": "$idpagamento$" ]}
                </div>

                <div class="col-md-3">
                    {[ "type": "select", "label": "<?php echo tr('Stato'); ?>", "name": "id_stato", "required": 1, "values": "query=SELECT id, descrizione FROM co_staticontratti", "value": "$id_stato$", "class": "unblockable" ]}
                </div>
            </div>

			<div class="row">

				<div class="col-md-3">
					{[ "type": "number", "label": "<?php echo tr('Validità offerta'); ?>", "name": "validita", "decimals": "0", "value": "$validita$", "icon-after": "giorni" ]}
				</div>

				<div class="col-md-3">
					{[ "type": "checkbox", "label": "<?php echo tr('Rinnovabile'); ?>", "name": "rinnovabile", "help": "<?php echo tr('Il contratto è rinnovabile?'); ?>", "value": "$rinnovabile$" ]}
				</div>

				<div class="col-md-3">
					{[ "type": "number", "label": "<?php echo tr('Preavviso per rinnovo'); ?>", "name": "giorni_preavviso_rinnovo", "decimals": "0", "value": "$giorni_preavviso_rinnovo$", "icon-after": "giorni", "disabled": <?php echo $record['rinnovabile'] ? 0 : 1; ?> ]}
				</div>

                <div class="col-md-3">
					{[ "type": "number", "label": "<?php echo tr('Ore rimanenti rinnovo'); ?>", "name": "ore_preavviso_rinnovo", "decimals": "0", "value": "$ore_preavviso_rinnovo$", "icon-after": "ore", "disabled": <?php echo $record['rinnovabile'] ? 0 : 1; ?>, "help": "Numero ore restanti nel contratto per avviso rinnovo anticipato." ]}
				</div>
			</div>

			<div class="row">

			</div>

			<div class="row">

				<div class="col-md-3">
					{[ "type": "select", "multiple": "1", "label": "<?php echo tr('Impianti'); ?>", "name": "matricolaimpianto[]", "values": "query=SELECT idanagrafica, id AS id, IF(nome = '', matricola, CONCAT(matricola, ' - ', nome)) AS descrizione FROM my_impianti WHERE idanagrafica='$idanagrafica$' ORDER BY descrizione", "value": "$idimpianti$" ]}
				</div>
            </div>


			<div class="row">
				<div class="col-md-12">
					{[ "type": "textarea", "label": "<?php echo tr('Esclusioni'); ?>", "name": "esclusioni", "class": "autosize", "value": "$esclusioni$" ]}
				</div>
			</div>

			<div class="row">
				<div class="col-md-12">
					{[ "type": "textarea", "label": "<?php echo tr('Descrizione'); ?>", "name": "descrizione", "class": "autosize", "value": "$descrizione$" ]}
				</div>
			</div>
		</div>
	</div>

    <!-- Fatturazione Elettronica -->
    <div class="card card-primary <?php echo ($record['tipo_anagrafica'] == 'Ente pubblico' || $record['tipo_anagrafica'] == 'Azienda') ? 'show' : 'hide'; ?>" >
        <div class="card-header">
            <h3 class="card-title"><?php echo tr('Dati appalto'); ?></h3>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
					{[ "type": "text", "label": "<?php echo tr('Identificatore Documento'); ?>", "help": "<?php echo tr('<span>Obbligatorio per valorizzare CIG/CUP. &Egrave; possible inserire: </span><ul><li>N. determina</li><li>RDO</li><li>Ordine MEPA</li></ul>'); ?>","name": "id_documento_fe", "required": 0, "value": "$id_documento_fe$", "maxlength": 20 ]}
				</div>

                <div class="col-md-6">
					{[ "type": "text", "label": "<?php echo tr('Numero Riga'); ?>", "name": "num_item", "required": 0, "value": "$num_item$", "maxlength": 15 ]}
				</div>
			</div>
			<div class="row">
                <div class="col-md-6">
					{[ "type": "text", "label": "<?php echo tr('Codice CIG'); ?>", "name": "codice_cig", "required": 0, "value": "$codice_cig$", "maxlength": 15 ]}
				</div>

				<div class="col-md-6">
					{[ "type": "text", "label": "<?php echo tr('Codice CUP'); ?>", "name": "codice_cup", "required": 0, "value": "$codice_cup$", "maxlength": 15 ]}
				</div>
            </div>
        </div>
    </div>

	<!-- COSTI -->
	<div class="card card-primary">
		<div class="card-header">
			<h3 class="card-title"><?php echo tr('Costi unitari'); ?></h3>
		</div>

		<div class="card-body">
			<div class="row">
				<div class="col-md-12 col-lg-12">
<?php

$idtipiintervento = ['-1'];

// Loop fra i tipi di attività e i relativi costi del tipo intervento
$rs = $dbo->fetchArray('SELECT co_contratti_tipiintervento.*, in_tipiintervento.descrizione FROM co_contratti_tipiintervento INNER JOIN in_tipiintervento ON in_tipiintervento.idtipointervento = co_contratti_tipiintervento.idtipointervento WHERE idcontratto='.prepare($id_record).' AND (co_contratti_tipiintervento.costo_ore != in_tipiintervento.costo_orario OR co_contratti_tipiintervento.costo_km != in_tipiintervento.costo_km OR co_contratti_tipiintervento.costo_dirittochiamata != in_tipiintervento.costo_diritto_chiamata) ORDER BY in_tipiintervento.descrizione');

if (!empty($rs)) {
    echo '
                    <table class="table table-striped table-condensed table-bordered">
                        <tr>
                            <th width="300">'.tr('Tipo attività').'</th>

                            <th>'.tr('Addebito orario').' <span class="tip" title="'.tr('Addebito al cliente').'"><i class="fa fa-question-circle-o"></i></span></th>
                            <th>'.tr('Addebito km').' <span class="tip" title="'.tr('Addebito al cliente').'"><i class="fa fa-question-circle-o"></i></span></th>
                            <th>'.tr('Addebito diritto ch.').' <span class="tip" title="'.tr('Addebito al cliente').'"><i class="fa fa-question-circle-o"></i></span></th>

                            <th width="40"></th>
                        </tr>';

    for ($i = 0; $i < sizeof($rs); ++$i) {
        echo '
                            <tr>
                                <td>'.$rs[$i]['descrizione'].'</td>

                                <td>
                                    {[ "type": "number", "name": "costo_ore['.$rs[$i]['id_tipo_intervento'].']", "value": "'.$rs[$i]['costo_ore'].'" ]}
                                </td>

                                <td>
                                    {[ "type": "number", "name": "costo_km['.$rs[$i]['id_tipo_intervento'].']", "value": "'.$rs[$i]['costo_km'].'" ]}
                                </td>

                                <td>
                                    {[ "type": "number", "name": "costo_dirittochiamata['.$rs[$i]['id_tipo_intervento'].']", "value": "'.$rs[$i]['costo_dirittochiamata'].'" ]}
                                </td>

                                <td>
                                    <button type="button" class="btn btn-warning" data-toggle="tooltip" title="Importa valori da tariffe standard" onclick="if( confirm(\'Importare i valori dalle tariffe standard?\') ){ $.post( \''.$rootdir.'/modules/contratti/actions.php\', { op: \'import\', idcontratto: \''.$id_record.'\', idtipointervento: \''.$rs[$i]['idtipointervento'].'\' }, function(data){ location.href=\''.$rootdir.'/editor.php?id_module='.$id_module.'&id_record='.$id_record.'\'; } ); }">
                                    <i class="fa fa-download"></i>
                                    </button>
                                </td>

                            </tr>';

        $idtipiintervento[] = prepare($rs[$i]['id_tipo_intervento']);
    }
    echo '
                    </table>';
}

echo '
                    <button type="button" onclick="$(this).next().toggleClass(\'hide\');" class="btn btn-info btn-sm"><i class="fa fa-th-list"></i> '.tr('Mostra tipi di attività non modificati').'</button>
					<div class="hide">';

//Loop fra i tipi di attività e i relativi costi del tipo intervento (quelli a 0)
$rs = $dbo->fetchArray('SELECT * FROM co_contratti_tipiintervento INNER JOIN in_tipiintervento ON in_tipiintervento.id = co_contratti_tipiintervento.id_tipo_intervento WHERE co_contratti_tipiintervento.id_tipo_intervento NOT IN('.implode(',', $idtipiintervento).') AND idcontratto='.prepare($id_record).' ORDER BY descrizione');

if (!empty($rs)) {
    echo '
                        <div class="clearfix">&nbsp;</div>
						<table class="table table-striped table-condensed table-bordered">
							<tr>
								<th width="300">'.tr('Tipo attività').'</th>

								<th>'.tr('Addebito orario').' <span class="tip" title="'.tr('Addebito al cliente').'"><i class="fa fa-question-circle-o"></i></span></th>
								<th>'.tr('Addebito km').' <span class="tip" title="'.tr('Addebito al cliente').'"><i class="fa fa-question-circle-o"></i></span></th>
								<th>'.tr('Addebito diritto ch.').' <span class="tip" title="'.tr('Addebito al cliente').'"><i class="fa fa-question-circle-o"></i></span></th>

                                <th width="40"></th>
							</tr>';

    for ($i = 0; $i < sizeof($rs); ++$i) {
        echo '
                            <tr>
                                <td>'.$rs[$i]['descrizione'].'</td>

                                <td>
                                    {[ "type": "number", "name": "costo_ore['.$rs[$i]['id_tipo_intervento'].']", "value": "'.$rs[$i]['costo_orario'].'" ]}
                                </td>

                                <td>
                                    {[ "type": "number", "name": "costo_km['.$rs[$i]['id_tipo_intervento'].']", "value": "'.$rs[$i]['costo_km'].'" ]}
                                </td>

                                <td>
                                    {[ "type": "number", "name": "costo_dirittochiamata['.$rs[$i]['id_tipo_intervento'].']", "value": "'.$rs[$i]['costo_diritto_chiamata'].'" ]}
                                </td>

                                <td>
                                <button type="button" class="btn btn-warning" data-toggle="tooltip" title="Importa valori da tariffe standard" onclick="if( confirm(\'Importare i valori dalle tariffe standard?\') ){ $.post( \''.$rootdir.'/modules/contratti/actions.php\', { op: \'import\', idcontratto: \''.$id_record.'\', idtipointervento: \''.$rs[$i]['idtipointervento'].'\' }, function(data){ location.href=\''.$rootdir.'/editor.php?id_module='.$id_module.'&id_record='.$id_record.'\'; } ); }">
                                    <i class="fa fa-download"></i>
                                </button>
                                </td>

                            </tr>';
    }
    echo '
                        </table>';
}
?>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>


<!-- RIGHE -->
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title"><?php echo tr('Righe'); ?></h3>
    </div>

    <div class="card-body">
<?php
if (!$block_edit) {
    echo '
            <a class="btn btn-sm btn-primary" data-href="'.$structure->fileurl('row-add.php').'?id_module='.$id_module.'&id_record='.$id_record.'&is_articolo" data-toggle="tooltip" data-title="'.tr('Aggiungi articolo').'">
                <i class="fa fa-plus"></i> '.tr('Articolo').'
            </a>';

    echo '
            <a class="btn btn-sm btn-primary" data-href="'.$structure->fileurl('row-add.php').'?id_module='.$id_module.'&id_record='.$id_record.'&is_riga" data-toggle="tooltip" data-title="'.tr('Aggiungi riga').'">
                <i class="fa fa-plus"></i> '.tr('Riga').'
            </a>';

    echo '
            <a class="btn btn-sm btn-primary" data-href="'.$structure->fileurl('row-add.php').'?id_module='.$id_module.'&id_record='.$id_record.'&is_descrizione" data-toggle="tooltip" data-title="'.tr('Aggiungi descrizione').'">
                <i class="fa fa-plus"></i> '.tr('Descrizione').'
            </a>';

    echo '
            <a class="btn btn-sm btn-primary" data-href="'.$structure->fileurl('row-add.php').'?id_module='.$id_module.'&id_record='.$id_record.'&is_sconto" data-toggle="tooltip" data-title="'.tr('Aggiungi sconto/maggiorazione').'">
                <i class="fa fa-plus"></i> '.tr('Sconto/maggiorazione').'
            </a>';
}
?>
        <div class="clearfix"></div>
        <br>

        <div class="row">
            <div class="col-md-12">
<?php

include $docroot.'/modules/contratti/row-list.php';

?>
            </div>
        </div>
    </div>
</div>

{( "name": "filelist_and_upload", "id_module": "$id_module$", "id_record": "$id_record$" )}

{( "name": "log_email", "id_module": "$id_module$", "id_record": "$id_record$" )}

<script type="text/javascript">
    $(document).ready(function(){
        $('#data_accettazione').on("dp.change", function(){
            if($(this).val()){
                $('#data_rifiuto').attr('disabled', true);
            }else{
                $('#data_rifiuto').attr('disabled', false);
            }
        });

        $('#data_rifiuto').on("dp.change", function(){
            if($(this).val()){
                $('#data_accettazione').attr('disabled', true);
            }else{
                $('#data_accettazione').attr('disabled', false);
            }
        });

        $("#data_accettazione").trigger("dp.change");
        $("#data_rifiuto").trigger("dp.change");
    });

	$('#idanagrafica_c').change( function(){
        $("#idsede").selectInfo('idanagrafica', $(this).val());
        $("#idreferente").selectInfo('idanagrafica', $(this).val());

        $("#idsede").selectReset();
        $("#idreferente").selectReset();
	});

	$('#codice_cig, #codice_cup').bind("keyup change", function(e) {

		if ($('#codice_cig').val() == '' && $('#codice_cup').val() == '' ){
			$('#id_documento_fe').prop('required', false);
		}else{
			$('#id_documento_fe').prop('required', true);
		}

	});
</script>

<?php
// Collegamenti diretti
// Fatture collegate a questo contratto
$elementi = $dbo->fetchArray('SELECT `co_documenti`.*, `co_tipidocumento`.`descrizione` AS tipo_documento, `co_tipidocumento`.`dir` FROM `co_documenti` JOIN `co_tipidocumento` ON `co_tipidocumento`.`id` = `co_documenti`.`id_tipo_documento` WHERE `co_documenti`.`id` IN (SELECT `iddocumento` FROM `co_righe_documenti` WHERE `idcontratto` = '.prepare($id_record).') ORDER BY `data`');

if (!empty($elementi)) {
    echo '
<div class="card card-outline card-warning collapsable collapsed-box">
    <div class="card-header">
        <h3 class="card-title"><i class="fa fa-warning"></i> '.tr('Documenti collegati: _NUM_', [
            '_NUM_' => count($elementi),
        ]).'</h3>
        <div class="card-tools float-right">
            <button type="button" class="btn btn-card-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
        </div>
    </div>
    <div class="card-body">
        <ul>';

    foreach ($elementi as $fattura) {
        $descrizione = tr('_DOC_ num. _NUM_ del _DATE_', [
            '_DOC_' => $fattura['tipo_documento'],
            '_NUM_' => !empty($fattura['numero_esterno']) ? $fattura['numero_esterno'] : $fattura['numero'],
            '_DATE_' => Translator::dateToLocale($fattura['data']),
        ]);

        $modulo = ($fattura['dir'] == 'entrata') ? 'Fatture di vendita' : 'Fatture di acquisto';
        $id = $fattura['id'];

        echo '
        <li>'.Modules::link($modulo, $id, $descrizione).'</li>';
    }

    echo '
        </ul>
    </div>
</div>';
}

if (!empty($elementi)) {
    echo '
<div class="alert alert-error">
    '.tr('Eliminando questo documento si potrebbero verificare problemi nelle altre sezioni del gestionale').'.
</div>';
}

?>

<a class="btn btn-danger ask" data-backto="record-list">
    <i class="fa fa-trash"></i> <?php echo tr('Elimina'); ?>
</a>
