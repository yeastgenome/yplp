<?php
/**
 * array_data.php
 *
 * Retrives data from the database and prints it to the screen
 * @author Florian Leitner <florian.leitner AT student DOT tugraz DOT at>
 * @version 1.0
 * @package YPLP
 * @copyright (c) 2009 Yeast Genetics and Molecular Biology Group University Graz
 */
/**
 * Define YPLP
 */

// get the header for the html output
include'standardheader.php';

// get the encryptor class for encrypting some strings (e.g. the path to a picture)
include'includes/Encryptor.php';
$crypt->class_obj = new Encryptor;

// to make the database-name clear
include'includes/helpers.php';

// a couple of defines
include'includes/defines.php';

// functions for logging a user
include'includes/functions_log.php';

//get ip and hide the last 3 digits of it
$ip_address = substr(getUserIP(), 0, -3)."xxx";

//TODO: check if the HTTP_REFERER is valid and plausible
$http_referer = $_SERVER['HTTP_REFERER'];
$log_id = 0;
$log_id = get_last_insert_id();
if(check_ip_active($ip_address, "30") == false)
{
  $log_id++;
  add_log($log_id, $ip_address, time(), $http_referer);
}else{
  $log_id = logid_to_ip($ip_address);
}

$data_username = "";

echo '
<table border="0" width="1142">
  <colgroup>
    <col width="450" />
    <col width="10" />
    <col width="512" />
    <col width="10" />
    <col width="160" />
  </colgroup>
  <tr class="tbllinks">
    <td>
      <div>';

$delete_tbl = "";
if (isset($_GET["delete_tbl"]))
  $delete_tbl = $_GET['delete_tbl'];

$tbl_id_to_delete = "";
if (isset($_GET["tbl_id_to_delete"]))
  $tbl_id_to_delete = $_GET['tbl_id_to_delete'];
$tbl_id_to_delete = preg_replace("/[^a-zA-Z0-9_#]/", "", $tbl_id_to_delete);

$gene = "";
if (isset($_GET["gene"]))
  $gene = strtoupper($_GET['gene']);
$gene = preg_replace("/[^a-zA-Z0-9_\-. ]/", "", $gene);

$search_gene = "";
if (isset($_GET["search_gene"]))
  $search_gene = strtoupper($_GET['search_gene']);
$search_gene = preg_replace("/[^a-zA-Z0-9_\- ]/", "", $search_gene);

$tbl_id = "";
if (isset($_GET["tbl_id"]))
  $tbl_id = $_GET['tbl_id'];
$tbl_id = preg_replace("/[^a-zA-Z0-9_#\-]/", "", $tbl_id);

$limit = "";
if (isset($_GET["limit"]))
  $limit = $_GET['limit'];
$limit = preg_replace("/[^0-9]/", "", $limit);

$pics_flex = explode('-',$tbl_id);

$tbl_name = TBL_PREFIX.$tbl_id;

$tbl_public = "1";

if($gene == '*')
  $gene = '';

if($gene == '')
  $search_gene = 'ALL';

if($search_gene == '')
  $search_gene = $gene;

$search_gene_log = '';
if($search_gene == 'ALL')
  $search_gene_log = $gene;
else
  $search_gene_log = $search_gene;

add_detail($log_id, time(), $search_gene_log);

$showgallery = "";
if (isset($_GET["showgallery"]))
  $showgallery = $_GET['showgallery'];
$showgallery = preg_replace("/[^a-zA-Z]/", "", $showgallery);

$searchwhere = "";
if (isset($_GET["searchwhere"]))
  $searchwhere = $_GET['searchwhere'];
$searchwhere = preg_replace("/[^a-zA-Z0-9_*]/", "", $searchwhere);

$showinfos = "";
if (isset($_GET["showinfos"]))
  $showinfos = $_GET['showinfos'];
$showinfos = preg_replace("/[^a-zA-Z0-9_\-]/", "", $showinfos);

if(($searchwhere == '') || ($searchwhere == '*'))
  $searchwhere = '';

$resultsperpage = "";
if (isset($_GET["resultsperpage"]))
  $resultsperpage = $_GET['resultsperpage'];
$resultsperpage = preg_replace("/[^0-9]/", "", $resultsperpage);
if($resultsperpage == '')
  $resultsperpage = '20';

$showonepicture = "";
if (isset($_GET["showonepicture"]))
  $showonepicture = $_GET['showonepicture'];
$showonepicture = preg_replace("/[^a-zA-Z]/", "", $showonepicture);
if($showonepicture == 'yes')
  $showgallery == 'yes';
?>
<form action="<?php $PHP_SELF ?>" method="get" name="searchform">
  <table border="0" width="100%">
    <colgroup>
      <col width="100%" />
    </colgroup>
    <tr>
      <td class="ueberschrifttbl">
        <div class="ueberschrifttxt">Search:<a href="manual/manual2.htm#2_1" target="_blank">[Help]</a></div>
      </td>
    </tr>
    <tr>
      <td class="tblstandardfuellung">
        <table width="100%" class="tblstandardfuellung" >
          <colgroup>
            <col width="20%" />
            <col width="80%" />
          </colgroup>
          <tr>
            <td align="right">
              <div class="standardtext">Gene/Localisation:</div>
            </td>
            <td>
              <div class="standardtext">
                <input type="hidden" name="data_username" value="<?php echo($data_username); ?>"></input>
                <input name="limit" value="" type="hidden"></input>
                <input class="standardtext" type="text" name="gene" size="29" value="<?php if(($_GET['gene'] != '')  && ($_GET['search_gene'] != 'ALL')){ echo($search_gene); }else{ echo'*'; } ?>"></input>
              </div>
            </td>
          </tr>
          <tr>
            <td align="right">
              <div class="standardtext">&#160;</div>
            </td>
            <td>
              <div class="standardtext">
                <input name="limit" value="" type="hidden"></input>
                <input type="hidden" name="data_username" value="<?php echo($data_username); ?>"></input>
                <input type="hidden" name="tbl_id" value="<?php echo(preg_replace("/[^a-zA-Z0-9_\-]/", "", $_GET['tbl_id'])); ?>"></input>
                <input type="hidden" name="searchwhere" value="<?php echo(preg_replace("/[^a-zA-Z0-9_\-]/", "", $_GET['searchwhere'])); ?>"></input>
              </div>
            </td>
          </tr>
          <tr>
            <td align="right">
              <div class="standardtext">Results/Page:</div>
            </td>
            <td>
              <input type="hidden" name="data_username" value="<?php echo($data_username); ?>"></input>
              <input type="hidden" name="limit" value="<?php echo($limit); ?>"></input>
              <input type="hidden" name="orderby" value="<?php echo(preg_replace("/[^a-zA-Z0-9_]/", "", $_GET['orderby'])); ?>"></input>
              <input type="hidden" name="tbl_id" value="<?php echo(preg_replace("/[^a-zA-Z0-9_\-]/", "", $_GET['tbl_id'])); ?>"></input>
              <input type="hidden" name="showinfos" value="<?php echo(preg_replace("/[^a-zA-Z0-9_\-]/", "", $_GET['showinfos'])); ?>"></input>
              <select class="standardtext" name="resultsperpage" size="1" tabindex="11" onchange="this.form.submit();"> 
                <option <?php if($resultsperpage == '10'){ echo'selected="selected"'; } ?> value="10">10</option>
                <option <?php if($resultsperpage == '20'){ echo'selected="selected"'; } ?> value="20">20</option>
                <option <?php if($resultsperpage == '30'){ echo'selected="selected"'; } ?> value="30">30</option>
                <option <?php if($resultsperpage == '40'){ echo'selected="selected"'; } ?> value="40">40</option>
                <option <?php if($resultsperpage == '50'){ echo'selected="selected"'; } ?> value="50">50</option>
                <option <?php if($resultsperpage == '60'){ echo'selected="selected"'; } ?> value="60">60</option>
                <option <?php if($resultsperpage == '70'){ echo'selected="selected"'; } ?> value="70">70</option>
                <option <?php if($resultsperpage == '500'){ echo'selected="selected"'; } ?> value="500">500</option>
              </select>&#160;
              <input class="standardtext" type="submit" value="Search"></input>&#160;
              <input class="standardtext" type="reset" value="Clear"></input>
            </td>
          </tr>
          <tr>
            <td align="right">
              <div class="standardtext">&#160;</div>
            </td>
            <td>
              <div class="standardtext">
                <input class="standardtext" type="checkbox" <?php if($showgallery == 'yes') { echo'checked="checked"'; } ?> name="showgallery" value="yes" onclick="this.form.submit();"></input>Show Gallery everytime
              </div>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</form>
