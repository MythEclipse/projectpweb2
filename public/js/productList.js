function productList() {
    return {
        // State untuk search dan modal detail
        search: '',
        modalOpen: false,
        selectedProduct: null,

        // State untuk modal beli
        showBuyModal: false,
        availableSizes: [],
        availableColors: [],
        maxStock: 1,
        loading: false,

        // Method untuk filter produk
        filterProduct(name) {
            return name.toLowerCase().includes(this.search.toLowerCase());
        },

        // Method untuk buka modal detail produk
        openModal(product) {
            this.selectedProduct = product;
            this.modalOpen = true;
            this.loadProductData(); // Panggil data produk ketika modal dibuka
        },

        // Method untuk tutup modal detail
        closeModal() {
            this.selectedProduct = null;
            this.modalOpen = false;
        },

        // Method untuk load data produk ketika modal beli dibuka
        loadProductData() {
            if (!this.selectedProduct) return;

            this.loading = true;

            // Ganti spasi dengan tanda minus (-) di nama produk
            const productName = this.selectedProduct.name.replace(/\s+/g, '-');

            fetch(`/products/${productName}/options`) // Gunakan nama produk yang sudah diganti
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Gagal mengambil data produk.');
                    }
                    return response.json();
                })
                .then(data => {
                    this.availableSizes = data.sizes;
                    this.availableColors = data.colors;
                    this.maxStock = data.max_stock;
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
