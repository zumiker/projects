<?php
function ora_getcolumnnbsp($cur,$pos)
{
if (ora_getcolumn($cur,$pos)==' ') return " "; 
else return ora_getcolumn($cur,$pos);
}
session_start();

$header='';

$conn=ora_logon("GANGTEMP@IUSDB.DEVELOPERS","gdb");



if(isset($_GET['id_fac']))
{
	$id_fac=$_GET['id_fac'];
	$sql1="FACID='$id_fac' ";
}

if(isset($_GET['id_con']))
{
	$id_con=$_GET['id_con'];
	$sql1.="CON_ID='$id_con' ";
}

if(isset($_GET['nabor']))
{
	$nab = $_GET['nabor'];
	$sql3=" AND TNABOR='$nab'";
	if($nab=='1')
	{
		$name='��������� �����';
		$sql3 = " AND (TNABOR='$nab' OR TNABOR='8')";
		}
	if($nab=='2')
		$name='������� �����';
	if($nab=='3')
	{
		$name='������������ �����';
		$sql3 = " AND (TNABOR='$nab' OR TNABOR='8')";
		//$sql3 .= " AND TNABOR='8'";
		}
	if($nab=='4')
		$name='��������';
	
}

if(isset($_GET['podl']))
{
	$podl=$_GET['podl'];
	if ($podl==1)
	{
		$sql3.=" AND DOC='����'";
		$doc=", ����������";
	}
	else 
		$doc="";
}


if(isset($_GET['tur']))
{
	$tur=$_GET['tur'];
/*	$sql4 = $sql3;
	$sql3 .=" AND TUR='$tur'";*/
}

 $sql="SELECT CONGROUP,MEST_HOST FROM ABI_CONGROUP WHERE ID_CON=$id_con";
 $cur=ora_do($conn,$sql);

	 for ($i=0;$i<ora_numrows($cur);$i++)
	 {
	 		$CONGROUP=ora_getcolumn($cur,0);
			$MEST=ora_getcolumn($cur,1);
	 }


   $sql="SELECT SPCNAME, SPCBRIFE, SPCCODENEW, ID_SPEC" .
		" FROM ABIVIEW_CON_SPEC " .
		" WHERE  ID_CON='$id_con' ORDER BY SPCBRIFE";
		
		$cur=ora_do($conn,$sql);

$date = date("d.m.y H:i");

		
$html.= "<table border='0' width='180'>";
$html.= "<tr><td colspan='99' family='TimesNewRomanPS-BoldMT' size=17 align='center'>" .
		"�������� �� ���������� �� 1 ���� ��� ����� � ���� </td></tr>";
$html.= "<tr><td colspan='99' family='TimesNewRomanPS-BoldMT' size=17 align='center'>" .
		"����� �.�.������� �� $date</td></tr>"; 
//$html.= "<tr><td colspan='99' align='center'>" .
//		"�� $date</td></tr>"; 		
$html.= "<tr><td colspan='99' family='TimesNewRomanPS-BoldMT' size=17 align='center'>" .
		"�� ��������� �������������</td></tr>";	

/*		
$html.= "<tr><td><tr><td colspan='99' align='center'>" .
		"�� \"����������\"                     $CONGROUP </td></tr><tr><td>";
$html.= "<tr><td colspan='99' align='center'>" .
		"($name $doc) ��� $tur</td></tr><tr><td>";
*/
$html.= "<tr><td><tr><td colspan='99' align='left'>" .
		"$CONGROUP   ($name $doc) ��� $tur</td></tr><tr><td>";
		//"($name $doc) </td></tr><tr><td>"; 	
