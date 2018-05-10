<?php
    include "db.php";
	
	if(!isset($_POST["pemail"])){
	   exit;
	}


	$email 				= $_POST["pemail"];
	

	
	 $verifica_existencia = "select pin_recupera_senha, nome, sobrenome from ap_registro_usuario where email =  '". $email ."'";
	 $exec_row = $conn->query($verifica_existencia );

	 if($exec_row->num_rows > 0)
	 {

		$row = $exec_row->fetch_row();

		$retorno = array("retorno" => 'YES', "token" => $row[0], "nome" => $row[1], "sobrenome" => $row[2] );

	 }
	 else
	 {
		$retorno = array("retorno" => 'NO');
	 }
	 echo json_encode($retorno);


	 $conn->close();

?>