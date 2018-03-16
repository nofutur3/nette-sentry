<?php
/**
 * Sentry.php.
 *
 * @author   : Jakub Vyvazil <jakub@vyvazil.cz>
 * @copyright: 2016
 */

namespace Nofutur3\Sentry;

use Exception;
use Nette\Environment;
use Tracy\Debugger;
use Tracy\Logger;

class Sentry extends Logger
{
    /** @var \Nette\Security\User @inject */
    public $user;

    /** @var \Raven_Client */
    private $client;

    /** @var bool */
    private $isEnabled = true;

    /**
     * Sentry constructor.
     *
     * @param mixed $dsn
     * @param mixed $isDebugMode
     * @param null|mixed $directory
     * @param null|mixed $email
     * @param mixed $options
     * @param mixed $user
     * @param mixed $skip_capture
     */
    public function __construct($dsn, $isDebugMode = false, $directory = null, $email = null, $user = [], $skip_capture = [], $options = [])
    {
        parent::__construct($directory, $email, Debugger::getBlueScreen());

        $this->isEnabled = Debugger::$productionMode || $isDebugMode;
        $this->client = new \Raven_Client($dsn, $options);

        $sentry = $this;
        Debugger::$onFatalError[] = function ($e) use ($sentry, $skip_capture, $user) {
            $sentry->onFatalError($e, $skip_capture, $user);
        };
        Debugger::setLogger($this);
    }

    public function onFatalError($error, $skip_capture, $user)
    {
        if ($this->isEnabled && $this->shouldNotBeSkipped($error, $skip_capture)) {
            if (!$this->user) {
                $this->client->user_context($this->getUserContext($user));
            }
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

    private function shouldNotBeSkipped($error, $skip_capture)
    {
        return !in_array(get_class($error), $skip_capture, true);
    }

    private function getUserContext($user)
    {
        $identity = $this->user ? $this->user->getIdentity() : null;
        $propertyUsername = array_key_exists('username', $user) ? $user['username'] : 'username';
        $propertyEmail = array_key_exists('email', $user) ? $user['email'] : 'email';
        $username = $identity && array_key_exists($propertyUsername, $identity->data) ? $identity->data[$propertyUsername] : null;
        $email = $identity && array_key_exists($propertyEmail, $identity->data) ? $identity->data[$propertyEmail] : null;

        // user context
        $usrctx = [
            'id' => $this->user ? $this->user->id : null,
            'ip_address' => Environment::getHttpRequest()->getRemoteAddress(),
        ];

        if ($email) {
            $usrctx['email'] = $email;
        }

        if ($username) {
            $usrctx['username'] = $username;
        }

        return $usrctx;
    }
}
