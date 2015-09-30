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
class SmsMedium extends NotificationMedium
{
    /**
     * @var string
     */
    private $phoneNumber;

    /**
     * Constructs a group of pieces of information and procedures to send a notification via sms. This group is
     * constructed from a phone number.
     * @param string $phoneNumber The phone number to be used to send a notification via sms
     */
    public function __construct($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
    }

    public function sendProcedure(ClientNotification $notification){
        //
    }


}