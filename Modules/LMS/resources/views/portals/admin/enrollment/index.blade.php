<x-dashboard-layout>
    <x-slot:title>{{ translate('Enrollment/Manage') }}</x-slot:title>
    <!-- BREADCRUMB -->
    <x-portal::admin.breadcrumb title="Student List" page-to="Enroll" action-route="{{ route('enrollment.create') }}" />

    @if ($enrollments->count() > 0)
        <div class="card overflow-hidden">
            <div class="overflow-x-auto">
                <table
                    class="table-auto w-full whitespace-nowrap text-left text-gray-500 dark:text-dark-text font-medium leading-none">
                    <thead class="text-primary-500">
                        <tr>
                            <th
                                class="px-3.5 py-4 bg-[#F2F4F9] dark:bg-dark-card-two first:rounded-l-lg last:rounded-r-lg first:dk-theme-card-square-left last:dk-theme-card-square-right">
                                {{ translate('Name') }}
                            </th>
                            <th
                                class="px-3.5 py-4 bg-[#F2F4F9] dark:bg-dark-card-two first:rounded-l-lg last:rounded-r-lg first:dk-theme-card-square-left last:dk-theme-card-square-right">
                                {{ translate('Instructor') }}
                            </th>
                            <th
                                class="px-3.5 py-4 bg-[#F2F4F9] dark:bg-dark-card-two first:rounded-l-lg last:rounded-r-lg first:dk-theme-card-square-left last:dk-theme-card-square-right">
                                {{ translate('Course') }}
                            </th>
                            <th
                                class="px-3.5 py-4 bg-[#F2F4F9] dark:bg-dark-card-two first:rounded-l-lg last:rounded-r-lg first:dk-theme-card-square-left last:dk-theme-card-square-right">
                                {{ translate('Enroll') }}
                            </th>
                            <th
                                class="px-3.5 py-4 bg-[#F2F4F9] dark:bg-dark-card-two first:rounded-l-lg last:rounded-r-lg first:dk-theme-card-square-left last:dk-theme-card-square-right">
                                {{ translate('Payment Method') }}
                            </th>

                            <th
                                class="px-3.5 py-4 bg-[#F2F4F9] dark:bg-dark-card-two first:rounded-l-lg last:rounded-r-lg first:dk-theme-card-square-left last:dk-theme-card-square-right">
                                {{ translate('Payment Status') }}
                            </th>
                            <th
                                class="px-3.5 py-4 bg-[#F2F4F9] dark:bg-dark-card-two first:rounded-l-lg last:rounded-r-lg first:dk-theme-card-square-left last:dk-theme-card-square-right">
                                {{ translate('Status') }}
                            </th>
                            <th
                                class="px-3.5 py-4 bg-[#F2F4F9] dark:bg-dark-card-two first:rounded-l-lg last:rounded-r-lg first:dk-theme-card-square-left last:dk-theme-card-square-right">
                                {{ translate('Action') }}
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 dark:divide-dark-border-three">
                        @foreach ($enrollments as $enrollment)
                            @php
                                $userInfo = $enrollment?->user?->userable ?? null;
                                $instructors = $enrollment?->course?->instructors ?? [];
                                $bundleInstructor = $enrollment?->courseBundle->instructor ?? null;
                                $bundleOrganization = $enrollment?->courseBundle->organization ?? null;

                                $studentTranslations = parse_translation($userInfo);

                            @endphp
                            <tr>
                                <td class="px-2 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex flex-col space-y-2.5">
                                            <h6 class="text-sm">
                                                <span class="text-heading dark:text-dark-text font-semibold">
                                                    {{ translate('Name') }}:
                                                </span>
                                                {{ $studentTranslations['first_name'] ?? $userInfo?->first_name }}
                                                {{ $studentTranslations['last_name'] ?? $userInfo?->last_name }}
                                            </h6>
                                            <h6 class="text-sm">
                                                <span class="text-heading dark:text-dark-text font-semibold">
                                                    {{ translate('Email') }}:
                                                </span>
                                                {{ $enrollment?->user?->email }}
                                            </h6>
                                            <h6 class="text-sm">
                                                <span class="text-heading dark:text-dark-text font-semibold">
                                                    {{ translate('Phone') }}:
                                                </span>
                                                {{ $userInfo?->phone }}
                                            </h6>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-2 py-4">
                                    @if (isset($instructors) && !empty($instructors))
                                        @foreach ($instructors as $instructor)
                                            @php
                                                $instructorInfo = $instructor?->userable ?? null;
                                                $instructorTranslations = parse_translation($instructorInfo);
                                            @endphp
                                            {{ $instructorTranslations['first_name'] ?? $instructorInfo?->first_name }}
                                            {{ $instructorTranslations['last_name'] ?? $instructorInfo?->last_name }}
                                            @if (!$loop->last)
                                                ,
                                            @endif
                                        @endforeach
                                    @elseif($bundleInstructor)
                                        @php
                                            $bundleUser = $bundleInstructor->userable ?? null;
                                            $bundleUserTranslations = parse_translation($bundleUser);
                                        @endphp
                                        {{ $bundleUserTranslations['first_name'] ?? $bundleUser?->first_name }}
                                        {{ $bundleUserTranslations['last_name'] ?? $bundleUser?->last_name }}
                                    @elseif($bundleOrganization)
                                        @php
                                            $orgUser = $bundleOrganization->userable ?? null;
                                            $orgUserTranslations = parse_translation($orgUser);
                                        @endphp
                                        {{ $orgUserTranslations['name'] ?? $orgUser?->name }}
                                    @endif
                                </td>
                                <td class="px-2 py-4">
                                    @if ($enrollment->purchase_type == 'course')
                                        @php
                                            $courseTranslations = parse_translation($enrollment->course);
                                        @endphp
                                        <a href="{{ route('course.edit', $enrollment->course_id) }}">
                                            {{ str_limit($courseTranslations['title'] ?? $enrollment->course?->title, 20, '...') }}</a>
                                    @elseif ($enrollment->purchase_type == 'bundle')
                                        @php
                                            $bundleTranslations = parse_translation($enrollment->courseBundle);
                                        @endphp

                                        <a href="{{ route('bundle.list') }}">
                                            {{ str_limit($bundleTranslations['title'] ?? $enrollment->courseBundle?->title, 20, '...') }}</a>
                                    @endif
                                </td>
                                <td class="px-2 py-4">
                                    {{ customDateFormate($enrollment->created_at, $format = 'd M Y h:i a') }}
                                </td>

                                <td class="px-2 py-4">
                                    {{ $enrollment?->purchase->payment_method }}
                                </td>
                                <td class="px-2 py-4">
                                    @switch($enrollment?->purchase->status)
                                        @case('success')
                                            <span class="badge b-solid badge-warning-solid capitalize">
                                                {{ translate('success') }}
                                            </span>
                                        @break

                                        @case('fail')
                                            <span class="badge b-solid badge-danger-solid capitalize">
                                                {{ translate('fail') }}
                                            </span>
                                        @break
                                    @endswitch
                                </td>
                                <td class="px-2 py-4">
                                    <label class="inline-flex items-center me-5 cursor-pointer">
                                        <input type="checkbox" class="appearance-none peer status-change" name="status"
                                            {{ $enrollment->status !== \Modules\LMS\Enums\PurchaseStatus::INACTIVE ? 'checked' : '' }}
                                            data-action="{{ route('enrollment.status', $enrollment->id) }}">
                                        <span class="switcher switcher-primary-solid"></span>
                                    </label>
                                </td>
                                 <td>
                                    <div class="flex items-center gap-1">
                                        <a href="{{ route('enrollment.show', $enrollment->id) }}"
                                            class="btn-icon btn-primary-icon-light size-8"
                                            title="View">
                                            <i class="ri-eye-line text-inherit text-base"></i>
                                        </a>
                                        <!-- Edit Permissions Button -->
                                        <button type="button"
                                            class="btn-icon btn-warning-icon-light size-8 edit-permissions-btn"
                                            title="Edit Permissions"
                                            data-id="{{ $enrollment->id }}"
                                            data-video="{{ ($enrollment->topic_permissions['video'] ?? false) ? '1' : '0' }}"
                                            data-assignment="{{ ($enrollment->topic_permissions['assignment'] ?? false) ? '1' : '0' }}"
                                            data-quiz="{{ ($enrollment->topic_permissions['quiz'] ?? false) ? '1' : '0' }}"
                                            data-reading="{{ ($enrollment->topic_permissions['reading'] ?? false) ? '1' : '0' }}"
                                            data-action="{{ route('enrollment.update.permissions', $enrollment->id) }}">
                                            <i class="ri-lock-unlock-line text-inherit text-base"></i>
                                        </button>
                                    </div>
                                 </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Start Pagination -->
            {{ $enrollments->links('portal::admin.pagination.paginate') }}
        </div>
    @else
        <x-portal::admin.empty-card title="No enrollment" action="{{ route('enrollment.create') }}"
            btnText="Add New" />
    @endif

    {{-- ========= Edit Permissions Modal ========= --}}
    <div id="editPermissionsModal"
        class="fixed inset-0 z-[999] hidden flex-center bg-black/50 backdrop-blur-sm transition-all p-4">
        <div class="bg-white dark:bg-dark-card-two rounded-2xl shadow-2xl w-full max-w-xl overflow-hidden relative border border-gray-100 dark:border-dark-border">
            {{-- Header --}}
            <div class="p-6 border-b border-gray-100 dark:border-dark-border text-center relative bg-slate-50/50 dark:bg-dark-card-two">
                <h5 class="text-xl font-bold text-heading dark:text-white uppercase tracking-tight">{{ translate('Edit Permissions') }}</h5>
                <p class="text-xs text-gray-500 mt-1">{{ translate('Manage student access for this enrollment') }}</p>
                
                {{-- Close button --}}
                <button id="closePermissionsModal" type="button"
                    class="absolute top-4 right-4 size-9 flex-center rounded-xl bg-white dark:bg-dark-icon shadow-sm hover:bg-gray-50 dark:hover:bg-dark-border transition-all border border-gray-100 dark:border-dark-border group">
                    <i class="ri-close-fill text-xl text-gray-400 group-hover:text-danger transition-colors"></i>
                </button>
            </div>

            <form id="editPermissionsForm" method="POST" class="p-5 pb-5">
                @csrf
                <div class="space-y-4">
                    @foreach(['video' => 'Video', 'assignment' => 'Assignment', 'quiz' => 'Quiz', 'reading' => 'Reading'] as $pKey => $pLabel)
                        <div class="flex items-center justify-between gap-4 p-4 rounded-xl border border-gray-100 dark:border-dark-border hover:bg-slate-50/50 dark:hover:bg-dark-input transition-all group">
                            <div class="flex items-center gap-4">
                                @php
                                    $iconMap = [
                                        'video' => ['icon' => 'ri-vidicon-line', 'bg' => 'bg-blue-100 text-blue-600'],
                                        'assignment' => ['icon' => 'ri-file-list-3-line', 'bg' => 'bg-amber-100 text-amber-600'],
                                        'quiz' => ['icon' => 'ri-questionnaire-line', 'bg' => 'bg-purple-100 text-purple-600'],
                                        'reading' => ['icon' => 'ri-book-open-line', 'bg' => 'bg-emerald-100 text-emerald-600']
                                    ];
                                    $style = $iconMap[$pKey];
                                @endphp
                                <div class="size-10 flex-center rounded-lg {{ $style['bg'] }} text-lg shadow-sm">
                                    <i class="{{ $style['icon'] }}"></i>
                                </div>
                                <div>
                                    <span class="text-base font-semibold text-heading dark:text-white block">{{ translate($pLabel) }}</span>
                                    <span class="text-xs text-gray-400">{{ translate('Allow access to') }} {{ strtolower($pLabel) }}</span>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox"
                                    name="topic_permissions[{{ $pKey }}]"
                                    value="1"
                                    class="sr-only peer modal-permission-checkbox"
                                    data-key="{{ $pKey }}">
                                <div class="w-12 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700
                                    peer-checked:after:translate-x-full peer-checked:after:border-white
                                    after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white
                                    after:border-gray-300 after:border after:rounded-full after:size-5
                                    after:transition-all dark:border-gray-600 peer-checked:bg-primary-500 shadow-inner">
                                </div>
                            </label>
                        </div>
                    @endforeach
                </div>

                <div class="flex items-center justify-center gap-4 mt-10 mb-2">
                    <button type="button" id="cancelPermissionsModal"
                        class="btn b-outline btn-secondary-outline h-12 px-10 rounded-xl font-semibold">
                        {{ translate('Cancel') }}
                    </button>
                    <button type="submit" id="savePermissionsBtn"
                        class="btn b-solid btn-primary-solid h-12 px-10 rounded-xl font-semibold shadow-lg shadow-primary-500/30">
                        <i class="ri-save-line mr-2"></i> {{ translate('Save Changes') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    (function () {
        const modal        = document.getElementById('editPermissionsModal');
        const form         = document.getElementById('editPermissionsForm');
        const closeBtn     = document.getElementById('closePermissionsModal');
        const cancelBtn    = document.getElementById('cancelPermissionsModal');
        const saveBtn      = document.getElementById('savePermissionsBtn');
        const checkboxes   = form.querySelectorAll('.modal-permission-checkbox');

        function openModal(btn) {
            const id     = btn.dataset.id;
            const action = btn.dataset.action;
            form.action  = action;

            // Pre-populate toggles from data attributes
            checkboxes.forEach(function (cb) {
                const key = cb.dataset.key;
                cb.checked = btn.dataset[key] === '1';
            });

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeModal() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        document.querySelectorAll('.edit-permissions-btn').forEach(function (btn) {
            btn.addEventListener('click', function () { openModal(btn); });
        });

        closeBtn.addEventListener('click',   closeModal);
        cancelBtn.addEventListener('click',  closeModal);
        modal.addEventListener('click', function (e) {
            if (e.target === modal) closeModal();
        });

        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(form);

            saveBtn.disabled = true;
            saveBtn.innerHTML = '<i class="ri-loader-4-line animate-spin mr-1"></i> {{ translate("Saving...") }}';

            fetch(form.action, {
                method:  'POST',
                body:    formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
            })
            .then(res => res.json())
            .then(data => {
                saveBtn.disabled = false;
                saveBtn.innerHTML = '<i class="ri-save-line mr-1"></i> {{ translate("Save Changes") }}';

                if (data.status === 'success') {
                    toastr.success(data.message || '{{ translate("Permissions updated.") }}');
                    setTimeout(() => { location.reload(); }, 1000);
                } else {
                    toastr.error(data.message || '{{ translate("Something went wrong.") }}');
                }
            })
            .catch(() => {
                saveBtn.disabled = false;
                saveBtn.innerHTML = '<i class="ri-save-line mr-1"></i> {{ translate("Save Changes") }}';
                toastr.error('{{ translate("Request failed. Please try again.") }}');
            });
        });
    })();
    </script>

</x-dashboard-layout>
