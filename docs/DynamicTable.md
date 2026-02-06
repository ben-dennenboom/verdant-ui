## Dynamic Table – Filter modal

The Dynamic Table can show a **Filter** button (before the Columns dropdown) that opens a modal. Filter controls (text, number, date, checkbox, select) are built from a definition array, similar to column headers.

### Providing filters

- **Via data provider**: Implement `filterColumns(): ?array` on your `DynamicTableDataProvider`. Return `null` or an empty array to hide the filter UI. Existing implementors must add this method (e.g. `return null;` if they do not use filters).
- **Via array config**: When passing an array as table data, include a `filters` key with the filter definitions.

### Filter definition

Each filter is an array with:

| Key | Required | Description |
|-----|----------|-------------|
| `key` | yes | Request parameter name (e.g. `status`, `role`) |
| `label` | yes | Label shown next to the control |
| `type` | yes | `text`, `number`, `date`, `checkbox`, `select` |
| `options` | for select | Array of `['value' => x, 'label' => y]` or `value => label` |
| `placeholder` | no | Placeholder for text/number |
| `default` | no | Default value when no request value |

### Example

```php
'filters' => [
    [
        'key'   => 'status',
        'label' => 'Status',
        'type'  => 'select',
        'options' => [
            ['value' => '', 'label' => 'Any'],
            ['value' => 'active', 'label' => 'Active'],
            ['value' => 'archived', 'label' => 'Archived'],
        ],
    ],
    [
        'key'   => 'verified',
        'label' => 'Verified only',
        'type'  => 'checkbox',
    ],
    [
        'key'         => 'q',
        'label'       => 'Keyword',
        'type'        => 'text',
        'placeholder' => 'Search…',
    ],
],
```

The form submits via GET to the current URL. Your controller or data provider should read the same parameter names from the request and apply them when building the table data.

### Usage example

**Controller** – build table data, define filters, apply request params to the query:

```php
use Dennenboom\VerdantUI\Tables\DynamicTableData;
use Dennenboom\VerdantUI\Tables\DynamicTableSort;

class UserTableController extends Controller
{
    public function index()
    {
        $query = User::query();

        // Apply filter params from the request (same keys as filter definitions)
        if ($status = request('status')) {
            $query->where('status', $status);
        }
        if (request('verified')) {
            $query->whereNotNull('email_verified_at');
        }
        if ($q = request('q')) {
            $query->where(function ($qry) use ($q) {
                $qry->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            });
        }

        $users = $query->paginate(15);

        $table = DynamicTableData::fromCollection($users, [
            'name'  => ['label' => 'Name'],
            'email' => ['label' => 'Email'],
            'status' => ['label' => 'Status'],
        ], fn ($user) => [/* actions */])
            ->withColumnVisibility('users-table')
            ->withFilters([
                [
                    'key'      => 'status',
                    'label'    => 'Status',
                    'type'     => 'select',
                    'multiple' => true,
                    'options'  => [
                        ['value' => '', 'label' => 'Any'],
                        ['value' => 'active', 'label' => 'Active'],
                        ['value' => 'inactive', 'label' => 'Inactive'],
                    ],
                ],
                [
                    'key'   => 'verified',
                    'label' => 'Verified only',
                    'type'  => 'checkbox',
                ],
                [
                    'key'         => 'q',
                    'label'       => 'Keyword',
                    'type'        => 'text',
                    'placeholder' => 'Search name or email…',
                ],
            ])
            ->withSorting(DynamicTableSort::fromRequest());

        return view('users.index', ['table' => $table]);
    }
}
```

**Blade** – pass the provider as `data`:

```blade
<x-v-dynamic-table.container :data="$table" emptyText="No users found." />
```

**Array-based alternative** (no provider): pass `headers`, `rows`, and `filters` in the view or controller:

```php
return view('users.index', [
    'tableData' => [
        'headers' => [['key' => 'name', 'label' => 'Name'], ['key' => 'email', 'label' => 'Email']],
        'rows'    => $rows,
        'filters' => [
            ['key' => 'status', 'label' => 'Status', 'type' => 'select', 'options' => $statusOptions],
            ['key' => 'q', 'label' => 'Keyword', 'type' => 'text', 'placeholder' => 'Search…'],
        ],
    ],
]);
```

```blade
<x-v-dynamic-table.container :data="$tableData" />
```