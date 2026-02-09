## Column definitions

Columns can be defined using the fluent `Column` DTO for better IDE support and discoverability:

```php
use Dennenboom\VerdantUI\Tables\Column;
use Dennenboom\VerdantUI\Tables\DynamicTableData;

$columns = [
    Column::make('last_name', 'Last Name')->default()->sortable(),
    Column::make('first_name', 'First Name')->sortable(),
    Column::make('full_name', 'Full Name')
        ->value(static fn (User $user) => $user->first_name . ' ' . $user->last_name)
        ->sortable('last_name'),
    Column::make('is_active', 'Active')
        ->format(static fn (bool $value) => $value ? 'Yes' : 'No'),
    Column::make('roles', 'Roles')
        ->render(static fn ($roles) => view('users.table.roles', compact('roles'))),
];
```

### Column methods

| Method | Description |
|--------|-------------|
| `Column::make(string $key, string $label)` | Create a column with key and header label |
| `->default()` | Include in default visible columns when column visibility is enabled; use with `withColumnVisibility($key, null)` to derive defaults from columns |
| `->value(callable)` | Computed value: `fn ($model) => mixed` – use when the column is not a model attribute |
| `->format(callable)` | Format value: `fn ($value, $model) => scalar\|array` – can return `['html' => true, 'value' => '...']` for trusted HTML |
| `->render(callable)` | Custom render: `fn ($value, $model) => Htmlable` – fully app-controlled UI (View, HtmlString, etc.) |
| `->sortable(bool\|string\|callable)` | `true` = sort by column key; string = DB column for ORDER BY; callable = in-memory sort value `fn ($model) => mixed` |
| `->sortValue(callable)` | Value used for in-memory sorting when not using DB pagination: `fn ($model) => mixed` |
| `->sortableQuery(callable)` | Complex DB sort (joins, raw): `fn ($query, $direction) => void` – applied via `Column::applySort()` |
| `->visibleWhen(callable)` | Table-level visibility: `fn ($context) => bool` – pass context as 4th arg to `fromCollection($items, $columns, $actions, $context)` |
| `->pinned(bool)` | Column always visible and cannot be hidden in the column picker (default `true` when called) |
| `->width(string)` | Column width hint (e.g. `'8rem'`, `'minmax(100px, 1fr)'`) |
| `->align(string)` | Text alignment: `'left'`, `'center'`, or `'right'` |
| `->tooltip(string)` | Header tooltip (title attribute) |
| `->class(string)` | Optional CSS class for the header cell |

### Default visible columns from Column::default()

When you use column visibility and pass `null` as the second argument, default visible columns are derived from columns marked with `->default()`:

```php
$table = DynamicTableData::fromCollection($users, $columns, $actions)
    ->withColumnVisibility('users-table', null);  // defaults from Column::default()
```

### Sort key resolution and server-side sort

When a column is computed (e.g. `full_name`) but you want to sort by a real DB column (e.g. `last_name`), use `sortable('last_name')`. For **simple** query building use `Column::resolveSortKey()`. For **complex** sorts (joins, raw SQL) use `sortableQuery()` and `Column::applySort()`:

```php
use Dennenboom\VerdantUI\Tables\Column;
use Dennenboom\VerdantUI\Tables\DynamicTableSort;

$sortableKeys = ['name', 'email', 'created_at'];
$sort = DynamicTableSort::fromRequest($sortableKeys);
foreach ($sort->columns as $col) {
    Column::applySort($col['key'], $columns, $query, $col['direction']);
}
```

**Recommended:** Use `Column::applySort()` in your controller so each column's `sort_key` and `sort_query` are respected. Restrict accepted sort keys with `DynamicTableSort::fromRequest($allowedKeys)` to avoid arbitrary request params. `applySort()` runs the column's `sort_query` callback when present; otherwise it calls `orderBy(resolveSortKey(...), $direction)`.

### Export

Reuse the same column definitions (value + format, no render) to build export-ready rows:

