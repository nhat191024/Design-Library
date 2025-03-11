<section>
    <div class="flex flex-row justify-between items-center">

        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Thông tin liên hệ') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Quản lý thông tin liên hệ của bạn') }}
            </p>
        </header>

        <div class="mt-6 flex justify-end">
            <x-secondary-button onclick="my_modal_1.showModal()">
                {{ __('Thêm liên hệ') }}
            </x-secondary-button>
        </div>

    </div>

    <div class="grid grid-cols-2 gap-4">
        @foreach ($contacts as $key => $contact)
            <form method="post" action="{{ route('profile.update-contact', $contact->id) }}" class="mt-6 space-y-6">
                @csrf
                @method('PATCH')

                <div>
                    <p class="mt-1- mb-2 text-md text-white">
                        {{ __('Liên hệ') }} {{ 1 + $key }}
                    </p>

                    <div>
                        <x-input-label for="contact-name" :value="__('Tên')" />
                        <x-text-input id="contact-name" name="contact_name" type="text" class="mt-1 block w-full"
                            :value="$contact->name" autofocus />
                        <x-input-error class="mt-2" :messages="$errors->get('contact_name')" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="contact-phone" :value="__('Số điện thoại')" />
                        <x-text-input id="contact-phone" name="contact_phone" type="text" class="mt-1 block w-full"
                            :value="$contact->phone" autofocus />
                        <x-input-error class="mt-2" :messages="$errors->get('contact_phone')" />
                    </div>
                </div>

                <div class="flex flex-row gap-2">
                    <x-primary-button>{{ __('Lưu') }}</x-primary-button>
                    <x-danger-button class="btn btn-error"
                        onclick="event.preventDefault(); if (confirm('Are you sure you want to delete contact {{ $contact->name }} ?')) { window.location.href = '{{ route('profile.delete-contact', $contact->id) }}'; }">
                        {{ __('Xóa') }}
                    </x-danger-button>
                </div>
            </form>
        @endforeach
    </div>

    <!-- add contact modal -->
    <dialog id="my_modal_1" class="modal">
        <div class="modal-box">
            <h3 class="text-lg font-bold">{{ __('Add New Contact Information') }}</h3>
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            </form>

            <form method="POST" action="{{ route('profile.add-contact') }}" class="space-y-6 mt-6">
                @csrf

                <div>
                    <x-input-label for="contact-name" :value="__('Tên')" />
                    <x-text-input id="contact-name" name="contact_name" type="text" class="mt-1 block w-full"
                        required autofocus />
                    <x-input-error class="mt-2" :messages="$errors->get('contact_name')" />
                </div>

                <div>
                    <x-input-label for="contact-phone" :value="__('Số điện thoại')" />
                    <x-text-input id="contact-phone" name="contact_phone" type="text" class="mt-1 block w-full"
                        required autofocus />
                    <x-input-error class="mt-2" :messages="$errors->get('contact_phone')" />
                </div>

                <div class="modal-action flex items-center gap-4">
                    <x-primary-button>{{ __('Lưu') }}</x-primary-button>
                </div>

            </form>

        </div>
    </dialog>
</section>
