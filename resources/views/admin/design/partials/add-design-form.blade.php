<section>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <header>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __('Add New Design') }}
                        </h2>
                    </header>

                    <div class="flex gap-6 mt-6">
                        <!-- Left side - Form -->
                        <div class="flex-1">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                                {{ __('Design Information') }}
                            </h3>
                            <form id="designForm" method="POST" action="{{ route('designs.store') }}"
                                class="max-w-xl space-y-6" enctype="multipart/form-data">
                                @csrf

                                <div>
                                    <x-input-label for="name" :value="__('Design Name')" />
                                    <x-text-input id="name" name="name" type="text"
                                        class="mt-1 block w-full" />
                                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                                </div>

                                <div>
                                    <x-input-label for="description" :value="__('Design Description')" />
                                    <x-text-input id="description" name="description" type="text"
                                        class="mt-1 block w-full" />
                                    <x-input-error class="mt-2" :messages="$errors->get('description')" />
                                </div>

                                <div>
                                    <x-input-label for="category" :value="__('Design Category')" />
                                    <div class="mt-1"></div>
                                    <x-select-input name="category" class="mt-1 block w-full">
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </x-select-input>
                                    <x-input-error class="mt-2" :messages="$errors->get('category')" />
                                </div>

                                <div>
                                    <x-input-label for="tag" :value="__('Design Tags')" />
                                    <div class="mt-1"></div>
                                    <x-select-input name="tags[]" class="select-category-multiple mt-1 block w-full"
                                        multiple="multiple">
                                        @foreach ($tags as $tag)
                                            <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                                        @endforeach
                                    </x-select-input>
                                    <x-input-error class="mt-2" :messages="$errors->get('tags')" />
                                </div>

                                <div>
                                    <x-input-label for="image" :value="__('Design Images')" />
                                    <x-file-input id="image" type="file" class="mt-1 block w-full"
                                        accept="image/*" multiple />
                                    <x-input-error class="mt-2" :messages="$errors->get('images')" />
                                </div>

                                <div>
                                    <x-input-label for="main-image" :value="__('Main Image')" />
                                    <div class="mt-1"></div>
                                    <x-select-input name="main-image" id="main-image-select" class="mt-1 block w-full">
                                    </x-select-input>
                                    <x-input-error class="mt-2" :messages="$errors->get('main_image')" />
                                </div>

                                <div>
                                    <button class="btn btn-error" type="button" onclick="window.history.back()">
                                        Cancel
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        Add Design
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Right side - Image Preview -->
                        <div class="flex-1">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                                {{ __('Design Images Preview') }}
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
