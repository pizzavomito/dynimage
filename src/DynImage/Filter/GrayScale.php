<?php

namespace DynImage\Filter;

use DynImage\FilterInterface;
use DynImage\Filter;
use DynImage\Events;

class GrayScale extends Filter implements FilterInterface
{
    protected $event = Events::EARLY_APPLY_FILTER;

    public function apply()
    {
        $this->dynImageAware->getImage()->effects()->grayscale();
    }
}
