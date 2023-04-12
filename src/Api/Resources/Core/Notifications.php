<?php

namespace KyleWLawrence\GForms\Api\Resources\Core;

use KyleWLawrence\GForms\Api\Exceptions\ResponseException;
use KyleWLawrence\GForms\Api\Resources\ResourceAbstract;
use KyleWLawrence\GForms\Api\Traits\Resource\Create;
use KyleWLawrence\GForms\Api\Traits\Utility\InstantiatorTrait;

/**
 * The Notifications class exposes key methods for reading, updating and deleting an entry
 *
 * @method Notifications notifications()
 */
class Notifications extends ResourceAbstract
{
    use InstantiatorTrait;
    use Create {
        create as traitCreate;
    }

    /**
     * {@inheritdoc}
     */
    public static function getValidSubResources()
    {
        return [
        ];
    }

    /**
     * {@inherticdoc}
     */
    public function getAdditionalRouteParams(): array
    {
        $entry_id = $this->getLatestChainedParameter([get_class()]);
        $entryParam = ['entry_id' => reset($entry_id)];

        return array_merge($entryParam, $this->additionalRouteParams);
    }

    /**
     * Declares routes to be used by this resource.
     */
    protected function setUpRoutes()
    {
        parent::setUpRoutes();

        $this->setRoutes([
            'send' => 'entries/{entry_id}/notifications',
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
    public function send(array $params)
    {
        return $this->traitCreate($params);
    }
}
