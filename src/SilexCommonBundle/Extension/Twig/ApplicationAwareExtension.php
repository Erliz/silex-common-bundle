<?php
/**
 * @author Stanislav Vetlovskiy
 * @date   21.11.2014
 */

namespace Erliz\SilexCommonBundle\Extension\Twig;


use Silex\Application;
use Twig_Extension;

abstract class ApplicationAwareExtension extends Twig_Extension
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
    public function getApp()
    {
        return $this->app;
    }
}
