<?php

namespace Smartie\Utils;

use ReflectionProperty;

/**
 * Utils related to entity manipulation.
 *
 * @author Ivo Hradek <ivohradek@gmail.com>
 */
class EntityUtils
{
    private function __construct() { }

    /**
     * Fill entity with provided data as key-value array, where key represents entity's property.
     * If no such property exists, {@link InvalidArgumentException} is thrown.
     *
     * @param $entity
     * @param array $data
     */
    public static function fill($entity, array $data)
    {
        $class = get_class($entity);
        $rc = new \ReflectionClass($class);
        $props = $rc->getProperties(
            ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED | ReflectionProperty::IS_PRIVATE
        );
        $propNames = array_map(function ($prop) { return $prop->name; }, $props);
        $diff = array_diff(array_keys($data), $propNames);

        if (!empty($diff)) {
            throw new \InvalidArgumentException("$class doesn't have [" . implode(", ", $diff) . "] properties");
        }

        foreach ($data as $key => $value) {
            $prop = $rc->getProperty($key);
            $prop->setAccessible(true);
            $prop->setValue($entity, $value);
        }
    }
}

