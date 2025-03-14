<div class="navbar sticky top-0 z-50 backdrop-blur-md bg-base-100/70 shadow-sm">
    <div class="navbar-start">
        <!-- Mobile dropdown -->
        <div class="dropdown">
            <label tabindex="0" role="button" class="btn btn-ghost lg:hidden">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </label>
            <ul tabindex="0"
                    class="menu menu-sm dropdown-content bg-base-100 rounded-box z-10 mt-3 p-2 shadow max-h-[86vh] overflow-auto w-max"
                    style="flex-flow: column;"
                    >
                <li><a class="font-semibold" href="/">Trang chủ</a></li>
                <li><a class="font-semibold" href="{{ route('client.shop.index') }}">Khám phá</a></li>
                <li><a class="font-semibold" href="{{ route('client.contact.index') }}">Liên hệ</a></li>
                @foreach ($shared_categories as $category)
                    <li>
                        <a class="font-bold" href="{{ route('client.shop.category', ['slug' => $category->slug]) }}">{{ strtoupper($category->name) }}</a>
                        <ul class="p-2">
                            @foreach ($category->Children as $child)
                                <li><a href="{{ route('client.shop.category', ['slug' => $child->slug]) }}">{{ strtoupper($child->name) }}</a></li>
                            @endforeach
                        </ul>
                    </li>
                @endforeach
            </ul>
        </div>
        <!-- Your existing logo link -->
        <a class="btn btn-ghost swap swap-flip w-20 md:w-24 h-20 md:h-24 px-0" href="/">
            <img src="{{ asset('images/logos/logo-nav.png') }}" alt="Logo" class="absolute h-18 w-18 md:h-24 md:w-24 lg:h-24 lg:w-24 xl:h-24 xl:w-24 text-primary swap-off">
            <img src="{{ asset('images/logos/logo-footer.png') }}" alt="Logo" class="absolute h-18 w-18 md:h-24 md:w-24 lg:h-24 lg:w-24 xl:h-24 xl:w-24 text-primary swap-on">
        </a>
        <a class="btn btn-ghost px-0" href="/">
            <span class="ml-1 font-bold text-xl hidden md:block">THIETKEDECOR.VN</span>
        </a>
    </div>

    <div class="navbar-center hidden lg:flex">
        <ul class="menu menu-horizontal px-1 text-base dropdown-hover-container">
            <li><a href="/">TRANG CHỦ</a></li>
            <li><a href="{{ route('client.shop.index') }}">KHÁM PHÁ</a></li>
            @foreach ($shared_categories as $shared_category)
            <li class="dropdown-hover">
                <details class="dropdown">
                    <summary class="mx-0">{{ strtoupper($shared_category->name) }}</summary>
                    <ul class="p-2 shadow menu dropdown-content z-[1] bg-base-100 rounded-box w-52" style="margin-top:0;">
                            <li><a class="font-bold text-lg" href="{{ route('client.shop.category', ['slug' => $shared_category->slug]) }}">{{ strtoupper($shared_category->name) }}</a></li>
                            @foreach ($shared_category->Children as $category)
                                <li><a href="{{ route('client.shop.category', ['slug' => $category->slug]) }}">{{ strtoupper($category->name) }}</a></li>
                            @endforeach
                        </ul>
                    </details>
                </li>
            @endforeach

            {{-- Dropdown for more categories --}}
            {{-- @if ($shared_categories->count() > 3)
            <li class="dropdown-hover">
                <details class="dropdown">
                    <summary class="mx-0">KHÁC</summary>
                    <ul class="p-2 shadow menu dropdown-content z-[1] bg-base-100 rounded-box w-52" style="margin-top: 0;">
                        @foreach ($shared_categories->skip(3) as $category)
                            <li><a href="{{ route('client.shop.category', ['slug' => $category->slug]) }}">{{ strtoupper($category->name) }}</a></li>
                        @endforeach
                    </ul>
                </details>
            </li>
            @endif --}}
        </ul>
    </div>

    <div class="navbar-end">
        <label class="toggle text-base-content">
            <input type="checkbox" value="dark" class="theme-controller hidden">

            <svg aria-label="sun" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><g stroke-linejoin="round" stroke-linecap="round" stroke-width="2" fill="none" stroke="currentColor"><circle cx="12" cy="12" r="4"></circle><path d="M12 2v2"></path><path d="M12 20v2"></path><path d="m4.93 4.93 1.41 1.41"></path><path d="m17.66 17.66 1.41 1.41"></path><path d="M2 12h2"></path><path d="M20 12h2"></path><path d="m6.34 17.66-1.41 1.41"></path><path d="m19.07 4.93-1.41 1.41"></path></g></svg>

            <svg aria-label="moon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><g stroke-linejoin="round" stroke-linecap="round" stroke-width="2" fill="none" stroke="currentColor"><path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"></path></g></svg>

        </label>
        <ul class="menu menu-horizontal px-1 text-lg font-bold">
            <li><a href="{{ route('client.contact.index') }}">LIÊN HỆ</a></li>
        </ul>
    </div>
</div>
<script>
    $(document).ready(function () {
        const themeToggle = $(".theme-controller");
        let currentTheme = localStorage.getItem("theme") || "light";
        $("html").attr("data-theme", currentTheme);
        themeToggle.prop("checked", currentTheme === "dark");
        themeToggle.on("change", function () {
            const newTheme = themeToggle.is(":checked") ? "dark" : "light";
            $("html").attr("data-theme", newTheme);
            localStorage.setItem("theme", newTheme);
            flipLogo();
        });

        initLogo();

        function flipLogo() {
            const logo = $(".swap");
            if (localStorage.getItem("theme") === "light") {
                if (!logo.hasClass("swap-active")) {
                    logo.addClass("swap-active");
                }
            } else {
                if (logo.hasClass("swap-active")) {
                    logo.removeClass("swap-active");
                }
            }
        }

        function initLogo() {
            const logo = $(".swap");
            if (localStorage.getItem("theme") === "light") {
                if (!logo.hasClass("swap-active")) {
                    logo.addClass("swap-active");
                }
            } else {
                if (logo.hasClass("swap-active")) {
                    logo.removeClass("swap-active");
                }
            }
        }
    });
    $(document).ready(function() {
        const $dropdowns = $('.dropdown-hover');

        $dropdowns.each(function() {
            const $dropdown = $(this);
            const $details = $dropdown.find('details');

            $dropdown.on('mouseenter', function() {
                $details.attr('open', true);
            });

            $dropdown.on('mouseleave', function() {
                if (!$details.hasClass('clicked')) {
                    $details.removeAttr('open');
                }
            });
        });

        $dropdowns.each(function() {
            const $dropdown = $(this);
            const $details = $dropdown.find('details');
            const $summary = $dropdown.find('summary');

            $summary.on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                $dropdowns.not($dropdown).each(function() {
                    $(this).find('details').removeAttr('open').removeClass('clicked');
                });

                if ($details.attr('open') && $details.hasClass('clicked')) {
                    $details.removeAttr('open').removeClass('clicked');
                } else {
                    $details.attr('open', true).addClass('clicked');
                }
            });
        });

        $dropdowns.find('.dropdown-content').on('click', function(e) {
            e.stopPropagation();
        });

        $(document).on('click touchend', function(e) {
            if ($(e.target).closest('.dropdown-hover').length === 0) {
                $dropdowns.each(function() {
                    const $details = $(this).find('details');
                    $details.removeAttr('open');
                    $details.removeClass('clicked');
                });
            }
        });

        $('details.dropdown').on('click', function(e) {
            e.preventDefault();
        });
    });
</script>
