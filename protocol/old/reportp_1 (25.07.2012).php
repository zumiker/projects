<?php
function ora_getcolumnnbsp($cur,$pos)
{
	if (ora_getcolumn($cur,$pos)==' ') return " "; 
	else return ora_getcolumn($cur,$pos);
}
session_start();
$header='';
$fac=$_GET['fac'];
$conn=ora_logon("GANGTEMP@IUSDB.DEVELOPERS","gdb");
$sql="SELECT FACID from ABI_FAC WHERE FACID=$fac ORDER BY FACNAME";
$cur=ora_do($conn,$sql);
$id_fac=ora_getcolumn($cur,0);
if(isset($_GET['id_con'])){
	$id_con=$_GET['id_con'];
	$sql1.="CON_ID='$id_con' ";
}
if(isset($_GET['nabor'])){
	$nab = $_GET['nabor'];
	$sql3=" AND TNABOR='$nab'";
	if($nab=='1'){
		$name='��������� �����';
		$sql3 = " AND (TNABOR='$nab' OR TNABOR='8')";
	}
	if($nab=='2')
		$name='������� �����';
	if($nab=='3')	{
		$name='������������ �����';
		$sql3 = " AND (TNABOR='$nab' OR TNABOR='8')";
	}
	if($nab=='4')
		$name='��������';
	if($nab=='5'){
		$name='��������� �����';
		$sql3 = " AND (TNABOR='3' OR TNABOR='8')";
	}
}
$sql5="";
if(isset($_GET['podl'])){
	$podl=$_GET['podl'];
	if ( $podl == 1 ){
		$sql5.=" AND DOC='����'";
		$doc=", ����������";
	}
	else 
		$doc="";
}
if(isset($_GET['tur'])){
	$tur=$_GET['tur'];
	$sql4 = $sql3;
	$sql3 .=" AND TUR='$tur'";
}
$sql="SELECT CONGROUP,MEST_HOST FROM ABI_CONGROUP WHERE ID_CON=$id_con";
$cur=ora_do($conn,$sql);
for ($i=0;$i<ora_numrows($cur);$i++){
	$CONGROUP=ora_getcolumn($cur,0);
	$MEST=ora_getcolumn($cur,1);
}
$sql="SELECT SPCNAME, SPCBRIFE, SPCCODENEW, ID_SPEC FROM ABIVIEW_CON_SPEC WHERE  ID_CON='$id_con' ORDER BY SPCBRIFE";
$cur=ora_do($conn,$sql);
$date = date("d.m.y H:i");
		
$html= "<table border='0' width='180'>
<tr>
	<td colspan='99' family='TimesNewRomanPS-BoldMT' size=17 align='center'>�������� �� ���������� �� 1 ���� ��� ����� � ���� </td>
</tr>
<tr>
	<td colspan='99' family='TimesNewRomanPS-BoldMT' size=17 align='center'>����� �.�.������� �� $date</td>
</tr>
<tr>
	<td colspan='99' family='TimesNewRomanPS-BoldMT' size=17 align='center'>�� ��������� �������������</td>
</tr>
<tr>
	<td colspan='99' align='left'>$CONGROUP   ($name $doc) ��� $tur</td>
</tr>"; 	
for ($i=0;$i<ora_numrows($cur);$i++){		
	$SPCNAME	= ora_getcolumnnbsp($cur,0);
	$SPCBRIFE	= ora_getcolumnnbsp($cur,1);
	$SPCCODE	= ora_getcolumn($cur,2);
	$ID_SPEC	= ora_getcolumn($cur,3);
	if ( $CONGROUP == '���������� ������ 13' ){
		if ( $nab == 1 ){
			if ( $ID_SPEC != 228 && $ID_SPEC != 229 ){
				$html.= "<tr><td colspan='6' size='14' align='left'>$SPCBRIFE    $SPCNAME</td></tr>";	
				if( strlen( $SPCNAME ) >= 55 )
					$html.= "<tr><td>";
			}
		}
		else{
			$html.= "<tr><td colspan='6' size='14' align='left'>$SPCBRIFE    $SPCNAME</td></tr>";	
			if( strlen( $SPCNAME ) >= 55 )
				$html.= "<tr><td>";
		}
	}
	else{
		$html.= "<tr><td colspan='6' size='14' align='left'>$SPCBRIFE    $SPCNAME</td></tr>";	
			if( strlen( $SPCNAME ) >= 55 )
				$html.= "<tr><td>";
	}
	ora_fetch($cur);		
}

