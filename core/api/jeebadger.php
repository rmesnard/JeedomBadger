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
require_once dirname(__FILE__) . "/../../../../core/php/core.inc.php";
/*
if (init('apikey') != config::byKey('api') || config::byKey('api') == '') {
	connection::failed();
	echo 'Clef API non valide, vous n\'etes pas autorisé à effectuer cette action (jeeApi)';
	die();
}
*/
$ip = init('ip');
$cmd = init('cmd');
$value = init('value');
$name = init('name');
$model = init('model');

$readerid = $name;

log::add('badger', 'debug', 'APi received ');

//  /plugins/badger/core/api/jeebadger.php?apikey=...&name=BADGER1&ip=192.168.0.177&id=12345678&model=wiegand1&cmd=tag&value=70605040


$elogic = badger::byLogicalId($readerid, 'badger');
if (!is_object($elogic)) {

	if (config::byKey('allowAllinclusion', 'badger') != 1) {
		// Gestion des lecteurs inconnus
		log::add('badger', 'error', 'Lecteur inconnu detecté IP : '.$ip.' MODEL : '.$model);
		return true;
	}
	// Ajout du lecteur de badge si il n'existe pas et discover actif
	$elogic = new badger();
	$elogic->setEqType_name('badger');
	$elogic->setLogicalId($readerid);
	$elogic->setName($name);
	$elogic->setConfiguration('ip',$ip);
	$elogic->setConfiguration('model',$model);
	$elogic->setConfiguration('type','reader');
	$elogic->setCategory('security', 1);		
	$elogic->save();
	$elogic->generateCmds();

} else {
	// Mise a jours des infos du lecteur de badge 
	if (($model != $elogic->getConfiguration('model')) | ($ip != $elogic->getConfiguration('ip')) ) {
		$elogic->setConfiguration('ip',$ip);
		$elogic->setConfiguration('model',$model);
		
		$elogic->save();
	}	
}

$readername = $elogic->getName();

if($cmd == "tag")
{
	$badgeid = "BADGE".$value;
	
	// Badge Présenté au lecteurs , ajout de ce badge si il n'existe pas et include actif
	$elogic = badger::byLogicalId($badgeid, 'badger');
	
	if (!is_object($elogic)) {
		
	
		if (config::byKey('allowAllinclusion', 'badger') != 1) {
			// Gestion des tags inconnus
			log::add('badger', 'error', 'Badge : '.$value.' inconnu présenté sur le lecteur :'.$readername);
		
			// Update or Create Counter of wrong tag
/*			
			$elogic = badger::byLogicalId('BAD_TAG_COUNTER', 'badger');
			if (!is_object($elogic)) {
				$elogic = new badger();
				$elogic->setEqType_name('badger');
				$elogic->setLogicalId('BAD_TAG_COUNTER');
				$elogic->setName('Erreurs de badge');
				$elogic->setConfiguration('type','counter');
				$elogic->setConfiguration('maxalerte',5);
				$elogic->setConfiguration('maxblocking',20);
				$elogic->setCategory('security', 1);		
				$elogic->save();
			}

			$eqcounter = badgerCmd::byEqLogicIdAndLogicalId($elogic->getId(),'BAD_TAG_COUNTER_CMD');
			if (!is_object($eqcounter))
			{
				$eqcounter = new badgerCmd();
				$eqcounter->setLogicalId('BAD_TAG_COUNTER_CMD');
				$eqcounter->setName('Compteur');
				$eqcounter->setTemplate('dashboard', 'tile');
				$eqcounter->setEqLogic_id($elogic->getId());
				$eqcounter->setType('info');
				$eqcounter->setSubType('numeric');
				$eqcounter->setValue(0);	
				$eqcounter->save();
			}	
			
			$tagcounter = $eqcounter->getValue();
			$tagcounter++;
			$eqcounter->setValue($tagcounter);				
			$eqcounter->setCollectDate($datetime);
			$eqcounter->save();
			$elogic->setConfiguration('compteur',$tagcounter);
			$elogic->save();
*/			
			return true;
		}

		// Ajout du badge si il n'existe pas et include actif
		$elogic = new badger();
		$elogic->setEqType_name('badger');
		$elogic->setLogicalId($badgeid);
		$elogic->setName('Badge '.$value);
		$elogic->setConfiguration('model','Tag RFID');
		$elogic->setConfiguration('type','badge');
		$elogic->setConfiguration('value',$value);
		$elogic->setCategory('security', 1);		
		$elogic->save();
		$elogic->generateCmds();
	}
	else  {
		// le badge existe process des commandes
		$datetime = date('Y-m-d 00:00:00');
		log::add('badger', 'info', 'Badge :'.$elogic->getName().' présenté sur le lecteur : '.$readername);
		
		foreach ( badgerCmd::byLogicalId('TAG_'.$elogic->getId()) as $cmd) {
			$cmd->setCollectDate($datetime);
			$cmd->event(0);				
		}
	}	
}


if($cmd == "pin")
{
	$badgeid = "CODE".$value;
	// PIN Présenté au lecteurs 
	$elogic = badger::byLogicalId($badgeid, 'badger');
	
	if (!is_object($elogic)) {

		// Gestion des tags inconnus
		log::add('badger', 'error', 'CODE : '.$value.' faux présenté sur le lecteur :'.$readername);
	
		// Update or Create Counter of wrong tag
/*		
		$elogic = badger::byLogicalId('BAD_PIN_COUNTER', 'badger');
		if (!is_object($elogic)) {
			$elogic = new badger();
			$elogic->setEqType_name('badger');
			$elogic->setLogicalId('BAD_PIN_COUNTER');
			$elogic->setName('Codes Faux');
			$elogic->setConfiguration('type','counter');
			$elogic->setConfiguration('maxalerte',5);
			$elogic->setConfiguration('maxblocking',20);
			$elogic->setCategory('security', 1);		
			$elogic->save();
		}

		$eqcounter = badgerCmd::byEqLogicIdAndLogicalId($elogic->getId(),'BAD_PIN_COUNTER_CMD');
		if (!is_object($eqcounter))
		{
			$eqcounter = new badgerCmd();
			$eqcounter->setLogicalId('BAD_PIN_COUNTER_CMD');
			$eqcounter->setName('Compteur');
			$eqcounter->setTemplate('dashboard', 'tile');
			$eqcounter->setEqLogic_id($elogic->getId());
			$eqcounter->setType('info');
			$eqcounter->setSubType('numeric');
			$eqcounter->setValue(0);	
			$eqcounter->save();
		}	
		
		$tagcounter = $eqcounter->getValue();
		$tagcounter++;
		$eqcounter->setValue($tagcounter);				
		$eqcounter->setCollectDate($datetime);
		$eqcounter->save();
		$elogic->setConfiguration('compteur',$tagcounter);
		$elogic->save();
*/
	}
	else  {
		// process des commandes
		$datetime = date('Y-m-d 00:00:00');
		log::add('badger', 'info', 'Code :'.$elogic->getName().' présenté sur le lecteur : '.$readername);
		
		foreach ( badgerCmd::byLogicalId('CODE_'.$elogic->getId()) as $cmd) {
			$cmd->setCollectDate($datetime);
			$cmd->event(0);				
		}
	}	
}


return true;
?>
