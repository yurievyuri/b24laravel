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
    public function useDump(): ?string
    {
        if ( !$this->getHandler()->useDump() ) return null;
        return Dumper::make( $this->getHandler()->getName(), $this->getHandler()->getArguments() );
    }

    public function useAgent( string $dumpKey = null ): bool
    {
        if ( !$this->getHandler()->useAgent() ) return false;

        $name = $this->getHandler()->getName();
        $agentName = '\\' . static::class . str_repeat(PATH_SEPARATOR,2) . $name .'("';

        // todo решить вопрос с объектами орм

        if ( $dumpKey != null ) {
            $agentName .= $dumpKey;
        } else {
            $args = is_array($this->getHandler()->getArguments())
                ? $this->getHandler()->getArguments()[0]
                : $this->getHandler()->getArguments()[1]
            ;
            if ( isset($args['ID']) && is_numeric($args['ID']))
            {
                $agentName .= $args['ID'];
            }
        }


        $agentName .= '");';

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
    public function send(): bool
    {
        $dumpKey = $this->useDump();
        if ( !$this->useAgent($dumpKey) ) {
            $class = '\\' . static::class;
            $class::{$this->getHandler()->getName()}($dumpKey, $this->getHandler()->getArguments());
        }
        return true;
    }


    /**
     * Processing for an agent
     * @param string $name
     * @param null $arguments
     * @return void
     */
    public static function __callStatic(string $name, $arguments = null)
    {
        // search for cached data
        $data['cached'] = Dumper::take($name, $arguments[0]);
        $data['args'] = $arguments;

        //  todo получение дополнительных данных для отправки
        //  необходимые условия будут храниться в файлах настройки,
        //  обращение туда необходимо реализовать собственным классом

        $obj = (new static);
        $obj->setMethod($name)
            ->request($data ?: $arguments);
    }
}