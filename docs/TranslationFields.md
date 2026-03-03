# Translation Form Fields

Verdant provides four translation-aware form components that display language tabs above the input, allowing users to switch between language variants of a single field.

For a working example, see [demo/resources/views/demo/forms.blade.php](demo/resources/views/demo/forms.blade.php) (Translation Fields section at `/demo/forms`).

## Components

- **`v-form.translation-input`** – Text input with language tabs
- **`v-form.translation-textarea`** – Textarea with language tabs
- **`v-form.translation-richtext`** – HTML richtext editor with language tabs
- **`v-form.translation-image`** – Image upload (with optional crop) with language tabs
- **`v-form.translation-tabs`** – Shared language tabs (used internally; requires parent `activeTab` Alpine scope)

## Data Structure

Pass a `label` and a `languages` array. Each language object has:

| Key     | Type         | Description                                              |
|---------|--------------|----------------------------------------------------------|
| `name`  | string       | Form field name (e.g. `report_introduction_en` or `name[en]` for Spatie) |
| `label` | string       | Tab label, often with flag emoji (e.g. `🇬🇧 English`)    |
| `value` | string\|null | Current value (for images: URL or path to existing image) |

## Tab Styling

- **Active tab**: Blue background, bold text
- **Tab with value (inactive)**: White/gray background, normal text
- **Tab without value (empty)**: Gray background, muted text, grayscale flag

## Usage Examples

### Translation Input

```blade
<x-v-form.translation-input
    label="Report Title"
    :languages="[
        ['name' => 'report_title_en', 'label' => '🇬🇧 English', 'value' => 'Annual Report 2024'],
        ['name' => 'report_title_nl', 'label' => '🇳🇱 Dutch', 'value' => null],
        ['name' => 'report_title_es', 'label' => '🇪🇸 Spanish', 'value' => 'Informe Anual 2024'],
    ]"
/>
```

### Translation Textarea

```blade
<x-v-form.translation-textarea
    label="Report Summary"
    :languages="[
        ['name' => 'report_summary_en', 'label' => '🇬🇧 English', 'value' => $report->summary_en ?? null],
        ['name' => 'report_summary_nl', 'label' => '🇳🇱 Dutch', 'value' => $report->summary_nl ?? null],
    ]"
/>
```

### Translation Richtext (HTML markup field)

```blade
<x-v-form.translation-richtext
    label="Report Introduction"
    :languages="[
        ['name' => 'report_introduction_en', 'label' => '🇬🇧 English', 'value' => $report->introduction_en ?? null],
        ['name' => 'report_introduction_nl', 'label' => '🇳🇱 Dutch', 'value' => $report->introduction_nl ?? null],
    ]"
/>
```

### Translation Image

```blade
<x-v-form.translation-image
    label="Certificate Logo"
    :languages="[
        ['name' => 'logo[en]', 'label' => '🇬🇧 English', 'value' => $certificate?->getLogoUrl('en')],
        ['name' => 'logo[nl]', 'label' => '🇳🇱 Dutch', 'value' => $certificate?->getLogoUrl('nl')],
    ]"
    disable-crop
/>
```

Image component props (pass-through to image-cropper): `disableCrop`, `aspectRatio`, `uploadUrl`, `minWidth`, `minHeight`, `maxWidth`, `maxHeight`, `maxScale`.

## Spatie Laravel Translatable

When using [spatie/laravel-translatable](https://github.com/spatie/laravel-translatable), use a helper to build the `languages` array. Add to `app/helpers.php` (and register in `composer.json` autoload `files`):

```php
if (! function_exists('translatable_languages')) {
    function translatable_languages(string $attribute, ?object $model, $languages): array
    {
        return $languages->map(fn ($lang) => [
            'name'  => "{$attribute}[{$lang->code}]",
            'label' => $lang->enum()->flag().' '.$lang->enum()->label(),
            'value' => old("{$attribute}.{$lang->code}", $model?->getTranslation($attribute, $lang->code)),
        ])->values()->toArray();
    }
}
```

Usage with Spatie:

```blade
<x-v-form.translation-input
    label="Name"
    :languages="translatable_languages('name', $page, $languages)"
    required
/>

<x-v-form.translation-richtext
    label="Description"
    :languages="translatable_languages('description', $page, $languages)"
    required
/>

<x-v-form.translation-image
    label="Logo"
    :languages="translatable_languages('logo', $certificate, $languages)"
    disable-crop
/>
```

For images, the helper's `value` is the model's translation (stored path). If your model returns a full URL (e.g. via `getLogoUrl`), use a custom mapping or extend the helper.

Ensure your `Language` model has `code` and an `enum()` method returning an enum with `flag()` and `label()`.

## Props

| Prop        | Type    | Default  | Component              | Description                         |
|-------------|---------|----------|------------------------|-------------------------------------|
| `label`     | string  | `''`     | all                    | Main label above the tabs           |
| `languages` | array   | `[]`     | all                    | Array of language objects           |
| `type`      | string  | `'text'` | translation-input      | HTML input type (text, email, etc.) |
| `required`  | boolean | `false`  | all                    | Mark first language as required     |
| `disableCrop` | boolean | `false` | translation-image      | Skip crop step; use image as-is     |
| `aspectRatio` | string | `null`  | translation-image      | Crop ratio (e.g. `1:1`, `16:9`)     |
| `uploadUrl` | string  | `null`  | translation-image      | Server URL for image upload         |
| `minWidth`, `minHeight` | int | `0` | translation-image | Min dimensions (px)              |
| `maxWidth`, `maxHeight` | int | `5000` | translation-image | Max dimensions (px)             |
| `maxScale`  | int     | `512`   | translation-image      | Max output size when scaling        |
