<?php

/**
 * @author Stanislav Vetlovskiy
 * @date 21.11.2014
 */

namespace Erliz\Common\Command;


use Monolog\Logger;
use Pimple;
use Symfony\Component\Console\Command\Command;

abstract class ApplicationAwareCommand extends Command
{
    /**
     * @var Pimple
     */
    private $projectApplication;

    /**
     * @param Pimple $app
     */
    public function setProjectApplication(Pimple $app)
    {
        $this->projectApplication = $app;
    }

    /**
     * @return Pimple
     */
    protected function getProjectApplication()
    {
        return $this->projectApplication;
    }

    /**
     * @return Logger
     */
    protected function getLogger()
    {
        return $this->getProjectApplication()['logger'];
    }
} 