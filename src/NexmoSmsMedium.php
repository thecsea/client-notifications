<?php
/**
 * Created by PhpStorm.
 * User: Giorgio Pea <annatar93@gmail.com>
 * Date: 27/09/15
 * Time: 14:15
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

/**
 * A class that groups pieces of information and procedures to send a notification using a medium that is the sms
 * @package it\thecsea\client_notifications
 * @author Giorgio Pea <annatar93@gmail.com>
 * @copyright 2015 Giorgio Pea
 * @version 1.0.0
 */
class NexmoSmsMedium extends NotificationMedium
{
    /**
     * @var string
     */
    private $phoneNumber;

    /**
     * @var String
     */
    private $apiKey;

    /**
     * @var String
     */
    private $apiSecret;

    /**
     * Constructs a group of pieces of information and procedures to send a notification via sms. This group is
     * constructed from a phone number.
     * @param string $phoneNumber The phone number to be used to send a notification via sms
     * @param String $apiKey Nexmo ApiKey
     * @param String $apiSecret Nexmo ApiSecret
     */
    public function __construct($phoneNumber, $apiKey, $apiSecret)
    {
        $this->phoneNumber = $phoneNumber;
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
    }

    /**
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * @param string $phoneNumber
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * @return String
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param String $apiKey
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @return String
     */
    public function getApiSecret()
    {
        return $this->apiSecret;
    }

    /**
     * @param String $apiSecret
     */
    public function setApiSecret($apiSecret)
    {
        $this->apiSecret = $apiSecret;
    }



    public function sendProcedure(ClientNotification $notification){
    }



}