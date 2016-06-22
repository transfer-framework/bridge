<?php

namespace Bridge\HttpApi\Worker;

use Bridge\Action\AbstractAction;
use Bridge\Registry;
use ProxyManager\Factory\LazyLoadingValueHolderFactory;
use ProxyManager\Proxy\LazyLoadingInterface;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Transfer\Worker\WorkerInterface;

class VirtualizationWorker implements WorkerInterface
{
    /**
     * @var array
     */
    private $arguments;

    /**
     * @var Registry
     */
    private $registry;

    /**
     * @var array
     */
    private $virtualProperties;

    /**
     * @var array
     */
    private $deserialization;

    /**
     * @param Registry $registry
     * @param $virtualProperties
     * @param array $deserialization
     * @param array $arguments
     */
    public function __construct($registry, $virtualProperties, $deserialization, $arguments)
    {
        $this->arguments = $arguments;
        $this->registry = $registry;
        $this->virtualProperties = $virtualProperties;
        $this->deserialization = $deserialization;
    }
    /**
     * {@inheritdoc}
     */
    public function handle($object)
    {
        $factory = new LazyLoadingValueHolderFactory();

        $initializer = function (&$wrappedObject, LazyLoadingInterface $proxy, $method, array $parameters, &$initializer) use ($object) {
            static $tracker = array();

            $wrappedObject = $object;

            if ($this->registry == null) {
                return true;
            }

            if (!in_array($method, $tracker)) {
                foreach ($this->virtualProperties as $name => $parameters) {
                    if (preg_match(sprintf('/^.*(%s|%s)$/', $name, ucfirst($name)), $method)) {
                        $action = $this->registry->get($parameters['action']);

                        if (!$action instanceof AbstractAction) {
                            throw new \LogicException(sprintf(
                                'Component "%s" must resolve to an action, "%s" given.',
                                $parameters['action'], get_class($action)
                            ));
                        }

                        $language = new ExpressionLanguage();

                        $parameters['arguments'] = array_map(
                            function ($argument) use ($object, $language) {
                                return $language->evaluate(
                                    $argument,
                                    array(
                                        'object' => $object,
                                        'parent_arguments' => $this->arguments,
                                    )
                                );
                            },
                            $parameters['arguments']
                        );

                        $result = $action->getGroup()->call($action->getName(), $parameters['arguments']);

                        $property = new \ReflectionProperty(get_class($object), $name);
                        $property->setAccessible(true);
                        $property->setValue($object, $result);

                        $tracker[] = $method;

                        break;
                    }
                }
            }

            return true;
        };

        return $factory->createProxy($this->deserialization['single_type'], $initializer);
    }
}
