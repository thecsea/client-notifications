<?php
/**
 * Created by PhpStorm.
 * User: claudio
 * Date: 26/09/15
 * Time: 16.44
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

namespace it\thecsea\client_notifications;
use it\thecsea\client_notifications\exceptions\NonValidArgumentException;

/**
 * A class that represents a notification
 *
 * @package it\thecsea\client_notifications
 * @author Giorgio Pea <annatar93@gmail.com>
 * @copyright 2015 Giorgio Pea
 * @version 1.0.0
 */
class ClientNotification
{
    /**
     * @var string
     */
    private $message;
    /**
     * @var int
     */
    private $timestamp;
    /**
     * @var int
     */
    private $userId;
    /**
     * @var NotificationMedium[]\NotificationMedium
     */
    private $notificationMediums;


    /**
     * Checks if a given integer is a non negative, non zero
     * integer
     * @param int $userId The integer to be tested
     * @return bool The result of the test
     */
    public static function checkUserIdValidity($userId){

        if($userId > 0){
            return true;
        }
        return false;
    }

    /**
     * Constructs a notification from its encapsulated message, the user it refers to and from the
     * mediums to be used to send it
     *
     * @param string $message The message encapsulated in the notification
     * @param int $userId The id of the user the notification refers to
     * @param NotificationMedium[]|NotificationMedium $notificationMediums An array of NotificationMedium objects or one NotificationMedium
     * @throws NonValidArgumentException If the provided user id is a negative or a zero value integer
     */
    public function __construct($message, $userId, $notificationMediums)
    {
        if(!self::checkUserIdValidity($userId)){
            throw new NonValidArgumentException('The given user id is not valid');
        }
        $this->message = $message;
        $this->userId = $userId;
        $this->timestamp = time();
        $this->notificationMediums = $notificationMediums;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @param int $timestamp
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }

    /**
     * @param int $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @param NotificationMedium[] $notificationMediums
     */
    public function setNotificationMediums($notificationMediums)
    {
        $this->notificationMediums = $notificationMediums;
    }

    /**
     * @return  NotificationMedium | NotificationMedium[]
     */
    public function getNotificationMediums()
    {
        return $this->notificationMediums;
    }
    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return int
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

}