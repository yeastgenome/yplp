<?php
/**
 * list.php
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


$tbl_id = '669523384469120242866';
$tbl_id = preg_replace("/[^a-zA-Z0-9_#\-]/", "", $tbl_id);
$tbl_name = TBL_PREFIX.$tbl_id;
?>

            <table border="0" width="100%">
              <colgroup>
               <col width="100%" />
              </colgroup>
            </table>
            <table width="100%" >
              <colgroup>
               <col width="20%" />
               <col width="80%" />
              </colgroup>
              <tr class="tblstandardfuellungmitte">
                <td>
                  <div class="standardtext">Gene</div>
                </td>
                <td>
                  <div class="standardtext">URL</div>
                </td>
              </tr>

              <?php
              $gene = '';
              $query_gene = 'SELECT gen_name, picture_number FROM '.$tbl_name.' ';
              $db_query = pg_query($db_connection, $query_gene . ' ORDER BY gen_name ASC');
              while ($db_result = pg_fetch_array($db_query))
              { 
                  if($db_result["picture_number"] == '1')
                  {
                      $gene = $db_result["gen_name"];
                    ?>
  
                    <tr>
                      <td class="tblrandergebnisse">
                      <div class="standardtext"><a href="?gene=<?php echo(strtoupper($db_result["gen_name"]));echo('&amp;showinfos='.$showinfos.'&amp;orderby='.$orderby.'&amp;limit='.$limittmp.'&amp;searchwhere='.$searchwhere.'&amp;resultsperpage='.$resultsperpage.'&amp;showgallery='.$showgallery.'&amp;search_gene='.$search_gene.'&amp;tbl_id='.$tbl_id.'&amp;limit='.$limit.'&amp;data_username='.$data_username); ?>"><?php echo(strtoupper($db_result["gen_name"])); ?></a></div>
                      </td>
                      <td class="tblrandergebnisse">
                      <div class="standardtext"><a href="http://yplp.uni-graz.at/array_data.php?tbl_id=<?php echo$tbl_id; ?>&amp;showgallery=yes&amp;showinfos=&amp;newsite=yes&amp;gene=<?php echo strtoupper($db_result["gen_name"]) ?>">http://yplp.yeastgenome.org/array_data.php?tbl_id=<?php echo$tbl_id; ?>&showgallery=yes&showinfos=&newsite=yes&gene=<?php echo strtoupper($db_result["gen_name"]) ?></a></div>
                      </td>
                     </tr>

                    <?php
                  }
              }
              ?>
 
            </table>
  </body>
</html>
