<?php
 include "db.php";
 

if(!isset($_POST["id"]))
{
   exit;
}


$id		 	= $_POST["id"];


$dados_checkin = "SELECT pk_mov_sorteio_mv, fk_cabe_sorteio, foto_catalogo, foto_premiacao, texto_premiacao, pincash_premio, titulo, chamada FROM ap_pincash_sorteio_mov
                  where fk_cabe_sorteio = '". $id ."' order by pincash_premio ";




 $exec_row = $conn->query($dados_checkin );

 $result = array();

 while($info = $exec_row->fetch_assoc())
 {
      $result[] = $info;
 }


 echo json_encode($result);

 $conn->close();

?>