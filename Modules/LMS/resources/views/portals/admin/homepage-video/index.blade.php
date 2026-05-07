<x-dashboard-layout>
    <x-slot:title> {{ translate('Manage Homepage Video') }} </x-slot:title>
    <!-- BREADCRUMB -->
    <x-portal::admin.breadcrumb title="Homepage Video" page-to="Homepage Video"
        action-route="{{ route('homepage-video.create') }}" />

    <div class="card overflow-hidden">

        <div class="flex items-center gap-2 pb-5 mb-5 border-b border-gray-200 dark:border-dark-border">
            <a href="{{ route('homepage-video.index', ['filter' => 'all']) }}"
                class="badge badge-primary-outline b-outline group/b-counter is-hover-active rounded-full dk-theme-card-square {{ get_active_filter_tab() === 'all' ? 'active' : '' }}">{{ translate('All') }}
                <span class="badge-counter rounded-full dk-theme-card-square">{{ $countData['total'] ?? 0 }}</span>
            </a>
            <a href="{{ route('homepage-video.index') }}"
                class="badge badge-primary-outline b-outline group/b-counter is-hover-active rounded-full dk-theme-card-square {{ get_active_filter_tab() === 'published' ? 'active' : '' }}">
                {{ translate('Published') }}
                <span
                    class="badge-counter rounded-full dk-theme-card-square">{{ $countData['published'] ?? 0 }}</span></a>
            <a href="{{ route('homepage-video.index', ['filter' => 'trash']) }}"
                class="badge badge-primary-outline b-outline group/b-counter is-hover-active rounded-full dk-theme-card-square {{ get_active_filter_tab() === 'trash' ? 'active' : '' }}">
                {{ translate('Trash') }}
                <span class="badge-counter rounded-full dk-theme-card-square">{{ $countData['trashed'] ?? 0 }}</span>
            </a>
        </div>
        @if ($videos->count() > 0)
            <div class="overflow-x-auto">
                <table
                    class="table-auto border-collapse w-full whitespace-nowrap text-left text-gray-500 dark:text-dark-text font-medium">
                    <thead>

                        <tr class="text-primary-500">
                            <th
                                class="px-3.5 py-4 bg-[#F2F4F9] dark:bg-dark-card-two first:rounded-l-lg last:rounded-r-lg first:dk-theme-card-square-left last:dk-theme-card-square-right">
                                {{ translate('Thumbnail') }}
                            </th>
                            <th
                                class="px-3.5 py-4 bg-[#F2F4F9] dark:bg-dark-card-two first:rounded-l-lg last:rounded-r-lg first:dk-theme-card-square-left last:dk-theme-card-square-right">
                                {{ translate('Title') }}
                            </th>
                            <th
                                class="px-3.5 py-4 bg-[#F2F4F9] dark:bg-dark-card-two first:rounded-l-lg last:rounded-r-lg first:dk-theme-card-square-left last:dk-theme-card-square-right">
                                {{ translate('Type') }}
                            </th>
                            <th
                                class="px-3.5 py-4 bg-[#F2F4F9] dark:bg-dark-card-two first:rounded-l-lg last:rounded-r-lg first:dk-theme-card-square-left last:dk-theme-card-square-right">
                                {{ translate('Status') }}
                            </th>
                            <th
                                class="px-3.5 py-4 bg-[#F2F4F9] dark:bg-dark-card-two first:rounded-l-lg last:rounded-r-lg first:dk-theme-card-square-left last:dk-theme-card-square-right w-10">
                                {{ translate('Action') }}
                            </th>
                        </tr>

                    </thead>

                    <tbody class="divide-y divide-gray-200 dark:divide-dark-border-three">
                        @foreach ($videos as $video)
                            @php $translations = parse_translation($video); @endphp
                            <tr>
                                <td class="px-4 py-4">
                                    @if (fileExists('lms/videos/thumbnails', $video->thumbnail) && $video->thumbnail != '')
                                        <img src="{{ asset('storage/lms/videos/thumbnails/' . $video->thumbnail) }}"
                                            alt="Thumbnail image"
                                            class="size-12 rounded-lg object-cover overflow-hidden dk-theme-card-square">
                                    @else
                                        <div class="size-12 rounded-lg bg-gray-200 flex items-center justify-center dk-theme-card-square">
                                            <i class="ri-video-line text-2xl text-gray-400"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-4 py-4">{{ $translations['title'] ?? ($video->title ?? '') }}</td>
                                <td class="px-4 py-4">
                                    <span class="badge badge-info-outline">
                                        {{ $video->video_type == 'upload' ? translate('Local Upload') : ucfirst($video->video_type) }}
                                    </span>
                                </td>
                                <td class="px-4 py-4">
                                    <label class="inline-flex items-center me-5 cursor-pointer">
                                        <input type="checkbox" class="appearance-none peer status-change"
                                            name="status" {{ $video->status == 1 ? 'checked' : '' }}
                                            data-action="{{ route('homepage-video.status', $video->id) }}">
                                        <span class="switcher switcher-primary-solid"></span>
                                    </label>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-2">
                                        @if ($video->trashed())
                                            <button
                                                data-action="{{ route('homepage-video.restore', ['id' => $video->id]) }}"
                                                class="btn-icon btn-primary-icon-light size-8 trash-restore-btn-cs"
                                                title="{{ translate('Restore') }}"
                                                data-title="{{ translate('Do you want to restore it') }}">
                                                <i class="ri-refresh-line text-inherit text-base"></i>
                                            </button>
                                        @else
                                            <a href="{{ route('homepage-video.translate', ['id' => $video->id, 'locale' => app()->getLocale()]) }}"
                                                class="btn-icon btn-primary-icon-light size-8" title="{{ translate('Translate') }}">
                                                <i class="ri-translate text-inherit text-base"></i>
                                            </a>
                                            <a href="{{ route('homepage-video.edit', $video->id) }}"
                                                class="btn-icon btn-primary-icon-light size-8" title="{{ translate('Edit') }}">
                                                <i class="ri-edit-2-line text-inherit text-base"></i>
                                            </a>
                                        @endif
                                        <button class="btn-icon btn-danger-icon-light size-8 delete-btn-cs"
                                            data-action="{{ route('homepage-video.destroy', $video->id) }}" title="{{ translate('Delete') }}">
                                            <i class="ri-delete-bin-line text-inherit text-base"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Start Pagination -->
            {{ $videos->links('portal::admin.pagination.paginate') }}
        @else
            <x-portal::admin.empty-card title="Homepage Video" action="{{ route('homepage-video.create') }}"
                btnText="Add New" />
        @endif
    </div>

</x-dashboard-layout>
