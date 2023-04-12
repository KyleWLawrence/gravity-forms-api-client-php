<?php

namespace KyleWLawrence\GForms\Api\Resources\Core;

use KyleWLawrence\GForms\Api\Resources\ResourceAbstract;
use KyleWLawrence\GForms\Api\Traits\Resource\Create;
use KyleWLawrence\GForms\Api\Traits\Utility\InstantiatorTrait;

/**
 * The Submissions class exposes key methods for reading, updating and deleting a submission
 *
 * @method Submissions submissions()
 */
class Submissions extends ResourceAbstract
{
    use InstantiatorTrait;
    use Create;

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
            'create' => 'forms/{form_id}/submissions',
        ]);
    }
}
