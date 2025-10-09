@extends('client.layouts.master')
@section('content')
    <div class="container mx-auto px-4 py-6">
        {{-- Advanced Search Title --}}
        <h2 class="mb-4 text-xl font-bold">Tìm kiếm</h2>

        {{-- Categories - First Row --}}
        <div class="mb-3 flex flex-wrap gap-2">
            <button class="bg-base-200md btn btn-ghost btn-sm" disabled>
                <span class="text-base-content/70 text-sm">Danh mục:</span>
            </button>
            @foreach ($categories as $category)
                <a class="bg-base-200md btn btn-ghost btn-sm text-sm" href="{{ route('client.shop.category', ['slug' => $category->slug]) }}">
                    <span class="text-base-content/70">{{ $category->name }}</span>
                </a>
            @endforeach
        </div>

        {{-- Tags - Second Row --}}
        <div class="mb-6 flex flex-wrap gap-2 overflow-x-auto" style="flex-flow: nowrap">
            <button class="bg-base-200md btn btn-ghost btn-sm" disabled>
                <span class="text-base-content/70 text-sm">Tags:</span>
            </button>
            @foreach ($tagSuggestions as $tag)
                <a class="btn btn-ghost btn-sm bg-base-200" href="/products?q={{ $tag->name }}">
                    <span class="text-base-content/70 text-sm">{{ $tag->name }}</span>
                </a>
            @endforeach
        </div>

        {{-- Search Bar with Autocomplete --}}
        <div class="mb-8 flex flex-col gap-4 sm:flex-row">
            <div class="relative flex-1 join">
                <div class="w-full join-item">
                    <div class="relative">
                        <form id="search-form" method="GET" action="{{ route('client.shop.index') }}">
                            <input id="search-input" class="input-bordered input w-full pr-10" name="q" type="search" value="{{ request('q') ?? isset($query) ? $query : '' }}" placeholder="Tìm kiếm..." autocomplete="off" />
                        </form>

                        {{-- Suggestions Dropdown --}}
                        <div id="search-suggestions" class="absolute z-20 mt-1 hidden w-full rounded-md border border-base-300 bg-base-100 shadow-lg">
                            <ul class="max-h-60 overflow-y-auto py-2"></ul>
                        </div>
                    </div>
                </div>
            </div>

            <button class="btn btn-soft font-bold" onclick="search()">Tìm kiếm</button>
        </div>

        @if (request('q'))
            {{-- Search Results Title --}}
            <div class="mb-6 text-xl font-medium">
                Kết quả cho {{ 'từ khóa' }}: <span class="font-bold">{{ request('q') ?? isset($query) ? $query : '' }}</span>
            </div>
        @endif
    </div>

    {{-- main page content --}}
    <main class="container mx-auto px-4 pb-10">
        <div class="grid grid-cols-2 gap-6 md:grid-cols-3 lg:grid-cols-4">
            <div class="col-span-full mt-4 flex-col justify-center">
                {{ $products->appends(request()->except('page'))->links() }}
            </div>
            @include('client.partials.products-loop')
            <div class="col-span-full mt-4 flex-col justify-center">
                {{ $products->appends(request()->except('page'))->links() }}
            </div>
        </div>
    </main>
    <script>
        function search() {
            const query = document.querySelector('input[type="search"]').value;
            if (query) {
                window.location.href = `/products?q=${encodeURIComponent(query)}`;
            }
        }
    </script>
    @include('client.partials.search-suggestions')
@endsection
