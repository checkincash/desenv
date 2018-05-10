<?php

//verifica se busca parametros via GET ou POST
$requestType = filter_input(INPUT_SERVER, 'REQUEST_METHOD');
if ($requestType == 'GET') {
    $apikey = filter_input(INPUT_GET, 'apikey');
    $contratoId = filter_input(INPUT_GET, 'contrato', FILTER_VALIDATE_INT);
} else if ($requestType == 'POST') {
    $apikey = filter_input(INPUT_POST, 'apikey');
    $contratoId = filter_input(INPUT_POST, 'contrato', FILTER_VALIDATE_INT);
}
//Se requisição for GET ou POST (praticamente sempre vai ser)
if (in_array($requestType, array('GET', 'POST'))) {
    $chave = md5('cH#cK_!n*c45h'); //092865a8e6b63f1db3e06eea3e3652cd
    
    require_once './model/apConfiguracaoDAO.php';
    require_once './model/apContratoDAO.php';
    require_once './model/apPincashCreditoDAO.php';
    require_once './PHPMailer/PHPMailerAutoload.php';
    $mail = new PHPMailer(true);
    
    \date_default_timezone_set('America/Sao_Paulo');

    $assunto = "Alerta Pincash";
    
    if(empty($apikey) || $apikey != $chave || empty($contratoId)) {
        echo json_encode(array(
            'result' => 0,
            'msg' => 'Parâmetros incorretos!'
        ), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        return;
    }
    
    //BUSCA CONTRATO
    $contratoDAO = new apContratoDAO();
    $contrato = $contratoDAO->select('*', "pk_contrato = $contratoId")->fetch(PDO::FETCH_OBJ);
    if(empty($contrato)) {
        echo json_encode(array(
            'result' => 0,
            'msg' => 'Contrato não encontrado'
        ), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        return;
    }
    
    //BUSCA PINCASH SALDO CONTRATO
    $pincashCredDAO = new apPincashCreditoDAO();
    $pincashCred = $pincashCredDAO->select('*', "fk_contrato = $contrato->pk_contrato")->fetch(PDO::FETCH_OBJ);
    
    //BUSCA CONFIGURAÇOES (TEXTO E QUATIDADE ALERTAS)
    $configuracaoDAO = new apConfiguracaoDAO();
    $conf = $configuracaoDAO->select('*', 'id = 1')->fetch(PDO::FETCH_OBJ);
    
    $msg = $conf->alerta_pincash_msg;
    
    $msg = str_replace('{lojista}', $contrato->fantasia, $msg);
    $msg = str_replace('{saldo}', $pincashCred->pincash_saldo, $msg);
    
    $body = '<div><img src="http://checkincash.com.br/images/logo-checkincash.png"/></div><br/>'
            .'<span style="white-space: pre-wrap;">'.$msg.'</span>';
    
    try {
        $mail->IsSMTP();
        $mail->Host = 'checkincash.com.br'; // Endereço do servidor SMTP (Autenticação, utilize o host smtp.seudomínio.com.br)
        $mail->SMTPAuth = true;  // Usar autenticação SMTP (obrigatório para smtp.seudomínio.com.br)
        $mail->Port = 465; //  Usar 587 porta SMTP
        $mail->SMTPSecure = 'ssl';
        $mail->Username = 'naoresponda@checkincash.com.br'; // Usuário do servidor SMTP (endereço de email)
        $mail->Password = 'a1c2R102018'; // Senha do servidor SMTP (senha do email usado)
        $mail->CharSet = 'UTF-8';
//        $mail->SMTPDebug = 1;
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        //Define o remetente
        // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=    
        $mail->SetFrom('naoresponda@checkincash.com.br', 'CHECK-INcash'); //Seu e-mail
//        $mail->AddReplyTo('seu@e-mail.com.br', 'Nome'); //Seu e-mail
        $mail->Subject = $assunto; //Assunto do e-mail
        //Define os destinatário(s)
        //=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
        $mail->AddAddress($contrato->email, $contrato->fantasia);
        
        //Campos abaixo são opcionais 
        //=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
//        $mail->AddCC('usuario@email.com', 'Nome'); // Copia
        $mail->AddBCC('checkin.tecnologia@gmail.com', 'Checkin Tecnologia`'); // Cópia Oculta
        
        //Define o corpo do email
        $mail->MsgHTML($body);
        
        if ($mail->Send()) {
            echo json_encode(array(
                'result' => 1,
                'msg' => 'Alerta enviado com sucesso!'
            ), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        } else {
            echo json_encode(array(
                'result' => 0,
                'msg' => $mail->ErrorInfo
            ), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }
    } catch (phpmailerException $exc) {
        echo $exc->errorMessage();
    }
} else {
    header('location: ./');
}