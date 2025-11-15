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
    protected array $content;

    /**
     * The number of the current page
     * @var int
     */
    protected int $pageNumber;

    /**
     * The number of elements on the page
     * @var int
     */
    protected int $itemsNumber;

    /**
     * The number of total pages
     * @var int
     */
    protected int $totalPages;

    /**
     * The total amount of elements
     * @var int
     */
    protected int $totalItems;

    /**
     * Page constructor.
     *
     * @param array $content
     * @param int $pageNumber
     * @param int $itemsNumber
     * @param int $totalPages
     * @param int $totalItems
     */
    public function __construct(array $content, int $pageNumber, int $itemsNumber, int $totalPages, int $totalItems)
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
    public function getContent(): array
    {
        return $this->content;
    }

    /**
     * Get the current page.
     *
     * @return int
     */
    public function getPageNumber(): int
    {
        return $this->pageNumber;
    }

    /**
     * The number of elements on the page.
     *
     * @return int
     */
    public function getItemsNumber(): int
    {
        return $this->itemsNumber;
    }

    /**
     * The number of total pages.
     *
     * @return int
     */
    public function getTotalPages(): int
    {
        return $this->totalPages;
    }

    /**
     * The total amount of elements
     *
     * @return int
     */
    public function getTotalItems(): int
    {
        return $this->totalItems;
    }

    /**
     * Is there a next page exists
     *
     * @return bool
     */
    public function hasNext(): bool
    {
        return ($this->totalPages > $this->pageNumber + 1);
    }

    /**
     * Determine if the given item exists.
     *
     * @param  mixed $offset
     * @return bool
     */
    public function offsetExists(mixed $offset): bool
    {
        return array_key_exists($offset, $this->content);
    }

    /**
     * Get the item at the given offset.
     *
     * @param  mixed $offset
     * @return mixed
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->content[$offset];
    }

    /**
     * Set the item at the given offset.
     *
     * @param  mixed $offset
     * @param  mixed $value
     * @return void
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (is_null($offset)) {
            $this->content[] = $value;
        } else {
            $this->content[$offset] = $value;
        }
    }

    /**
     * Unset the item at the given key.
     *
     * @param  mixed $offset
     * @return void
     */
    public function offsetUnset(mixed $offset): void
    {
        unset($this->content[$offset]);
    }

    /**
     * Count the number of items in the collection.
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->content);
    }

    /**
     * Get an iterator for the items.
     *
     * @return ArrayIterator
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->content);
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray(): array
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
    public function jsonSerialize(): array
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
