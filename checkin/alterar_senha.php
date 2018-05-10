<?php

        include "db.php";
		
		if(!isset($_POST["pemail"])){
		   exit;
		}

		
		$email 				= $_POST["pemail"];
		$senha				= $_POST["psenha"];
	
		
		 $verifica_existencia = "select email, nome from ap_registro_usuario where email =  '". $email ."'";
		 $exec_row = $conn->query($verifica_existencia );

		 if($exec_row->num_rows != 0)
		 {
			 $row = $exec_row->fetch_row();
			 
			 $query = 'update ap_registro_usuario set senha = ? where email =  ?';

			 $stm = $conn->prepare($query);
			 $stm->bind_param("ss", $senha, $email);
			 if( $stm->execute() )
			 {
				$retorno = array("retorno" => 'YES', "nome" => $row[1]);
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