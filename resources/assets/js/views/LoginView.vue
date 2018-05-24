<template>
    <div>
        <h4 class="subtitle is-5">Авторизация</h4>
        <div class="field">
            <p class="control has-icons-left has-icons-right">
                <input class="input" type="email" placeholder="Электронная почта" v-model="formLogin.email">
                <span class="icon is-small is-left">
                    <i class="fas fa-envelope"></i>
                </span>
            </p>
        </div>
        <div class="field">
            <p class="control has-icons-left">
                <input class="input" type="password" placeholder="Пароль" v-model="formLogin.password">
                <span class="icon is-small is-left">
                    <i class="fas fa-lock"></i>
                </span>
            </p>
        </div>
        <div class="field is-inline">
            <p class="control has-text-right">
                <router-link class="button is-white" to="/register">
                    Зарегистрироваться
                </router-link>
                <button class="button is-dark" @click="login">
                    Войти
                </button>
            </p>
        </div>
    </div>
</template>

<script>
    export default {
        name: "LoginView",
        data: function () {
            return {
                formLogin: {
                    email: '',
                    password: '',
                }
            }
        },
        methods: {
            login()
            {
                axios.post('/login', this.formLogin)
                    .then(response => {
                        location.href = '/';
                    });
            }
        }
    }
</script>

<style scoped>

</style>