<?php

namespace Bridge\HttpApi\Action;

use Bridge\Action\ProceduralAction;
use Bridge\HttpApi\Adapter\HttpApiAdapter;
use Bridge\HttpApi\Worker\SerializationWorker;
use Bridge\HttpApi\Worker\VirtualizationWorker;
use Bridge\Registry;
use Bridge\RegistryAwareInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Transfer\Adapter\CacheAdapter;
use Transfer\Adapter\Transaction\Request;
use Transfer\Adapter\CallbackAdapter;

class HttpApiAction extends ProceduralAction implements RegistryAwareInterface
{
    /**
     * @var array Source configuration
     */
    private $source;

    /**
     * @var array Deserialization configuration
     */
    private $deserialization;

    /**
     * @var array Virtual properties
     */
    private $virtualProperties;

    /**
     * @var Registry
     */
    private $registry;

    /**
     * @param string $name    HTTP API Action name
     * @param array  $options Action options
     */
    public function __construct($name, array $options = array())
    {
        parent::__construct($name);

        $this->source = isset($options['source']) ? $options['source'] : null;
        $this->deserialization = isset($options['deserialization']) ? $options['deserialization'] : null;
        $this->virtualProperties = isset($options['virtual_properties']) ? $options['virtual_properties'] : null;
    }

    /**
     * @param Registry $registry
     */
    public function setRegistry(Registry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(array $arguments = array())
    {
        if ($this->source) {
            $this->addSource($arguments);
        }

        if ($this->deserialization) {
            $this->addSerializationWorker();
        }

        if (isset($this->deserialization['multiple']) && $this->deserialization['multiple']) {
            $this->builder->split();
        }

        if ($this->virtualProperties) {
            $this->addVirtualizationWorker($arguments);
        }

        $response = parent::execute($arguments);

        if (isset($this->deserialization['multiple']) && $this->deserialization['multiple']) {
            return $response->getIterator();
        } else {
            return $response->getIterator()->current();
        }
    }

    /**
     * Adds source adapter.
     *
     * @param array $arguments
     */
    private function addSource(array $arguments = array())
    {
        $extraData = &$this->extraData;

        $this->builder->addSource(
            new CallbackAdapter(function (Request $request) use (&$extraData) {
                $adapter = new CacheAdapter(
                    new FilesystemAdapter(),
                    new HttpApiAdapter()
                );

                $response = $adapter->receive($request);

                $extraData = $response->getHeaders();

                return $response;
            }),
            new Request(array(
                'source' => $this->source,
                'arguments' => $arguments,
                'service' => $this->getGroup()->getService()->getName(),
                'group' => $this->getGroup()->getName(),
                'action' => $this->getName(),
            )));
    }

    /**
     * Adds serialization worker.
     */
    private function addSerializationWorker()
    {
        $this->builder->addWorker(new SerializationWorker($this->deserialization, $this->source));
    }

    /**
     * Adds virtualization worker.
     *
     * @param array $arguments
     */
    private function addVirtualizationWorker($arguments = array())
    {
        $this->builder->addWorker(new VirtualizationWorker(
            $this->registry,
            $this->virtualProperties,
            $this->deserialization,
            $arguments
        ));
    }
}
