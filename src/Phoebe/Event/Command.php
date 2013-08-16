<?php
namespace Phoebe\Event;

use Phergie\Irc\Client\React\WriteStream;
use Phergie\Irc\Connection;
use Monolog\Logger;

class Command extends Event
{
    protected $message;
    protected $write;
    protected $connection;
    protected $logger;

    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setWriteStream(WriteStream $writeStream)
    {
        $this->write = $writeStream;
    }

    public function getWriteStream()
    {
        return $this->write;
    }

    public function setConnection(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function setLogger(Logger $logger)
    {
        $this->logger = $logger;
    }

    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * Determines whether a given string is a valid IRC channel name.
     *
     * @param string $string String to analyze
     *
     * @return bool TRUE if $string contains a valid channel name, FALSE
     *         otherwise
     */
    protected function isChannelName($string)
    {
        // Per the 2000 RFCs 2811 and 2812, channels may begin with &, #, +, or !
        return (strspn($string, '#&+!', 0, 1) >= 1);
    }

    /**
     * Returns the channel name if the event occurred in a channel or the
     * user nick if the event was a private message directed at the bot by a
     * user.
     *
     * @return string
     */
    public function getSource()
    {
        $msg = $this->getMessage();
        
        if (!empty($msg['targets'])) {
            return $msg['targets'][0];
        }
    }

    /**
     * Returns whether or not the event occurred within a channel.
     *
     * @return TRUE if the event is in a channel, FALSE otherwise
     */
    public function isInChannel()
    {
        return $this->isChannelName($this->getSource());
    }
}
