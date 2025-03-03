@extends('client.layouts.master')
@section('content')
    <header class="hero bg-base-100 py-10 md:py-28">
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
                                <input type="search" class="grow input input-ghost focus:outline-none" placeholder="Gõ từ khóa tài nguyên bạn cần" />
                            </label>
                            <div class="validator-hint hidden">Vui lòng nhập khóa</div>
                        </div>
                        <button id="btn-search-submit" class="btn btn-soft join-item">Tìm kiếm</button>
                    </div>
                </div>

                <!-- Popular Tags -->
                <div class="flex flex-wrap justify-center gap-2 mt-5">
                    @foreach (['file', 'image', 'video', 'audio', 'document', 'text'] as $tag)
                        <a href="/search?query={{ $tag }}" class="badge badge-soft badge-accent gap-1 p-2 hover:bg-base-200">
                            <i class="las la-search"></i>
                            <span>{{ $tag }}</span>
                        </a>
                    @endforeach
                </div>

                <!-- Categories -->
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mt-24 justify-items-center">
                    @foreach ([] as $category)
                        <a href="{{ $category['url'] }}"
                            class="flex flex-col items-center hover:opacity-80 transition-opacity">
                            <div class="avatar">
                                <div class="w-10 h-10 md:w-24 md:h-24 rounded-full ring ring-warning ring-offset-2">
                                    <img src="{{ $category['image'] }}" alt="{{ $category['name'] }}" />
                                </div>
                            </div>
                            <div class="mt-2 text-xs md:text-base text-black">{{ $category['name'] }}</div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </header>

    {{-- main page content --}}
    
@endsection
