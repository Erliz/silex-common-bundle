<?php
/**
 * @author Stanislav Vetlovskiy
 * @date   21.11.2014
 */

namespace Erliz\SilexCommonBundle\Service;


use Doctrine\ORM\EntityManager;
use Monolog\Logger;
use Silex\Application;

class ApplicationAwareService
{
    /** @var Application */
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @return Application
     */
    protected function getApp()
    {
        return $this->app;
    }

    /**
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        return $this->app['orm.ems'][$this->app['region.skyforge.service']->getDbConnectionNameByRegion()];
    }

    protected function renderView($templatePath, array $variables = array())
    {
        $app = $this->getApp();
        return $app['twig']->render($templatePath, $variables);
    }

    /**
     * @return Logger
     */
    protected function getLogger()
    {
        return $this->app['logger'];
    }
}
