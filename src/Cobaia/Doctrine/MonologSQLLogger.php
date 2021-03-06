<?php
namespace Cobaia\Doctrine;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Doctrine\DBAL\Logging\SQLLogger;

class MonologSQLLogger implements SQLLogger
{

    private $logger;

    public function __construct(Logger $logger = null, StreamHandler $handler = null, $path = null)
    {
        $this->logger = $logger ?: new Logger('doctrine');
        if (is_null($handler) && is_null($path)) {
            throw new InvalidArgumentException('As you are not passing one handler, you must provide one path!');
        }
        $handler = $handler ?: new StreamHandler($path . 'doctrine.log', Logger::DEBUG);
        $this->logger->pushHandler($handler);
    }

    /**
     * {@inheritdoc}
     */
    public function startQuery($sql, array $params = null, array $types = null)
    {
        $this->logger->addDebug($sql);

        if ($params) {
            $this->logger->addDebug(json_encode($params));
        }

        if ($types) {
            $this->logger->addDebug(json_encode($types));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function stopQuery()
    {

    }
}