</div>
<br />
            <table border="0" width="100%">
              <colgroup>
               <col width="100%" />
              </colgroup>
              <tr>
                <td class="ueberschrifttbl">
                  <div class="ueberschrifttxt">Result:<a href="manual/manual2.htm#2_2" target="_blank">[Help]</a></div>
                </td>
              </tr>
            </table>
            <table width="100%" >
              <colgroup>
               <col width="20%" />
               <col width="20%" />
               <col width="20%" />
               <col width="20%" />
               <col width="20%" />
              </colgroup>
              <tr class="tblstandardfuellungmitte">
                <td>
                  <div class="standardtext"><a href="?tbl_id=<?php echo($tbl_id); ?>&amp;showinfos=<?php echo($showinfos); ?>&amp;searchwhere=<?php echo($searchwhere); ?>&amp;gene=<?php echo($gene); ?>&amp;ge_id=<?php echo($ge_id); ?>&amp;orderby=gen_name&amp;resultsperpage=<?php echo($resultsperpage); ?>&amp;limit=<?php echo($limit); ?>&amp;showgallery=<?php echo($showgallery); ?>&amp;search_gene=<?php echo($search_gene); ?>&amp;data_username=<?php echo($data_username); ?>">Gene</a></div>
                </td>
                <td>
                  <div class="standardtext"><a href="?tbl_id=<?php echo($tbl_id); ?>&amp;showinfos=<?php echo($showinfos); ?>&amp;searchwhere=<?php echo($searchwhere); ?>&amp;gene=<?php echo($gene); ?>&amp;ge_id=<?php echo($ge_id); ?>&amp;orderby=ge_name&amp;resultsperpage=<?php echo($resultsperpage); ?>&amp;limit=<?php echo($limit); ?>&amp;showgallery=<?php echo($showgallery); ?>&amp;search_gene=<?php echo($search_gene); ?>&amp;data_username=<?php echo($data_username); ?>">Name</a></div>
                </td>
                <td>
                  <div class="standardtext"><a href="?tbl_id=<?php echo($tbl_id); ?>&amp;showinfos=<?php echo($showinfos); ?>&amp;searchwhere=<?php echo($searchwhere); ?>&amp;gene=<?php echo($gene); ?>&amp;ge_id=<?php echo($ge_id); ?>&amp;orderby=ge_feld1&amp;resultsperpage=<?php echo($resultsperpage); ?>&amp;limit=<?php echo($limit); ?>&amp;showgallery=<?php echo($showgallery); ?>&amp;search_gene=<?php echo($search_gene); ?>&amp;data_username=<?php echo($data_username); ?>">Field 1</a></div>
                </td>
                <td>
                  <div class="standardtext"><a href="?tbl_id=<?php echo($tbl_id); ?>&amp;showinfos=<?php echo($showinfos); ?>&amp;searchwhere=<?php echo($searchwhere); ?>&amp;gene=<?php echo($gene); ?>&amp;ge_id=<?php echo($ge_id); ?>&amp;orderby=ge_feld2&amp;resultsperpage=<?php echo($resultsperpage); ?>&amp;limit=<?php echo($limit); ?>&amp;showgallery=<?php echo($showgallery); ?>&amp;search_gene=<?php echo($search_gene); ?>&amp;data_username=<?php echo($data_username); ?>">Field 2</a></div>
                </td>
                <td>
                  <div class="standardtext"><a href="?tbl_id=<?php echo($tbl_id); ?>&amp;showinfos=<?php echo($showinfos); ?>&amp;searchwhere=<?php echo($searchwhere); ?>&amp;gene=<?php echo($gene); ?>&amp;ge_id=<?php echo($ge_id); ?>&amp;orderby=ge_feld3&amp;resultsperpage=<?php echo($resultsperpage); ?>&amp;limit=<?php echo($limit); ?>&amp;showgallery=<?php echo($showgallery); ?>&amp;search_gene=<?php echo($search_gene); ?>&amp;data_username=<?php echo($data_username); ?>">Field 3</a></div>
                </td>
              </tr>

              <?php
              $limit = $_GET['limit'];
              $limit = preg_replace("/[^0-9]/", "", $limit);

              if($gene == '*')
              {
                $gene = '';
              }

              $searchwhere = $_GET['searchwhere'];
              $searchwhere = preg_replace("/[^a-zA-Z0-9_*]/", "", $searchwhere);
              $orderby = $_GET['orderby'];

              if(($orderby != 'gen_name') && ($orderby != 'ge_name') && ($orderby != 'ge_feld1') && ($orderby != 'ge_feld2') && ($orderby != 'ge_feld3'))
                $orderby = 'gen_name';

              $resultsperpage = $_GET['resultsperpage'];

              if (!$limit) 
              {
                $limit = 1;
              }

              if($resultsperpage == '')
              {
                $resultsperpage = '20';
              }

              //TODO
              //if(check_table_exist($tbl_name) == false)
              //  die('Database doesn\'t exist.');

              $db_query_string = "SELECT PICTURE_TOTAL FROM $tbl_name";
              $db_query = pg_query($db_connection, $db_query_string);
              $db_result = pg_fetch_array($db_query, 0);
              $picture_total_tmp = $db_result["picture_total"];

              if($search_gene == 'ALL')
              {
                $search_gene = '';
                $search_gene_tmp = 'ALL';
              }

              if($data_username == '')
              {
                if((($gene == '')) && (($searchwhere == '') || ($searchwhere == '*')))
                {
                  $gene = '';
                  $ge_name = '';
                  $query_gene = 'SELECT nr, gen_name, gen_pfad, picture_number, ge_id, ge_name, ge_feld1, ge_feld2, ge_feld3 FROM '.$tbl_name.' ';
                }elseif($gene == '*'){
                  if($searchwhere == 'gen_name'){
                    $query_gene = 'SELECT * FROM '.$tbl_name.' WHERE GEN_NAME) != NULL';               
                  }elseif($searchwhere == 'ge_name'){
                    $query_gene = 'SELECT * FROM '.$tbl_name.' WHERE GE_NAME != NULL';               
                  }elseif($searchwhere == 'ge_feld1'){
                    $query_gene = 'SELECT * '.$tbl_name.' WHERE ge_feld1 != NULL';               
                  }elseif($searchwhere == 'ge_feld2'){
                    $query_gene = 'SELECT * '.$tbl_name.' WHERE ge_feld2 != NULL';               
                  }elseif($searchwhere == 'ge_feld3'){
                    $query_gene = 'SELECT * '.$tbl_name.' WHERE ge_feld3 != NULL';               
                  }elseif($searchwhere == '*'){
                    $query_gene = 'SELECT * '.$tbl_name.''; 
                  }
                }else{
                  if($searchwhere == 'gen_name'){
                    $query_gene = "SELECT * FROM ".$tbl_name." WHERE GEN_NAME LIKE '%".$search_gene."%'";               
                  }elseif($searchwhere == 'ge_name'){
                    $query_gene = "SELECT * FROM ".$tbl_name." WHERE GE_NAME LIKE '%".$search_gene."%'";               
                  }elseif($searchwhere == 'ge_feld1'){
                    $query_gene = "SELECT * FROM ".$tbl_name." WHERE ge_feld1 LIKE '%".$search_gene."%'";               
                  }elseif($searchwhere == 'ge_feld2'){
                    $query_gene = "SELECT * FROM ".$tbl_name." WHERE ge_feld2 LIKE '%".$search_gene."%'";               
                  }elseif($searchwhere == 'ge_feld3'){
                    $query_gene = "SELECT * FROM ".$tbl_name." WHERE ge_feld3 LIKE '%".$search_gene."%'";               
                  }elseif(($searchwhere == '*') || ($searchwhere == '')){
                    $query_gene = "SELECT * FROM ".$tbl_name." WHERE (GEN_NAME LIKE '%".$search_gene."%') OR (GE_NAME LIKE '%".$search_gene."%') OR (ge_feld1 LIKE '%".$search_gene."%') OR (ge_feld2 LIKE '%".$search_gene."%') OR (ge_feld3 LIKE '%".$search_gene."%')";               
                  }
                }
              }else{
                if((($gene == '')) && (($searchwhere == '') || ($searchwhere == '*')))
                {
                  $gene = '';
                  $ge_name = '';
                  $query_gene = "SELECT nr, gen_name, gen_pfad, ge_id, ge_name, ge_feld1, ge_feld2, ge_feld3, PICTURE_NUMBER FROM $tbl_name WHERE USERNAME = '".$user_id."'";
                }elseif($gene == '*'){
                  if($searchwhere == 'gen_name'){
                    $query_gene = "SELECT * FROM ".$tbl_name." WHERE GEN_NAME != NULL AND USERNAME = '".$user_id."'";               
                  }elseif($searchwhere == 'ge_name'){
                    $query_gene = "SELECT * FROM ".$tbl_name." WHERE GE_NAME != NULL AND USERNAME = '".$user_id."'";               
                  }elseif($searchwhere == 'ge_feld1'){
                    $query_gene = "SELECT * ".$tbl_name." WHERE ge_feld1 != NULL AND USERNAME = '".$user_id."'";               
                  }elseif($searchwhere == 'ge_feld2'){
                    $query_gene = "SELECT * FROM ".$tbl_name." WHERE ge_feld2 != NULL AND USERNAME = '".$user_id."'";               
                  }elseif($searchwhere == 'ge_feld3'){
                    $query_gene = "SELECT * FROM ".$tbl_name." WHERE ge_feld3 != NULL AND USERNAME = '".$user_id."'";               
                  }elseif($searchwhere == '*'){
                    $query_gene = "SELECT * FROM ".$tbl_name." WHERE USERNAME = '".$user_id."'"; 
                  }
                }else{
                  if($searchwhere == 'gen_name'){
                    $query_gene = "SELECT * FROM ".$tbl_name." WHERE GEN_NAME LIKE '%".$search_gene."%' AND USERNAME = '".$user_id."'";               
                  }elseif($searchwhere == 'ge_name'){
                    $query_gene = "SELECT * FROM ".$tbl_name." WHERE GE_NAME LIKE '%".$search_gene."%' AND USERNAME = '".$user_id."'";               
                  }elseif($searchwhere == 'ge_feld1'){
                    $query_gene = "SELECT * FROM ".$tbl_name." WHERE ge_feld1 LIKE '%".$search_gene."%' AND USERNAME = '".$user_id."'";               
                  }elseif($searchwhere == 'ge_feld2'){
                    $query_gene = "SELECT * FROM ".$tbl_name." WHERE ge_feld2 LIKE '%".$search_gene."%' AND USERNAME = '".$user_id."'";               
                  }elseif($searchwhere == 'ge_feld3'){
                    $query_gene = "SELECT * FROM ".$tbl_name." WHERE ge_feld3 LIKE '%".$search_gene."%' AND USERNAME = '".$user_id."'";               
                  }elseif(($searchwhere == '*') || ($searchwhere == '')){
                    $query_gene = "SELECT * FROM ".$tbl_name." WHERE ((GEN_NAME LIKE '%".$search_gene."%') OR (GE_NAME LIKE '%".$search_gene."%') OR (ge_feld1 LIKE '%".$search_gene."%') OR (ge_feld2 LIKE '%".$search_gene."%') OR (ge_feld3 LIKE '%".$search_gene."%')) AND USERNAME = '".$user_id."'";               
                  }
                }
              }

              if($search_gene_tmp == 'ALL')
              {
                $search_gene = 'ALL';
              }

              $db_query_string = "SELECT COUNT(*) FROM ($query_gene) AS rows";
              $db_query = pg_query($db_connection, $db_query_string);
              $db_result = pg_fetch_array($db_query, 0);
              $rowsfound = $db_result["count"];

              if(($rowsfound/$picture_total_tmp) == '1')
              {
                $limit = '1';
              }
              $limitmax = $limit+($resultsperpage*$picture_total_tmp)-$picture_total_tmp;
              $db_query = pg_query($db_connection, $query_gene . ' ORDER BY '.$orderby.' ASC');
              $rowcount = 0;
              $firstgene = 'no';
              while ($db_result = pg_fetch_array($db_query))
              {
                $rowcount++;
                if(($rowcount >= $limit) && ($rowcount <= $limitmax+1))
                {
                  if($db_result["picture_number"] == '1')
                  {
                    $gen_name_temp = strtoupper($db_result["gen_name"]);
                    if(($_GET['newsite'] == 'yes') && ($firstgene != 'yes'))
                    {
                      $gene = strtoupper($db_result["gen_name"]);
                      $firstgene = 'yes';
                    }
                    ?>
  
                    <tr>
                      <td class="tblrandergebnisse">
                      <div class="<?php if(strtoupper($db_result["gen_name"]) == $gene){ echo'highlight'; }else{ echo'standardtext'; } ?>"><?php if(strtoupper($db_result["gen_name"]) == ''){ echo'&#160;'; } ?><a href="?gene=<?php if(strtoupper($db_result["gen_name"]) != ''){ echo(strtoupper($db_result["gen_name"]));echo('&amp;showinfos='.$showinfos.'&amp;orderby='.$orderby.'&amp;limit='.$limittmp.'&amp;searchwhere='.$searchwhere.'&amp;resultsperpage='.$resultsperpage.'&amp;showgallery='.$showgallery.'&amp;search_gene='.$search_gene.'&amp;tbl_id='.$tbl_id.'&amp;limit='.$limit.'&amp;data_username='.$data_username); } ?>"><?php echo(strtoupper($db_result["gen_name"])); ?></a></div>
                      </td>
                      <td class="tblrandergebnisse">
                      <div class="<?php if(strtoupper($db_result["gen_name"]) == $gene){ echo'highlight'; }else{ echo'standardtext'; } ?>"><?php if($db_result["ge_name"] == ''){ echo'&#160;'; } ?><a title="<?php echo($db_result["ge_name"]); ?>" href="?gene=<?php if(strtoupper($db_result["gen_name"]) != ''){ echo(strtoupper($db_result["gen_name"]));echo('&amp;showinfos='.$showinfos.'&amp;orderby='.$orderby.'&amp;limit='.$limittmp.'&amp;searchwhere='.$searchwhere.'&amp;resultsperpage='.$resultsperpage.'&amp;showgallery='.$showgallery.'&amp;search_gene='.$search_gene.'&amp;tbl_id='.$tbl_id.'&amp;limit='.$limit); } ?>&amp;resultsperpage=<?php echo($resultsperpage); ?>&amp;data_username=<?php echo($data_username); ?>&amp;showgallery=<?php echo($showgallery); ?>"><?php if(strlen($db_result["ge_name"]) > 11 ){ echo(substr($db_result["ge_name"], 0, 8).'...'); }else{ echo($db_result["ge_name"]); } ?></a></div>
                      </td>
                      <td class="tblrandergebnisse">
                      <div class="<?php if(strtoupper($db_result["gen_name"]) == $gene){ echo'highlight'; }else{ echo'standardtext'; } ?>"><?php if($db_result["ge_feld1"] == ''){ echo'&#160;'; } ?><a title="<?php echo($db_result["ge_feld1"]); ?>" href="?gene=<?php if(strtoupper($db_result["gen_name"]) != ''){ echo(strtoupper($db_result["gen_name"]));echo('&amp;showinfos='.$showinfos.'&amp;orderby='.$orderby.'&amp;limit='.$limittmp.'&amp;searchwhere='.$searchwhere.'&amp;resultsperpage='.$resultsperpage.'&amp;showgallery='.$showgallery.'&amp;search_gene='.$search_gene.'&amp;tbl_id='.$tbl_id.'&amp;limit='.$limit); } ?>&amp;resultsperpage=<?php echo($resultsperpage); ?>&amp;data_username=<?php echo($data_username); ?>&amp;showgallery=<?php echo($showgallery); ?>"><?php if(strlen($db_result["ge_feld1"]) > 11 ){ echo(substr($db_result["ge_feld1"], 0, 8).'...'); }else{ echo($db_result["ge_feld1"]); } ?></a></div>
                      </td>
                      <td class="tblrandergebnisse">
                      <div class="<?php if(strtoupper($db_result["gen_name"]) == $gene){ echo'highlight'; }else{ echo'standardtext'; } ?>"><?php if($db_result["ge_feld2"] == ''){ echo'&#160;'; } ?><a title="<?php echo($rdb_result["ge_feld2"]); ?>" href="?gene=<?php if(strtoupper($db_result["gen_name"]) != ''){ echo(strtoupper($db_result["gen_name"]));echo('&amp;showinfos='.$showinfos.'&amp;orderby='.$orderby.'&amp;limit='.$limittmp.'&amp;searchwhere='.$searchwhere.'&amp;resultsperpage='.$resultsperpage.'&amp;showgallery='.$showgallery.'&amp;search_gene='.$search_gene.'&amp;tbl_id='.$tbl_id.'&amp;limit='.$limit); } ?>&amp;data_username=<?php echo($data_username); ?>&amp;showgallery=<?php echo($showgallery); ?>"><?php if(strlen($db_result["ge_feld2"]) > 11 ){ echo(substr($db_result["ge_feld2"], 0, 8).'...'); }else{ echo($db_result["ge_feld2"]); } ?></a></div>
                      </td>
                      <td class="tblrandergebnisse">
                      <div class="<?php if(strtoupper($db_result["gen_name"]) == $gene){ echo'highlight'; }else{ echo'standardtext'; } ?>"><?php if($db_result["ge_feld3"] == ''){ echo'&#160;'; } ?><a title="<?php echo($db_result["ge_feld3"]); ?>" href="?gene=<?php if(strtoupper($db_result["gen_name"]) != ''){ echo(strtoupper($db_result["gen_name"]));echo('&amp;showinfos='.$showinfos.'&amp;orderby='.$orderby.'&amp;limit='.$limittmp.'&amp;searchwhere='.$searchwhere.'&amp;resultsperpage='.$resultsperpage.'&amp;showgallery='.$showgallery.'&amp;search_gene='.$search_gene.'&amp;tbl_id='.$tbl_id.'&amp;limit='.$limit); } ?>&amp;data_username=<?php echo($data_username); ?>&amp;resultsperpage=<?php echo($resultsperpage); ?>&amp;showgallery=<?php echo($showgallery); ?>"><?php if(strlen($db_result["ge_feld3"]) > 11 ){ echo(substr($db_result["ge_feld3"], 0, 8).'...'); }else{ echo($db_result["ge_feld3"]); } ?></a></div>
                      </td>
                    </tr>

                    <?php
                  }
                }
              }
              ?>
 
            </table>
            <table width="100%" >
              <colgroup>
               <col width="100%" />
              </colgroup>
              <tr class="tblergebnissengmitte">
                <td>
                  <div class="standardtext">
                    <table width="100%" >
                      <colgroup>
                        <col width="50%" />
                        <col width="50%" />
                      </colgroup>
                      <tr>
                        <td>
                          <?php
                            $db_query_string = "SELECT COUNT(*) FROM ($query_gene) AS rows";
                            $db_query = pg_query($db_connection, $db_query_string);
                            $db_result = pg_fetch_array($db_query, 0);
                            $anzahl = $db_result["count"];
                            {
                              $weiter = $nb+$resultsperpage*$picture_total_tmp;
                              $seiten_z1 = $anzahl/($resultsperpage*$picture_total_tmp); 
                              $seiten_z2 = ceil($seiten_z1); 
                            }
                          $previous_site = $limit - $weiter;
                          if($previous_site >= 1)
                            echo "<div class=\"tblstandardfuellunglinks\"><a href='?showinfos=".$showinfos."&amp;newsite=yes&amp;orderby=".$orderby."&amp;limit=$previous_site&amp;tbl_id=".$tbl_id."&amp;gene=".$gene."&amp;searchwhere=".$searchwhere."&amp;resultsperpage=".$resultsperpage."&amp;showgallery=".$showgallery."&amp;search_gene=".$search_gene."'>Previous</a></div>"; 
                          ?>
                        </td>
                        <td class="tblstandardfuellungrechts">
                          <?php
                          $next_site = $limit + $weiter;
                          if($seiten_z2*$weiter > $next_site)
                            echo "<div class=\"tblstandardfuellungrechts\"><a href='?showinfos=".$showinfos."&amp;newsite=yes&amp;orderby=".$orderby."&amp;limit=$next_site&amp;tbl_id=".$tbl_id."&amp;gene=".$gene."&amp;searchwhere=".$searchwhere."&amp;resultsperpage=".$resultsperpage."&amp;showgallery=".$showgallery."&amp;search_gene=".$search_gene."'>Next</a></div>"; 
                          ?>                        
                        </td>
                      </tr>
                    </table>
                    <?php
                    $db_query_string = "SELECT COUNT(*) FROM ($query_gene) AS rows";
                    $db_query = pg_query($db_connection, $db_query_string);
                    $db_result = pg_fetch_array($db_query, 0);
                    $anzahl = $db_result["count"];
                    {
                      $seiten_z1 = $anzahl/($resultsperpage*$picture_total_tmp); 
                      $seiten_z2 = ceil($seiten_z1); 
                      $weiter = $nb+$resultsperpage*$picture_total_tmp;
                      $zurueck = $nb-$resultsperpage*$picture_total_tmp;
                      $resultpagescount = 0;
                      for($i=0;$i<$seiten_z2;$i++) 
                      {
                        $resultpagescount++;
                        $g = $i*$resultsperpage*$picture_total_tmp;
                        $s = $i+1;
                        if($resultpagescount == 11)
                        {
                          echo'<br/>';
                          $resultpagescount = 0;
                        }
                        $limittmp = $limit;
                        $limittmp--;
                        if($limittmp != $g) 
                        { 
                          $limittmp = $g;
                          $limittmp++;
                          if(($gene == '') && ($gene != '*'))
                          {          
                            echo "<a href='?showinfos=".$showinfos."&amp;newsite=yes&amp;orderby=".$orderby."&amp;limit=$limittmp&amp;tbl_id=".$tbl_id."&amp;gene=".$gene."&amp;searchwhere=".$searchwhere."&amp;resultsperpage=".$resultsperpage."&amp;showgallery=".$showgallery."&amp;search_gene=".$search_gene."&amp;data_username=".$data_username."'>$s</a>&#160"; 
                          }else{

                            echo "<a href='?showinfos=".$showinfos."&amp;newsite=yes&amp;orderby=".$orderby."&amp;limit=$limittmp&amp;tbl_id=".$tbl_id."&amp;gene=".$gene."&amp;searchwhere=".$searchwhere."&amp;resultsperpage=".$resultsperpage."&amp;showgallery=".$showgallery."&amp;search_gene=".$search_gene."&amp;data_username=".$data_username."'>$s</a>&#160;"; 
                          }
                        }else{ 
                          echo "$s&#160;"; 
                        } 
                      } 
                    }
                    $nbsminus = ($seiten_z2-1)*(($resultsperpage*$picture_total_tmp)/2);
                    if(($anzahl[0] == 0) && ($data_username == ''))
                      echo'No pictures were found for gene "'.$gene.'" and where "'.$searchwhere.'"'; 
                    elseif(($anzahl[0] == 0) && ($data_username != ''))
                      echo'No pictures were found for username "'.$data_username.'" and gene "'.$gene.'" and where "'.$searchwhere.'"';
                    ?>

                    <br />

                  </div>
                </td>
              </tr>
            </table>
            <br />
            <table width="100%" >
              <colgroup>
               <col width="100%" />
              </colgroup>
              <tr>
                <td class="ueberschrifttbl">
                  <div class="ueberschrifttxt">
                    Details:<a href="manual/manual2.htm#2_3" target="_blank">[Help]</a>
                  </div>
                </td>
              </tr>
            </table>
            <table width="100%" >
              <colgroup>
               <col width="30%" />
               <col width="70%" />
              </colgroup>

              <?php
                if($gene == '*')
                {
                  $gene = '';
                }
                $db_query_txt = "SELECT gen_pfad, ge_name, picture_number, picture_total, ge_feld1, ge_feld2, ge_feld3 FROM ".$tbl_name." WHERE gen_name =  $1";
                $db_query = pg_query_params($db_connection, $db_query_txt, array($gene));
                while ($db_result = pg_fetch_array($db_query))
                {
                  $detailspathtmp = $db_result["gen_pfad"];
                  $ge_name = $db_result["ge_name"];
                  $picture_number = $db_result["picture_number"];
                  $picture_total = $db_result["picture_total"];
                  $ge_feld1 = $db_result["ge_feld1"];
                  $ge_feld2 = $db_result["ge_feld2"];
                  $ge_feld3 = $db_result["ge_feld3"];
                }
                $detailspath = $detailspathtmp; 

                if($ge_id != '')
                {
                  $ge_datum = $ge_id;
                  $gen_details[4] = date("dmY",substr($ge_datum, 0, 10));
                  $gen_details[5] = $ge_name;
                  $gen_details[6] = $ge_feld1;
                  $gen_details[7] = $ge_feld2;
                  $gen_details[8] = $ge_feld3;
                }else{
                  $record_details = explode("/",$detailspathtmp);
                  $record_details_number = -1;
                  foreach($record_details as $record_details_tmp)
                    $record_details_number++;
                  $gen_details[4] = $record_details[$record_details_number-1];
                  $gen_details[5] = $record_details[$record_details_number-2];
                }
              ?>

              <tr>
                <td class="tblstandardfuellung">
                  <div class="ueberschrifttxt">Date:</div>
                </td>
                <td class="tbldetails">
                  <div class="standardtext"><?php echo($gen_details[4]); ?></div>
                </td>
              </tr>
              <tr>
                <td class="tblstandardfuellung">
                  <div class="ueberschrifttxt">2:</div>
                </td>
                <td class="tbldetails">
                  <div class="standardtext"><?php echo($gen_details[5]); ?></div>
                </td>
              </tr>
              <tr>
                <td class="tblstandardfuellung">
                  <div class="ueberschrifttxt">3:</div>
                </td>
                <td class="tbldetails">
                  <div class="standardtext"><?php echo($gen_details[6]); ?></div>
                </td>
              </tr>
              <tr>
                <td class="tblstandardfuellung">
                  <div class="ueberschrifttxt">4:</div>
                </td>
                <td class="tbldetails">
                  <div class="standardtext"><?php echo($gen_details[7]); ?></div>
                </td>
              </tr>
              <tr>
                <td class="tblstandardfuellung">
                  <div class="ueberschrifttxt">5:</div>
                </td>
                <td class="tbldetails">
                  <div class="standardtext"><?php echo($gen_details[8]); ?></div>
                </td>
              </tr>
              <tr>
                <td class="tblstandardfuellung">
                  <div class="ueberschrifttxt">6:</div>
                </td>
                <td class="tbldetails">
                  <div class="standardtext"><?php echo($normalized); ?></div>
                </td>
              </tr>
           </table>

            <br />
            <table width="100%" >
              <colgroup>
               <col width="100%" />
              </colgroup>
              <tr>
                <td class="ueberschrifttbl">
                  <div class="ueberschrifttxt">
                    Quantitative Data:<a href="manual/manual2.htm#2_4" target="_blank">[Help]</a>
                  </div>
                </td>
              </tr>
            </table>
            <table width="100%" >
              <colgroup>
               <col width="30%" />
               <col width="70%" />
              </colgroup>

              <?php

                if($gene == '*')
                {
                  $gene = '';
                }
