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
	
	if ( $this->getConfiguration('type') == null )
		{
		$pin = rand(0,9999)+10000;
		$stpin = strval($pin);
		$this->setConfiguration('code',substr($stpin,1));
		$this->setConfiguration('type','code');

		$nbcode = 0;
		foreach (badger::byType('badger') as $reader) {
		if ($reader->getConfiguration('type') == 'code') 
			$nbcode++;	
		}
		$readerid = 'CODE ' . strval($nbcode);
		$this->setLogicalId($readerid);
		}
	}

	public function postSave() {
				
		// delete all cmds
	/*	foreach (badgerCmd::byEqLogicId($this->getId())  as $cmd) {
			$cmd->remove();
		}	
	*/
		if (($this->getConfiguration('type')=='badge')|($this->getConfiguration('type')=='code'))
		{	
			 $cmd = badgerCmd::byEqLogicIdAndLogicalId($this->getId(),'Presentation');
			if (!is_object($cmd))
				$this->createCmdinfo('Presentation',$this->getId(),'Presentation');	
	
			 $cmd = badgerCmd::byEqLogicIdAndLogicalId($this->getId(),'BadgerID');
			if (!is_object($cmd))
				$this->createCmdinfo('BadgerID',$this->getId(),'BadgerID');	
		}

		if ($this->getConfiguration('type')=='code')
		{	
			 $cmd = badgerCmd::byEqLogicIdAndLogicalId($this->getId(),'ChangePin');
			if (!is_object($cmd))
				$this->createCmdmessage('ChangePin',$this->getId(),'ChangePin');	
			/*
			http://xxxxx/core/api/jeeApi.php?apikey=xxxxxx&type=cmd&id=427&title=set&message=1234
			*/
		}

		if ($this->getConfiguration('type')=='reader')
		{
			 $cmd = badgerCmd::byEqLogicIdAndLogicalId($this->getId(),'TagTryLimit');
			if (!is_object($cmd))
				$this->createCmdinfo('TagTryLimit',$this->getId(),'TagTryLimit');	

			if ($this->getConfiguration('model','')=='wiegand2' ){
				 $cmd = badgerCmd::byEqLogicIdAndLogicalId($this->getId(),'PinTryLimit');
				if (!is_object($cmd))
					$this->createCmdinfo('PinTryLimit',$this->getId(),'PinTryLimit');				
			}

		}		

	}

	public function preSave() {


		if ($this->getConfiguration('type')=='reader')
		{

			if ($this->getIsEnable()==false)
			{
				$this->setConfiguration('tagcount','0');
				if ($this->getConfiguration('model','')=='wiegand2' )
					$this->setConfiguration('pincount','0');
			}
		}

	}	
	
	public function preRemove() {

		// delete all cmds
		foreach (badgerCmd::byEqLogicId($this->getId())  as $cmd) {
			$cmd->remove();
		}	

	}	
		
		
	public function createCmdinfo($cmdname,$eqlogic,$cmdlogic) {
		
		$cmd = new badgerCmd();
		$cmd->setLogicalId($cmdlogic);
		$cmd->setName($cmdname);
		$cmd->setTemplate('dashboard', 'tile');
		$cmd->setEqLogic_id($eqlogic);
		$cmd->setType('info');
		$cmd->setSubType('string');
		$cmd->save();

	}	

	public function createCmdmessage($cmdname,$eqlogic,$cmdlogic) {
		
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

	if (isset($_options['title']) && isset($_options['message'])) {
		$eqlogic = $this->getEqLogic();
		if ( $_options['title']=='set' )
			$pincode = $_options['message'];
		else if ( $_options['title']=='rnd' ){
			$pin = rand(0,9999)+10000;
			$stpin = strval($pin);
			$pincode =substr($stpin,1);
		}
		else
			return;

		$eqlogic->setConfiguration('code',$pincode);
		$eqlogic->save();
		return ($pincode);
		}

		return;
	}

	/*     * **********************Getteur Setteur*************************** */
}

?>
