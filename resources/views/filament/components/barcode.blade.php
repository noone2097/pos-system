<div class="p-4 space-y-4">
    <div class="text-center">
        <img src="data:image/png;base64,{{ DNS1DFacade::getBarcodePNG($barcode, 'C128', 2, 60) }}" 
             alt="{{ $barcode }}" 
             class="mx-auto">
        <div class="mt-2 text-sm font-mono">{{ $barcode }}</div>
    </div>
    <div class="flex justify-center space-x-2">
        <button type="button" 
                onclick="copyToClipboard('{{ $barcode }}')"
                class="filament-button filament-button-size-sm inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset min-h-[2rem] px-3 text-sm text-gray-800 bg-white border-gray-300 hover:bg-gray-50 focus:ring-primary-600">
            <x-heroicon-m-clipboard-document class="w-4 h-4" />
            Copy Code
        </button>
    </div>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        const notification = document.createElement('div');
        notification.className = 'fixed bottom-4 right-4 bg-primary-500 text-white px-4 py-2 rounded-lg shadow-lg';
        notification.textContent = 'Barcode copied to clipboard';
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 2000);
    });
}
</script>