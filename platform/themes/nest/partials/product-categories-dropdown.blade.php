@foreach(!$more ? $categories->take(10)->chunk(5) : $categories->skip(10)->chunk(ceil((count($categories) - 10) / 2)) as $chunk)
    <ul @if ($loop->last) class="end" @endif>
        @foreach($chunk as $category)
            <li>
                <a href="{{ $category->url }}">
                    @if ($category->getMetaData('icon_image', true))
                        <img src="{{ RvMedia::getImageUrl($category->getMetaData('icon_image', true)) }}" alt="{{ $category->name }}" width="30" height="30">
                    @elseif ($category->getMetaData('icon', true))
                        <i class="{{ $category->getMetaData('icon', true) }}"></i>
                    @endif {!! BaseHelper::clean($category->name) !!}
                </a>
            </li>
        @endforeach
    </ul>
@endforeach
