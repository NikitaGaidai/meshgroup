<template>
    <div>
        <h4 class="subtitle is-5">Регистрация</h4>
        <div class="field">
            <p class="control has-icons-left has-icons-right">
                <input class="input" type="email" placeholder="Имя пользователя" v-model="formRegister.name">
                <span class="icon is-small is-left">
                    <i class="fas fa-envelope"></i>
                </span>
            </p>
        </div>
        <div class="field">
            <p class="control has-icons-left has-icons-right">
                <input class="input" type="email" placeholder="Электронная почта" v-model="formRegister.email">
                <span class="icon is-small is-left">
                    <i class="fas fa-envelope"></i>
                </span>
            </p>
        </div>
        <div class="field">
            <p class="control has-icons-left">
                <input class="input" type="password" placeholder="Пароль" v-model="formRegister.password">
                <span class="icon is-small is-left">
                    <i class="fas fa-lock"></i>
                </span>
            </p>
        </div>
        <div class="field">
            <p class="control has-icons-left">
                <input class="input" type="password" placeholder="Подтверждение пароля" v-model="formRegister.password_confirmation">
                <span class="icon is-small is-left">
                    <i class="fas fa-lock"></i>
                </span>
            </p>
        </div>
        <div class="field is-inline">
            <p class="control has-text-right">
                <button class="button is-dark" @click="register">
                    Завершить регистрацию
                </button>
            </p>
        </div>
    </div>
</template>

<script>
    export default {
        name: "RegisterView",
        data: function ()
        {
            return {

                formRegister: {
                    errors: [],
                    name: '',
                    email: '',
                    password: '',
                    password_confirmation: '',
                }

            }
        },

        methods: {
            register() {

                axios.post('/register', this.formRegister)
                    .then(response => {
                        location.href = '/';
                    });

            }
        }
    }
</script>

<style scoped>

</style>