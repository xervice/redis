<?php
declare(strict_types=1);


namespace Xervice\Redis\Business\Model\Commands;


class Collection implements \Iterator, \Countable
{
    /**
     * @var \Xervice\Redis\Business\Model\Commands\CommandProviderInterface[]
     */
    private $collection;

    /**
     * @var int
     */
    private $position;

    /**
     * Collection constructor.
     *
     * @param \Xervice\Redis\Business\Model\Commands\CommandProviderInterface[] $collection
     */
    public function __construct(array $collection)
    {
        foreach ($collection as $validator) {
            $this->add($validator);
        }
    }

    /**
     * @param \Xervice\Redis\Business\Model\Commands\CommandProviderInterface $validator
     */
    public function add(CommandProviderInterface $validator): void
    {
        $this->collection[] = $validator;
    }

    /**
     * @return \Xervice\Redis\Business\Model\Commands\CommandProviderInterface
     */
    public function current(): CommandProviderInterface
    {
        return $this->collection[$this->position];
    }

    public function next(): void
    {
        $this->position++;
    }

    /**
     * @return int
     */
    public function key(): int
    {
        return $this->position;
    }

    /**
     * @return bool
     */
    public function valid(): bool
    {
        return isset($this->collection[$this->position]);
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return \count($this->collection);
    }
}
