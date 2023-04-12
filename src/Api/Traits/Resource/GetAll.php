<?php

namespace KyleWLawrence\GForms\Api\Traits\Resource;

use KyleWLawrence\GForms\Api\Exceptions\RouteException;

trait GetAll
{
    /**
     * List all of this resource
     *
     * @param  array  $params
     * @param  string  $routeKey
     * @return \stdClass | null
     *
     * @throws \GForms\Api\Exceptions\AuthException
     * @throws \GForms\Api\Exceptions\ApiResponseException
     */
    public function getAll(array $params = [], $routeKey = __FUNCTION__)
    {
        $params = array_merge(['per_page' => 100], $params);

        try {
            $route = $this->getRoute($routeKey, $params);
        } catch (RouteException $e) {
            if (! isset($this->resourceName)) {
                $this->resourceName = $this->getResourceNameFromClass();
            }

            $route = $this->resourceName;
            $this->setRoute(__FUNCTION__, $route);
        }

        return $this->client->get(
            $route,
            $params
        );
    }
}
