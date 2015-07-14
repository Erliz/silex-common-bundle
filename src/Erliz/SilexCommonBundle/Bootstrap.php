<?php

/**
 * @author Stanislav Vetlovskiy
 * @date 18.11.2014
 */

namespace Erliz\SilexCommonBundle;


use Erliz\SilexCommonBundle\Extension\Twig\AssetsExtension;
use Pimple;
use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;
use Silex\Provider\ServiceControllerServiceProvider;
use Symfony\Component\HttpFoundation\Request;

class Bootstrap implements ControllerProviderInterface
{
    private $prefix = __NAMESPACE__;

    /**
     * Returns routes to connect to the given application.
     *
     * @param Application $app An Application instance
     *
     * @return ControllerCollection A ControllerCollection instance
     */
    public function connect(Application $app)
    {
        $app->register(new ServiceControllerServiceProvider());
//        $this->setControllers($app);
        $controllersFactory = $this->getControllersFactory($app);

        $this->addSettings($app);
//        $this->addServices($app);
        $this->addExtensions($app);

        return  $controllersFactory;
    }

    /**
     * @param Application $app
     *
     * @return Pimple
     */
    private function setControllers(Application $app)
    {

    }

    /**
     * @param Application $app
     *
     * @return ControllerCollection
     */
    private function getControllersFactory(Application $app)
    {
        $controllersFactory = $app['controllers_factory'];

        return  $controllersFactory;
    }

    /**
     * @param Application $app
     */
    private function addSettings(Application $app)
    {
        $app['twig.loader.filesystem']->addPath(
            __DIR__ . '/Resources/views',
            'Common'
        );
    }

    /**
     * @param Application $app
     */
    private function addExtensions(Application $app)
    {
        $app['twig']->addExtension(new AssetsExtension($app));
    }

    /**
     * @param Application $app
     */
    private function addServices(Application $app)
    {

    }
}
