@push('styles')
<style>
    
    .fi-header, .fi-header-heading {
        display: none !important;
    }

    .fi-main {
        padding: 0 !important;
    }

    #scanner-modal {
        z-index: 9999;
    }
    
    #camera-feed-container {
        position: relative;
        width: 100%;
        height: 300px;
        background: black;
    }
    
    .scan-success-flash {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 255, 0, 0.3);
        animation: flash 0.5s ease-out;
        pointer-events: none;
        z-index: 15;
    }

    @keyframes flash {
        from { opacity: 1; }
        to { opacity: 0; }
    }
    
    #camera-feed {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .drawingBuffer {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }
    
    .scanner-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 10;
    }
    
    .scanner-frame {
        width: 80%;
        height: 150px;
        border: 3px solid rgba(255, 255, 255, 0.5);
        box-shadow: 0 0 0 1000px rgba(0, 0, 0, 0.5);
    }
    
    .scanner-status {
        position: absolute;
        bottom: 10px;
        left: 0;
        width: 100%;
        text-align: center;
        color: white;
        background: rgba(0, 0, 0, 0.7);
        padding: 5px;
        z-index: 20;
    }

    /* Custom POS Styling */
    .pos-container {
        height: calc(100vh - 8rem);
        background: white;
        padding: 1rem;
    }

    .dark .pos-container {
        background: rgb(10, 10, 10);
    }

    .pos-grid {
        display: grid;
        grid-template-columns: 350px 1fr;
        gap: 1rem;
        height: calc(100vh - 8rem);
    }

    .pos-left-panel {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        max-height: calc(100vh - 8rem);
    }

    .pos-right-panel {
        background: white;
        border-radius: 0.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        max-height: calc(100vh - 8rem);
    }

    .dark .pos-right-panel {
        background: rgb(18, 18, 18);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.5);
    }

    .cart-items {
        flex: 1;
        overflow-y: auto;
        padding: 1rem;
        max-height: calc(100vh - 12rem);
    }

    .cart-item {
        display: flex;
        align-items: center;
        padding: 0.75rem;
        border-radius: 0.375rem;
        background: #f9fafb;
        margin-bottom: 0.5rem;
        transition: all 0.2s ease;
    }

    .dark .cart-item {
        background: rgb(28, 28, 28);
    }

    .cart-item:hover {
        background: #f3f4f6;
    }

    .dark .cart-item:hover {
        background: rgb(38, 38, 38);
    }

    .cart-controls {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .quantity-control {
        display: flex;
        align-items: center;
        border: 1px solid #e5e7eb;
        border-radius: 0.375rem;
        overflow: hidden;
        background: white;
    }

    .dark .quantity-control {
        border-color: rgb(38, 38, 38);
        background: rgb(28, 28, 28);
    }

    .quantity-btn {
        padding: 0.25rem 0.5rem;
        background: #f9fafb;
        border: none;
        cursor: pointer;
        color: #4b5563;
        transition: all 0.2s ease;
    }

    .dark .quantity-btn {
        background: rgb(28, 28, 28);
        color: rgb(156, 156, 156);
    }

    .quantity-btn:hover {
        background: #f3f4f6;
    }

    .dark .quantity-btn:hover {
        background: rgb(38, 38, 38);
        color: white;
    }

    .quantity-display {
        padding: 0.25rem 0.75rem;
        border-left: 1px solid #e5e7eb;
        border-right: 1px solid #e5e7eb;
        background: white;
    }

    .dark .quantity-display {
        border-color: rgb(38, 38, 38);
        background: rgb(28, 28, 28);
        color: white;
    }

    /* Form styling overrides */
    .fi-fo-component-ctn {
        gap: 0.5rem !important;
    }

    .fi-input-wrp {
        margin-bottom: 0 !important;
    }

    /* Scrollbar styling */
    .cart-items::-webkit-scrollbar {
        width: 8px;
    }

    .cart-items::-webkit-scrollbar-track {
        background: transparent;
    }

    .cart-items::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }

    .dark .cart-items::-webkit-scrollbar-thumb {
        background: rgb(38, 38, 38);
    }

    .cart-items::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    .dark .cart-items::-webkit-scrollbar-thumb:hover {
        background: rgb(48, 48, 48);
    }

    /* Dark mode form overrides */
    .dark .fi-input-wrp {
        background: rgb(28, 28, 28) !important;
    }

    .dark .fi-select-input {
        background: rgb(28, 28, 28) !important;
    }

    .dark .fi-input-wrp-prefix {
        border-color: rgb(38, 38, 38) !important;
    }

    /* Dark mode specific styling */
    .dark .direct-scan-input {
        background-color: rgb(38, 38, 38) !important;
        color: white !important;
        border-color: rgb(55, 65, 81) !important;
    }
    
    .dark .total-amount {
        color: white !important;
    }
    
    .dark .change-amount {
        color: rgb(74, 222, 128) !important;
    }

    .dark .label-text {
        color: white !important;
    }
    
    .dark .total-label {
        color: white !important;
    }

    [data-field-wrapper="scannedBarcode"] {
        display: none !important;
    }

    #direct-scan-input:focus + .custom-focus-ring,
    #direct-scan-input:focus-within + .custom-focus-ring {
        opacity: 1;
    }
    
    .fi-input-wrp:focus-within {
        border-radius: 0.5rem;
        outline: 2px solid rgb(245, 158, 11) !important;
        outline-offset: -2px;
        ring-color: rgb(245, 158, 11) !important;
        ring-width: 2px !important;
    }

    #barcode-input-wrapper.ring-2 {
        outline: 2px solid rgb(245, 158, 11) !important;
    }
    
    #barcode-input-wrapper.ring-amber-500 {
        --tw-ring-color: rgb(245, 158, 11) !important;
        --tw-ring-opacity: 1 !important;
    }
    
    *:focus, *:focus-visible {
        outline: none !important;
        outline-color: transparent !important;
        box-shadow: none !important;
    }
    
    *.focus-within\:ring-primary-600:focus-within,
    *.focus-within\:ring-primary-500:focus-within,
    *.focus-within\:ring-blue-500:focus-within,
    *.focus-within\:ring-blue-600:focus-within,
    *.focus-within\:ring-2:focus-within {
        --tw-ring-color: transparent !important;
        --tw-ring-opacity: 0 !important;
        box-shadow: none !important;
    }
    
    #barcode-input-wrapper.ring-2.ring-amber-500 {
        --tw-ring-color: rgb(245, 158, 11) !important;
        --tw-ring-opacity: 1 !important;
        box-shadow: 0 0 0 2px rgb(245, 158, 11) !important;
    }
    
    #direct-scan-input:focus {
        outline: none !important;
        box-shadow: none !important;
    }
    
    #barcode-input-wrapper {
        border-radius: 0.5rem !important;
        overflow: hidden;
    }

    input:focus, 
    input:focus-visible,
    .fi-input:focus,
    .fi-input-wrp:focus-within {
        outline: none !important;
        box-shadow: none !important;
        border-color: rgb(245, 158, 11) !important;
    }
    
    :focus {
        outline: none !important;
    }
    
    .ring-primary-600,
    .focus-within\:ring-primary-600,
    .focus\:ring-primary-600 {
        --tw-ring-opacity: 0 !important;
        --tw-ring-color: transparent !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js" defer></script>
<script>
    let isProcessing = false;
    let lastScannedCode = null;
    let lastScannedTime = 0;
    let quaggaInitialized = false;
    let cameraStream = null;
    let keepScanning = false; 
    let scanCount = 0;
    
    let barcodeBuffer = '';
    let lastKeyTime = 0;
    let barcodeTimeoutId = null;
    
    function processDirectScanInput() {
        const input = document.getElementById('direct-scan-input');
        if (!input || input.value.trim() === '') {
            return;
        }
        
        const value = input.value.trim();
        console.log('Processing direct scan input:', value);
        
        window.Livewire.dispatch('handleBarcode', [value]);
        
        input.value = '';
        
        setTimeout(() => {
            input.focus();
        }, 100);
    }
    
    function focusDirectScanInput() {
        const input = document.getElementById('direct-scan-input');
        if (input) {
            input.focus();
            
            const wrapper = input.closest('.fi-input-wrp');
            if (wrapper) {
                wrapper.classList.add('focus-within:ring-2', 'focus-within:ring-amber-500', 'dark:focus-within:ring-amber-500');
            }
        }
    }
    
    function setupCustomInputFocus() {
        const input = document.getElementById('direct-scan-input');
        if (!input) return;
        
        const wrapper = input.closest('.fi-input-wrp');
        if (!wrapper) return;
        
        input.addEventListener('focus', function() {
            wrapper.classList.add('ring-2', 'ring-amber-500', 'dark:ring-amber-500');
        });
        
        input.addEventListener('blur', function() {
            wrapper.classList.remove('ring-2', 'ring-amber-500', 'dark:ring-amber-500');
        });
    }
    
    function setupBarcodeCapture() {
        console.log('Setting up direct barcode capture (ultra-aggressive)');
        
        function processCompleteBarcode(barcode) {
            if (barcode.length < 4) return; 
            
            console.log('DIRECT CAPTURE: Processing barcode:', barcode);
            
            const inputs = document.querySelectorAll('input[type="text"]');
            inputs.forEach(input => {
                if (input.value && input.value.includes(barcode)) {
                    input.value = '';
                }
            });
            
            window.Livewire.dispatch('handleBarcode', [barcode]);
            
            barcodeBuffer = '';
        }
        
        function addBarcodeListener(input) {
            console.log('Adding barcode listener to:', input);
            
            const newInput = input.cloneNode(true);
            input.parentNode.replaceChild(newInput, input);
            
            newInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.keyCode === 13) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    console.log('MUTATION OBSERVER: Captured Enter in barcode input');
                    
                    const value = this.value.trim();
                    if (!value) return false;
                    
                    window.Livewire.dispatch('handleBarcode', [value]);
                    
                    this.value = '';
                    
                    setTimeout(() => {
                        this.focus();
                    }, 100);
                    
                    return false;
                }
            }, true);
        }
        
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'childList') {
                    const barcodeInputs = document.querySelectorAll('input[name="scannedBarcode"]');
                    barcodeInputs.forEach(addBarcodeListener);
                }
            });
        });
        
        observer.observe(document.body, { childList: true, subtree: true });
        
        const barcodeInputs = document.querySelectorAll('input[name="scannedBarcode"]');
        barcodeInputs.forEach(addBarcodeListener);
        
        document.addEventListener('keydown', function(e) {
            const now = Date.now();
            
            if (isModalOpen) return;
            
            const target = e.target;
            if (target.tagName === 'INPUT' && 
                target.type === 'text' && 
                !target.name.includes('barcode') && 
                !target.id.includes('barcode')) {
                return;
            }
            
            if (e.key === 'Enter' || e.keyCode === 13) {
                if (target.form && !target.name.includes('barcode')) {
                    return;
                }
                
                if (barcodeBuffer.length > 0) {
                    e.preventDefault();
                    processCompleteBarcode(barcodeBuffer);
                    
                    const barcodeInput = document.querySelector('input[name="scannedBarcode"]');
                    if (barcodeInput && barcodeInput.value.trim()) {
                        window.Livewire.dispatch('handleBarcode', [barcodeInput.value.trim()]);
                        barcodeInput.value = '';
                    }
                    
                    return false;
                }
                
                const barcodeInput = document.querySelector('input[name="scannedBarcode"]');
                if (barcodeInput && barcodeInput.value.trim()) {
                    e.preventDefault();
                    window.Livewire.dispatch('handleBarcode', [barcodeInput.value.trim()]);
                    barcodeInput.value = '';
                    return false;
                }
                
                return;
            }
            
            if (e.key.length === 1 || (e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 65 && e.keyCode <= 90)) {
                if (barcodeTimeoutId) {
                    clearTimeout(barcodeTimeoutId);
                }
                
                if (barcodeBuffer.length === 0 || (now - lastKeyTime > 1000)) {
                    barcodeBuffer = e.key;
                } else {
                    barcodeBuffer += e.key;
                }
                
                lastKeyTime = now;
                
                barcodeTimeoutId = setTimeout(() => {
                    if (barcodeBuffer.length >= 4) {
                        processCompleteBarcode(barcodeBuffer);
                    }
                    barcodeBuffer = '';
                }, 500);
            }
        });
        
        console.log('Direct barcode capture setup complete');
    }
    
    function hackFilamentBarcodeInput() {
        setTimeout(() => {
            try {
                const inputs = document.querySelectorAll('input[name="scannedBarcode"]');
                if (inputs.length === 0) {
                    console.error('No barcode input found - cannot apply hack');
                    return;
                }

                if (document.getElementById('custom_barcode_input')) {
                    document.getElementById('custom_barcode_input').focus();
                    return;
                }

                const input = inputs[0];
                console.log('Found Filament barcode input:', input);
                
                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' || e.keyCode === 13) {
                        e.preventDefault();
                        e.stopPropagation();
                        
                        console.log('DIRECT EVENT: Captured Enter in original input');
                        
                        const value = this.value.trim();
                        if (value === '') return false;
                        
                        window.Livewire.dispatch('handleBarcode', [value]);
                        
                        this.value = '';
                        
                        return false;
                    }
                }, true); 
                
                const newInput = document.createElement('input');
                newInput.type = 'text';
                newInput.name = 'custom_barcode_input';
                newInput.id = 'custom_barcode_input';
                newInput.placeholder = 'Scan or enter barcode';
                newInput.autocomplete = 'off';
                
                for (let i = 0; i < input.attributes.length; i++) {
                    const attr = input.attributes[i];
                    if (attr.name !== 'name' && attr.name !== 'id' && attr.name !== 'wire:model') {
                        newInput.setAttribute(attr.name, attr.value);
                    }
                }
                
                newInput.className = input.className;
                
                newInput.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' || e.keyCode === 13) {
                        e.preventDefault();
                        e.stopPropagation();
                        
                        const value = this.value.trim();
                        if (value === '') return false;
                        
                        console.log('HACK: Processing barcode from modified input:', value);
                        
                        window.Livewire.dispatch('handleBarcode', [value]);
                        
                        this.value = '';
                        
                        setTimeout(() => {
                            this.focus();
                        }, 100);
                        
                        return false;
                    }
                });
                
                input.style.display = 'none';
                
                input.parentNode.insertBefore(newInput, input.nextSibling);
                
                const searchBtn = document.createElement('button');
                searchBtn.type = 'button';
                searchBtn.className = 'absolute inset-y-0 right-0 px-3 flex items-center bg-primary-600 hover:bg-primary-700 text-white rounded-r-md';
                searchBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" /></svg>';
                searchBtn.addEventListener('click', function() {
                    const value = newInput.value.trim();
                    if (value === '') return;
                    
                    console.log('HACK: Processing barcode from button click:', value);
                    
                    window.Livewire.dispatch('handleBarcode', [value]);
                    
                    newInput.value = '';
                    
                    setTimeout(() => {
                        newInput.focus();
                    }, 100);
                });
                
                const wrapper = input.closest('.fi-input-wrapper') || input.parentNode;
                wrapper.style.position = 'relative';
                
                wrapper.appendChild(searchBtn);
                
                newInput.focus();
                
                console.log('Successfully applied aggressive hack to barcode input');
            } catch (error) {
                console.error('Error applying barcode input hack:', error);
            }
        }, 500);
    }
    
    function handleDirectBarcodeSubmit(event) {
        if (event) {
            event.preventDefault();
        }
        
        const input = document.getElementById('direct-barcode-input');
        if (!input || input.value.trim() === '') {
            return false;
        }
        
        console.log('Direct form submit with barcode:', input.value);
        
        window.Livewire.dispatch('handleBarcode', [input.value.trim()]);
        
        input.value = '';
        
        return false;
    }
    
    let processedBarcodes = {};
    const MINIMUM_RESCAN_TIME = 10000; 
    
    let blockAllScans = false;
    
    let isModalOpen = false;
    let isForceOpeningModal = false;
    let lastModalToggleTime = 0;
    const MODAL_TOGGLE_COOLDOWN = 1000; 
    
    function setupBarcodeInputHandling() {
        const barcodeInputs = document.querySelectorAll('input[name="scannedBarcode"]');
        if (barcodeInputs.length === 0) return;
        
        const barcodeInput = barcodeInputs[0];
        console.log('Found barcode input:', barcodeInput);
        
        function submitBarcode(input) {
            const value = input.value.trim();
            if (value === '') return;
            
            console.log('Submitting barcode:', value);
            
            window.Livewire.dispatch('handleBarcode', [value]);
            
            setTimeout(() => {
                input.value = '';
                input.dispatchEvent(new Event('input', { bubbles: true }));
            }, 50);
        }
        
        barcodeInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.keyCode === 13) {
                console.log('Enter key pressed directly on barcode input');
                e.preventDefault();
                submitBarcode(this);
                return false;
            }
        });
        
        document.addEventListener('keydown', function(e) {
            if ((e.key === 'Enter' || e.keyCode === 13) && document.activeElement === barcodeInput) {
                console.log('Enter key pressed globally with barcode input focused');
                e.preventDefault();
                submitBarcode(barcodeInput);
                return false;
            }
        });
        
        const form = barcodeInput.closest('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                console.log('Form submit intercepted');
                
                submitBarcode(barcodeInput);
                return false;
            });
        }
        
        console.log('Barcode input handling setup complete with direct event handler');
    }
    
    async function processBarcode(code) {
        if (blockAllScans) {
            console.log('All scans are currently blocked');
            return;
        }
        
        if (isProcessing) {
            console.log('Already processing a barcode');
            return;
        }
        
        if (!keepScanning) {
            console.log('Scanner is not active');
            return;
        }
        
        const now = Date.now();
        
        try {
            blockAllScans = true;
            isProcessing = true;
            
            processedBarcodes[code] = now;
            lastScannedCode = code;
            lastScannedTime = now;
            
            showScanSuccess();
            updateStatus("Processing: " + code, true);
            
            await window.Livewire.dispatch('handleBarcode', [code]);
            
        } catch (error) {
            console.error('Error processing barcode:', error);
            updateStatus("Error: " + error.message, false);
        } finally {
            setTimeout(() => {
                blockAllScans = false;
                isProcessing = false;
                updateStatus("Ready for next scan", true);
            }, 2000); 
        }
    }

    function submitManualBarcode() {
        const input = document.getElementById('manual-barcode-input');
        if (input && input.value) {
            processBarcode(input.value);
            input.value = '';
        }
    }

    function initQuagga() {
        if (quaggaInitialized) {
            return;
        }
        
        const config = {
            inputStream: {
                name: "Live",
                type: "LiveStream",
                target: document.getElementById('camera-feed-container'),
                constraints: {
                    width: { min: 640 },
                    height: { min: 480 },
                    aspectRatio: { min: 1, max: 2 },
                    facingMode: "environment"
                }
            },
            decoder: {
                readers: [
                    "ean_reader",
                    "ean_8_reader",
                    "code_128_reader",
                    "code_39_reader",
                    "upc_reader",
                    "upc_e_reader"
                ]
            },
            locate: true,
            frequency: 10,
        };

        Quagga.init(config, function(err) {
            if (err) {
                console.error("Quagga initialization error:", err);
                updateStatus("Error initializing scanner: " + err.message, false);
                document.getElementById('fallback-container')?.classList.remove('hidden');
                return;
            }
            
            quaggaInitialized = true;
            
            const videoEl = document.querySelector('#camera-feed-container video');
            if (videoEl && videoEl.srcObject) {
                cameraStream = videoEl.srcObject;
            }
            
            Quagga.start();
            updateStatus("Ready to scan");
            
            keepScanning = true;
        });

        Quagga.onDetected(function(result) {
            if (!result || !result.codeResult || !result.codeResult.code || !keepScanning) {
                return;
            }
            
            const code = result.codeResult.code;
            
            if (isProcessing || blockAllScans) {
                return;
            }
            
            processBarcode(code);
        });
    }

    function openModal() {
        const now = Date.now();
        
        if (now - lastModalToggleTime < MODAL_TOGGLE_COOLDOWN) {
            console.log('Modal toggle cooldown in effect');
            return;
        }
        
        if (isModalOpen) {
            console.log('Modal already open');
            return;
        }
        
        lastModalToggleTime = now;
        
        isForceOpeningModal = true;
        
        resetScanCount();
        
        const modal = document.getElementById('scanner-modal');
        if (!modal) {
            console.error('Modal element not found');
            return;
        }
        
        modal.classList.remove('hidden');
        modal.style.display = 'flex';
        
        if (!quaggaInitialized) {
            initQuagga();
        } else {
            keepScanning = true;
        }
        
        isModalOpen = true;
        
        setTimeout(() => {
            isForceOpeningModal = false;
        }, 500);
    }

    function closeModal() {
        const now = Date.now();
        
        if (now - lastModalToggleTime < MODAL_TOGGLE_COOLDOWN) {
            console.log('Modal toggle cooldown in effect');
            return;
        }
        
        if (!isModalOpen) {
            console.log('Modal already closed');
            return;
        }
        
        lastModalToggleTime = now;
        
        keepScanning = false;
        
        if (quaggaInitialized) {
            try {
                Quagga.stop();
                quaggaInitialized = false;
            } catch (e) {
                console.error("Error stopping Quagga:", e);
            }
            
            if (cameraStream) {
                try {
                    cameraStream.getTracks().forEach(track => track.stop());
                    cameraStream = null;
                } catch (e) {
                    console.error("Error stopping camera stream:", e);
                }
            }
        }
        
        const modal = document.getElementById('scanner-modal');
        if (!modal) {
            console.error('Modal element not found');
            return;
        }
        
        modal.classList.add('hidden');
        modal.style.display = 'none';
        
        isProcessing = false;
        lastScannedCode = null;
        lastScannedTime = 0;
        blockAllScans = false;
        
        isModalOpen = false;
        
        document.dispatchEvent(new CustomEvent('closeModal'));
        
        if (window.Livewire) {
            window.Livewire.dispatch('modalClosed', { userClosed: true });
        }
    }
    
    function safeCheckModalState() {
        if (isForceOpeningModal || (Date.now() - lastModalToggleTime < MODAL_TOGGLE_COOLDOWN)) {
            return;
        }
        
        const modal = document.getElementById('scanner-modal');
        if (!modal) return;
        
        const isActuallyVisible = !modal.classList.contains('hidden') && modal.style.display !== 'none';
        
        if (isModalOpen !== isActuallyVisible) {
            console.log(`Modal state mismatch: isModalOpen=${isModalOpen}, actual=${isActuallyVisible}`);
            
            isModalOpen = isActuallyVisible;
            
            if (!isActuallyVisible) {
                keepScanning = false;
                
                if (quaggaInitialized) {
                    console.log('Stopping camera because modal is closed');
                    try {
                        Quagga.stop();
                        quaggaInitialized = false;
                    } catch (e) {
                        console.error("Error stopping Quagga:", e);
                    }
                    
                    if (cameraStream) {
                        try {
                            cameraStream.getTracks().forEach(track => track.stop());
                            cameraStream = null;
                        } catch (e) {
                            console.error("Error stopping camera stream:", e);
                        }
                    }
                }
                
                if (window.Livewire) {
                    window.Livewire.dispatch('modalClosed');
                }
            } else if (isActuallyVisible && !keepScanning) {
                keepScanning = true;
                
                if (!quaggaInitialized) {
                    initQuagga();
                }
            }
        }
        
        if (!isModalOpen && quaggaInitialized) {
            console.log('Extra safety: Camera still running with closed modal - stopping it');
            try {
                Quagga.stop();
                quaggaInitialized = false;
                
                if (cameraStream) {
                    cameraStream.getTracks().forEach(track => track.stop());
                    cameraStream = null;
                }
            } catch (e) {
                console.error("Error in safety camera shutdown:", e);
            }
        }
    }
    
    function updateScanCount() {
        scanCount++;
        const scanCountElement = document.getElementById('scan-count');
        if (scanCountElement) {
            scanCountElement.textContent = scanCount;
        }
    }
    
    function resetScanCount() {
        scanCount = 0;
        const scanCountElement = document.getElementById('scan-count');
        if (scanCountElement) {
            scanCountElement.textContent = '0';
        }
        
        processedBarcodes = {};
    }
    
    function clearScanCount() {
        resetScanCount();
        
        const lastScannedItem = document.getElementById('last-scanned-item');
        if (lastScannedItem) {
            lastScannedItem.classList.add('hidden');
        }
        
        updateStatus("Scan counter reset", false);
        setTimeout(() => {
            updateStatus("Ready for next scan", true);
        }, 1500);
    }
    
    function updateLastScannedItem(product) {
        if (!product) return;
        
        const lastScannedItem = document.getElementById('last-scanned-item');
        const lastItemName = document.getElementById('last-item-name');
        const lastItemPrice = document.getElementById('last-item-price');
        
        if (lastScannedItem && lastItemName && lastItemPrice) {
            lastItemName.textContent = product.name;
            lastItemPrice.textContent = `₱${parseFloat(product.price).toFixed(2)}`;
            lastScannedItem.classList.remove('hidden');
        }
    }

    function showScanSuccess() {
        const container = document.getElementById('camera-feed-container');
        if (!container) return;
        
        const flash = document.createElement('div');
        flash.className = 'scan-success-flash';
        container.appendChild(flash);
        
        setTimeout(() => {
            if (flash && flash.parentNode) {
                flash.remove();
            }
        }, 500);
    }

    function updateStatus(message, isSuccess = false) {
        const statusElement = document.getElementById('scanner-status');
        const statusTextElement = document.getElementById('scanner-status-text');
        
        if (statusElement) {
            statusElement.textContent = message;
            statusElement.classList.remove('hidden');
            statusElement.className = 'mb-3 text-center font-medium ' + 
                (isSuccess ? 'text-green-600 dark:text-green-400' : 'text-primary-600 dark:text-primary-400');
        }
        
        if (statusTextElement) {
            statusTextElement.textContent = message;
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        setupCustomInputFocus();
        
        focusDirectScanInput();
        
        setupBarcodeCapture();
        
        hackFilamentBarcodeInput();
        
        safeCheckModalState();
        
        Livewire.on('cart-updated', (data) => {
            console.log('Cart updated:', data);
            
            setTimeout(focusDirectScanInput, 200);
            
            if (data.keep_modal_open === true) {
                if (!isModalOpen) {
                    console.log('Reopening modal after cart update');
                    setTimeout(() => openModal(), 100);
                }
            }
        });
        
        document.addEventListener('livewire:initialized', () => {
            focusDirectScanInput();
            
            hackFilamentBarcodeInput();
            
            Livewire.hook('commit.after', () => {
                setTimeout(hackFilamentBarcodeInput, 300);
                setTimeout(focusDirectScanInput, 400);
            });
        });
        
        Livewire.on('scan-complete', (data) => {
            console.log('Scan complete event received:', data);
            
            if (!isModalOpen) {
                setTimeout(focusDirectScanInput, 200);
            }
            
            safeCheckModalState();
            
            if (data.success) {
                if (data.message !== 'Already processed this barcode') {
                    updateStatus(`Added: ${data.product.name}`, true);
                    
                    updateScanCount();
                    updateLastScannedItem(data.product);
                    
                    setTimeout(() => {
                        blockAllScans = false;
                        isProcessing = false;
                        updateStatus("Ready for next scan", true);
                    }, 2000);
                } else {
                    console.log('Duplicate scan ignored by server');
                    updateStatus("Item already scanned", false);
                    setTimeout(() => {
                        blockAllScans = false;
                        isProcessing = false;
                        updateStatus("Ready for next scan", true);
                    }, 1500);
                }
            } else {
                if (data.message === 'Already processed this barcode') {
                    console.log('Duplicate scan detected by server');
                    updateStatus("Item already scanned, try another", false);
                } else {
                    updateStatus(data.message || "Error processing barcode", false);
                }
                
                setTimeout(() => {
                    blockAllScans = false;
                    isProcessing = false;
                    updateStatus("Ready for next scan", false);
                }, 2000);
            }
            
            if (data.keep_modal_open === false) {
                closeModal();
            } else if (data.keep_modal_open === true && !isModalOpen) {
                openModal();
            }
            
            setTimeout(safeCheckModalState, 300);
        });
        
        Livewire.on('forceReopenModal', () => {
            console.log('Force reopening modal requested');
            if (!isModalOpen && !isForceOpeningModal) {
                openModal();
            }
        });
        
        Livewire.on('ensureModalClosed', () => {
            console.log('Server requested to ensure modal is closed');
            if (isModalOpen) {
                closeModal();
            }
        });
        
        Livewire.on('checkScanningState', () => {
            console.log('Checking if scanner should be reopened');
            setTimeout(() => {
                if (!isModalOpen && !isForceOpeningModal) {
                    console.log('Reopening scanner after unexpected close');
                    openModal();
                }
            }, 500);
        });
        
        const stateCheckInterval = setInterval(safeCheckModalState, 500); 
        
        document.addEventListener('livewire:initialized', () => {
            Livewire.hook('commit.after', () => {
                setTimeout(safeCheckModalState, 100);
            });
        });
        
        document.addEventListener('visibilitychange', () => {
            if (!document.hidden) {
                setTimeout(safeCheckModalState, 200);
            }
        });
        
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                const modal = document.getElementById('scanner-modal');
                const input = document.getElementById('manual-barcode-input');
                
                if (isModalOpen && input && document.activeElement === input) {
                    e.preventDefault();
                    submitManualBarcode();
                    return false;
                }
                
                const customInput = document.getElementById('custom_barcode_input');
                if (customInput && document.activeElement === customInput) {
                    return;
                }
            }
            
            if (e.key === 'Escape' && isModalOpen) {
                e.preventDefault();
                closeModal();
                return false;
            }
        });
    });
