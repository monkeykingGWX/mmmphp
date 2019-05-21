<?php

function throwErr (string $errMsg, callable $callback = null)
{
    if (APP_DEBUG) {
        throw new \Exception($errMsg);
    } else {
        $callback();
    }
}