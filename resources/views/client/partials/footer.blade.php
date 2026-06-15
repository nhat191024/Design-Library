<footer class="footer bg-base-300 p-10 text-base-content sm:footer-horizontal relative z-10">
    <div class="container mx-auto grid w-full grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4">
        <nav class="flex flex-col">
            <h6 class="footer-title">Về chúng tôi</h6>
            <p class="link link-hover">Thietkedecor.vn là đối tác hàng đầu của các doanh nghiệp, đơn vị tổ chức sự kiện.
            </p>
            <p class="link link-hover">Cập nhật liên tục, đáp ứng mọi yêu cầu của khách hàng trong lĩnh vực thiết kế
                decor.</p>
            <p class="link link-hover">Giúp khách hàng tối ưu chi phí và tiết kiệm thời gian</p>
            <p class="link link-hover">Cam kết về chất lượng sản phẩm.</p>
        </nav>
        <nav class="flex flex-col">
            <h6 class="footer-title">Phương thức liên hệ</h6>
            <div class="flex flex-col">
                <a class="link link-hover" href="{{ route('client.contact.index') }}">Zalo/Call: 0908556913 (Minh)</a>
                <a class="link link-hover" href="{{ route('client.contact.index') }}">Zalo/Call: 0975038534 (Phương)</a>
            </div>
            <h6 class="footer-title mt-4">Cộng đồng</h6>
            <div class="grid grid-flow-col gap-4">
                <a class="link link-hover" href="{{ route('client.contact.index') }}">Facebook</a>
                <a class="link link-hover" href="{{ route('client.contact.index') }}">Zalo</a>
                <a class="link link-hover" href="{{ route('client.contact.index') }}">Pinterest</a>
            </div>
        </nav>
        <nav class="flex flex-col">
            <h6 class="footer-title">Danh mục thiết kế</h6>
            @foreach ($shared_categories->take(8) as $shared_category)
                <a class="link link-hover" href="{{ route('client.shop.category', ['slug' => $shared_category->slug]) }}">- {{ $shared_category->name }}</a>
            @endforeach
        </nav>
        <nav class="flex flex-col">
            <h6 class="footer-title">Dịch vụ</h6>
            <p class="link link-hover">Nhận thiết kế & thương mại file Event, Birthday, Wedding,...</p>
            <p class="link link-hover">Cung cấp nguồn tài nguyên thiết kế đa dạng, chất lượng.</p>
            <p class="link link-hover">Tìm kiếm tài nguyên theo yêu cầu, Tải tài nguyên từ các trang web như: Etsy,
                Freepik, PNGtree, Shutterstock, Istock, Pikbest, Adobe Stock,...</p>
        </nav>
    </div>
</footer>