```php
$rows = DynamicTableData::exportFromCollection($items, $columns);
// Optional: include a header row (column_key => label) as the first row
$rowsWithHeaders = DynamicTableData::exportFromCollection($items, $columns, true);
```

Columns with only `render` still export using value/format when present; `format` output is used as-is. If `format` returns `['html' => true, 'value' => '...']`, the export helper strips HTML to plain text.

### Conditional column visibility

Pass a context as the 4th argument to `fromCollection()`; columns with `->visibleWhen(callable)` are included only when the callback returns true for that context:

```php
$table = DynamicTableData::fromCollection($users, $columns, $actions, auth()->user());
// Columns with ->visibleWhen(fn ($user) => $user->isAdmin()) are only included for admin users
```

### Array-based column definitions (backward compatible)

The legacy format still works:

```php
DynamicTableData::fromCollection($users, [
    'name'  => ['label' => 'Name', 'sortable' => true],
    'email' => 'Email',
], $actions);
```

---

## Usage examples

The following patterns show how to build and render the dynamic table in common scenarios. For a full working example combining Column DTOs, actions (route + render), search, filters, column visibility, and sort, see [demo/resources/views/demo/tables.blade.php](demo/resources/views/demo/tables.blade.php).

### 1. Minimal (array only)

No paginator, sort, or filters—just headers and rows:

```php
$headers = [
    ['key' => 'name', 'label' => 'Name'],
    ['key' => 'email', 'label' => 'Email'],
];
$rows = [
    ['name' => 'John', 'email' => 'john@example.com'],
    ['name' => 'Jane', 'email' => 'jane@example.com'],
];
$tableData = DynamicTableData::from($headers, $rows);
```

Blade—pass `data` or use `headers`/`rows` as fallback:

```blade
<x-v-dynamic-table.container :data="$tableData" />
```

### 2. Collection with Column DTOs and actions

Paginated collection, Column definitions, and row actions (route-based buttons and/or custom render):

```php
$users = User::query()->paginate(15);
$columns = [
    Column::make('name', 'Name')->sortable(),
    Column::make('email', 'Email')->sortable(),
];
$table = DynamicTableData::fromCollection(
    $users,
    $columns,
    fn (User $user) => [
        ['label' => 'Edit', 'route' => 'users.edit', 'params' => ['user' => 'id'], 'button' => 'secondary'],
        'render' => fn () => view('users.table.actions', ['user' => $user]),
    ]
);
```

Blade:

```blade
<x-v-dynamic-table.container :data="$table" emptyText="No users found." />
```

### 3. Column visibility

Explicit default visible column keys:

```php
$table->withColumnVisibility('users-table', ['name', 'email', 'created_at']);
```

Defaults from columns marked `->default()` (pass `null` as second argument):

```php
$table->withColumnVisibility('users-table', null);
```

### 4. Sorting

Attach sort state from the request and (optionally) restrict allowed keys:

```php
$sort = DynamicTableSort::fromRequest(['name', 'email', 'created_at']);
$table->withSorting($sort);
```

