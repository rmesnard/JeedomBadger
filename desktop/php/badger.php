<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}

$plugin = plugin::byId('badger');
sendVarToJS('eqType', $plugin->getId());
$eqLogics = eqLogic::byType($plugin->getId());

?>




<div class="row row-overflow">
	<div class="col-lg-2 col-md-3 col-sm-4">
		<div class="bs-sidebar">
			<ul id="ul_eqLogic" class="nav nav-list bs-sidenav">
				<a class="btn btn-warning " style="width : 100%;margin-top : 5px;margin-bottom: 5px;" href="/index.php?v=d&p=plugin&id=badger">
					<i class="fa fa-cogs"></i> {{Configuration du plugin}} 
				</a>   
				<a class="btn btn-warning " style="width : 100%;margin-top : 5px;margin-bottom: 5px;" href="/index.php?v=d&p=log&logfile=badger">
					<i class="fa fa-comment"></i> {{Logs du plugin}} 
				</a> 	  
				<li class="filter" style="margin-bottom: 5px;"><input class="filter form-control input-sm" placeholder="{{Rechercher}}" style="width: 100%"/></li>
				<legend><i class="fa fa-cog"></i> {{Lecteurs}}</legend>
				<?php
						foreach ($eqLogics as $eqLogic) {
						if ( $eqLogic->getConfiguration('type') == 'reader'  ){
							$opacity = ($eqLogic->getIsEnable()) ? '' : jeedom::getConfiguration('eqLogic:style:noactive');
							echo '<li class="cursor li_eqLogic" data-eqLogic_id="' . $eqLogic->getId() . '" style="' . $opacity . '"><a>' . $eqLogic->getHumanName(true) . '</a></li>';
							}
						}
						?>
				<legend><i class="fa fa-cog"></i> {{Badges}}</legend>
				<?php
						foreach ($eqLogics as $eqLogic) {
						if ( $eqLogic->getConfiguration('type') == 'badge'  ){
							$opacity = ($eqLogic->getIsEnable()) ? '' : jeedom::getConfiguration('eqLogic:style:noactive');
							echo '<li class="cursor li_eqLogic" data-eqLogic_id="' . $eqLogic->getId() . '" style="' . $opacity . '"><a>' . $eqLogic->getHumanName(true) . '</a></li>';
							}
						}
						?>
				<legend><i class="fa fa-cog"></i> {{Codes}}</legend>
				<?php
						foreach ($eqLogics as $eqLogic) {
						if ( $eqLogic->getConfiguration('type') == 'code'  ){
							$opacity = ($eqLogic->getIsEnable()) ? '' : jeedom::getConfiguration('eqLogic:style:noactive');
							echo '<li class="cursor li_eqLogic" data-eqLogic_id="' . $eqLogic->getId() . '" style="' . $opacity . '"><a>' . $eqLogic->getHumanName(true) . '</a></li>';
							}
						}
						?>
			</ul>
		</div>
	</div>

	<div class="col-lg-10 col-md-9 col-sm-8 eqLogicThumbnailDisplay" style="border-left: solid 1px #EEE; padding-left: 25px;">
		<legend><i class="fa fa-cog"></i> {{Configuration}}</legend> 



		<div class="eqLogicThumbnailContainer">
			<div class="cursor eqLogicAction" data-action="gotoPluginConf" style="background-color : #ffffff; height : 140px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;">
				<center>
					<i class="fa fa-wrench" style="font-size : 6em;color:#00979C;"></i>
				</center>
				<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#00979C"><center>{{Configuration}}</center></span>
			</div>		
			<div class="cursor eqLogicAction" style="background-color : #ffffff; height : 140px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;">
				<a target="_blank" style="text-decoration: none!important;" href="https://github.com/rmesnard/JeedomBadger/blob/master/doc/fr_FR/index.asciidoc">
					<center>
						<i class="fa fa-book" style="font-size : 6em;color:#00979C;"></i>
					</center> 
					<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#00979C"><center>{{Documentation}}</center></span>
				</a>
			</div>	


	  <?php
	if (config::byKey('allowAllinclusion', 'badger', 0) == 0) 
		echo '<div class="cursor startIncludeState include card" data-state="1" style="background-color : #ffffff; height : 140px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >';
	else 
		echo '<div class="cursor startIncludeState include card" data-state="1" style="background-color : #ffffff; height : 140px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;display: none;" >';
	?>	

		<center>
		<i class="fa fa-sign-in fa-rotate-90" style="font-size : 6em;color:#00979C;"></i>
		</center>
		<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#00979C"><center>{{Mode inclusion}}</center></span>
	</div>

  <?php

	if (config::byKey('allowAllinclusion', 'badger', 0) == 1) 
		echo '<div class="cursor stopIncludeState include card" data-state="0" style="background-color : #ffffff; height : 140px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >';
	else 
		echo '<div class="cursor stopIncludeState include card" data-state="0" style="background-color : #ffffff; height : 140px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;display: none;" >';
	?>	

		<center>
		<i class="fa fa-sign-in fa-rotate-90" style="font-size : 6em;color:#ff1111;"></i>
		</center>
		<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#00979C"><center>{{Arrêter inclusion}}</center></span>
	</div>


		</div>

		<legend><i class="fa fa-table"></i> {{Lecteurs}}
		</legend>
		<div class="eqLogicThumbnailContainer">
			<?php
				foreach ($eqLogics as $eqLogic) {

				if ( $eqLogic->getConfiguration('type') == 'reader'  ){
						$opacity = ($eqLogic->getIsEnable()) ? '' : jeedom::getConfiguration('eqLogic:style:noactive');
						echo '<div class="eqLogicDisplayCard cursor" data-eqLogic_id="' . $eqLogic->getId() . '" style="background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;' . $opacity . '" >';
						echo "<center>";
						if ( $eqLogic->getConfiguration('modelReader') == 'wiegand1'  )
							echo '<img src="plugins/badger/doc/images/wiegand1.png" height="105" width="95" />';
						else
							echo '<img src="plugins/badger/doc/images/wiegand2.png" height="105" width="95" />';
						echo "</center>";
						echo '<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;"><center>' . $eqLogic->getHumanName(true, true) . '</center></span>';
						echo '</div>';
						}
				}
				?>
		</div>
		<legend><i class="fa fa-table"></i> {{Badges}}
		</legend>
		<div class="eqLogicThumbnailContainer">
			<?php
				foreach ($eqLogics as $eqLogic) {

				if ( $eqLogic->getConfiguration('type') == 'badge'  ){
						$opacity = ($eqLogic->getIsEnable()) ? '' : jeedom::getConfiguration('eqLogic:style:noactive');
						echo '<div class="eqLogicDisplayCard cursor" data-eqLogic_id="' . $eqLogic->getId() . '" style="background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;' . $opacity . '" >';
						echo "<center>";
						if ( $eqLogic->getConfiguration('modelTag') == 'Tag RFID'  )
							echo '<img src="plugins/badger/doc/images/badge1.png" height="105" width="95" />';
						elseif (( $eqLogic->getConfiguration('modelTag') == 'Carte RFID'  ) | ( $eqLogic->getConfiguration('modelTag') == 'Carte NFC'  ) ) 
							echo '<img src="plugins/badger/doc/images/badge2.png" height="105" width="95" />';
						elseif ( $eqLogic->getConfiguration('modelTag') == 'Tag NFC'  ) 
							echo '<img src="plugins/badger/doc/images/badge3.png" height="105" width="95" />';
						elseif ( $eqLogic->getConfiguration('modelTag') == 'Sticker'  )
							echo '<img src="plugins/badger/doc/images/badge4.png" height="105" width="95" />';
						elseif ( $eqLogic->getConfiguration('modelTag') == 'Mobile'  )
							echo '<img src="plugins/badger/doc/images/badge5.png" height="105" width="95" />';
						elseif ( $eqLogic->getConfiguration('modelTag') == 'Bague NFC'  )
							echo '<img src="plugins/badger/doc/images/badge6.png" height="105" width="95" />';
						else
							echo '<img src="plugins/badger/doc/images/badge1.png" height="105" width="95" />';

						echo "</center>";
						echo '<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;"><center>' . $eqLogic->getHumanName(true, true) . '</center></span>';
						echo '</div>';
						}
				}
				?>
		</div>
		<legend><i class="fa fa-table"></i> {{Codes}}
		</legend>
		<div class="eqLogicThumbnailContainer">
			<div class="cursor eqLogicAction" data-action="add" style="background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >
				<center>
					<i class="fa fa-plus-circle" style="font-size : 7em;color:#00979C;"></i>
				</center>
				<span style="font-size : 1.1em;position:relative; top : 23px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#00979C"><center>Ajouter</center></span>
			</div>
			<?php
				foreach ($eqLogics as $eqLogic) {

				if ( $eqLogic->getConfiguration('type') == 'code'  ){
						$opacity = ($eqLogic->getIsEnable()) ? '' : jeedom::getConfiguration('eqLogic:style:noactive');
						echo '<div class="eqLogicDisplayCard cursor" data-eqLogic_id="' . $eqLogic->getId() . '" style="background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;' . $opacity . '" >';
						echo "<center>";
						echo '<img src="plugins/badger/doc/images/code.png" height="105" width="95" />';
						echo "</center>";
						echo '<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;"><center>' . $eqLogic->getHumanName(true, true) . '</center></span>';
						echo '</div>';
						}
				}
				?>
		</div>
	</div>

	<!-- Affichage de l'eqLogic sélectionné -->

	<div class="col-lg-10 col-md-9 col-sm-8 eqLogic" style="border-left: solid 1px #EEE; padding-left: 25px;display: none;">
		<legend>
			<i class="fa fa-arrow-circle-left eqLogicAction cursor" data-action="returnToThumbnailDisplay"></i> {{Général}}
			<i class='fa fa-cogs eqLogicAction pull-right cursor expertModeVisible' data-action='configure'></i>
		</legend>
		
		<a class="btn btn-success eqLogicAction pull-right" data-action="save"><i class="fa fa-check-circle"></i> {{Sauvegarder la configuration}}</a>
		<a class="btn btn-danger eqLogicAction pull-right" data-action="remove"><i class="fa fa-minus-circle"></i> {{Supprimer l'équipement}}</a>

				<form class="form-horizontal">
					<fieldset>						
					
						<div class="form-group">
							<label class="col-sm-2 control-label">{{Nom de l'équipement}}</label>
							<div class="col-sm-2">
								<input type="text" class="eqLogicAttr form-control" data-l1key="name"  />
								<input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;" />
								<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="type" style="display : none;" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" >{{Objet parent}}</label>
							<div class="col-sm-2">
								<select id="sel_object" class="eqLogicAttr form-control" data-l1key="object_id">
									<option value="">{{Aucun}}</option>
									<?php
											foreach (object::all() as $object) {
												echo '<option value="' . $object->getId() . '">' . $object->getName() . '</option>';
											}
											?>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label"></label>
							<div class="col-sm-8">
								<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked="true" />{{Activer}}</label>
								<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked="true" />{{Visible}}</label>
							</div>
						</div>

						<div class="showreader">

							<div class="form-group">
								<label class="col-sm-2 control-label">{{ID}}</label>
								<div class="col-sm-2">
									<input id="inp_P" type="text" class="eqLogicAttr form-control" data-l1key="logicalId" readonly="true" />
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label">{{IP / Hostname}}</label>
								<div class="col-sm-2">
									<input id="inp_P" type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="ip" readonly="true" />
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label">{{Model}}</label>
								<div class="col-sm-2">
									<select id="sel_modelTag" class="eqLogicAttr form-control input-sm" data-l1key="configuration" data-l2key="modelReader">
									<option value="wiegand1">{{Lecteur RFID}}</option>
									<option value="wiegand2">{{Lecteur RFID + PinPad}}</option>
								</select>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label">{{Temps de blocage}}</label>
								<div class="col-sm-2">
									<input id="inp_P" type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="retrytimer" placeholder="1" />
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label">{{Compteur de badge inconnu}}</label>
								<div class="col-sm-2">
									<input id="inp_P" type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="tagcount" readonly="true" />
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label">{{Limite de badge inconnu}}</label>
								<div class="col-sm-2">
									<input id="inp_P" type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="tagtrylimit" placeholder="1" />
								</div>
							</div>

						</div>

						<div class="showpinreader">

							<div class="form-group">
								<label class="col-sm-2 control-label">{{Compteur de code faux}}</label>
								<div class="col-sm-2">
									<input id="inp_P" type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="pincount" readonly="true" />
								</div>
							</div>


							<div class="form-group">
								<label class="col-sm-2 control-label">{{Limite de code faux}}</label>
								<div class="col-sm-2">
									<input id="inp_P" type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="pintrylimit" placeholder="3" />
								</div>
							</div>

						</div>

						<div class="showtag">

							<div class="form-group">
								<label class="col-sm-2 control-label">{{Model}}</label>
								<div class="col-sm-2">
									<select id="sel_modelTag" class="eqLogicAttr form-control input-sm" data-l1key="configuration" data-l2key="modelTag">
									<option value="Tag RFID">{{Tag RFID}}</option>
									<option value="Carte RFID">{{Carte RFID}}</option>
									<option value="Sticker">{{Sticker}}</option>
									<option value="Mobile">{{Mobile}}</option>
									<option value="Carte NFC">{{Carte NFC}}</option>
									<option value="Bague NFC">{{Bague NFC}}</option>
								</select>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label">{{Valeur}}</label>
								<div class="col-sm-2">
									<input id="inp_P" type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="value" readonly="true" />
								</div>
							</div>
						</div>

						<div class="showcode">
							<div class="form-group">
								<label class="col-sm-2 control-label">{{Code}}</label>
								<div class="col-sm-2">
									<input id="inp_P" type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="code"  />
								</div>
							</div>
						</div>

					</fieldset>
				</form>

			</div>

				
			</div>



		</div>
	</div>
</div>


<?php include_file('desktop', 'badger', 'js', 'badger');?>
<?php include_file('core', 'plugin.template', 'js');?>