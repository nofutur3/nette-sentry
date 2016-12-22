<?php
/**
 * Sentry.php
 *
 * @package  : Sentry
 * @author   : Jakub Vyvazil <jakub@vyvazil.cz>
 * @copyright: 2016
 */

namespace Nofutur3\Sentry;


use Exception;
use Tracy\Debugger;
use Tracy\Logger;

class Sentry extends Logger
{
    /** @var  \Raven_Client */
    private $client;

    /** @var bool */
    private $isEnabled = true;

    /**
     * Sentry constructor.
     */
    public function __construct($dsn, $isDebugMode = false, $directory = null, $email = null, $options = [])
    {
        parent::__construct($directory, $email, Debugger::getBlueScreen());

        $this->isEnabled = Debugger::$productionMode || $isDebugMode;
        $this->client = new \Raven_Client($dsn, $options);

        $sentry = $this;
        Debugger::$onFatalError[] = function($e) use ($sentry) {
            $sentry->onFatalError($e);
        };
        Debugger::setLogger($this);
    }

    public function onFatalError($error)
    {
        if ($this->isEnabled) {
            $this->client->captureException($error);
        }
    }

    public function log($message, $priority = self::INFO)
    {
        if ($this->isEnabled) {
            $data = $message instanceof Exception ? $this->getExceptionFile($message) : null;
            $data = $this->formatLogLine($message, $data);
            $this->client->captureException($message, $data, $priority);
        }

        return parent::log($message, $priority);
    }

    public function setUserContext($data = null)
    {
        $this->client->user_context($data);
    }
}