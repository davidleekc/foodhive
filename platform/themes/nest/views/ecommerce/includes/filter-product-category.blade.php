@foreach ($categories as $category)
    <span>{!! BaseHelper::clean($indent) !!}</span>
    <input class="form-check-input category-filter-input" data-id="{{ $category->id }}" data-parent-id="{{ $category->parent_id }}"
           name="categories[]"
           type="checkbox"
           id="category-filter-{{ $category->id }}"
           value="{{ $category->id }}"
           @if (in_array($category->id, request()->input('categories', []))) checked @endif>
    <label class="form-check-label" for="category-filter-{{ $category->id }}">
        <span class="d-inline-block">{!! BaseHelper::clean($category->name) !!}</span>
    </label>
    <br>

    @if ($category->activeChildren->count())
        @include(Theme::getThemeNamespace() . '::views.ecommerce.includes.filter-product-category', ['categories' => $category->activeChildren, 'indent' => $indent . '&nbsp;&nbsp;'])
    @endif
@endforeach
