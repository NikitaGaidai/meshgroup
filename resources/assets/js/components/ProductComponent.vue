<template>
    <div class="box">
        <article class="media">
            <div class="media-left">
                <figure class="image is-64x64">
                    <img src="https://bulma.io/images/placeholders/128x128.png" alt="Image">
                </figure>
            </div>
            <div class="media-content">
                <div class="content">
                    <p>
                        <strong>{{ product.name }}</strong> <small>{{ product.created_at }}</small>
                        <br>
                        {{ product.description }}
                    </p>
                </div>
                <nav class="level is-mobile">
                    <div class="level-left">
                        <p class="level-item">
                            <strong>Стоимость: {{ product.price }}$</strong>
                        </p>
                        <a class="level-item" aria-label="edit" v-if="this.$root.logged" @click="updateProduct">
                            <span class="icon is-small">
                                <i class="fas fa-edit" aria-hidden="true"></i>
                            </span>
                        </a>
                        <a class="level-item" aria-label="delete" v-if="this.$root.logged" @click="deleteProduct">
                            <span class="icon is-small">
                                <i class="fas fa-trash" aria-hidden="true"></i>
                            </span>
                        </a>
                    </div>
                </nav>
            </div>
        </article>
    </div>
</template>

<script>
    export default {
        name: "ProductComponent",
        props: ['product'],
        methods: {
            updateProduct()
            {
                this.$emit('editproduct', this.product.id);
            },
            deleteProduct()
            {
                this.$emit('deleteproduct', this.product.id);
            }
        }
    }
</script>

<style scoped>

</style>