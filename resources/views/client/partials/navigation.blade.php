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
                    class="menu menu-sm dropdown-content bg-base-100 rounded-box z-10 mt-3 w-52 p-2 shadow">
                <li><a href="/">Trang chủ</a></li>
                <li><a href="{{ route('client.shop.index') }}">Khám phá</a></li>
            </ul>
        </div>
        <!-- Logo -->
        <a class="btn btn-ghost flex items-center" href="/">
            <svg width="42" height="42" viewBox="0 0 415 415" xmlns="http://www.w3.org/2000/svg"><rect x="82.5" y="290" width="250" height="125" rx="62.5" fill="#1AD1A5"></rect><circle cx="207.5" cy="135" r="130" fill="black" fill-opacity=".3"></circle><circle cx="207.5" cy="135" r="125" fill="white"></circle><circle cx="207.5" cy="135" r="56" fill="#FF9903"></circle></svg>
            <span class="ml-1 font-bold text-2xl">designSC</span>
        </a>
    </div>

    <div class="navbar-center hidden lg:flex">
        <ul class="menu menu-horizontal px-1 text-lg">
            <li><a href="/">TRANG CHỦ</a></li>
            <li><a href="{{ route('client.shop.index') }}">KHÁM PHÁ</a></li>
            @foreach ($shared_categories as $shared_category)
                <li><a href="{{ route('client.shop.category', ['slug' => $shared_category->slug]) }}">{{ strtoupper($shared_category->name) }}</a></li>
            @endforeach
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
        });
    });
</script>
