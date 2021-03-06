@extends('layouts.backend')

@section('title')
    @lang('views.glossary_management')
@endsection

@section('buttons')
    @include('partials.forms.button',[
       'title' => 'Add glossary',
       'url' => route('glossaries.create'),
       'color' => 'indigo',
       'icon' => '',
       'size' => 1,
       'extraClasses' => 'mb-4',
       'kind' => 'primary',
       'target' => '_self',
   ])
@endsection

@section('content')

    @include('partials.glossary.searchBar')

    {{-- Tailwind Component: https://tailwindui.com/components/application-ui/lists/stacked-lists--}}
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <ul class="divide-y divide-gray-200">
            @foreach($glossaries as $glossary)
                @include('partials.glossary.indexItem', [
                    'glossary' => $glossary
                ])
            @endforeach
        </ul>
    </div>

    <div class="my-5">
        {{ $glossaries->links() }}
    </div>


@endsection
