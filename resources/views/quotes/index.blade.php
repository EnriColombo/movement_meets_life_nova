@extends('layouts.backend')

@section('title')
    @lang('views.quote_management')
@endsection

@section('buttons')

    @include('partials.forms.button',[
        'title' => 'Add quote',
        'url' => route('quotes.create'),
        'color' => 'indigo',
        'icon' => '',
        'size' => 1,
        'extraClasses' => 'mb-4',
        'kind' => 'primary',
        'target' => '_self',
    ])

@endsection

@section('content')

    @include('partials.quotes.searchBar')

    {{-- Tailwind Component: https://tailwindui.com/components/application-ui/lists/stacked-lists--}}
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <ul class="divide-y divide-gray-200">
            @foreach($quotes as $quote)
                @include('partials.quotes.indexItem', [
                    'quote' => $quote
                ])
            @endforeach
        </ul>
    </div>

    <div class="my-5">
        {{ $quotes->links() }}
    </div>


@endsection
