@extends('client.layouts.master')
@section('content')
    {{-- resources/views/products/show.blade.php --}}
<div class="container mx-auto px-4 py-8">
    {{-- Breadcrumb --}}
    <div class="text-sm breadcrumbs mb-6">
        <ul>
            <li><a href="/">Trang chủ</a></li>
            <li><a href="{{ route('client.shop.index') }}">Khám phá</a></li>
            <li><a href="#">Chi tiết</a></li>
        </ul>
    </div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    <div class="relative">
        <div class="absolute left-4 top-4 z-10">
        </div>
        <div class="carousel w-full">
            @if($product->Images->count() > 0)
                @foreach($product->Images as $index => $image)
                    <div id="slide{{ $index }}" class="carousel-item relative w-full">
                        <div class="aspect-square rounded-full overflow-hidden bg-[#FFE4E1] w-full">
                            <img
                                src="{{ asset($image->url) }}"
                                alt="{{ $product->name }} - Hình {{ $index + 1 }}"
                                class="w-full h-full"
                            />
                        </div>
                        <div class="absolute flex justify-between transform -translate-y-1/2 left-5 right-5" style="top: 95%;">
                            <a href="#slide{{ $index == 0 ? $product->Images->count() - 1 : $index - 1 }}" class="btn btn-circle btn-sm bg-white/70 hover:bg-white">❮</a>
                            <a href="#slide{{ $index == $product->Images->count() - 1 ? 0 : $index + 1 }}" class="btn btn-circle btn-sm bg-white/70 hover:bg-white">❯</a>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="aspect-square rounded-full overflow-hidden bg-[#FFE4E1] w-full">
                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                        Không có hình ảnh
                    </div>
                </div>
            @endif
        </div>

        {{-- Thumbnail Navigation --}}
        @if($product->Images->count() > 1)
            <div class="flex justify-center w-full py-2 gap-2 mt-4">
                @foreach($product->Images as $index => $image)
                    <a href="#slide{{ $index }}" class="w-12 h-12 rounded-full overflow-hidden border-2 hover:border-primary transition-all {{ $index == 0 ? 'border-primary' : 'border-gray-200' }}">
                        <img
                            src="{{ asset($image->url) }}"
                            alt="Thumbnail {{ $index + 1 }}"
                            class="w-full h-full object-cover"
                        />
                    </a>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Product Info --}}
    <div class="flex flex-col gap-6">
        <h1 class="text-3xl font-bold">{{ $product->name ?? '' }}</h1>

        {{-- Category --}}
        <div>
            <span class="text-gray-600">Danh mục: </span>
            <a href="{{ route('client.shop.category', ['slug' => $product->Category->slug]) }}" class="link link-hover">{{ $product->Category->name }}</a>
        </div>

        {{-- Action Buttons --}}
        <div class="flex flex-wrap gap-4">
            <button class="btn btn-primary flex-1">Liên hệ ngay</button>
        </div>

        {{-- Social Share --}}
        <div class="flex gap-2 mt-4">
            <button class="btn btn-circle btn-ghost">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="fill-current">
                    <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"></path>
                </svg>
            </button>
            <button class="btn btn-circle btn-ghost">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="fill-current">
                    <path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"></path>
                </svg>
            </button>
            <button class="btn btn-circle btn-ghost">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="fill-current">
                    <path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"></path>
                </svg>
            </button>
        </div>
    </div>
</div>

    {{-- Product Tabs --}}
    <div class="mt-12">

        <div class="border-b border-gray-200">
            <div class="flex gap-8">
                <button class="py-4 text-base font-medium relative group text-primary border-b-2 border-primary">
                    Mô tả
                </button>
            </div>
        </div>


        <div class="py-6">

            <div id="description-content" class="tab-content block">
                <h3 class="text-xl font-semibold mb-4">{{ $product->name }}</h3>
                <p>{{ $product->description }}</p>
            </div>

        </div>
    </div>
</div>
@endsection
