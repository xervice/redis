<?php

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
    public function current()
    {
        return $this->collection[$this->position];
    }

    public function next()
    {
        $this->position++;
    }

    /**
     * @return int
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return isset($this->collection[$this->position]);
    }

    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * @return int
     */
    public function count()
    {
        return \count($this->collection);
    }

    public function clear()
    {
        $this->collection = [];
    }
}