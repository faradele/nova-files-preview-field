<?php

namespace Faradele\Files;

use Faradele\Files\FieldServiceProvider;
use Illuminate\Support\Facades\Storage;
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
    public function __construct($name, $attribute = null, $disk = null, $storageCallback = null)
    {
        parent::__construct($name, $attribute, $disk, $storageCallback);

        // * ensure there is only a single '/' at the end of the path prefix
        // ! Calling Storage::disk('xxx')->url() with '' or '/' now leads to an error
        // ! so we have to scrap this pathPrefix stuff for now.
        // ! The component will still work as long as we are passing the 'path_url' in the
        // ! array of image data we provide to the component.
        // $pathPrefix = trim(Storage::disk($this->disk)->url('/'), '/').'/';


        $this->disk($disk ?? config('filesystems.default'))
            ->acceptedTypes('image/*')
            ->deletable(false)
            ->prunable(true)
            ->withMeta([
                'vapor' => false,
                'acceptedTypes' => 'image/*',
                'pathPrefix' => $pathPrefix ?? '/',
                'blankImageURL' => FieldServiceProvider::$blankImageURL ?? '/vendor/files/images/blank.jpeg',
            ]);
    }

    public function withLogViewHistory(bool $value = true): self
    {
        return $this->withMeta([
            'logViewHistory' => $value && FieldServiceProvider::$logImageViewHistoryCallback !== null,
        ]);
    }

    public function disableLightboxOnResourcePreview(bool $isResourcePreviewModal = false): self
    {
        return $this->withMeta([
            'isResourcePreviewModal' => $isResourcePreviewModal,
            'disablePreviewModal' => false,
        ]);
    }

    public function disablePreviewModal(bool $value = true): self
    {
        return $this->withMeta([
            'isResourcePreviewModal' => $value,
            'disablePreviewModal' => $value,
        ]);
    }

    public function maskUnopenedImages(bool $value = true): self
    {
        return $this->withMeta([
            'maskUnopenedImages' => $value,
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
}
