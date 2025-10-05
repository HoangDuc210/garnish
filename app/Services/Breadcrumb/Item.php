<?php

namespace App\Services\Breadcrumb;

class Item
{
    /**
     * @var array[]
     */
    protected $attributes;

    /**
     * @param $name
     * @param $link
     */
    public function __construct($name, $link)
    {
        $this->setAttribute('name', $name);
        $this->setAttribute('link', $link);
    }

    /**
     * @param $key
     * @param $value
     */
    protected function setAttribute($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    /**
     * Dynamically retrieve attributes
     *
     * @param string $key
     *
     * @return mixed
     */
    public function __get($key)
    {
        return $this->attributes[$key] ?? null;
    }
}
