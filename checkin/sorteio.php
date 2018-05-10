<?php
 include "db.php";
 


$dados_checkin = "SELECT pk_sorteio_cabe_pincash, ativo, datainicio, datafim, texto_campanha, foto_campanha FROM ap_pincash_sorteio where ativo = 1";


 $exec_row = $conn->query($dados_checkin );

 $result = array();

 while($info = $exec_row->fetch_assoc())
 {
      $result[] = $info;
 }


 echo json_encode($result);

 $conn->close();

?>