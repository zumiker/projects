﻿
<?php
define('FPDF_FONTPATH','../../../../lib/fpdf/font/');
require('../../../../lib/pdftable/lib/pdftable.inc.php');
require_once("../include.php");
$divid = $_REQUEST['divid'];
$dateb = $_REQUEST['dateb'];
$datee = $_REQUEST['datee'];
$vub = $_REQUEST['vub'];
$fac= $_REQUEST['div'];
$fac = DecodeStr($fac);
$header_date="";
$n56	= DecodeStr( 'Результаты на дату ' );
$n651	= DecodeStr( 'Результаты на период с' );
$n65	= DecodeStr( 'по' );
$n652   = DecodeStr( 'Результаты на период по' );
if ($dateb!=""){
if($datee==""){
	$sql2="AND DATE_IN LIKE TO_DATE('$dateb', 'dd.mm.yyyy')";
	$header_date="$n56 $dateb";}
	else{
		$sql2="AND DATE_IN  BETWEEN TO_DATE('$dateb', 'dd.mm.yyyy') AND  TO_DATE('$datee', 'dd.mm.yyyy')";
			$header_date="$n651 $dateb $n65 $datee";

	}}
else if($datee!=""){
    $sql2="AND DATE_IN LIKE TO_DATE('$datee', 'dd.mm.yyyy')";
    $header_date="$n652 $datee";
}

if($vub=='1')			$sql3=" AND TNABOR IN (1,8)";
if($vub=='')			$sql3=" AND TNABOR IN(1,2,3,4,8)";
if($vub=='3')	        $sql3=" AND TNABOR IN (3,4,8)";
if($vub=='2')	        $sql3=" AND TNABOR = '2' ";

/*if(isset($_GET['nabor']))
{
	$nab = $_GET['nabor'];
	$sql3=" AND TNABOR='$nab'";
	if($nab=='3')
	{
		$sql3=" TNABOR IN (3,4,8) AND ";
	}
	if($nab=='undefined')
		$sql3="   TNABOR IN(1,3,4,8) AND";
	
}*/

if ($divid=='null')
$sql1="";
else
$sql1="where FACID='$divid'";



$sql="SELECT DISTINCT FAC, CONGROUP, ABI_NUMBER, LASTNAME, FIRSTNAME, PATRONYMIC, SEX, TO_CHAR(BIRTHDAY, 'dd.mm.yyyy'), PASNUMBER,
 HOST, FACID, CON_ID,SPECID, TNABOR, MUSOR, PRIORITET,  TCENAME_ABI AS TCENAME, EDUDOCUMENT, DOCUMENT,
  EDUDOCUMENTNUMBER, DOC, SIROTAID, TCENAME_ABI as ABITUR, AWARD, NULL TAWNAMEF, DATE_IN, ABI_ID, SPCBRIFE,KATEGOR
FROM ABIVIEW_LGOTY_12
ORDER BY CONGROUP, SPECID,KATEGOR,LASTNAME, FIRSTNAME";
		

$n1212122	= DecodeStr( 'Факультет:' );

$d=date("d.m.Y H:i ");
$n6543= DecodeStr( 'Отчёт сформирован' );
		$hdate="$n6543 $d";
$n326543= DecodeStr( 'Списки абитуриентов льготных категорий' );		
$html .="<table border='1' align='center'  style='text-align:left; font-family: Verdana; font-size: 14px;>";		
$html .= "<tr><td align = center colspan=4>$n326543<br>$header_date<br>$hdate<br><br></td></tr>";
$n	= DecodeStr( '№' );
$n1	= DecodeStr( '№ <br/>л.д.' );
$n2	= DecodeStr( 'ФИО' );
$n3	= DecodeStr( 'Примечание' );
$s1 .= "<tr>" .
	"<td align='center'><b>$n</td>" .
		"<td align='center'><b>$n1</td>" .
		"<td align='center'><b>$n2</td>" .
		"<td align='center'><b>$n3</td>";
				
		$sqlbal="SELECT DISTINCT " .
		" ID_PR, PREDMET " .
		" FROM ABI_BAL " .
		"  $sql1";
