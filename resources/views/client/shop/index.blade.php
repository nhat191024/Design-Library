@extends('client.layouts.master')
@section('content')
    <div class="container mx-auto px-4 py-6">
        {{-- Advanced Search Title --}}
        <h2 class="text-xl font-bold mb-4">Tìm kiếm</h2>

        {{-- Categories - First Row --}}
        <div class="flex flex-wrap gap-2 mb-3">
            <button class="btn btn-sm btn-ghost bg-base-200md" disabled>
                <span class="text-base-content/70">Danh mục:</span>
            </button>
            @foreach ($categories as $category)
                <a href="{{ route('client.shop.category', ['slug' => $category->slug]) }}" class="btn btn-sm btn-ghost bg-base-200md">
                    <span class="text-base-content/70">{{ $category->name }}</span>
                </a>
            @endforeach
        </div>

        {{-- Tags - Second Row --}}
        <div class="flex flex-wrap gap-2 mb-6">
            <button class="btn btn-sm btn-ghost bg-base-200md" disabled>
                <span class="text-base-content/70">Tags:</span>
            </button>
            @foreach ($tags as $tag)
                <a href="/products?q={{ $tag->name }}" class="btn btn-sm btn-ghost bg-base-200">
                    <span class="text-base-content/70">{{ $tag->name }}</span>
                </a>
            @endforeach
        </div>

        {{-- Search Bar --}}
        <div class="flex flex-col sm:flex-row gap-4 mb-8">
            <div class="join flex-1">
                <div class="w-full join-item">
                    <div class="relative">
                        <form method="GET" action="{{ route('client.shop.index') }}">
                            <input name="q" type="search" placeholder="Tìm kiếm..." class="input input-bordered w-full pr-10"
                                value="{{ request('q') ?? isset($query)?$query:'' }}" />
                            {{-- <button type="reset" class="hidden btn btn-ghost btn-circle absolute right-0 top-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button> --}}
                        </form>
                    </div>
                </div>
            </div>

            <button class="btn btn-soft" onclick="search()">Tìm kiếm</button>
        </div>

        {{-- Search Results Title --}}
        <div class="text-xl font-medium mb-6">
            Kết quả cho {{ 'từ khóa' }}: <span class="font-bold">{{ request('q') ?? isset($query)?$query:'' }}</span>
        </div>
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
@endsection