$sql="SELECT sum(VSEGO), sum(HOST) ,sum(OSTATKI) FROM ABI_PODVAL_2 WHERE  CON_ID='$id_con' $sql4";
$cur=ora_do($conn,$sql);
$VSEGO2	= ora_getcolumnnbsp($cur,0);
$HOST	= ora_getcolumnnbsp($cur,1);
$OSTATKI= ora_getcolumn($cur,2);

$sql="SELECT  sum(NEUD) FROM ABI_PODVAL_4 WHERE  CON_ID='$id_con' $sql4";
$cur=ora_do($conn,$sql);
$NEUD = ora_getcolumnnbsp($cur,0);	
$VIDERJALI=$VSEGO2-$NEUD;

$sql="SELECT sum(MUJ),sum(JEN) FROM ABI_PODVAL_3 WHERE  CON_ID='$id_con' $sql4";
$cur=ora_do($conn,$sql);
$MUJ = ora_getcolumnnbsp($cur,0);
$JEN = ora_getcolumnnbsp($cur,1);	

$sql="SELECT OSTATKI_HOST FROM ABI_PODVAL_5 WHERE  CON_ID='$id_con'";
$cur=ora_do($conn,$sql);
$OSTATKI_HOST = ora_getcolumnnbsp($cur,0);

if($nab==3){
	$sql="SELECT SUM(MEST_COM) FROM ABI_CON_SPEC WHERE ID_CON='$id_con'";
	$cur=ora_do($conn,$sql);
	$MEST_BUD = ora_getcolumnnbsp($cur,0);
	$MEST_BUD_host=0;
}
else{
	$sql="SELECT SUM(MEST_BUD) FROM ABI_CON_SPEC WHERE ID_CON='$id_con'";
	$cur=ora_do($conn,$sql);
	$MEST_BUD = ora_getcolumnnbsp($cur,0);
	$MEST_BUD_host=$MEST_BUD;
}
$tmp = $VSEGO2/$MEST_BUD;
$tmp =  number_format($tmp, 1, '.', '');;

$html.= "<tr>
			<td colspan='5' size='15' align='left'>����� ���� � ���������� ������</td>
			<td  width='15' > $MEST_BUD</td>
		</tr>";	
