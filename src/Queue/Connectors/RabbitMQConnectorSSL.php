<?php

namespace VladimirYuldashev\LaravelQueueRabbitMQ\Queue\Connectors;

use PhpAmqpLib\Connection\AMQPSSLConnection;
use VladimirYuldashev\LaravelQueueRabbitMQ\Queue\RabbitMQQueue;

class RabbitMQConnectorSSL implements RabbitMQConnectorInterface
{
    /** @var AMQPSSLConnection */
    private $connection;

    /**
     * Establish a queue connection.
     *
     * @param array $config
     *
     * @return \Illuminate\Contracts\Queue\Queue
     */
    public function connect(array $config)
    {
        // Remove null values from the SSL config
        foreach ($config['ssl_params'] as $idx => $option) {
            if ($option === null || empty($option)) {
                unset($config['ssl_params'][$idx]);
            }
        }

        // Create connection with AMQP
        $this->connection = new AMQPSSLConnection(
            $config['host'],
            $config['port'],
            $config['login'],
            $config['password'],
            $config['vhost'],
            $config['ssl_params']
        );

        return new RabbitMQQueue(
            $this,
            $config
        );
    }

    public function connection()
    {
        return $this->connection;
    }

    public function reconnect()
    {
        $this->connection->reconnect();
    }
}
