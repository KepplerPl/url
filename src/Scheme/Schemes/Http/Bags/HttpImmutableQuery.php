<?php
declare(strict_types=1);

namespace Keppler\Url\Scheme\Schemes\Http\Bags;

use Keppler\Url\Interfaces\Immutable\ImmutableBagInterface;
use Keppler\Url\Scheme\Schemes\AbstractImmutable;
use Keppler\Url\Traits\Accessor;

/**
 * Class HttpImmutableQuery
 * @package Keppler\Url\Schemes\Http\Bags
 */
class HttpImmutableQuery extends AbstractImmutable implements ImmutableBagInterface
{
    use Accessor;

    /**
     * query = *( pchar / "/" / "?" )
     *
     * @var array
     */
    private $query = [];

    /**
     * @var string
     */
    private $raw = '';

    /**
     * This should be the ONLY entry point and it should accept ONLY the raw string
     *
     * HttpImmutableQuery constructor.
     *
     * @param string $raw
     */
    public function __construct(string $raw = '')
    {
        // Leave the class with defaults if no valid raw string is provided
        if ('' !== trim($raw)) {
            $this->raw = $raw;

            $result = [];
            parse_str($raw, $result);
            $this->buildFromParsed($result);
        }
    }

///////////////////////////
/// PRIVATE FUNCTIONS  ///
/////////////////////////

    /**
     * @param $result
     */
    private function buildFromParsed($result)
    {
        $this->query = $result;
    }

//////////////////////////
/// GETTER FUNCTIONS  ///
////////////////////////

    /**
     * @param $key
     * @return mixed
     * @throws \Keppler\Url\Exceptions\ComponentNotFoundException
     */
    public function get($key)
    {
        return $this->getKeyIn($this->query, $key);
    }

    /**
     * @param $key
     * @return bool
     */
    public function has($key): bool
    {
        return $this->hasKeyIn($this->query, $key);
    }

    /**
     * @return array|null
     */
    public function first(): ?array
    {
        return $this->firstInQuery($this->query);
    }

    /**
     * @return array|null
     */
    public function last(): ?array
    {
        return $this->lastInQuery($this->query);
    }

    /**
     * @return \Generator
     */
    public function walkRecursive(): \Generator
    {
        foreach (new \RecursiveIteratorIterator(new \RecursiveArrayIterator($this->query)) as $key => $value) {
            yield $key => $value;
        }
    }


/////////////////////////////////
/// INTERFACE IMPLEMENTATION  ///
////////////////////////////////

    /**
     * Returns all the components of the query
     *
     * @return array
     */
    public function all(): array
    {
        return $this->query;
    }

    /**
     * Return the raw unaltered query
     *
     * @return string
     */
    public function raw(): string
    {
        return $this->raw;
    }
}