<?php
/**
 * @author Stanislav Vetlovskiy
 * @date   27.02.14
 */

namespace Erliz\SilexCommonBundle\Extension\Twig;


use Twig_Extension;
use Twig_SimpleFilter;
use Twig_SimpleFunction;

class StringExtension extends Twig_Extension
{

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'String';
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new Twig_SimpleFunction('pluralize', array($this, 'pluralize'))
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return array(
            new Twig_SimpleFilter('month_to_string', array($this, 'monthToString'))
        );
    }


    /**
     * @param Int   $number
     * @param Array $forms words массив из трех словоформ ('туров','тур','тура')
     *
     * @return string
     */
    public function pluralize($number, $forms)
    {
        $index = $number % 100;

        if ($number == 0 || ($index >= 11 && $index <= 14)) {
            $index = 0;
        } else {
            $index = ($index %= 10) < 5?($index > 2?2:$index):0;
        }

        return ($forms[$index]);
    }


    public function monthToString($month, $useGenitive = false)
    {
        $months = array(
            array('январь', 'января'),
            array('февраль', 'февраля'),
            array('март', 'марта'),
            array('апрель', 'апреля'),
            array('май', 'мая'),
            array('июнь', 'июня'),
            array('июль', 'июля'),
            array('август', 'августа'),
            array('сентябрь', 'сентября'),
            array('октябрь', 'октября'),
            array('ноябрь', 'ноября'),
            array('декабрь', 'декабря')
        );

        return $months[$month - 1][(int)$useGenitive];
    }
}
