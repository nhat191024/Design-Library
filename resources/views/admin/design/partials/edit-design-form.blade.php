<section>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <header>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __('Design Edit') }}
                        </h2>
                    </header>

                    <div class="flex gap-6 mt-6">
                        <!-- Left side - Form -->
                        <div class="flex-1">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                                {{ __('Design Information') }}
                            </h3>
                            <form method="POST" action="{{ route('designs.update', $design->id) }}"
                                class="max-w-xl space-y-6">
                                @csrf
                                @method('patch')
                                <div>
                                    <x-input-label for="name" :value="__('Design Name')" />
                                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                        :value="old('name', $design['name'])" required autofocus autocomplete="name" />
                                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                                </div>

                                <div>
                                    <x-input-label for="description" :value="__('Design Description')" />
                                    <x-text-input id="description" name="description" type="text"
                                        class="mt-1 block w-full" :value="old('description', $design['description'])" required autofocus
                                        autocomplete="description" />
                                    <x-input-error class="mt-2" :messages="$errors->get('description')" />
                                </div>

                                <div>
                                    <x-input-label for="category" :value="__('Design Category')" />
                                    <x-select-input name="category" class="select-search mt-1 block w-full" required>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category['id'] }}"
                                                {{ old('category', $design->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </x-select-input>
                                    <x-input-error class="mt-2" :messages="$errors->get('category')" />
                                </div>

                                <div>
                                    <x-input-label for="tag" :value="__('Design Tags')" />
                                    <x-select-input name="tags[]"
                                        class="select-search select-category-multiple mt-1 block w-full" required
                                        multiple="multiple">
                                        @foreach ($tags as $tag)
                                            <option value="{{ $tag->id }}"
                                                {{ in_array($tag->id, old('tags', $designTags)) ? 'selected' : '' }}>
                                                {{ $tag->name }}
                                            </option>
                                        @endforeach
                                    </x-select-input>
                                    <x-input-error class="mt-2" :messages="$errors->get('tags')" />
                                </div>

                                <div>
                                    <x-input-label for="image" :value="__('Design Image')" />
                                    <x-file-input id="image-edit" name="image" class="mt-1 block w-full"
                                        accept="image/*" />
                                    <div id="upload-progress" class="hidden mt-2">
                                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                                            <div class="bg-blue-600 h-2.5 rounded-full" style="width: 0%"></div>
                                        </div>
                                    </div>
                                    <x-input-error class="mt-2" :messages="$errors->get('image')" />
                                </div>

                                <div>
                                    <x-input-label for="main-image" :value="__('Main Image')" />
                                    <div class="mt-1"></div>
                                    <x-select-input name="main_image" id="main-image-select" class="mt-1 block w-full">
                                        @foreach ($design->Images as $image)
                                            <option value="{{ $image->id }}"
                                                id = "main-image-select-{{ $image->id }}"
                                                {{ $design->main_image == $image->id ? 'selected' : '' }}>
                                                {{ "Image $image->id: " . basename($image->url) }}
                                            </option>
                                        @endforeach
                                    </x-select-input>
                                    <x-input-error class="mt-2" :messages="$errors->get('main_image')" />
                                </div>

                                <div>
                                    <x-input-label for="is_showcase" :value="__('Is Design Show On Home Page')" />
                                    <div class="mt-1"></div>
                                    <x-select-input name="is_showcase" class="mt-1 block w-full">
                                        <option value="0"
                                            {{ old('is_showcase', $design->is_showcase) == 0 ? 'selected' : '' }}>
                                            No
                                        </option>
                                        <option value="1"
                                            {{ old('is_showcase', $design->is_showcase) == 1 ? 'selected' : '' }}>
                                            Yes
                                        </option>
                                    </x-select-input>
                                    <x-input-error class="mt-2" :messages="$errors->get('category')" />
                                </div>

                                <div>
                                    <a class="btn btn-error" href="{{ route('designs.index') }}">
                                        Cancel Edit
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        Edit Design
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Right side - Images -->
                        <div class="flex-1">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                                {{ __('Design Images') }}
                            </h3>

                            <!-- Main Image -->
                            <div class="w-full aspect-square mb-4">
                                <img id="mainImage" src="{{ asset($design->images->first()->url) }}"
                                    class="w-full h-full object-contain rounded-lg" alt="Main design image">
                            </div>

                            <!-- Thumbnails -->
                            <div class="flex gap-2 overflow-x-auto pb-2">
                                @foreach ($design->images as $image)
                                    <div class="relative group flex-none" id="image-{{ $image->id }}">
                                        <img class="w-24 h-24 object-cover rounded-lg cursor-pointer hover:ring-1 hover:ring-primary thumbnail-image"
                                            src="{{ asset($image->url) }}" alt="Design thumbnail"
                                            onclick="updateMainImage(this.src)">
                                        <div
                                            class="absolute top-1 right-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <button class="btn btn-error btn-xs btn-circle delete-image"
                                                data-id="{{ $image->id }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    function updateMainImage(src) {
        document.getElementById('mainImage').src = src;
    }
</script>
