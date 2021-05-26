<?php

namespace Faradele\Files;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Http\Requests\NovaRequest;

class Files extends File
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'files';

    /**
     * Other options.
     *
     * @var array
     */
    public $options = [];

    /**
     * Create a new field.
     *
     * @param  string  $name
     * @param  string  $attribute
     * @param  string|null  $disk
     * @param  callable|null  $storageCallback
     * @return void
     */
    public function __construct($name, $attribute = null, $disk = 'public', $storageCallback = null)
    {
        parent::__construct($name, $attribute, $disk, $storageCallback);

        $this->disk(config('filesystems.default'))
            ->acceptedTypes('image/*')
            ->deletable(false)
            ->prunable(true)
            ->withMeta([
                'acceptedTypes' => 'image/*',
                'pathPrefix' => Str::replaceLast('//', '/', Storage::disk($this->disk)->url('/')),
            ]);
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
                    ->create([
                        'path' => $file->store($this->getStorageDir(), $this->getStorageDisk()),
                        'type' => $this->options['type'] ?? null,
                    ]);
            }
        }
    }
}
