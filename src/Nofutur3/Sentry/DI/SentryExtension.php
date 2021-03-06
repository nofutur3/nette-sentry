<?php

namespace Nofutur3\Sentry\DI;

use Nette\Configurator;
use Nette\DI\Compiler;
use Nette\DI\CompilerExtension;
use Nette\PhpGenerator\ClassType;
use Nette\Utils\Validators;
use Nofutur3\Sentry\Sentry;
use Tracy\Debugger;

class SentryExtension extends CompilerExtension
{
    private $defaults = [];

    public function __construct()
    {
        $this->defaults = $this->getDefaults();
    }

    public function afterCompile(ClassType $class)
    {
        $config = $this->getConfig($this->defaults);

        Validators::assertField($config, 'dsn', 'string');

        if (method_exists($class, 'getMethod')) {
            $init = $class->getMethod('initialize');
        } else {
            $init = $class->methods['initialize'];
        }

        $code = '$sentry = new '.Sentry::class.'(?, ?, ?, ?, ?, ?, ?);'.PHP_EOL;
        $code .= Debugger::class.'::$onFatalError[] = function($e) use ($sentry) {$sentry->onFatalError($e);};'.PHP_EOL;
        $code .= Debugger::class.'::setLogger($sentry);';

        $init->addBody($code, $config);
    }

    public static function register(Configurator $configurator)
    {
        $configurator->onCompile[] = function ($config, Compiler $compiler) {
            $compiler->addExtension('sentry', new SentryExtension());
        };
    }

    private function getDefaults()
    {
        $defaults = [];
        $defaults['dsn'] = null;
        $defaults['debug'] = false;
        $defaults['dir'] = Debugger::$logDirectory;
        $defaults['email'] = Debugger::$email;
        $defaults['user'] = [];
        $defaults['skip_capture'] = [];
        $defaults['options'] = [];

        return $defaults;
    }
}
