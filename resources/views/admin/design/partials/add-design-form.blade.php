<section>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <header>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __('Thêm thiết kế mới') }}
                        </h2>
                    </header>

                    <div class="flex gap-6 mt-6">
                        <!-- Left side - Form -->
                        <div class="flex-1">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                                {{ __('Thông tin thiết kế') }}
                            </h3>
                            <form id="designForm" method="POST" action="{{ route('designs.store') }}"
                                class="max-w-xl space-y-6" enctype="multipart/form-data">
                                @csrf

                                <div>
                                    <x-input-label for="name" :value="__('Tên')" />
                                    <x-text-input id="name" name="name" type="text"
                                        class="mt-1 block w-full" />
                                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                                </div>

                                <div>
                                    <x-input-label for="description" :value="__('Mô tả')" />
                                    <x-text-input id="description" name="description" type="text"
                                        class="mt-1 block w-full" />
                                    <x-input-error class="mt-2" :messages="$errors->get('description')" />
                                </div>

                                <div>
                                    <x-input-label for="category" :value="__('Danh mục')" />
                                    <div class="mt-1"></div>
                                    <x-select-input name="category" class="select-search mt-1 block w-full">
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </x-select-input>
                                    <x-input-error class="mt-2" :messages="$errors->get('category')" />
                                </div>

                                <div>
                                    <x-input-label for="tag" :value="__('Nhãn')" />
                                    <div class="mt-1"></div>
                                    <x-select-input name="tags[]"
                                        class="select-search select-category-multiple mt-1 block w-full"
                                        multiple="multiple">
                                        @foreach ($tags as $tag)
                                            <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                                        @endforeach
                                    </x-select-input>
                                    <x-input-error class="mt-2" :messages="$errors->get('tags')" />
                                </div>

                                <div>
                                    <x-input-label for="image" :value="__('Ảnh')" />
                                    <x-file-input id="image" type="file" class="mt-1 block w-full"
                                        accept="image/*" multiple />
                                    <x-input-error class="mt-2" :messages="$errors->get('images')" />
                                </div>

                                <div>
                                    <x-input-label for="main-image" :value="__('Ảnh chính')" />
                                    <div class="mt-1"></div>
                                    <x-select-input name="main-image" id="main-image-select" class="mt-1 block w-full">
                                        <option value="" disabled>select your option</option>
                                    </x-select-input>
                                    <x-input-error class="mt-2" :messages="$errors->get('main_image')" />
                                </div>

                                <div>
                                    <x-input-label for="is_showcase" :value="__('Hiển thị trên trang chủ?')" />
                                    <div class="mt-1"></div>
                                    <x-select-input name="is_showcase" class="mt-1 block w-full">
                                        <option value="0">Không</option>
                                        <option value="1">Có</option>
                                    </x-select-input>
                                    <x-input-error class="mt-2" :messages="$errors->get('category')" />
                                </div>

                                <div>
                                    <button class="btn btn-error" type="button" onclick="window.history.back()">
                                        Hủy
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        Xác nhận
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Right side - Image Preview -->
                        <div class="flex-1">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                                {{ __('Ảnh thiết kế') }}
                            </h3>

                            <!-- Main Image Preview -->
                            <div class="w-full aspect-square mb-4">
                                <img id="mainImage" src=""
                                    class="w-full h-full object-contain rounded-lg hidden" alt="Main design image">
                            </div>

                            <!-- Thumbnails Preview -->
                            <div id="thumbnailContainer" class="flex gap-2 overflow-x-auto pb-2">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
