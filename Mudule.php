<?php

namespace mickey\article;
use yii\base\Module as BaseModule;

/**
 * article module definition class
 */
class Mudule extends BaseModule
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'mickey\article\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
