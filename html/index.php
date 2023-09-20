<?php
/**
 * index.php
 *
 * Index site
 * @author Florian Leitner <florian.leitner AT student DOT tugraz DOT at>
 * @version 1.0
 * @package YPLP
 * @copyright (c) 2009 Yeast Genetics and Molecular Biology Group University Graz
 */
?>
<?php
include'connect.inc.php';
include'includes/functions_helpers.php';
include'includes/defines.php';
$task = $_GET['task'];

if(($_POST['task'] == 'login') && (isset($_POST['submit'])))
{
  if((trim($_POST['name']) == "") || (trim($_POST['email']) == "") || (trim($_POST['institution']) == "") || (check_email_address($_POST['email']) == false))
    $task = 'register';
  else
  {
    include'includes/functions_users.php';
    $user_id = md5($_POST['email'].time());
    $user_type = 0;

    // by http://www.strassenprogrammierer.de/php-ip-adresse-ermitteln_tipp_576.html
    if(!isset($_SERVER['HTTP_X_FORWARDED_FOR']))
    {
      $client_ip = $_SERVER['REMOTE_ADDR'];
    } else {
      $client_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }

    add_username($_POST['email'], $_POST['name'], $_POST['institution'], $user_type, $user_id, time(), $client_ip);
    $cookie_data = $user_id.'---'.md5($_POST['name'].'dfgdfg$%$%regfdvgFDGDFwefCBcv');
    if(setcookie("yplp_db",$cookie_data,0,'/')!=TRUE) 
    { 
      echo'Your browser must support cookies!';              
    } 
    header("location:array_data.php?&tbl_id=669523384469120242866&showgallery=yes&showinfos=&newsite=yes");  
  }
}
?>
<?php echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n"; ?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
  <head>
    <meta name="author" content="Institute of Molecular Biosciences, University Graz, FL"/>
    <meta name="copyright" content="All material copyright" />
    <meta name="description" content="This image database provides information about the subcellular localization of proteins in live yeast (Saccharomyces cerevisiae) cells, obtained by high-resolution confocal imaging." />
    <meta name="distribution" content="global" />
    <meta name="keywords" content="" />
    <meta name="language" content="english" />
    <meta http-equiv="pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <meta http-equiv="cache-control" content="no-cache" />
    <meta name="page-topic" content="This image database provides information about the subcellular localization of proteins in live yeast (Saccharomyces cerevisiae) cells, obtained by high-resolution confocal imaging." />
    <meta name="revisit-after" content="7 days" />
    <meta name="robots" content="index,nofollow" />
    <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
   <link rel="shortcut icon" href="favicon.ico" type="image/ico">
    <title>YPL+</title>
    <link rel="stylesheet" type="text/css" href="https://yplp.qa.yeastgenome.org/normal.css" />
  </head>
  <body class="hintergrund"> 

    <table border="0" width="100%" class="starttbl">
      <colgroup>
        <col width="100%" />
      </colgroup>
      <tr>
        <td><p><span class="header">YPL+ Database</span>&#160;&#160;&#160;&#160;&#160;&#160;&#160;<span class="header_small">Yeast Protein Localization Plus Database</span>
          <br/>
          <a class="header_url" href="http://www-ang.kfunigraz.ac.at/%7Ekohlwein/home.htm" target="_blank" >Yeast Genetics and Molecular Biology Group (YGMBG) University Graz</a></p>
        </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
    </table>

    <p><img src="yplp.png" alt="YPL+"></img><br/>

    <table border="0" width="960" >
      <colgroup>
        <col width="160" />
        <col width="800" />
      </colgroup>
      <tr>
        <td valign="top">
          <table border="0" width="100%" >
            <colgroup>
              <col width="100%" />
            </colgroup>
            <tr>
              <td>
                <span class="main_text_bold"><a href="index.php">home</a></span>
                <br/><br/>
                <span class="main_text_bold"><a href="array_data.php?&tbl_id=669523384469120242866&showgallery=yes&showinfos=&newsite=yes">enter YPL+</a></span>
                <br/><br/>
                <span class="main_text_bold"><a href="manual/default.htm">how to use YPL+</a></span>
                <br/><br/>
                <span class="main_text_bold"><a href="index.php?task=contact">contact</a></span> 
                <br/><br/>
                <span class="main_text_bold"><a target="_new" href="http://yeast.uni-graz.at/">yeast genetics group</a></span> 
                <br/><br/>
                <span class="main_text_bold"><a href="index.php?task=links">links</a></span> 

            </td>
            </tr>

          </table>
        </td>
        <td align="left" valign="top">
          <table border="0" width="100%" class="index_tbl_right">
            <colgroup>
              <col width="100%" />
            </colgroup>
            <tr>
              <td>

                <?php

                if($task == 'register')
                {
                  echo'To access the database please enter your name, e-mailaddress and institution in the boxes below.<br/><br/>
                  <form name="login" action="'.$PHP_SELF.'" method="post">

                    <table border="0" width="100%">

                    <colgroup>

                    <col width="20%" />

                    <col width="80%" style="bgcolor:#FFFFFF;" />

                    </colgroup>

                    <tr>

                    <td>Name:</td>

                    <td><input class="standardtext" name="name" type="text" /></td>

                    </tr>

                    <tr>

                    <td>E-mailaddress:</td>

                    <td><input class="standardtext" name="email" type="text" /></td>

                    </tr>

                    <tr>

                    <td>Institution:</td>

                    <td><input class="standardtext" name="institution" type="text" /> <input class="standardtext" name="task" value="login" type="hidden" /></td>

                    </tr>
                    </table>

                    <br/><br/>
                    <input class="standardtext" type="submit" name="submit" value="Login" ></input>
                    <br/><br/>
                    <b><u>Please note:</u></b> We require this information for internal record keeping, to support funding for image database maintenance and development. We do not sell, trade or otherwise transfer this information to third parties.<br/>
We use cookies to identify a user. The cookie will be destroyed upon leaving the website.
This website may contain links to other websites; we take no responsibility or liability for their content.
                  </form>';
                }elseif($task == 'contact'){
#                  echo'Institute of Molecular Biosciences<br/>
#                  University Graz<br/>
#                  Humboldtstrasse 50/II<br/>
#                  8010 Graz<br/>
#                  Austria<br/><br/>
#
#                  Sepp Dieter Kohlwein<br/><br/>
#
#                  Tel: ++43 316 380 5487<br/>
#
#                  Fax: ++43 316 380 9854<br/>
#
#                  Email: ';echo"<script language='JavaScript' type='text/javascript'> 
#var pref = '&#109;a' + 'i&#108;' + '&#116;o'; 
#var attribut = 'hr' + 'ef' + '='; var first = '%73%65%70%70%2E%6B%6F%68%6C%77%65%69%6E'; var at = '%40'; var last = '&#x75;&#x6E;&#x69;&#x2D;&#x67;&#x72;&#x61;&#x7A;&#x2E;&#x61;&#x74;'; 
#var first2 = '&#x73;&#x65;&#x70;&#x70;&#x2E;&#x6B;&#x6F;&#x68;&#x6C;&#x77;&#x65;&#x69;&#x6E;'; var at2 = '&#x40;'; var last2 = '&#117;&#110;&#105;&#45;&#103;&#114;&#97;&#122;&#46;&#97;&#116;'; 
#document.write( '<a ' + attribut + '\'' + pref + ':' + first + at + last + '\'>' ); 
#document.write( first2 + at2 + last2 ); document.write( '<\/a>' ); </script> <noscript> 
#<span style='display:none; '>are-</span><span style='display:inline; '>&#x73;&#x65;&#x70;&#x70;&#x2E;&#x6B;&#x6F;&#x68;&#x6C;&#x77;&#x65;&#x69;&#x6E;</span><span style='display:none; '>-xya34</span><span style='display:inline; '>[at]</span><span style='display:none; '>ddks-</span><span style='display:inline; '>&#117;&#110;&#105;&#45;&#103;&#114;&#97;&#122;&#46;&#97;&#116;</span> </noscript>";
#
                  echo'Site Hosted By:<br/><br/>
                  <a href="https://www.yeastgenome.org/">
                  Saccharomyces Genome Database</a><br/>
                  Cherry Lab<br/>
                  Department of Genetics<br/>
                  Stanford University<br/><br/>

                  Tel: +1 650 725 8956<br/><br/>

                  Email: <a href="mailto:sgd-helpdesk@lists.stanford.edu">
                  sgd-helpdesk@lists.stanford.edu</a>';

                }elseif($task == 'links'){
echo'<p><font color="#333333" size="3"  face="Verdana"><b><font ><a name="microscopy"></a>Microscopy</font></b></font></p>

<!--
<p><font   face="Verdana"><a href="http://pantheon.cis.yale.edu/%7Ewfm5/gfp_gateway.html" target="_parent">GFP 
  Application page</a> Yale<br>
-->

<!--
  <a href="http://www.dkfz-heidelberg.de/abt0840/GFP/" target="_parent">The GFP 
  project</a> Heidelberg<br>
-->

  <a href="http://www.loci.wisc.edu/" target="_parent">http://www.loci.wisc.edu/</a> Wisconsin (Loci)<br>
  <a href="http://www.confocal-club.ru/" target="_parent">http://www.confocal-club.ru/</a> The confocal club<br>
  <a href="http://www.llt.de/" target="_parent">ihttp://www.llt.de/</a> Leica<br>
  <a href="https://yeastgfp.yeastgenome.org/" target="_parent">https://yeastgfp.yeastgenome.org/ </a>YeastGFP<br>
  </font></p>

<p>&nbsp;</p>
<p><font   face="Verdana"><b><font size="3" color="#333333"><a name="knowledge"></a>Knowledge 
  databases</font></b></font></p>
<p><font   face="Verdana"><span style="font-family:Arial;
color:black">

<!--
<a href="http://www.proteome.com/databases/index.html" target="_parent">http://www.proteome.com/databases/index.html</a> 
  </span>BioKnowledge Database (Proteome)<span style="font-family:Arial;
color:black"><br>
-->

  <a href="https://www.yeastgenome.org/cgi-bin/geneHunter" target="_parent">https://www.yeastgenome.org/cgi-bin/geneHunter</a> 
  </span>Global Gene Hunter (Stanford)</font></p>
<p>&nbsp;</p>
<p><font face="Verdana" ><b><font size="3" color="#333333"><a name="general"></a>General 
  genome/proteome databases</font></b></font></p>
<p><font   face="Verdana"><span
style="font-family:Arial"><a
href="http://bioinformatik.wzw.tum.de/index.php?id=63&L=1" target="_parent">http://bioinformatik.wzw.tum.de/index.php?id=63&L=1<a> 
  (genome viewer)</span><br>
  <span
style="font-family:Arial"><a
href="https://www.yeastgenome.org/" target="_parent">https://www.yeastgenome.org/</a> 
  SGD<br>

  </span></font><font   face="Verdana"><span
style="font-family:Arial">

<!--
<a href="http://www.proteome.com/databases/index.html" target="_parent">http://www.proteome.com/databases/index.html</a> 
  Yeast Proteome Database<o:p></o:p><span
style="mso-tab-count:1">&nbsp;&nbsp;&nbsp;&nbsp; <br>
-->

  </span>
<a
href="http://pir.georgetown.edu" target="_parent">http://pir.georgetown.edu</a> 
  PIR database<o:p></o:p></span><span style="font-size:14.0pt;
font-family:Arial"><o:p></o:p></span></font></p>
<p><font   face="Verdana"><span style="font-size:14.0pt;
font-family:Arial"><o:p></o:p></span></font></p>
<p>&nbsp;</p>
<p><font face="Verdana"><b><font size="3" color="#333333"><a name="sequence"></a>Sequence 
  tools</font></b></font></p>
<p><font face="Verdana"  ><span style="font-family:Arial"><a
href="https://www.yeastgenome.org/cgi-bin/seqTools" target="_parent">https://www.yeastgenome.org/cgi-bin/seqTools</a> 
  </span></font></p>
<p>&nbsp;</p>
<p><font face="Verdana"  ><b><font size="3"  color="#333333"><a name="mutant"></a>Mutant 
  phenotypes, functional analysis</font></b></font></p>
<p><font   face="Verdana"><span style="font-family:Arial;
color:black"><a
href="https://downloads.yeastgenome.org/unpublished_data/triples/" target="_parent">https://downloads.yeastgenome.org/unpublished_data/triples/</a><span style="mso-tab-count:1">&nbsp; 
  </span></span>Yale TRIPLES<span style="font-family:Arial;
color:black"><span style="mso-tab-count:1"><br>
  </span>

<!--
<a
href="http://www.mips.biochem.mpg.de/proj/eurofan/eurofan_1.html" target="_parent">http://www.mips.biochem.mpg.de/proj/eurofan/eurofan_1.html</a></span> 
  (MIPS)<span style="font-family:Arial;
color:black"><span style="mso-tab-count:1"><br>

  </span>
-->

<!--
<a
href="http://www.mips.biochem.mpg.de/proj/eurofan/eurofan_2_summary.html" target="_parent">http://www.mips.biochem.mpg.de/proj/eurofan/eurofan_2_summary.html</a></span> 
  (MIPS)<span style="font-family:Arial;
color:black"><span style="mso-tab-count:1"><br>
  </span>
-->

<a href="https://downloads.yeastgenome.org/curation/literature/" target="_parent">https://downloads.yeastgenome.org/curation/literature/</a> 
  </span></font></p>
<p>&nbsp;</p>
<p><font face="Verdana"  ><b><font size="3"  color="#333333"><a name="functional"></a>Functional 
  genomics</font></b></font></p>
<p><font   face="Verdana"><span style="font-family:Arial;
color:black"><a
href="http://www.highveld.com/genomics.html" target="_parent">http://www.highveld.com/genomics.html</a> Highveld
  </span></font></p>
<p>&nbsp;</p>
<p><font face="Verdana"  ><b><font size="3"  color="#333333"><a name="gene"></a>Gene Expression/Transcriptome analysis - array/SAGE data mining</font></b></font></p>
<!--
<pre>
-->
<font   face="Verdana"><span style="font-family:Arial;
color:black"><a
href="https://downloads.yeastgenome.org/unpublished_data/triples/" target="_parent">https://downloads.yeastgenome.org/unpublished_data/triples/</a> Yale TRIPLES</span>
<br/>
<span style="font-family:Arial;
color:black">
</span>
<span style="font-family:Arial;color:black">

<!--
<a href="http://genome-www4.stanford.edu/MicroArray/SMD/" target="_parent">http://genome-www4.stanford.edu/MicroArray/SMD/</a>
-->

</span><span style="font-family:Arial;
color:black"><a
href="https://spell.yeastgenome.org/" target="_parent">https://spell.yeastgenome.org/</a>
<span style="mso-spacerun: yes">&nbsp;</span></span>SPELL<span style="font-family:Arial;
color:black"><span style="mso-spacerun: yes"> </span></span>
<br/>
<span style="font-family:Arial;
color:black"><a
href="http://www.transcriptome.ens.fr/ymgv/" target="_parent">http://www.transcriptome.ens.fr/ymgv/</a> <span style="mso-tab-count:1">&nbsp;</span></span>Microarray global viewer<span style="font-family:Arial;
color:black"><span style="mso-tab-count:1">
</span>
<br/>
<!--
<a href="http://genome-www4.stanford.edu/cgi-bin/SGD/SAGE/querySAGE" target="_parent">http://genome-www4.stanford.edu/cgi-bin/SGD/SAGE/querySAGE</a> 
<a
href="http://webminer.ucsf.edu/cgi-bin/mkjavascript" target="_parent">http://webminer.ucsf.edu/cgi-bin/mkjavascript</a> <o:p></o:p></span>WebMiner Database, UCSF<span style="font-family:Arial;
color:black"><span style="mso-tab-count:1">
</span>
-->
<a href="http://arep.med.harvard.edu/ExpressDB/" target="_parent">http://arep.med.harvard.edu/ExpressDB/</a> ExpressDB
<br/>
<!--
<a href="http://young39.wi.mit.edu/chipdb_public/" target="_parent">http://young39.wi.mit.edu/chipdb_public/</a></span><br><br></font>
-->
<!--
</pre>
-->
<br/>

<p><font   face="Verdana"><b><font size="3" color="#333333"><a name="promoter"></a>Promoter/Transcription 
  factors/binding sites</font></b></font></p>

<p><font   face="Verdana"> <span style="font-family:Arial;
color:black"><a
href="http://rulai.cshl.edu/SCPD/" target="_parent">http://rulai.cshl.edu/SCPD/</a> 
  <o:p></o:p> </span>Promoter database, CSH
<!--
<span style="font-family:Arial;
color:black"><span style="mso-tab-count:1"> </span><a
href="http://transfac.gbf.de/" target="_parent"><br>
  http://transfac.gbf.de/</a> <o:p></o:p></span>
-->
<span style="font-family:Arial;color:black"><a href="http://www.biobase.de/" target="_parent"><br>
  http://www.biobase.de/</a> <o:p></o:p><a href="http://www.gene-regulation.com/" target="_parent"><br>
  http://www.gene-regulation.com/</a><o:p></o:p>
<!--
<a href="http://www.ncbi.nlm.nih.gov/CBBresearch/Postdocs/Wataru/PROSPECT/" target="_parent"><br>

  http://www.ncbi.nlm.nih.gov/CBBresearch/Postdocs/Wataru/PROSPECT/</a></span></font></p>
-->
<p>&nbsp;</p>
<p><font   face="Verdana"><b><font size="3"  color="#333333"><a name="proteomics"></a>Proteomics, 
  Protein functional data</font></b> </font></p>
<p><font   face="Verdana"><span style="font-family:Arial;
color:black"><a
href="http://ycga.yale.edu/" target="_parent">http://ycga.yale.edu/</a> 
  <o:p></o:p><br>
  </span></font>
<!--
<font   face="Verdana"><span style="font-family:Arial;
color:black"><a
href="http://genome-www4.stanford.edu/MicroArray/SMD/" target="_parent">http://genome-www4.stanford.edu/MicroArray/SMD/<br>
  </a>
-->
<a href="http://bioinfo.mbb.yale.edu/genome/yeast/chip/" target="_parent">http://bioinfo.mbb.yale.edu/genome/yeast/chip/</a></span> 
  protein arrays 
<br/>
<!--
</font></p>
-->
<a href="https://www.ebi.ac.uk/arrayexpress/" target="_parent">https://www.ebi.ac.uk/arrayexpress/</a>
<br/>
<a href="https://puma.princeton.edu/" target="_parent">https://puma.princeton.edu/</a>
<p>&nbsp;</p>
<p><font   face="Verdana"><b><font size="3"  color="#333333"><a name="proteinlinkage"></a>Protein 
  linkage map </font></b> </font></p>
<p><font   face="Verdana"><span style="font-family:Arial;
color:black"><a
href="http://www.yeastrc.org/pdr/pages/front.jsp" target="_parent">http://www.yeastrc.org/pdr/pages/front.jsp</a> 
  <span style="mso-tab-count:1">&nbsp;</span></span>Two hybrid data<span style="font-family:Arial;
color:black"><span style="mso-tab-count:1"> </span>
<!--
<a
href="http://genome.c.kanazawa-u.ac.jp/~webgen/webgen.html" target="_parent"><br>

  http://genome.c.kanazawa-u.ac.jp/~webgen/webgen.html</a> </span> </font></p>
-->
<p>&nbsp;</p>
<p><font   face="Verdana"><b><font size="3" color="#333333"><a name="proteinstructure"></a>Protein 
  structure</font></b> </font></p>
<p><font   face="Verdana">
<!--
<span style="font-family:Arial;color:black"><a
href="http://genome-www.stanford.edu/Sacch3D/" target="_parent">http://genome-www.stanford.edu/Sacch3D/</a> 
  </span> 
-->
<span style="font-family:Arial;color:black"><a
href="http://www.rcsb.org/" target="_parent">http://www.rcsb.org/</a> PDB 
</span> 
</font></p>
<p>&nbsp;</p>
<p><font   face="Verdana"><font ><b><font size="3" color="#333333"><a name="metabolism"></a>Metabolism, 
  pathways </font></b></font></font></p>
<p><font   face="Verdana"><span style="font-family:Arial;
color:black"><a href="http://www.genome.jp/kegg/" target="_parent">http://www.genome.jp/kegg/</a> 
  <o:p></o:p> </span>Kyoto Encyclopedia of Genes and Genomes
<span style="font-family:Arial;
color:black"><span style="mso-tab-count:1"> </span>
<!--
<a href="http://www.mips.biochem.mpg.de/proj/yeast/pathways/index.html" target="_parent">http://www.mips.biochem.mpg.de/proj/yeast/pathways/index.html</a> 
  </span>Pathways at MIPS
-->
 </font></p>
<br/>
<p><font size="3"  face="Verdana"><b><font  color="#333333"><a name="yeastandother"></a>Yeast 
  and other organisms </font></b> </font></p>

<!--
<p><font   face="Verdana"><span style="font-family:Arial;
color:black"><a
href="http://quest7.proteome.com/databases/index.html" target="_parent">http://quest7.proteome.com/databases/index.html</a> 
  <o:p></o:p> </span>YPD<br>
  <span style="font-family:Arial;
color:black"><a
href="http://genome-www.stanford.edu/Saccharomyces/worm/" target="_parent">http://genome-www.stanford.edu/Saccharomyces/worm/</a> 
  <o:p></o:p><span style="mso-tab-count:1">&nbsp;</span></span>Yeast/Worm<span style="font-family:Arial;
color:black"><span style="mso-tab-count:1">&nbsp;&nbsp; </span><a
href="http://genome-www.stanford.edu/Saccharomyces/mammal/" target="_parent"><br>
  http://genome-www.stanford.edu/Saccharomyces/mammal/</a> </span>Yeast/Mammals 
  </font></p>
<p>&nbsp;</p>
-->
<a href="http://www.alliancegenome.org/" target="_parent">
http://www.alliancegenome.org/</a> </span> Alliance of Genome Resources
  </font></p>
<br/>

<p><font   face="Verdana"><b><font  color="#333333"><a name="yeastandhuman"></a>Yeast 
  and human disease </font></b> </font></p>

<p><font   face="Verdana"><span style="font-family:Arial;
color:black"><a
href="https://downloads.yeastgenome.org/curation/literature/" target="_parent">https://downloads.yeastgenome.org/curation/literature/</a> 
  <a
href="https://www.ncbi.nlm.nih.gov/genbank/collab/db_xref/" target="_parent"><br>
  https://www.ncbi.nlm.nih.gov/genbank/collab/db_xref/</a> </span>YREF database </font></p>
<p>&nbsp;</p>
<!--
<p><font   face="Verdana"><b><font size="3" color="#333333"><a name="other"></a>Other 
  yeast sites</font></b> </font></p>
<p><font   face="Verdana"><span
style="font-family:Arial;color:black"><a
href="http://genome-www.stanford.edu/Saccharomyces/VL-yeast.html" target="_parent">http://genome-www.stanford.edu/Saccharomyces/VL-yeast.html</a> 
  </span> </font></p>
<p>&nbsp;</p>
-->
<p><font   face="Verdana"><b><font size="3" color="#333333"><a name="literature"></a>Literature, 
  news groups, Yeast labs</font></b> </font></p>
<p><font   face="Verdana"><span
style="font-family:Arial"><a
href="http://wiki.yeastgenome.org/index.php/General_Topics" target="_parent">http://wiki.yeastgenome.org/index.php/General_Topics</a> 
  Yeast Virtual Libary

<!--
 <o:p></o:p><span
style="mso-tab-count:1">&nbsp;&nbsp;&nbsp;&nbsp;<br>

  </span>
-->
<!--
<a href="http://genome-www.stanford.edu/Saccharomyces/AT-yeastbiosci.html" target="_parent">http://genome-www.stanford.edu/Saccharomyces/AT-yeastbiosci.html</a> 
  BioSci Yeast Archive<o:p></o:p><span
style="mso-tab-count:1">&nbsp;&nbsp;&nbsp;&nbsp; </span>
-->

<a href="http://www.bio.net/hypermail/yeast/" target="_parent"><br>
  http://www.bio.net/hypermail/yeast/</a> access to Yeast News Group <o:p></o:p>
<a href="https://www.yeastgenome.org/search?category=colleague&page=0" target="_parent"><br>
 https://www.yeastgenome.org/search?category=colleague&page=0</a> 
  Yeast Labs</span> </font></p>

<p>&nbsp;</p>
<p><font   face="Verdana"><b><font size="3" color="#333333" >Strains, 
  resources</font></b> <span style="font-size:14.0pt;
font-family:Arial"><o:p></o:p></span></font></p>

<p><font   face="Verdana"><span
style="font-family:Arial;color:black"><a href="http://www.atcc.org/" target="_parent">http://www.atcc.org/</a> 
  </span>American Type Culture Collection<span
style="font-family:Arial;color:black"> <o:p></o:p><a
href="http://www.thermofisher.com/us/en/home.html" target="_parent"><br>
  http://www.thermofisher.com/us/en/home.html</a> </span>Yeast deletion mutants<span
style="font-family:Arial;color:black"> <o:p></o:p><a
href="http://www.euroscarf.de/index.php?name=News" target="_parent"><br>
  http://www.euroscarf.de/index.php?name=News</a> </span><span style="font-family:Arial"><o:p></o:p></span>Yeast 
  deletion mutants <span style="font-family:Arial"><a
href="http://depts.washington.edu/yeastrc/strains-and-plasmids/" target="_parent"><br>
  http://depts.washington.edu/yeastrc/strains-and-plasmids/</a> NCRR Yeast Resource Center 
  <o:p></o:p>
<!--
<a href="http://mips.gsf.de/proj/yeast/CYGD/db/function_index.html" target="_parent"><br>

  http://mips.gsf.de/proj/yeast/CYGD/db/function_index.html</a> GFP vectors</span> 
-->
  </font></p>
';
                }else{
                  echo'<b>Welcome to the Yeast Protein Localization<sup>Plus</sup> Database, YPL+.db</b><br/><br/>

This image database provides information about the subcellular localization of proteins in live yeast (<i>Saccharomyces cerevisiae</i>) cells, obtained by high-resolution confocal imaging. The imaged cells are derived from the collection of GFP fusion constructs that were generated by C-terminal chromosomal tagging (<a href="http://www.ncbi.nlm.nih.gov/pubmed/14562095" target="_new">Huh et al., 2003, Nature 425, 686-691</a>) and the collection of proteins involved in lipid-metabolism, constructed by in vivo recombination (<a href="http://www.ncbi.nlm.nih.gov/pubmed/15716577" target="_new">Natter et al., 2005, Mol. Cell. Proteomics 4(5), 662-672</a>).
<br/><br/>
Use of information provided by YPL+.db in publications should be referenced as &quot;Oskolkova, Leitner and Kohlwein, personal communication&quot;.
<br/><br/>
<b><i>Please note:</i></b> as with all tagging techniques, GFP tagging may alter the function and localization of the protein under study. Although we noted that most of the localization patterns appear to be consistent with other available data, we provide this information &quot;as is&quot;, without any warranty for its correctness. See also <a href="http://www.ncbi.nlm.nih.gov/pubmed/19521820" target="_new">Wolinski et al., 2009, Methods in Molecular Biology, Vol 548, pp75-99</a>, for potential pitfalls and limitations of yeast live cell imaging.
<br/><br/><br/>
This project is supported by:<br/>
<a href="http://gold.uni-graz.at" target="_new"><img width="360px" src="images_sup/gold.jpg" alt="GOLD"></img></a>
<br/><br/>
<a href="http://www.gen-au.at/index.jsp?lang=en" target="_new"><img width="400px" src="images_sup/gen_au.png" alt="GEN-AU"></img></a>
<br/><br/>
<a href="http://lipotox.uni-graz.at" target="_new"><img width="160px" src="images_sup/lipotox.jpg" alt="LIPOTOX"></img></a>
<br/><br/>
<a href="http://www.fwf.ac.at/en/index.asp" target="_new"><img src="images_sup/fwf.gif" alt="FWF"></img><img src="images_sup/fwf1.gif" alt="FWF"></img></a>
<br/><br/>
<a href="http://dk.uni-graz.at" target="_new"><img src="images_sup/dk.png" alt="DK"></img></a>
<br/><br/>
';
                }
                ?>

              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table> 


 </body>
</html>
