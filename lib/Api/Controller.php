<?php

namespace Dev\Larabit\Api;

use Bitrix\Main\Type\DateTime;
use Dev\Larabit\Agent;
use Dev\Larabit\Dumper;
use Dev\Larabit\Handlers;
use Dev\Larabit\Hooks;
use Dev\Larabit\Http;
use Exception;
use ReflectionException;
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

class Controller extends Http
{
    protected string $path = '/controller';
    protected Handlers $handler;

    /**
     * @throws Exception
     */
    public static function register(string $method = 'connection.register')
    {
        $data = Hooks::getInboundHookData();
        $obRes = (new self)
            ->setMethod($method)
            ->request($data);

        if (!$obRes->getResponse('success') ) {
            throw new Exception('Error: ' . $obRes->getResponse('message'));
        }

        return $obRes->getResponse();
    }

    public function setHandler(Handlers $handler): Controller
    {
        $this->handler = $handler;
        return $this;
    }
    public function getHandler(): Handlers
    {
        return $this->handler;
    }

    /**
     * @throws ReflectionException
     */
    public function useDump(): bool
    {
        if ( !$this->getHandler()->useDump() ) return true;
        return Dumper::make( $this->getHandler()->getName(), $this->getHandler()->getArguments() );
    }

    public function useAgent(&$agentName): bool
    {
        if ( !$this->getHandler()->useAgent() ) return false;
        $name = $this->getHandler()->getName();
        $agentName = '\\' . static::class . '::agent("'. $name .'"';

        $args = $this->getHandler()->getArguments();
        if ( isset($args['ID']) && is_numeric($args['ID']))
        {
            $agentName .= ', "' . $args['ID'] . '"';
        }
        $agentName .= ');';
        return Agent::create([
            'NAME'=> $agentName,
            'IS_PERIOD' => 'Y',
            'SORT'    => 1000,
            'DATE_EXEC' => (new DateTime())
                ->add("T1M")
                ->format(Agent::defaultDateTimeFormat())
        ], true);
    }

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public function send()
    {
        if ( !$this->useDump() ) {
            throw new Exception(Loc::getMessage('DEV_LARABIT_DUMP_CREATION_ERROR'));
        }

        if ( $this->useAgent($agentName) ) return true;
        eval($agentName);
    }

    /**
     * Обработка для агента
     * @param $name
     * @param $arguments
     * @return void
     */
    public static function __callStatic($name, $arguments)
    {
        $args = [];

        $obRes = (new static)
            ->setMethod('handler/' . $name)
            ->request($arguments);
    }
}