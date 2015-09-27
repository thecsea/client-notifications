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
use it\thecsea\mysqltcs\Mysqltcs;
use it\thecsea\mysqltcs\MysqltcsException;
use it\thecsea\mysqltcs\MysqltcsOperations;

class NotificationManager
{
    private $dbConnection;
    private $dbOperations;
    /**
     * Constructs a notification manager that manages the sending of notifications
     * and the saving of notifications
     *
     * @param
     */
    public function __construct($dbHost,$dbUser,$dbPwd,$dbName)
    {
        $this->dbConnection = new Mysqltcs($dbHost,$dbUser,$dbPwd,$dbName);
        $this->dbOperations = new MysqltcsOperations($this->dbConnection);

    }


    public function record(ClientNotification $notification){
        $user_id = $this->getEscapedString((string) $notification->getUserId());
        $message = $this->getEscapedString((string) $notification->getMessage());
        $timestamp = $this->getEscapedString((string) $notification->getTimestamp());
        try{
            $this->dbOperations->insert(array('user_id','message','date'),
                array($user_id,$message,$timestamp));
        }
        catch(MysqltcsException $e)
        {
            throw new DatabaseException('An error occured in the insertion of the data in the database');
        }

    }
    public function send(ClientNotification $notification){
        $vehicles = $notification->getNotificationVehicles();
        if(!is_array($vehicles)){
            $vehicles->send($notification);
            $this->record($notification);
        }
        else{
            foreach($vehicles as $vehicle){
                if(!is_array($vehicle) && ($vehicle instanceof NotificationVehicle)){
                    $vehicle->send($notification);
                    $this->record($notification);
                }
                else{
                    throw new WrongTypeException('No valid vehicle for the notification has been define');
                }

            }
        }
    }
    private function getEscapedString($string){
        return $this->dbConnection->getEscapedString($string);
    }
}