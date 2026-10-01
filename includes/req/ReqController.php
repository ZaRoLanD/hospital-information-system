<?php
include "../../includes/req/cart.php";
require '../../includes/PHPMailer/src/Exception.php';
require '../../includes/PHPMailer/src/PHPMailer.php';
require '../../includes/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;



if (isset($_POST['type'])) :

    require_once "../../includes/Session.php";
    $session = new Session();
    if (in_array($_POST['type'], array('approve', 'deny'), true)) {
        $session->requireRole('admin'); // approving requisitions is admin-only
    }

    $req = new Req();

    switch ($_POST['type']) {

        case "addToCart":
            echo $req->addToCart($_POST['id'], $_POST['name'], $_POST['expiry'], $_POST['presp'], $_POST['price'], $_POST['qty']);
            break;
        case "removeFromCart":
            echo $req->removeFromCart($_POST['id'], $_POST['name'], $_POST['expiry'], $_POST['presp'], $_POST['price'], $_POST['qty']);
            break;
        case "saveCart":
            echo $req->saveCart();
            break;
        case "approve":
            echo $req->approve($_POST['id']);
            break;
        case "deny":
            echo $req->deny($_POST['id']);
            break;
    }
endif;


class Req
{
    function addToCart($id, $name, $expiry, $prep, $price, $qty)
    {
        $cart = new cart();
        return $cart->addToCart($id, $name, $expiry, $prep, $price, $qty);
    }

    function getCart($filename)
    {
        $cart = new cart();
        return $cart->getCart($filename);
    }
    function removeFromCart($id, $name, $expiry, $prep, $price, $qty)
    {
        $cart = new cart();
        return $cart->removeFromCart($id, $name, $expiry, $prep, $price, $qty);
    }
    function clearCart()
    {
        $cart = new cart();
        return $cart->deleteCart();
    }

