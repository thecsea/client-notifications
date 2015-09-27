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

/**
 * Class ClientNotifications
 * @package it\thecsea\client_notifications
 * @author Giorgio Pea <annatar93@gmail.com>
 * @copyright 2015 Giorgio Pea
 * @version 1.0.0
 */
class ClientNotification
{
    private $message;
    private $timestamp;
    private $userId;
    private $notificationVehicles;



    public static function checkUserIdValidity($userId){
        if(is_int($userId)){
            if($userId > 0){
                return true;
            }
            return false;
        }
        return false;
    }

    /**
     * ClientNotification constructor.
     * @param $message
     */
    public function __construct($message, $userId, $notificationVehicles)
    {
        if(!self::checkUserIdValidity($userId)){
            throw new NonValidArgumentException('The given user id is not valid');
        }
        $this->message = $message;
        $this->userId = $userId;
        $this->timestamp = time();
        $this->notificationVehicles = $notificationVehicles;
    }

    /**
     * @return mixed
     */
    public function getNotificationVehicles()
    {
        return $this->notificationVehicles;
    }
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return array
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

}