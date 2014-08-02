<?php
namespace Phoebe\Plugin\PingPong;

use Phoebe\Event\Event;
use Phoebe\Plugin\PluginInterface;

class PingPongPlugin implements PluginInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            'irc.received.PING' => ['onPing']
        );
    }

    public function onPing(Event $event)
    {
        $pongMessage = $event->getMessage()['params']['all'];
        $event->getWriteStream()->ircPong($pongMessage);
    }
}
