<?php
/**
 * info_gene.php
 *
 * Retrives data
 * @author Florian Leitner <florian.leitner AT student DOT tugraz DOT at>
 * @version 1.0
 * @package YPLP
 * @copyright (c) 2009 Yeast Genetics and Molecular Biology Group University Graz
 */

// include the search-function
include'includes/functions_helpers.php';

echo <<<HTML
<?xml version="1.0" encoding="iso-8859-1" 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
  <head>
    <meta name="author" content=""/>
    <meta name="copyright" content="All material copyright" />
    <meta name="description" content="" />
    <meta name="distribution" content="global" />
    <meta name="keywords" content="" />
    <meta name="audience" content="" />
    <meta name="language" content="" />
    <meta name="expires" content="" />
    <meta name="page-topic" content="" />
    <meta name="revisit-after" content="7 days" />
    <meta name="robots" content="index,follow" />
    <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
    <title>Genes</title>
    <link rel="stylesheet" type="text/css" href="normal.css" />
  </head>
  <body class="tblstandardfuellung"> 
HTML;

$gene = $_GET['gene'];
if($gene == "")
 die();
$buffer_txt = '';
$description_txt = '';
$handle = fopen ("https://www.yeastgenome.org/locus/".$gene, "r");
while (!feof($handle))
  $buffer_txt .= fgets($handle, 4096);
fclose ($handle);
$description_txt = find_inside("<dt>Description</dt>", "<span data-tooltip aria-haspopup=", $buffer_txt);
$description_txt = substr($description_txt[0],8);
$description_txt = str_replace("<dd>", "", $description_txt);
$standard_name = find_inside("<dt>Standard Name</dt>", "<span data-tooltip aria-haspopup=", $buffer_txt);
$standard_name = trim($standard_name[0]);
$standard_name = str_replace("<dd>", "", $standard_name);

if($standard_name == '')
  $standard_name = 'n/a';
if($description_txt != '')
{
echo <<<HTML
<table>
  <tr>
    <td class="tblstandardfuellung">
      <div class="ueberschrifttxt">
        Description by <a href="https://www.yeastgenome.org/locus/$gene" target="_new">Saccharomyces Genome Database</a>&#169;:<br/>
        Stanardname: $standard_name <br/>
        $description_txt
      </div>
    </td>
  </tr>
</table>
HTML;
    }
echo <<<HTML
  </body>
</html>
HTML;
?>