for ($i=0;$i<ora_numrows($cur);$i++)
		{		
			$SPCNAME = ora_getcolumnnbsp($cur,0);
			$SPCBRIFE = ora_getcolumnnbsp($cur,1);
			$SPCCODE = ora_getcolumn($cur,2);
			$ID_SPEC = ora_getcolumn($cur,3);
			
//			if ($CONGROUP=='���������� ������ 11')
			if ($CONGROUP=='���������� ������ 13')
	{
	if ($nab==1)
		{
		if ($ID_SPEC!=228 && $ID_SPEC!=229)
		{
			$html.= "<tr><td colspan='6' size='14' align='left'>" .
			"$SPCBRIFE    $SPCNAME</tr>";	
			if(strlen($SPCNAME)>=55)
			{
				$html.= "<tr><td>";
			}
		}
/* 		else if ($SPCBRIFE!='��')
		{
			$html.= "<tr><td colspan='6' size='14' align='left'>" .
			"$SPCBRIFE    $SPCNAME</tr>";	
			if(strlen($SPCNAME)>=55)
			{
				$html.= "<tr><td>";
			}
		} */
		}
		else
		{
		$html.= "<tr><td colspan='6' size='14' align='left'>" .
			"$SPCBRIFE    $SPCNAME</tr>";	
			if(strlen($SPCNAME)>=55)
			{
				$html.= "<tr><td>";
			}	
		}
	}
	else
	{
				
			$html.= "<tr><td colspan='6' size='14' align='left'>" .
			"$SPCBRIFE    $SPCNAME</tr>";	
			if(strlen($SPCNAME)>=55)
			{
				$html.= "<tr><td>";
			}
	}
			ora_fetch($cur);		
		}

 		$sql="SELECT sum(VSEGO), sum(HOST) ,sum(OSTATKI) " .
		" FROM ABI_PODVAL_2 " .
		" WHERE  CON_ID='$id_con' $sql4";
		$cur=ora_do($conn,$sql);
		
		$VSEGO2 = ora_getcolumnnbsp($cur,0);
		$HOST = ora_getcolumnnbsp($cur,1);
		$OSTATKI = ora_getcolumn($cur,2);
		
		$sql="SELECT  sum(NEUD) " .
		" FROM ABI_PODVAL_4 " .
		" WHERE  CON_ID='$id_con' $sql4";
		$cur=ora_do($conn,$sql);
		
		$NEUD = ora_getcolumnnbsp($cur,0);	
		$VIDERJALI=$VSEGO2-$NEUD;
		 $sql="SELECT sum(MUJ),sum(JEN) " .
		" FROM ABI_PODVAL_3 " .
		" WHERE  CON_ID='$id_con' $sql4";
		$cur=ora_do($conn,$sql);
		$MUJ = ora_getcolumnnbsp($cur,0);
		$JEN = ora_getcolumnnbsp($cur,1);	

		
		if($nab==3)
		{
			
		$sql="SELECT SUM(MEST_COM) FROM ABI_CON_SPEC WHERE ID_CON='$id_con'";
		$cur=ora_do($conn,$sql);
		 $MEST_BUD = ora_getcolumnnbsp($cur,0);
		 $MEST_BUD_host=0;
		}
		else
		{
		 $sql="SELECT SUM(MEST_BUD) FROM ABI_CON_SPEC WHERE ID_CON='$id_con'";
		$cur=ora_do($conn,$sql);
		 $MEST_BUD = ora_getcolumnnbsp($cur,0);
		 $MEST_BUD_host=$MEST_BUD;
		}
		
		
		$tmp = $VSEGO2/$MEST_BUD;
		$tmp =  number_format($tmp, 1, '.', '');;
				
		//$viderjali = $VSEGO - $NEUD;
			
$html.= "<tr><td><tr><td><tr><td colspan='5' size='15' align='left'>" .
			"����� ���� � ���������� ������</td><td  width='15' > $MEST_BUD</td></tr>";	
			if($tur==2)
			{
				$html.= "<td><td><tr><td colspan='5' size='15' align='left'>" .
				"�������� ���� � ���������� ������</td><td  width='15' > $OSTATKI</td></tr>";	
			}
	if ($nab!='3')
	{
		$html.= "<td><tr><td><tr><td colspan='5' size='15' align='left'>" .
			"���� � ���������</td><td  width='15' > $MEST</td></tr>";
		if($tur==2)
			{
				$html.= "<td><td><tr><td colspan='5' size='15' align='left'>" .
				"�������� ���� � ���������</td><td  width='15' > </td></tr>";	
			}
	}
	else
	{
		$html.= "<td><tr><td><tr><td colspan='5' size='15' align='left'>" .
			"���� � ���������</td><td  width='15' > 0</td></tr>";	
	}
//$html.= "<tr><td colspan='5' size='15' align='left'>" ."�� ��� � ����������</td><td width='15' >$MEST_BUD_host</td></tr>";

