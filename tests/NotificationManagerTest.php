<?php

/**
 * Created by PhpStorm.
 * User: Giorgio Pea <annatar93@gmail.com>
 * Date: 29/09/15
 * Time: 13:53
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
use it\thecsea\mysqltcs\Mysqltcs;
use it\thecsea\mysqltcs\MysqltcsOperations;

require_once(__DIR__ . "/../vendor/autoload.php");

class NotificationManagerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var NotificationManager
     */
    private $instance;

    /**
     * @var Mysqltcs
     */
    private $dbCon;

    /**
     * @var array
     */
    private $dbInfos;



    public function setUp()
    {
        $this->dbInfos = require(__DIR__ . "/../tests/config.php");
        $this->dbCon = new Mysqltcs($this->dbInfos['host'],$this->dbInfos['user'],$this->dbInfos['psw'],$this->dbInfos['db']);
        $this->instance = new NotificationManager($this->dbCon,
            $this->dbInfos['tables']['notifications'],$this->dbInfos['tables']['notification_type'],$this->dbInfos['tables']['types']);
    }


    public function testStaticMethods(){
        $testTables = array('table1','table2','table3');
        try{
            NotificationManager::matchTable('table2',$testTables);
        }
        catch(DatabaseException $e){
            $this->assertTrue(false);
        }
        try{
            NotificationManager::matchTable(231,$testTables);
        }
        catch(DatabaseException $e){
            $this->assertTrue(true);
        }

    }

    public function testInstancesBuild(){
        $ops = new MysqltcsOperations($this->dbCon,$this->dbInfos['tables']['notifications']);
        $this->assertEquals($this->instance->getNotificationsTable(),$this->dbInfos['tables']['notifications']);
        $this->assertEquals($this->instance->getNotificationTypeTable(),$this->dbInfos['tables']['notification_type']);
        $this->assertEquals($this->instance->getTypesTable(),$this->dbInfos['tables']['types']);
        $this->assertEquals($this->instance->getDbConnection(),$this->dbCon);
        $this->assertEquals($this->instance->getDbOperations(),$ops);

    }
    public function testStoreMethod(){
        $ops = $this->instance->getDbOperations();
        $mediums = new NexmoSmsMedium(1111, "", "");
        $notification = new ClientNotification('_',1,$mediums);
        $id = $this->instance->store($notification);
        $this->assertEquals($ops->getValue('message','id = '.$id),$notification->getMessage());
        $this->assertEquals($ops->getValue('user_id','id = '.$id),$notification->getUserId());
        $value = $ops->getValue('id','name = '."'".get_class($mediums)."'",'types');
        $this->assertEquals($ops->getValue('type_id','notification_id = '.$id,'notification_type'),$value);
        $ops->deleteRows('1');
    }

}
