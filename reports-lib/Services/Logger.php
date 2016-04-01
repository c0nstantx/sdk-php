<?php
/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>
 */

namespace RAM\Services;

/**
 * Description of Logger
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
class Logger
{
    const LOG_LIMIT = 100;

    /** @var  Storage */
    protected $storage;

    final public function setStorage(Storage $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @param string $message
     *
     * @return bool
     */
    public function log($message)
    {
        $message = (string)$message;
        $logs = $this->storage->find('logs', true);
        $date = new \DateTime();
        if (null === $logs) {
            $logs = [$date->format(DATE_ISO8601) => $message];
        } else {
            if (count($logs) >= self::LOG_LIMIT) {
                $logs = array_reverse($logs, true);
                $logs = array_splice($logs, 0, self::LOG_LIMIT-1);
                $logs = array_reverse($logs, true);
            }
            $logs[$date->format(DATE_ISO8601)] = $message;
        }

        return $this->storage->save('logs', $logs);
    }

    /**
     * @return array
     */
    public function getLogs()
    {
        $logs = $this->storage->find('logs', true);
        if (null === $logs) {
            return [];
        }

        return array_reverse($logs, true);
    }
}