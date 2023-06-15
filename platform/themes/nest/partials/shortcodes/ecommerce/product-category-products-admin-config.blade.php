<div class="form-group">
    <label class="control-label">{{ __('Product category') }}</label>
    {!! Form::customSelect('category_id', $categories, Arr::get($attributes, 'category_id')) !!}
</div>
