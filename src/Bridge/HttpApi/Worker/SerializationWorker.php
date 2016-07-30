<?php

namespace Bridge\HttpApi\Worker;

use Bridge\HttpApi\Serializer\PathDenormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;
use Transfer\Worker\WorkerInterface;

class SerializationWorker implements WorkerInterface
{
    /**
     * @var array
     */
    private $deserialization;

    /**
     * @var array
     */
    private $source;

    /**
     * @param array $deserialization
     * @param array $source
     */
    public function __construct($deserialization, $source)
    {
        $this->deserialization = $deserialization;
        $this->source = $source;
    }

    /**
     * {@inheritdoc}
     */
    public function handle($object)
    {
        if (isset($this->deserialization['serializer'])) {
            $serializer = $this->deserialization['serializer'];
        } else {
            $serializer = $this->getDefaultSerializer();
        }

        $object = $serializer->deserialize(
            $object,
            $this->deserialization['type'],
            $this->source['format']
        );

        return $object;
    }

    private function getDefaultSerializer()
    {
        $normalizers = array();

        $normalizers[] = new GetSetMethodNormalizer();

        if (isset($this->deserialization['path'])) {
            $normalizers[] = new PathDenormalizer($this->deserialization['path']);
        }

        $normalizers[] = new ArrayDenormalizer();

        $encoders = array(new JsonEncoder());

        $serializer = new Serializer($normalizers, $encoders);

        return $serializer;
    }
}
