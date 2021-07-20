<?php

namespace Faradele\Files;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Http\Requests\NovaRequest;

class FilesVapor extends File
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
            ->rules([
                'nullable',
                'attachments' => ['required', 'array'],
                'attachments.*' => ['json'],
                'attachments.*.ext' => ['required', 'string'],
                'attachments.*.key' => ['required', 'string'],
                'attachments.*.uuid' => ['required', 'uuid'],
            ])
            ->store(function (Request $request, $model, $attribute, $requestAttribute) {
                // TODO: only needed if we are using this to store uploaded files from actual
                // TODO: nova form screens, not from action modals
                // We are getting an array of json objects. Each object contains the info we need to get
                // to the temporary uploaded file on the cloud storage bucket.
                // so we will decode the array and move those temporary files to permanent storage
                $tmpPaths = json_decode($request->get($requestAttribute));
                if (! empty($tmpPaths)) {
                    foreach ($tmpPaths as $tmpPath) {
                        $finalPath = implode(DIRECTORY_SEPARATOR, [
                            $this->getStorageDir(),
                            Str::of(Str::random(40))->upper().'.'.$tmpPath['ext'],
                        ]);
                        Storage::disk($this->getStorageDisk())->copy($tmpPath['key'], $finalPath);

                        // TODO: we can accept a callback here and call it with the model and final path
                        // $callback($model, $finalPath)
                    }
                }
            })
            ->prunable(true)
            ->withMeta([
                'vapor' => true,
                'acceptedTypes' => 'image/*',
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
            // we are getting an json array of items containing info about each of
            // the uploaded files details on the cloud bucket
            // so decode the json
            $model->{$this->attribute} = $decodedArray = json_decode($request->get($this->attribute), true);
            request()->merge([$this->attribute => $decodedArray]);
        }
    }
}
