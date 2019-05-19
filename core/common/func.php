<?php

function throwErr (string $errMsg, callable $callback = null)
{
    if (DEBUG) {
        throw new \Exception($errMsg);
    } else {
        $callback();
    }
}