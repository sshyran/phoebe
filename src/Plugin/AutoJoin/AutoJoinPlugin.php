<?php
namespace Phoebe\Plugin\AutoJoin;

use Phoebe\Event\Event;
use Phoebe\Plugin\PluginInterface;
use Phergie\Irc\Client\React\WriteStream;

class AutoJoinPlugin implements PluginInterface
{
    protected $channels = array();

    public static function getSubscribedEvents()
    {
        return array(
            'irc.received.001' => array('onWelcome', 0)
        );
    }

    public function onWelcome(Event $event)
    {
        $this->joinChannels($event->getWriteStream());
    }

    /**
     * Joins all the channels added to auto join list
     * @param  WriteStream $writeStream WriteStream object
     * @return void
     */
    protected function joinChannels(WriteStream $writeStream)
    {
        foreach ($this->channels as $channel => $key) {
            $writeStream->ircJoin($channel, $key);
        }
    }

    /**
     * Add channel to auto join
     * @param string $channel Channel to auto join
     * @param null|string $key     Channel password. NULL when ommited
     * @return void
     */
    public function addChannel($channel, $key = null)
    {
        $this->channels[$channel] = $key;
    }

    /**
     * Add channels to auto join
     * @param array $channels Array of channels to join. Available formats:
     *                        array('#channel1', '#channel2') or
     *                        array('#channel1', '#channel2' => 'password') or
     *                        array('#channel1' => null, '#channel2' => 'password')
     * @return void
     */
    public function addChannels($channels)
    {
        foreach ($channels as $k => $v) {
            if (preg_match('/^[0-9]+$/', $k)) {
                $this->addChannel($v);
            } else {
                $this->addChannel($k, $v);
            }
        }
    }

    /**
     * Removes channel from auto join list
     * @param  string $channel Channel name
     * @return void
     */
    public function removeChannel($channel)
    {
        if (isset($this->channels)) {
            unset($this->channels[$channel]);
        }
    }
}