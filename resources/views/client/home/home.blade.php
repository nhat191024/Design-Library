@extends('client.layouts.master')
@section('content')
    <header class="hero bg-base-100 pt-10 md:pt-5">
        <div class="hero-content text-center pb-0">
            <div class="max-w-4xl">
                <h1 class="text-3xl md:text-5xl font-bold mb-4">KHO TÀI NGUYÊN THIẾT KẾ DECOR EVENT-BIRTHDAY-WEDDING</h1>
                <h5 class="mb-6 text-xl">Nhận thiết kế thương mại file chất lượng giá tốt và uy tín</h5>

                <!-- Search Bar Container -->
                <div class="flex justify-center w-full px-4">
                    <div class="form-control w-full max-w-3xl">
                        <div class="join w-full">
                            <div class="w-full relative">
                                <label class="input validator join-item w-full">
                                    <svg class="h-[1em] opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                        <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2.5" fill="none"
                                            stroke="currentColor">
                                            <circle cx="11" cy="11" r="8"></circle>
                                            <path d="m21 21-4.3-4.3"></path>
                                        </g>
                                    </svg>
                                    <input type="search" class="grow input input-ghost focus:outline-none text-lg"
                                        id="search-input"
                                        placeholder="Gõ từ khóa tài nguyên bạn cần"
                                        autocomplete="off" />
                                </label>
                                <div class="validator-hint hidden">Vui lòng nhập khóa</div>

                                {{-- Suggestions Dropdown --}}
                                <div id="search-suggestions" class="absolute z-20 w-full mt-1 bg-base-100 shadow-lg rounded-md border border-base-300 hidden">
                                    <ul class="py-2 max-h-60 overflow-y-auto text-left"></ul>
                                </div>
                            </div>
                            <button onclick="search()" id="btn-search-submit" class="btn btn-soft join-item whitespace-nowrap font-semibold">Tìm kiếm</button>
                        </div>
                    </div>
                </div>

                <!-- Popular Tags -->
                <div class="flex flex-wrap justify-center gap-2 mt-5">
                    @foreach ($tags->take(15) as $tag)
                        <a href="/products?q={{ $tag->name }}"
                            class="badge badge-soft badge-accent gap-1 p-2 hover:bg-base-200">
                            <i class="las la-search"></i>
                            <span class="text-lg">{{ $tag->name }}</span>
                        </a>
                    @endforeach
                </div>

                <!-- Categories -->
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mt-7 justify-items-center">
                    @foreach ($showcaseCategories as $category)
                        <a href="{{ route('client.shop.category', ['slug' => $category->slug]) }}"
                            class="flex flex-col items-center hover:opacity-80 transition-opacity">
                            <div class="avatar">
                                <div class="w-10 h-10 md:w-14 md:h-14 rounded-full ring ring-warning ring-offset-2">
                                    <img src="{{ asset($category->image) }}" alt="{{ $category->name }}" onerror="this.onerror=null;this.src='{{ asset('/images/designs/placeholder.jpg') }}';" />
                                </div>
                            </div>
                            <div class="mt-2 text-lg md:text-base font-semibold">{{ $category->name }}</div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </header>

    {{-- main page content --}}
    <main class="pb-10 container mx-auto px-4">
        @foreach ($categories as $category)
            <div class="mb-4 mt-6">
                <a href="javascript:void(0)">
                    <h1 onclick="location.href='{{ route('client.shop.category', ['slug' => $category->slug]) }}'" class="text-lg md:text-xl lg:text-2xl font-bold">
                        {{ $category->name }}
                    </h1>
                </a>
                <div class="mt-2 h-1 w-20 bg-primary rounded-full"></div>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @php
                    $products = $category->ProductsShowCase()->get();
                    if ($products->isEmpty()) {
                        $products = $category->Products->take(8);
                    }
                @endphp
                @include('client.partials.products-loop', ['products' => $products])
            </div>
        @endforeach
        <div class="mt-12 mb-4">
            <a href="javascript:void(0)">
                <h1 onclick="location.href='{{ route('client.shop.index') }}'" class="text-lg md:text-xl lg:text-2xl font-bold">
                    Mới nhất
                </h1>
            </a>
            <div class="mt-2 h-1 w-20 bg-primary rounded-full"></div>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @include('client.partials.products-loop')
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