</script>
@endpush

<x-filament-panels::page>
    <div class="pos-container dark:bg-gray-900">
        
        <div id="barcode-overlay-container" style="position: absolute; z-index: 1000; opacity: 0; pointer-events: none;">
            <input 
                type="text" 
                id="barcode-overlay-input" 
                style="width: 100%; height: 40px;" 
                autocomplete="off"
            >
        </div>
        
        <form id="direct-barcode-form" style="display: none;" onsubmit="return handleDirectBarcodeSubmit(event)">
            <input type="text" id="direct-barcode-input" name="direct-barcode">
            <button type="submit">Submit</button>
        </form>
        
    <div id="scanner-modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-70 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-xl mx-4">
            <div class="px-6 py-4 border-b dark:border-gray-700 flex justify-between items-center">
                <h3 class="text-xl font-medium text-gray-900 dark:text-gray-100">Barcode Scanner</h3>
                <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 focus:outline-none">
                    <x-heroicon-m-x-mark class="w-6 h-6" />
                </button>
            </div>
            <div class="p-6">
                <div id="scanner-status" class="mb-3 text-center text-primary-600 dark:text-primary-400 font-medium hidden"></div>
                <div id="camera-feed-container">
                    <div class="scanner-overlay">
                        <div class="scanner-frame"></div>
                    </div>
                    <div class="scanner-status" id="scanner-status-text">Point camera at barcode</div>
                </div>
                
                <div class="mt-4 flex justify-between gap-2">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-600 text-white font-medium rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500">
                        <x-heroicon-o-x-circle class="w-5 h-5 inline-block mr-1" />
                        Done Scanning
                    </button>
                    
                    <div class="text-sm text-gray-600 dark:text-gray-400 flex items-center justify-center">
                        <span id="scan-count" class="font-medium">0</span> items scanned
                    </div>
                    
                    <button type="button" onclick="clearScanCount()" class="px-4 py-2 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500">
                        <x-heroicon-o-arrow-path class="w-5 h-5 inline-block mr-1" />
                        Reset Count
                    </button>
                </div>
                
                <div id="last-scanned-item" class="mt-4 p-3 bg-gray-100 dark:bg-gray-700 rounded-lg hidden">
                    <h4 class="text-base font-medium text-gray-900 dark:text-gray-100">Last Scanned:</h4>
                    <p id="last-item-name" class="text-sm mt-1 text-gray-800 dark:text-gray-200"></p>
                    <p id="last-item-price" class="text-sm text-gray-600 dark:text-gray-400"></p>
                </div>
                
                <div id="fallback-container" class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700 hidden">
                    <h4 class="text-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Scanner not working? Enter barcode manually:</h4>
                    <div class="flex gap-2">
                        <input type="text" id="manual-barcode-input" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500" placeholder="Enter barcode number">
                        <button type="button" onclick="submitManualBarcode()" class="px-4 py-2 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500">
                            Submit
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

        <div class="pos-grid">
            <div class="pos-left-panel">
                
                <button type="button" onclick="openModal()" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-primary-600 text-white rounded-lg hover:bg-primary-700 focus:ring-2 focus:ring-primary-500 dark:bg-primary-700 dark:hover:bg-primary-600">
                    <x-heroicon-o-camera class="w-5 h-5" />
                    <span class="font-medium">Scan Barcode</span>
                </button>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow flex-1 p-4">
                    <h2 class="text-base font-semibold text-gray-900 dark:text-gray-100 mb-4">Payment Details</h2>
                    
                    <div class="mb-4">
                        <div class="fi-fo-field-wrp">
                            <label class="fi-fo-field-wrp-label inline-flex items-center gap-x-2 text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                <span>Barcode</span>
                            </label>
                            
                            <div id="barcode-input-wrapper" class="mt-1 fi-input-wrp flex rounded-lg shadow-sm ring-1 ring-inset transition duration-75 bg-white dark:bg-gray-900 ring-gray-950/10 dark:ring-white/20">
                                <div class="min-w-0 flex-1">
                                    <input 
                                        type="text" 
                                        id="direct-scan-input" 
                                        class="fi-input block w-full border-0 bg-transparent py-1.5 px-3 text-gray-950 dark:text-white placeholder:text-gray-500 focus:ring-0 disabled:text-gray-500 sm:text-sm sm:leading-6 outline-none"
                                        placeholder="Scan or enter barcode"
                                        onkeydown="if(event.key === 'Enter' || event.keyCode === 13) { event.preventDefault(); processDirectScanInput(); return false; }"
                                        onfocus="document.getElementById('barcode-input-wrapper').classList.add('ring-2', 'ring-amber-500');"
                                        onblur="document.getElementById('barcode-input-wrapper').classList.remove('ring-2', 'ring-amber-500');"
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="[&_.fi-fo-component-ctn]:gap-3">
                        <style>

                            [data-field-wrapper="scannedBarcode"] {
                                display: none !important;
                            }
                        </style>
                        {{ $this->form }}
                    </div>

                    <div class="mt-6 space-y-3">
                        <div class="flex justify-between items-center text-lg">
                            <span class="total-label font-medium text-gray-700">Total:</span>
                            <span class="total-amount font-bold text-gray-900">₱{{ number_format($total, 2) }}</span>
                        </div>

                        @if($payment_amount > 0)
                            <div class="flex justify-between items-center text-green-600">
                                <span class="font-medium">Change:</span>
                                <span class="change-amount font-bold">₱{{ number_format($this->calculateChange(), 2) }}</span>
                            </div>
                        @endif
                    </div>

                    <button wire:click="checkout" @if(empty($cart)) disabled @endif class="mt-6 w-full flex justify-center items-center gap-2 bg-primary-600 text-white py-3 px-4 rounded-lg hover:bg-primary-700 focus:ring-2 focus:ring-primary-500 disabled:opacity-50 disabled:cursor-not-allowed dark:bg-primary-700 dark:hover:bg-primary-600">
                        <x-heroicon-o-shopping-bag class="w-5 h-5" />
                        <span class="font-medium">Complete Sale</span>
                    </button>
            </div>
        </div>
        
            <div class="pos-right-panel" x-data="{ cartItems: @entangle('cart').live }">
                    <div class="flex justify-between items-center p-4 border-b dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Shopping Cart</h2>
                        <button wire:click="clearCart" class="flex items-center gap-1 text-red-600 hover:text-red-800 dark:text-red-500 dark:hover:text-red-400">
                            <x-heroicon-o-trash class="w-4 h-4" />
                        <span>Clear Cart</span>
                        </button>
                    </div>

                <div class="cart-items">
                    <template x-if="cartItems.length > 0">
                        <template x-for="item in cartItems" :key="item.unique_id">
                            <div class="cart-item" :data-product-id="item.id">
                                <div class="flex-1">
                                    <h3 class="font-medium text-gray-900 dark:text-gray-100" x-text="item.name"></h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400" x-text="'₱' + parseFloat(item.price).toFixed(2)"></p>
                                </div>
                                <div class="cart-controls">
                                    <div class="quantity-control">
                                        <button @click="$wire.decrementQuantity(item.unique_id)" class="quantity-btn">
                                            <x-heroicon-o-minus class="w-4 h-4 text-gray-600 dark:text-gray-400"/>
                                        </button>
                                        <span class="quantity-display font-medium text-gray-900 dark:text-gray-100" x-text="item.quantity"></span>
                                        <button @click="$wire.incrementQuantity(item.unique_id)" class="quantity-btn">
                                            <x-heroicon-o-plus class="w-4 h-4 text-gray-600 dark:text-gray-400"/>
                                        </button>
                                    </div>
                                    <button @click="$wire.removeFromCart(item.unique_id)" class="p-1 text-red-500 hover:text-red-700">
                                        <x-heroicon-o-trash class="w-5 h-5"/>
                                    </button>
                                    <span class="font-medium text-gray-900 dark:text-gray-100 min-w-[80px] text-right" x-text="'₱' + parseFloat(item.subtotal).toFixed(2)"></span>
                                </div>
                            </div>
                        </template>
                    </template>

                    <template x-if="cartItems.length === 0">
                        <div class="flex flex-col items-center justify-center h-full text-gray-500 dark:text-gray-400">
                            <x-heroicon-o-shopping-cart class="w-16 h-16 mb-4" />
                            <p class="text-lg font-medium">Cart is empty</p>
                            <p class="text-sm">Scan a product or enter a barcode to add items</p>
                        </div>
                    </template>
                </div>
        </div>
        </div>
    </div>
</x-filament-panels::page>