<?php
/**
 *  This file is part of ubiquitous.

 *  ubiquitous is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.

 *  ubiquitous is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.

 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses></http:>.
 * 
 *  2007 - 2016, Rainer Furtmeier - Rainer@Furtmeier.IT
 */

class mSystemGUI extends anyC implements iGUIHTMLMP2 {
	
	public function getHTML($id, $page){
		$this->loadMultiPageMode($id, $page, 0);

		$gui = new HTMLGUIX($this);
		$gui->version("mSystem");

		$gui->name("System");
		
		$gui->attributes(array());
		
		$gui->options(true, true, false);
		
		$B = $gui->addSideButton("Neue\nEinstellung", "new");
		$B->popup("", "Neue Einstellung", "mSystem", "-1", "addSettingPopup");
		
		$B = $gui->addSideButton("System\nneu starten", "./ubiquitous/System/cog.png");
		$B->popup("", "System neu starten", "mSystem", "-1", "reboot");
		
		return $gui->getBrowserHTML($id);
	}

	public function reboot(){
		echo exec("sudo reboot");
	}
	
	public function addSettingPopup(){
		$T = new HTMLTable(2);
		$T->setColWidth(1, 20);
		$T->useForSelection(false);
		
		$S = new System(1);
		
		$B = new Button("Eintrag erstellen", "./images/i2/cart.png", "icon");
		foreach($S->types AS $k => $v){
			$T->addRow(array(
				$B,
				$v->name
			));

			$T->addRowEvent("click", OnEvent::rme($this, "addSettingNew", array("'$k'"), "function(t){ ".OnEvent::closePopup("mSystem").OnEvent::reload("Right")." contentManager.loadFrame('contentLeft', 'System', t.responseText); }"));		
		}
		
		echo $T;
	}
	
	public function addSettingNew($type){
		$F = new Factory("System");
		
		$F->sA("SystemType", $type);
		
		echo $F->store();
	}

}
?>