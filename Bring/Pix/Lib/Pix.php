<?php  /** @author contato@bring.com.br */

namespace Bring\Pix\Lib;
/**
 * Pix Integration Library
 * Access Pix for payments integration
 *
 *
 */

use Endroid\QrCode\QrCode;

class Pix {
    /**
     *
     */
    const version = "0.1.0";

    public static function getPix($chaveRecebedor, $descricao, $valor, $nomeRecebedor, $cidadeRecebedor, $identificador = '***') {
        include __DIR__ . DIRECTORY_SEPARATOR . 'qrcodepix/funcoes_pix.php';

        $nomeRecebedor = self::removeSpecial(substr($nomeRecebedor, 0, 30));
        $cidadeRecebedor = self::removeSpecial(substr($cidadeRecebedor, 0, 15)); 
        $descricao = self::removeSpecial(substr($descricao, 0, 70));
        $identificador = self::removeSpecial(substr($identificador, 0, 25));
        
        $px[00]="01"; //Payload Format Indicator, Obrigatório, valor fixo: 01
        $px[01]="12"; //Se o valor 12 estiver presente, significa que o BR Code só pode ser utilizado uma vez. 
        $px[26][00]="BR.GOV.BCB.PIX"; //Indica arranjo específico; “00” (GUI) obrigatório e valor fixo: br.gov.bcb.pix
        $px[26][01]=$chaveRecebedor; //Chave do destinatário do pix, pode ser EVP, e-mail, CPF ou CNPJ.
        $px[26][02]=$descricao; // Descrição da transação, opcional.

        $px[52]="0000"; //Merchant Category Code “0000” ou MCC ISO18245
        $px[53]="986"; //Moeda, “986” = BRL: real brasileiro - ISO4217
        $px[54]=$valor; //Valor da transação, se comentado o cliente especifica o valor da transação no próprio app. Utilizar o . como separador decimal. Máximo: 13 caracteres.
        $px[58]="BR"; //“BR” – Código de país ISO3166-1 alpha 2
        $px[59]=$nomeRecebedor; //Nome do beneficiário/recebedor. Máximo: 25 caracteres.
        $px[60]=$cidadeRecebedor; //Nome cidade onde é efetuada a transação. Máximo 15 caracteres.
        $px[62][05]=$identificador; //Identificador de transação, quando gerado automaticamente usar ***. Vide nota abaixo.

        //print_r($px);
        $pix=montaPix($px);

        /*
        # A função montaPix prepara todos os campos existentes antes do CRC (campo 63).
        # O CRC deve ser calculado em cima de todo o conteúdo, inclusive do próprio 63.
        # O CRC tem 4 dígitos, então o campo será um 6304.
        */
        $pix.="6304"; //Adiciona o campo do CRC no fim da linha do pix.
        $pix.=crcChecksum($pix); //Calcula o checksum CRC16 e acrescenta ao final.
      
        return($pix);
    }

    public static function getQrcode($pix) {      
        $size = '300x300';
        $correction = 'L';
        $encoding = 'UTF-8';

        //echo $pix;
        
		/*$url = "http://chart.googleapis.com/chart?cht=qr&chs=$size&chl=$pix&choe=$encoding&chld=$correction";
        return($url);*/
		
		if (!empty($pix)) {
            $qrCode = new QrCode($pix);
            $qrCode->setSize(300);
        }
		
		return $qrCode->writeDataUri();
    }

    private static function removeSpecial($string) {
        $caracteres_sem_acento = array(
            'Š'=>'S', 'š'=>'s', 'Ð'=>'Dj','À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A',
            'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I',
            'Ï'=>'I', 'Ñ'=>'N', 'Ń'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U',
            'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss','à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a',
            'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i',
            'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ń'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u',
            'ú'=>'u', 'û'=>'u', 'ü'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', 'ƒ'=>'f',
            'ă'=>'a', 'î'=>'i', 'â'=>'a', 'ș'=>'s', 'ț'=>'t', 'Ă'=>'A', 'Î'=>'I', 'Â'=>'A', 'Ș'=>'S', 'Ț'=>'T',
        );

        $clean = preg_replace("/[^a-zA-Z0-9\ \.]/", "", strtr($string, $caracteres_sem_acento));

        return($clean);
    }
}