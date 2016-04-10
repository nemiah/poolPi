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
		</div>
		
		<div class="right">
			<?php echo $right; ?>
		</div>
		
		<div id="darkOverlay" style="display: none; background-color:black;"></div>
    </body>
</html>
