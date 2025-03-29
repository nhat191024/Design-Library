<section>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <header>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __('Cập nhật nhãn') }}
                        </h2>
                    </header>

                    <form method="POST" action="{{ route('tags.update', $tag->id) }}" class="max-w-xl space-y-6">
                        @csrf
                        @method('patch')
                        <div>
                            <x-input-label for="name" :value="__('Tên nhãn')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                :value="old('name', $tag['name'])" required autofocus autocomplete="name" />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>
                        
                        <div>
                            <x-input-label for="isShow" :value="__('Hiển thị')" />
                            <select id="isShow" name="isShow" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-500 focus:border-indigo-500
                                       dark:bg-gray-800 dark:text-white dark:border-gray-600">
                                <option value="1" {{ old('isShow', $tag->is_show) == 1 ? 'selected' : '' }}>Có</option>
                                <option value="0" {{ old('isShow', $tag->is_show) == 0 ? 'selected' : '' }}>Không</option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('isShow')" />
                        </div>

                        <div>
                            <a class="btn btn-error" href="{{ route('tags.index') }}">
                                Hủy
                            </a>
                            <button type="submit" class="btn btn-primary">
                                Xác nhận
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
