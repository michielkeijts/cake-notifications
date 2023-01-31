<?php
/*
 * @copyright (C) 2020 Michiel Keijts, Normit
 *
 */

namespace CakeNotifications\Transport;

use CakeNotifications\Model\Entity\Notification;
use CakeNotifications\Transport\AbstractTransport;
use CakeApiConnector\Model\Table\DataobjectsTable;
use CakeApiConnector\Model\Entity\Dataobject;
use Cake\ORM\TableRegistry;
use App\Model\Entity\User;
use CakeNotifications\Transport\Provider\ProviderFactory;
use Cake\Core\Configure;

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
        $transporterConfig = Configure::read('CakeNotifications.Transport.Browser');

        if ($transporterConfig['provider']) {
            $provider = is_array($transporterConfig['provider']) ? array_key_first($transporterConfig['provider']) : $transporterConfig['provider'];
            $config = is_array($transporterConfig['provider']) ? $transporterConfig['provider'][$provider] : [];

            $provider = ProviderFactory::get($provider, $config);

            return $provider->send($message, $to, $notification);
        }

        $dataobject = $this->getDataobjectsTable()->newEntity([
            'entity' => Notification::class,
            'entity_id' => $notification->id,
            'parent_model' => User::class,
            'parent_id' => reset($to),
            'runner_status' => Dataobject::STATUS_WAITING
        ]);

        $dataobject->data = $notification;

        return !empty($this->getDataobjectsTable()->save($dataobject));
    }

    private function getDataobjectsTable() : DataobjectsTable
    {
        return TableRegistry::getTableLocator()->get('Dataobjects');
    }
}
