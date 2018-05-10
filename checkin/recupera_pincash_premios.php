<?php
 include "db.php";
 
if(!isset($_POST["pusuario"]))
{
   exit;
}

$pusuario = $_POST["pusuario"];

$dados_checkin = "SELECT a.pk_sorteio_cabe_pincash as id, b.foto_premiacao, b.pincash_premio as pin_necessario, b.label, datafim as data_sorteio,
 ( case when (select saldo_flutuante from ap_pincash_user where fk_usuario = '". $pusuario . "' ) is null then 0 else 
 (select saldo_flutuante from ap_pincash_user where fk_usuario = '". $pusuario . "' ) end) as saldo_usuario,
 (select max(pincash_premio) + 20 FROM ap_pincash_sorteio_mov inner join ap_pincash_sorteio on  ap_pincash_sorteio_mov.fk_cabe_sorteio = ap_pincash_sorteio.pk_sorteio_cabe_pincash 
 where ap_pincash_sorteio.ativo = 1) as maxpin, b.titulo, b.chamada FROM ap_pincash_sorteio a
 inner join ap_pincash_sorteio_mov b on a.pk_sorteio_cabe_pincash = b.fk_cabe_sorteio where a.ativo = 1 order by b.pincash_premio ";




 $exec_row = $conn->query($dados_checkin );

 $result = array();

 while($info = $exec_row->fetch_assoc())
 {
      $result[] = $info;
 }


 echo json_encode($result);

 $conn->close();

?>