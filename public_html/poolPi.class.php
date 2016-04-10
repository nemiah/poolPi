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

class poolPi {
	public static function col(poolAnzeige $A){
		$html = "";
		
		$AC = anyC::get("poolAnzeigeControl", "poolAnzeigeControlpoolAnzeigeID", $A->getID());
		$AC->addOrderV3("poolAnzeigeControlOrder");
		$AC->addOrderV3("poolAnzeigeControlID");
		while($C = $AC->n())
			$html .= "
				
			<div class=\"container\">".self::control($C)."</div>";
		
		
		return $html;
	}

	public static function row(poolAnzeige $A){
		$html = "
			
			<div class=\"container\">";
		
		$html .= "
				<div class=\"rowLabel\" style=\"height:120px;\">".$A->A("poolAnzeigeName")."</div>";
		
		$close = 0;
		$AC = anyC::get("poolAnzeigeControl", "poolAnzeigeControlpoolAnzeigeID", $A->getID());
		$AC->addOrderV3("poolAnzeigeControlOrder");
		$AC->addOrderV3("poolAnzeigeControlID");
		while($C = $AC->n()){
			if($C->A("poolAnzeigeControlNewLine")){
				$html .= "<div style=\"margin-top:1em;\">
					<div class=\"rowLabel\">&nbsp;</div>";
				$close++;
			}
			$html .= self::control($C);
		}
		
		for($i = 0; $i < $close; $i++)
			$html .= "</div>";
		
		$html .= "
			</div>";
		
		return $html;
	}
	
	public static function control(poolAnzeigeControl $C){
		$B = "";
		$B .= "data-server=\"".$C->A("poolAnzeigeControlServer")."\" ";
		$B .= "data-master=\"".$C->A("poolAnzeigeControlMaster")."\" ";
		
		if($C->A("poolAnzeigeControlGroup") != "")
		$B .= "data-group=\"".$C->A("poolAnzeigeControlGroup")."\" ";
		
		if($C->A("poolAnzeigeControlGroupDelay") != "")
		$B .= "data-group-delay=\"".$C->A("poolAnzeigeControlGroupDelay")."\" ";
		
		if($C->A("poolAnzeigeControlGroupDelayIf") != "")
		$B .= "data-group-delayif=\"".$C->A("poolAnzeigeControlGroupDelayIf")."\" ";
		
		if($C->A("poolAnzeigeControlUpdate") != "")
		$B .= "data-update=\"".$C->A("poolAnzeigeControlUpdate")."\" ";
		
		if($C->A("poolAnzeigeControlValue") != "")
			$B .= "data-value=\"".$C->A("poolAnzeigeControlValue")."\" ";
		
		$B .= "src=\"".$C->A("poolAnzeigeControlSrc")."\" ";
		
		
		if($C->A("poolAnzeigeControlClass") == "value"){
			
			$B .= "class=\"".$C->A("poolAnzeigeControlClass")."\" ";
		
			$smallLabel = "";
			if($C->A("poolAnzeigeControlCaption") != "")
				$smallLabel = "<div class=\"valueLabel\">".$C->A("poolAnzeigeControlCaption")."</div>";
			
			return ($smallLabel != "" ? "
				$smallLabel" : "")."
				<div $B></div>";
		}
		
		$B .= "class=\"touch ".$C->A("poolAnzeigeControlClass")."\" ";
		
		$smallLabel = "";
		if($C->A("poolAnzeigeControlCaption") != "")
			$smallLabel = "<div class=\"smallLabel\">".$C->A("poolAnzeigeControlCaption")."</div>";
		
		$labelLabel = "";
		if($C->A("poolAnzeigeControlLabel") != "")
			$labelLabel = "<div class=\"colorLabel\">".$C->A("poolAnzeigeControlLabel")."</div>";
		
		return "
			
				<div class=\"inline\">".($smallLabel != "" ? "
					$smallLabel" : "")."
					<img $B />".($labelLabel != "" ? "
					$labelLabel" : "")."
				</div>";
	}
}
?>