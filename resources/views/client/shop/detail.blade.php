@extends('client.layouts.master')
@section('content')
    {{-- resources/views/products/show.blade.php --}}
<div class="container mx-auto px-4 py-8">
    {{-- Breadcrumb --}}
    <div class="text-lg breadcrumbs mb-6">
        <ul>
            <li><a href="/">Trang chủ</a></li>
            <li><a href="{{ route('client.shop.index') }}">Khám phá</a></li>
            <li><a href="#">Chi tiết</a></li>
            <li><a href="#">{{ $product->code }}</a></li>
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
                            <div class="aspect-[9/6] rounded-lg overflow-hidden bg-base-100 w-full">
                                <img
                                    src="{{ asset($image->url) }}"
                                    alt="{{ $product->name }} - Hình {{ $index + 1 }}"
                                    class="w-full h-full object-contain"
                                />
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="aspect-[9/6] rounded-lg overflow-hidden bg-[#FFE4E1] w-full">
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
                    <a href="javascript:void(0)"
                        id="thumb{{ $index }}"
                        onclick="event.preventDefault(); setActiveThumbnail({{ $index }})"
                        class="w-12 h-12 rounded-full overflow-hidden border-2 hover:border-primary transition-all {{ $index == 0 ? 'border-primary' : 'border-gray-200' }}">
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

            {{-- Price --}}
            <div class="flex items-center gap-4">
                @if($product->price)
                    <span class="text-3xl font-bold text-primary">{{ $product->price }}</span>
                @else
                    <span class="text-2xl font-bold text-error">Giá liên hệ</span>
                @endif
            </div>

            <div class="flex items-center gap-4">
                {{-- Description --}}
                <div id="description-content" class="tab-content block">
                    <h3 class="text-xl font-semibold mb-4"></h3>
                    <p class="text-md text-gray-500">Mã SP: {{ $product->code }}</p>
                    <p class="text-lg">{{ $product->description }}</p>
                </div>
            </div>

            {{-- Category --}}
            <div>
                <span class="text-gray-600 text-lg">Danh mục: </span>
                <a href="{{ route('client.shop.category', ['slug' => $product->Category->slug]) }}" class="font-semibold text-lg link link-hover">{{ $product->Category->name }}</a>
            </div>

            <div class="flex flex-wrap gap-2 mb-6">
                <span class="text-gray-600 text-lg">Tags: </span>
                @foreach ($tags as $tag)
                <a href="/products?q={{ $tag->name }}" class="btn btn-sm btn-ghost bg-base-200">
                    <span class="text-base-content/70 text-lg font-semibold">{{ $tag->name }}</span>
                </a>
                @endforeach
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-wrap gap-4">
                <button id="downloadButton" class="btn btn-soft text-lg" onclick="downloadImage('x')">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 28 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8l-8 8-8-8" />
                    </svg>Tải xuống
                </button>
                <button class="btn btn-primary flex-1 text-lg" onclick="location.href='{{ route('client.contact.index') }}'">Liên hệ ngay</button>
            </div>

            {{-- Social Share --}}
            <div class="flex gap-2 mt-4" onclick="location.href='{{ route('client.contact.index') }}'">
                <button class="btn btn-circle btn-ghost">
                    <img src="{{ asset('images/logos/zalo.png') }}" alt="Zalo Icon" class="h-6 w-6 text-primary">
                </button>
                <button class="btn btn-circle btn-ghost">
                    <img src="{{ asset('images/logos/facebook.png') }}" alt="Facebook Icon" class="h-6 w-6 text-primary">
                </button>
            </div>
        </div>
    </div>

    {{-- Related Products --}}
    <div class="mt-12">
        <div class="border-t border-gray-200 py-8">
            <div class="container mx-auto px-4">
                <h1 class="text-2xl font-bold text-center mb-8">Xem thêm thiết kế khác liên quan</h1>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @include('client.partials.products-loop', ['products' => $products->take(16)])
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function setActiveThumbnail(activeIndex) {
        $('[id^="thumb"]').removeClass('border-primary').addClass('border-gray-200');
        $(`#thumb${activeIndex}`).removeClass('border-gray-200').addClass('border-primary');
        document.getElementById(`slide${activeIndex}`).scrollIntoView({
            behavior: 'smooth',
            block: 'nearest',
            inline: 'center'
        });
    }

    $(document).ready(function() {
        setActiveThumbnail(0);

        $('[id^="thumb"]').on('click', function(e) {
            e.preventDefault();
            const index = $(this).attr('id').replace('thumb', '');
            setActiveThumbnail(index);
        });
    });

    downloadImage = (x) => {
        $.ajax({
            url: `{{ route('client.product.detail.download', ['slug' => $product->slug]) }}`,
            type: 'GET',
            xhrFields: {
                responseType: 'blob'
            },
            success: function(blob) {
                location.href = `{{ route('client.product.detail.download', ['slug' => $product->slug]) }}`;
                $('#downloadButton').html(`<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg> Đã tải xuống`).prop('disabled', false);
            },
            beforeSend: function() {
                $('#downloadButton').html('<span class="loading loading-spinner"></span> Đang tải xuống').prop('disabled', true);
                console.log('beforeSend...');
            },
            error: function(xhr, status, error) {
                $('#downloadButton').html(`<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg> Vui lòng thử lại sau`).prop('disabled', false);
            }
        });
    }
</script>
@endsection
