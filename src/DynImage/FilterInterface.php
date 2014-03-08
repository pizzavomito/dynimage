<?php



namespace DynImage;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

interface FilterInterface
{
    
    public function connect(Request $request, Application $app);
    
    
}

