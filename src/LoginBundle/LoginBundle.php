<?php

namespace LoginBundle;

use Pimcore\Extension\Bundle\AbstractPimcoreBundle;

class LoginBundle extends AbstractPimcoreBundle
{
    public function getJsPaths()
    {
        return [
            '/bundles/login/js/pimcore/startup.js'
        ];
    }
}