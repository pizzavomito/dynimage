<?php

namespace DynImage;

use Symfony\Component\EventDispatcher\EventDispatcher;
use DynImage\DynImage;

interface FilterInterface {

    public function apply();

    public function setEvent($event);

    public function connect(DynImageAware $dynImageAware, EventDispatcher $dispatcher);
}
