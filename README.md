# PopupForm Plugin

The PopupForm plugin for WinterCMS provides a convenient way to handle form submissions within popup modals. This plugin extends the functionality of the FormController behavior to support popup forms.

## Requirements

https://github.com/wintercms/winter/compare/develop...wpjscc-labs:winter:develop

```
php artisan winter:util compile js
```

## Installation

To install this plugin, add it to your WinterCMS project using Composer:

```bash
composer require wpjscc/wn-popupform-plugin -vvv
```

## Usage

### Traits

The `PopupFormTrait` provides methods to handle form submissions and refresh the UI accordingly.

#### Methods

- `create_onSave($context = null)`: Handles the save action for the create form.
- `update_onSave($recordId = null, $context = null)`: Handles the save action for the update form

### Behaviors

The `PopupFormController` behavior extends the base ControllerBehavior to support popup forms.

#### Methods

- `onCreateForm($context = null)`: Renders the create form in a popup.

```php
class Posts extends Controller
{
    use \Wpjscc\PopupForm\Traits\PopupFormTrait;

    public $implement = [
       ...
        \Wpjscc\PopupForm\Behaviors\PopupFormController::class,
    ];
}

```

```html
    <a  onclick="$.popup({ url: '<?=Backend::url('wpjscc/popupform/fieldupdate/create') ?>' ,handler: 'onCreateForm_',extraData: {refresh_relation:'genjins',update_form:1, mode:'user_genjin'}}) ">
        <i class="wn-icon-plus"></i>
    </a>
```

- `onUpdateForm($recordId = null, $context = null)`: Renders the update form in a popup.
```

```html
    <a  onclick="$.popup({ url: '<?=Backend::url('wpjscc/popupform/fieldupdate/update/'.$record->id) ?>' ,handler: 'onUpdateForm_',extraData: {refresh_relation:'xxxx',update_form:1, mode:'xxx'}}) ">
        <i class="wn-icon-edit"></i>
    </a>
```

`config_list.yaml`
```yaml
recordOnClick: "$.popup({ url: '/backend/winter/blog/posts/update/:id' ,handler: 'onUpdateForm_',extraData: {refresh_relation:'xxxx',update_form:1, mode:'xxx'}}) "
```

## License

This plugin is licensed under the MIT License. See the [LICENSE](LICENSE) file for more details.