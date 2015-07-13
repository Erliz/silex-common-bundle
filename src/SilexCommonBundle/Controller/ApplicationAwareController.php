<?php
/**
 * @author Stanislav Vetlovskiy
 * @date   21.11.2014
 */

namespace Erliz\SilexCommonBundle\Controller;


use Doctrine\ORM\EntityManager;
use Silex\Application;

class ApplicationAwareController
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
        return $this->app['orm.em'];
    }

    protected function renderView($templatePath, array $variables = array())
    {
        $app = $this->getApp();
        return $app['twig']->render($templatePath, $variables);
    }
}
