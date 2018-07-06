<?php
/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';
include_file('core', 'authentification', 'php');
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
?>
<form class="form-horizontal">
    <fieldset>
         <legend><i class="fa fa-list-alt"></i> {{Général}}</legend>
      <div class="form-group">
        <label class="col-lg-4 control-label">{{Autoriser l'inclusion de devices inconnus}}</label>
        <div class="col-lg-3">
           <input type="checkbox" class="configKey" data-l1key="allowAllinclusion" />
       </div>
	</div>
	<div class="form-group">
			<label class="col-lg-5 control-label">{{Sketch Arduino}}</label>
            <div class="col-lg-4">
			     <a class="btn btn-default" href="/../arduino/BadgerENC28J60/BadgerENC28J60.ino"><i class="fa fa-cloud-download"></i> {{Sketch pour Arduino + ENC28J60}}</a>
				 <a class="btn btn-default" href="/../arduino/BadgerW5100/BadgerW5100.ino"><i class="fa fa-cloud-download"></i> {{Sketch pour Arduino + W5100}}</a>
				 <a class="btn btn-default" href="/../arduino/JeedouinoLAN/JeedouinoLAN.ino"><i class="fa fa-cloud-download"></i> {{Sketch Jeedouino modifié}}</a>
				</div>  
	</div>
   </fieldset>
</form>

<script>
 $('.changeLogLive').on('click', function () {
	 $.ajax({// fonction permettant de faire de l'ajax
            type: "POST", // methode de transmission des données au fichier php
            url: "plugins/blea/core/ajax/blea.ajax.php", // url du fichier php
            data: {
                action: "changeLogLive",
				level : $(this).attr('data-log')
            },
            dataType: 'json',
            error: function (request, status, error) {
                handleAjaxError(request, status, error);
            },
            success: function (data) { // si l'appel a bien fonctionné
                if (data.state != 'ok') {
                    $('#div_alert').showAlert({message: data.result, level: 'danger'});
                    return;
                }
                $('#div_alert').showAlert({message: '{{Réussie}}', level: 'success'});
            }
        });
});
</script>
