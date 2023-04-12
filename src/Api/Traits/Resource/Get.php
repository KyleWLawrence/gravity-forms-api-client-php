<?php

namespace KyleWLawrence\GForms\Api\Traits\Resource;

use KyleWLawrence\GForms\Api\Exceptions\MissingParametersException;
use KyleWLawrence\GForms\Api\Exceptions\RouteException;

trait Get
{
    /**
     * Get a specific ticket by id or series of ids
     *
     * @param    $id
     * @param  array  $queryParams
     * @param  string  $routeKey
     * @return null|\stdClass
     *
     * @throws MissingParametersException
     */
    public function get($id = null, array $queryParams = [], $routeKey = __FUNCTION__)
    {
        if (empty($id)) {
            $id = $this->getChainedParameter(get_class($this));
        }

        if (empty($id)) {
            throw new MissingParametersException(__METHOD__, ['id']);
        }

        try {
            $route = $this->getRoute($routeKey, ['id' => $id]);
        } catch (RouteException $e) {
            if (! isset($this->resourceName)) {
                $this->resourceName = $this->getResourceNameFromClass();
            }

            $this->setRoute(__FUNCTION__, $this->resourceName.'/{id}');
            $route = $this->resourceName.'/'.$id.'';
        }

        return $this->client->get(
            $route,
            $queryParams
        );
    }
}
