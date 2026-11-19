# Select

`<x-v-form.select>` is a searchable dropdown for one or many values.

```blade
<x-v-form.select name="country" label="Country" :options="$countries" value-key="id" label-key="name" :selected="$user->country_id" />
```

## Live API search

Give the select a `search-url` to turn on live search. The options are then loaded from that URL instead of the `options` prop:

- when the dropdown opens, with an empty search
- while the user types, 250 ms after the last keystroke

```blade
<x-v-form.select
    name="customer_id"
    label="Customer"
    search-url="{{ route('customers.search') }}"
    value-key="id"
    label-key="name"
    :selected="$order->customer_id"
    :selected-label="$order->customer?->name"
/>
```

The select sends `GET {search-url}?q={term}` with the session cookie and `Accept: application/json`. The endpoint returns the options either as a plain array or wrapped in `data`, so a Laravel API resource or paginator works as is:

```php
public function index(Request $request): JsonResponse
{
    $customers = Customer::query()
        ->where('name', 'like', "%{$request->query('q')}%")
        ->orderBy('name')
        ->limit(20)
        ->get(['id', 'name']);

    return response()->json($customers);
}
```

The search term is not filtered again in the browser: the endpoint decides what matches.

While a request runs, the dropdown shows "Searching...". A failed request shows "Could not load results". When the user keeps typing, a request that is still running is cancelled, so an older response never replaces newer results.

### Initial value

The selected value is usually not in the first page of results, so pass its label with `selected-label`. For a `multiple` select, pass a map of value to label:

```blade
<x-v-form.select
    name="tag_ids[]"
    label="Tags"
    multiple
    search-url="{{ route('tags.search') }}"
    value-key="id"
    label-key="name"
    :selected="$post->tags->pluck('id')->all()"
    :selected-label="$post->tags->pluck('name', 'id')->all()"
/>
```

Picked options keep their label after a new search replaces the list.

### Dynamic URL

Inside Alpine, use `x-search-url` with a JavaScript expression to change the URL at runtime, for example to filter by another field:

```blade
<x-v-form.select name="city_id" label="City" x-search-url="`/api/cities?country=${country}`" />
```

## Events

The select dispatches `select-change` (`detail: {name, value, option}`) whenever an option is picked.
