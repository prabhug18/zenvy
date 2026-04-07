<x-dashboard-layout>
    <x-slot:title>{{ isset($currency) ? translate('Edit') : translate('Create') }} {{ translate('Currency') }}
    </x-slot:title>
    <x-portal::admin.breadcrumb back-url="{{ route('currency.index') }}"
        title="{{ isset($currency) ? 'Edit' : 'Create' }}" page-to="Currency" />
    <form action="{{ isset($currency) ? route('currency.update', $currency->id) : route('currency.store') }}"
        method="post" class="form mb-4">
        @csrf
        @if (isset($currency))
            @method('PUT')
        @endif
        <div class="grid grid-cols-12 gap-x-4">
            <div class="col-span-full lg:col-span-12 card">
                <div class="leading-none mt-6">
                    <label for="currency" class="form-label">{{ translate('Currency') }}</label>
                    @php $currencyOptions = get_currency_list(); @endphp
                    @if(!empty($currencyOptions))
                        <select data-select id="currency" name="currency" class="singleSelect">
                            <option selected disabled data-display="{{ translate('Select Currency') }}">
                                {{ translate('Select Currency') }} </option>
                            @foreach ($currencyOptions as $currencyList)
                                <option
                                    value="{{ $currencyList['symbol'] . '-' . $currencyList['code'] . '-' . $currencyList['name'] }}"
                                    {{ isset($currency) && $currencyList['code'] == $currency->code ? 'selected' : '' }}>
                                    {{ $currencyList['symbol'] }} - {{ $currencyList['code'] }}
                                </option>
                            @endforeach
                        </select>
                    @else
                        <div class="grid grid-cols-3 gap-2">
                            <div>
                                <label class="form-label">{{ translate('Symbol') }}</label>
                                <input type="text" name="symbol" value="{{ $currency->symbol ?? old('symbol') }}" class="form-control" placeholder="e.g. $">
                            </div>
                            <div>
                                <label class="form-label">{{ translate('Code') }}</label>
                                <input type="text" name="code" value="{{ $currency->code ?? old('code') }}" class="form-control" placeholder="e.g. USD">
                            </div>
                            <div>
                                <label class="form-label">{{ translate('Name') }}</label>
                                <input type="text" name="name" value="{{ $currency->name ?? old('name') }}" class="form-control" placeholder="e.g. US Dollar">
                            </div>
                        </div>
                        <small class="text-muted">{{ translate('Intl extension not available. Enter currency details manually.') }}</small>
                    @endif
                    <div class="justify-end mt-3">
                        <button type="submit" class="btn b-solid btn-primary-solid w-max dk-theme-card-square">
                            {{ isset($currency) ? translate('Update') : translate('Save') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</x-dashboard-layout>
