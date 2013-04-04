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

if (!empty($_FILES)) {
	$tempFile = $_FILES['image']['tmp_name'];
	
	$targetPath = './uploads';
	$fileTypes = array('jpg','jpeg','gif','png'); // File extensions
	
	$fileParts = pathinfo($_FILES['image']['name']);
	
	$filename = getImagename().'.'.$fileParts['extension'];
	$targetFile = rtrim($targetPath,'/') . '/' . $filename;
	
	if (in_array($fileParts['extension'],$fileTypes)) {
		move_uploaded_file($tempFile,$targetFile);
		$image_info = getimagesize('uploads/'.$filename);
		echo json_encode(array(true, $filename, $image_info[0], $image_info[1]));
	} else {
		echo json_encode(array(false, 'Error: Invalid file type.'));
	}
}

function getImagename() {
	$val = '';
	for($i = 0; $i < 5; $i++)
		$val .= getRandomSign();
	$val = substr(hash('md5', $val), 10, 20);
	$val .= substr(hash('sha1', time()), 0, 6);
	for($i = 0; $i < 6; $i++)
		$val .= getRandomSign();
	return $val;
}

function getRandomSign() {
	$sign = array_merge(range ('a', 'z'), range ('A', 'Z'), range (0, 9));
	return $sign[mt_rand(0, 61)];
}
	
?>