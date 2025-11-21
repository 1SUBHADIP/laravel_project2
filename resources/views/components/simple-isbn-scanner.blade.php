<!-- Simple Inline ISBN Scanner - No Popup -->
<div x-data="inlineIsbnScanner()" class="space-y-3">
    <!-- ISBN Input Field -->
    <div class="relative">
        <input 
            type="text" 
            name="isbn"
            x-model="isbn"
            @input="validateIsbn"
            placeholder="Enter ISBN (10 or 13 digits)" 
            class="w-full pl-4 pr-16 py-3 border rounded-lg text-lg font-mono bg-slate-900 border-slate-700 text-white focus:ring-2 focus:ring-blue-500"
            :class="{ 
                'border-green-500 bg-green-900/20': isValidIsbn && isbn, 
                'border-red-500 bg-red-900/20': isbn && !isValidIsbn,
                'border-slate-700': !isbn
            }"
            required
        >
        
        <!-- Buttons -->
        <div class="absolute right-2 top-1/2 transform -translate-y-1/2 flex gap-1">
            <button 
                type="button"
                @click="clearIsbn"
                class="p-2 text-slate-400 hover:text-red-400 transition-colors"
                title="Clear ISBN"
            >
                <i class="fas fa-times"></i>
            </button>
            <button 
                type="button"
                @click="toggleScanner"
                class="p-2 text-slate-400 hover:text-blue-400 transition-colors"
                :class="{ 'text-blue-400': scannerActive }"
                title="Toggle Camera Scanner"
            >
                <i class="fas fa-camera"></i>
            </button>
        </div>
    </div>

    <!-- Status Messages -->
    <div class="space-y-2">
        <!-- Validation Status -->
        <div x-show="isbn" class="text-sm flex items-center gap-2">
            <div x-show="isValidIsbn" class="text-green-400 flex items-center gap-1">
                <i class="fas fa-check-circle"></i>
                <span>Valid ISBN ✓</span>
            </div>
            <div x-show="!isValidIsbn && isbn" class="text-red-400 flex items-center gap-1">
                <i class="fas fa-exclamation-circle"></i>
                <span>Invalid ISBN format</span>
            </div>
        </div>

        <!-- Scanner Status -->
        <div x-show="scannerActive" class="text-blue-400 text-sm flex items-center gap-2">
            <i class="fas fa-video animate-pulse"></i>
            <span>Camera active - Position book barcode in view below</span>
        </div>

        <!-- Help Text -->
        <div x-show="!scannerActive && !isbn" class="text-slate-400 text-xs">
            💡 Type ISBN manually or click <i class="fas fa-camera mx-1"></i> to use camera
        </div>
    </div>

    <!-- Inline Camera View (No Popup) -->
    <div x-show="scannerActive" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-95"
         class="bg-slate-800 rounded-lg p-4 border border-slate-600">
        
        <!-- Camera Preview -->
        <div class="relative bg-black rounded-lg overflow-hidden mb-3" style="height: 200px;">
            <video 
                x-ref="video" 
                class="w-full h-full object-cover"
                autoplay 
                muted 
                playsinline
            ></video>
            
            <!-- Scanning Guide -->
            <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                <div class="border-2 border-red-400 w-40 h-12 rounded opacity-70"></div>
            </div>
            
            <div class="absolute bottom-2 left-2 right-2 text-center">
                <div class="bg-black bg-opacity-60 text-white text-xs py-1 px-2 rounded">
                    Position barcode in red box
                </div>
            </div>
        </div>

        <!-- Manual Input and Controls -->
        <div class="flex gap-2">
            <input 
                type="text" 
                x-model="manualInput"
                @keydown.enter="useManualInput"
                placeholder="Or type ISBN here..."
                class="flex-1 px-3 py-2 bg-slate-900 border border-slate-600 rounded text-white text-sm font-mono"
            >
            <button 
                @click="useManualInput" 
                class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-3 rounded text-sm"
                :disabled="!manualInput"
            >
                Use
            </button>
            <button 
                @click="stopScanner" 
                class="bg-slate-600 hover:bg-slate-700 text-white py-2 px-3 rounded text-sm"
            >
                Close
            </button>
        </div>
    </div>