if($tur==2){
	$html.= "<tr>
				<td colspan='5' size='15' align='left'>�������� ���� � ���������� ������</td>
				<td  width='15' > $OSTATKI</td>
			</tr>";	
}
if ($nab!='3'){
	$html.= "<td><tr><td><tr><td colspan='5' size='15' align='left'>" .
		"���� � ���������</td><td  width='15' > $MEST</td></tr>";
	if($tur==2){
		$html.= "<tr>
					<td colspan='5' size='15' align='left'>�������� ���� � ���������</td>
					<td  width='15' > $OSTATKI_HOST</td>
				</tr>";	
	}
}
else{
	$html.= "<tr>
				<td colspan='5' size='15' align='left'>���� � ���������</td>
				<td  width='15' > 0</td>
			</tr>";	
}
if ( $podl != '1' && $tur != '3' ){
	$html.= "<tr>
				<td colspan='5' size='15' align='left'>����� ������ ���������</td>
				<td width='15' >$VSEGO2</td>
			</tr>
			<tr>
				<td colspan='5' size='15' align='left'>������� �� ����������</td>
				<td width='15' >$tmp</td>
			</tr>";
	$sql = "SELECT  sum(VSEGO), sum(HOST), sum(SOBESED), sum(PODTV), sum(OTL), sum(VNEKON) FROM ABI_PODVAL_1 WHERE CON_ID= '$id_con' $sql4";
	$cur=ora_do($conn,$sql);
	$VSEGO = ora_getcolumnnbsp($cur,0);
	$HOST= ora_getcolumnnbsp($cur,1);
	$SOBESED= ora_getcolumnnbsp($cur,2);
	$PODTV= ora_getcolumnnbsp($cur,3);
	$OTL= ora_getcolumnnbsp($cur,4);
	$VNEKON= ora_getcolumnnbsp($cur,5);
	if ($nab!='3' || $tur!='2'){
		$html.= "<tr>
					<td colspan='5' size='15' align='left'>�������� ������������� ����������</td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td colspan='4' size='15'  align='left'>            (���������� ��������)</td>
					<td width='15' >$PODTV</td>
				</tr>
				<tr>
					<td colspan='5' size='15'  align='left'>��� ��������</td>
					<td width='15' >$VNEKON</td>
				</tr>";
	}	
	$html.= "<tr>
				<td colspan='5' size='15'  align='left'>����� ������</td>
				<td width='15' >$MUJ</td>
			</tr>
			<tr>
				<td colspan='5' size='15'  align='left'>����� ������</td>
				<td width='15' >$JEN</td>
			</tr>";
	$KONK	= ($VIDERJALI/$MEST_BUD);
	$KONK	= number_format($KONK, 1, '.', '');
	$MEST	= $MEST_BUD-$VSEGO;
	$KONK	= ($VIDERJALI-$SOBESED)/($MEST_BUD-$SOBESED);
	$KONK	= number_format($KONK, 1, '.', '');
}
$html .= "</table><table border='1' style='text-align:center; font-family: Verdana; font-size: 7px;'>
<tr>
	<td colspan='9' align=center family='TimesNewRomanPSMT' size='7'><b>������ � ����������</b></td>
</tr>
<tr>
	<td rowspan='2' align='center' width='9'><b>�<br> �/�</b></td>
	<td rowspan='2' align='center' width='30'><b>���</b></td>
	<td rowspan='2' align='center' width='10'><b>�����<br>������</b></td>
	<td rowspan='2' align='center' width='15'><b>���������</b></td>
	<td rowspan='2' align='center' width='11'><b>������</b></td>
	<td rowspan='2' align='center'><b>��������</b></td></tr>
	<td colspan='2' align='center' width='27'><b>���������</b></td></tr>
	<td rowspan='2' align='center' width='10' ><b>� �.�.</b></td></tr>
	<tr><td align='center'><b>���������</b></td>
	<td align='center'><b>��������</b></td>
