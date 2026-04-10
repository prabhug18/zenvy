@php
    $bottom =
        get_theme_option('footer_bottom' . active_language()) ?:
        get_theme_option('footer_bottomen') ?? get_theme_option('footer_bottom' . app('default_language'));
@endphp


@if (isset($bottom['status']))
    <style>
        .footer-bottom-text * {
            color: rgba(255, 255, 255, 0.8) !important;
        }
        .footer-bottom-links a {
            color: rgba(255, 255, 255, 0.8) !important;
        }
        .footer-bottom-links a:hover {
            color: white !important;
        }
    </style>
    <div class="container relative z-[2]">
        <div class="bg-white/5 backdrop-blur-md px-4 py-5 border border-white/10 border-b-0 rounded-t-xl shadow-sm">
            <div class="grid grid-cols-12 gap-7">
                <div class="col-span-full lg:col-span-6">
                    <div class="text-center lg:text-left footer-bottom-text">
                        <div class="text-sm !leading-none font-bold text-white/80">
                            {!! clean($bottom['copy_right'] ?? '') !!}
                        </div>
                    </div>
                </div>
                <div class="col-span-full lg:col-span-6">
                    <div class="text-center lg:text-left footer-bottom-text footer-bottom-links">
                        <div
                            class="flex items-center justify-center lg:justify-end space-x-5 divide-x divide-white/20 [&>:not(:first-child)]:pl-5 grow text-sm font-semibold text-white/80 transition-transform">
                            {!! clean($bottom['menu'] ?? '') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