    function findReqs()
    {
        include "../../includes/DBconn.php";
        $db = new DBconnection();
        $dbConn = $db->getConnection();
        return $dbConn->query("SELECT req_id, created, status, count(req_id) as count
        from requisitions
        group by req_id, created, status
        having count(req_id)");
    }
    function findReqsByID($email)
    {
        include "../../includes/DBconn.php";
        $db = new DBconnection();
        $dbConn = $db->getConnection();
        return $dbConn->query("SELECT req_id, created, status, count(req_id) as count
        from requisitions
        where email = '$email'
        group by req_id, created, status
        having count(req_id)");
    }


    function viewReq($id)
    {
        include "../../includes/DBconn.php";
        $db = new DBconnection();
        $dbConn = $db->getConnection();
        return $dbConn->query("SELECT requisitions.*, drug.*, requisitions.created
        FROM requisitions
        INNER JOIN drug ON drug.drug_id = requisitions.drug_id
        WHERE requisitions.req_id = $id
        ORDER BY requisitions.created
        DESC");
    }

    function viewReqByDate($from, $to)
    {
        include "../../includes/DBconn.php";
        $db = new DBconnection();
        $dbConn = $db->getConnection();
        return $dbConn->query("SELECT requisitions.req_id, count(requisitions.drug_id) as count, drug.name, requisitions.created, requisitions.qty
        FROM requisitions
        INNER JOIN drug ON drug.drug_id = requisitions.drug_id
        WHERE requisitions.created between '$from' and '$to'
        GROUP BY requisitions.drug_id
        ORDER BY requisitions.drug_id
        DESC");
    }


    function viewReqs()
    {
        include "../../includes/DBconn.php";
        $db = new DBconnection();
        $dbConn = $db->getConnection();
        return $dbConn->query("SELECT requisitions.req_id, count(requisitions.drug_id) as count, drug.name, requisitions.created, requisitions.qty
                                FROM requisitions
                                INNER JOIN drug ON drug.drug_id = requisitions.drug_id
                                GROUP BY requisitions.drug_id
                                ORDER BY requisitions.drug_id
                                DESC");
    }


    function saveCart()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $email = $_SESSION['email'];
        include "../../includes/DBconn.php";
        $db = new DBconnection();
        $dbConn = $db->getConnection();
        $cartList = $this->getCart('cart.txt');

        try {
            $items = array();
            foreach ($cartList as $item) {
                if (isset($item[5]) && (int) $item[0] > 0 && (int) $item[5] > 0) {
                    $items[] = array('drug_id' => (int) $item[0], 'qty' => (int) $item[5]);
                }
            }
            if (count($items) < 1) {
                return "Le panier est vide";
            }

            $dbConn->beginTransaction();

            // Lock and validate stock before writing anything
            $check = $dbConn->prepare("SELECT qty FROM drug WHERE drug_id = :drug_id FOR UPDATE");
            foreach ($items as $item) {
                $check->execute(array(':drug_id' => $item['drug_id']));
                $available = $check->fetchColumn();
                if ($available === false) {
                    throw new RuntimeException("Le médicament n° " . $item['drug_id'] . " n'existe pas");
                }
                if ((int) $available < $item['qty']) {
                    throw new RuntimeException("Stock insuffisant pour le médicament n° " . $item['drug_id'] . " (" . (int) $available . " restant)");
                }
            }

            $reqID = (int) $dbConn->query("SELECT COALESCE(MAX(req_id), 0) + 1 FROM requisitions")->fetchColumn();

            $insert = $dbConn->prepare("INSERT INTO requisitions (req_id,drug_id,qty, email)
                            VALUES (:req_id, :drug_id, :qty, :email)");
            $deduct = $dbConn->prepare("UPDATE drug
                            SET qty = qty - :qty
                            WHERE drug_id = :drug_id");

            foreach ($items as $item) {
                $insert->execute(array(
                    ':req_id' => $reqID,
                    ':drug_id' => $item['drug_id'],
                    ':qty' => $item['qty'],
                    ':email' => $email
                ));
                $deduct->execute(array(':qty' => $item['qty'], ':drug_id' => $item['drug_id']));
            }

            $dbConn->commit();
            $this->clearCart();
            return "success";
        } catch (Exception $e) {
            if ($dbConn->inTransaction()) {
                $dbConn->rollBack();
            }
            return $e->getMessage();
        }
    }

    function approve($id)
    {
        include "../../includes/DBconn.php";
        $db = new DBconnection();
        $dbConn = $db->getConnection();
        $sql = "UPDATE requisitions
                SET status = 1
                WHERE req_id = $id";
        $query = $dbConn->prepare($sql);
        try {
            if ($query->execute()) :
                $this->sendEmail($id, "Approved"); // corps traduit dans sendEmail()
                return "success";
            endif;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }
    function deny($id)
    {
        include "../../includes/DBconn.php";
        $db = new DBconnection();
        $dbConn = $db->getConnection();
        $sql = "UPDATE requisitions
                SET status = 2
                WHERE req_id = $id";
        $query = $dbConn->prepare($sql);
        try {
            if ($query->execute()) :
                $this->sendEmail($id, "Denied");
                return "success";
            endif;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    function sendEmail($Reqid, $status)
    {
        $db = new DBconnection();
        $dbConn = $db->getConnection();
        $query = $dbConn->query("SELECT email from requisitions where req_id = $Reqid LIMIT 1");
        $result = $query->fetch(PDO::FETCH_ASSOC);
        $email = $result['email'];


        $to = $email;
        $from = 'HIS@gmail.com';

        // Identifiants SMTP fournis via l'environnement (HIS_SMTP_USER / HIS_SMTP_PASS).
        // Aucun secret dans le code : voir README, section "Limitations connues".
        $smtpUser = getenv('HIS_SMTP_USER') ?: '';
        $smtpPass = getenv('HIS_SMTP_PASS') ?: '';
        if ($smtpUser === '' || $smtpPass === '') {
            return "success"; // e-mail non configuré : l'approbation reste valide
        }

        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        $mail->Host = "smtp.gmail.com";
        $mail->Port = 587;
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        $mail->IsHTML(true);
        $mail->Username = $smtpUser;
        $mail->Password = $smtpPass;
        $mail->SetFrom($smtpUser);
        $mail->Subject = "Système d'Information Hospitalier"; // Objet du mail
        $mail->Body = "Votre réquisition a été " . ($status === "Approved" ? "approuvée" : "refusée") . ".";
        $mail->AddAddress($to);
        if (!$mail->Send()) {
            echo "Failed to send mail";
            exit;
        }
        return "success";
    }
}
