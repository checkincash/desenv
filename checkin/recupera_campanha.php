<?php
    include "db.php";
	
	if(!isset($_POST["pcodigopub"])){
	   exit;
	}


	$platitude 		 	= $_POST["platitude"];
	$plongitude			= $_POST["plongitude"];
	$pcodigopub     = $_POST["pcodigopub"];



	
	 $verifica_existencia = "SELECT b.pk_publicador as 'id', a.fantasia as 'razao', c.descricao as 'categoria',
	  a.cnpj, b.nomenclatura as 'titulo', b.rua, b.numero, b.complemento, b.bairro, b.cidade, b.estado, b.cep, a.telefone, b.pdesconto, b.foto_publicacao, b.texto_publicacao, b.foto_premiacao, b.texto_premiacao, b.gera_dados,
	 (6371 * acos(
	  cos( radians(" . $platitude .") )
	  * cos( radians( b.latitude ) )
	  * cos( radians( b.longitude ) - radians(". $plongitude .") )
	  + sin( radians(" . $platitude . ") )
	  * sin( radians( b.latitude ) )
	  )
	 ) AS distancia, b.latitude, b.longitude, (case when seg_m_de is not null then substring(seg_m_de,1,5) else '00:00' end) seg_m_de,
	 (case when seg_m_ate is not null then substring(seg_m_ate,1,5) else '00:00' end) seg_m_ate,
	 (case when seg_t_de is not null then substring(seg_t_de,1,5) else '00:00' end) seg_t_de,
	 (case when seg_t_ate is not null then substring(seg_t_ate,1,5) else '00:00' end) seg_t_ate,
	 (case when ter_m_de is not null then substring(ter_m_de,1,5) else '00:00' end) ter_m_de,
	 (case when ter_m_ate is not null then substring(ter_m_ate,1,5) else '00:00' end) ter_m_ate,
	 (case when ter_t_de is not null then substring(ter_t_de,1,5) else '00:00' end) ter_t_de,
	 (case when ter_t_ate is not null then substring(ter_t_ate,1,5) else '00:00' end) ter_t_ate,
	 
	 (case when qua_m_de is not null then substring(qua_m_de,1,5) else '00:00' end) qua_m_de,
	 (case when qua_m_ate is not null then substring(qua_m_ate,1,5) else '00:00' end) qua_m_ate,
	 (case when qua_t_de is not null then substring(qua_t_de,1,5) else '00:00' end) qua_t_de,
	 (case when qua_t_ate is not null then substring(qua_t_ate,1,5) else '00:00' end) qua_t_ate,
	 
	 (case when qui_m_de is not null then substring(qui_m_de,1,5) else '00:00' end) qui_m_de,
	 (case when qui_m_ate is not null then substring(qui_m_ate,1,5) else '00:00' end) qui_m_ate,
	 (case when qui_t_de is not null then substring(qui_t_de,1,5) else '00:00' end) qui_t_de,
	 (case when qui_t_ate is not null then substring(qui_t_ate,1,5) else '00:00' end) qui_t_ate,
	 
	 (case when sex_m_de is not null then substring(sex_m_de,1,5) else '00:00' end) sex_m_de,
	 (case when sex_m_ate is not null then substring(sex_m_ate,1,5) else '00:00' end) sex_m_ate,
	 (case when sex_t_de is not null then substring(sex_t_de,1,5) else '00:00' end) sex_t_de,
	 (case when sex_t_ate is not null then substring(sex_t_ate,1,5) else '00:00' end) sex_t_ate,
	 
	 (case when sab_m_de is not null then substring(sab_m_de,1,5) else '00:00' end) sab_m_de,
	 (case when sab_m_ate is not null then substring(sab_m_ate,1,5) else '00:00' end) sab_m_ate,
	 (case when sab_t_de is not null then substring(sab_t_de,1,5) else '00:00' end) sab_t_de,
	 (case when sab_t_ate is not null then substring(sab_t_ate,1,5) else '00:00' end) sab_t_ate,
	 
	 (case when dom_m_de is not null then substring(dom_m_de,1,5) else '00:00' end) dom_m_de,
	 (case when dom_m_ate is not null then substring(dom_m_ate,1,5) else '00:00' end) dom_m_ate,
	 (case when dom_t_de is not null then substring(dom_t_de,1,5) else '00:00' end) dom_t_de,
	 (case when dom_t_ate is not null then substring(dom_t_ate,1,5) else '00:00' end) dom_t_ate,
	(case when b.pseg is null then '0'else b.pseg end) as psegunda,
	(case when b.pter is null then '0'else b.pter end) as pterca,
	(case when b.pqua is null then '0'else b.pqua end) as pquarta,
	(case when b.pqui is null then '0'else b.pqui end) as pquinta,
	(case when b.psex is null then '0'else b.psex end) as psexta,
	(case when b.psab is null then '0'else b.psab end) as psabado,
	(case when b.pdom is null then '0'else b.pdom end) as pdomingo,
	( case when a.classificacao1 is null then '' else a.classificacao1 end) classificacao1, 
	( case when a.classificacao2 is null then '' else a.classificacao2 end) classificacao2,
	( case when a.website is null then 'https://www.checkincash.com.br' else a.website end) website,
	a.fachada
	 FROM ap_contrato a inner join ap_contrato_publicador b on a.pk_contrato = b.fk_contrato
	 inner join ap_classificacao c on a.fk_classificacao = c.pk_classificacao
	 where b.pk_publicador = " . $pcodigopub . " ORDER BY distancia";
	
	 $exec_row = $conn->query($verifica_existencia );

	 if($exec_row->num_rows > 0)
	 {

		$row = $exec_row->fetch_row();

		$retorno = array("retorno" => 'YES', "id" => $row[0], "razao" => $row[1], "categoria" => $row[2], "cnpj" => $row[3], "titulo" => $row[4], "rua"=> $row[5], "numero" => $row[6], 
		"complemento" => $row[7], "bairro" => $row[8], "cidade" => $row[9], "estado" => $row[10], "cep" => $row[11], "telefone" => $row[12], "desconto" => $row[13], "foto_publicacao" => $row[14],
		"texto_publicacao" => $row[15],"foto_premiacao" => $row[16],"texto_premiacao" => $row[17],"gera_dados" => $row[18], "distancia" => $row[19], "latitude" => $row[20], "longitude" => $row[21],
		"segunda" => $row[22] . "  " . $row[23] . "  " . $row[24] . "  " . $row[25],
		"terca" => $row[26] . "  " . $row[27] . "  " . $row[28] . "  " . $row[29],
		"quarta" => $row[30] . "  " . $row[31] . "  " . $row[32] . "  " . $row[33],
		"quinta" => $row[34] . "  " . $row[35] . "  " . $row[36] . "  " . $row[37],
		"sexta" => $row[38] . "  " . $row[39] . "  " . $row[40] . "  " . $row[41],
		"sabado" => $row[42] . "  " . $row[43] . "  " . $row[44] . "  " . $row[45],
		"domingo" => $row[46] . "  " . $row[47] . "  " . $row[48] . "  " . $row[49],
		"psegunda"=> $row[50], "pterca" => $row[51], "pquarta"=> $row[52], "pquinta" => $row[53],
		 "psexta"=> $row[54], "psabado"=> $row[55], "pdomingo" => $row[56], "classificacao1" => $row[57], "classificacao2" => $row[58],
		 "website"=> $row[59], "fachada"=> $row[60]
		
		);



	 }
	 else
	 {
		$retorno = array("retorno" => 'NO');
	 }
	 echo json_encode($retorno);


	 $conn->close();

?>