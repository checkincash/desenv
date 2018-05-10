<?php

        include "db.php";
		
		if(!isset($_POST["pemail"])){
		   exit;
		}

		$datareg 			= date("Y-m-d H:i:s");
		$codigopais 		= "+55";
		$email 				= $_POST["pemail"];
		$nome 				= $_POST["pnome"];
		$sobrenome 			= $_POST["psobrenome"];
		$senha				= $_POST["psenha"];

		
		 $verifica_existencia = "select email from ap_registro_usuario where email =  '". $email ."'";
		 $exec_row = $conn->query($verifica_existencia );

		 if($exec_row->num_rows == 0)
		 {
			 $query = 'insert into ap_registro_usuario (codigopais, email, nome, sobrenome, senha, dataregistro) values (?, ?, ?, ?, ?, ?)';

			 $stm = $conn->prepare($query);
			 $stm->bind_param("ssssss", $codigopais, $email, $nome, $sobrenome, $senha, $datareg);
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
			$retorno = array("retorno" => 'JA_EXISTE');
		 }
		 echo json_encode($retorno);

		
		 $conn->close();

?>