<?php

namespace Dev\Larabit;

class Reflect
{
    private $object;

    /**
     * @param object|null $object
     */
    public function __construct(object $object = null)
    {
        $this->setObject($object);
    }

    /**
     * setObject
     * @param object $obj
     * @return $this
     */
    public function setObject(object $obj): Reflect
    {
        $this->object = $obj;
        return $this;
    }

    /**
     * getObject
     * @return mixed
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * get_included_classes
     * @param string $nameSpace
     * @param bool $removeNameSpace
     * @static
     * @return array
     */
    public static function get_included_classes(string $nameSpace, bool $removeNameSpace = true): array
    {
        $ar = [];
        $data = get_declared_classes();
        foreach ($data as $class) {
            if (!stristr($class, $nameSpace)) continue;
            $ar[] = $removeNameSpace ? str_ireplace($nameSpace, '', $class) : $class;
        }
        unset($data);
        return $ar;
    }

    /**
     * @throws \ReflectionException
     */
    public function getProtectedProperty(string $property)
    {
        $ref = new \ReflectionClass($this->getObject());
        $prop = $ref->getProperty($property);
        $prop->setAccessible(true);
        return $prop->getValue($this->getObject());
    }
}