<div class="col-md-6 col-lg-4">
    <a href="{{ route('frontend.employee_document_category', ['slug' => $category->slug, 'space' => $space ?? null]) }}" class="doc-folder">
        <div class="doc-folder__icon"><i class="fa fa-folder"></i></div>
        <div class="doc-folder__body">
            <h3>{{ $category->name }}</h3>
            @if($category->description)
                <p>{!! \Illuminate\Support\Str::limit(strip_tags($category->description), 70) !!}</p>
            @endif
            <span class="doc-folder__count">
                <i class="fa fa-file-text-o"></i>
                {{ $count }} {{ \Illuminate\Support\Str::plural('document', $count) }}
            </span>
        </div>
        <i class="fa fa-angle-right doc-folder__arrow"></i>
    </a>
</div>
