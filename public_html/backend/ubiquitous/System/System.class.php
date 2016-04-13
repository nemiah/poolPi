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
class System extends PersistentObject {

	public $types = array();
	public function __construct($id) {
		parent::__construct($id);
		
		$ip = new stdClass();
		$ip->attributes = array("SystemSetting1"/*, "SystemSetting2"*/, "SystemSetting3", "SystemSetting4");
		$ip->labels = array(
			"SystemSetting1" => "IP-Adresse", 
			#"SystemSetting2" => "Netzmaske", 
			"SystemSetting3" => "Gateway", 
			"SystemSetting4" => "DNS-Server"
		);
		$ip->name = "IP-Adresse";
		
		$this->types["ip"] = $ip;
	}
	
	public function deleteMe() {
		if($this->A("SystemType") == "ip"){
			$data = "
hostname
clientid
persistent
option rapid_commit

option domain_name_servers, domain_name, domain_search, host_name
option classless_static_routes
option ntp_servers
require dhcp_server_identifier

slaac private

nohook lookup-hostname
";
			echo shell_exec("sudo 'echo \"$data\" > /etc/dhcpcd.conf 2>&1");
		}
		
		return parent::deleteMe();
	}
	
	public function saveMe($checkUserData = true, $output = false) {
		
		if($this->A("SystemType") == "ip"){
			$data = "
hostname
clientid
persistent
option rapid_commit

option domain_name_servers, domain_name, domain_search, host_name
option classless_static_routes
option ntp_servers
require dhcp_server_identifier

slaac private

nohook lookup-hostname

interface eth0
static ip_address=".$this->A("SystemSetting1")."/24
static routers=".$this->A("SystemSetting3")."
static domain_name_servers=".$this->A("SystemSetting4")."
";
			echo "sudo 'echo \"$data\" > /etc/dhcpcd.conf'";
			echo shell_exec("sudo 'echo \"$data\" > /etc/dhcpcd.conf' 2>&1");
		}
		
		parent::saveMe($checkUserData, $output);
		
	}
}
?>