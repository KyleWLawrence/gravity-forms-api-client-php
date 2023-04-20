<?php

namespace KyleWLawrence\GForms\Api\Resources\Core;

use KyleWLawrence\GForms\API\Exceptions\MissingParametersException;
use KyleWLawrence\GForms\Api\Exceptions\ResponseException;
use KyleWLawrence\GForms\Api\Resources\ResourceAbstract;
use KyleWLawrence\GForms\Api\Traits\Resource\Defaults;
use KyleWLawrence\GForms\Api\Traits\Utility\InstantiatorTrait;

/**
 * The Validation class exposes key methods for reading, updating and deleting a validation
 *
 * @method Validation validation()
 */
class Validation extends ResourceAbstract
{
    use InstantiatorTrait;
    use Defaults {
        create as traitCreate;
        update as traitUpdate;
    }

    /**
     *  Mandatory create validation keys
     */
    public static $form_params = [
        'fields',
        'title',
    ];

    /**
     * {@inheritdoc}
     */
    public static function getValidSubResources()
    {
        return [
            'entries' => Entries::class,
            'submissions' => Submissions::class,
        ];
    }

    /**
     * Declares routes to be used by this resource.
     */
    protected function setUpRoutes()
    {
        parent::setUpRoutes();

        $this->setRoutes([
            'getAll' => 'forms',
            'get' => 'forms/{id}',
            'create' => 'forms',
            'update' => 'forms/{id}',
            'delete' => 'forms/{id}',
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
        if (! $this->hasKeys($params, self::$form_params)) {
            throw new MissingParametersException(__METHOD__, self::$form_params);
        }

        return $this->traitCreate($params);
    }

    /**
     * Update a form
     *
     * @param  array  $params
     * @return \stdClass | null
     *
     * @throws ResponseException
     * @throws \Exception
     * @throws \GForms\Api\Exceptions\AuthException
     * @throws \GForms\Api\Exceptions\ApiResponseException
     */
    public function update(string|int $id, array $params)
    {
        if (! $this->hasKeys($params, self::$form_params)) {
            throw new MissingParametersException(__METHOD__, self::$form_params);
        }

        return $this->traitUpdate($id, $params);
    }
}