//                if(check_table_exist(QUANT_DATA_PREFIX.strtoupper(makeTblnameClear($tbl_name))) == true)
//                {
                  $db_query_txt = "SELECT gen_info1, gen_info2, gen_info3, gen_info4, gen_info5, gen_info6  FROM ".QUANT_DATA_PREFIX.makeTblnameClear($tbl_name)." WHERE gen_name =  $1";
                  $db_query = pg_query_params($db_connection, $db_query_txt, array($gene));
                  while ($db_result = pg_fetch_array($db_query))
                  {
                    $gen_info1 = $db_result["gen_info1"];
                    $gen_info2 = $db_result["gen_info2"];
                    $gen_info3 = $db_result["gen_info3"];
                    $gen_info4 = $db_result["gen_info4"];
                    $gen_info5 = $db_result["gen_info5"];
                    $gen_info6 = $db_result["gen_info6"];
                  }
//                }
              ?>

              <tr>
                <td class="tblstandardfuellung">
                  <div class="ueberschrifttxt">1:</div>
                </td>
                <td class="tbldetails">
                  <div class="standardtext"><?php echo($gen_info1); ?></div>
                </td>
              </tr>
              <tr>
                <td class="tblstandardfuellung">
                  <div class="ueberschrifttxt">2:</div>
                </td>
                <td class="tbldetails">
                  <div class="standardtext"><?php echo($gen_info2); ?></div>
                </td>
              </tr>
              <tr>
                <td class="tblstandardfuellung">
                  <div class="ueberschrifttxt">3:</div>
                </td>
                <td class="tbldetails">
                  <div class="standardtext"><?php echo($gen_info3); ?></div>
                </td>
              </tr>
              <tr>
                <td class="tblstandardfuellung">
                  <div class="ueberschrifttxt">4:</div>
                </td>
                <td class="tbldetails">
                  <div class="standardtext"><?php echo($gen_info4); ?></div>
                </td>
              </tr>
              <tr>
                <td class="tblstandardfuellung">
                  <div class="ueberschrifttxt">5:</div>
                </td>
                <td class="tbldetails">
                  <div class="standardtext"><?php echo($gen_info5); ?></div>
                </td>
              </tr>
              <tr>
                <td class="tblstandardfuellung">
                  <div class="ueberschrifttxt">6:</div>
                </td>
                <td class="tbldetails">
                  <div class="standardtext"><?php echo($gen_info6); ?></div>
                </td>
              </tr>

           </table>

        </td>
        <td>&#160;</td>
        <td class="tbllinks">
          <div class="standardtext">

          <table width="100%" >
            <colgroup>
             <col width="100%" />
            </colgroup>
            <tr>
              <td class="ueberschrifttbl">
                <div class="ueberschrifttxt">
                  Image Notes:<a href="manual/manual2.htm#2_5" target="_blank">[Help]</a>
                </div>
              </td>
            </tr>
            <tr>
              <td class="tblstandardfuellung">
                <div class="ueberschrifttxt">
                  <?php
                  if($tbl_public == 0)
                    echo'<form style="display:inline" name="saveimagenotes" action="saveImageNotes.php" method="get" onsubmit="return submit_form()">';
                  ?>
                    <?php 

                    $db_query_string = "SELECT image_notes FROM $tbl_name WHERE gen_name = $1";
                    $db_query_params = pg_query_params($db_connection, $db_query_string, array($gene));
                    $db_result = pg_fetch_array($db_query_params, 0);
                    $imagenotes_ = $db_result["image_notes"];
                    if($pics_flex[0] == 'flex')
                    {
                      $crypt->class_obj = new Encryptor;
                      $imagenotes_ = $crypt->class_obj->Decrypt($imagenotes_);
                    }
                    ?>

                    <input type="hidden" name="imagenotes" id="imagenotes" value="test"></input>
                    <?php
                    function createlinks($pubmedtxt)
                    {
                      $pubmedlinktxt = '[';
                      $pubmedtxt = explode(',',$pubmedtxt);
                      $count_links = count($pubmedtxt);
                      $i = 0;
                      foreach($pubmedtxt as $pubmedlink)
                      {
                        $pubmedlinktxt .= '<a href="http://www.ncbi.nlm.nih.gov/pubmed/'.trim($pubmedlink).'" target="_new">'.trim($pubmedlink).'</a>';
                        $i++;
                        if($count_links > $i)
                          $pubmedlinktxt .= ', ';
                      }
                      $pubmedlinktxt .= ']';
                      return $pubmedlinktxt;
                    }
                    ?>
                    <iframe class="standardtext" id="editor" style="width:99%;height:160px;border-width:1px;background-color:#ffffff;font-size:10px;font-family: Verdana"src=""></iframe>
                    <script type="text/javascript">
                      window.onload = function()
                     {
                       if (document.all)
                       {
 //IE
                         frames.editor.document.designMode = "On";
                         frames.editor.document.body.innerHTML = '<?php echo(preg_replace('/\[([0-9, ]+)\]/e', "''.createlinks('\\1').''", $imagenotes_)); ?>';
                       }else
                       {
 //Mozilla
                         document.getElementById("editor").contentWindow.document.designMode = 'on';
                         document.getElementById("editor").contentWindow.document.body.innerHTML = '<?php echo(preg_replace('/\[([0-9, ]+)\]/e', "''.createlinks('\\1').''", $imagenotes_)); ?>';
                       }                        
                      }
                    </script>
                    <br/><br/>
                    <input type="hidden" name="gene" value="<?php echo($gene); ?>"></input>
                    <input type="hidden" name="search_gene" value="<?php echo($search_gene); ?>"></input>
                    <input type="hidden" name="limit" value="<?php echo($limit); ?>"></input>
                    <input type="hidden" name="showgallery" value="<?php echo($showgallery); ?>"></input>
                    <input type="hidden" name="showonepicture" value="<?php echo($showonepicture); ?>"></input>
                    <input type="hidden" name="resultsperpage" value="<?php echo($resultsperpage); ?>"></input>
                    <input type="hidden" name="url" value="array_data.php?tbl_id=<?php echo($tbl_id); ?>"></input>
                    <input type="hidden" name="db" value="<?php echo($tbl_name); ?>"></input>
                    <input type="hidden" name="orderby" value="<?php echo(preg_replace("/[^a-zA-Z0-9_]/", "", $_GET['orderby'])); ?>"></input>
                    <input type="hidden" name="showinfos" value="<?php echo($showinfos); ?>"></input>
                    <input type="hidden" name="data_username" value="<?php echo($data_username); ?>"></input>
                    <?php
                    if($tbl_public == 0)
                    {
                      echo'<input class="imagenavigationbutton" type="submit" name="Submit" value="Save" onclick="return confirm(\'Save?\'); submit();"></input>';
                      echo'</form>';
                    }
                    ?>
                </div>
              </td>
            </tr>
          </table>

            <table width="100%" >
              <colgroup>
                <col width="100%" />
              </colgroup>
              <tr>
                <td>
                  <div class="ueberschrifttxt">

                    <?php
                    $levelnumber = $_GET['levelnumber'];
                    $levelnumber = preg_replace("/[^0-9]/", "", $levelnumber);
                    $aktuell = $_GET['aktuell'];
                    $aktuell = preg_replace("/[^0-9]/", "", $aktuell);
                    if(($levelnumber == '') && ($aktuell == ''))
                    {
                      $levelnumber = '1';
                    }elseif($aktuell != ''){
                      $levelnumber = $aktuell;
                    }

                    $db_query_string = "SELECT gen_pfad, picture_total FROM $tbl_name WHERE gen_name = $1 AND picture_number = $2";
                    $db_query_params = pg_query_params($db_connection, $db_query_string, array($gene, $levelnumber));
                    $db_result = pg_fetch_array($db_query_params, 0);
                    $picturepathtmp = $db_result["gen_pfad"];
                    $levelnr = $db_result["picture_total"];
                    $picturepath = $picturepathtmp; 
                    $imageurl = $picturepath;

                    if($ge_id != '')
                    {
                      $ge_picture = $picturepath;
                      $levelnr = '1';
                      $levelnumber = '1';
                    }

                    ?>

                    <form name="gallery" id="gallery" action="" method="get">
                      <script type="text/javascript">
                        <!--
                        ImageUrls = new Array("platzhalter", 
                        <?php
                        $db_query_string = "SELECT gen_pfad FROM $tbl_name WHERE gen_name = $1 ORDER BY picture_number ASC";
                        $db_query_params = pg_query_params($db_connection, $db_query_string, array($gene));
                        while($db_result = pg_fetch_array($db_query_params))
                        {
                          $picturepathtmpjavascript = $db_result["gen_pfad"];
                          echo('"getPicture.php?pfad='.$crypt->class_obj->Encrypt($picturepathtmpjavascript).'", ');
                        }
                        ?>
                        "ende");
                        function wechselplus() {
                          document.getElementById('aktuell').value = document.getElementById('aktuell').value*1 + 1*1;
                          document.getElementById('levelnumber_aktuell').value = document.getElementById('aktuell').value;
                          if(document.getElementById('aktuell').value == document.getElementById('gesamt').value*1+1)
                          {
                            document.getElementById('aktuell').value = '1';
                            document.getElementById('levelnumber_aktuell').value = document.getElementById('aktuell').value;
                          }
                          document.getElementById('gene_picture').src = ImageUrls[document.getElementById('aktuell').value];
                          document.getElementById('levelnumber').value = document.getElementById('aktuell').value;
                        }
 
                        function wechselminus() {
                          document.getElementById('aktuell').value = document.getElementById('aktuell').value - 1;
                          document.getElementById('levelnumber_aktuell').value = document.getElementById('aktuell').value;
                          if(document.getElementById('aktuell').value == '0')
                          {
                            document.getElementById('aktuell').value = document.getElementById('gesamt').value;
                            document.getElementById('levelnumber_aktuell').value = document.getElementById('aktuell').value;
                          }
                          document.getElementById('gene_picture').src = ImageUrls[document.getElementById('aktuell').value];
                          document.getElementById('levelnumber').value = document.getElementById('aktuell').value;
                        }
                        //-->
                    </script>


                    <?php
                    if(($_GET['showgallery'] == 'yes') && ($_GET['showonepicture'] == ''))
                    { 
                      if($levelnumber == '')
                      {
                        $levelnumber = '0';
                      } 
                      ?>

                      <table border="0" width="100%">
                      <colgroup>
                        <col width="49%" />
                          <col width="2%" />
                          <col width="49%" />
                        </colgroup>
            
                        <?php
                        $levelnumbertmp = '0';
                        while($levelnumbertmp < $levelnr)
                        { 
                          $levelnumbertmp++;
                          ?>  

                           <tr>
                            <td>
                              <div class="">

                                <?php
                                $db_query_string = "SELECT gen_pfad FROM $tbl_name WHERE gen_name = $1 AND picture_number = $2";
                                $db_query_params = pg_query_params($db_connection, $db_query_string, array($gene, $levelnumbertmp));
                                $db_result = pg_fetch_array($db_query_params);
                                $picturepath = $db_result["gen_pfad"];
                                if(file_exists($picturepath))
                                {
                                  echo"<a href=\"?showinfos=$showinfos&amp;tbl_id=$tbl_id&amp;resultsperpage=$resultsperpage)&amp;gene=$gene&amp;levelnumber=$levelnumbertmp&amp;search_gene=$search_gene&amp;limit=$limit&amp;showonepicture=yes&amp;showgallery=yes&amp;orderby=";echo(preg_replace("/[^a-zA-Z0-9_]/", "", $_GET['orderby']));echo"&amp;data_username="?><?php echo($data_username); ?>"><img class="noborder" width="250" id="gene_picture<?php echo($levelnumbertmp); ?>" name="gene_picture<?php echo($levelnumbertmp); ?>" src="getPicture.php?pfad=<?php echo($crypt->class_obj->Encrypt($picturepath)); ?>" alt=""></img></a><?php
                                }elseif($ge_id != ''){
                                  if(file_exists($ge_picture))
                                  {
                                    echo'<a href="?showinfos='.$showinfos.'&amp;tbl_id='.$tbl_id.'&amp;ge_id='.$ge_id.'"><img class="noborder" width="250" id="gene_picture<?php echo($levelnumbertmp); ?>" name="gene_picture<?php echo($levelnumbertmp); ?>" src="getPicture.php?pfad='.$crypt->class_obj->Encrypt($ge_picture).'" alt=""></img></a>';
                                  }
                                }   
                                ?>

                              </div>
                            </td>
  
                            <?php
                            $levelnumbertmp++;
                            ?> 

                            <td>
                              <div class="">

                              <?php
                              $db_query_string = "SELECT gen_pfad FROM $tbl_name WHERE gen_name = $1 AND picture_number = $2";
                              $db_query_params = pg_query_params($db_connection, $db_query_string, array($gene, $levelnumbertmp));
                              $db_result = pg_fetch_array($db_query_params);
                              $picturepath = $db_result["gen_pfad"];
                              if(file_exists($picturepath))
                              {
                              ?>

                                <a href="?showinfos=<?php echo($showinfos); ?>&amp;tbl_id=<?php echo($tbl_id); ?>&amp;resultsperpage=<?php echo($resultsperpage); ?>&amp;gene=<?php echo($gene);?>&amp;levelnumber=<?php echo($levelnumbertmp); ?>&amp;search_gene=<?php echo($search_gene); ?>&amp;limit=<?php echo($limit); ?>&amp;showonepicture=yes&amp;showgallery=yes&amp;orderby=<?php echo(preg_replace("/[^a-zA-Z0-9_]/", "", $_GET['orderby'])); ?>&amp;data_username=<?php echo($data_username); ?>"><img class="noborder" width="250" id="gene_picture<?php echo($levelnumbertmp); ?>" name="gene_picture<?php echo($levelnumbertmp); ?>" src="getPicture.php?pfad=<?php echo($crypt->class_obj->Encrypt($picturepath)); ?>" alt=""></img></a>

                              <?php
                              } 
                              ?>

                              </div>
                            </td>
                          </tr>

                          <?php
                        } 
                        ?>
                      </table>

                    <?php
                    }else{
                      $realsizeimage = $_GET['realsizeimage'];
                      if($realsizeimage == 'yes')
                      {
                        $image_ratio = '';
                      }else{
                        $image_ratio = 'width="512" height="512"';
                      }
                      $levelnumber = $_GET['levelnumber'];
                      $levelnumber = preg_replace("/[^0-9]/", "", $levelnumber);
                      $aktuell = $_GET['aktuell'];
                      $aktuell = preg_replace("/[^0-9]/", "", $aktuell);
                      if(($levelnumber == '') && ($aktuell == ''))
                      {
                        $levelnumber = '1';
                      }elseif($aktuell != ''){
                        $levelnumber = $aktuell;
                      }
                      if(file_exists($picturepath))
                      { 
                      ?>

                      <img <?php echo($image_ratio); ?> id="gene_picture" name="gene_picture" src="getPicture.php?pfad=<?php echo($crypt->class_obj->Encrypt($picturepath)); ?>" alt=""></img>

                      <?php

                      }elseif($ge_id != ''){
                        echo'<img '.$image_ratio.' id="gene_picture" name="gene_picture" src="getPicture.php?pfad='.$crypt->class_obj->Decrypt($ge_picture).'" alt=""></img>';
                      }  
                    }
                    ?>
