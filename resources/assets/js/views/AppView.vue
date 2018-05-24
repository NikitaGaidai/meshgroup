<template>
    <div class="container fullwidth fullheight">
        <header-component title="Meshgroup Demo" :userdata="this.$root.user"></header-component>
        <body-wrapper v-on:category_created="getCategories">
            <div class="columns">
                <div class="column is-3-desktop is-4-tablet is-12-mobile">
                    <category-wrapper :category_data="categories" v-on:category_deleted="getCategories">
                        <category-component v-for="category in categories" :key="category.id" v-if="category.parent_id == null" v-on:activate="setActive" :category="category"></category-component>
                    </category-wrapper>
                </div>
                <div class="column">
                    <router-view></router-view>
                </div>
            </div>
        </body-wrapper>
        <footer-component></footer-component>
    </div>
</template>

<script>
    import HeaderComponent from "../components/HeaderComponent";
    import BodyWrapper from "../components/BodyWrapper";
    import FooterComponent from "../components/FooterComponent";
    import CategoryWrapper from "../components/CategoryWrapper";
    import CategoryComponent from "../components/CategoryComponent";

    export default {
        components: {
            CategoryComponent,
            CategoryWrapper,
            FooterComponent,
            HeaderComponent,
            BodyWrapper
        },

        data: function ()
        {
            return {
                categories: [],
            }
        },

        watch: {
            '$route' (to, from) {
                this.checkRouter();
                this.getCategories();
            },
        },

        created()
        {
            this.getCategories();
        },

        updated()
        {
            this.checkRouter();
        },

        methods: {
            getCategories(type)
            {
                axios.get('/api/categories')
                    .then(response => {
                        this.categories = response.data.categories;
                    });
            },

            // Categories Navigation
            checkRouter()
            {
                let name = this.$router.currentRoute.name;
                if (name == 'category') {
                    let params = this.$router.currentRoute.params;
                    this.setActive(params.id)
                }
                else {
                    this.deactivateAll(null);
                }
            },

            setActive(sender)
            {
                let item = $("#" + sender)[0];
                $("#" + item.id).addClass(' is-active');

                this.deactivateAll(item);
            },

            deactivateAll(active)
            {
                let items = $('.m-i');

                $.each(items, function (index, item) {
                    if(item != active)
                        $('#' + item.id).removeClass(' is-active');
                });
            },
        }
    }
</script>

<style scoped>
    .fullwidth
    {
        width: 100% !important;
        max-width: 100% !important;
    }

    .fullheight
    {
        height: 100% !important;
        position: absolute;
        left: 0px;
        right: 0px;
    }
</style>