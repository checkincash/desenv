<?php
    include "db.php";
	
	if(!isset($_POST["pemail"])){
	   exit;
	}

	

	$email 				= $_POST["pemail"];
	

	
	 $verifica_existencia = "select nome, sobrenome, data_nascimento, senha, rua, numero, complemento, bairro, cidade, estado, cep, codigoarea, codigopais, celular, pk_usuario, iscompleto  from ap_registro_usuario where email =  '". $email ."'";
	 $exec_row = $conn->query($verifica_existencia );

	 if($exec_row->num_rows > 0)
	 {

		$row = $exec_row->fetch_row();

		$retorno = array("retorno" => 'YES', "nome" => $row[0], "sobrenome" => $row[1], "data_nascimento" => $row[2], "senha" => $row[3], "rua" => $row[4], "numero"=> $row[5], "complemento"=> $row[6], "bairro"=> $row[7], "cidade"=> $row[8], "estado"=> $row[9], "cep"=> $row[10], "codigoarea"=> $row[11], "codigopais"=> $row[12], "celular"=> $row[13], "pk_usuario" => $row[14], "iscompleto" => $row[15] );

	 }
	 else
	 {
		$retorno = array("retorno" => 'NO');
	 }
	 echo json_encode($retorno);


	 $conn->close();

?>