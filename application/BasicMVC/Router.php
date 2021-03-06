<?php
/**
 * Router
 * Using Request, this class requires the Controller and then invokes the
 * appropriate Method, appended with with arguments (if available).
 * If the controller doesn't exist or isn't readable, it throws an 404 exception.
 */

namespace BasicMVC;

class Router
{
    public static function route(Request $request)
    {
        $controller = $request->getController() . 'Controller';
        $method     = $request->getMethod();
        $args       = $request->getArguments();

        $controllerFile = SITE_PATH . 'Controllers/' . $controller . '.php';

        /* Given that controllerFile exists and is readable,
           include the file, instantiate controller-class and call method
           (default 'index') */
        if ( is_readable($controllerFile) )
        {
            require_once($controllerFile);

            $controllerName = '\BasicMVC\Controllers\\' . $controller;
            $controllerInst = new $controllerName;
            $method     = ( is_callable([$controllerInst, $method]) ) ? $method: 'index';

            if ( !empty($args) ) {
                call_user_func_array([$controllerInst, $method], $args);
            } else {
                call_user_func([$controllerInst, $method]);
            }
            return;
        }
        throw new \Exception('404 -  ' . $controllerName . ' not found');
    }
}
