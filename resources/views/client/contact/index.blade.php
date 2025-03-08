@extends('client.layouts.master')
@section('content')
    <div class="container mx-auto px-4 py-12">
        {{-- Page Header --}}
        <div class="text-center mb-12">
            <h1 class="text-3xl font-bold mb-2">Thông tin liên hệ</h1>
            {{-- <p class="text-base-content/70 max-w-2xl mx-auto">
                Hãy liên hệ với chúng tôi nếu bạn có bất kỳ câu hỏi hoặc yêu cầu nào. Chúng tôi sẽ phản hồi trong thời gian
                sớm nhất.
            </p> --}}
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            {{-- Contact Information --}}
            <div class="flex flex-col gap-8">
                {{-- Info Card --}}
                <div class="card bg-base-100 shadow-lg">
                    <div class="card-body">
                        {{-- <h2 class="card-title text-xl mb-4">Thông tin liên hệ</h2> --}}

                        <div class="space-y-4">
                            <div class="flex items-start gap-4">
                                <div class="bg-primary/10 p-3 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                </div>
                                <a href="https://zalo.me/0908556913" target="_blank" rel="noopener noreferrer"
                                    class="group hover:text-primary transition-colors duration-200">
                                    <h3 class="font-semibold group-hover:underline transition-all duration-200">Zalo: Minh
                                    </h3>
                                    <p
                                        class="text-base-content/70 group-hover:underline group-hover:text-primary transition-all duration-200">
                                        0908.556.913</p>
                                </a>
                            </div>

                            <div class="flex items-start gap-4">
                                <div class="bg-primary/10 p-3 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                </div>
                                <a href="https://zalo.me/0975038534" target="_blank" rel="noopener noreferrer"
                                    class="group hover:text-primary transition-colors duration-200">
                                    <h3 class="font-semibold group-hover:underline transition-all duration-200">Zalo: Phương
                                    </h3>
                                    <p
                                        class="text-base-content/70 group-hover:underline group-hover:text-primary transition-all duration-200">
                                        0975.038.534</p>
                                </a>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Map Card --}}
                {{-- <div class="card bg-base-100 shadow-lg">
                    <div class="card-body p-0 overflow-hidden rounded-box">
                        <div class="aspect-[16/9]">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3920.0444663545394!2d106.69758571474399!3d10.732145992347592!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752f9023a3a85d%3A0xdee5c99a7b02feab!2sNguyen%20Van%20Linh%2C%20Ho%20Chi%20Minh%20City%2C%20Vietnam!5e0!3m2!1sen!2s!4v1647834456950!5m2!1sen!2s"
                                width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
                                class="w-full h-full"></iframe>
                        </div>
                    </div>
                </div> --}}

            </div>
        </div>
    </div>
@endsection
