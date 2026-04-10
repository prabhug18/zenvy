<div class="relative pt-16 sm:pt-24 lg:pt-[120px] mt-16 sm:mt-24 lg:mt-[120px] overflow-hidden">
    <div class="container relative z-[1]">
        <div class="grid grid-cols-12 rounded-2xl overflow-hidden">
            <div class="col-span-full lg:col-span-7">
                <div class="bg-gradient-to-b from-[#FEFBF0] to-[#E6F3EB] px-5 py-8 xl:p-20 h-full">
                    <h2 class="area-title">
                        {{ translate('Free Consultation') }}
                    </h2>
                    <div class="area-description mt-3 mb-8">
                        {{ translate('Have questions? Fill in the form below and our team will get back to you shortly.') }}
                    </div>

                    @if(session('success'))
                        <div class="mb-4 px-4 py-3 rounded-xl bg-green-100 text-green-700 font-semibold">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="mb-4 px-4 py-3 rounded-xl bg-red-100 text-red-700 font-semibold">
                            {{ session('error') }}
                        </div>
                    @endif

                    <style>
                        .consultation-form .form-row {
                            display: grid;
                            grid-template-columns: 1fr 1fr;
                            gap: 16px;
                            margin-bottom: 16px;
                        }
                        .consultation-form .form-row-full {
                            margin-bottom: 16px;
                        }
                        .consultation-form .form-field input,
                        .consultation-form .form-field textarea {
                            width: 100%;
                            padding: 14px 20px;
                            border: 1px solid #e5e7eb;
                            border-radius: 9999px;
                            background: #fff;
                            font-size: 15px;
                            color: #333;
                            outline: none;
                            transition: border-color 0.2s, box-shadow 0.2s;
                        }
                        .consultation-form .form-field input:focus,
                        .consultation-form .form-field textarea:focus {
                            border-color: var(--primary-color, #7C3AED);
                            box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.15);
                        }
                        .consultation-form .form-field input::placeholder,
                        .consultation-form .form-field textarea::placeholder {
                            color: #9ca3af;
                        }
                        .consultation-form .form-field textarea {
                            border-radius: 16px;
                            resize: none;
                            min-height: 120px;
                        }
                        .consultation-form .submit-btn {
                            display: inline-flex;
                            align-items: center;
                            gap: 8px;
                            padding: 14px 32px;
                            border-radius: 9999px;
                            font-weight: 700;
                            font-size: 16px;
                            white-space: nowrap;
                            cursor: pointer;
                            transition: transform 0.2s, box-shadow 0.2s;
                        }
                        .consultation-form .submit-btn:hover {
                            transform: translateY(-2px);
                            box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3);
                        }
                        @media (max-width: 480px) {
                            .consultation-form .form-row {
                                grid-template-columns: 1fr;
                            }
                        }
                    </style>

                    <form action="{{ route('contact.store') }}" method="POST" class="consultation-form">
                        @csrf
                        <div class="form-row">
                            <div class="form-field">
                                <input type="text" name="name" placeholder="Full Name *" required value="{{ old('name') }}" />
                                @error('name') <p style="color:#ef4444;font-size:12px;margin-top:4px;padding-left:12px;">{{ $message }}</p> @enderror
                            </div>
                            <div class="form-field">
                                <input type="email" name="email" placeholder="Email *" required value="{{ old('email') }}" />
                                @error('email') <p style="color:#ef4444;font-size:12px;margin-top:4px;padding-left:12px;">{{ $message }}</p> @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-field">
                                <input type="text" name="phone" placeholder="Phone *" required value="{{ old('phone') }}" />
                                @error('phone') <p style="color:#ef4444;font-size:12px;margin-top:4px;padding-left:12px;">{{ $message }}</p> @enderror
                            </div>
                            <div class="form-field">
                                <input type="text" name="subject" placeholder="Subject *" required value="{{ old('subject') }}" />
                                @error('subject') <p style="color:#ef4444;font-size:12px;margin-top:4px;padding-left:12px;">{{ $message }}</p> @enderror
                            </div>
                        </div>
                        <div class="form-row-full">
                            <div class="form-field">
                                <textarea name="message" placeholder="Write your message *" required rows="5">{{ old('message') }}</textarea>
                                @error('message') <p style="color:#ef4444;font-size:12px;margin-top:4px;padding-left:12px;">{{ $message }}</p> @enderror
                            </div>
                        </div>
                        <div>
                            <button type="submit" class="btn b-solid btn-primary-solid submit-btn">
                                <span>{{ translate('Send Now') }}</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M7 7h10v10" />
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-span-full lg:col-span-5 hidden lg:block">
                <div class="p-0 m-0 aspect-[1/1.38] h-full overflow-hidden">
                    <img data-src="{{ asset('lms/frontend/assets/images/admission/admission-one.webp') }}"
                        alt="Consultation banner" class="size-full object-cover">
                </div>
            </div>
        </div>
    </div>
    <!-- POSITIONAL ELEMENT -->
    <ul>
        <!-- TOP LEFT -->
        <li
            class="block size-[29vw] rounded-50 bg-[#D2EB1A]/15 blur-[200px] absolute top-0 xl:-top-20 right-0 rtl:right-auto rtl:left-0 xl:-right-20 rtl:xl:right-auto rtl:xl:-left-20 z-0">
        </li>
        <!-- TOP RIGHT -->
        <li
            class="block size-[29vw] rounded-50 bg-[#B326F4]/15 blur-[200px] absolute top-0 xl:-top-20 left-0 rtl:left-auto rtl:right-0 xl:-left-20 rtl:xl:left-auto rtl:xl:-right-20 z-0">
        </li>
    </ul>
</div>