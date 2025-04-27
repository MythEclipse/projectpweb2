function productList() {
    return {
        search: '',
        modalOpen: false,
        selectedProduct: null,
        showBuyModal: false,

        filterProduct(name) {
            return name.includes(this.search.toLowerCase());
        },

        openModal(product) {
            this.selectedProduct = product;
            this.modalOpen = true;
        },

        closeModal() {
            this.selectedProduct = null;
            this.modalOpen = false;
        }
    }
}
