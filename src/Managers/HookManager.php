<?php

namespace Managers;

abstract class HookManager
{
    abstract public function manage();

    abstract public function response($results);
}