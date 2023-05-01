<?php

namespace Dev\Larabit;

use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Dev\Larabit\Api\Controller;
use Dev\Larabit\Api\Handler;
use ReflectionException;

/**
 * @method static ActionOnBeforeCrmSomethingData(int[] $array)
 * @method static ActionOnAfterCrmSomethingData(int[] $array)
 * @method static ReactionOnBeforeCrmSomethingData(int[] $array)
 */
class Handlers
{
    private const DEFAULT_SPLITTER = 'On';
    private const ACTION = 'Action';
    private const REACTION = 'Reaction';
    private const DEFAULT_TYPE = self::ACTION;
    public const ON_BEFORE = 'OnBefore';
    public const ON_AFTER = 'OnAfter';

    private const DEFAULT_ALLOW_MODES = [
        self::ON_BEFORE,
        self::ON_AFTER,
    ];
    private const DEFAULT_CACHE_MODE = self::DEFAULT_ALLOW_MODES[0];
    private const DEFAULT_MODE = self::DEFAULT_ALLOW_MODES[1];

    protected string $name;
    protected $arguments;
    protected string $type = self::DEFAULT_TYPE;
    protected string $mode = self::DEFAULT_MODE;
    protected bool $useDump = false;

    /**
     * @throws LoaderException
     */
    public static function moduleOnProlog(): void
    {
        Loader::includeModule(\Dev\Larabit\Option::getModuleId());
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getMode(): string
    {
        return $this->mode;
    }

    public function setName(string $name)
    {
        $this->name = $name;
        $this->extract();
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setArguments($arguments = null): Handlers
    {
        $this->arguments = $arguments;
        return $this;
    }

    public function getArguments()
    {
        return $this->arguments;
    }

    private function extract(): void
    {
        $this->type = $this->name
            ? substr($this->name, 0, strpos($this->name, static::DEFAULT_SPLITTER))
            : static::DEFAULT_TYPE;

        $tmp = str_replace($this->type . static::DEFAULT_SPLITTER, '', $this->name);
        $this->mode = static::DEFAULT_SPLITTER . preg_split('/(?=[A-Z])/', $tmp, -1, PREG_SPLIT_NO_EMPTY)[0];
        if (!in_array($this->mode, static::DEFAULT_ALLOW_MODES)) {
            $this->mode = static::DEFAULT_MODE;
        }
    }

    public function useDump(): bool
    {
        if ($this->mode === self::ON_AFTER) return false;
        if ($this->mode === self::ON_BEFORE && $this->type !== self::ACTION) return false;
        return $this->useDump = true;
    }

    public function useAgent(): bool
    {
        $option =  Option::getUseAgent();
        if ( !$option ) return false;
        if ( $this->mode === self::ON_BEFORE && $this->type === self::REACTION ) return false;
        if ( $this->mode === self::ON_AFTER ) return true;
        return true;
    }
    /**
     * @throws ReflectionException
     */
    public static function __callStatic($name, $arguments = null): void
    {
        $obj = new self;
        $obj->setName($name)->setArguments($arguments);

        /** @var Handler $class */
        $class = '\\Dev\\Larabit\\Api\\' . $obj->getType();
        ( new $class )->setHandler($obj)->register();
    }
}