</tr>";
if ($nab!='3'){
	if ($podl==1){
		$sql="SELECT ABI_NUMBER, LASTNAME, FIRSTNAME, PATRONYMIC, HOST, TAWNAME_SMALL, KATEGORIA, DOC, decode(PRIORITET,1,1,4,2,7,3), BALL, PRIKAZ, OJID
				FROM ABIVIEW_PODVAL_24
				WHERE CON_ID='$id_con' AND (TNABOR='$nab') AND KATEGOR IN (21,23) AND DOC='����' and tur_fix = $tur 
				ORDER BY KATEGOR,BALL DESC, LASTNAME";
	}
	else{
		$sql="SELECT ABI_NUMBER, LASTNAME, FIRSTNAME, PATRONYMIC, HOST, TAWNAME_SMALL, KATEGORIA, DOC, decode(PRIORITET,1,1,4,2,7,3), BALL, PRIKAZ, OJID
				FROM ABIVIEW_PODVAL_24
				WHERE CON_ID='$id_con' AND (TNABOR='$nab') AND KATEGOR IN (21,23) and tur_fix = $tur 
				ORDER BY KATEGOR,BALL DESC, LASTNAME";
	}
	if ($nab == 5){
		$sql="SELECT ABI_NUMBER, LASTNAME, FIRSTNAME, PATRONYMIC, HOST, TAWNAME_SMALL, KATEGORIA, DOC, decode(PRIORITET,1,1,4,2,7,3), BALL, PRIKAZ, OJID
				FROM ABIVIEW_PODVAL_24
				WHERE CON_ID='$id_con' AND (TNABOR='$nab') AND KATEGOR IN (21,23,32) and tur_fix = $tur 
				ORDER BY KATEGOR,BALL DESC, LASTNAME";
	}
//	$html .= "<tr><td colspan=9>$sql</td></tr>";
	$cur=ora_do($conn,$sql);
	$num=0;	
	$KATEGORIA_OLD='ned';
	for ($i=0;$i<ora_numrows($cur);$i++){
		$num=$num+1;
		$ABI_NUMBER = ora_getcolumnnbsp($cur,0);
		$LASTNAME = ora_getcolumnnbsp($cur,1);
		$FIRSTNAME = ora_getcolumnnbsp($cur,2);
		$PATRONYMIC = ora_getcolumnnbsp($cur,3);
		$HOST = ora_getcolumnnbsp($cur,4);
		$TAWNAME_SMALL = ora_getcolumnnbsp($cur,5);
		$KATEGORIA = ora_getcolumnnbsp($cur,6);
		$DOC = ora_getcolumnnbsp($cur,7);
		$PRIORITET = ora_getcolumnnbsp($cur,8);
		$BALL = ora_getcolumnnbsp($cur,9);
		$PRIKAZ = ora_getcolumnnbsp($cur,10);
		$OJID = ora_getcolumnnbsp($cur,11);
		if($KATEGORIA_OLD!=$KATEGORIA){
			$html.= "<tr><td colspan='99' family='TimesNewRomanPS-BoldMT' size='12' align='left'>$KATEGORIA</td></tr>";
			$KATEGORIA_OLD=$KATEGORIA;	
		}	
		$col='#ffffff';
		$html .= "<tr bgcolor='$col'>
						<td align='center' family='TimesNewRomanPSMT' width='10' size='10'>$num</td>
						<td align='left' width='90'>$LASTNAME $FIRSTNAME $PATRONYMIC</td>
						<td align='center' >$BALL</td>
						<td align='center' >$PRIORITET </td>
						<td align='center'>$TAWNAME_SMALL</td>
						<td align='center'>$DOC</td>
						<td align='center'>$HOST</td>
						<td align='center' size='9'>$OJID</td>";
		if ( $PRIORITET != 1 )
			$html .= "<td align='center' size='9'>$ABI_NUMBER</td>";
		else
			$html .= "<td align='center'></td>";
		$html .="</tr>";
		ora_fetch($cur);		
	}
}
else if ($nab=='3'){
	if ($podl==1){
	$sql="SELECT ABI_NUMBER, LASTNAME, FIRSTNAME, PATRONYMIC, HOST, TAWNAME_SMALL, KATEGORIA, DOC, decode(PRIORITET,1,1,4,2,7,3), BALL, PRIKAZ, OJID
			FROM ABIVIEW_PODVAL_24
			WHERE CON_ID='$id_con' AND (TNABOR=3) AND KATEGOR IN (21,23) AND DOC='����' and tur_fix = $tur 
			ORDER BY KATEGOR,BALL DESC, LASTNAME";
	}
	else{
	$sql="SELECT ABI_NUMBER, LASTNAME, FIRSTNAME, PATRONYMIC, HOST, TAWNAME_SMALL, KATEGORIA, DOC, decode(PRIORITET,1,1,4,2,7,3), BALL, PRIKAZ, OJID
			FROM ABIVIEW_PODVAL_24
			WHERE CON_ID='$id_con' AND (TNABOR=3) AND KATEGOR IN (21,23) and tur_fix = $tur 
			ORDER BY KATEGOR,BALL DESC, LASTNAME";
	}
//	$html .= "<tr><td colspan=9>$sql</td></tr>";
	$cur=ora_do($conn,$sql);
	$num=0;	
	$KATEGORIA_OLD='ned';
	for ($i=0;$i<ora_numrows($cur);$i++){			
		$num=$num+1;
		$ABI_NUMBER = ora_getcolumnnbsp($cur,0);
		$LASTNAME = ora_getcolumnnbsp($cur,1);
		$FIRSTNAME = ora_getcolumnnbsp($cur,2);
		$PATRONYMIC = ora_getcolumnnbsp($cur,3);
		$HOST = ora_getcolumnnbsp($cur,4);
		$TAWNAME_SMALL = ora_getcolumnnbsp($cur,5);
		$KATEGORIA = ora_getcolumnnbsp($cur,6);
		$DOC = ora_getcolumnnbsp($cur,7);
		$PRIORITET = ora_getcolumnnbsp($cur,8);
		$BALL = ora_getcolumnnbsp($cur,9);
		$PRIKAZ = ora_getcolumnnbsp($cur,10);
		$OJID = ora_getcolumnnbsp($cur,11);
		if($KATEGORIA_OLD!=$KATEGORIA){
			$html.= "<tr><td colspan='99' family='TimesNewRomanPS-BoldMT' size='12' align='left'>$KATEGORIA</td></tr>";
			$KATEGORIA_OLD=$KATEGORIA;	
		}	
		$col='#ffffff';
		$html .= "<tr bgcolor='$col'>
						<td align='center' family='TimesNewRomanPSMT' width='10' size='10'>$num</td>
						<td align='left' width='90'>$LASTNAME $FIRSTNAME $PATRONYMIC</td>
						<td align='center' >$BALL</td>
						<td align='center' >$PRIORITET </td>
						<td align='center'>$TAWNAME_SMALL</td>
						<td align='center'>$DOC</td>
						<td align='center'>$HOST</td>
						<td align='center' size='9'>$OJID</td>";
		if ( $PRIORITET != 1 )
			$html .= "<td align='center' size='9'>$ABI_NUMBER</td>";
		else
			$html .= "<td align='center'></td>";
		$html .="</tr>";
		ora_fetch($cur);		
	}
}
for ($j=0;$j<=0;$j++){
	if($j==0){
		if ($podl==1){
			$sql="SELECT ABI_NUMBER, LASTNAME, FIRSTNAME, PATRONYMIC, HOST, TAWNAME_SMALL, KATEGORIA, DOC, BALL, KATEGOR, COLOR, decode(PRIORITET,1,1,4,2,7,3), PRIKAZ, OJID 
					FROM ABIVIEW_PODVAL_24 
					WHERE  CON_ID='$id_con' AND (TNABOR='$nab') AND KATEGOR='29' AND DOC='����' and tur_fix = $tur 
					ORDER BY KATEGOR, BALL desc, LASTNAME";
		}
		else{
			$sql="SELECT ABI_NUMBER, LASTNAME, FIRSTNAME, PATRONYMIC, HOST, TAWNAME_SMALL, KATEGORIA, DOC, BALL, KATEGOR, COLOR, decode(PRIORITET,1,1,4,2,7,3), PRIKAZ, OJID 
					FROM ABIVIEW_PODVAL_24 
					WHERE  CON_ID='$id_con' AND (TNABOR='$nab') AND KATEGOR='29' and tur_fix = $tur 
					ORDER BY KATEGOR, BALL desc, LASTNAME";
		}
	}
	else{
		$sql="SELECT ABI_NUMBER, LASTNAME, FIRSTNAME, PATRONYMIC, HOST, TAWNAME_SMALL, KATEGORIA, DOC, NVL(BALL16,0), KATEGOR, OJID 
				FROM ABIVIEW_PODVAL_26 
				WHERE  CON_ID='$id_con' AND (TNABOR='$nab') 
				ORDER BY KATEGOR, NVL(BALL,0) desc, LASTNAME";		
	}
//	$html .= "<tr><td colspan=9>$sql</td></tr>";
	$cur=ora_do($conn,$sql);
	$BALL16_OLD='ned';
	$KATEGOR_OLD='ned';
	$switchhh=0;
	for ($i=0;$i<ora_numrows($cur);$i++){			
		$num=$num+1;
		$ABI_NUMBER = ora_getcolumnnbsp($cur,0);
		$LASTNAME = ora_getcolumnnbsp($cur,1);
		$FIRSTNAME = ora_getcolumnnbsp($cur,2);
		$PATRONYMIC = ora_getcolumnnbsp($cur,3);
		$HOST = ora_getcolumnnbsp($cur,4);
		$TAWNAME_SMALL = ora_getcolumnnbsp($cur,5);
		$KATEGORIA = ora_getcolumnnbsp($cur,6);
		$DOC = ora_getcolumnnbsp($cur,7);
		$BALL16 = ora_getcolumnnbsp($cur,8);
		$KATEGOR = ora_getcolumnnbsp($cur,9);
		$COLOR= ora_getcolumnnbsp($cur,10);
		$PRIORITET= ora_getcolumnnbsp($cur,11);
		$PRIKAZ= ora_getcolumnnbsp($cur,12);
		$OJID=ora_getcolumnnbsp($cur,13);
		if ($nab=='3'){
			if($KATEGOR!=$KATEGOR_OLD){
				$KATEGOR_OLD=$KATEGOR;
				switch($KATEGOR){
					case 29:
						if ($tur!='3')
							$html.= "<tr>
										<td colspan='99' family='TimesNewRomanPS-BoldMT' size=12 align='left'>��������������� � ����������</td>
									</tr>";
						else
							$html.= "<tr>
										<td colspan='99' family='TimesNewRomanPS-BoldMT' size=12 align='left'>��������� �������� �� ���������� ��������� �����</td>
									</tr>";
						break;
					case 31:
						$html.= "<tr>
									<td colspan='99' family='TimesNewRomanPS-BoldMT' size=12 align='left'>������</td>
								</tr>";
						break;
				}
			}
		}
		else{
			if($KATEGOR!=$KATEGOR_OLD){
				$KATEGOR_OLD=$KATEGOR;
				switch($KATEGOR){
					case 29:
						if ($tur!='3')
							$html.= "<tr>
										<td colspan='99' family='TimesNewRomanPS-BoldMT' size=12 align='left'>��������������� � ����������</td>
									</tr>";
						else
							$html.= "<tr>
										<td colspan='99' family='TimesNewRomanPS-BoldMT' size=12 align='left'>��������� �������� �� ���������� ��������� �����</td>
									</tr>";
						break;
					case 31:
						$html.= "<tr>
									<td colspan='99' family='TimesNewRomanPS-BoldMT' size=12 align='left'>������</td>
								</tr>";
						break;
					}
				}
		}
		if ($COLOR==1)
			$col='#eeeeee';
		else
			$col='#ffffff';
		$html .= "<tr bgcolor='$col'>
					<td align='center' family='TimesNewRomanPSMT' size='10'>$num</td>
					<td align='left' width='90'>$LASTNAME $FIRSTNAME $PATRONYMIC</td>
					<td align='center'>$BALL16</td>
					<td align='center'>$PRIORITET</td>
					<td align='center'>$TAWNAME_SMALL</td>
					<td align='center'>$DOC</td>
					<td align='center'>$HOST</td>
					<td align='center' size='9'>$OJID</td>";
		if ( $PRIORITET != 1 )
			$html .= "<td align='center' valign='bottom' size='7'>$ABI_NUMBER</td>";
		else
			$html .= "<td align='center'></td>";
		$html .="</tr>";
		ora_fetch($cur);		
	}	
}
$html .= "</table>";
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