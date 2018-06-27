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

/* * ***************************Includes********************************* */
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';

class badger extends eqLogic {
	/*     * *************************Attributs****************************** */

	/*     * ***********************Methode static*************************** */

	public function generateCmds() {
		log::add('badger', 'debug', 'generateCmds');

	}
	
	public function preInsert(){
	
	$pin = rand(0,9999)+10000;
	$stpin = strval($pin);
	$this->setConfiguration('code',substr($stpin,1));
	$this->setConfiguration('type','code');

	$nbcode = 0;
	foreach (badger::byType('badger') as $reader) {
	if ($reader->getConfiguration('type') == 'code') 
		$nbcode++;	
	}
	$readerid = 'CODE' . strval($nbcode);
	$this->setLogicalId($readerid);

	}

	public function postSave() {
				
		if ($this->getConfiguration('type')=='badge')
		{	
			 $cmd = badgerCmd::byEqLogicIdAndLogicalId($this->getId(),'Present');
			if (!is_object($cmd))
				$this->createCmd('Present',$this->getId(),'Present');	
		}

		if ($this->getConfiguration('type')=='code')
		{	
			 $cmd = badgerCmd::byEqLogicIdAndLogicalId($this->getId(),'Present');
			if (!is_object($cmd))
				$this->createCmd('Present',$this->getId(),'Present');	
		}		

	}
	
	public function preRemove() {

		// delete all cmds
		foreach (badgerCmd::byEqLogicId($this->getId())  as $cmd) {
			$cmd->remove();
		}	

	}	
		
	
	public function createCmd($cmdname,$eqlogic,$cmdlogic) {
		
		$cmd = new badgerCmd();
		$cmd->setLogicalId($cmdlogic);
		$cmd->setName($cmdname);
		$cmd->setTemplate('dashboard', 'tile');
		$cmd->setEqLogic_id($eqlogic);
		$cmd->setType('action');
		$cmd->setSubType('message');
		$cmd->save();

	}	

	
	/*     * **********************Getteur Setteur*************************** */
}

class badgerCmd extends cmd {
	/*     * *************************Attributs****************************** */

	/*     * ***********************Methode static*************************** */

	/*     * *********************Methode d'instance************************* */


	
	public function dontRemoveCmd() {
		return true;
	}

	public function execute($_options = array()) {
		return;
	}

	/*     * **********************Getteur Setteur*************************** */
}

?>
