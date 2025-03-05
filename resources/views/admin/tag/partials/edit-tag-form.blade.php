<section>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <header>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __('Tag Edit') }}
                        </h2>
                    </header>

                    <form method="POST" action="{{ route('tags.update', $tag->id) }}" class="max-w-xl space-y-6">
                        @csrf
                        @method('patch')
                        <div>
                            <x-input-label for="name" :value="__('Tag Name')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                :value="old('name', $tag['name'])" required autofocus autocomplete="name" />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div>
                            <a class="btn btn-error" href="{{ route('tags.index') }}">
                                Cancel Edit
                            </a>
                            <button type="submit" class="btn btn-primary">
                                Edit Tag
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
