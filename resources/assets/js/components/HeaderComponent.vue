<template>
    <section class="hero is-dark">
        <div class="hero-body">
            <div class="container">
                <h1 class="title">
                    <router-link :to="{ name: 'index' }">{{ title }}</router-link>
                </h1>
                <h2 class="subtitle" v-if="userdata">
                    Здравствуйте, <router-link to="/user" ><strong>{{ userdata.name }}</strong></router-link>
                </h2>
                <h2 class="subtitle" v-else>
                    <router-link :to="{ name: 'login' }">Авторизоваться</router-link>
                </h2>
            </div>
        </div>
    </section>
</template>

<script>
    export default {
        props: ['title', 'userdata'],
        data: function () {
            return {
                showLogoutModal: false,
            }
        },
        methods: {
            logout()
            {
                axios.post('/logout')
                    .then(response => {
                        location.reload();
                    })
                    .catch(error => {
                    location.reload();
                });
            }
        }
    }
</script>
