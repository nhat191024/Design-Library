<section>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <header>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __('Edit New Category') }}
                        </h2>
                    </header>

                    <div class="flex gap-6 mt-6">
                        <!-- Left side - Form -->
                        <div class="flex-1">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                                {{ __('Category Information') }}
                            </h3>
                            <form method="POST" action="{{ route('categories.update', $category->id) }}"
                                class="max-w-xl space-y-6" enctype="multipart/form-data">
                                @csrf
                                @method('patch')
                                <div>
                                    <x-input-label for="name" :value="__('Category Name')" />
                                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                        :value="old('name', $category->name)" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                                </div>

                                <div>
                                    <x-input-label for="parent_id" :value="__('Parent Category')" />
                                    <div class="mt-1"></div>
                                    <x-select-input name="parent_id" class="select-search mt-1 block w-full">
                                        <option value="0">None</option>
                                        @foreach ($categories as $item)
                                            <option value="{{ $item->id }}"
                                                {{ old('parent_id', $category->parent_id) == $item->id ? 'selected' : '' }}>
                                                {{ $item->name }}</option>
                                        @endforeach
                                    </x-select-input>
                                    <x-input-error class="mt-2" :messages="$errors->get('parent_id')" />
                                </div>

                                <div>
                                    <x-input-label for="is_show" :value="__('Is show on nav')" />
                                    <div class="mt-1"></div>
                                    <x-select-input id="is_show" name="is_show" class="mt-1 block w-full" required>
                                        <option value="1"
                                            {{ old('is_show', $category->is_show) == 1 ? 'selected' : '' }}>
                                            Yes
                                        </option>
                                        <option value="0"
                                            {{ old('is_show', $category->is_show) == 0 ? 'selected' : '' }}>
                                            No
                                        </option>
                                    </x-select-input>
                                    <x-input-error class="mt-2" :messages="$errors->get('is_show')" />
                                </div>

                                <div>
                                    <x-input-label for="image" :value="__('Category Images')" />
                                    <x-file-input id="image" type="file" name="image" class="mt-1 block w-full"
                                        accept="image/*" :value="old('image', $category->image)" />
                                    <x-input-error class="mt-2" :messages="$errors->get('image')" />
                                </div>

                                <div>
                                    <a class="btn btn-error" type="button" href="{{ route('categories.index') }}">
                                        Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        Edit category
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Right side - Image Preview -->
                        <div class="flex-1">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                                {{ __('Category Images Preview') }}
                            </h3>

                            <!-- Main Image Preview -->
                            <div class="w-full aspect-square mb-4">
                                <img id="mainImage" src="{{ asset($category->image) }}"
                                    class="w-full h-full object-contain rounded-lg" alt="Category image">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