if ($podl!='1' && $tur!='3')
{
	$html.= "<tr><td colspan='5' size='15' align='left'>" .
			"����� ������ ���������</td><td width='15' >$VSEGO2</td></tr>";
			
		
	$html.= "<tr><td colspan='5' size='15' align='left'>" .
				"������� �� ����������</td><td width='15' >$tmp</td></tr>";
				
	$sql = "SELECT  sum(VSEGO), sum(HOST), sum(SOBESED), 
				sum(PODTV), sum(OTL), sum(VNEKON) FROM ABI_PODVAL_1 WHERE CON_ID= '$id_con' $sql4";
			$cur=ora_do($conn,$sql);
			$VSEGO = ora_getcolumnnbsp($cur,0);
			$HOST= ora_getcolumnnbsp($cur,1);
			$SOBESED= ora_getcolumnnbsp($cur,2);
			$PODTV= ora_getcolumnnbsp($cur,3);
			$OTL= ora_getcolumnnbsp($cur,4);
			$VNEKON= ora_getcolumnnbsp($cur,5);
				 
				
	if ($nab!='3')
	{
		$html.= "<tr><td colspan='5' size='15' align='left'>" .
					"�������� ������������� ����������</td><td></td></tr>";
		///$html.= "<tr><td></td><td colspan='4' size='15'  align='left'>" ."            �� ��� � ����������</td><td width='15' >$HOST</td></tr>";					
		/*$html.= "<tr><td></td><td colspan='4' size='15'  align='left'>" .
					"            �� �������������</td><td>$SOBESED</td></tr>";*/
		$html.= "<tr><td></td><td colspan='4' size='15'  align='left'>" .
					"            (���������� ��������)</td><td width='15' >$PODTV</td></tr>";
		$html.= "<tr><td colspan='5' size='15'  align='left'>" .
					"��� ��������</td><td width='15' >$VNEKON</td></tr>";
		/*$html.= "<tr><td></td><td colspan='4' size='15'  align='left'>" .
					"            ���������</td><td>$OTL</td></tr>";*/
		/*	$sql = "SELECT COUNT(*),CON_ID FROM ABITURIENT WHERE TUR = 26 AND CON_ID='$id_con' $sql3 GROUP BY CON_ID";		
			$cur=ora_do($conn,$sql);
			$NEUD = ora_getcolumnnbsp($cur,0);*/
	}	
		
	/*$html.= "<tr><td colspan='5' size='15' align='left'>" .
				"�����</td><td></td></tr>";//$VIDERJALI*/
	$html.= "<tr><td colspan='5' size='15'  align='left'>" .
				"����� ������</td><td width='15' >$MUJ</td></tr>";
	$html.= "<tr><td colspan='5' size='15'  align='left'>" .
				"����� ������</td><td width='15' >$JEN</td></tr>";

	$KONK=($VIDERJALI/$MEST_BUD);
	$KONK =  number_format($KONK, 1, '.', '');
	/*$html.= "<tr><td></td><td colspan='4' size='15'  align='left'>" .
				"������� �� ����������� �������</td><td></td></tr>";//$KONK*/
				
				$MEST=$MEST_BUD-$VSEGO;
	/*$html.= "<tr><td></td><td colspan='4' size='15'  align='left'>" .
				"�������� ���� �� ��������� �������</td><td>$MEST</td></tr>";	*/		
				
	$KONK=($VIDERJALI-$SOBESED)/($MEST_BUD-$SOBESED);
	$KONK =  number_format($KONK, 1, '.', '');
				
	/*$html.= "<tr><td></td><td colspan='4' size='15'  align='left'>" .
				"������� � ������ ������������� ����������</td><td></td></tr>";//$KONK*/	
}
									
		$s1 = "<table border='1' style=' text-align:center; font-family: Verdana; font-size: 10px;'>";
		$s1.=
						"<tr><td align='center' width='10'><b>�</b></td>" .
						"<td align='center' width='110'><b>���</b></td>" .
						"<td align='center'><b>����</b></td>" .
						"<td align='center'><b>��������</b></td>" .
						"<td align='center'><b>���������</b></td></tr>" ;

