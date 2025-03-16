@extends('client.layouts.master')
@section('content')
    <div class="container mx-auto px-4 py-6">
        {{-- Advanced Search Title --}}
        <h2 class="text-xl font-bold mb-4">Tìm kiếm</h2>

        {{-- Categories - First Row --}}
        <div class="flex flex-wrap gap-2 mb-3">
            <button class="btn btn-sm btn-ghost bg-base-200md" disabled>
                <span class="text-base-content/70 text-sm">Danh mục:</span>
            </button>
            @foreach ($categories->take(9) as $category)
                <a href="{{ route('client.shop.category', ['slug' => $category->slug]) }}" class="btn btn-sm btn-ghost bg-base-200md text-sm">
                    <span class="text-base-content/70">{{ $category->name }}</span>
                </a>
            @endforeach
        </div>

        {{-- Tags - Second Row --}}
        <div class="flex flex-wrap gap-2 mb-6">
            <button class="btn btn-sm btn-ghost bg-base-200md" disabled>
                <span class="text-base-content/70 text-sm">Tags:</span>
            </button>
            @foreach ($tags->take(10) as $tag)
                    <a href="/products?q={{ $tag->name }}" class="btn btn-sm btn-ghost bg-base-200">
                        <span class="text-base-content/70 text-sm">{{ $tag->name }}</span>
                    </a>
            @endforeach
        </div>

        {{-- Search Bar with Autocomplete --}}
        <div class="flex flex-col sm:flex-row gap-4 mb-8">
            <div class="join flex-1 relative">
                <div class="w-full join-item">
                    <div class="relative">
                        <form method="GET" action="{{ route('client.shop.index') }}" id="search-form">
                            <input
                                name="q"
                                id="search-input"
                                type="search"
                                placeholder="Tìm kiếm..."
                                class="input input-bordered w-full pr-10"
                                value="{{ request('q') ?? isset($query)?$query:'' }}"
                                autocomplete="off"
                            />
                        </form>

                        {{-- Suggestions Dropdown --}}
                        <div id="search-suggestions" class="absolute z-20 w-full mt-1 bg-base-100 shadow-lg rounded-md border border-base-300 hidden">
                            <ul class="py-2 max-h-60 overflow-y-auto"></ul>
                        </div>
                    </div>
                </div>
            </div>

            <button class="btn btn-soft font-bold" onclick="search()">Tìm kiếm</button>
        </div>

        @if(request('q'))
            {{-- Search Results Title --}}
            <div class="text-xl font-medium mb-6">
                Kết quả cho {{ 'từ khóa' }}: <span class="font-bold">{{ request('q') ?? isset($query)?$query:'' }}</span>
            </div>
        @endif
    </div>


    {{-- main page content --}}
    <main class="pb-10 container mx-auto px-4">
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <div class="flex-col justify-center col-span-full mt-4">
                {{ $products->links() }}
            </div>
            @include('client.partials.products-loop')
            <div class="flex-col justify-center col-span-full mt-4">
                {{ $products->links() }}
            </div>
        </div>
    </main>
    <script>
        function search() {
            const query = document.querySelector('input[type="search"]').value;
            window.location.href = `/products?q=${query}`;
        }
    </script>
    @include('client.partials.search-suggestions')
@endsection
