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
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($attachment) }}" 
                                 alt="Arte {{ $index + 1 }}" 
                                 class="max-w-full max-h-full object-contain rounded drop-shadow-sm">
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
