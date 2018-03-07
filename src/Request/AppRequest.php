<?php

namespace Smartie\Request;

use ReflectionClass;
use ReflectionProperty;

/**
 * @author Ivo Hradek <ivohradek@gmail.com>
 */
abstract class AppRequest
{
    /**
     * @param array $hidden
     * @return array
     */
    public function toArray($hidden = [])
    {
        $rc = new ReflectionClass($this);
        $props   = $rc->getProperties(
            ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED | ReflectionProperty::IS_PRIVATE);

        $array = [];
        foreach ($props as $prop) {
            if (in_array($prop, $hidden)) {
                continue;
            }
            $array[$prop->name] = $prop->getValue($this);
        }

        return $array;
    }
}