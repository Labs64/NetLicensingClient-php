<?php

namespace NetLicensing;

use ArrayIterator;
use Countable;
use ArrayAccess;
use JsonSerializable;
use IteratorAggregate;

class Page implements ArrayAccess, Countable, IteratorAggregate, JsonSerializable
{
    /**
     * The content of this page
     * @var array
     */
    protected $content;

    /**
     * The number of the current page
     * @var int
     */
    protected $pageNumber;

    /**
     * The number of elements on the page
     * @var int
     */
    protected $itemsNumber;

    /**
     * The number of total pages
     * @var int
     */
    protected $totalPages;

    /**
     * The total amount of elements
     * @var int
     */
    protected $totalItems;

    /**
     * Page constructor.
     *
     * @param array $content
     * @param $pageNumber
     * @param $itemsNumber
     * @param $totalPages
     * @param $totalItems
     * @param $hasNext
     */
    public function __construct(array $content, $pageNumber, $itemsNumber, $totalPages, $totalItems)
    {
        $this->content = $content;
        $this->pageNumber = $pageNumber;
        $this->itemsNumber = $itemsNumber;
        $this->totalPages = $totalPages;
        $this->totalItems = $totalItems;
    }

    /**
     * Get the slice of items being paginated.
     * @return array
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Get the current page.
     *
     * @return int
     */
    public function getPageNumber()
    {
        return $this->pageNumber;
    }

    /**
     * The number of elements on the page.
     *
     * @return int
     */
    public function getItemsNumber()
    {
        return $this->itemsNumber;
    }

    /**
     * The number of total pages.
     *
     * @return int
     */
    public function getTotalPages()
    {
        return $this->totalPages;
    }

    /**
     * The total amount of elements
     *
     * @return int
     */
    public function getTotalItems()
    {
        return $this->totalItems;
    }

    /**
     * Is there a next page exists
     *
     * @return bool
     */
    public function hasNext()
    {
        return ($this->totalPages > $this->pageNumber + 1);
    }

    /**
     * Determine if the given item exists.
     *
     * @param  mixed $key
     * @return bool
     */
    public function offsetExists($key)
    {
        return array_key_exists($key, $this->content);
    }

    /**
     * Get the item at the given offset.
     *
     * @param  mixed $key
     * @return mixed
     */
    public function offsetGet($key)
    {
        return $this->content[$key];
    }

    /**
     * Set the item at the given offset.
     *
     * @param  mixed $key
     * @param  mixed $value
     * @return void
     */
    public function offsetSet($key, $value)
    {
        if (is_null($key)) {
            $this->content[] = $value;
        } else {
            $this->content[$key] = $value;
        }
    }

    /**
     * Unset the item at the given key.
     *
     * @param  mixed $key
     * @return void
     */
    public function offsetUnset($key)
    {
        unset($this->content[$key]);
    }

    /**
     * Count the number of items in the collection.
     *
     * @return int
     */
    public function count()
    {
        return count($this->content);
    }

    /**
     * Get an iterator for the items.
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->content);
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'content' => $this->getContent(),
            'pageNumber' => $this->getPageNumber(),
            'itemsNumber' => $this->getItemsNumber(),
            'totalPages' => $this->getTotalPages(),
            'totalItems' => $this->getTotalItems(),
            'hasNext' => $this->hasNext(),
        ];
    }

    /**
     * Convert the object into something JSON serializable.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }


    /**
     * Convert the entity to its string representation.
     *
     * @return string
     */
    public function __toString()
    {
        $contentType = 'UNKNOWN';

        if ($this->content) {
            $contentType = get_class($this->content[0]);
        }

        return sprintf('Page %s of %d containing %s instances', $this->getPageNumber(), $this->getTotalPages(), $contentType);
    }
}