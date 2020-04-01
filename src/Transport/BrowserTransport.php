<?php
/*
 * @copyright (C) 2020 Michiel Keijts, Normit
 * 
 */

namespace CakeNotifications\Transport;

use CakeNotifications\Model\Entity\Notification;
use CakeNotifications\Transport\AbstractTransport;
use CakeApiConnector\Model\Table\DataobjectsTable;
use Cake\ORM\TableRegistry;
use App\Model\Entity\User;

/**
 * Description of Browswertransport
 * 
 * Creates DataObjects which can be send to the browser
 *
 * @author michiel
 */
class BrowserTransport extends AbstractTransport {
    
    /**
     * Abstract send method to send the notification to a single recipient
     * @param string $message 
     * @param string $to 
     * @param Notification $notification
     * @return bool
     */
    public function send(string $message, array $to, Notification $notification = null) : bool
    {
        $dataobject = $this->getDataobjectsTable()->newEntity([
            'entity' => Notification::class,
            'entity_id' => $notification->id,
            'parent_model' => User::class,
            'parent_id' => reset($to)
        ]);
        
        $dataobject->data = $notification;
        
        return !empty($this->getDataobjectsTable()->save($dataobject));
    }
    
    private function getDataobjectsTable() : DataobjectsTable
    {
        return TableRegistry::getTableLocator()->get('CakeApiConnector.Dataobjects');
    }
}
