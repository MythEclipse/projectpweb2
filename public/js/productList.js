function productList() {
    return {
        search: '',
        modalOpen: false,
        selectedProduct: null,

        showBuyModal: false,
        availableSizes: [],
        availableColors: [],
        maxStock: 1,
        loading: false,

        filterProduct(name) {
            return name.toLowerCase().includes(this.search.toLowerCase());
        },

        openModal(product) {
            this.selectedProduct = product;
            this.modalOpen = true;
            this.loadProductData();
        },

        closeModal() {
            this.selectedProduct = null;
            this.modalOpen = false;
        },

        openBuyModal(product) {
            this.selectedProduct = product;
            this.showBuyModal = true;
            this.loadProductData();  // Ensure to load product data when opening the buy modal
        },

        closeBuyModal() {
            this.selectedProduct = null;
            this.showBuyModal = false;
        },

        loadProductData() {
            if (!this.selectedProduct) return;

            this.loading = true;
            const productName = this.selectedProduct.name.replace(/\s+/g, '-').toLowerCase();

            fetch(`/products/${productName}/options`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Gagal mengambil data produk.');
                    }
                    return response.json();
                })
                .then(data => {
                    this.availableSizes = data.sizes || [];
                    this.availableColors = data.colors || [];
                    this.maxStock = data.max_stock || 1;
                })
                .catch(error => {
                    console.error(error);
                    alert('Gagal memuat data produk, silakan coba lagi.');
                })
                .finally(() => {
                    this.loading = false;
                });
        }
    }
}
