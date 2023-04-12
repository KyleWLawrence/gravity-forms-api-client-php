<?php

namespace KyleWLawrence\GForms\Api\Resources\Core;

use KyleWLawrence\GForms\Api\Resources\ResourceAbstract;
use KyleWLawrence\GForms\Api\Traits\Resource\Create;
use KyleWLawrence\GForms\Api\Traits\Resource\Get;
use KyleWLawrence\GForms\Api\Traits\Utility\InstantiatorTrait;

/**
 * The FormEntries class exposes key methods for reading, updating and deleting an entry
 *
 * @method FormEntries entries()
 */
class FormEntries extends ResourceAbstract
{
    use InstantiatorTrait;
    use Create;
    use Get;

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
        $form_id = $this->getLatestChainedParameter([get_class()]);
        $formParam = ['form_id' => reset($form_id)];

        return array_merge($formParam, $this->additionalRouteParams);
    }

    /**
     * Declares routes to be used by this resource.
     */
    protected function setUpRoutes()
    {
        parent::setUpRoutes();

        $this->setRoutes([
            'get' => 'forms/{form_id}/entries',
            'create' => 'forms/{form_id}/entries',
        ]);
    }
}
