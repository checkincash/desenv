<?php
 include "db.php";
 
if(!isset($_POST["pusuario"]))
{
   exit;
}

$pusuario           = $_POST["pusuario"];

$dados_checkin = "SELECT a.fk_usuario, a.data_lancamento, a.fk_contrato_publicador, c.fantasia as razao, b.abreviatura, c.cnpj, b.cidade, b.estado, c.fachada, a.pincash_qtde, a.desconto_recebido,
a.token, a.token_validado, a.token_data_ativacao, ( case when d.saldo_flutuante is null then 0 else d.saldo_flutuante end) as saldopin
FROM `ap_pincash_user_mov` a inner join ap_contrato_publicador b on a.fk_contrato_publicador = b.pk_publicador
      inner join ap_pincash_sorteio l on l.pk_sorteio_cabe_pincash =  a.fk_pincash_sorteio
      inner join ap_contrato c on c.pk_contrato = b.fk_contrato
      left outer join ap_pincash_user d on a.fk_usuario = d.fk_usuario
WHERE a.fk_usuario = '". $pusuario ."'  and l.ativo = 1 and a.token_validado = 1 and data_lancamento > ADDDATE( now(), INTERVAL -90 DAY) order by a.data_lancamento desc";




 $exec_row = $conn->query($dados_checkin );

 $result = array();

 while($info = $exec_row->fetch_assoc())
 {
      $result[] = $info;
 }


 echo json_encode($result);

 $conn->close();

?>