</form>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="ueberschrifttbl">
                  <div class="ueberschrifttxt">
                    Infos:<a href="manual/manual2.htm#2_7" target="_blank">[Help]</a>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="tblstandardfuellung">
                  <div class="ueberschrifttxt">
                    <form action="<?php $PHP_SELF ?>" method="get" name="searchform">
                      <input type="hidden" name="gene" value="<?php echo($gene); ?>"></input>
                      <input type="hidden" name="search_gene" value="<?php echo($search_gene); ?>"></input>
                      <input type="hidden" name="limit" value="<?php echo($limit); ?>"></input>
                      <input type="hidden" name="showgallery" value="<?php echo($showgallery); ?>"></input>
                      <input type="hidden" name="showonepicture" value="<?php echo($_GET['showonepicture']); ?>"></input>
                      <input type="hidden" name="resultsperpage" value="<?php echo($resultsperpage); ?>"></input>
                      <input type="hidden" name="url" value="array_data.php?tbl_id=<?php echo($tbl_id); ?>"></input>
                      <input type="hidden" name="orderby" value="<?php echo(preg_replace("/[^a-zA-Z0-9_]/", "", $_GET['orderby'])); ?>"></input>
                      <input type="hidden" name="realsizeimage" value="no"></input>
                      <input type="hidden" name="tbl_id" value="<?php echo($tbl_id); ?>"></input>
                      <input type="hidden" name="data_username" value="<?php echo($data_username); ?>"></input>
                      <input class="standardtext" type="checkbox" <?php if($showinfos == 'yes') { echo'checked="checked"'; } ?> name="showinfos" value="yes" onclick="this.form.submit();"></input>Show Infos Everytime
                    </form>
                    <?php
                    if($pics_flex[0] == 'flex')
                    {
                      $gene_explode = explode('_',$gene);
                      $gene_explode = $gene_explode[0];
                    }else{
                      if($tbl_id == '38226173363617177840')
                      {
                        $gene_explode = explode('-',$gene);
                        $gene_explode = $gene_explode[0];
                      }else{
                        $gene_explode = $gene;
                      }
                    }
                    if($showinfos == 'yes') { ?>
                      <iframe frameborder="0" src="info_gene.php?gene=<?php echo($gene_explode); ?>" width="100%" height="150px" name="gene_description_yeastgenome">
                      <p>Your Browser does not support IFrames. To see the content please click <a href="info_gene.php?gene=<?php echo($gene_explode); ?>">here</a></p>
                      </iframe>
                    <?php } ?>
                  </div>
                </td>
              </tr>
            </table>
          </div>
        </td>
        <td>&#160;</td>
        <td class="tbllinks">
          <table width="100%" >
            <colgroup>
             <col width="100%" />
            </colgroup>
            <tr>
              <td class="ueberschrifttbl">
                <div class="ueberschrifttxt">
                  Image Navigation:<a href="manual/manual2.htm#2_8" target="_blank">[Help]</a>
                </div>
              </td>
            </tr>
            <tr>
              <td class="tblstandardfuellung">
                <div class="ueberschrifttxt">
                  <input onclick="wechselminus()" class="standardtext" type="button" name="Submit" value=" &lt; "></input>
                  <input onclick="wechselplus()" class="standardtext" type="button" name="Submit" value=" > "></input>
                  <input type="hidden" name="imageurl" value="<?php echo($crypt->class_obj->Encrypt($imageurl)); ?>"></input>
                  <br />
                  <input class="galleryaufzaehlung" readonly="readonly" type="text" id="aktuell" name="aktuell" value="<?php echo($levelnumber); ?>"></input>/<input class="galleryaufzaehlung" readonly="readonly" type="text" id="gesamt" name="gesamt" value="<?php echo($levelnr); ?>"></input>

                  <form style="display:inline" name="showgallery" action="<?php $PHP_SELF ?>" method="get">
                    <input type="hidden" name="showgallery" value="yes"></input>
                    <input type="hidden" name="gene" value="<?php echo($gene); ?>"></input>
                    <input type="hidden" name="resultsperpage" value="<?php echo($resultsperpage); ?>"></input>
                    <input type="hidden" name="search_gene" value="<?php echo($search_gene); ?>"></input>
                    <input type="hidden" name="limit" value="<?php echo($limit); ?>"></input>
                    <input type="hidden" name="orderby" value="<?php echo(preg_replace("/[^a-zA-Z0-9_]/", "", $_GET['orderby'])); ?>"></input>
                    <input type="hidden" name="tbl_id" value="<?php echo($tbl_id); ?>"></input>
                    <input type="hidden" name="showinfos" value="<?php echo($showinfos); ?>"></input>
                    <input type="hidden" name="data_username" value="<?php echo($data_username); ?>"></input>
                    <input class="imagenavigationbutton" type="submit" name="Submit" value="Show Gallery"></input>
                  </form>
                  <br />
                  <?php
                  if($tbl_public == 0)
                    echo'<form style="display:inline" name="edit" action="editDataSet.php" method="get">';
                  ?>
                      <input type="hidden" name="gene" value="<?php echo($gene); ?>"></input>
                      <input type="hidden" name="search_gene" value="<?php echo($search_gene); ?>"></input>
                      <input type="hidden" name="limit" value="<?php echo($limit); ?>"></input>
                      <input type="hidden" name="showgallery" value="<?php echo($showgallery); ?>"></input>
                      <input type="hidden" name="showonepicture" value="<?php echo($showonepicture); ?>"></input>
                      <input type="hidden" name="resultsperpage" value="<?php echo($resultsperpage); ?>"></input>
                      <input type="hidden" name="url" value="array_data.php?tbl_id=<?php echo($tbl_id); ?>"></input>
                      <input type="hidden" name="db" value="<?php echo($tbl_name); ?>"></input>
                      <input type="hidden" name="orderby" value="<?php echo(preg_replace("/[^a-zA-Z0-9_]/", "", $_GET['orderby'])); ?>"></input>
                      <input type="hidden" name="showinfos" value="<?php echo($showinfos); ?>"></input>
                      <?php
                      if($tbl_public == 0)
                      {
                        echo'<input class="imagenavigationbutton" type="submit" name="Submit" value="Edit"></input>';
                        echo'</form><br />';
                        echo'<form style="display:inline" name="deletedataset" action="deleteDataSet.php" method="get">';
                      }
                      ?>
                      <input type="hidden" name="gene" value="<?php echo($gene); ?>"></input>
                      <input type="hidden" name="search_gene" value="<?php echo($search_gene); ?>"></input>
                      <input type="hidden" name="limit" value="<?php echo($limit); ?>"></input>
                      <input type="hidden" name="showgallery" value="<?php echo($showgallery); ?>"></input>
                      <input type="hidden" name="showonepicture" value="<?php echo($showonepicture); ?>"></input>
                      <input type="hidden" name="resultsperpage" value="<?php echo($resultsperpage); ?>"></input>
                      <input type="hidden" name="url" value="array_data.php?tbl_id=<?php echo($tbl_id); ?>"></input>
                      <input type="hidden" name="db" value="<?php echo($tbl_name); ?>"></input>
                      <input type="hidden" name="orderby" value="<?php echo(preg_replace("/[^a-zA-Z0-9_]/", "", $_GET['orderby'])); ?>"></input>
                      <input type="hidden" name="showinfos" value="<?php echo($showinfos); ?>"></input>
                      <input type="hidden" name="data_username" value="<?php echo($data_username); ?>"></input>
                      <?php
                      if($tbl_public == 0)
                      {
                        echo'<input class="imagenavigationbutton" type="submit" name="delete" value="Delete Data Set" onclick="return confirm(\'Do you really want to delete this data set?\'); submit();"></input>';
                        echo'</form><br/>';
                        echo'<form style="display:inline" action="deleteOnePicture.php" method="get" name="individuell_loeschen">';
                      }
                      ?>
                      <input type="hidden" id="levelnumber_aktuell" name="levelnumber_aktuell" value="<?php echo($levelnumber); ?>"></input>
                    <?php 
                    if(($showonepicture == 'yes') || ($showgallery != 'yes')) 
                    { ?>
                      <input type="hidden" name="gene" value="<?php echo($gene); ?>"></input>
                      <input type="hidden" name="search_gene" value="<?php echo($search_gene); ?>"></input>
                      <input type="hidden" name="limit" value="<?php echo($limit); ?>"></input>
                      <input type="hidden" name="showgallery" value="<?php echo($showgallery); ?>"></input>
                      <input type="hidden" name="showonepicture" value="<?php echo(preg_replace("/[^a-zA-Z0-9]/", "", $_GET['showonepicture'])); ?>"></input>
                      <input type="hidden" name="resultsperpage" value="<?php echo($resultsperpage); ?>"></input>
                      <input type="hidden" name="url" value="array_data.php?tbl_id=<?php echo($tbl_id); ?>"></input>
                      <input type="hidden" name="db" value="<?php echo($tbl_name); ?>"></input>
                      <input type="hidden" name="orderby" value="<?php echo(preg_replace("/[^a-zA-Z0-9_]/", "", $_GET['orderby'])); ?>"></input>
                      <input type="hidden" name="showinfos" value="<?php echo($showinfos); ?>"></input>
                      <input type="hidden" name="data_username" value="<?php echo($data_username); ?>"></input>
                      <?php
                      if($tbl_public == 0)
                        echo'<input class="imagenavigationbutton" type="submit" name="delete" value="Delete Image" onclick="return confirm(\'Do you really want to delete this picture?\'); submit();">';
                    }
                    if($tbl_public == 0)
                      echo'</form><br/>';
                    ?>
                    <form style="display:inline" action="<?php $PHP_SELF ?>" method="get" name="realsizeimage">
                      <input type="hidden" name="levelnumber" value="<?php echo($levelnumber); ?>"></input>
                    <?php 
                    $realsizeimage = $_GET['realsizeimage'];
                    if((($showonepicture == 'yes') || ($showgallery != 'yes')) && ($realsizeimage != 'yes')) 
                    { ?>
                      <input type="hidden" name="gene" value="<?php echo($gene); ?>"></input>
                      <input type="hidden" name="search_gene" value="<?php echo($search_gene); ?>"></input>
                      <input type="hidden" name="limit" value="<?php echo($limit); ?>"></input>
                      <input type="hidden" name="showgallery" value="<?php echo($showgallery); ?>"></input>
                      <input type="hidden" name="showonepicture" value="<?php echo(preg_replace("/[^a-zA-Z0-9_]/", "", $_GET['showonepicture'])); ?>"></input>
                      <input type="hidden" name="resultsperpage" value="<?php echo($resultsperpage); ?>"></input>
                      <input type="hidden" name="url" value="array_data.php?tbl_id=<?php echo($tbl_id); ?>"></input>
                      <input type="hidden" name="orderby" value="<?php echo(preg_replace("/[^a-zA-Z0-9_]/", "", $_GET['orderby'])); ?>"></input>
                      <input type="hidden" name="realsizeimage" value="yes"></input>
                      <input type="hidden" name="tbl_id" value="<?php echo($tbl_id); ?>"></input>
                      <input type="hidden" name="showinfos" value="<?php echo($showinfos); ?>"></input>
                      <input type="hidden" name="data_username" value="<?php echo($data_username); ?>"></input>
                      <input type="hidden" id="levelnumber" name="levelnumber" value="<?php echo$_GET['levelnumber']; ?>"></input>
                      <input class="imagenavigationbutton" type="submit" name="delete" value="Original Image Size" >
                      <br/>
                    <?php 
                    }
                    ?>
                    </form>
                    <form style="display:inline" action="<?php $PHP_SELF ?>" method="get" name="fitsizeimage">
                      <input type="hidden" name="levelnumber" value="<?php echo($levelnumber); ?>"></input>
                    <?php 
                    $realsizeimage = $_GET['realsizeimage'];
                    if((($showonepicture == 'yes') || ($showgallery != 'yes')) && ($realsizeimage == 'yes')) 
                    { ?>
                      <input type="hidden" name="gene" value="<?php echo($gene); ?>"></input>
                      <input type="hidden" name="search_gene" value="<?php echo($search_gene); ?>"></input>
                      <input type="hidden" name="limit" value="<?php echo($limit); ?>"></input>
                      <input type="hidden" name="showgallery" value="<?php echo($showgallery); ?>"></input>
                      <input type="hidden" name="showonepicture" value="<?php echo(preg_replace("/[^a-zA-Z0-9]/", "", $_GET['showonepicture'])); ?>"></input>
                      <input type="hidden" name="resultsperpage" value="<?php echo($resultsperpage); ?>"></input>
                      <input type="hidden" name="url" value="array_data.php?tbl_id=<?php echo($tbl_id); ?>"></input>
                      <input type="hidden" name="orderby" value="<?php echo(preg_replace("/[^a-zA-Z0-9_]/", "", $_GET['orderby'])); ?>"></input>
                      <input type="hidden" name="realsizeimage" value="no"></input>
                      <input type="hidden" name="tbl_id" value="<?php echo($tbl_id); ?>"></input>
                      <input type="hidden" name="showinfos" value="<?php echo($showinfos); ?>"></input>
                      <input type="hidden" name="data_username" value="<?php echo($data_username); ?>"></input>
                      <input type="hidden" id="levelnumber" name="levelnumber" value="<?php echo$_GET['levelnumber']; ?>"></input>
                      <input class="imagenavigationbutton" type="submit" name="delete" value="Fit Image Size" >
                      <br />
                    <?php 
                    }
                    ?>
                    </form>
                    <?php
                  ?>
                </div>
              </td>
            </tr>
            <tr>
              <td>
                &#160;
              </td>
            </tr>
            <tr>
              <td class="ueberschrifttbl">
                <div class="ueberschrifttxt">
                  External Databases:<a href="manual/manual2.htm#2_9" target="_blank">[Help]</a>
                </div>
              </td>
            </tr>
            <tr>
              <td class="tblstandardfuellung">
                <div class="ueberschrifttxt">
                    <a href="https://www.yeastgenome.org/locus/<?php echo($gene_explode); ?>" target="_new">SGD</a>
                    <br />
                    <a href="http://pathway.yeastgenome.org/YEAST/NEW-IMAGE?type=NIL&object=<?php echo($gene_explode); ?>" target="_new">SGD YBP</a>
                    <br />
                    <a href="http://scmd.gi.k.u-tokyo.ac.jp/datamine/ViewStats.do;?orf=<?php echo($gene_explode); ?>" target="_new">SCMD</a>
                    <br />
                    <form style="display:inline" name="gfp" action="https://yeastgfp.yeastgenome.org/search.php" enctype="multipart/form-data" method="post" target="_new">
                    <input type="hidden" name="orf_number" value="<?php echo($gene_explode); ?>"></input>
                    <a href="javascript:document.gfp.submit();">GFP UCSF</a>
                    </form>
                    <br />
                    <form style="display:inline" name="biopixie" action="http://avis.princeton.edu/pixie/graph.php" enctype="multipart/form-data" method="get" target="_new">
                    <input type="hidden" name="graph_genes" value="<?php echo($gene_explode); ?>"></input>
                    <a href="javascript:document.biopixie.submit();">bioPIXIE</a>
                    </form>
                    <br />
                    <a href="http://mips.helmholtz-muenchen.de/genre/proj/yeast/singleGeneReport.html?entry=<?php echo($gene_explode); ?>" target="_new">MIPS</a>
                </div>
              </td>
            </tr>
          </table>
          <br/>          
        </td>
      </tr>
    </table>
  </body>
</html>
