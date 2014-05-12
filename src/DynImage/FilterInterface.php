<?php

namespace DynImage;

use Symfony\Component\EventDispatcher\EventDispatcher;
use DynImage\DynImage;

interface FilterInterface {

    public function apply();

    public function getArguments();

    public function setEvent($event);

    public function connect(DynImage $dynimage, EventDispatcher $dispatcher);
}
