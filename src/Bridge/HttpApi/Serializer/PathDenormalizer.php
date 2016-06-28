<?php

namespace Bridge\HttpApi\Serializer;

use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerInterface;

class PathDenormalizer implements DenormalizerInterface, SerializerAwareInterface
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var SerializerInterface|DenormalizerInterface
     */
    private $serializer;

    /**
     * @var PropertyAccessor
     */
    private $accessor;

    /**
     * @param string $path Property path
     */
    public function __construct($path)
    {
        $this->path = $path;

        $this->accessor = PropertyAccess::createPropertyAccessor();
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $class, $format = null, array $context = array())
    {
        return $this->serializer->denormalize($this->accessor->getValue($data, $this->path), $class, $format, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return
            $this->accessor->isReadable($data, $this->path) &&
            $this->accessor->getValue($data, $this->path) !== null;
    }

    /**
     * {@inheritdoc}
     */
    public function setSerializer(SerializerInterface $serializer)
    {
        if (!$serializer instanceof DenormalizerInterface) {
            throw new InvalidArgumentException('Expected a serializer that also implements DenormalizerInterface.');
        }

        $this->serializer = $serializer;
    }
}
