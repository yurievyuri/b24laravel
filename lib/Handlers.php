<?php

namespace Dev\Larabit;

use Bitrix\Main\Loader;

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

    private string $name;
    private $arguments;
    private string $type = self::DEFAULT_TYPE;
    private string $mode = self::DEFAULT_MODE;
    private bool $useDump = false;

    public static function moduleOnProlog()
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

    public function setArguments($arguments = null)
    {
        $this->arguments = $arguments;
        return $this;
    }

    private function extract()
    {
        $this->type = $this->name
            ? substr($this->name, 0, strpos($this->name, static::DEFAULT_SPLITTER))
            : static::DEFAULT_TYPE;

        $tmp = str_replace($this->type . static::DEFAULT_SPLITTER, '', $this->name);
        $this->mode = static::DEFAULT_SPLITTER . preg_split('/(?=[A-Z])/', $tmp, -1, PREG_SPLIT_NO_EMPTY)[0];
        if (!in_array($this->mode, static::DEFAULT_ALLOW_MODES)) {
            $this->mode = static::DEFAULT_MODE;
        }
        return $this;
    }

    public function useDump(): bool
    {
        if ($this->mode === self::ON_AFTER) return false;
        if ($this->mode === self::ON_BEFORE && $this->type !== self::ACTION) return false;
        return $this->useDump = true;
    }

    public static function __callStatic($name, $arguments = null): void
    {
        $obj = new static;
        $obj->setName($name)->setArguments($arguments);

        /** @var \Dev\Larabit\Api\Laravel $class */
        $class = '\\Dev\\Larabit\\Api\\' . $obj->getType();
        (new $class($obj))->send($obj);

        // в аргементах будут данные самого битрикс
        // из наименования обращения к несуществующему методу надо понять:

        // 1. это должна быть мгновенная реакция или действие
        // 2. может ли эта реакция быть обработана на стороне битрикс
        // 3. необходимо ли закешировать данные для последующей обработки на стороне ларавель
        // 4. создать обращение в ларавель и передать все данные
    }

}