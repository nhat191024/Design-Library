<div class="navbar bg-base-100 shadow-sm sticky top-0 z-50">
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
                    class="menu menu-sm dropdown-content bg-base-100 rounded-box z-10 mt-3 w-52 p-2 shadow">
                <li><a href="/">Trang chủ</a></li>
                <li><a href="{{ route('client.shop.index') }}">Khám phá</a></li>
            </ul>
        </div>
        <!-- Logo -->
        <a class="btn btn-ghost flex items-center" href="/">
            <svg width="32" height="32" viewBox="0 0 415 415" xmlns="http://www.w3.org/2000/svg"><rect x="82.5" y="290" width="250" height="125" rx="62.5" fill="#1AD1A5"></rect><circle cx="207.5" cy="135" r="130" fill="black" fill-opacity=".3"></circle><circle cx="207.5" cy="135" r="125" fill="white"></circle><circle cx="207.5" cy="135" r="56" fill="#FF9903"></circle></svg>
            <span class="ml-1">designSC</span>
        </a>
    </div>

    <div class="navbar-center hidden lg:flex">
        <ul class="menu menu-horizontal px-1">
            <li><a href="/">Trang chủ</a></li>
            <li><a href="{{ route('client.shop.index') }}">Khám phá</a></li>
            @foreach ($shared_categories as $shared_category)
                <li><a href="{{ route('client.shop.category', ['slug' => $shared_category->slug]) }}">{{ $shared_category->name }}</a></li>
            @endforeach

        </ul>
    </div>

    <div class="navbar-end">
        <ul class="menu menu-horizontal px-1">
            <li><a href="/">Liên hệ</a></li>
        </ul>
    </div>
</div>
