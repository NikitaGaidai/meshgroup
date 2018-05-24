<style scoped>
    .action-link {
        cursor: pointer;
    }
</style>

<template>
    <div class="box">
        <div class="level">
            <div class="level-left">
                <div class="level-item">
                    <h5 class="subtitle">Персональные токены доступа</h5>
                </div>
            </div>
            <div class="level-right">
                <div class="level-item">
                    <a class="action-link button is-dark" tabindex="-1" @click="showCreateTokenForm">
                        Создать новый токен
                    </a>
                </div>
            </div>
        </div>
        <hr>

        <!-- No Tokens Notice -->
        <p  v-if="tokens.length === 0">
            Персональных токенов доступа не обнаружено.
        </p>

        <!-- Personal Access Tokens -->
        <table class="table border-0" v-if="tokens.length > 0" style="width: 100%">
            <thead>
            <tr>
                <th>Имя</th>
                <th></th>
            </tr>
            </thead>

            <tbody>
            <tr v-for="token in tokens">
                <!-- Client Name -->
                <td style="vertical-align: middle;">
                    {{ token.name }}
                </td>

                <!-- Delete Button -->
                <td style="vertical-align: middle;">
                    <div class="buttons is-right">
                        <a class="button is-danger" @click="revoke(token)">Удалить</a>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>

        <!-- Create Token Modal -->
        <div class="modal is-active" id="modal-create-token" tabindex="-1" role="dialog" v-if="isCreateModal">
            <div class="modal-background"></div>
            <div class="modal-content">
                <div class="box">
                    <nav class="level">
                        <div class="level-left">
                            <div class="level-item">
                                <h5 class="subtitle">Создать токен</h5>
                            </div>
                        </div>
                        <div class="level-right">
                            <div class="level-item">
                                <button type="button" class="button is-transparent" @click="showCreateTokenForm">&times;</button>
                            </div>
                        </div>
                    </nav>
                    <hr>

                    <!-- Form Errors -->
                    <div class="notification is-danger" v-if="form.errors.length > 0">
                        <p><strong>Упс!</strong> Что-то пошло не так!</p>
                        <br>
                        <ul>
                            <li v-for="error in form.errors">
                                {{ error }}
                            </li>
                        </ul>
                    </div>

                    <!-- Create Token Form -->
                    <form role="form" @submit.prevent="store">
                        <!-- Name -->
                        <div class="field">
                            <label class="label">Имя</label>
                            <div class="control">
                                <input id="create-token-name" type="text" class="input" name="name" v-model="form.name">
                            </div>
                        </div>

                        <div class="field" v-if="scopes.length > 0">
                            <label class="label">Цели</label>
                            <div class="control">
                                <div v-for="scope in scopes">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox"
                                                   @click="toggleScope(scope.id)"
                                                   :checked="scopeIsAssigned(scope.id)">

                                            {{ scope.id }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <hr>

                    <!-- Modal Actions -->
                    <div class="buttons is-right">
                        <button type="button" class="button is-primary" @click="store">Создать</button>
                        <button type="button" class="button is-dark" @click="showCreateTokenForm">Закрыть</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Access Token Modal -->
        <div class="modal is-active" id="modal-access-token" tabindex="-1" role="dialog" v-if="isTokenModal">
            <div class="modal-background"></div>
            <div class="modal-content">
                <div class="box">
                    <nav class="level">
                        <div class="level-left">
                            <div class="level-item">
                                <h5 class="subtitle">Персональный токен доступа</h5>
                            </div>
                        </div>
                        <div class="level-right">
                            <div class="level-item">
                                <button type="button" class="button is-transparent" @click="isTokenModal = false">&times;</button>
                            </div>
                        </div>
                    </nav>
                    <hr>

                    <p>
                        Здесь Ваш новый персональный токен доступа. Сохраните его, так как это окно показывается только один раз!
                        Теперь Вы можете использовать этот токен для совершения запросов к API.
                    </p>

                    <textarea class="textarea" rows="10">{{ accessToken }}</textarea>

                    <!-- Modal Actions -->
                    <div class="buttons is-right">
                        <button type="button" class="button is-dark" @click="isTokenModal = false">Закрыть</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        /*
         * The component's data.
         */
        data: function () {
            return {
                isCreateModal: false,
                isTokenModal: false,

                accessToken: null,

                tokens: [],
                scopes: [],

                form: {
                    name: '',
                    scopes: [],
                    errors: []
                }
            }
        },

        /**
         * Prepare the component (Vue 1.x).
         */
        ready() {
            this.prepareComponent();
        },

        /**
         * Prepare the component (Vue 2.x).
         */
        mounted() {
            this.prepareComponent();
        },

        methods: {
            /**
             * Prepare the component.
             */
            prepareComponent() {
                this.getTokens();
                this.getScopes();
            },

            /**
             * Get all of the personal access tokens for the user.
             */
            getTokens() {
                axios.get('/oauth/personal-access-tokens')
                        .then(response => {
                            this.tokens = response.data;
                        });
            },

            /**
             * Get all of the available scopes.
             */
            getScopes() {
                axios.get('/oauth/scopes')
                        .then(response => {
                            this.scopes = response.data;
                        });
            },

            /**
             * Show the form for creating new tokens.
             */
            showCreateTokenForm() {
                this.isCreateModal = !this.isCreateModal;
            },

            /**
             * Create a new personal access token.
             */
            store() {
                this.accessToken = null;

                this.form.errors = [];

                axios.post('/oauth/personal-access-tokens', this.form)
                        .then(response => {
                            this.form.name = '';
                            this.form.scopes = [];
                            this.form.errors = [];

                            this.tokens.push(response.data.token);

                            this.showAccessToken(response.data.accessToken);
                        })
                        .catch(error => {
                            this.form.errors = ['Что-то пошло не так. Попробуйте снова.'];
                        });
            },

            /**
             * Toggle the given scope in the list of assigned scopes.
             */
            toggleScope(scope) {
                if (this.scopeIsAssigned(scope)) {
                    this.form.scopes = _.reject(this.form.scopes, s => s == scope);
                } else {
                    this.form.scopes.push(scope);
                }
            },

            /**
             * Determine if the given scope has been assigned to the token.
             */
            scopeIsAssigned(scope) {
                return _.indexOf(this.form.scopes, scope) >= 0;
            },

            /**
             * Show the given access token to the user.
             */
            showAccessToken(accessToken) {
                this.showCreateTokenForm();

                this.accessToken = accessToken;

                this.isTokenModal = true;
            },

            /**
             * Revoke the given token.
             */
            revoke(token) {
                axios.delete('/oauth/personal-access-tokens/' + token.id)
                        .then(response => {
                            this.getTokens();
                        });
            }
        }
    }
</script>
