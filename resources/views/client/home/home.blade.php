@extends('client.layouts.master')
@section('content')
    <header class="hero bg-base-100 pt-10 md:pt-28">
        <div class="hero-content text-center">
            <div class="max-w-4xl">
                <h1 class="text-3xl md:text-5xl font-bold mb-4">Kho tài nguyên thiết kế chất lượng nhất Việt Nam</h1>
                <h5 class="mb-6">Các file tài nguyên mới sẽ được update mỗi ngày</h5>

                <!-- Search Bar -->
                <div class="form-control w-full mx-auto">
                    <div class="join">
                        <div>
                            <label class="input validator join-item w-[50vw]">
                                <svg class="h-[1em] opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2.5" fill="none"
                                        stroke="currentColor">
                                        <circle cx="11" cy="11" r="8"></circle>
                                        <path d="m21 21-4.3-4.3"></path>
                                    </g>
                                </svg>
                                <input type="search" class="grow input input-ghost focus:outline-none"
                                    placeholder="Gõ từ khóa tài nguyên bạn cần" />
                            </label>
                            <div class="validator-hint hidden">Vui lòng nhập khóa</div>
                        </div>
                        <button onclick="search()" id="btn-search-submit" class="btn btn-soft join-item">Tìm kiếm</button>
                    </div>
                </div>

                <!-- Popular Tags -->
                <div class="flex flex-wrap justify-center gap-2 mt-5">
                    @foreach ($tags as $tag)
                        <a href="/products?q={{ $tag->name }}"
                            class="badge badge-soft badge-accent gap-1 p-2 hover:bg-base-200">
                            <i class="las la-search"></i>
                            <span>{{ $tag->name }}</span>
                        </a>
                    @endforeach
                </div>

                <!-- Categories -->
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mt-24 justify-items-center">
                    @foreach ($categories as $category)
                        <a href="{{ route('client.shop.category', ['slug' => $category->slug]) }}"
                            class="flex flex-col items-center hover:opacity-80 transition-opacity">
                            <div class="avatar">
                                <div class="w-10 h-10 md:w-24 md:h-24 rounded-full ring ring-warning ring-offset-2">
                                    <img src="{{ asset($category->image) }}" alt="category" />
                                </div>
                            </div>
                            <div class="mt-2 text-xs md:text-base text-white">{{ $category->name }}</div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </header>

    {{-- main page content --}}
    <main class="pb-10 container mx-auto px-4">
        @foreach ($categories as $category)
            <div class="mb-4 mt-12">
                <h1 class="text-2xl md:text-3xl lg:text-4xl font-bold">
                    {{ $category->name }}
                </h1>
                <div class="mt-2 h-1 w-20 bg-primary rounded-full"></div>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @include('client.partials.products-loop', ['products' => $category->Products])
            </div>
        @endforeach
        <div class="mt-12 mb-4">
            <h1 class="text-2xl md:text-3xl lg:text-4xl font-bold">
                {{ 'Mới nhất' }}
            </h1>
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
@endsection
