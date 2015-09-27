<?php
/**
 * Created by PhpStorm.
 * User: Giorgio Pea <annatar93@gmail.com>
 * Date: 27/09/15
 * Time: 14:18
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
use it\thecsea\client_notifications\exceptions\DatabaseException;
use it\thecsea\client_notifications\exceptions\NonValidArgumentException;
use it\thecsea\mysqltcs\Mysqltcs;
use it\thecsea\mysqltcs\MysqltcsException;
use it\thecsea\mysqltcs\MysqltcsOperations;

/**
 * A class that manages the sending and storing of notifications
 *
 * @package it\thecsea\client_notifications
 * @author Giorgio Pea <annatar93@gmail.com>
 * @copyright 2015 Giorgio Pea
 * @version 1.0.0
 */
class NotificationManager
{
    /**
     * @var Mysqltcs
     */
    private $dbConnection;
    /**
     * @var MysqltcsOperations
     */
    private $dbOperations;

    /**
     * Constructs a notification manager that manages the sending and storing of notifications.
     * This manager is constructed from pieces of information relative to the db used to store the notifications
     *
     * @param string $dbHost The db host
     * @param string $dbUser The db username
     * @param string $dbPwd The password relative to the specified db username
     * @param string $dbName The name of the db
     */
    public function __construct($dbHost,$dbUser,$dbPwd,$dbName)
    {
        $this->dbConnection = new Mysqltcs($dbHost,$dbUser,$dbPwd,$dbName);
        $this->dbOperations = new MysqltcsOperations($this->dbConnection);

    }

    /**
     * Stores a given notification in the provided db
     *
     * @param ClientNotification $notification A notification to be store in the specified db
     * @throws DatabaseException If an error occured in the storing of the notification in the db
     */
    public function store(ClientNotification $notification){
        //String conversion and escaping in order to perform a correct and safe sql query
        $user_id = $this->getEscapedString((string) $notification->getUserId());
        $message = $this->getEscapedString((string) $notification->getMessage());
        $timestamp = $this->getEscapedString((string) $notification->getTimestamp());
        try{
            $this->dbOperations->insert(array('user_id','message','date'),
                array($user_id,$message,$timestamp));
        }
        catch(MysqltcsException $e)
        {
            throw new DatabaseException('An error occurred in the insertion of the data in the database');
        }

    }

    /**
     * Sends a given notification to the client using the sending vehicles specified in
     * the notification itself
     *
     * @param ClientNotification $notification A notification to be sent to the client
     * @throws DatabaseException If an error occurred in the storing of the notification in the db
     * @throws NonValidArgumentException If the sending vehicles specified in the notification are not valid
     */
    public function send(ClientNotification $notification){
        $mediums = $notification->getNotificationVehicles();
        //Checks if $vehicles is an object of the type NotificationVehicle
        if(!is_array($mediums) && $mediums instanceof NotificationMedium){
            $mediums->sendProcedure($notification);
            $this->store($notification);
        }
        else{
            foreach($mediums as $medium){
                //Checks if $vehicles does not contain array but objects of the type NotificationVehicle
                if(!is_array($medium) && ($medium instanceof NotificationMedium)){
                    $medium->sendProcedure($notification);
                    $this->store($notification);
                }
                else{
                    throw new NonValidArgumentException('No valid medium for the notification has been defined');
                }

            }
        }
    }

    /**
     * Escapes a given string from special characters
     * @param string $string A string to be escaped
     * @return string the escaped string
     */
    private function getEscapedString($string){
        return $this->dbOperations->getEscapedString($string);
    }
}