@extends('layout')

@section('title', 'Edit Book')

@section('content')
<form action="{{ route('books.update', $book) }}" method="POST" class="mt-3 space-y-4" x-data="bookForm()">
  @csrf
  @method('PUT')
  <div>
    <label class="block text-sm text-slate-300 mb-1">Title</label>
    <input type="text" name="title" class="w-full rounded-md border border-slate-700 bg-slate-900 px-3 py-2 text-sm" value="{{ old('title', $book->title) }}" required>
  </div>
  <div>
    <label class="block text-sm text-slate-300 mb-1">Author</label>
    <input type="text" name="author" class="w-full rounded-md border border-slate-700 bg-slate-900 px-3 py-2 text-sm" value="{{ old('author', $book->author) }}" required>
  </div>
  <div>
    <label class="block text-sm text-slate-300 mb-1">Category</label>
    <select name="category_id" class="w-full rounded-md border border-slate-700 bg-slate-900 px-3 py-2 text-sm">
      <option value="">None</option>
      @foreach($categories as $category)
        <option value="{{ $category->id }}" @selected(old('category_id', $book->category_id) == $category->id)>{{ $category->name }}</option>
      @endforeach
    </select>
  </div>
  <div>
    <label class="block text-sm text-slate-300 mb-1">ISBN</label>
    <div class="relative">
      <input 
        type="text" 
        name="isbn" 
        x-model="isbn"
        x-ref="isbnInput"
        @input="validateIsbn"
        placeholder="Enter or scan ISBN" 
        class="w-full pl-4 pr-12 py-2 border border-slate-700 bg-slate-900 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-white"
        :class="{ 'border-green-500': isValidIsbn, 'border-red-500': isbn && !isValidIsbn }"
        value="{{ old('isbn', $book->isbn) }}"
        required
      >
      
      <!-- Scanner Button -->
      <button 
        type="button"
        @click="toggleScanner"
        class="absolute right-2 top-1/2 transform -translate-y-1/2 p-1 text-slate-400 hover:text-slate-200"
        :class="{ 'text-blue-400': scannerActive }"
      >
        <i class="fas fa-qrcode text-lg"></i>
      </button>
    </div>

    <!-- Scanner Status -->
    <div x-show="scannerActive" class="mt-2 text-sm text-blue-400">
      <i class="fas fa-camera mr-1"></i>
      Scanner active - Point camera at barcode or press Enter after typing ISBN
    </div>

    <!-- ISBN Validation Status -->
    <div x-show="isbn" class="mt-1 text-sm">
      <span x-show="isValidIsbn" class="text-green-400">
        <i class="fas fa-check-circle mr-1"></i>
        Valid ISBN detected
      </span>
      <span x-show="!isValidIsbn && isbn" class="text-red-400">
        <i class="fas fa-exclamation-circle mr-1"></i>
        Invalid ISBN format
      </span>
    </div>

    <!-- Camera Preview -->
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
          <div class="absolute text-white text-sm bg-black bg-opacity-50 px-2 py-1 rounded">
            Position ISBN barcode within the red rectangle
          </div>
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
    <div x-show="!scannerActive" class="mt-2 text-xs text-slate-400">
      Click the <i class="fas fa-qrcode"></i> icon to use camera scanner for ISBN detection
    </div>
  </div>
  <div>
    <label class="block text-sm text-slate-300 mb-1">Total Copies</label>
    <input type="number" name="total_copies" class="w-full rounded-md border border-slate-700 bg-slate-900 px-3 py-2 text-sm" value="{{ old('total_copies', $book->total_copies) }}" min="1" required>
  </div>
  <div class="flex gap-2">
    <a href="{{ route('books.index') }}" class="inline-flex items-center rounded-md border border-slate-600 px-3 py-2 text-sm hover:bg-slate-800">Cancel</a>
    <button class="inline-flex items-center rounded-md bg-primary px-3 py-2 text-sm font-medium text-white hover:bg-primary-600">Update</button>
  </div>
</form>

<script>
function bookForm() {
    return {
        isbn: '{{ old("isbn", $book->isbn) }}',
        scannerActive: false,
        isValidIsbn: false,
        stream: null,
        
        init() {
            if (this.isbn) {
                this.validateIsbn();
            }
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
                    this.showToast('Camera access is not supported in this browser', 'error');
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
                
                this.showToast('Camera activated! Type ISBN and press Enter to scan', 'success');
                
                // Add keyboard listener for Enter key
                document.addEventListener('keydown', this.handleKeyPress);
                
            } catch (error) {
                console.error('Error accessing camera:', error);
                this.showToast('Unable to access camera. Please ensure camera permissions are granted.', 'error');
            }
        },
        
        handleKeyPress(e) {
            if (e.key === 'Enter' && this.scannerActive && this.isbn) {
                this.onBarcodeDetected(this.isbn);
            }
        },
        
        stopScanner() {
            if (this.stream) {
                this.stream.getTracks().forEach(track => track.stop());
                this.stream = null;
            }
            this.scannerActive = false;
            document.removeEventListener('keydown', this.handleKeyPress);
        },
        
        onBarcodeDetected(code) {
            this.isbn = code;
            this.validateIsbn();
            this.stopScanner();
            this.showToast('ISBN updated successfully: ' + code, 'success');
        },
        
        showToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 z-50 px-4 py-2 rounded-lg text-white shadow-lg transition-all duration-300 ${
                type === 'success' ? 'bg-green-500' : 
                type === 'error' ? 'bg-red-500' : 'bg-blue-500'
            }`;
            toast.innerHTML = `<i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'exclamation-triangle' : 'info'} mr-2"></i>${message}`;
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }
    }
}
</script>
@endsection


