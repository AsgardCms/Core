<?php

namespace Modules\Core\Traits;

trait EntityClassName
{
    protected function getEntityClassName()
    {
        if (isset(static::$entityNamespace)) {
            return static::$entityNamespace;
        }

        return $this->values()->getMorphClass();
    }
}
