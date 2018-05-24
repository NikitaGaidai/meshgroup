<template>
    <section class="section">
        <!-- Breadcrumbs -->
        <nav class="breadcrumb" aria-label="breadcrumbs">
            <ul>
                <li v-for="parent in category.parents" :key="parent.id">
                    <router-link :to="'/category/' + parent.id">{{ parent.name }}</router-link>
                </li>
                <li class="is-active">
                    <a href="#" aria-current="page">{{ category.name }}</a>
                </li>
            </ul>
        </nav>

        <div class="level">
            <!-- Title -->
            <div class="level-left">
                <div class="level-item">
                    <h1 class="title">
                        {{ category.name }}
                    </h1>
                </div>
            </div>
            <!-- Control Buttons -->
            <div class="level-right">
                <div class="level-item">
                    <div class="buttons">
                        <button class="button is-dark" @click="toggleUpdateCategoryModal" v-if="this.$root.logged">Изменить</button>
                        <button class="button is-dark" @click="toggleCreateProductModal" v-if="this.$root.logged">Добавить товар</button>
                        <button class="button is-danger" @click="toggleDeleteCategoryModal" v-if="this.$root.logged">Удалить</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products List -->
        <product-wrapper :category_id="id" :category_name="category.name" @product_created="fetchProducts">

            <product-component v-for="product in category.products" :key="product.id"
                               :product="product"
                               @editproduct="toggleUpdateProductModal(product)"
                               @deleteproduct="toggleDeleteProductModal(product)"></product-component>

            <product-component v-for="product in category.subproducts" :key="product.id"
                               :product="product"
                               @editproduct="toggleUpdateProductModal(product)"
                               @deleteproduct="toggleDeleteProductModal(product)"></product-component>

        </product-wrapper>

        <!-- Update Category Modal -->
        <div class="modal is-active" role="dialog" id="modal-edit-category" v-if="showUpdateCategoryModal">
            <div class="modal-background"></div>
            <div class="modal-content">
                <div class="box">
                    <nav class="level">
                        <div class="level-left">
                            <div class="level-item">
                                <p class="subtitle is-5">Изменить категорию</p>
                            </div>
                        </div>
                        <div class="level-right">
                            <div class="level-item">
                                <button type="button" class="button is-white" @click="toggleUpdateCategoryModal">&times;</button>
                            </div>
                        </div>
                    </nav>
                    <hr>
                    <form role="form">
                        <div class="field">
                            <label class="label">Название категории</label>
                            <div class="control">
                                <input class="input" type="text" placeholder="Например: ООО ''Рога и копыта''" v-model="category.name">
                            </div>
                            <br/>
                            <label class="label">Родительская категория</label>
                            <div class="control">
                                <div class="select is-fullwidth">
                                    <select v-model="category.parent_id">
                                        <option value="null">Без родителя</option>
                                        <option v-for="category in categories" :value="category.id">{{ setOptionName(category) }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                    <hr>
                    <div class="buttons is-right">
                        <a class="button is-primary" @click="updateCategory">Изменить</a>
                        <a class="button is-dark" @click="toggleUpdateCategoryModal">Отмена</a>
                    </div>
                </div>
            </div>
            <button class="modal-close is-large" aria-label="close" @click="toggleUpdateCategoryModal"></button>
        </div>

        <!-- Delete Category Modal -->
        <div class="modal is-active" role="dialog" id="modal-delete-category" v-if="showDeleteCategoryModal">
            <div class="modal-background"></div>
            <div class="modal-content">
                <div class="box">
                    <nav class="level">
                        <div class="level-left">
                            <div class="level-item">
                                <h5>Подтверждение удаления</h5>
                            </div>
                        </div>
                        <div class="level-right">
                            <div class="level-item">
                                <button type="button" class="button is-white" @click="toggleDeleteCategoryModal">&times;</button>
                            </div>
                        </div>
                    </nav>
                    <hr>
                    <p>
                        Вы действительно хотите удалить категорию {{ category.name }}? <br/>
                        Все товары в этой категории также будут удалены!
                    </p>
                    <hr>
                    <div class="buttons is-right">
                        <a class="button is-primary" @click="deleteCategory">Удалить</a>
                        <a class="button is-dark" @click="toggleDeleteCategoryModal">Отмена</a>
                    </div>
                </div>
            </div>
            <button class="modal-close is-large" aria-label="close" @click="toggleDeleteCategoryModal"></button>
        </div>

        <!-- Create New Product Modal -->
        <div class="modal is-active" role="dialog" id="modal-add-product" v-if="showCreateProductModal">
            <div class="modal-background"></div>
            <div class="modal-content">
                <div class="box">
                    <nav class="level">
                        <div class="level-left">
                            <div class="level-item">
                                <p class="subtitle is-5">Добавить товар в категорию {{ category.name }}</p>
                            </div>
                        </div>
                        <div class="level-right">
                            <div class="level-item">
                                <button type="button" class="button is-white" @click="toggleCreateProductModal">&times;</button>
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
                        <a class="button is-primary" @click="createProduct">Добавить товар</a>
                        <a class="button is-dark" @click="toggleCreateProductModal">Отмена</a>
                    </div>
                </div>
            </div>
            <button class="modal-close is-large" aria-label="close" @click="toggleCreateProductModal"></button>
        </div>

        <!-- Update Product Modal -->
        <div class="modal is-active" role="dialog" id="modal-update-product" v-if="showUpdateProductModal">
            <div class="modal-background"></div>
            <div class="modal-content">
                <div class="box">
                    <nav class="level">
                        <div class="level-left">
                            <div class="level-item">
                                <p class="subtitle is-5">Редактирование товара {{ product.name }}</p>
                            </div>
                        </div>
                        <div class="level-right">
                            <div class="level-item">
                                <button type="button" class="button is-white" @click="toggleUpdateProductModal">&times;</button>
                            </div>
                        </div>
                    </nav>
                    <hr>
                    <form role="form">
                        <div class="field">
                            <label class="label">Название товара</label>
                            <div class="control">
                                <input class="input" type="text" placeholder='Например: Шарф "Одуванчик"' v-model="product.name">
                            </div>
                            <br/>
                            <label class="label">Описание товара</label>
                            <div class="control">
                                <input class="input" type="text" placeholder="Например: Самый легкий шарф в мире" v-model="product.description">
                            </div>
                            <br/>
                            <label class="label">Стоимость товара</label>
                            <div class="control">
                                <input class="input" type="number" placeholder="100.50" v-model="product.price">
                            </div>
                            <br/>
                            <label class="label">Родительская категория</label>
                            <div class="control">
                                <div class="select is-fullwidth">
                                    <select v-model="product.category_id">
                                        <option v-for="category in categories" :value="category.id">{{ setOptionName(category) }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                    <hr>
                    <div class="buttons is-right">
                        <a class="button is-primary" @click="updateProduct">Сохранить изменения</a>
                        <a class="button is-dark" @click="toggleUpdateProductModal">Отмена</a>
                    </div>
                </div>
            </div>
            <button class="modal-close is-large" aria-label="close" @click="toggleUpdateProductModal"></button>
        </div>

        <!-- Delete Category Modal -->
        <div class="modal is-active" role="dialog" id="modal-delete-product" v-if="showDeleteProductModal">
            <div class="modal-background"></div>
            <div class="modal-content">
                <div class="box">
                    <nav class="level">
                        <div class="level-left">
                            <div class="level-item">
                                <h5>Подтверждение удаления</h5>
                            </div>
                        </div>
                        <div class="level-right">
                            <div class="level-item">
                                <button type="button" class="button is-white" @click="toggleDeleteProductModal">&times;</button>
                            </div>
                        </div>
                    </nav>
                    <hr>
                    <p>
                        Вы действительно хотите удалить товар {{ product.name }}? <br/>
                    </p>
                    <hr>
                    <div class="buttons is-right">
                        <a class="button is-primary" @click="deleteProduct">Удалить</a>
                        <a class="button is-dark" @click="toggleDeleteProductModal">Отмена</a>
                    </div>
                </div>
            </div>
            <button class="modal-close is-large" aria-label="close" @click="toggleDeleteProductModal"></button>
        </div>

    </section>
</template>

<script>
    import ProductWrapper from '../components/ProductWrapper';
    import ProductComponent from "../components/ProductComponent";
    export default {
        name: "CategoryView",
        props: ['id'],
        components: {
            ProductComponent,
            ProductWrapper,
        },

        data: function ()
        {
            return {
                showUpdateCategoryModal: false,
                showDeleteCategoryModal: false,

                showCreateProductModal: false,
                showUpdateProductModal: false,
                showDeleteProductModal: false,

                formAddProduct: {
                    errors: [],
                    name: '',
                    description: '',
                    price: null,
                    category_id: this.id,
                },

                product: [],
                category: [],
                categories: [],
            }
        },

        mounted() {
            this.fetchProducts('CategoryView');
            this.getCategories();
        },

        watch: {
            id: function () {
                this.fetchProducts('CategoryView');
                this.formAddProduct.category_id = this.id;
            }
        },

        methods: {
            fetchProducts (sender)
            {
                this.loading = true;

                axios.get('/api/categories/' + this.id)
                    .then(response => {
                        this.category = response.data.category;
                        this.loading = false;
                    });
            },

            getCategories()
            {
                axios.get('/api/categories')
                    .then(response => {
                        this.categories = response.data.categories;
                    });
            },

            toggleUpdateCategoryModal()
            {
                this.showUpdateCategoryModal = !this.showUpdateCategoryModal;
            },

            toggleDeleteCategoryModal()
            {
              this.showDeleteCategoryModal = !this.showDeleteCategoryModal;
            },

            toggleCreateProductModal()
            {
                this.showCreateProductModal = !this.showCreateProductModal;
            },

            toggleUpdateProductModal(product)
            {
                this.product = product;
                this.showUpdateProductModal = !this.showUpdateProductModal;
            },

            toggleDeleteProductModal(product)
            {
                this.product = product;
                this.showDeleteProductModal = !this.showDeleteProductModal;
            },

            updateCategory()
            {
                let vm = this;
                axios.post('/api/categories/update/' + this.category.id, this.category)
                    .then(response => {
                        vm.toggleUpdateCategoryModal();
                        vm.$parent.$emit('category_created');
                    });
            },

            deleteCategory()
            {
                axios.post('/api/categories/delete/' + this.category.id)
                    .then(response => {
                        this.toggleDeleteCategoryModal();
                        this.$parent.$emit('category_deleted', 'delete');
                        this.$router.push('/');
                    });
            },

            createProduct()
            {
                axios.post('/api/products/create', this.formAddProduct)
                    .then(response => {
                        console.log(response);
                        this.toggleCreateProductModal();
                        this.fetchProducts('CategoryView');
                    });
            },

            updateProduct()
            {
                axios.post('/api/products/update/' + this.product.id, this.product)
                    .then(response => {
                        this.toggleUpdateProductModal(null);
                        this.fetchProducts('CategoryView');
                    });
            },

            deleteProduct()
            {
                axios.post('/api/products/delete/' + this.product.id)
                    .then(response => {
                        this.toggleDeleteProductModal(null);
                        this.fetchProducts('CategoryView');
                    });
            },

            // construction an option name
            setOptionName(category)
            {
                let name = category.name;
                let parents = this.getParent(category);

                return parents + name;
            },

            getParent(category)
            {
                let vm = this;
                let parents = '';

                if (category.parent_id != null) {
                    let parent;

                    $.each(vm.categories, function (index, item) {
                        if(item.id == category.parent_id) {
                            parent = item;
                            parents = parent.name + '->' + parents;

                            let subparent = '';

                            if (parent.parent_id != null) {
                                subparent = vm.getParent(parent);
                                parents = subparent + parents;
                            }
                        }
                    })

                }

                return parents;
            },
        }
    }
</script>

<style scoped>

</style>