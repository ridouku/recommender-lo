<?php
namespace MatrixGenerator;
use RecursiveIteratorIterator;
use ArrayIterator;
// resource from http://codepad.org/ZmkGFBBZ
/**
 * Stack based array iterator over sub-arrays
 * 
 * hasChildren: is_array();
 */
class FlatRecursiveArrayIterator extends ArrayIterator
{
    private $stack;
    private $key;
    /**
     * solve stack into a state where first value
     * is not an array.
     */
    private function resolveStack()
    {
        while($this->stack)
        {
            $current = reset($this->stack);
            if (is_array($current))
            {
                array_shift($this->stack);
                $this->stack = array_merge(array_values($current), $this->stack);
                continue;
            }
            break;
        }
    }
    /**
     * fetch from and forward parent until there is something on stack which
     * is not an array.
     */
    private function forwardParent()
    {
        while (parent::valid() && !$this->stack)
        {
            $this->stack = array_merge(array_values((array) parent::current()), $this->stack);
            $this->key = parent::key();
            parent::next();
            $this->resolveStack();
        }
    }
    public function rewind()
    {
        $this->stack = array();
        $this->key = NULL;
        parent::rewind();
        $this->forwardParent();
    }
    public function valid()
    {
        return (bool) $this->stack;
    }
    public function current()
    {
        if ($this->stack)
        {
            return reset($this->stack);
        }
    }
    public function key()
    {
        return $this->key;
    }
    public function next()
    {
        if ($this->stack)
        {
            array_shift($this->stack);
            $this->resolveStack();
        }

        if (!$this->stack)
        {
            $this->forwardParent();
        }
    }
}