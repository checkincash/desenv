<?php
 include "db.php";
 
if(!isset($_POST["platitude"]))
{
   exit;
}

 
$platitude 		 	= $_POST["platitude"];
$plongitude			= $_POST["plongitude"];
$pcategoria         = $_POST["pcategoria"];
$pdistancia         = $_POST["pdistancia"];
$pusuario           = $_POST["pusuario"];


if( $pcategoria != 0 )
{

	$dados_checkin = "SELECT b.pk_publicador as 'id', a.fantasia as 'razao',b.destaque,
	 ( case when a.classificacao1 is null then '' else a.classificacao1 end) classificacao1, 
	 ( case when a.classificacao2 is null then '' else a.classificacao2 end) classificacao2,
	 c.descricao as 'categoria', a.cnpj, b.nomenclatura, b.abreviatura, b.rua, b.numero, b.complemento, b.bairro, b.cidade, b.estado, b.cep,
	 a.telefone, b.pdesconto, a.fachada, b.foto_publicacao, b.texto_publicacao, b.foto_premiacao, b.texto_premiacao, b.gera_dados,
	 ( select pk_sorteio_cabe_pincash from ap_pincash_sorteio where ativo = 1 ) as idsorteio, 
	 (case when p.pincash_qtde is null then 0 else p.pincash_qtde end) as pincash,
	 (case when f.ischeckin is null then '0' else f.ischeckin end) as ischeckin,
     (case when f.contador is null then 0 else f.contador end) as contador,
	 (case when f.validade is null then now() else f.validade end) as validade,
	 (case when f.token is null then '0000' else f.token end) as token,
	 (case when b.pseg is null then '0'else b.pseg end) as segunda,
	 (case when b.pter is null then '0'else b.pter end) as terca,
	 (case when b.pqua is null then '0'else b.pqua end) as quarta,
	 (case when b.pqui is null then '0'else b.pqui end) as quinta,
	 (case when b.psex is null then '0'else b.psex end) as sexta,
	 (case when b.psab is null then '0'else b.psab end) as sabado,
	 (case when b.pdom is null then '0'else b.pdom end) as domingo,
	 (case when b.pinseg is null then '0'else b.pinseg end) as pin_segunda,
	 (case when b.pinter is null then '0'else b.pinter end) as pin_terca,
	 (case when b.pinqua is null then '0'else b.pinqua end) as pin_quarta,
	 (case when b.pinqui is null then '0'else b.pinqui end) as pin_quinta,
	 (case when b.pinsex is null then '0'else b.pinsex end) as pin_sexta,
	 (case when b.pinsab is null then '0'else b.pinsab end) as pin_sabado,
	 (case when b.pindom is null then '0'else b.pindom end) as pin_domingo,
	 
	 (6371 * acos(
	  cos( radians(" . $platitude .") )
	  * cos( radians( b.latitude ) )
	  * cos( radians( b.longitude ) - radians(". $plongitude .") )
	  + sin( radians(" . $platitude . ") )
	  * sin( radians( b.latitude ) )
	  )
	 ) AS distancia
	 FROM ap_contrato a inner join ap_contrato_publicador b on a.pk_contrato = b.fk_contrato
	 inner join ap_classificacao c on a.fk_classificacao = c.pk_classificacao
	 left outer join ap_contrato_coletor f on b.pk_publicador = f.fk_publicador and f.fk_usuario = ". $pusuario . "
	 left outer join ap_pincash_user_mov p on p.fk_usuario = ". $pusuario ." and  p.fk_contrato_publicador = b.pk_publicador and p.token = f.token
	 where a.ativo = 1 and b.situacao = 0 and c.pk_classificacao = " . $pcategoria . "  
	 HAVING distancia < ". $pdistancia ." 
	 ORDER BY distancia  
	";

}
else
{
	$dados_checkin = "SELECT b.pk_publicador as 'id', a.fantasia as 'razao',
	( case when a.classificacao1 is null then '' else a.classificacao1 end) classificacao1, 
	( case when a.classificacao2 is null then '' else a.classificacao2 end) classificacao2,
	 b.destaque, c.descricao as 'categoria', a.cnpj, b.nomenclatura, b.abreviatura, b.rua, b.numero, b.complemento, b.bairro, b.cidade,
	 b.estado, b.cep, a.telefone, b.pdesconto, a.fachada, b.foto_publicacao, b.texto_publicacao, b.foto_premiacao, b.texto_premiacao, b.gera_dados,
	( select pk_sorteio_cabe_pincash from ap_pincash_sorteio where ativo = 1 ) as idsorteio, 
	(case when p.pincash_qtde is null then 0 else p.pincash_qtde end) as pincash,
	(case when f.ischeckin is null then '0' else f.ischeckin end) as ischeckin,
    (case when f.contador is null then 0 else f.contador end) as contador,
    (case when f.validade is null then now() else f.validade end) as validade,	
	(case when f.token is null then '0000' else f.token end) as token,
    (case when b.pseg is null then '0'else b.pseg end) as segunda,
	(case when b.pter is null then '0'else b.pter end) as terca,
	(case when b.pqua is null then '0'else b.pqua end) as quarta,
	(case when b.pqui is null then '0'else b.pqui end) as quinta,
	(case when b.psex is null then '0'else b.psex end) as sexta,
	(case when b.psab is null then '0'else b.psab end) as sabado,
	(case when b.pdom is null then '0'else b.pdom end) as domingo,
	(case when b.pinseg is null then '0'else b.pinseg end) as pin_segunda,
	 (case when b.pinter is null then '0'else b.pinter end) as pin_terca,
	 (case when b.pinqua is null then '0'else b.pinqua end) as pin_quarta,
	 (case when b.pinqui is null then '0'else b.pinqui end) as pin_quinta,
	 (case when b.pinsex is null then '0'else b.pinsex end) as pin_sexta,
	 (case when b.pinsab is null then '0'else b.pinsab end) as pin_sabado,
	 (case when b.pindom is null then '0'else b.pindom end) as pin_domingo,
		
	(6371 * acos(
	  cos( radians(" . $platitude .") )
	  * cos( radians( b.latitude ) )
	  * cos( radians( b.longitude ) - radians(". $plongitude .") )
	  + sin( radians(" . $platitude . ") )
	  * sin( radians( b.latitude ) )
	  )
	 ) AS distancia
	 FROM ap_contrato a inner join ap_contrato_publicador b on a.pk_contrato = b.fk_contrato
	 inner join ap_classificacao c on a.fk_classificacao = c.pk_classificacao
	 left outer join ap_contrato_coletor f on b.pk_publicador = f.fk_publicador and f.fk_usuario = ". $pusuario . "
	 left outer join ap_pincash_user_mov p on p.fk_usuario = ". $pusuario ." and  p.fk_contrato_publicador = b.pk_publicador and p.token = f.token
	 where a.ativo = 1 and b.situacao = 0  HAVING distancia < ". $pdistancia ." 
	 ORDER BY  distancia  
	";
}	



 $exec_row = $conn->query($dados_checkin );

 $result = array();

 while($info = $exec_row->fetch_assoc())
 {
      $result[] = $info;
 }


 echo json_encode($result);

 $conn->close();

?>