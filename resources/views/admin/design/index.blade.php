<x-admin-layout>
    <x-slot name="style">
        <style>
            #design-table th {
                text-align: center;
            }

            #design-table td {
                text-align: center;
            }
        </style>
    </x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Designs') }}
        </h2>
    </x-slot>

    @include('admin.design.partials.edit-design-form')

    <div class="py-12">
        <div class=" max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @include('admin.design.partials.datatables')
                </div>
            </div>
        </div>
    </div>

    <x-slot name="script">
        <script>
            $(document).ready(function() {
                $('#design-table').DataTable({
                    language: {
                        paginate: {
                            "first": "",
                            "last": "",
                            "next": "Next",
                            "previous": "Previous"
                        },
                    }
                });
            });
        </script>
    </x-slot>
</x-admin-layout>
