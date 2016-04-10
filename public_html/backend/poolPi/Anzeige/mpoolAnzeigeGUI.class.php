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

class mpoolAnzeigeGUI extends anyC implements iGUIHTMLMP2 {
	
	public function getHTML($id, $page){
		$html =  "<div id=\"preview\" style=\"overflow:auto;background:linear-gradient(to right, #f1f1f1 0%,#f1f1f1 215px,#ffffff 215px,#ffffff 100%);\">";
		
		$html .= "<div style=\"display: inline-block;vertical-align: top;width: calc(100% - 200px);box-sizing: border-box;\">";
		
		$AC = anyC::get("poolAnzeige", "poolAnzeigeTyp", "row");
		$AC->addOrderV3("poolAnzeigeOrder");
		$AC->addOrderV3("poolAnzeigeID");
		
		while($A = $AC->n()){
			$html .= $this->row($A);
		}
		
		$B = new Button("Zeile\nhinzufügen", "new");
		$B->rmePCR("mpoolAnzeige", "-1", "addRow", "", OnEvent::reload("Screen"));
		$B->style("margin:10px;");
		
		$html .= $B;
		
		$html .= "</div><div style=\"display: inline-block;vertical-align: top;width: 190px;box-sizing: border-box;padding-left: 1em;border-left: 1px solid #ddd;\">";
		
		$AC = anyC::get("poolAnzeige", "poolAnzeigeTyp", "col");
		$AC->addOrderV3("poolAnzeigeOrder");
		$AC->addOrderV3("poolAnzeigeID");
		
		$A = $AC->n();
		if($A != null)
			$html .= $this->col($A);
		else {
			$B = new Button("Spalte\nhinzufügen", "new");
			$B->rmePCR("mpoolAnzeige", "-1", "addCol", "", OnEvent::reload("Screen"));
			$B->style("margin:10px;float:right;");

			$html .= $B;
		}
		$html .= "</div></div>";
		
		$html .= OnEvent::script("\$j('#preview').css('height', contentManager.maxHeight());");
		return $html;
	}
	
	private function col(poolAnzeige $A){
		$html = "";
		
		$AC = anyC::get("poolAnzeigeControl", "poolAnzeigeControlpoolAnzeigeID", $A->getID());
		$AC->addOrderV3("poolAnzeigeControlOrder");
		$AC->addOrderV3("poolAnzeigeControlID");
		while($C = $AC->n())
			$html .= $this->control($C);
		
		$B = new Button("Element\nhinzufügen", "new");
		$B->style("margin-top:10px;display:inline-block;vertical-align:top;");
		$B->popup("", "Element hinzufügen", "mpoolAnzeige", "-1", "addElementPopup", array($A->getID()));
		$html .= $B;
		
		$B = new Button("Spalte löschen", "trash_stroke", "iconicL");
		$B->style("margin-top:10px;");
		$B->doBefore("if(confirm('Die Spalte löschen?')) %AFTER");
		$B->rmePCR("poolAnzeige", $A->getID(), "deleteMe", "", OnEvent::reload("Screen"));
		$html .= $B;
		
		return $html;
	}

	private function row(poolAnzeige $A){
		$html = "
			<div style=\"height:120px;padding-top:30px;padding-bottom:25px;border-bottom: 1px solid #ddd;\">";
		
		$I = new HTMLInput("poolAnzeigeName", "text", $A->A("poolAnzeigeName"));
		$I->activateMultiEdit("poolAnzeige", $A->getID());
		$I->style("text-align: right;width:90%;");
		$I->placeholder("Beschriftung");
		
		$B = new Button("Zeile löschen", "trash_stroke", "iconicL");
		$B->style("margin-top:10px;");
		$B->doBefore("if(confirm('Die Zeile löschen?')) %AFTER");
		$B->rmePCR("poolAnzeige", $A->getID(), "deleteMe", "", OnEvent::reload("Screen"));
		
		$html .= "
				<div style=\"text-align: right;color: #777;font-size: 1.5em;display: inline-block;width: 195px;height: auto;vertical-align: top;margin-top: 7px;margin-right: 21px;\">
					$I$B
				</div>";
		
		$AC = anyC::get("poolAnzeigeControl", "poolAnzeigeControlpoolAnzeigeID", $A->getID());
		$AC->addOrderV3("poolAnzeigeControlOrder");
		$AC->addOrderV3("poolAnzeigeControlID");
		while($C = $AC->n())
			$html .= $this->control($C);
		
		$B = new Button("Element\nhinzufügen", "new");
		$B->style("margin:10px;display:inline-block;vertical-align:top;");
		$B->popup("", "Element hinzufügen", "mpoolAnzeige", "-1", "addElementPopup", array($A->getID()));
		$html .= $B;
		
		$html .= "</div>";
		
		return $html;
	}
	
