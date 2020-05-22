<?php

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

$collection = new RouteCollection();

$collection->add('login_homepage', new Route('/login', array(
    '_controller' => 'LoginBundle:Login:login',
)));
$collection->add('logout', new Route('/logout', array(
    '_controller' => 'LoginBundle:Login:logout',
)));

return $collection;
