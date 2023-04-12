<?php

namespace KyleWLawrence\GForms\Api\Resources\Core;

use KyleWLawrence\GForms\API\Exceptions\MissingParametersException;
use KyleWLawrence\GForms\Api\Exceptions\ResponseException;
use KyleWLawrence\GForms\Api\Resources\ResourceAbstract;
use KyleWLawrence\GForms\Api\Traits\Resource\Defaults;
use KyleWLawrence\GForms\Api\Traits\Utility\InstantiatorTrait;

/**
 * The Entries class exposes key methods for reading, updating and deleting an entry
 *
 * @method Entries entries()
 */
class Entries extends ResourceAbstract
{
    use InstantiatorTrait;
    use Defaults {
        create as traitCreate;
    }

    /**
     *  Mandatory create entries keys
     */
    public static $entry_params = [
        'form_id',
    ];

    /**
     * {@inheritdoc}
     */
    public static function getValidSubResources()
    {
        return [
            'notifications' => Notifications::class,
        ];
    }

    /**
     * Declares routes to be used by this resource.
     */
    protected function setUpRoutes()
    {
        parent::setUpRoutes();

        $this->setRoutes([
            'getAll' => 'entries',
            'get' => 'entries/{id}',
            'create' => 'entries',
            'update' => 'entries/{id}',
            'delete' => 'entries/{id}',
        ]);
    }

    /**
     * Create a form
     *
     * @param  array  $params
     * @return \stdClass | null
     *
     * @throws ResponseException
     * @throws \Exception
     * @throws \GForms\Api\Exceptions\AuthException
     * @throws \GForms\Api\Exceptions\ApiResponseException
     */
    public function create(array $params)
    {
        if (! $this->hasKeys($params, self::$entry_params)) {
            throw new MissingParametersException(__METHOD__, self::$entry_params);
        }

        return $this->traitCreate($params);
    }
}
