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

session_name("pool_".sha1(__FILE__));
define("PHYNX_NO_SESSION_RELOCATION", true);

require "./backend/system/connect.php";
require_once './backend/ubiquitous/CustomerPage/CCPage.class.php';
require_once './poolPi.class.php';

$P = new CCPage();
$P->loadPlugin("poolPi", "Steuerung");
$P->loadPlugin("poolPi", "Anzeige");

$servers = "";
$AC = anyC::get("poolSteuerung");
while($S = $AC->n())
	$servers .= ($servers != "" ? ",\n					" : "").$S->A("poolSteuerungTyp").": \"".$S->A("poolSteuerungIP")."\"";

$left = "";
$right = "";

$AC = anyC::get("poolAnzeige", "poolAnzeigeTyp", "row");
$AC->addOrderV3("poolAnzeigeOrder");
$AC->addOrderV3("poolAnzeigeID");

while($A = $AC->n())
	$left .= poolPi::row($A);


$AC = anyC::get("poolAnzeige", "poolAnzeigeTyp", "col");
$AC->addOrderV3("poolAnzeigeOrder");
$AC->addOrderV3("poolAnzeigeID");

$A = $AC->n();
if($A != null)
	$right = poolPi::col($A);
?>

<!doctype html>
<html class="no-js" lang="">
    <head>
        <meta charset="utf-8">
        <title>poolPi</title>
        <meta name="description" content="">
		
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=0">
		
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
		<meta name="mobile-web-app-capable" content="yes" />
		
        <link rel="stylesheet" href="css/normalize.css">
        <link rel="stylesheet" href="css/main.css">
        <link rel="stylesheet" href="css/pace.css">
		
		<script src="js/vendor/jquery-1.11.3.min.js"></script>
		<script src="js/vendor/notify.min.js"></script>
		<script src="js/vendor/pace.min.js"></script>
		<script src="js/plugins.js"></script>
		<script src="js/main.js"></script>
		
		<script type="text/javascript">
			var control = {
				server: {
					<?php echo $servers; ?>
				
				}
			};
			
			
			$(function(){
				poolPi.init();
			});

		</script>
    </head>
    <body>
		<div class="left">
			<?php echo $left; ?>
			<!--<div class="container">
				<div class="rowLabel">Abdeckung</div>
				
				<img class="touch manual" data-server="Euromatik" data-master="0033" data-value="1" src="./img/abdzu-0.svg" />
				<img class="touch manual" data-server="Euromatik" data-master="0033" data-value="0" src="./img/abdstop.svg" />
				<img class="touch manual" data-server="Euromatik" data-master="0033" data-value="2" src="./img/abdauf-0.svg" />
			</div>

			
			<div class="container">
				<div class="rowLabel">Attraktionen</div>
				
				
				<div class="inline">
					<div class="smallLabel">Stufe 1</div>
					<img class="touch load" data-server="Attraktion" data-master="0017" src="./img/gstrom-0.svg" />
				</div>
				
				<div class="inline">
					<div class="smallLabel">Stufe 2</div>
					<img class="touch load" data-server="Attraktion" data-master="0018" src="./img/gstrom-0.svg" />
				</div>
					
				<img class="touch load" data-server="Attraktion" data-master="0021" src="./img/wfall-0.svg" />
			</div>

			<div class="container">
				<div class="rowLabel">Licht</div>
				
				<img class="touch load" data-server="Color" data-group="light" data-update="colors,light" data-master="0008" src="./img/cc_off.svg" />

				<div class="inline">
					<img class="touch load" data-group="colors" data-server="Color" data-master="0101" data-update="colors,light" src="./img/dsnone.svg" />
					<div class="colorLabel">Bunt</div>
				</div>

				<div class="inline">
					<img class="touch load" data-group="colors" data-server="Color" data-master="0113" data-update="colors,light" src="./img/dsnone.svg" />
					<div class="colorLabel">Pastell</div>
				</div>

				<div style="margin-top:1em;">
					<div class="rowLabel">&nbsp;</div>
				
					<img class="touch load" data-server="Color" data-group="light" data-group-delay="7" data-group-delayif="on" data-master="0009" src="./img/cc_pause.svg" />
					
					<div class="inline">
						<img class="touch load" data-group="colors" data-server="Color" data-master="0110" data-update="colors,light" src="./img/dsnone.svg" />
						<div class="colorLabel">Grün</div>
					</div>

					<div class="inline">
						<img class="touch load" data-group="colors" data-server="Color" data-master="0111" data-update="colors,light" src="./img/dsnone.svg" />
						<div class="colorLabel">Blau</div>
					</div>

					<div class="inline">
						<img class="touch load" data-group="colors" data-server="Color" data-master="0114" data-update="colors,light" src="./img/dsnone.svg" />
						<div class="colorLabel">Weiß</div>
					</div>
				</div>
			</div>-->
		</div>
		
		<div class="right">
			<?php echo $right; ?>
			<!--<div class="container">
				<div class="valueLabel">Wassertemperatur</div>
				<div class="value" data-master="0100" data-server="Euromatik"></div>
			</div>

			<div class="container">
				<div class="valueLabel">pH-Wert</div>
				<div class="value" data-master="6" data-server="Messung"></div>
			</div>

			<div class="container">
				<div class="valueLabel">Chlor-Wert</div>
				<div class="value" data-master="5" data-server="Messung"></div>
			</div>-->
		</div>
		
		<div id="darkOverlay" style="display: none; background-color:black;"></div>
    </body>
</html>