</div>

<script>
function inlineIsbnScanner() {
    return {
        isbn: '',
        manualInput: '',
        scannerActive: false,
        isValidIsbn: false,
        stream: null,
        
        init() {
            // Initialize with any existing value
            const existingInput = this.$el.querySelector('input[name="isbn"]');
            if (existingInput && existingInput.value) {
                this.isbn = existingInput.value;
                this.validateIsbn();
            }
        },
        
        validateIsbn() {
            this.isValidIsbn = this.isValidIsbnFormat(this.isbn);
        },
        
        isValidIsbnFormat(isbn) {
            if (!isbn) return false;
            
            // Remove non-digit characters (except X for ISBN-10)
            const clean = isbn.replace(/[^0-9X]/gi, '').toUpperCase();
            
            // Check length and basic format
            if (clean.length === 10) {
                return this.validateIsbn10(clean);
            } else if (clean.length === 13) {
                return this.validateIsbn13(clean);
            }
            return false;
        },
        
        validateIsbn10(isbn) {
            let sum = 0;
            for (let i = 0; i < 9; i++) {
                const digit = parseInt(isbn[i]);
                if (isNaN(digit)) return false;
                sum += digit * (10 - i);
            }
            
            const checksum = isbn[9];
            const calculatedChecksum = (11 - (sum % 11)) % 11;
            
            return (calculatedChecksum === 10 && checksum === 'X') || 
                   (calculatedChecksum < 10 && parseInt(checksum) === calculatedChecksum);
        },
        
        validateIsbn13(isbn) {
            let sum = 0;
            for (let i = 0; i < 12; i++) {
                const digit = parseInt(isbn[i]);
                if (isNaN(digit)) return false;
                sum += digit * (i % 2 === 0 ? 1 : 3);
            }
            
            const checksum = parseInt(isbn[12]);
            const calculatedChecksum = (10 - (sum % 10)) % 10;
            
            return checksum === calculatedChecksum;
        },
        
        clearIsbn() {
            this.isbn = '';
            this.isValidIsbn = false;
            this.manualInput = '';
        },
        
        async toggleScanner() {
            if (this.scannerActive) {
                this.stopScanner();
            } else {
                await this.startScanner();
            }
        },
        
        async startScanner() {
            try {
                if (!navigator.mediaDevices?.getUserMedia) {
                    this.showMessage('Camera not supported in this browser', 'error');
                    return;
                }
                
                this.stream = await navigator.mediaDevices.getUserMedia({
                    video: { 
                        facingMode: 'environment',
                        width: { ideal: 640 },
                        height: { ideal: 480 }
                    }
                });
                
                this.scannerActive = true;
                this.manualInput = '';
                
                this.$nextTick(() => {
                    if (this.$refs.video) {
                        this.$refs.video.srcObject = this.stream;
                    }
                });
                
                this.showMessage('Camera activated!', 'success');
                
            } catch (error) {
                console.error('Camera error:', error);
                this.showMessage('Cannot access camera. Please type ISBN manually.', 'error');
            }
        },
        
        stopScanner() {
            if (this.stream) {
                this.stream.getTracks().forEach(track => track.stop());
                this.stream = null;
            }
            this.scannerActive = false;
            this.manualInput = '';
        },
        
        useManualInput() {
            if (this.manualInput.trim()) {
                this.isbn = this.manualInput.trim();
                this.validateIsbn();
                this.stopScanner();
                
                this.showMessage(
                    this.isValidIsbn ? 'Valid ISBN entered!' : 'ISBN entered (check format)', 
                    this.isValidIsbn ? 'success' : 'warning'
                );
            }
        },
        
        showMessage(message, type = 'info') {
            const colors = {
                success: 'bg-green-500',
                warning: 'bg-yellow-500', 
                error: 'bg-red-500',
                info: 'bg-blue-500'
            };
            
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 z-40 px-4 py-2 rounded-lg text-white ${colors[type]} shadow-lg transition-opacity duration-300`;
            toast.textContent = message;
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 300);
            }, 2500);
        }
    }
}
</script>
