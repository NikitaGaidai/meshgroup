<template>
    <section class="section" v-if="this.$root.logged">
        <nav class="level">
            <div class="level-left">
                <h1 class="title level-item">
                    Раздел пользователя
                </h1>
            </div>
            <div class="level-right">
                <div class="buttons is-right">
                    <a class="button is-transparent" @click="showLogoutModal = true">Выйти из учетной записи</a>
                </div>
            </div>
        </nav>

        <authorized-clients></authorized-clients>
        <clients></clients>
        <personal-access-tokens></personal-access-tokens>

        <!-- Logout Modal -->
        <div class="modal is-active" role="dialog" id="modal-logout" v-if="showLogoutModal">
            <div class="modal-background"></div>
            <div class="modal-content">
                <div class="box">
                    <nav class="level">
                        <div class="level-left">
                            <div class="level-item">
                                <h5>Подтверждение выхода</h5>
                            </div>
                        </div>
                        <div class="level-right">
                            <div class="level-item">
                                <button type="button" class="button is-white" @click="showLogoutModal = false">&times;</button>
                            </div>
                        </div>
                    </nav>
                    <hr>
                    <p>Вы действительно хотите выйти?</p>
                    <hr>
                    <div class="buttons is-right">
                        <a class="button is-primary" @click="logout">Выйти</a>
                        <a class="button is-dark" @click="showLogoutModal = false">Отмена</a>
                    </div>
                </div>
            </div>
            <button class="modal-close is-large" aria-label="close" @click="showLogoutModal = false"></button>
        </div>
    </section>
</template>

<script>
    import AuthorizedClients from '../components/passport/AuthorizedClients';
    import Clients from '../components/passport/Clients';
    import PersonalAccessTokens from '../components/passport/PersonalAccessTokens';

    export default {
        name: "UserView",
        components: {
            AuthorizedClients,
            Clients,
            PersonalAccessTokens
        },
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
                        location.href = '/';
                    })
                    .catch(error => {
                        location.href = '/';
                    });
            }
        }
    }
</script>

<style scoped>

</style>