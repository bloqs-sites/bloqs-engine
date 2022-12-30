<?php

/**
 *
 */

use TorresDeveloper\MVC\Controller;

class MarketController extends Controller
{
    public function index(): void
    {
        var_dump($this->req->getUri());
    }
}
