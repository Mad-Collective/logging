<?php
/**
 * Created by PhpStorm.
 * User: jaroslawgabara
 * Date: 20/09/16
 * Time: 18:07
 */

namespace Cmp\Logging\Handler;


use Cmp\Logging\Provider\ProviderInterface;
use Zend\Log\Processor\ProcessorInterface;

interface HandlerInterface
{
    public function addProvider(ProviderInterface $provider);


    public function addProcessor(ProcessorInterface $processor);

}