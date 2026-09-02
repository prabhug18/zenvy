<x-dashboard-layout>
    <div class="card">
        <div class="flex justify-end mb-4">
            <button onclick="printCertificate()" 
                    class="btn b-solid btn-primary-solid dk-theme-card-square flex items-center gap-2">
                <i class="ri-download-line"></i>
                {{ translate('Download as PDF') }}
            </button>
        </div>
        <div id="certificate-builder-area" class="certificate-builder-area text-align-justify !overflow-x-auto">
            {!! $certificate->certificate_content !!}
        </div>
    </div>

    @push('css')
    <style>
        .certificate-template-container {
            position: relative !important;
            width: 1056px !important;
            height: 816px !important;
            overflow: hidden !important;
            text-align: left !important;
            margin-left: auto !important;
            margin-right: auto !important;
            background-size: 100% 100% !important;
            background-repeat: no-repeat !important;
        }
        .dragable-element {
            position: absolute !important;
            display: inline-block !important;
            white-space: nowrap !important;
            text-align: left !important;
            line-height: 1 !important;
        }
        @media print {
            body {
                background: white !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            #certificate-builder-area {
                visibility: visible !important;
                position: absolute !important;
                left: 0 !important;
                top: 0 !important;
                width: 100% !important;
                height: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                display: block !important;
                z-index: 9999 !important;
                overflow: visible !important;
            }
            /* Hide all UI elements */
            nav, aside, footer, .card-header, button, .flex, .breadcrumb, .top-bar {
                display: none !important;
            }
            /* Ensure the layout doesn't clip */
            main, section, .card {
                padding: 0 !important;
                margin: 0 !important;
                border: none !important;
                background: transparent !important;
                box-shadow: none !important;
                overflow: visible !important;
            }
        }
    </style>
    @endpush

    @push('js')
    <script>
        function printCertificate() {
            window.print();
        }
        
        // Auto-print if download=1 is in URL
        window.addEventListener('load', function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('download')) {
                setTimeout(function() {
                    window.print();
                }, 1000); // Small delay to ensure everything is rendered
            }
        });
    </script>
    @endpush
</x-dashboard-layout>
