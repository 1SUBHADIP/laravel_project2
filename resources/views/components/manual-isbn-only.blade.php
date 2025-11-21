<!-- Simple Manual ISBN Input Only -->
<div x-data="manualIsbnOnly()" class="space-y-2">
    <!-- ISBN Input Field -->
    <div class="relative">
        <input 
            type="text" 
            name="isbn"
            x-model="isbn"
            @input="validateIsbn"
            placeholder="Enter ISBN (10 or 13 digits)" 
            class="w-full pl-4 pr-12 py-3 border rounded-lg text-lg font-mono bg-slate-900 border-slate-700 text-white focus:ring-2 focus:ring-blue-500"
            :class="{ 
                'border-green-500 bg-green-900/20': isValidIsbn && isbn, 
                'border-red-500 bg-red-900/20': isbn && !isValidIsbn,
                'border-slate-700': !isbn
            }"
            required
        >
        
        <!-- Clear Button -->
        <button 
            type="button"
            @click="clearIsbn"
            x-show="isbn"
            class="absolute right-3 top-1/2 transform -translate-y-1/2 p-1 text-slate-400 hover:text-red-400 transition-colors"
            title="Clear ISBN"
        >
            <i class="fas fa-times"></i>
        </button>
    </div>

    <!-- Status Messages -->
    <div class="space-y-1">
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

        <!-- Help Text -->
        <div x-show="!isbn" class="text-slate-400 text-xs">
            💡 Enter the ISBN number found on the back of the book (10 or 13 digits)
        </div>
        
        <!-- Examples -->
        <div x-show="!isbn" class="text-slate-500 text-xs">
            Examples: 9780134685991 or 0123456789
        </div>
    </div>
</div>

<script>
function manualIsbnOnly() {
    return {
        isbn: '',
        isValidIsbn: false,
        
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
        }
    }
}
</script>
