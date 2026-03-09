@extends('client.layouts.master')
@section('content')
    <div class="container mx-auto px-4 py-12">
        {{-- Page Header --}}
        <div class="mb-12 text-center">
            <h1 class="mb-2 text-3xl font-bold">Thông tin liên hệ</h1>
            {{-- <p class="text-base-content/70 max-w-2xl mx-auto">
                Hãy liên hệ với chúng tôi nếu bạn có bất kỳ câu hỏi hoặc yêu cầu nào. Chúng tôi sẽ phản hồi trong thời gian
                sớm nhất.
            </p> --}}
        </div>

        <div class="grid grid-cols-1 gap-12 lg:grid-cols-2">
            {{-- Contact Information --}}
            <div class="flex flex-col gap-8">
                {{-- Info Card --}}
                <div class="card bg-base-100 shadow-lg">
                    <div class="card-body">
                        {{-- <h2 class="card-title text-xl mb-4">Thông tin liên hệ</h2> --}}

                        <div class="space-y-4">
                            <div class="flex items-start gap-4">
                                <div class="bg-primary/10 rounded-full p-3">
                                    <img class="h-6 w-6 text-primary" src="{{ asset('images/logos/zalo.png') }}" alt="Zalo Icon">
                                </div>
                                <a class="group transition-colors duration-200 hover:text-primary" href="https://zalo.me/0908556913" target="_blank" rel="noopener noreferrer">
                                    <h3 class="font-semibold transition-all duration-200 group-hover:underline">Zalo: Minh
                                    </h3>
                                    <p class="text-base-content/70 transition-all duration-200 group-hover:text-primary group-hover:underline">
                                        0908.556.913</p>
                                </a>
                            </div>

                            <div class="flex items-start gap-4">
                                <div class="bg-primary/10 rounded-full p-3">
                                    <img class="h-6 w-6 text-primary" src="{{ asset('images/logos/zalo.png') }}" alt="Zalo Icon">
                                </div>
                                <a class="group transition-colors duration-200 hover:text-primary" href="https://zalo.me/0975038534" target="_blank" rel="noopener noreferrer">
                                    <h3 class="font-semibold transition-all duration-200 group-hover:underline">Zalo: Phương
                                    </h3>
                                    <p class="text-base-content/70 transition-all duration-200 group-hover:text-primary group-hover:underline">
                                        0975.038.534</p>
                                </a>
                            </div>

                            {{-- Zalo Group Link --}}
                            <div class="flex items-start gap-4">
                                <div class="bg-primary/10 rounded-full p-3">
                                    <svg class="h-6 w-6 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <a class="group transition-colors duration-200 hover:text-primary" href="https://zalo.me/g/lgfeqx256" target="_blank" rel="noopener noreferrer">
                                    <h3 class="font-semibold transition-all duration-200 group-hover:underline">Nhóm Zalo
                                    </h3>
                                    <p class="text-base-content/70 transition-all duration-200 group-hover:text-primary group-hover:underline">
                                        Tham gia nhóm Zalo của chúng tôi</p>
                                </a>
                            </div>

                            {{-- Facebook Link --}}
                            <div class="flex items-start gap-4">
                                <div class="bg-primary/10 rounded-full p-3">
                                    <img class="h-6 w-6 text-primary" src="{{ asset('images/logos/facebook.png') }}" alt="Facebook Icon">
                                </div>
                                <a class="group transition-colors duration-200 hover:text-primary" href="https://www.facebook.com/profile.php?id=61574064683251" target="_blank" rel="noopener noreferrer">
                                    <h3 class="font-semibold transition-all duration-200 group-hover:underline">Facebook
                                    </h3>
                                    <p class="text-base-content/70 transition-all duration-200 group-hover:text-primary group-hover:underline">
                                        Theo dõi chúng tôi trên Facebook</p>
                                </a>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
            {{-- Map Card --}}
            <div class="">
                <div class="card h-[90%] bg-base-100 shadow-lg">
                    <div class="card-body overflow-hidden rounded-box p-0">
                        <div class="aspect-[16/9]">
                            <img class="h-[100%] w-full object-cover" src="{{ asset('images/logos/tag_web_tam_thoi.jpg') }}" alt="Placeholder Image" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
