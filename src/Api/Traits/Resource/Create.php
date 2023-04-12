<?php

namespace KyleWLawrence\GForms\Api\Traits\Resource;

use KyleWLawrence\GForms\Api\Exceptions\RouteException;

trait Create
{
    /**
     * Create a new resource
     *
     * @param  array  $params
     * @param  string  $routeKey
     * @return null|\stdClass
     */
    public function create(array $params, $routeKey = __FUNCTION__)
    {
        try {
            $route = $this->getRoute($routeKey, $params);
        } catch (RouteException $e) {
            if (! isset($this->resourceName)) {
                $this->resourceName = $this->getResourceNameFromClass();
            }

            $route = $this->resourceName.'';
            $this->setRoute(__FUNCTION__, $route);
        }

        return $this->client->post(
            $route,
            $params
        );
    }
}
