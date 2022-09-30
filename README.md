### Nova Image Preview Field

This simple package adds a field to nova for showing a collection of uploaded images both on the index (table view) and show pages.

On the index page, the images are represented with tiny thumbnails that can be clicked to open a lightbox while the same behaviour applies on the show page except the thumbnails are bigger.

### NOTE
This is untested and probably not worth using in any serious project. This was made to scratch a very specific itch.

### Installation
 Add the snippets below to the `require` and `repositories` sections of your `composer.json` file.
```json
"require": {
    ...
    "faradele/nova-files-preview-field": "dev-nova-4"
}

"repositories": [
    ...
    {
        "type": "vcs",
        "url": "git@github.com:faradele/nova-files-preview-field.git"
    },
]
```
Then run `composer update`

### Sample Usage - With external image urls
In the nova resource class add the definition below into your `fields` or `fieldsForIndex` method.

To display the attachments images for a record, generate an array that contains one item for each image to display.
- `id` - This could easily be the images array index. It just needs to be unique per item in the array
- `attachable_id` - This must be the same for all images that are related to the same parent. For instance, if the images to be shown are related to a trade with an id 17, then 17 must be the `attachable_id` value for every item in the array.
- `path_url` - The full url to the image

```php
use Faradele\Files\Files;
Files::make(
    'Attachments',
    fn () => [[
        'id' => 0,
        'attachable_id' => 17,
        'path_url' => 'https://www.motorbiscuit.com/wp-content/uploads/2021/02/Tesla-Roadster.jpg',
    ], [
        'id' => 1,
        'attachable_id' => 17,
        'path_url' => 'http://tonsoffacts.com/wp-content/uploads/2021/02/tesla-model-s-raven-2.jpg'
    ]]
)->showOnIndex()
```
