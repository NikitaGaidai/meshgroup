<template>
    <section class="section">
        <slot>
            <div class="box has-text-centered is-shadowless border-0">
                <h3 class="subtitle is-3 has-text-centered">В данной категории нет товаров.</h3>
                <button class="button is-dark" @click="toggleCreateModal" v-if="this.$root.logged">Добавить товар</button>
            </div>
        </slot>
        <!-- Create New Product Modal -->
        <div class="modal is-active" role="dialog" id="modal-add-product" v-if="isCreateModal">
            <div class="modal-background"></div>
            <div class="modal-content">
                <div class="box">
                    <nav class="level">
                        <div class="level-left">
                            <div class="level-item">
                                <p class="subtitle is-5">Добавить товар в категорию {{ category_name }}</p>
                            </div>
                        </div>
                        <div class="level-right">
                            <div class="level-item">
                                <button type="button" class="button is-white" @click="toggleCreateModal">&times;</button>
                            </div>
                        </div>
                    </nav>
                    <hr>
                    <form role="form">
                        <div class="field">
                            <label class="label">Название товара</label>
                            <div class="control">
                                <input class="input" type="text" placeholder='Например: Шарф "Одуванчик"' v-model="formAddProduct.name">
                            </div>
                            <br/>
                            <label class="label">Описание товара</label>
                            <div class="control">
                                <input class="input" type="text" placeholder="Например: Самый легкий шарф в мире" v-model="formAddProduct.description">
                            </div>
                            <br/>
                            <label class="label">Стоимость товара</label>
                            <div class="control">
                                <input class="input" type="number" placeholder="100.50" v-model="formAddProduct.price">
                            </div>
                        </div>
                    </form>
                    <hr>
                    <div class="buttons is-right">
                        <a class="button is-primary" @click="addProduct">Добавить товар</a>
                        <a class="button is-dark" @click="toggleCreateModal">Отмена</a>
                    </div>
                </div>
            </div>
            <button class="modal-close is-large" aria-label="close" @click="toggleCreateModal"></button>
        </div>
    </section>
</template>

<script>
    export default {
        name: "ProductWrapper",
        props: ['category_id', 'category_name'],
        data: function ()
        {
            return {
                isCreateModal: false,

                formAddProduct: {
                    errors: [],
                    name: '',
                    description: '',
                    price: null,
                    category_id: this.category_id,
                },
            }
        },

        watch: {
            category_id: function () {
                this.formAddProduct.category_id = this.category_id;
            }
        },

        methods: {
            toggleCreateModal ()
            {
                this.isCreateModal = !this.isCreateModal;
            },

            addProduct ()
            {
                axios.post('/api/products/create', this.formAddProduct)
                    .then(response => {
                        console.log(response);
                        this.toggleCreateModal();
                        this.$emit('product_created', 'ProductWrapper');
                    });
            }
        }
    }
</script>

<style scoped>

</style>