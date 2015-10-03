<?php
namespace TYPO3\Eel\FlowQuery\Operations;

/*
 * This file is part of the TYPO3.Eel package.
 *
 * (c) Contributors of the Neos Project - www.neos.io
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * Get the last element inside the context.
 */
class LastOperation extends AbstractOperation
{
    /**
     * {@inheritdoc}
     *
     * @var string
     */
    protected static $shortName = 'last';

    /**
     * {@inheritdoc}
     *
     * @param \TYPO3\Eel\FlowQuery\FlowQuery $flowQuery the FlowQuery object
     * @param array $arguments Ignored for this operation
     * @return void
     */
    public function evaluate(\TYPO3\Eel\FlowQuery\FlowQuery $flowQuery, array $arguments)
    {
        $context = $flowQuery->getContext();
        if (count($context) > 0) {
            $flowQuery->setContext(array(end($context)));
        } else {
            $flowQuery->setContext(array());
        }
    }
}
