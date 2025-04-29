// public/js/productList.js

function productList() {
    return {
        // --- Modal Visibility ---
        showBuyModal: false,
        modalOpen: false, // For the detail modal

        // --- Loading State ---
        loading: false, // Primarily for loading options in the buy modal

        // --- Selected Product Data ---
        selectedProduct: null, // Holds the full product object when a modal is opened

        // --- Buy Modal Form State ---
        selectedSizeId: '',    // Bound to the size select dropdown (name="size_id")
        selectedColorId: '',   // Bound to the color select dropdown (name="color_id")
        quantity: 1,         // Bound to the quantity input (name="quantity")
        maxStock: 0,         // Calculated max stock for the selected size/color combo
        availableSizes: [],    // Dynamically populated list of sizes for the dropdown
        availableColors: [],   // Dynamically populated list of colors for the dropdown

        // --- Methods ---

        // Opens the Detail Modal
        openModal(product) {
            this.selectedProduct = product; // Store the product data
            this.modalOpen = true;          // Show the modal
        },

        // Closes the Detail Modal
        closeModal() {
            this.modalOpen = false;
            // Delay clearing to allow fade-out transition
            setTimeout(() => { this.selectedProduct = null; }, 300);
        },

        // Opens the Buy Modal
        openBuyModal(product) {
            // console.log("Opening Buy Modal for:", product); // Debugging
            this.selectedProduct = product;
            this.loading = true;        // Show loading indicator immediately
            this.resetBuyFormState(); // Clear previous selections/states

            // Simulate a small delay if needed, or directly populate
            // In a real scenario, if options were fetched via API, you'd do it here.
            // Since data is already in `product.stock_combinations`, we populate directly.
            this.populateInitialOptions();

            this.loading = false;       // Hide loading indicator
            this.showBuyModal = true;       // Show the modal
        },

        // Closes the Buy Modal
        closeBuyModal() {
            this.showBuyModal = false;
             // Delay clearing to allow fade-out transition
            setTimeout(() => {
                this.selectedProduct = null;
                this.resetBuyFormState();
            }, 300);
        },

        // Resets the state of the buy modal form
        resetBuyFormState() {
            this.selectedSizeId = '';
            this.selectedColorId = '';
            this.quantity = 1;
            this.maxStock = 0;
            this.availableSizes = [];
            this.availableColors = [];
        },

        // Populates initial dropdown options when buy modal opens
        populateInitialOptions() {
            if (!this.selectedProduct || !this.selectedProduct.stock_combinations) {
                console.error("No product or stock combinations found for populating options.");
                return;
            }

            const combinations = this.selectedProduct.stock_combinations;
            // console.log("Combinations:", combinations); // Debugging

            // Get unique sizes that exist in combinations
            // Use Map to ensure uniqueness based on size.id
            const uniqueSizes = [...new Map(combinations
                .filter(c => c.size) // Filter out combinations without a size object
                .map(item => [item.size.id, { id: item.size.id, name: item.size_name }])) // Use pre-processed name
                .values()];

            // Get unique colors that exist in combinations
            const uniqueColors = [...new Map(combinations
                .filter(c => c.color) // Filter out combinations without a color object
                .map(item => [item.color.id, { id: item.color.id, name: item.color_name }])) // Use pre-processed name
                .values()];

            // console.log("Unique Sizes:", uniqueSizes);   // Debugging
            // console.log("Unique Colors:", uniqueColors); // Debugging

            this.availableSizes = uniqueSizes;
            this.availableColors = uniqueColors;

             // Important: Ensure selections are reset *after* populating
             this.selectedSizeId = '';
             this.selectedColorId = '';
             this.maxStock = 0;
        },

        // Updates the list of available colors based on the selected size
        updateAvailableColors() {
            if (!this.selectedProduct || !this.selectedProduct.stock_combinations) return;

            const combinations = this.selectedProduct.stock_combinations;
            const sizeId = parseInt(this.selectedSizeId);

            if (!sizeId) {
                // If no size is selected, show all unique colors again
                 this.availableColors = [...new Map(combinations
                    .filter(c => c.color)
                    .map(item => [item.color.id, { id: item.color.id, name: item.color_name }]))
                    .values()];
                this.selectedColorId = ''; // Reset color selection
                this.maxStock = 0; // Reset stock
                return;
            }

            // Find colors available *for the selected size*
            const colorsForSize = combinations
                .filter(c => c.size_id === sizeId && c.color) // Match selected size_id and ensure color exists
                .map(c => ({ id: c.color.id, name: c.color_name })); // Map to the color object/data needed

            // Update availableColors with unique values for the selected size
            this.availableColors = [...new Map(colorsForSize.map(item => [item.id, item])).values()];

            // If the currently selected color is no longer in the available list for the new size, reset it
            if (this.selectedColorId && !this.availableColors.some(c => c.id === parseInt(this.selectedColorId))) {
                this.selectedColorId = '';
            }

            // Always update max stock after selection changes
            this.updateMaxStock();
        },

        // Updates the list of available sizes based on the selected color (Optional but good UX)
        updateAvailableSizes() {
            if (!this.selectedProduct || !this.selectedProduct.stock_combinations) return;

            const combinations = this.selectedProduct.stock_combinations;
            const colorId = parseInt(this.selectedColorId);

            if (!colorId) {
                // If no color is selected, show all unique sizes again
                this.availableSizes = [...new Map(combinations
                    .filter(c => c.size)
                    .map(item => [item.size.id, { id: item.size.id, name: item.size_name }]))
                    .values()];
                 this.selectedSizeId = ''; // Reset size selection
                 this.maxStock = 0; // Reset stock
                 return;
            }

            // Find sizes available *for the selected color*
            const sizesForColor = combinations
                .filter(c => c.color_id === colorId && c.size) // Match selected color_id and ensure size exists
                .map(c => ({ id: c.size.id, name: c.size_name })); // Map to the size object/data needed

            // Update availableSizes with unique values for the selected color
            this.availableSizes = [...new Map(sizesForColor.map(item => [item.id, item])).values()];

            // If the currently selected size is no longer in the available list for the new color, reset it
             if (this.selectedSizeId && !this.availableSizes.some(s => s.id === parseInt(this.selectedSizeId))) {
                this.selectedSizeId = '';
            }

            // Always update max stock after selection changes
            this.updateMaxStock();
        },


        // Calculates and updates the maximum stock based on selected size and color
        updateMaxStock() {
            if (!this.selectedProduct || !this.selectedProduct.stock_combinations || !this.selectedSizeId || !this.selectedColorId) {
                this.maxStock = 0; // Not enough info to determine stock
                this.validateQuantity(); // Re-validate quantity based on potentially zero stock
                return;
            }

            const sizeId = parseInt(this.selectedSizeId);
            const colorId = parseInt(this.selectedColorId);

            const combination = this.selectedProduct.stock_combinations.find(
                c => c.size_id === sizeId && c.color_id === colorId
            );

            this.maxStock = combination ? combination.stock : 0; // Set to 0 if combo not found
            // console.log(`Max stock for Size ${sizeId} / Color ${colorId}: ${this.maxStock}`); // Debugging

            // Re-validate quantity against the new maxStock
            this.validateQuantity();
        },

        // Validates the quantity input
        validateQuantity() {
             // Ensure quantity is treated as a number
            let currentQuantity = parseInt(this.quantity);

            // Handle non-numeric input or NaN
            if (isNaN(currentQuantity)) {
                currentQuantity = 1;
            }

            // Minimum is 1
            if (currentQuantity < 1) {
                currentQuantity = 1;
            }

            // Maximum is maxStock (only if maxStock > 0)
            if (this.maxStock > 0 && currentQuantity > this.maxStock) {
                currentQuantity = this.maxStock;
            }

            // If maxStock is 0 (or less), and selections are made, quantity should ideally be unenterable
            // but we can force it to 1 here, the submit button logic handles the disabling.
            if (this.maxStock <= 0 && this.selectedSizeId && this.selectedColorId) {
                 currentQuantity = 1; // Or keep as is, but ensure button is disabled
            }

             // Update the model value if it changed
             if (this.quantity !== currentQuantity) {
                this.quantity = currentQuantity;
            }
        },

        // --- Initialization Logic (Optional) ---
        // init() {
        //     console.log('Product List Alpine component initialized.');
        //     // You could potentially load something initially here if needed
        // }
    };
}
