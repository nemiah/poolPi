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
 *  2007 - 2020, open3A GmbH - Support@open3A.de
 */
class SystemGUI extends System implements iGUIHTML2 {
	function getHTML($id){
		$gui = new HTMLGUIX($this);
		$gui->name("System");
		
		$d = $this->types[$this->A("SystemType")];
		
		$gui->attributes($d->attributes);
		
		foreach($d->labels AS $k => $b)
			$gui->label($k, $b);
		
		$message = "";
		if($this->A("SystemType") == "ip"){
			if($this->A("SystemSetting1") == ""){
				$ips = explode(" ", exec("hostname --all-ip-addresses"));
				
				$this->changeA("SystemSetting1", $ips[0]);
			}
			if($this->A("SystemSetting3") == ""){
				$gw = shell_exec("ip route | grep default | awk {'print $3'}");
				$this->changeA("SystemSetting3", $gw);
			}
			if($this->A("SystemSetting4") == ""){
				$dns = file("/etc/resolv.conf");
				$fdns = "";
				foreach($dns AS $line){
					if(strpos($line, "nameserver") === 0){
						$fdns = trim(str_replace("nameserver ", "", $line));
						break;
					}
						
							
				}
				$this->changeA("SystemSetting4", $fdns);
			}
			
			$B = $gui->addSideButton("Aktuelle\nWerte", "computer");
			$B->popup("", "Aktuelle Werte", "System", $this->getID(), "ipCurrentPopup", "", "", "{width:600}");
			
			$B = $gui->addSideButton("Aktuelle\nEinstellungen", "computer");
			$B->popup("", "Aktuelle Werte", "System", $this->getID(), "ipSettingsPopup", "", "", "{width:600}");
			
			$message = "<p class=\"highlight\">Die Änderungen wirken sich erst nach einem Neustart des Systems aus!</p>";
		}
		return $message.$gui->getEditHTML();
	}
	
	function ipCurrentPopup(){
		echo "<pre style=\"font-size:10px;padding:5px;\">";
		echo shell_exec("ifconfig");
		echo "</pre>";
	}
	
	function ipSettingsPopup(){
		echo "<pre style=\"font-size:10px;padding:5px;\">";
		echo shell_exec("cat /etc/network/interfaces");
		echo "</pre>";
	}
}
?>