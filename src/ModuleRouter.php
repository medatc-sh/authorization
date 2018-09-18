<?php

namespace Authorization;

class ModuleRouter
{

    public static function resolve()
    {
        $data = [];
        $app = app();
        $routers = method_exists($app, 'getRoutes') ? app()->getRoutes() : app()->router->getRoutes();

        foreach ($routers as $key => $router) {
            
            $uses = array_get($router, 'action.uses', '');
            $class = explode('@', $uses);

            if (count($class) != 2) {
                continue;
            }

            try {
                $ref = new \ReflectionClass($class[0]);
                preg_match('/@functional (?<comment>.*)/', $ref->getMethod($class[1])->getDocComment(), $matches);
                $routerComment = array_get($matches, 'comment', '');

                if ($routerComment) {
                    $data[] = [
                        'code' => md5($key),
                        'data' => preg_replace('#\{(\w+):(.*)\}#', '(?P<$1>$2)', "#$key#"),
                        'name' => trim(array_get($matches, 'comment', '')),
                        'type' => 'ROUTER',
                        'own_mod' => 1,
                    ];

                }
            } catch (\Exception $ex) {
            }
        }
        return $data;
    }
}