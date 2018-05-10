<?php

        include "db.php";
		
	 
		if(!isset($_POST["pcontrato"]))
		{
		   exit;
		}

        $dataativacao 		= date("Y-m-d H:i:s");
		$pcontrato 		    = $_POST["pcontrato"];
	    $pusuario           = "0";
	    $ptoken             = $_POST["ptoken"];
	    $idpublicador       = "0"; 
	    $pinqtde            = "0";
	 
	
		
	    $verifica_existencia = "select  a.pk_contrato, c.pk_publicador, a.cnpj, a.razao, b.pincash_saldo, d.pincash_qtde, d.fk_usuario FROM ap_contrato a 
 inner join ap_pincash_contrato_creditos b on a.pk_contrato = b.fk_contrato
 inner join ap_contrato_publicador c on a.pk_contrato = c.fk_contrato 
 inner join ap_pincash_user_mov d on c.pk_publicador = d.fk_contrato_publicador 
       and d.token = '". $ptoken ."' where a.pk_contrato = '". $pcontrato ."'
       and c.situacao = 0 and d.token_validado = 0";
	    
	    
	    if ($result = $conn->query($verifica_existencia)) {

				/* fetch object array */
				while ($row = $result->fetch_row()) {
					$idpublicador =  $row[1];
					$pinqtde      =  $row[5];
					$pusuario     =  $row[6];
				}

				/* free result set */
				$result->close();
			}
        
         // Inicia a rotina de atualizacao dos saldos
         $exec_row = $conn->query($verifica_existencia );
	
		 if($exec_row->num_rows > 0)
		 {
			 $query = "update ap_pincash_contrato_creditos set pincash_saldo = pincash_saldo - (
			 select pincash_qtde from ap_pincash_user_mov where fk_contrato_publicador = ?  
			 and fk_usuario = ? and token = ? ) where fk_contrato = ?";

			 $stm = $conn->prepare($query);
			 $stm->bind_param("ssss", $idpublicador, $pusuario, $ptoken, $pcontrato);
			 if( $stm->execute() )
			 {
			 
			     $conn->commit();
			     
				// atualiza saldo usuario -> ap_pincash_user
			 	 $query = "select fk_usuario from ap_pincash_user where fk_usuario = '". $pusuario ."'";
		 	     $ex_row = $conn->query( $query );
		         // Insere
		 		 if($ex_row->num_rows == 0)
				 {
				 
				     $query = 'insert into ap_pincash_user (fk_usuario, saldo_flutuante, saldo_acumulado) values (?, ?, ?)';
					 $stm = $conn->prepare($query);
					 $stm->bind_param("sss", $pusuario, $pinqtde, $pinqtde);
					 if( $stm->execute() )
					 {
 
						 $conn->commit();
	 
						// atualiza tabela do usuario ap_pincash_user_mov 
						 $query = "update  ap_pincash_user_mov set token_validado = '1', token_data_ativacao = '". $dataativacao ."' 
						 where fk_contrato_publicador = '". $idpublicador ."'  and fk_usuario = '". $pusuario ."' and token = '". $ptoken ."'";
						 
						 $ex_row = $conn->query( $query );

					     $retorno = array("retorno" => 'YES', "pin_mov" => $pinqtde  ) ;
						
	 
					 }
				 }
				 else
				 {
						
				     $query = 'update ap_pincash_user set saldo_flutuante = saldo_flutuante + ?, saldo_acumulado = saldo_acumulado + ?
				     where fk_usuario = ? ';
					 $stm = $conn->prepare($query);
					 $stm->bind_param("sss",  $pinqtde, $pinqtde, $pusuario);
					 if( $stm->execute() )
					 {
 
						 $conn->commit();
	 
						// atualiza tabela do usuario ap_pincash_user_mov 
						 $query = "update ap_pincash_user_mov set token_validado = '1', token_data_ativacao = '". $dataativacao ."' 
						 where fk_contrato_publicador = '". $idpublicador ."'  and fk_usuario = '". $pusuario ."' and token = '". $ptoken ."'";
						 
						 $ex_row = $conn->query( $query );
						 
						 // faz checout
						 $query = "update ap_contrato_coletor set ischeckin = 0 where fk_publicador =  '". $idpublicador ."' and fk_usuario = '". $pusuario ."' and token = '". $ptoken ."'";

 						 $ex_row = $conn->query( $query );
					
						 $retorno = array("retorno" => 'YES', "pin_mov" => $pinqtde ) ;
						
	 
					 }
					  else
						 {
							$retorno = array("retorno" => 'NAO_ATUALIZOU_SALDO');
						 }
				 }
				 
				 
				 
			 }
			 else
			 {
				$retorno = array("retorno" => 'NAO_ATUALIZOU_SALDO');
			 }
			 
			 $stm->close();
			     
			
			 
		 }
		 else
		 {
			$retorno = array("retorno" => 'TOKEN_NAO_PODE_SER_PROCESSADO');
		 }
		 echo '['. json_encode($retorno) .']'; //normaliza array

		
		 $conn->close();

?>