<?php
/**
 * @author Stanislav Vetlovskiy
 * @date   21.11.14
 */

namespace Erliz\SilexCommonBundle\Extension\Twig;


use Symfony\Component\Yaml\Exception\RuntimeException;
use Twig_SimpleFilter;

class TranslateExtension extends ApplicationAwareExtension
{
    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'Translate';
    }

    public function getFilters()
    {
        return array(
            new Twig_SimpleFilter('trans', array($this, 'trans'))
        );
    }

    /**
     * @param string $stringKey
     *
     * @return string
     */
    public function trans($stringKey)
    {
        $app = $this->getApp();

        if (!$app['translator']) {
            throw new RuntimeException('TranslationServiceProvider should be registered to use trans function');
        }

        return $app['translator']->trans($stringKey);
    }
}
