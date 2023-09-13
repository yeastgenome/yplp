<?php
/**
 * getPicture.php
 *
 * Get a picture
 * @author Florian Leitner <florian.leitner AT student DOT tugraz DOT at>
 * @version 1.0
 * @package YPLP
 * @copyright (c) 2009 Yeast Genetics and Molecular Biology Group University Graz
 */

include'includes/Encryptor.php';

$pfad=$_GET['pfad'];
$crypt->class_obj = new Encryptor;
$pfad = $crypt->class_obj->Decrypt($pfad);
header("Content-Type: image/jpeg");
readfile($pfad); 
?>
