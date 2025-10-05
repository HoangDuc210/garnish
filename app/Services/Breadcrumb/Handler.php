<?php

namespace App\Services\Breadcrumb;

class Handler
{
    /**
     * @var array|\Illuminate\Support\Collection
     */
    protected $breadcrumbs = [];

    /**
     *
     */
    public function __construct()
    {
        $this->breadcrumbs = collect([]);
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function pushGenealogy(array $data)
    {
        foreach ($data as $crumb) {
            $this->push($crumb['name'], $crumb['link'] ?? null);
        }

        return $this;
    }

    /**
     * Push category
     *
     * @param string $name
     * @param string $link
     *
     * @return $this
     */
    public function push($name, $link = null)
    {
        $this->breadcrumbs->push(new Item($name, $link));

        return $this;
    }

    /**
     * @return array|\Illuminate\Support\Collection
     */
    public function render()
    {
        return $this->breadcrumbs;
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return !($this->breadcrumbs->count());
    }

    /**
     * @return false|string
     */
    public function jsonLdListItem()
    {
        $startPosition = 1;
        $result        = [];
        $result[]      = $this->buildJsonLdItem(trans('app.home page'), url(''), $startPosition);

        foreach ($this->breadcrumbs as $breadcrumb) {
            $startPosition++;
            $result[] = $this->buildJsonLdItem($breadcrumb['name'], $breadcrumb['link'] ?? url(''), $startPosition);
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @param $name
     * @param $url
     * @param $position
     *
     * @return array
     */
    private function buildJsonLdItem($name, $url, $position)
    {
        return [
            '@type'    => 'ListItem',
            'position' => $position,
            'name'     => $name,
            'item'     => $url,
        ];
    }
}
