<?php

namespace Oro\Bundle\ApiBundle\Processor\Config\GetConfig\Rest;

use Symfony\Component\HttpFoundation\Response;

use Oro\Component\ChainProcessor\ContextInterface;
use Oro\Component\ChainProcessor\ProcessorInterface;
use Oro\Bundle\ApiBundle\Config\EntityDefinitionConfig;
use Oro\Bundle\ApiBundle\Config\StatusCodeConfig;
use Oro\Bundle\ApiBundle\Config\StatusCodesConfig;
use Oro\Bundle\ApiBundle\Processor\Config\ConfigContext;

abstract class CompleteStatusCodes implements ProcessorInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContextInterface $context)
    {
        /** @var ConfigContext $context */

        $definition = $context->getResult();
        if (null === $definition) {
            $definition = new EntityDefinitionConfig();
            $context->setResult($definition);
        }
        $statusCodes = $definition->getStatusCodes();
        if (null === $statusCodes) {
            $statusCodes = new StatusCodesConfig();
            $context->getResult()->setStatusCodes($statusCodes);
        }
        $this->addStatusCodes($statusCodes);
    }

    /**
     * @param StatusCodesConfig $statusCodes
     */
    protected function addStatusCodes(StatusCodesConfig $statusCodes)
    {
        if (!$statusCodes->hasCode(Response::HTTP_INTERNAL_SERVER_ERROR)) {
            $statusCodes->addCode(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                $this->createStatusCode('Returned when an unexpected error occurs')
            );
        }
    }

    /**
     * @param string $description
     *
     * @return StatusCodeConfig
     */
    protected function createStatusCode($description)
    {
        $code = new StatusCodeConfig();
        $code->setDescription($description);

        return $code;
    }
}
