<x-dashboard-layout>
    <x-slot:title> {{ translate('My Certificate') }} </x-slot:title>
    <!-- BREADCRUMB -->
    <x-portal::admin.breadcrumb title="My All Certificate" page-to="Certificate">
        <button type="button" data-modal-target="certificate-preview-modal" 
                data-modal-toggle="certificate-preview-modal"
                class="btn b-solid btn-primary-solid dk-theme-card-square flex items-center gap-2">
            <i class="ri-eye-line"></i>
            {{ translate('Preview Certificate') }}
        </button>
    </x-portal::admin.breadcrumb>

    <!-- Start Main Content -->
    <div class="card overflow-hidden">
        @if ($certificates->count() > 0)
            <x-portal::certificates.certificate-list :certificates=$certificates />
        @else
            <x-portal::admin.empty-card title="You have no Certificate" />
        @endif
    </div>

    <!-- Certificate Preview Modal -->
    <div id="certificate-preview-modal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed inset-0 z-modal flex-center w-full h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-5xl max-h-full">
            <div class="relative bg-white rounded-lg shadow-lg dark:bg-dark-card-shade dk-theme-card-square">
                <!-- Header -->
                <div class="flex items-center justify-between p-5 border-b dark:border-dark-border-three">
                    <h3 class="text-xl font-semibold text-heading dark:text-white">
                        {{ translate('Certificate Preview') }}
                    </h3>
                    <button type="button" data-modal-hide="certificate-preview-modal"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                        <i class="ri-close-line text-xl"></i>
                    </button>
                </div>
                <!-- Body: Render the Certificate Template -->
                <div class="p-6 overflow-x-auto">
                    <div class="certificate-builder-area text-align-justify">
                        {!! $certificateTemplate !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout>