//		 "<table border='0' width='180'>"; 		
	if ($nab!='3')
	{	
/*		$sql="SELECT ABI_NUMBER, LASTNAME, FIRSTNAME, PATRONYMIC, HOST, TAWNAME_SMALL, KATEGORIA, DOC, PRIORITET, BALL, PRIKAZ" .
		" FROM ABIVIEW_PODVAL_24 " .
		" WHERE  $sql1  $sql3 AND KATEGOR IN (21,23) and tur_fix = $tur ORDER BY KATEGOR,BALL DESC, LASTNAME";*/
$sql="SELECT ABI_NUMBER, LASTNAME, FIRSTNAME, PATRONYMIC, OJID, TAWNAME_SMALL, KATEGORIA, DOC, BALL, KATEGOR" .
		" FROM ABIVIEW_PODVAL_24 " .
		" WHERE $sql1  $sql3 AND KATEGOR IN (21,23) AND DOC='����' and tur_fix = $tur ORDER BY KATEGOR,BALL DESC, LASTNAME";
		
		$cur=ora_do($conn,$sql);
		$num=0;	
		$KATEGORIA_OLD='ned';
			
		for ($i=0;$i<ora_numrows($cur);$i++)
		{			
			
		$num=$num+1;
		$ABI_NUMBER = ora_getcolumnnbsp($cur,0);
		$LASTNAME = ora_getcolumnnbsp($cur,1);
		$FIRSTNAME = ora_getcolumnnbsp($cur,2);
		$PATRONYMIC = ora_getcolumnnbsp($cur,3);
		$HOST = ora_getcolumnnbsp($cur,4);
		$TAWNAME_SMALL = ora_getcolumnnbsp($cur,5);
		$KATEGORIA = ora_getcolumnnbsp($cur,6);
		$DOC = ora_getcolumnnbsp($cur,7);
		$BALL = ora_getcolumnnbsp($cur,8);
		$KATEGOR = ora_getcolumnnbsp($cur,9);
		
		if($KATEGORIA_OLD!=$KATEGORIA)
		{
			
			if ( $KATEGORIA_OLD == 'ned')
			{
				$html.=$s1;
			}
			$html.= "<tr><td><tr><td colspan='99' family='TimesNewRomanPS-BoldMT' size='14' align='left'>$KATEGORIA</td></tr>";
			//$num=1;
			$KATEGORIA_OLD=$KATEGORIA;	
		}	
		
		/*if ($col=='#ffffff')
		{
			$col='#eeeeee';
		}
		else*/
		{
			$col='#ffffff';
		}
			
		$html .= "<tr bgcolor='$col'>" .
		"<td align='center' family='TimesNewRomanPSMT' size='10'>$num</td>" .
		"<td align='left' width='10'>$LASTNAME $FIRSTNAME $PATRONYMIC</td>".
		"<td align='center'>$BALL</td>".
		"<td align='center'>$DOC</td>".
		"<td align='center'>$HOST</td>";
		$html .="</tr>";
			ora_fetch($cur);		
		}
	}	

		for ($j=0;$j<=0;$j++)
		{
			if($j==0)
			{
/*				$sql="SELECT ABI_NUMBER, LASTNAME, FIRSTNAME, PATRONYMIC, HOST, TAWNAME_SMALL, KATEGORIA, DOC, BALL, KATEGOR, COLOR, PRIORITET, PRIKAZ " .
				" FROM ABIVIEW_PODVAL_24 " .
				" WHERE  $sql1  $sql3   AND KATEGOR IN (29,31) and tur_fix = $tur ORDER BY KATEGOR, BALL desc, LASTNAME";*/
/*				$sql="SELECT ABI_NUMBER, LASTNAME, FIRSTNAME, PATRONYMIC FROM ABIVIEW_PODVAL_24 " .
				" WHERE  $sql1  $sql3   AND KATEGOR IN (29,31) and tur_fix = $tur ORDER BY KATEGOR, BALL desc, LASTNAME";*/
				$sql="SELECT ABI_NUMBER, LASTNAME, FIRSTNAME, PATRONYMIC, OJID, TAWNAME_SMALL, KATEGORIA, DOC, BALL, KATEGOR" .
						" FROM ABIVIEW_PODVAL_24 " .
						" WHERE $sql1  $sql3 AND KATEGOR IN (29,31) AND DOC='����' and tur_fix = $tur ORDER BY KATEGOR,BALL DESC, LASTNAME";
			}
			else
			{
				$sql="SELECT ABI_NUMBER, LASTNAME, FIRSTNAME, PATRONYMIC, HOST, TAWNAME_SMALL, KATEGORIA, DOC, NVL(BALL16,0), KATEGOR, OJID" .
				" FROM ABIVIEW_PODVAL_26 " .
				" WHERE  $sql1  $sql3 ORDER BY KATEGOR, NVL(BALL,0) desc, LASTNAME";	
			}
//			$html.="<tr><td>$sql</td></tr>";
			$cur=ora_do($conn,$sql);
			
			$BALL16_OLD='ned';
			$KATEGOR_OLD='ned';
			$switchhh=0;
			for ($i=0;$i<ora_numrows($cur);$i++)
			{			
				
			$num=$num+1;
		$ABI_NUMBER = ora_getcolumnnbsp($cur,0);
		$LASTNAME = ora_getcolumnnbsp($cur,1);
		$FIRSTNAME = ora_getcolumnnbsp($cur,2);
		$PATRONYMIC = ora_getcolumnnbsp($cur,3);
		$HOST = ora_getcolumnnbsp($cur,4);
		$TAWNAME_SMALL = ora_getcolumnnbsp($cur,5);
		$KATEGORIA = ora_getcolumnnbsp($cur,6);
		$DOC = ora_getcolumnnbsp($cur,7);
		$BALL = ora_getcolumnnbsp($cur,8);
		$KATEGOR = ora_getcolumnnbsp($cur,9);
			
			if($KATEGOR!=$KATEGOR_OLD)
			{
				$KATEGOR_OLD=$KATEGOR;
				if ($KATEGORIA_OLD=='ned')
			{
			$html.=$s1;
			}
				switch($KATEGOR)
				{
					case 29:
						if ($tur!='3')
							$html.= "<tr><td><tr><td colspan='99' family='TimesNewRomanPS-BoldMT' size=14 align='left'>��������������� � ���������� </td></tr>";
						else
							$html.= "<tr><td><tr><td colspan='99' family='TimesNewRomanPS-BoldMT' size=14 align='left'>��������� �������� �� ���������� ��������� �����</td></tr>";
						break;
					case 31:
						
						if ($tur!='3')
						{
							$html.= "<tr><td><tr><td colspan='99' family='TimesNewRomanPS-BoldMT' size=14 align='left'>����������� ������������� ��������� � ������������ �� �����������";
							if ($tur=='1')
								$html.= "<br>�� 1-� ���� ������������ �� ��������� ����� ����� 1 ���� </td></tr>"; 
							else
								$html.= "<br>�� 1-� ���� ������������ �� ��������� ����� ����� 2 ���� </td></tr>"; 
						}
							//$html.= "<tr><td><tr><td colspan='99' family='TimesNewRomanPS-BoldMT' size=14 align='left'>������</td></tr>";
						break;
						
				}
			}
			/*if($KATEGOR=='24')
			{	
				if($BALL16_OLD!=$BALL16)
				{
					
					if ($id_con==13 || $id_con==14 || $id_con==15 ||  $id_con==17)
					{
						$nummm=23;
					}
					else
					{
						$nummm=11;
					}
									
					if($BALL16>$nummm)
					{
					$html.= "<tr><td><tr><td colspan='99' family='TimesNewRomanPS-BoldMT' size=14 align='left'>�����������, ��������� $BALL16 ������</td></tr>";
					}
					
					
					if($BALL16<=$nummm && $switchhh=='0')
					{
						$switchhh=1;
						$html.= "<tr><td><tr><td colspan='99' family='TimesNewRomanPS-BoldMT' size=14 align='left'>�� ����� ��������</td></tr>";
					}
					//$num=1;
					$BALL16_OLD=$BALL16;	
				
				}
			}
			if($KATEGOR=='26' && $switchhh=='0')
			{
				$switchhh=1;
				$html.= "<tr><td><tr><td colspan='99' family='TimesNewRomanPS-BoldMT' size=14 align='left'>�� ����� ��������</td></tr>";
			}	*/
			
			if ($COLOR==1)
			{
				$col='#eeeeee';
			}
			else
			{
				$col='#ffffff';
			}
				
			$html .= "<tr bgcolor='$col'>" .
			"<td align='center' family='TimesNewRomanPSMT' size='10'>$num</td>" .
			"<td align='left'>$LASTNAME $FIRSTNAME $PATRONYMIC</td>".
			"<td align='center'>$BALL</td>".
			"<td align='center'>$DOC</td>".
			"<td align='center'>$HOST</td>";
			$html .="</tr>";
				ora_fetch($cur);		
			}	
		}
	
	define('FPDF_FONTPATH','font/');
require('lib/pdftable.inc.php');
	
$p = new PDFTable();
$p->AddPage('P');
$p->SetMargins(10,10,10);
$p->AddFont('TimesNewRomanPSMT','','times.php');  
$p->AddFont('TimesNewRomanPS-BoldMT','','timesbd.php');
$p->SetFont('TimesNewRomanPSMT','',14); 
$p->htmltable($html);

$p->output('','I');	

	
?>
