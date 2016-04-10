<?php
/**
 *  This file is part of poolPi.

 *  poolPi is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.

 *  poolPi is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.

 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses></http:>.
 * 
 *  2007 - 2016, Rainer Furtmeier - Rainer@Furtmeier.IT
 */
class poolAnzeigeControlGUI extends poolAnzeigeControl implements iGUIHTML2 {
	function getHTML($id){
		$B = new Button("Element\nlöschen", "trash", "icon");
		$B->rmePCR("poolAnzeigeControl", $this->getID(), "deleteMe", "", OnEvent::closePopup("poolAnzeigeControl").OnEvent::reload("Screen"));
		$B->style("margin:10px;");
		
		$gui = new HTMLGUIX($this);
		$gui->name("Element");
		$gui->displayMode("popupS");
		
		$gui->attributes(array(
			"poolAnzeigeControlCaption",
			"poolAnzeigeControlLabel",
			#"poolAnzeigeControlOrder",
			"poolAnzeigeControlClass",
			"poolAnzeigeControlSrc",
			"poolAnzeigeControlServer",
			"poolAnzeigeControlMaster",
			"poolAnzeigeControlValue",
			"poolAnzeigeControlUpdate",
			"poolAnzeigeControlGroup",
			"poolAnzeigeControlGroupDelay",
			"poolAnzeigeControlGroupDelayIf"
		));
		
		$gui->label("poolAnzeigeControlCaption", "Überschrift");
		$gui->label("poolAnzeigeControlLabel", "Beschriftung");
		$gui->label("poolAnzeigeControlOrder", "Reihenfolge");
		$gui->label("poolAnzeigeControlClass", "Klasse");
		$gui->label("poolAnzeigeControlSrc", "Bild");
		$gui->label("poolAnzeigeControlServer", "Steuerung");
		$gui->label("poolAnzeigeControlGroupDelay", "Verzögerung in s");
		$gui->label("poolAnzeigeControlGroup", "Eigene Gruppe");
		$gui->label("poolAnzeigeControlUpdate", "Update Gruppe(n)");
		$gui->label("poolAnzeigeControlGroupDelayIf", "Wenn");
		
		$gui->type("poolAnzeigeControlServer", "readonly");
		$gui->type("poolAnzeigeControlClass", "select", array("manual" => "Statisch", "load" => "Laden", "value" => "Wert"));
		$gui->type("poolAnzeigeControlGroupDelayIf", "select", array("" => "ohne", "on" => "Ein"));
		if($this->A("poolAnzeigeControlMasterValues") != "")
			$gui->type ("poolAnzeigeControlMaster", "select", poolAnzeigeControl::values ($this->A("poolAnzeigeControlMasterValues")));
		
		$gui->descriptionField("poolAnzeigeControlUpdate", "Mehrere Gruppen durch Komma trennen");
		
		$gui->space("poolAnzeigeControlClass");
		$gui->space("poolAnzeigeControlServer");
		$gui->space("poolAnzeigeControlUpdate");
		
		return $B.$gui->getEditHTML();
	}
}
?>