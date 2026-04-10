@php
    $footer = $data['footer'] ?? [];
    $top =
        get_theme_option('footer_top' . active_language()) ?:
        get_theme_option('footer_topen') ?? get_theme_option('footer_top' . app('default_language'));

    $socials = get_theme_option(key: 'socials', parent_key: 'social') ?? [];
    $menus = $data['menus'] ?? get_menus();
    $childMenus = $menus['course_bundle']['childs'] ?? [];
@endphp

<div class="pt-16 lg:pt-24 pb-10 lg:pb-12 border-t-[8px] border-primary bg-[url('../../assets/images/footer/footer-bg-line.png')] bg-no-repeat bg-center relative z-[1]">
    <div class="container divide-y divide-white/10">
        <x-dynamic-component component="theme::footer.top" />
        <div class="grid grid-cols-12 items-center gap-7 mt-6 pt-6">
            <div class="col-span-full lg:col-span-6">
                <div class="area-description !text-white/70 max-w-[320px]">
                    {{ $top['one_title'] ?? '' }}
                </div>
                @if ($socials)
                    <x-theme::social.social-list-one :socials="$socials" ul-class="flex items-center gap-2 mt-5"
                        item-class="flex-center size-10 rounded-50 border border-white/15 !text-white [&_*]:!text-white hover:bg-primary hover:text-white hover:-translate-y-1 shadow-sm custom-transition" />
                @endif
            </div>
            <div class="col-span-full lg:col-span-6">
                <nav class="flex-center !justify-start lg:!justify-end">
                    <ul class="flex items-center gap-x-5 gap-y-2 flex-wrap leading-none !text-white font-bold">

                        <li class="flex-center">
                            <a href="https://zenvycoaching.com/terms-conditions"
                                class="inline-block px-2 py-3 !text-white/80 hover:!text-white hover:text-primary hover:-translate-y-0.5 custom-transition"
                                aria-label="Terms condition">
                                Terms condition
                            </a>
                        </li>
                        <li class="flex-center">
                            <a href="https://zenvycoaching.com/privacy-policy"
                                class="inline-block px-2 py-3 !text-white/80 hover:!text-white hover:text-primary hover:-translate-y-0.5 custom-transition"
                                aria-label="Privacy Policy">
                                Privacy Policy
                            </a>
                        </li>
                        <li class="flex-center">
                            <a href="https://zenvycoaching.com/refund-policy"
                                class="inline-block px-2 py-3 !text-white/80 hover:!text-white hover:text-primary hover:-translate-y-0.5 custom-transition"
                                aria-label="Refund Policy">
                                Refund Policy
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- POSITIONAL ELEMENT -->
<ul>
    <!-- TOP LEFT -->
    <li class="block size-[29vw] rounded-50 bg-[#D2EB1A]/30 blur-[180px] absolute top-0 xl:-top-20 left-0 xl:-left-20 z-0"></li>
    <!-- TOP RIGHT -->
    <li class="block size-[29vw] rounded-50 bg-[#B326F4]/30 blur-[180px] absolute top-0 xl:-top-20 right-0 xl:-right-20 z-0"></li>
</ul>
