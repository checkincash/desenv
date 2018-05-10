<?php

        include "db.php";
		
		if(!isset($_POST["pemail"])){
		   exit;
		}

		
		$email 				= $_POST["pemail"];
		$nome 				= $_POST["pnome"];
		$sobrenome 			= $_POST["psobrenome"];
		$cep				= $_POST["pcep"];
		$rua				= $_POST["prua"];
		$numero				= $_POST["pnumero"];
		$complemento		= $_POST["pcomplemento"];
		$bairro				= $_POST["pbairro"];
		$cidade				= $_POST["pcidade"];
		$estado				= $_POST["pestado"];
		
		
		
		 $verifica_existencia = "select email, nome from ap_registro_usuario where email =  '". $email ."'";
		 $exec_row = $conn->query($verifica_existencia );

		 if($exec_row->num_rows != 0)
		 {
			 $row = $exec_row->fetch_row();
			 
			 $query = 'update ap_registro_usuario set nome = ?, sobrenome = ?,  cep = ?, rua=?,numero=?,complemento=?,bairro=?,cidade=?, estado=?, iscompleto = 1 where email =  ?';

			 $stm = $conn->prepare($query);
			 $stm->bind_param("ssssssssss", $nome, $sobrenome,  $cep, $rua, $numero, $complemento, $bairro, $cidade, $estado, $email);
			 if( $stm->execute() )
			 {
				$retorno = array("retorno" => 'YES', "nome" => $row[0]);
			 }
			 else
			 {
				$retorno = array("retorno" => 'NO');
			 }
			 
			  $stm->close();
		 }
		 else
		 {
			$retorno = array("retorno" => 'NAO_EXISTE');
		 }
		 echo json_encode($retorno);

		
		 $conn->close();

?>