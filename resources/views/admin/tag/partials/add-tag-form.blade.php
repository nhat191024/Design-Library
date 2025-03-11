<section>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <header>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __('Thêm nhãn mới') }}
                        </h2>
                    </header>

                    <form method="POST" action="{{ route('tags.store') }}" class="max-w-xl space-y-6">
                        @csrf

                        <div>
                            <x-input-label for="name" :value="__('Tên')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
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
