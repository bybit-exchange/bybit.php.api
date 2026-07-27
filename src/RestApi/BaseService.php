<?php

declare(strict_types=1);

namespace Bybit\RestApi;

use Bybit\Session;

abstract class BaseService
{
    protected Session $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }
}
