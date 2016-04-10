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
class poolAnzeigeControl extends PersistentObject {
	public static function values($Steuerung){
		$elements = array();
		
		$c = array();
		for($i = 100; $i < 115; $i++)
			$c["0".$i] = "Programm ".($i - 99);
		
		$elements["colors"] = $c;
		
		return $elements[$Steuerung];
	}
	
	public static function get(){
		$elements = array();
		
		$e = new stdClass();
		$e->name = "Abdeckung zu";
		$e->class = "manual";
		$e->src = "./img/abdzu-0.svg";
		$e->server = "Euromatik";
		$e->master = "0033";
		$e->value = "1";
		
		$elements[] = $e;
		
		
		$e = new stdClass();
		$e->name = "Abdeckung stoppen";
		$e->class = "manual";
		$e->src = "./img/abdstop.svg";
		$e->server = "Euromatik";
		$e->master = "0033";
		$e->value = "0";
		
		$elements[] = $e;
		
		
		$e = new stdClass();
		$e->name = "Abdeckung auf";
		$e->class = "manual";
		$e->src = "./img/abdauf-0.svg";
		$e->server = "Euromatik";
		$e->master = "0033";
		$e->value = "2";
		
		$elements[] = $e;
		
		
		$e = new stdClass();
		$e->name = "Gegenstrom Stufe 1";
		$e->class = "load";
		$e->src = "./img/gstrom-0.svg";
		$e->server = "Attraktion";
		$e->master = "0017";
		$e->caption = "Stufe 1";
		
		$elements[] = $e;
		
		
		$e = new stdClass();
		$e->name = "Gegenstrom Stufe 2";
		$e->class = "load";
		$e->src = "./img/gstrom-0.svg";
		$e->server = "Attraktion";
		$e->master = "0018";
		$e->caption = "Stufe 2";
		
		$elements[] = $e;
		
		
		$e = new stdClass();
		$e->name = "Massagedüsen";
		$e->class = "load";
		$e->src = "./img/wfall-0.svg";
		$e->server = "Attraktion";
		$e->master = "0021";
		
		$elements[] = $e;
		
		
		$e = new stdClass();
		$e->name = "Licht ein/aus";
		$e->class = "load";
		$e->src = "./img/cc_off.svg";
		$e->server = "Color";
		$e->master = "0008";
		$e->value = "";
		$e->update = "colors,light";
		$e->group = "light";
		
		$elements[] = $e;
		
		
		$e = new stdClass();
		$e->name = "Licht pause";
		$e->class = "load";
		$e->src = "./img/cc_pause.svg";
		$e->server = "Color";
		$e->master = "0009";
		$e->value = "";
		$e->group = "light";
		$e->groupDelay = "7";
		$e->groupDelayIf = "on";
		
		$elements[] = $e;
		
		
		$e = new stdClass();
		$e->name = "Farbe";
		$e->class = "load";
		$e->src = "./img/dsnone.svg";
		$e->server = "Color";
		$e->master = "0100";
		$e->masterValues = "colors";
		$e->update = "colors,light";
		$e->group = "colors";
		$e->label = "Farbe";
		
		$elements[] = $e;
		
		
		$e = new stdClass();
		$e->name = "Wassertemperatur";
		$e->class = "value";
		$e->server = "Euromatik";
		$e->master = "0100";
		$e->caption = "Wassertemperatur";
		
		$elements[] = $e;
		
		
		$e = new stdClass();
		$e->name = "pH-Wert";
		$e->class = "value";
		$e->server = "Messung";
		$e->master = "6";
		$e->caption = "pH-Wert";
		
		$elements[] = $e;
		
		
		$e = new stdClass();
		$e->name = "Chlor-Wert";
		$e->class = "value";
		$e->server = "Messung";
		$e->master = "5";
		$e->caption = "Chlor-Wert";
		
		$elements[] = $e;
		
		return $elements;
	}
}
?>