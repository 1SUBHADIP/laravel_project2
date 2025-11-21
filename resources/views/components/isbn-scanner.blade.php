<!-- ISBN Scanner Component -->
<div x-data="isbnScanner()" class="relative">
    <!-- ISBN Input with Scanner Button -->
    <div class="relative">
        <input 
            type="text" 
            x-model="isbn" 
            x-ref="isbnInput"
            @input="handleIsbnInput"
            placeholder="Enter or scan ISBN" 
            class="w-full pl-4 pr-12 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            :class="{ 'border-green-500': isValidIsbn, 'border-red-500': isbn && !isValidIsbn }"
        >
        
        <!-- Scanner Button -->
        <button 
            type="button"
            @click="toggleScanner"
            class="absolute right-2 top-1/2 transform -translate-y-1/2 p-1 text-gray-400 hover:text-gray-600"
            :class="{ 'text-blue-500': scannerActive }"
        >
            <i class="fas fa-qrcode text-lg"></i>
        </button>
    </div>

    <!-- Scanner Status -->
    <div x-show="scannerActive" class="mt-2 text-sm text-blue-600">
        <i class="fas fa-camera mr-1"></i>
        Scanner active - Point camera at barcode
    </div>

    <!-- ISBN Validation Status -->
    <div x-show="isbn" class="mt-1 text-sm">
        <span x-show="isValidIsbn" class="text-green-600">
            <i class="fas fa-check-circle mr-1"></i>
            Valid ISBN detected
        </span>
        <span x-show="!isValidIsbn && isbn" class="text-red-600">
            <i class="fas fa-exclamation-circle mr-1"></i>
            Invalid ISBN format
        </span>
    </div>

    <!-- Camera Preview (Hidden by default) -->
    <div x-show="scannerActive" class="mt-4">
        <div class="relative bg-black rounded-lg overflow-hidden" style="height: 300px;">
            <video 
                x-ref="video" 
                class="w-full h-full object-cover"
                autoplay 
                muted 
                playsinline
            ></video>
            
            <!-- Scanning Overlay -->
            <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                <div class="border-2 border-red-500 w-64 h-32 opacity-50"></div>
            </div>
            
            <!-- Close Scanner Button -->
            <button 
                type="button"
                @click="stopScanner"
                class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-2 hover:bg-red-600"
            >
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

    <!-- Manual ISBN Entry Help -->
    <div x-show="!scannerActive" class="mt-2 text-xs text-gray-500">
        Enter 10 or 13 digit ISBN, or click the scanner icon to use camera
    </div>
</div>

<script>
function isbnScanner() {
    return {
        isbn: '',
        scannerActive: false,
        isValidIsbn: false,
        stream: null,
        
        init() {
            // Initialize with any existing ISBN value
            if (this.$refs.isbnInput.value) {
                this.isbn = this.$refs.isbnInput.value;
                this.validateIsbn();
            }
        },
        
        handleIsbnInput() {
            this.validateIsbn();
            // Update the actual form input if this is used in a form
            this.$refs.isbnInput.value = this.isbn;
            this.$refs.isbnInput.dispatchEvent(new Event('input'));
        },
        
        validateIsbn() {
            if (!this.isbn) {
                this.isValidIsbn = false;
                return;
            }
            
            // Remove any non-digit characters except X
            const cleanIsbn = this.isbn.replace(/[^0-9X]/gi, '').toUpperCase();
            
            // Check if it's a valid 10 or 13 digit ISBN
            if (cleanIsbn.length === 10) {
                this.isValidIsbn = this.validateIsbn10(cleanIsbn);
            } else if (cleanIsbn.length === 13) {
                this.isValidIsbn = this.validateIsbn13(cleanIsbn);
            } else {
                this.isValidIsbn = false;
            }
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
        
        async toggleScanner() {
            if (this.scannerActive) {
                this.stopScanner();
            } else {
                await this.startScanner();
            }
        },
        
        async startScanner() {
            try {
                // Check if getUserMedia is supported
                if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                    alert('Camera access is not supported in this browser');
                    return;
                }
                
                // Request camera access
                this.stream = await navigator.mediaDevices.getUserMedia({
                    video: { 
                        facingMode: 'environment', // Use back camera if available
                        width: { ideal: 1280 },
                        height: { ideal: 720 }
                    }
                });
                
                this.scannerActive = true;
                
                // Set video stream
                this.$nextTick(() => {
                    if (this.$refs.video) {
                        this.$refs.video.srcObject = this.stream;
                    }
                });
                
                // Start barcode detection (simplified version)
                this.startBarcodeDetection();
                
            } catch (error) {
                console.error('Error accessing camera:', error);
                alert('Unable to access camera. Please ensure camera permissions are granted.');
            }
        },
        
        stopScanner() {
            if (this.stream) {
                this.stream.getTracks().forEach(track => track.stop());
                this.stream = null;
            }
            this.scannerActive = false;
        },
        
        startBarcodeDetection() {
            // This is a simplified version - in a real implementation,
            // you would use a library like QuaggaJS or ZXing for barcode detection
            // For now, we'll just provide manual input functionality
            
            // Simulate barcode detection with keyboard input
            document.addEventListener('keydown', (e) => {
                if (this.scannerActive && e.key === 'Enter' && this.isbn) {
                    this.stopScanner();
                }
            });
        },
        
        // Method to be called when a barcode is detected (for future implementation)
        onBarcodeDetected(code) {
            this.isbn = code;
            this.validateIsbn();
            this.stopScanner();
            
            // Show success message
            this.$dispatch('isbn-scanned', { isbn: code });
        }
    }
}
</script>
