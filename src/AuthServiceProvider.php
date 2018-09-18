<?php

namespace Authorization;

use Authorization\Guard;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        method_exists($this->app, 'addRoute') ?
            $this->app->addRoute('GET', 'permission/router/presentation', function () {
                return ModuleRouter::resolve();
            }) :
            $this->app->router->addRoute('GET', 'permission/router/presentation', function () {
                return ModuleRouter::resolve();
            });


        $this->app->singleton('authorization', function ($app) {
            /**
             * @var  Application $app
             */
            /**
             * @var  Request $request
             */
            $request = $app['request'];

            return
                method_exists($app, 'makeWith') ?
                    $app->makeWith(Guard::class, [
                        'platform' => $request->header('platform'),
                        'organization' => $request->header('organization') ?? $request->input('organization_id'),
                        'uri' => $request->getRequestUri()
                    ]) :
                    $app->make(Guard::class, [
                        'platform' => $request->header('platform'),
                        'organization' => $request->header('organization') ?? $request->input('organization_id'),
                        'uri' => $request->getRequestUri()
                    ]);
        });
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * @var AuthManager $authManager
         */
        $authManager = $this->app['auth'];
        $authManager->viaRequest('api', function (Request $request) {
            $token = $request->header('token', '');
            $platform = $request->header('platform', '');
            $organization = $request->header('organization', 0);
            if ($token) {
                try {
                    $this->app['authorization']->connect();
                    $data = $this->app['authorization']->auth($token);
                    $user = User::create($data);
                    return $user;
                } catch (\Exception $ex) {
                    \Log::error($ex->getMessage());
                }
                return null;
            }
            return null;
        });
    }
}
