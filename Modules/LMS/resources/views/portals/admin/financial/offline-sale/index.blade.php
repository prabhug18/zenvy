@php
    $backendSetting = get_theme_option(key: 'backend_general') ?? null;
    $currency = $backendSetting['currency'] ?? 'USD-$';
    $currencySymbol = get_currency_symbol($currency);
@endphp

<x-dashboard-layout>
    <x-slot:title>{{ translate('Offline Sale/manage') }}</x-slot:title>
    <!-- BREADCRUMB -->
    <x-portal::admin.breadcrumb title="Offline Sales Report" page-to="Offline Sales" />


    {{-- <div class="card">
        <div class="grid grid-cols-12 gap-4">
            <x-portal::admin.course-overview-card color-type="primary" title="Total Offline & Manual Revenue"
                value="{{ $currencySymbol }}{{ $reports['total_sales'] ?? 0 }}" />
            <x-portal::admin.course-overview-card color-type="warning" title="Admin Enrollments"
                value="{{ $reports['total_admin_enrollment'] ?? 0 }}" />
            <x-portal::admin.course-overview-card color-type="success" title="Offline Payments"
                value="{{ $reports['total_offline_payment'] ?? 0 }}" />
        </div>
    </div> --}}
    <div class="card">
        <div class="flex justify-between items-center mb-4">
            <h6 class="text-heading dark:text-white font-semibold">{{ translate('Offline Sale List') }}</h6>
            <form action="{{ route('offline-sale.index') }}" method="GET" class="flex gap-4">
                <select name="date_filter" class="form-input pr-10 min-w-[160px]" onchange="this.form.submit()">
                    <option value="all" {{ request('date_filter') == 'all' ? 'selected' : '' }}>{{ translate('All Time') }}</option>
                    <option value="weekly" {{ request('date_filter') == 'weekly' ? 'selected' : '' }}>{{ translate('This Week') }}</option>
                    <option value="monthly" {{ request('date_filter') == 'monthly' ? 'selected' : '' }}>{{ translate('This Month') }}</option>
                </select>
                <!-- <select name="payment_type" class="form-input pr-10 min-w-[0px]" onchange="this.form.submit()">
                    <option value="all" {{ request('payment_type') == 'all' ? 'selected' : '' }}>{{ translate('All Records') }}</option>
                    <option value="offline" {{ request('payment_type') == 'offline' ? 'selected' : '' }}>{{ translate('Offline Payment') }}</option>
                    <option value="enrolled" {{ request('payment_type') == 'enrolled' ? 'selected' : '' }}>{{ translate('Admin Enrolled') }}</option>
                </select> -->
            </form>
        </div>
        <div class="overflow-x-auto scrollbar-table">

            @if (count($sales))
                <table
                    class="table-auto w-full whitespace-nowrap text-left text-gray-500 dark:text-dark-text font-medium leading-none">
                    <thead class="text-primary">
                        <tr>
                            <th class="px-3.5 py-4 bg-[#F2F4F9] dark:bg-dark-card-two first:rounded-l-lg last:rounded-r-lg first:dk-theme-card-square-left last:dk-theme-card-square-right">
                                {{ translate('Purchase ID') }}
                            </th>
                            <th class="px-3.5 py-4 bg-[#F2F4F9] dark:bg-dark-card-two first:rounded-l-lg last:rounded-r-lg first:dk-theme-card-square-left last:dk-theme-card-square-right">
                                {{ translate('Student') }}
                            </th>
                            <th class="px-3.5 py-4 bg-[#F2F4F9] dark:bg-dark-card-two first:rounded-l-lg last:rounded-r-lg first:dk-theme-card-square-left last:dk-theme-card-square-right">
                                {{ translate('Instructor') }}
                            </th>
                            <th class="px-3.5 py-4 bg-[#F2F4F9] dark:bg-dark-card-two first:rounded-l-lg last:rounded-r-lg first:dk-theme-card-square-left last:dk-theme-card-square-right">
                                {{ translate('Source') }}
                            </th>
                            <th class="px-3.5 py-4 bg-[#F2F4F9] dark:bg-dark-card-two first:rounded-l-lg last:rounded-r-lg first:dk-theme-card-square-left last:dk-theme-card-square-right">
                                {{ translate('Discount Price') }}
                            </th>
                            <th class="px-3.5 py-4 bg-[#F2F4F9] dark:bg-dark-card-two first:rounded-l-lg last:rounded-r-lg first:dk-theme-card-square-left last:dk-theme-card-square-right">
                                {{ translate('Price') }}
                            </th>
                            <th class="px-3.5 py-4 bg-[#F2F4F9] dark:bg-dark-card-two first:rounded-l-lg last:rounded-r-lg first:dk-theme-card-square-left last:dk-theme-card-square-right">
                                {{ translate('Item') }}
                            </th>
                            <th class="px-3.5 py-4 bg-[#F2F4F9] dark:bg-dark-card-two first:rounded-l-lg last:rounded-r-lg first:dk-theme-card-square-left last:dk-theme-card-square-right">
                                {{ translate('Purchase Date') }}
                            </th>
                            <th class="px-3.5 py-4 bg-[#F2F4F9] dark:bg-dark-card-two first:rounded-l-lg last:rounded-r-lg first:dk-theme-card-square-left last:dk-theme-card-square-right">
                                {{ translate('Payment Status') }}
                            </th>
                            <th class="px-3.5 py-4 bg-[#F2F4F9] dark:bg-dark-card-two first:rounded-l-lg last:rounded-r-lg first:dk-theme-card-square-left last:dk-theme-card-square-right">
                                {{ translate('Action') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-dark-border-three">
                        @foreach ($sales as $sale)
                            @php
                                $studentInfo = $sale->user->userable ?? null;
                                $studentTranslations = [];
                                if ($studentInfo) {
                                    $studentTranslations = parse_translation($studentInfo);
                                }
                                $firstName = $studentTranslations['first_name'] ?? ($studentInfo?->first_name ?? '');
                                $lastName = $studentTranslations['last_name'] ?? ($studentInfo?->last_name ?? '');

                                $instructors = $sale->course->instructors ?? [];
                                $courseTranslations = parse_translation($sale?->course);
                                $bundleTranslations = parse_translation($sale?->courseBundle);

                                $courseTitle = $courseTranslations['title'] ?? $sale?->course?->title;
                                $bundleTitle = $bundleTranslations['title'] ?? $sale?->courseBundle?->title;

                                $itemId = $sale?->course->id ?? ($sale?->courseBundle?->id ?? 0);

                            @endphp
                            <tr>
                                <td class="px-3.5 py-4">
                                    #{{ $sale->purchase_number }}
                                </td>
                                <td class="px-3.5 py-4">
                                    <div class="flex items-center gap-3.5">
                                        <div>
                                            <h6 class="leading-none text-heading dark:text-white font-semibold capitalize">
                                                <a href="#">
                                                    {{ $firstName . ' ' . $lastName }}
                                                </a>
                                            </h6>
                                            <p class="mb-1 text-sm"> {{ $sale->user?->email }}</p>
                                            <p class="text-sm">{{ $studentInfo?->phone }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-3.5 py-4">
                                    <div class="flex items-center gap-3.5">
                                        @foreach ($instructors as $instructor)
                                            @php
                                                $instructorInfo = $instructor->userable ?? null;
                                                $instructorTranslations = [];
                                                if ($instructorInfo) {
                                                    $instructorTranslations = parse_translation($instructorInfo);
                                                }
                                                $iFirstName =
                                                    $instructorTranslations['first_name'] ??
                                                    ($instructorInfo?->first_name ?? '');
                                                $iLastName =
                                                    $instructorTranslations['last_name'] ??
                                                    ($instructorInfo?->last_name ?? '');
                                            @endphp
                                            <div>
                                                <h6 class="leading-none text-heading dark:text-white font-semibold capitalize">
                                                    <a href="#"> {{ $iFirstName . ' ' . $iLastName }} </a>
                                                </h6>
                                                <p class="mb-1 text-sm"> {{ $instructor?->email }}</p>
                                                <p class="text-sm">{{ $instructorInfo?->phone }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-3.5 py-4">
                                    @if ($sale->type === \Modules\LMS\Enums\PurchaseType::ENROLLED)
                                        <span class="badge badge-primary-light">{{ translate('Admin Enrolled') }}</span>
                                    @else
                                        <span class="badge badge-success-light">{{ translate('Offline Bank Transfer') }}</span>
                                    @endif
                                </td>
                                <td class="px-3.5 py-4"> {{ $currencySymbol }}{{ $sale?->discount_price }}</td>
                                <td class="px-3.5 py-4">
                                    @if ($sale?->price)
                                        {{ $currencySymbol }}{{ $sale?->price }}
                                    @else
                                        {{ translate('Free') }}
                                    @endif
                                </td>
                                <td class="px-3.5 py-4" title="{{ $courseTitle ?? $bundleTitle }}">
                                    {{ str_limit($courseTitle ?? $bundleTitle, 20) }}
                                    <p class="text-sm mt-1"> {{ translate('Item Id') }} :#{{ $itemId }}</p>
                                </td>
                                <td class="px-3.5 py-4">
                                    {{ customDateFormate($sale->updated_at, $format = 'd M y h:i A') }}</td>
                                <td class="px-3.5 py-4"> {{ translate($sale?->purchase?->status) }}</td>
                                <td class="px-3.5 py-4">
                                    <a href="{{ route('sale.invoice', $sale->id) }}"
                                        class="btn-icon btn-primary-icon-light size-8" target="_blank">
                                        <i class="ri-printer-line text-inherit text-base"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <x-portal::admin.empty-card title="No Records Found" />
            @endif
        </div>
    </div>
</x-dashboard-layout>
