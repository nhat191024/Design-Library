@extends('client.layouts.master')
@section('content')
    {{-- resources/views/products/show.blade.php --}}
    <div class="container mx-auto px-4 py-8">
        {{-- Breadcrumb --}}
        <div class="breadcrumbs mb-6 text-lg">
            <ul>
                <li><a href="/">Trang chủ</a></li>
                <li><a href="{{ route('client.shop.index') }}">Khám phá</a></li>
                <li><a href="#">Chi tiết</a></li>
                <li><a href="#">{{ $product->code }}</a></li>
            </ul>
        </div>

        <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
            <div class="relative">
                <div class="absolute left-4 top-4 z-10">
                </div>
                <div class="carousel w-full">
                    @if ($product->Images->count() > 0)
                        @foreach ($product->Images as $index => $image)
                            <div id="slide{{ $index }}" class="carousel-item relative w-full">
                                <div class="aspect-[9/6] w-full overflow-hidden rounded-lg bg-base-100">
                                    <img class="h-full w-full object-contain" src="{{ asset($image->url) }}" alt="{{ $product->name }} - Hình {{ $index + 1 }}" />
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="aspect-[9/6] w-full overflow-hidden rounded-lg bg-[#FFE4E1]">
                            <div class="flex h-full w-full items-center justify-center text-gray-400">
                                Không có hình ảnh
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Thumbnail Navigation --}}
                @if ($product->Images->count() > 1)
                    <div class="mt-4 flex w-full justify-center gap-2 py-2">
                        @foreach ($product->Images as $index => $image)
                            <a id="thumb{{ $index }}" class="{{ $index == 0 ? 'border-primary' : 'border-gray-200' }} h-12 w-12 overflow-hidden rounded-full border-2 transition-all hover:border-primary" href="javascript:void(0)" onclick="event.preventDefault(); setActiveThumbnail({{ $index }})">
                                <img class="h-full w-full object-cover" src="{{ asset($image->url) }}" alt="Thumbnail {{ $index + 1 }}" />
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
                    @if ($product->price)
                        <span class="text-3xl font-bold text-primary">{{ $product->price }}</span>
                    @else
                        <span class="text-2xl font-bold text-error">Giá liên hệ</span>
                    @endif
                </div>

                <div class="flex items-center gap-4">
                    {{-- Description --}}
                    <div id="description-content" class="tab-content block">
                        <h3 class="mb-4 text-xl font-semibold"></h3>
                        <p class="text-md text-gray-500">Mã SP: {{ $product->code }}</p>
                        <p class="text-lg">{{ $product->description }}</p>
                    </div>
                </div>

                {{-- Category --}}
                <div>
                    <span class="text-lg text-gray-600">Danh mục: </span>
                    <a class="link link-hover text-lg font-semibold" href="{{ route('client.shop.category', ['slug' => $product->Category->slug]) }}">{{ $product->Category->name }}</a>
                </div>

                <div class="mb-6 flex flex-wrap gap-2">
                    <span class="text-lg text-gray-600">Tags: </span>
                    @foreach ($tags as $tag)
                        <a class="btn btn-ghost btn-sm bg-base-200" href="/products?tag={{ $tag->name }}">
                            <span class="text-base-content/70 text-lg font-semibold">{{ $tag->name }}</span>
                        </a>
                    @endforeach
                </div>

                {{-- Action Buttons --}}
                <div class="flex flex-wrap gap-4">
                    <button id="downloadButton" class="btn btn-soft text-lg" onclick="downloadImage('x')">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 28 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8l-8 8-8-8" />
                        </svg>Tải ảnh demo
                    </button>
                    <button class="btn btn-primary flex-1 text-lg" onclick="location.href='{{ route('client.contact.index') }}'">Liên hệ ngay</button>
                </div>

                {{-- Social Share --}}
                <div class="mt-4 flex gap-2" onclick="location.href='{{ route('client.contact.index') }}'">
                    <button class="btn btn-ghost btn-circle">
                        <img class="h-6 w-6 text-primary" src="{{ asset('images/logos/zalo.png') }}" alt="Zalo Icon">
                    </button>
                    <button class="btn btn-ghost btn-circle">
                        <img class="h-6 w-6 text-primary" src="{{ asset('images/logos/facebook.png') }}" alt="Facebook Icon">
                    </button>
                </div>
            </div>
        </div>

        {{-- Related Products --}}
        <div class="mt-12">
            <div class="border-t border-gray-200 py-8">
                <div class="container mx-auto px-4">
                    <h1 class="mb-8 text-center text-2xl font-bold">Xem thêm thiết kế khác liên quan</h1>
                    <div class="grid grid-cols-2 gap-6 md:grid-cols-3 lg:grid-cols-4">
                        @include('client.partials.products-loop', ['products' => $products->take(16)])
                    </div>
                </div>
            </div>
        </div>
    </div>

    <button id="download_modal_button" class="hidden" class="btn" onclick="download_modal.showModal()">open modal</button>
    <dialog id="download_modal" class="modal">
        <div class="modal-box">
            <h3 class="mb-4 text-lg font-bold">Tải xuống hình ảnh</h3>
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Ảnh</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="download_modal_body">
                        @foreach ($product->Images as $index => $image)
                            <tr>
                                <td><img src="{{ asset($image->url) }}" alt="{{ $image->name }}" width="50%"></td>
                                <td>
                                    <a class="btn btn-primary btn-sm" href="{{ asset($image->url) }}" download="{{ $image->name }}">
                                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-action">
                <form method="dialog">
                    <button class="btn">Đóng</button>
                </form>
            </div>
        </div>
    </dialog>

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

        function initDownloadModal() {
            $('#download_modal_button').click();

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
            isMobile = /iPhone|iPad|iPod|Android|webOS|BlackBerry|Windows Phone/i.test(navigator.userAgent);
            if (isMobile) {
                $.ajax({
                    url: `{{ route('client.product.detail.download-mobile', ['slug' => $product->slug, 'is_mobile' => true]) }}`,
                    type: 'GET',
                    success: function(res) {
                        // check if response type is blob
                        console.log('got many data...', res);
                        if (res.length > 200) {
                            console.log('too much data...');
                            location.href = `{{ route('client.product.detail.download', ['slug' => $product->slug]) }}`;
                        } else {
                            initDownloadModal(res.images);
                        }
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
                return;
            }

            $.ajax({
                url: `{{ route('client.product.detail.download', ['slug' => $product->slug]) }}`,
                type: 'GET',
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
