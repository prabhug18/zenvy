<div id="offcanvas-menu" class="bg-black/50 fixed size-full inset-0 invisible opacity-0 duration-300 z-[102]">
    <div class="offcanvas-menu-inner absolute top-0 bottom-0 right-0 rtl:right-auto rtl:left-0 flex flex-col py-4 bg-white w-64 sm:w-72 translate-x-full rtl:-translate-x-full duration-300 z-[103]">
        <!-- CLOSE MENU -->
        <button type="button" class="offcanvas-menu-close size-11 flex-center bg-white border border-transparent hover:border-primary absolute top-4 right-full rtl:right-auto rtl:left-full custom-transition">
            <i class="ri-close-line text-gray-500 dark:text-dark-text"></i>
        </button>
        <!-- header search -->
        {{-- <div class="px-4 pr-6 xl:pr-4">
            <x-theme::header.search />
        </div> --}}
        <div class="my-5 px-4 overflow-x-hidden grow">
            <ul class="text-lg leading-none text-heading dark:text-white font-medium">
                <li>
                    <a href="{{ route('home.index') }}" aria-label="Menu link" class="inline-block w-full py-3 hover:text-primary [&.active]:text-primary custom-transition {{ is_active('home.index') }}">
                        {{ translate('Home') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('about.us') }}" aria-label="Menu link" class="inline-block w-full py-3 hover:text-primary [&.active]:text-primary custom-transition {{ is_active('about.us') }}">
                        {{ translate('About Us') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('course.list') }}" aria-label="Menu link" class="inline-block w-full py-3 hover:text-primary [&.active]:text-primary custom-transition">
                        {{ translate('Course') }}
                    </a>
                </li>

                <li>
                    <a href="{{ route('blog.list') }}" aria-label="Menu link" class="inline-block w-full py-3 hover:text-primary [&.active]:text-primary custom-transition">
                        {{ translate('Blogs') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('contact.page') }}" aria-label="Menu link" class="inline-block w-full py-3 hover:text-primary [&.active]:text-primary custom-transition">
                        {{ translate('Contact') }}
                    </a>
                </li>
            </ul>
        </div>

    </div>
</div>
