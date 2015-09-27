<?php
/**
 * Created by PhpStorm.
 * User: Giorgio Pea <annatar93@gmail.com>
 * Date: 27/09/15
 * Time: 14:10
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 */

namespace it\thecsea\client_notifications;
use it\thecsea\client_notifications\ClientNotification;


class MailVehicle extends NotificationVehicle
{
    private $mail;
    private $mailer;

    public static function checkMailValidity($mail){
        return filter_var($mail, FILTER_VALIDATE_EMAIL);
    }

    public function __construct($mail,$mail_host,$smtp_auth,$username, $pwd, $encryption_type,$port, $subject,$from)
    {
        if(!$this->checkMailValidity($mail)){
            throw new NonValidArgumentException('The given mail is not valid');
        }
        $this->mail = $mail;
        $this->mailer = new \PHPMailer();

        $this->mailer->isSMTP();
        $this->mailer->Host = $mail_host;
        $this->mailer->SMTPAuth = $smtp_auth;
        $this->mailer->Username = $username;
        $this->mailer->Password = $pwd;
        $this->mailer->SMTPSecure = $encryption_type;
        $this->mailer->Port = $port;
        $this->mailer->setFrom($from);
        $this->mailer->addAddress($mail);
        $this->mailer->Subject = $subject;

    }
    public function setMail($mail){
        $this->mailer->clearAllRecipients();
        $this->mailer->addAddress($mail);
    }
    public function setMailHost($mail_host){
        $this->mailer->Host = $mail_host;
    }
    public function setSmtpAuth($smtpAuth){
        $this->mailer->SMTPAuth = $smtp_auth;
    }
    public function setUsername($username){
        $this->mailer->Username = $username;
    }
    public function setPwd($pwd){
        $this->mailer->Password = $pwd;
    }
    public function setEncryption($enc){
        $this->mailer->SMTPSecure = $enc;
    }
    public function setSubject($subject){
        $this->mailer->Subject = $subject;
    }
    public function setFrom($from){
        $this->mailer->setFrom($from);
    }
    public function setPort($port){
        $this->mailer->Port = $port;
    }
    public function sendProcedure(ClientNotification $notification){
        if(!$this->mailer->send()){
            throw new FailedMailNotificationSendingException('The sending of the notification via mail has failed');
        }
    }

}