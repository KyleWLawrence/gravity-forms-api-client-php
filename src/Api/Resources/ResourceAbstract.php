<?php

namespace KyleWLawrence\GForms\Api\Resources;

use Doctrine\Inflector\CachedWordInflector;
use Doctrine\Inflector\Inflector;
use Doctrine\Inflector\Rules\English;
use Doctrine\Inflector\RulesetInflector;
use KyleWLawrence\GForms\Api\Exceptions\RouteException;
use KyleWLawrence\GForms\Api\HttpClient;
use KyleWLawrence\GForms\Api\Traits\Utility\ChainedParametersTrait;

/**
 * Abstract class for all endpoints
 */
abstract class ResourceAbstract
{
    use ChainedParametersTrait;

    /**
     * @var string
     */
    protected $resourceName;

    /**
     * @var string
     */
    protected $objectName;

    /**
     * @var string
     */
    protected $objectNamePlural;

    /**
     * @var \GForms\Api\HttpClient
     */
    protected $client;

    /**
     * @var int
     */
    protected $lastId;

    /**
     * @var array
     */
    protected $routes = [];

    /**
     * @var array
     */
    protected $additionalRouteParams = [];

    /**
     * @var string
     */
    protected $apiBasePath;

    /**
     * @param  HttpClient  $client
     */
    public function __construct(HttpClient $client, $apiBasePath = 'wp-json/gf/v2/')
    {
        $this->apiBasePath = $apiBasePath;
        $this->client = $client;
        $this->client->setApiBasePath($this->apiBasePath);
        $inflector = new Inflector(
            new CachedWordInflector(new RulesetInflector(
                English\Rules::getSingularRuleset()
            )),

            new CachedWordInflector(new RulesetInflector(
                English\Rules::getPluralRuleset()
            ))
        );

        if (! isset($this->resourceName)) {
            $this->resourceName = $this->getResourceNameFromClass();
        }

        if (! isset($this->objectName)) {
            $this->objectName = $inflector->singularize($this->resourceName);
        }

        if (! isset($this->objectNamePlural)) {
            $this->objectNamePlural = $inflector->pluralize($this->resourceName);
        }

        $this->setUpRoutes();
    }

    /**
     * This returns the valid relations of this resource. Definition of what is allowed to chain after this resource.
     * Make sure to add in this method when adding new sub resources.
     * Example:
     *    $client->ticket()->comments();
     *    Where ticket would have a comments as a valid sub resource.
     *    The array would look like:
     *      ['comments' => '\GForms\Api\Resources\TicketComments']
     *
     * @return array
     */
    public static function getValidSubResources()
    {
        return [];
    }

    /**
     * Return the resource name using the name of the class (used for endpoints)
     *
     * @return string
     */
    protected function getResourceNameFromClass()
    {
        $namespacedClassName = get_class($this);
        $resourceName = implode('', array_slice(explode('\\', $namespacedClassName), -1));

        // This converts the resource name from camel case to underscore case.
        // e.g. MyClass => my_class
        $underscored = strtolower(preg_replace('/(?<!^)([A-Z])/', '_$1', $resourceName));

        return strtolower($underscored);
    }

    /**
     * @return string
     */
    public function getResourceName()
    {
        return $this->resourceName;
    }

    /**
     * Sets up the available routes for the resource.
     */
    protected function setUpRoutes()
    {
    }

    /**
     * Saves an id for future methods in the chain
     *
     * @param  int  $id
     * @return $this
     */
    public function setLastId($id)
    {
        $this->lastId = $id;

        return $this;
    }

    /**
     * Saves an id for future methods in the chain
     *
     * @return int
     */
    public function getLastId()
    {
        return $this->lastId;
    }

    /**
     * Check that all parameters have been supplied
     *
     * @param  array  $params
     * @param  array  $mandatory
     * @return bool
     */
    public function hasKeys(array $params, array $mandatory)
    {
        for ($i = 0; $i < count($mandatory); $i++) {
            if (! array_key_exists($mandatory[$i], $params)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check that any parameter has been supplied
     *
     * @param  array  $params
     * @param  array  $mandatory
     * @return bool
     */
    public function hasAnyKey(array $params, array $mandatory)
    {
        for ($i = 0; $i < count($mandatory); $i++) {
            if (array_key_exists($mandatory[$i], $params)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Wrapper for adding multiple routes via setRoute
     *
     * @param  array  $routes
     */
    public function setRoutes(array $routes)
    {
        foreach ($routes as $name => $route) {
            $this->setRoute($name, $route);
        }
    }

    /**
     * Add or override an existing route
     *
     * @param $name
     * @param $route
     */
    public function setRoute($name, $route)
    {
        $this->routes[$name] = $route;
    }

    /**
     * Return all routes for this resource
     *
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * Returns a route and replaces tokenized parts of the string with
     * the passed params
     *
     * @param    $name
     * @param  array  $params
     * @return mixed
     *
     * @throws \Exception
     */
    public function getRoute($name, array $params = [])
    {
        if (! isset($this->routes[$name])) {
            throw new RouteException('Route not found.');
        }

        $route = $this->routes[$name];

        $substitutions = array_merge($params, $this->getAdditionalRouteParams());
        foreach ($substitutions as $name => $value) {
            if (is_scalar($value)) {
                $route = str_replace('{'.$name.'}', $value, $route);
            }
        }

        return $route;
    }

    /**
     * @param  array  $additionalRouteParams
     */
    public function setAdditionalRouteParams($additionalRouteParams)
    {
        $this->additionalRouteParams = $additionalRouteParams;
    }

    /**
     * @return array
     */
    public function getAdditionalRouteParams()
    {
        return $this->additionalRouteParams;
    }

    /**
     * Wrapper for common GET requests
     *
     * @param    $route
     * @param  array  $params
     * @return \stdClass | null
     *
     * @throws ResponseException
     * @throws \Exception
     */
    private function sendGetRequest($route, array $params = [])
    {
        $response = Http::send(
            $this->client,
            $this->getRoute($route, $params),
            ['queryParams' => $params]
        );

        return $response;
    }
}
