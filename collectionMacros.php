<?php

use Illuminate\Support\Collection;

Collection::macro('toAssoc', function () {
    return $this->reduce(function ($assoc, $keyValuePair) {
        list($key, $value) = $keyValuePair;
        $assoc[$key] = $value;
        return $assoc;
    }, new static);
});

Collection::macro('mapToAssoc', function ($callback) {
    return $this->map($callback)->toAssoc();
});
