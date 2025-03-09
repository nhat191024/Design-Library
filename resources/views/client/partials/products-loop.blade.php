@forelse ($products as $product)
<div class="card bg-base-100 shadow-sm group relative overflow-hidden cursor-pointer" onclick="handleClick(event, this, '{{ route('client.product.detail', ['slug' => $product->slug]) }}')">
    <figure class="relative aspect-[4/3]">
        <img
            src="{{ asset($product->Images[0]->url ?? '/images/designs/placeholder.jpg') }}"
            onerror="this.onerror=null;this.src='{{ asset('/images/designs/placeholder.jpg') }}';"
            alt="{{ $product->name }}"
            class="w-full h-full object-cover" />
        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
    </figure>
    <div class="card-body absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-300 text-white flex flex-col justify-center p-4 overflow-hidden">
        {{-- Description --}}
        <div class="overflow-y-scroll">
            <p class="text-[0.65rem] sm:text-[0.75rem] md:text-[0.85rem]">
                <b>{{ Str::limit($product->name, 80) }}</b> <br> {{ Str::limit($product->description, 150) }}
            </p>
        </div>

        <div class="card-actions justify-end mt-auto pt-2">
            {{-- <a href="{{ route('client.product.detail', ['slug' => $product->slug]) }}" class="btn btn-outline btn-sm md:btn-md">
                Xem chi tiết
            </a> --}}
        </div>
    </div>
</div>
@empty
<div class="col-span-full text-center text-gray-400 py-12">
    Không có sản phẩm nào để hiển thị.
</div>
@endforelse
@once
<script>
    // handle mobile user clicks
    $(document).on("touchend", ".product-card", function (e) {
        handleClick(e, this);
    });

    function handleClick(event, element, link) {
        if (event.type === "touchend") {
            event.preventDefault();
        }
        console.log('going to: '+ link);
        window.location.href = link;
    }
</script>
@endonce
