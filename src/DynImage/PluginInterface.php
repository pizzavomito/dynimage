<?php

namespace DynImage;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;


interface PluginInterface
{
    public function init(Application $app, $plugins);
          
    public function connect(Request $request,Application $app);

}

