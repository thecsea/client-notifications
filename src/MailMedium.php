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
use it\thecsea\client_notifications\exceptions\FailedMailNotificationSendingException;
use it\thecsea\client_notifications\exceptions\NonValidArgumentException;

/**
 * A class that groups pieces of information and procedures to send a notification using a medium that is the email
 * @package it\thecsea\client_notifications
 * @author Giorgio Pea <annatar93@gmail.com>
 * @copyright 2015 Giorgio Pea
 * @version 1.0.0
 */
class MailMedium extends NotificationMedium
{
    /**
     * @var string
     */
    private $mailAddress;
    /**
     * @var \PHPMailer
     */
    private $mailer;


    /**
     * Constructs a group of pieces of information and procedures to send a notification via sms. An smtp mail server is supposed.
     *
     * @param string $mailAddress The notification's receiver email address
     * @param string $mail_host The host of the mail server
     * @param bool $smtp_auth If the host of the mail server requires smtp authentication
     * @param string $username The username of the notification's sender email account
     * @param string $pwd The password of the notification's sender email account
     * @param string $encryption_type optional The type of the chosen email encryption(tls,ssl)
     * @param int $port The port used to send the email
     * @param string $subject The subject of the email
     * @param string $from The sender's email address
     * @throws NonValidArgumentException If the provided email address are not valid or the mail encryption technology
     * indicated is not supported or doesn't exist
     * @throws \phpmailerException If an error occurred in the sending of the email
     */
    public function __construct($mailAddress,$mail_host,$smtp_auth,$username, $pwd, $encryption_type='',$port, $subject,$from)
    {
        self::checkMailValidity($mailAddress);
        self::checkMailValidity($from);
        $this->mailAddress = $mailAddress;
        $this->mailer = new \PHPMailer();
        $this->mailer->isSMTP();
        $this->mailer->Host = $mail_host;
        $this->mailer->SMTPAuth = $smtp_auth;
        $this->mailer->Username = $username;
        $this->mailer->Password = $pwd;
        self::encryptionTypeControl($encryption_type);
        $this->mailer->SMTPSecure = $encryption_type;
        self::portControl($port);
        $this->mailer->Port = $port;
        $this->mailer->setFrom($from);
        $this->mailer->addAddress($mailAddress);
        $this->mailer->Subject = $subject;

    }

    /**
     * @param string $mailAddress
     */
    public function setMailAddress($mailAddress){
        $this->mailer->clearAllRecipients();
        self::checkMailValidity($mailAddress);
        $this->mailer->addAddress($mailAddress);
    }

    /**
     * @param string $mailHost
     */
    public function setMailHost($mailHost){
        $this->mailer->Host = $mailHost;
    }

    /**
     * @param bool $smtpAuth
     */
    public function setSmtpAuth($smtpAuth){
        $this->mailer->SMTPAuth = $smtpAuth;
    }

    /**
     * @param string $username
     */
    public function setUsername($username){
        $this->mailer->Username = $username;
    }

    /**
     * @param string $pwd
     */
    public function setPwd($pwd){
        $this->mailer->Password = $pwd;
    }

    /**
     * @param string $enc
     * @throws NonValidArgumentException If the mail encryption technology
     * indicated is not supported or does not exist
     */
    public function setEncryption($enc){
        $this->encryptionTypeControl($enc);
        $this->mailer->SMTPSecure = $enc;
    }

    /**
     * @param string $subject
     */
    public function setSubject($subject){
        $this->mailer->Subject = $subject;
    }

    /**
     * @param string $from
     * @throws \phpmailerException If an error occured in the setting of the sender's mail address
     */
    public function setFrom($from){
        self::checkMailValidity($from);
        $this->mailer->setFrom($from);
    }

    /**
     * @param int $port
     * @throws NonValidArgumentException If the given port number is not valid
     */
    public function setPort($port){
       self::portControl($port);
    }

    /**
     *
     * @param \it\thecsea\client_notifications\ClientNotification $notification The notification to be sent via mail
     * @throws FailedMailNotificationSendingException If the sending of the mail has failed
     * @throws \Exception
     * @throws \phpmailerException If an error occured in the sending of the mail
     */
    public function sendProcedure(ClientNotification $notification){
        if(!$this->mailer->send()){
            throw new FailedMailNotificationSendingException('The sending of the notification via mail has failed');
        }
    }

    /**
     * Checks if a given string represents a valid email address
     * @param string $mail The string to be tested
     * @@throws NonValidArgumentException If the given email address is not valid
     */
    public static function checkMailValidity($mail){
        if(!filter_var($mail, FILTER_VALIDATE_EMAIL)){
            throw new NonValidArgumentException('The given mail is not valid');
        }
    }

    /**
     * Checks if a given string represents a valid mail encryption technology (only ssl/tls are supported)
     * @param string $enc The string that is tested to represent a valid mail encryption technology
     * @throws NonValidArgumentException If the given string does not represent a valid mail encryption technology
     */
    public static function encryptionTypeControl($enc){
        if(!($enc == 'tls' || $enc == 'ssl' || $enc == '')){
            throw new NonValidArgumentException('Non valid mail encryption technology, use only ssl or tls');
        }
    }

    /**
     * Checks if a given integer represents a valid tcp/udp port
     * @param int $port The integer that is tested to be a valid tcp/udp port
     * @throws NonValidArgumentException If the given integer is not a valid tcp/udp port
     */
    public static function portControl($port){
        if(!($port > 0 && $port < 65536)){
            throw new NonValidArgumentException('Tcp/Udp ports are in the range 0 - 65536');
        }
    }
}