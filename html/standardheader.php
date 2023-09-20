<?php
/**
 * standardheader.php
 *
 * the standard header
 * @author Florian Leitner <florian.leitner AT student DOT tugraz DOT at>
 * @version 1.0
 * @package YPLP
 * @copyright (c) 2009 Yeast Genetics and Molecular Biology Group University Graz
 */
include'connect.inc.php';
include'includes/defines.php';

$sub_string = '';
if($_SERVER["PHP_SELF"] == '/'.PATH.'/array_data.php')
{
  $db = $_GET['db'];
  $gene = $_GET['gene'];
  $ssl = $_GET['ssl'];
  $sub_string = 'array_data.php?db='.$db.'&gene='.$gene;
  if($ssl == 'no')
    $ssl_encryption = 'no';
}
if($sub_string == '')
  $sub_string = $_SERVER['QUERY_STRING'];

if($ssl_encryption == 'yes')
  if($_SERVER['SERVER_PORT'] != '443')
    header("Location:https://".$_SERVER['SERVER_NAME']."/".PATH."/".$sub_string);

?>
<?php echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n"; ?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
  <head>
    <meta name="author" content="Institute of Molecular Biosciences, University Graz, FL"/>
    <meta name="copyright" content="All material copyright" />
    <meta name="description" content="" />
    <meta name="distribution" content="global" />
    <meta name="keywords" content="" />
    <meta name="language" content="english" />
    <meta http-equiv="pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <meta http-equiv="cache-control" content="no-cache" />
    <meta name="page-topic" content="" />
    <meta name="revisit-after" content="7 days" />
    <meta name="robots" content="noindex,nofollow" />
    <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
    <title>YPL+</title>
    <?php
      $prefix = 'https';
    ?>
    <link rel="stylesheet" type="text/css" href="<?php echo($prefix.'://'.$_SERVER['SERVER_NAME'].'/'.PATH.'/'); ?>normal.css" />
  </head>
  <body class="hintergrund"> 


    <table border="0" width="100%" class="starttbl">
      <colgroup>
        <col width="70%"></col>
        <col width="33%"></col>
      </colgroup>
      <tr>
        <td>
          <p><span class="header"><a style="color:#FFFFFF;font-size: 14pt;font-weight:bold;" href="index.php">YPL+ Database</a></span>&#160;&#160;&#160;&#160;&#160;&#160;&#160;<span class="header_small">Yeast Protein Localization Plus Database</span>
          <br/>
          <a class="header_url" href="http://www-ang.kfunigraz.ac.at/%7Ekohlwein/home.htm" target="_blank" >Yeast Genetics and Molecular Biology Group (YGMBG) University Graz</a></p>
        </td>
        <td><div class="haupttbl"><a style="color:#FFFFFF;font-size: 10pt" href="manual/default.htm" target="_new">Help</a></div></td>
      </tr>
      <tr>
        <td><div class="haupttbl">&#160;</div></td>
        <td><div class="haupttbl">&#160;</div></td>
      </tr>
    </table>
    <br />
    
