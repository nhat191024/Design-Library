<footer class="footer sm:footer-horizontal bg-base-300 text-base-content p-10">
    <nav>
        <h6 class="footer-title">Về chúng tôi</h6>
        <p class="link link-hover">Thietkedecor.vn là đối tác hàng đầu của các doanh nghiệp, đơn vị tổ chức sự kiện.</p>
        <p class="link link-hover">Cập nhật liên tục, đáp ứng mọi yêu cầu của khách hàng trong lĩnh vực thiết kế decor.</p>
        <p class="link link-hover">Giúp khách hàng tối ưu chi phí và tiết kiệm thời gian. Tạo điều kiện cho các đơn vị decor sử dụng hình ảnh tư vấn khách và nhanh chóng chốt đơn.</p>
        <p class="link link-hover">Cam kết về chất lượng sản phẩm.</p>
    </nav>
    <nav>
        <h6 class="footer-title">Phương thức liên hệ</h6>
        <div class="flex flex-col">
            <a href="{{ route('client.contact.index') }}" class="link link-hover">Call/Zalo: 0908.556.913 (Minh)</a>
            <a href="{{ route('client.contact.index') }}" class="link link-hover">Call/Zalo: 0975.038.534 (Phương)</a>
        </div>
        <h6 class="footer-title mt-4">Cộng đồng</h6>
        <div class="grid grid-flow-col gap-4">
            <a href="{{ route('client.contact.index') }}" class="link link-hover">Facebook</a>
            <a href="{{ route('client.contact.index') }}" class="link link-hover">Zalo</a>
            <a href="{{ route('client.contact.index') }}" class="link link-hover">Pinterest</a>
        </div>
    </nav>
    <nav>
        <h6 class="footer-title">Danh mục</h6>
        @foreach ($shared_categories->take(8) as $shared_category)
            <a href="{{ route('client.shop.category', ['slug' => $shared_category->slug]) }}" class="link link-hover">- {{ $shared_category->name }}</a>
        @endforeach
    </nav>
    <nav>
        <h6 class="footer-title">Dịch vụ</h6>
        <p class="link link-hover">Nhận thiết kế & thương mại file Event, Birthday, Wedding,...</p>
        <p class="link link-hover">Cung cấp nguồn tài nguyên thiết kế đa dạng, chất lượng.</p>
        <p class="link link-hover">Tìm kiếm tài nguyên theo yêu cầu, Tải tài nguyên từ các trang web như: Etsy, Freepik, PNGtreeShutterstock, Istock, Pikbest, Adobe Stock,...</p>
    </nav>
</footer>
