<?php

namespace DynImage;

use Symfony\Component\EventDispatcher\EventDispatcher;

class DynImage
{
    private $filters = array();

    public function getFilters()
    {
        return $this->filters;
    }

    public function add(FilterInterface $filter, $event = null)
    {
        if (!is_null($event)) {
            $filter->setEvent($event);
        }

        $this->filters[] = $filter;
    }


    /**
     * @param $imageContent
     * @param null $driver
     * @return \Imagine\Image\AbstractImage|void
     */
    public function apply($imageContent, $driver = null)
    {
        if (empty($this->filters)) {
            return;
        }

        $dispatcher = new EventDispatcher();
        $dynimageAware = new DynImageAware();

        $dynimageAware->setDriver($driver)->loadImage($imageContent);

        foreach ($this->filters as $filter) {
            $filter->connect($dynimageAware, $dispatcher);
        }

        $dispatcher->dispatch(Events::AFTER_CREATE_IMAGE);

        $dispatcher->dispatch(Events::EARLY_APPLY_FILTER);

        $dispatcher->dispatch(Events::LATE_APPLY_FILTER);

        $dispatcher->dispatch(Events::FINISH_CREATE_IMAGE);

        return $dynimageAware->getImage();
    }
}