	private function control(poolAnzeigeControl $C){
		$B = new Button($C->A("poolAnzeigeControlName"), str_replace("./img/", "./poolPi/Anzeige/img/", $C->A("poolAnzeigeControlSrc")), "icon");
		$B->editInPopup("poolAnzeigeControl", $C->getID());
		
		if($C->A("poolAnzeigeControlClass") == "value"){
			$smallLabel = "";
			if($C->A("poolAnzeigeControlCaption") != "")
				$smallLabel = "<div style=\"position: relative;margin-top: -10px;width: 180px;box-sizing: border-box;color: #999999;\">".$C->A("poolAnzeigeControlCaption")."</div>";
		
			return "<div style=\"display: inline-block;vertical-align: top;padding-top:30px;padding-bottom:25px;\">$smallLabel<div onclick=\"".$B->getAction()."\" style=\"color: #aaa;cursor:pointer;width: 180px;height:120px;font-weight: bold;font-size: 4.5em;text-align: right;box-sizing: border-box;font-weight: 700;\">X</div></div>";
		}
		
		$smallLabel = "";
		if($C->A("poolAnzeigeControlCaption") != "")
			$smallLabel = "<div style=\"position: relative;height:15px;margin-top: -15px;text-align: center;width: 180px;box-sizing: border-box;color: #999999;\">".$C->A("poolAnzeigeControlCaption")."</div>";
		
		$labelLabel = "";
		if($C->A("poolAnzeigeControlLabel") != "")
			$labelLabel = "<div style=\"height: 80px;margin-top: -80px;padding-left: 10px;padding-right: 10px;cursor: pointer;font-size: 1.8em;text-align: center;width: 180px;box-sizing: border-box;color: #999999;\">".$C->A("poolAnzeigeControlLabel")."</div>";
		
		
		return "<div style=\"display: inline-block;vertical-align: top;\">$smallLabel$B$labelLabel</div>";
	}
	
	public function addRow(){
		$F = new Factory("poolAnzeige");
		$F->sA("poolAnzeigeTyp", "row");
		$F->store();
	}
	
	public function addCol(){
		$F = new Factory("poolAnzeige");
		$F->sA("poolAnzeigeTyp", "col");
		$F->store();
	}
	
	public function addElementPopup($poolAnzeigeID){
		$c = poolAnzeigeControl::get();
		
		$T = new HTMLTable(2);
		$T->weight("light");
		$T->maxHeight(450);
		foreach($c AS $k => $e){
			$B = new Button($e->name, str_replace("./img/", "./poolPi/Anzeige/img/", $e->src), "icon");
			$B->rmePCR("mpoolAnzeige", -1, "addElement", array($poolAnzeigeID, $k), OnEvent::reload("Screen"));
			
			$T->addRow(array($B, $e->name));
		}
		
		echo $T;
	}
	
	public function addElement($poolAnzeigeID, $elementID){
		$F = new Factory("poolAnzeigeControl");
		
		$F->sA("poolAnzeigeControlpoolAnzeigeID", $poolAnzeigeID);
		
		$c = poolAnzeigeControl::get();
		foreach($c[$elementID] AS $k => $e){
			$F->sA("poolAnzeigeControl".  ucfirst($k), $e);
		}
		
		$F->store();
	}
}
?>