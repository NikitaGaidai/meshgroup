<template>
    <li>
        <a class="m-i" :id="category.id" @click="activate(category.id)">{{ category.name }}</a>
        <ul v-if="category.children.length > 0">
            <category-component v-for="child in category.children" @click="activate(child.id)" :key="child.id" :category="child"></category-component>
            <!--<li v-for="child in category.children">
                <a class="m-i" :id="child.id" @click="$parent.setActive(child.id)">{{ child.name }}</a>
            </li>-->
        </ul>
    </li>
</template>

<script>
    export default {
        name: 'CategoryComponent',
        props: ['category'],
        methods: {
            activate(id)
            {
                this.$router.push('/category/'+ id);
            }
        }
    }
</script>

<style scoped>

</style>