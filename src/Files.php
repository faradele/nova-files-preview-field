<?php

namespace Faradele\Files;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Fields\Storable;
use Laravel\Nova\Http\Requests\NovaRequest;

class Files extends Field
{
    use Storable;

    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'files';

    /**
     * The meta data for the element.
     *
     * @var array
     */
    public $meta = [
        "pathPrefix" => "/storage/",
        "acceptedTypes" => "image/*",
    ];

    /**
     * Other options
     *
     * @var array
     */
    public $options = [];

    /**
     * Set other options
     *
     * @param array $options options
     *
     * @return $this
     */
    public function options(array $options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Hydrate the given attribute on the model based on the incoming request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  object  $model
     * @return void
     */
    public function fillForAction(NovaRequest $request, $model)
    {
        if (isset($request[$this->attribute])) {
            // we can't store the uploaded array of files on the model
            // for our action to use because UploadedFile class complains
            // that serialisation is not allowed
            // we will have to use plain old global request() helper in
            // our action handle method
            $model->{$this->attribute} = [];
        }
    }

    /**
     * Hydrate the given attribute on the model based on the incoming request.
     *
     * @param NovaRequest $request          incoming request
     * @param string      $requestAttribute the attribute name in the request
     * @param object      $model            the model
     * @param string      $attribute        the model attribute itself
     *
     * @return void
     */
    protected function fillAttributeFromRequest(
        NovaRequest $request,
        $requestAttribute,
        $model,
        $attribute
    ) {
        if ($request->exists($requestAttribute)) {
            // store the files in the destination folder and move on.
            $files = $request->file($requestAttribute);
            foreach ($files as $file) {
                $model->attachments()
                    ->create(
                        [
                            'path' => $file->store(
                                $this->getStorageDir(), $this->getStorageDisk()
                            ),
                            'type' => $this->options['type'] ?? null,
                        ]
                    );
            }
        }
    }
}
