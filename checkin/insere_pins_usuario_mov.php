<?php

    include "db.php";
		
	if(!isset($_POST["pusuario"])){
	   exit;
	}

  	$datamov	   = date("Y-m-d H:i:s");
  	$publicador    = $_POST["publicador"];
  	$idsorteio     = $_POST["idsorteio"];
  	$pusuario      = $_POST["pusuario"];
  	$qtdepin       = $_POST["qtdepin"];
  	$desconto      = $_POST["desconto"];
  	$token         = $_POST["token"];
  	
	
	
		
		 $verifica_existencia = "select fk_usuario from ap_pincash_user_mov where fk_usuario = '" . $pusuario . "' and fk_contrato_publicador = '". $publicador ."' and token = '". $token ."'";
		 $exec_row = $conn->query($verifica_existencia );

		 if($exec_row->num_rows == 0)
		 {
			
			 $query = 'insert into ap_pincash_user_mov (data_lancamento, fk_contrato_publicador, fk_pincash_sorteio, fk_usuario, pincash_qtde, desconto_recebido, token ) values (?, ?, ?, ?, ?, ?, ?)';

			 $stm = $conn->prepare($query);
			 $stm->bind_param("sssssss", $datamov, $publicador, $idsorteio, $pusuario, $qtdepin, $desconto, $token);
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
		    $retorno = array("retorno" => 'NO');
		    $stm->close();
		 
		 }
		 echo  json_encode($retorno) ;

		
		 $conn->close();

?>