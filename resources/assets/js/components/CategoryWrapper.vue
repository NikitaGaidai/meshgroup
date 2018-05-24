<template>
    <section class="section">
        <aside class="menu" v-if="this.category_data.length > 0" >
            <p class="menu-label">
                Категории
                <a class="is-pulled-right" @click="showCreateModal" v-if="this.$root.logged">Добавить новую</a>
            </p>
            <ul class="menu-list">
                <slot>
                    No Data Here
                </slot>
            </ul>
        </aside>
        <div class="content" v-else>
            <h5 class="subtitle is-5">
                Категории не найдены.
            </h5>
            <a class="button is-dark" @click="showCreateModal" v-if="this.$root.logged">Добавить новую категорию</a>
        </div>

        <!-- Create New Category Modal -->
        <div class="modal is-active" role="dialog" id="modal-create-category" v-if="showCategoryModal">
            <div class="modal-background"></div>
            <div class="modal-content">
                <div class="box">
                    <nav class="level">
                        <div class="level-left">
                            <div class="level-item">
                                <p class="subtitle is-5">Добавить новую категорию</p>
                            </div>
                        </div>
                        <div class="level-right">
                            <div class="level-item">
                                <button type="button" class="button is-white" @click="showCategoryModal = false">&times;</button>
                            </div>
                        </div>
                    </nav>
                    <hr>
                    <form role="form">
                        <div class="field">
                            <label class="label">Название категории</label>
                            <div class="control">
                                <input class="input" type="text" placeholder="Например: ООО ''Рога и копыта''" v-model="addCategory.name">
                            </div>
                            <br/>
                            <label class="label">Родительская категория</label>
                            <div class="control">
                                <div class="select is-fullwidth">
                                    <select v-model="addCategory.parent">
                                        <option value="no_parent" selected>Без родителя</option>
                                        <option v-for="category in category_data" :value="category.id">{{ setOptionName(category) }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                    <hr>
                    <div class="buttons is-right">
                        <a class="button is-primary" @click="createCategory">Добавить</a>
                        <a class="button is-dark" @click="showCategoryModal = false">Отмена</a>
                    </div>
                </div>
            </div>
            <button class="modal-close is-large" aria-label="close" @click="showCategoryModal = false"></button>
        </div>

    </section>
</template>

<script>
    import CategoryComponent from "./CategoryComponent";

    export default {
        name: "CategoryWrapper",

        components: {
            CategoryComponent
        },

        props: ['category_data'],

        data: function () {
          return {
              showCategoryModal: false,

              addCategory: {
                  errors: [],
                  name: '',
                  parent: 'no_parent'
              },
          };
        },

        methods: {

            showCreateModal() {
                this.showCategoryModal = true;
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

                    $.each(vm.category_data, function (index, item) {
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

            // Creating new category
            createCategory() {
                axios.post('/api/categories/create', this.addCategory)
                    .then(response => {
                        this.showCategoryModal = false;
                        this.$parent.$emit('category_created', 'create');
                    });
            }
        }
    }
</script>

<style scoped>

</style>