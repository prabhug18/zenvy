<x-dashboard-layout>
    <x-slot:title> {{ translate('View Homepage Video') }} </x-slot:title>
    <!-- BREADCRUMB -->
    <x-portal::admin.breadcrumb title="View Video" page-to="Homepage Video"
        action-route="{{ route('homepage-video.index') }}" />

    <div class="card">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-lg font-bold mb-4">{{ translate('Video Details') }}</h3>
                <div class="space-y-3">
                    <p><strong>{{ translate('Title') }}:</strong> {{ $video->title }}</p>
                    <p><strong>{{ translate('Type') }}:</strong> {{ ucfirst($video->video_type) }}</p>
                    <p><strong>{{ translate('Status') }}:</strong> {{ $video->status == 1 ? translate('Active') : translate('Inactive') }}</p>
                    <p><strong>{{ translate('Order') }}:</strong> {{ $video->order }}</p>
                </div>
            </div>
            <div>
                <h3 class="text-lg font-bold mb-4">{{ translate('Video Preview') }}</h3>
                @if ($video->video_type == 'upload')
                    <video src="{{ asset('storage/lms/videos/' . $video->video_url) }}" controls class="w-full rounded-lg shadow-sm"></video>
                @else
                    <div class="aspect-video">
                        <iframe src="{{ $video->video_url }}" class="w-full h-full rounded-lg shadow-sm" frameborder="0" allowfullscreen></iframe>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-dashboard-layout>
