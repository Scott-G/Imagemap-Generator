<?php
/*
 * HTML Imagemap Generator
 * build with PHP, jQuery Maphighlight and CSS3
 * Since 	1/2013
 * Version	v1.1.1
 * by		Dario D. Müller
 * 			http://dariodomi.de
 * License	Distributed under the Lesser General Public License (LGPL)
 * 			http://www.gnu.org/copyleft/lesser.html
 */

session_start();


// eigentlich json_decode, funktioniert jedoch nicht auf dem 1&1 server. result immer null -.-

//$values = json_decode($_POST['data']);
//$_SESSION['image'] = array($values[1], $values[2], $values[3]);


// work-around



$parts = explode('[', $_POST['data']); 		// [true,"34902e08fd6f0c4e4b92a4a67bSa9tgb.jpg",461,272]
$parts = explode(']', $parts[1]); 			// true,"34902e08fd6f0c4e4b92a4a67bSa9tgb.jpg",461,272]
$parts = explode(',', $parts[0]);			// true,"34902e08fd6f0c4e4b92a4a67bSa9tgb.jpg",461,272
											// true	"34902e08fd6f0c4e4b92a4a67bSa9tgb.jpg"	461	272
$part2 = explode('\"', $parts[1]);

//var_dump($parts);
//var_dump($part2[1]);

$_SESSION['image'] = array($part2[1], $parts[2], $parts[3]);

//var_dump($_SESSION['image']);

?>