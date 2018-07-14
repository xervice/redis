<?php
declare(strict_types=1);

namespace Xervice\Redis\Transaction;

class TransactionCollection implements \Iterator, \Countable
{
    /**
     * @var \Xervice\Redis\Transaction\TransactionInterface[]
     */
    private $collection = [];

    /**
     * @var int
     */
    private $position;

    /**
     * @param \Xervice\Redis\Transaction\TransactionInterface $validator
     */
    public function add(TransactionInterface $validator)
    {
        $this->collection[] = $validator;
    }

    /**
     * @return \Xervice\Redis\Transaction\TransactionInterface
     */
    public function current(): TransactionInterface
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

    public function clear(): void
    {
        $this->collection = [];
    }
}
