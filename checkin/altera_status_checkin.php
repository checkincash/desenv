<?php

    include "db.php";
		
	if(!isset($_POST["pfkpublicador"])){
	   exit;
	}


	$pfkpublicador     	= $_POST["pfkpublicador"];
	$pfkusuario       	= $_POST["pfkusuario"];
	$pischeckin       	= $_POST["pischeckin"];
	$ptoken       		= $_POST["ptoken"];
	$datamov			= date("Y-m-d H:i:s");
	

		
		 $verifica_existencia = "select pk_coletor from ap_contrato_coletor where fk_publicador = '" . $pfkpublicador . "' and fk_usuario = '" . $pfkusuario ."'" ;
		 $exec_row = $conn->query($verifica_existencia );

		 if($exec_row->num_rows != 0)
		 {
			 $row = $exec_row->fetch_row();
			 
			 $query = 'update ap_contrato_coletor set ischeckin = ?, contador = contador + 1, token = ?, validade = ?  where fk_publicador =  ? and fk_usuario = ?';

			 $stm = $conn->prepare($query);
			 $stm->bind_param("sssss", $pischeckin, $ptoken, $datamov, $pfkpublicador, $pfkusuario);
			 if( $stm->execute() )
			 {
				$retorno = array("retorno" => 'YES');
			 }
			 else
			 {
				$retorno = array("retorno" => 'NO');
			 }
			 
			  $stm->close();
		 }
		 else
		 {
			 $query = 'insert into ap_contrato_coletor (fk_publicador, fk_usuario, token) values (?, ?, ?)';

			 $stm = $conn->prepare($query);
			 $stm->bind_param("sss", $pfkpublicador, $pfkusuario, $ptoken);
			 if( $stm->execute() )
			 {
				$retorno = array("retorno" => 'YES');
			 }
			 else
			 {
				$retorno = array("retorno" => 'NO');
			 }
			 
			  $stm->close();
		 }
		 echo json_encode($retorno);

		
		 $conn->close();

?>