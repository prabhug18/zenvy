<x-dashboard-layout>
    <x-slot:title>
        @if (isset($video))
            {{ translate('Edit Homepage Video') }}
        @else
            {{ translate('Create Homepage Video') }}
        @endif
    </x-slot:title>
    <!-- BREADCRUMB -->
    <x-portal::admin.breadcrumb title="{{ isset($video) ? 'Edit' : 'Create' }} Homepage Video" page-to="Homepage Video"
        action-route="{{ route('homepage-video.index') }}" />

    @php
        $translations = isset($video) ? parse_translation($video) : [];
    @endphp

    <form action="{{ isset($video) ? route('homepage-video.update', $video->id) : route('homepage-video.store') }}"
        method="post" class="form" enctype="multipart/form-data">
        @csrf
        @if (isset($video))
            @method('put')
        @endif

        <input type="hidden" name="locale" value="{{ request()->locale ?? app()->getLocale() }}">

        <div class="card p-6">
            <div class="grid grid-cols-12 gap-x-4">
                <!-- Title -->
                <div class="col-span-full md:col-span-6 mb-4">
                    <label class="form-label">{{ translate('Title') }} <span class="text-danger-500">*</span></label>
                    <input type="text" name="title" class="form-input"
                        placeholder="{{ translate('Enter video title') }}"
                        value="{{ $translations['title'] ?? ($video->title ?? '') }}" required>
                </div>

                <!-- Video Type -->
                <div class="col-span-full md:col-span-6 mb-4">
                    <label class="form-label">{{ translate('Video Type') }} <span
                            class="text-danger-500">*</span></label>
                    <select name="video_type" id="video_type" class="form-select" required>
                        <option value="upload" {{ isset($video) && $video->video_type == 'upload' ? 'selected' : '' }}>
                            {{ translate('Local Upload') }}</option>
                        <option value="youtube" {{ isset($video) && $video->video_type == 'youtube' ? 'selected' : '' }}>
                            {{ translate('YouTube') }}</option>
                        <option value="vimeo" {{ isset($video) && $video->video_type == 'vimeo' ? 'selected' : '' }}>
                            {{ translate('Vimeo') }}</option>
                    </select>
                </div>

                <!-- Video File (Upload) -->
                <div class="col-span-full md:col-span-6 mb-4 {{ isset($video) && $video->video_type != 'upload' ? 'hidden' : '' }}"
                    id="upload_container">
                    <label class="form-label">{{ translate('Video File') }} (mp4, mov) @if (!isset($video))
                            <span class="text-danger-500">*</span>
                        @endif
                    </label>
                    <input type="file" name="video" class="form-input" accept="video/*">
                    @if (isset($video) && $video->video_type == 'upload')
                        <p class="text-xs mt-1 text-gray-500">{{ translate('Current video') }}: {{ $video->video_url }}
                        </p>
                    @endif
                </div>

                <!-- Video URL (External) -->
                <div class="col-span-full md:col-span-6 mb-4 {{ !isset($video) || $video->video_type == 'upload' ? 'hidden' : '' }}"
                    id="url_container">
                    <label class="form-label">{{ translate('Video URL') }} <span
                            class="text-danger-500">*</span></label>
                    <input type="text" name="video_url" class="form-input"
                        placeholder="{{ translate('Enter URL') }}"
                        value="{{ isset($video) && $video->video_type != 'upload' ? $video->video_url : '' }}">
                </div>

                <!-- Thumbnail -->
                <div class="col-span-full md:col-span-6 mb-4">
                    <div class="form-group">
                        <label class="form-label">{{ translate('Thumbnail Image') }}</label>
                        <div class="flex flex-col gap-2">
                            <input type="file" name="thumbnail_file" class="form-input" accept="image/*" onchange="previewImage(this)">
                            <div id="thumbnail-preview-container" class="mt-2 {{ isset($video) && $video->thumbnail ? '' : 'hidden' }}">
                                <p class="text-xs text-gray-500 mb-1">{{ translate('Current/New Preview:') }}</p>
                                <img id="thumbnail-preview" 
                                     src="{{ isset($video) && $video->thumbnail ? asset('storage/lms/videos/thumbnails/' . $video->thumbnail) : '#' }}" 
                                     alt="Preview" 
                                     class="w-32 h-20 object-cover rounded border shadow-sm">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order -->
                <div class="col-span-full md:col-span-6 mb-4">
                    <label class="form-label">{{ translate('Order') }}</label>
                    <input type="number" name="order" class="form-input" value="{{ $video->order ?? 0 }}">
                </div>

                <!-- Submit Button -->
                <div class="col-span-full mt-6">
                    <button type="submit" class="btn b-solid btn-primary-solid dk-theme-card-square">
                        {{ isset($video) ? translate('Update Video') : translate('Save Video') }}
                    </button>
                </div>
            </div>
        </div>
    </form>

    @push('js')
        <script>
            function previewImage(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById('thumbnail-preview').src = e.target.result;
                        document.getElementById('thumbnail-preview-container').classList.remove('hidden');
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }

            document.getElementById('video_type').addEventListener('change', function() {
                const type = this.value;
                const uploadContainer = document.getElementById('upload_container');
                const urlContainer = document.getElementById('url_container');

                if (type === 'upload') {
                    uploadContainer.classList.remove('hidden');
                    urlContainer.classList.add('hidden');
                } else {
                    uploadContainer.classList.add('hidden');
                    urlContainer.classList.remove('hidden');
                }
            });
        </script>
    @endpush
</x-dashboard-layout>
