<?php
 include "db.php";
 
if(!isset($_POST["pcontrato"]))
{
   exit;
}

$pcontrato		 	= $_POST["pcontrato"];

$dados_checkin = "select  a.pk_contrato, a.cnpj, a.razao, b.pincash_saldo FROM ap_contrato a 
 inner join ap_pincash_contrato_creditos b on a.pk_contrato = b.fk_contrato where a.pk_contrato = '". $pcontrato ."'" ;




 $exec_row = $conn->query($dados_checkin );

 $result = array();

 while($info = $exec_row->fetch_assoc())
 {
      $result[] = $info;
 }


 echo json_encode($result);

 $conn->close();

?>
