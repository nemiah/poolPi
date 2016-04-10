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
class poolSteuerung extends PersistentObject {
	public static $types = array("Euromatik"=> "Euromatik", "Color" => "Color", "Attraktion" => "Attraktion", "Messung" => "Messung");
	
	function newMe($checkUserData = true, $output = false) {
		$S = anyC::getFirst("poolSteuerung", "poolSteuerungTyp", $this->A("poolSteuerungTyp"));
		if($S !== null)
			Red::errorD ("Jeder Steuerungstyp darf maximal einmal angelegt werden.");
		
		return parent::newMe($checkUserData, $output);
	}
	
	function saveMe($checkUserData = true, $output = false) {
		$AC = anyC::get("poolSteuerung", "poolSteuerungTyp", $this->A("poolSteuerungTyp"));
		$AC->addAssocV3("poolSteuerungID", "!=", $this->getID());
		$S = $AC->n();
		if($S !== null)
			Red::errorD ("Jeder Steuerungstyp darf maximal einmal angelegt werden.");
		
		
		return parent::saveMe($checkUserData, $output);
	}
}
?>