Server-side: apply sort in the controller with `Column::applySort()` so `sort_key` and `sort_query` are respected (see [Sort key resolution and server-side sort](#sort-key-resolution-and-server-side-sort)). When not using a paginator, `withSorting($sort)` sorts the current rows in memory.

### 5. Filters

Add filter definitions and apply request params in the controller:

```php
$table->withFilters([
    ['key' => 'status', 'label' => 'Status', 'type' => 'select', 'multiple' => true, 'options' => $statusOptions],
    ['key' => 'verified', 'label' => 'Verified only', 'type' => 'checkbox'],
    ['key' => 'q', 'label' => 'Keyword', 'type' => 'text', 'placeholder' => 'Search…'],
]);
```

Controller: read the same keys from the request and apply to the query (e.g. `request('status')`, `request('verified')`, `request('q')`). Array-based table data can include a `filters` key instead of using a provider.

### 6. Search

Form-submit search: show the search bar and apply the term in the controller:

```php
$table->withSearchableColumns(['name', 'email']);
// In controller: if ($q = request('search')) { $query->where(...); }
```

API search (dropdown with results from an endpoint):

```php
$table->withSearchApiUrl(route('api.users.search'));
```

API should return JSON: array of objects with at least `url` and `label`.

### 7. Export

Reuse column definitions for CSV/Excel:

```php
$rows = DynamicTableData::exportFromCollection($items, $columns);
$rowsWithHeaders = DynamicTableData::exportFromCollection($items, $columns, true);
```

### 8. BaseTable

Define columns and actions in a dedicated table class:

```php
$tableData = UsersTable::make($users)
    ->withSorting(DynamicTableSort::fromRequest())
    ->withColumnVisibility('users-table', null);
```

Optional context for `Column::visibleWhen()`:

```php
UsersTable::make($users, context: auth()->user());
```

### 9. Conditional column visibility

Pass context as the 4th argument to `fromCollection()`; columns with `->visibleWhen(callable)` are included only when the callback returns true:

```php
$table = DynamicTableData::fromCollection($users, $columns, $actions, auth()->user());
// Column::make('admin_only', 'Admin')->visibleWhen(fn ($user) => $user->isAdmin())
```

### 10. Blade component props

| Prop | Description |
|------|-------------|
| `data` | `DynamicTableDataProvider` instance or array with `headers`, `rows`, and optionally `filters`, `column_visibility_key`, `default_visible_columns`, `searchable_columns`, `search_api_url` |
| `headers` | Fallback array of headers when `data` is not provided |
| `rows` | Fallback array of rows when `data` is not provided |
| `class` | Optional CSS class on the container |
| `emptyText` | Text shown when there are no rows (default: "No data available.") |
| `columnVisibilityKey` | Override the storage key for column visibility (when using array `data`) |

---

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
| `multiple` | no | For `select`: when true, allows multiple values (request sends key as array) |

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
use Dennenboom\VerdantUI\Tables\Column;
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

        $columns = [
            Column::make('name', 'Name')->sortable(),
            Column::make('email', 'Email')->sortable(),
            Column::make('status', 'Status'),
        ];

        $table = DynamicTableData::fromCollection($users, $columns, fn ($user) => [/* actions */])
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

---

## BaseTable

Verdant UI provides an abstract `BaseTable` for defining table columns and actions in a dedicated class. Use it when you want a single place per table (e.g. `UsersTable`, `OrdersTable`).

```php
use Dennenboom\VerdantUI\Tables\BaseTable;
use Dennenboom\VerdantUI\Tables\Column;
use Dennenboom\VerdantUI\Tables\DynamicTableSort;

// In your controller:
$tableData = UsersTable::make($users)->withSorting(DynamicTableSort::fromRequest());
```

**Subclass** – implement `columns()`; override `actions()` when needed. You can use the Column DTO or the legacy array format:

```php
namespace App\Tables;

use App\Models\User;
use Dennenboom\VerdantUI\Tables\BaseTable;
use Dennenboom\VerdantUI\Tables\Column;

class UsersTable extends BaseTable
{
    protected static function columns(): array
    {
        return [
            Column::make('last_name', 'Last Name')->default()->sortable(),
            Column::make('first_name', 'First Name')->sortable(),
            Column::make('is_active', 'Active')
                ->format(static fn (bool $value) => $value ? 'Yes' : 'No'),
            Column::make('roles', 'Roles')
                ->render(static fn ($roles) => view('system.users.table.roles', compact('roles'))),
        ];
    }

    protected static function actions(): ?callable
    {
        return static fn (User $user) => [
            'render' => fn () => view('system.users.table.actions', compact('user')),
        ];
    }
}
```

Legacy array format still works:

```php
protected static function columns(): array
{
    return [
        'last_name' => ['label' => 'Last Name', 'sortable' => true],
        'first_name' => 'First Name',
        'is_active' => ['label' => 'Active', 'format' => fn (bool $v) => $v ? 'Yes' : 'No'],
    ];
}
```

**Optional context** for `Column::visibleWhen()`:

```php
UsersTable::make($users, context: auth()->user());
```