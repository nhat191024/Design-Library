@extends('client.layouts.master')
@section('content')
    <style>
        #bg-zone0 {
            z-index: 1; /* Keep above zone1 but behind navbar */
        }
        #bg-zone0 .bg-zone-inner {
            background-size: cover; /* "Zoom to fit" filling the navbar area */
            background-position: center;
        }
    </style>

    {{-- === BACKGROUND LAYERS === --}}
    @if (!empty($bgSettings['zone0_image']))
    <div id="bg-zone0" class="bg-zone" aria-hidden="true"
         style="--bg-image: url('{{ asset($bgSettings['zone0_image']) }}');
                --blur: {{ $bgSettings['zone0_blur'] ?? 0 }}px;
                --opacity: {{ $bgSettings['zone0_opacity'] ?? 0.5 }};">
        <div class="bg-zone-inner"></div>
    </div>
    @endif
    @if (!empty($bgSettings['zone1_image']))
    <div id="bg-zone1" class="bg-zone" aria-hidden="true"
         style="--bg-image: url('{{ asset($bgSettings['zone1_image']) }}');
                --blur: {{ $bgSettings['zone1_blur'] ?? 0 }}px;
                --opacity: {{ $bgSettings['zone1_opacity'] ?? 0.5 }};">
        <div class="bg-zone-inner"></div>
    </div>
    @endif

    @if (!empty($bgSettings['zone2_image']))
    <div id="bg-zone2" class="bg-zone bg-zone--repeat" aria-hidden="true"
         style="--bg-image: url('{{ asset($bgSettings['zone2_image']) }}');
                --blur: {{ $bgSettings['zone2_blur'] ?? 0 }}px;
                --opacity: {{ $bgSettings['zone2_opacity'] ?? 0.5 }};">
        <div class="bg-zone-inner"></div>
    </div>
    @endif
    {{-- === END BACKGROUND LAYERS === --}}

    <header class="hero bg-base-100/80 pt-10 md:pt-5 relative z-10">
        <div class="hero-content pb-0 text-center">
            <div class="max-w-4xl">
                <h1 class="mb-4 text-3xl font-bold md:text-5xl">KHO TÀI NGUYÊN THIẾT KẾ DECOR EVENT-BIRTHDAY-WEDDING</h1>
                <h5 class="mb-6 text-xl">Nhận thiết kế thương mại file chất lượng giá tốt và uy tín</h5>

                <!-- Search Bar Container -->
                <div class="flex w-full justify-center px-4">
                    <div class="form-control w-full max-w-3xl">
                        <div class="w-full join">
                            <div class="relative w-full">
                                <label class="input validator w-full join-item">
                                    <svg class="h-[1em] opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                        <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2.5" fill="none" stroke="currentColor">
                                            <circle cx="11" cy="11" r="8"></circle>
                                            <path d="m21 21-4.3-4.3"></path>
                                        </g>
                                    </svg>
                                    <input id="search-input" class="input input-ghost grow text-lg focus:outline-none" type="search" placeholder="Gõ từ khóa tài nguyên bạn cần" autocomplete="off" />
                                </label>
                                <div class="validator-hint hidden">Vui lòng nhập khóa</div>

                                {{-- Suggestions Dropdown --}}
                                <div id="search-suggestions" class="absolute z-20 mt-1 hidden w-full rounded-md border border-base-300 bg-base-100 shadow-lg">
                                    <ul class="max-h-60 overflow-y-auto py-2 text-left"></ul>
                                </div>
                            </div>
                            <button id="btn-search-submit" class="btn btn-soft whitespace-nowrap font-semibold join-item" onclick="search()">Tìm kiếm</button>
                        </div>
                    </div>
                </div>

                <!-- Popular Tags -->
                <div class="mt-5 flex flex-wrap justify-center gap-2">
                    @foreach ($tagSuggestions as $tag)
                        <a class="badge badge-soft badge-accent gap-1 p-2 hover:bg-base-200" href="/products?tag={{ $tag->name }}">
                            <i class="las la-search"></i>
                            <span class="text-lg">{{ $tag->name }}</span>
                        </a>
                    @endforeach
                </div>

                <!-- Categories -->
                <div class="mt-7 grid grid-cols-2 justify-items-center gap-4 md:grid-cols-5">
                    @foreach ($showcaseCategories as $category)
                        <a class="flex flex-col items-center transition-opacity hover:opacity-80" href="{{ route('client.shop.category', ['slug' => $category->slug]) }}">
                            <div class="avatar">
                                <div class="h-10 w-10 rounded-full ring ring-warning ring-offset-2 md:h-14 md:w-14">
                                    <img class="lazy-image" src="{{ asset($category->image) }}" alt="{{ $category->name }}" loading="lazy" onerror="this.onerror=null;this.src='{{ asset('/images/designs/placeholder.jpg') }}';" />
                                </div>
                            </div>
                            <div class="mt-2 text-lg font-semibold md:text-base">{{ $category->name }}</div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </header>

    {{-- main page content --}}
    <main class="container mx-auto px-4 pb-10 relative z-10">
        <div id="design-section-header" class="mb-4 mt-12 flex items-center justify-between">
            <div>
                <a href="javascript:void(0)">
                    <h1 class="text-lg font-bold md:text-xl lg:text-2xl" onclick="location.href='{{ route('client.shop.index') }}'">
                        Design Nổi Bật
                    </h1>
                </a>
                <div class="mt-2 h-1 w-20 rounded-full bg-primary"></div>
            </div>
        </div>
        <div id="products-container" class="grid grid-cols-2 gap-6 md:grid-cols-3 lg:grid-cols-4">
            @include('client.partials.products-loop')
        </div>

        {{-- Loading Spinner --}}
        <div id="loading-spinner" class="mt-8 justify-center" style="display: none;">
            <span class="loading loading-spinner loading-lg text-primary"></span>
        </div>

        {{-- Intersection Observer Target --}}
        <div id="load-more-trigger" class="h-20"></div>
    </main>
    <script>
        function search() {
            const query = $('#search-input').val();
            window.location.href = `/products?q=${query}`;
        }

        $(document).ready(function() {
            let imageObserver = null;

            if ('IntersectionObserver' in window) {
                imageObserver = new IntersectionObserver(function(entries, observer) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            const img = $(entry.target);

                            if (img.data('src')) {
                                img.attr('src', img.data('src'));
                                img.removeAttr('data-src');
                            }

                            observer.unobserve(entry.target);
                        }
                    });
                });

                const lazyImages = $('.lazy-image');
                lazyImages.each(function() {
                    imageObserver.observe(this);
                });
            }

            // Infinite Scroll
            let currentPage = 1;
            let isLoading = false;
            let hasMoreProducts = true;

            const loadMoreProducts = () => {
                if (isLoading || !hasMoreProducts) return;

                isLoading = true;
                $('#loading-spinner').show();

                $.ajax({
                    url: '{{ route('client.home.loadMore') }}',
                    type: 'GET',
                    data: {
                        page: currentPage + 1
                    },
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    success: function(response) {
                        if (response.html && response.html.trim() !== '') {
                            $('#products-container').append(response.html);
                            currentPage++;
                            hasMoreProducts = response.hasMore;

                            if (imageObserver) {
                                const newImages = $('#products-container .lazy-image').not('[data-observed]');
                                newImages.each(function() {
                                    $(this).attr('data-observed', 'true');
                                    imageObserver.observe(this);
                                });
                            }

                            if (!response.hasMore) {
                                $('#load-more-trigger').html(
                                    '<div class="text-center py-8 text-base-content/60">' +
                                    '<i class="las la-check-circle text-4xl"></i><br>' +
                                    'Đã hiển thị tất cả sản phẩm nổi bật' +
                                    '</div>'
                                );
                            }

                            const zone2El = document.getElementById('bg-zone2');
                            // Height recalculation is no longer needed since we use bottom: 0px
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading more products:', error);
                        hasMoreProducts = false;
                    },
                    complete: function() {
                        isLoading = false;
                        $('#loading-spinner').hide();
                    }
                });
            };

            // Infinite scroll
            if ('IntersectionObserver' in window) {
                const scrollObserver = new IntersectionObserver(function(entries) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting && hasMoreProducts) {
                            loadMoreProducts();
                        }
                    });
                }, {
                    rootMargin: '200px'
                });

                const trigger = document.getElementById('load-more-trigger');
                if (trigger) {
                    scrollObserver.observe(trigger);
                }
            }
        });

        function positionBgZones() {
            const navbar   = document.querySelector('.navbar');
            const zone0El  = document.getElementById('bg-zone0');
            const zone1El  = document.getElementById('bg-zone1');
            const zone2El  = document.getElementById('bg-zone2');
            const designSectionHeader = document.getElementById('design-section-header');

            if (!navbar) return;
            const navbarHeight = navbar.getBoundingClientRect().height;

            let sectionTop = window.innerHeight; // Fallback height
            if (designSectionHeader) {
                sectionTop = designSectionHeader.getBoundingClientRect().top + window.scrollY;
            }

            if (zone0El) {
                zone0El.style.top = '-' + navbarHeight + 'px';
                zone0El.style.height = navbarHeight + 'px';
            }

            if (zone1El) {
                zone1El.style.top = '0px';
                zone1El.style.height = sectionTop + 'px';
            }

            if (zone2El) {
                
                zone2El.style.top = (sectionTop - 1) + 'px';
                zone2El.style.bottom = '0px';
                zone2El.style.height = 'auto';
                zone2El.style.WebkitMaskImage = 'none';
                zone2El.style.maskImage = 'none';
            }
        }

        $(window).on('load resize', positionBgZones);
    </script>
    @include('client.partials.search-suggestions')
@endsection
