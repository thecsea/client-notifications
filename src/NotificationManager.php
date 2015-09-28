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
     * @var string
     */
    private $notificationsTable;

    /**
     * @var string
     */
    private $notificationTypeTable;

    /**
     * @var string
     */
    private $typesTable;

    /**
     * /**
     * Constructs a notification manager that manages the sending and storing of notifications.
     * This manager is constructed from pieces of information relative to the db used to store the notifications
     *
     * @param Mysqltcs $connection The mysqltcs connection, it can be a connection used in other classes
     * @param string $notificationsTable The name of the the table in which notifications are stored
     * @param string $notificationTypeTable The name of the table that maps notifications with their sending mediums
     * @param string $typesTable The name of the table that maps the code of a sending medium with its name
     * @throws DatabaseException If one of the specified db tables names does not match any existing db table
     */
    public function __construct(Mysqltcs $connection, $notificationsTable, $notificationTypeTable, $typesTable)
    {
        $flag = false;
        $this->dbConnection = $connection;
        $notificationsTable = $connection->getEscapedString($notificationsTable);
        $notificationTypeTable = $connection->getEscapedString($notificationTypeTable);
        $this->notificationTypeTable = $notificationTypeTable;
        $typesTable = $connection->getEscapedString($typesTable);
        //this means that $notificationsTable is the default table, if from parameter is not set
        $this->dbOperations = new MysqltcsOperations($this->dbConnection, $notificationsTable);
        foreach($this->dbOperations->showTables() as $table){
            if($table == $notificationsTable){
                $flag = true;
            }
            else if($table == $notificationTypeTable){
                $flag = true;
            }
            else if($table == $typesTable){
                $flag = true;
            }
        }
        if(!$flag){
            throw new DatabaseException('One of the specified db tables names does not match any existing db table');
        }
        //TODO the previous check is not correct in fact the correct check is $flag := true if and only if all tables exist,
        //TODO while this check is $flag := true if a table exists.
        //TODO to avoid cyclomatic complexity and duplications we have to split the checks into a simple methods like here
        //TODO https://github.com/thecsea/users-management/blob/master/src/UsersManagement.php#L83
        //TODO to see the cyclomatic complexity take a look to https://scrutinizer-ci.com/g/thecsea/client-notifications/code-structure
        //TODO we have some methods that are duplicated
        //TODO https://scrutinizer-ci.com/g/thecsea/client-notifications/code-structure/master/operation/it%5Cthecsea%5Cclient_notifications%5CNotificationManager%3A%3AsetNotificationsTable
        $this->notificationsTable = $notificationsTable;
        $this->notificationTypeTable = $notificationTypeTable;
        $this->typesTable = $typesTable;
    }


    public function __clone()
    {
        $this->dbOperations = clone $this->dbOperations;
    }


    /**
     * @return Mysqltcs
     */
    public function getDbConnection()
    {
        //DON'T clone to keep mysql connections number efficient
        return $this->dbConnection;
    }

    /**
     * @param Mysqltcs $dbConnection
     */
    public function setDbConnection(Mysqltcs $dbConnection)
    {
        $this->dbConnection = $dbConnection;
        $this->dbOperations->setMysqltcs($dbConnection);
    }

    /**
     * @return MysqltcsOperations
     */
    public function getDbOperations()
    {
        //clone to avoid to modify original operations
        $operations = clone $this->dbOperations;
        return $operations;
    }

    /**
     * @return string
     */
    public function getNotificationsTable()
    {
        return $this->notificationsTable;
    }

    /**
     * @param string $notificationsTable
     * @throws DatabaseException If the specified table name does not match any existing db table
     */
    public function setNotificationsTable($notificationsTable)
    {
        $flag = false;
        $notificationsTable = $this->dbConnection->getEscapedString($notificationsTable);
        foreach($this->dbOperations->showTables() as $table){
            if($table == $notificationsTable){
                $flag = true;
            }
        }
        if(!$flag){
            throw new DatabaseException('The specified db table name does not match any existing db table');
        }
        $this->notificationsTable = $notificationsTable;
        $this->dbOperations->setDefaultFrom($notificationsTable);
    }

    /**
     * @return string
     */
    public function getNotificationTypeTable()
    {
        return $this->notificationTypeTable;
    }

    /**
     * @param string $notificationTypeTable
     * @throws DatabaseException If the specified table name does not match any existing db table
     */
    public function setNotificationTypeTable($notificationTypeTable)
    {
        $flag = false;
        $notificationTypeTable = $this->dbConnection->getEscapedString($notificationTypeTable);
        foreach($this->dbOperations->showTables() as $table){
            if($table == $notificationTypeTable){
                $flag = true;
            }
        }
        if(!$flag){
            throw new DatabaseException('The specified db table name does not match any existing db table');
        }
        $this->notificationTypeTable = $notificationTypeTable;
    }

    /**
     * @return string
     */
    public function getTypesTable()
    {
        return $this->typesTable;
    }

    /**
     * @param string $typesTable
     * @throws DatabaseException If the specified table name does not match any existing db table
     */
    public function setTypesTable($typesTable)
    {
        $flag = false;
        $typesTable = $this->dbConnection->getEscapedString($typesTable);
        foreach($this->dbOperations->showTables() as $table){
            if($table == $typesTable){
                $flag = true;
            }
        }
        if(!$flag){
            throw new DatabaseException('The specified db table name does not match any existing db table');
        }
        $this->typesTable = $typesTable;
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
        /** @var NotificationMedium[] $mediums */
        $mediums = $notification->getNotificationMediums();
        //Checks if $vehicles is an object of the type NotificationVehicle
        if(!is_array($mediums) && $mediums instanceof NotificationMedium){
            /** @var NotificationMedium $mediums */
            $mediums->sendProcedure($notification);
            $this->store($notification);
        }
        else if(is_array($mediums)){
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
        else{
            throw new NonValidArgumentException('No valid medium for the notification has been defined');
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