<div x-data="{
        swiper: null,
        init() {
            if (window.Swiper) {
                this.initSwiper();
            } else {
                // Dynamically load CSS
                if (!document.querySelector('link[href*=\'swiper-bundle.min.css\']')) {
                    const link = document.createElement('link');
                    link.rel = 'stylesheet';
                    link.href = 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css';
                    document.head.appendChild(link);
                }

                // Dynamically load JS
                const script = document.createElement('script');
                script.src = 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js';
                script.onload = () => {
                    this.initSwiper();
                };
                document.head.appendChild(script);
            }
        },
        initSwiper() {
            this.swiper = new Swiper(this.$refs.swiper, {
                loop: false,
                spaceBetween: 20,
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                on: {
                    slideChange: function () {
                        if (Alpine.store('gallery')) {
                            Alpine.store('gallery').activeIndex = this.realIndex;
                        }
                    },
                },
            });
        }
    }"
    class="relative w-full h-[550px]"
>
    <style>
        .swiper-button-next, .swiper-button-prev {
            color: #4f46e5; /* Primary-600 */
            background-color: rgba(255, 255, 255, 0.8);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .swiper-button-next:after, .swiper-button-prev:after {
            font-size: 18px;
            font-weight: bold;
        }
        .swiper-pagination-bullet-active {
            background-color: #4f46e5;
        }
    </style>

    @php
        $imageUrls = collect($record->attachments ?? [])->map(fn($path) => \Illuminate\Support\Facades\Storage::url($path))->values()->all();
    @endphp

    @if(count($imageUrls) > 0)
        <div class="swiper w-full h-full rounded-lg" x-ref="swiper"
             x-init="
                Alpine.store('gallery', {
                    activeIndex: 0,
                    urls: {{ json_encode($imageUrls) }}
                });
             "
        >
            <div class="swiper-wrapper">
                @foreach($record->attachments as $index => $attachment)
                    <div class="swiper-slide flex items-center justify-center bg-gray-50 dark:bg-gray-900">
                        <!-- Image Container -->
                        <div class="w-full h-full flex items-center justify-center overflow-hidden p-2">
                            @php
                                $extension = strtolower(pathinfo($attachment, PATHINFO_EXTENSION));
                                $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp']);
                                $url = \Illuminate\Support\Facades\Storage::url($attachment);
                                $filename = basename($attachment);
                            @endphp

                            @if($isImage)
                                <img src="{{ $url }}" 
                                     alt="Arte {{ $index + 1 }}" 
                                     class="max-w-full max-h-full object-contain rounded drop-shadow-sm">
                            @else
                                <div class="flex flex-col items-center justify-center space-y-4 text-center">
                                    {{-- Heroicon: Document --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-24 h-24 text-gray-400">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                    </svg>
                                    
                                    <div class="flex flex-col">
                                        <span class="text-lg font-medium text-gray-700 dark:text-gray-200">{{ $filename }}</span>
                                        <span class="text-sm text-gray-500 uppercase">{{ $extension }}</span>
                                    </div>

                                    <a href="{{ $url }}" download class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 border border-gray-300 text-gray-900 font-semibold rounded-lg shadow-sm hover:bg-gray-200 transition-colors focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">
                                        {{-- Heroicon: Arrow Down Tray --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-700">
                                          <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                        </svg>
                                        Descargar
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Navigation Buttons -->
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
            
            <!-- Pagination -->
            <div class="swiper-pagination"></div>
        </div>
    @else
        <div class="flex items-center justify-center h-full border border-dashed border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-900">
            <div class="text-center text-gray-500">
                <p class="text-lg">No hay imágenes adjuntas</p>
            </div>
        </div>
    @endif
</div>
