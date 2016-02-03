<?php

namespace DynImage\Filter;

use DynImage\FilterInterface;
use DynImage\Events;
use DynImage\Filter;

class Blur extends Filter implements FilterInterface
{
    protected $sigma = 3;

    protected $event = Events::AFTER_CREATE_IMAGE;

    public function apply()
    {
        $this->dynImageAware->getImage()->effects()->blur($this->sigma);
    }


    /**
     * @return int
     */
    public function getSigma()
    {
        return $this->sigma;
    }

    /**
     * @param int $sigma
     * @return Blur
     */
    public function setSigma($sigma)
    {
        $this->sigma = $sigma;
        return $this;
    }
}