//echo "$sqlbal";

		foreach($cur1 as $k=>$row){
			$ID_PR = DecodeStr($row['ID_PR']);
			$PREDMET = DecodeStr($row['PREDMET']);
			$s1 .= "<td  align='center'><b>$PREDMET</td>";
			$PREMETID[$i]=$ID_PR;	
		}
		
	
		$col = "#ffffff";
		$CON_ID_OLD="netu";
		$SPECID_OLD="netu";
		$cur=execq($sql);
		$num=1;
		/*echo $sqlbal;
		$cur1=execq($sqlbal,false);
		echo json_encode($cur1);*/
		
		foreach($cur as $k=>$row)
		{			
			
		$num++;
		$FAC = DecodeStr($row['FAC']);
    $CONGROUP = DecodeStr($row['CONGROUP']);
    $ABI_NUMBER = DecodeStr($row['ABI_NUMBER']);
    $LASTNAME = DecodeStr($row['LASTNAME']);
    $FIRSTNAME = DecodeStr($row['FIRSTNAME']);
    $PATRONYMIC = DecodeStr($row['PATRONYMIC']);
    $SEX = DecodeStr($row['SEX']);
    $BIRTHDAY = DecodeStr($row['BIRTHDAY']);
    $PASNUMBER = DecodeStr($row['PASNUMBER']);
    $HOST = DecodeStr($row['HOST']);
    $FACID = DecodeStr($row['FACID']);
    $CON_ID= DecodeStr($row['CON_ID']);
    $SPECID = DecodeStr($row['SPECID']);
    $NABOR = DecodeStr($row['NABOR']);
    $MUSOR = DecodeStr($row['MUSOR']);
    $PRIORITET = DecodeStr($row['PRIORITET']);
    $TCENAME = DecodeStr($row['TCENAME']);
    $EDUDOCUMENT = DecodeStr($row['EDUDOCUMENT']);
    $DOCUMENT = DecodeStr($row['DOCUMENT']);
    $EDUDOCUMENTNUMBER = DecodeStr($row['EDUDOCUMENTNUMBER']);
    $DOC = DecodeStr($row['DOC']);
    $SIROTAID = DecodeStr($row['SIROTAID']);
    $ABITUR = DecodeStr($row['ABITUR']);
    $AWARD = DecodeStr($row['AWARD']);
    $TAWNAME= DecodeStr($row['TAWNAME']);
    $DATE_IN= DecodeStr($row['DATE_IN']);
    $ABI_ID=DecodeStr($row['ABI_ID']);
    $SPCBRIFE = DecodeStr($row['SPCBRIFE']);
	$yy1=DecodeStr("Факультет:");
		$yy2=DecodeStr("Специальность:");
		if($SPECID_OLD!=$SPECID)
		{
			$html.= "<tr><td colspan='99' align='left'><b>$CONGROUP<br/></b>	$yy1 <b>$FAC</b> <br/>$yy2 <b>$SPCBRIFE</td></tr>";
			$html.=$s1;
			$num=1;
			$SPECID_OLD=$SPECID;	
		}	
		
		if ($col=='#ffffff')
		{
			$col='#dddddd';
		}
		else
		{
			$col='#ffffff';
		}
		$html .= "<tr bgcolor='$col'>" .
		"<td align='center'>$num</td>" .
		"<td align='center'>$ABI_NUMBER</td>" .
		"<td align='center'>$LASTNAME $FIRSTNAME $PATRONYMIC</td>" .
		"<td align='center'>$ABITUR</td>";
		
		for($j=0;$j<sizeof($PREMETID);$j++)
		{
			$sqlpr="SELECT " .
		" BAL,BALL16,VST " .
		" FROM ABI_BAL " .
		" WHERE ABI_ID='$ABI_ID' AND ID_PR='$PREMETID[$j]'";
			$cur2=execq($sqlpr);

			$ABI_BAL= DecodeStr($cur2[0]['ABI_BAL']);
			$BAL16= DecodeStr($cur2[0]['BAL16']);
			$VST= DecodeStr($cur2[0]['VST']);
			
			if (strlen($BAL16)>0) {
				if($BAL16<=5)
					{
						$ojidan=DecodeStr(' Ожидание');
					}
				
				
				$BAL16="($BAL16)";
				$VST = $VST[0];
				if($VST=='?')
				{
					$olimp=DecodeStr(' Олимпиада');
				}
			}
			else {$BAL16='';
					$VST = '';
			}
			  
			$html .= "<td align='center'><b>$VST</b> $ABI_BAL$BAL16</td>";
		}
		
		
		$html .="</tr>";
		
		
				
		}
$html	.= "</table>";			
$p = new PDFTable();
$p->AliasNbPages();
$p->AddFont( 'TimesNewRomanPSMT', '', 'times.php' );
$p->SetFont( 'TimesNewRomanPSMT', '', 10 );
$p->SetMargins( 10, 10, 10, 10 );
$p->AddPage();
if ($cur==[])
{
    if ($datee!="")
        if($dateb!=""){
            $adf1	= DecodeStr( 'В данный период с' );
            $adf111	= DecodeStr( 'по' );
            $adf12	= DecodeStr( 'не было подано льгот' );
            $pdf_table1 ="<table widtd='100%' cellpadding='3' border='1' align='center'>
				<tr><td  align ='center' height=15 >$adf1 $dateb $adf111 $datee $adf12 </tr>";
        }
       else{$adf1	= DecodeStr( 'В данный день' );
    $adf12	= DecodeStr( 'не было подано льгот' );
    $pdf_table1 ="<table widtd='100%' cellpadding='3' border='1' align='center'>
				<tr><td  align ='center' height=15 >$adf1 $datee $adf12 </tr>";}
    else
        if($dateb!=""){
            $adf1	= DecodeStr( 'В данный день' );
            $adf12	= DecodeStr( 'не было подано льгот' );
            $pdf_table1 ="<table widtd='100%' cellpadding='3' border='1' align='center'>
				<tr><td  align ='center' height=15 >$adf1 $dateb $adf12 </tr>";
        }

        $p->htmltable( $pdf_table1 );
}
else
$p->htmltable( $html );
$time = strtotime( date('d.m.Y H.i.s') );// время в секундах, чтобы название файла было уникальным при повторном сохранении документа
//$exit = EncodeStr( "$grocode" );  // кодирует название, чтобы читался русский язык при сохранении
ob_get_contents();
ob_clean();
flush();
$p->Output("Льготы $time.pdf",'D');     // сохраняет в Документы


	
?>
