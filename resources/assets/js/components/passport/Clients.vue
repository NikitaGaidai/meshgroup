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
                    <h5 class="subtitle">Клиенты OAuth</h5>
                </div>
            </div>
            <div class="level-right">
                <div class="level-item">
                    <a class="action-link button is-dark" tabindex="-1" @click="toggleCreateModal">
                        Создать клиент
                    </a>
                </div>
            </div>
        </div>
        <hr>
        <!-- Current Clients -->
        <p  v-if="clients.length === 0">
            Клиенты OAuth не найдены.
        </p>

        <table class="table border-0" v-if="clients.length > 0" style="width: 100%">
            <thead>
            <tr>
                <th>ID клиента</th>
                <th>Имя</th>
                <th>Секретный ключ</th>
                <th></th>
            </tr>
            </thead>

            <tbody>
            <tr v-for="client in clients">
                <!-- ID -->
                <td style="vertical-align: middle;">
                    {{ client.id }}
                </td>

                <!-- Name -->
                <td style="vertical-align: middle;">
                    {{ client.name }}
                </td>

                <!-- Secret -->
                <td style="vertical-align: middle;">
                    <code>{{ client.secret }}</code>
                </td>

                <!-- Delete Buttons -->
                <td style="vertical-align: middle;">
                    <div class="buttons is-right">
                        <a class="button is-transparent" tabindex="-1" @click="edit(client)">Изменить</a>
                        <a class="button is-danger" @click="destroy(client)">Удалить</a>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>

        <!-- Create Client Modal -->
        <div class="modal is-active" id="modal-create-client" tabindex="-1" role="dialog" v-if="isCreateModal">
            <div class="modal-background"></div>
            <div class="modal-content">
                <div class="box">
                    <nav class="level">
                        <div class="level-left">
                            <div class="level-item">
                                <h5 class="subtitle">Создать новый клиент</h5>
                            </div>
                        </div>
                        <div class="level-right">
                            <div class="level-item">
                                <button type="button" class="button is-transparent" @click="toggleCreateModal">&times;</button>
                            </div>
                        </div>
                    </nav>
                    <hr>

                    <!-- Form Errors -->
                    <div class="notification is-danger" v-if="createForm.errors.length > 0">
                        <p><strong>Упс!</strong> Что-то пошло не так!</p>
                        <br>
                        <ul>
                            <li v-for="error in createForm.errors">
                                {{ error }}
                            </li>
                        </ul>
                    </div>

                    <!-- Create Client Form -->
                    <form role="form">
                        <!-- Name -->
                        <div class="field">
                            <label class="label">Имя</label>
                            <div class="control">
                                <input id="create-client-name" type="text" class="input"
                                       @keyup.enter="store" v-model="createForm.name">

                                <p class="help">
                                    То, чему Ваши пользователи доверяют.
                                </p>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Адрес перенаправления</label>
                            <div class="control">
                                <input type="text" class="input" name="redirect"
                                       @keyup.enter="store" v-model="createForm.redirect">

                                <p class="help">
                                    Callback URL в вашем приложении.
                                </p>
                            </div>
                        </div>
                    </form>
                    <hr>

                    <!-- Modal Actions -->
                    <div class="buttons is-right">
                        <button type="button" class="button is-primary" @click="store">Создать</button>
                        <button type="button" class="button is-dark" @click="toggleCreateModal">Закрыть</button>
                    </div>

                </div>
            </div>
        </div>

        <!-- Edit Client Modal -->
        <div class="modal is-active" id="modal-edit-client" tabindex="-1" role="dialog" v-if="isEditModal">
            <div class="modal-background"></div>
            <div class="modal-content">
                <div class="box">
                    <nav class="level">
                        <div class="level-left">
                            <div class="level-item">
                                <h5 class="subtitle">Изменить клиент</h5>
                            </div>
                        </div>
                        <div class="level-right">
                            <div class="level-item">
                                <button type="button" class="button is-transparent" @click="toggleEditModal">&times;</button>
                            </div>
                        </div>
                    </nav>
                    <hr>
                    <!-- Form Errors -->
                    <div class="notification is-danger" v-if="editForm.errors.length > 0">
                        <p><strong>Упс!</strong> Что-то пошло не так!</p>
                        <br>
                        <ul>
                            <li v-for="error in editForm.errors">
                                {{ error }}
                            </li>
                        </ul>
                    </div>

                    <!-- Edit Client Form -->
                    <form role="form">
                        <!-- Name -->
                        <div class="field">
                            <label class="label">Имя</label>
                            <div class="control">
                                <input id="edit-client-name" type="text" class="input"
                                       @keyup.enter="update" v-model="editForm.name">

                                <p class="help">
                                    То, чему Ваши пользователи доверяют.
                                </p>
                            </div>
                        </div>

                        <!-- Redirect URL -->
                        <div class="field">
                            <label class="label">Адрес перенаправления</label>
                            <div class="control">
                                <input type="text" class="input" name="redirect"
                                       @keyup.enter="update" v-model="editForm.redirect">

                                <p class="help">
                                    Callback URL в вашем приложении.
                                </p>
                            </div>
                        </div>
                    </form>
                    <hr>

                    <!-- Modal Actions -->
                    <div class="buttons is-right">
                        <button type="button" class="button is-primary" @click="update">Сохранить изменения</button>
                        <button type="button" class="button is-dark" @click="toggleEditModal">Закрыть</button>
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
        data() {
            return {

                isCreateModal: false,
                isEditModal: false,

                clients: [],

                createForm: {
                    errors: [],
                    name: '',
                    redirect: ''
                },

                editForm: {
                    errors: [],
                    name: '',
                    redirect: ''
                }
            };
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

            toggleCreateModal() {
                this.isCreateModal = !this.isCreateModal;
            },

            toggleEditModal() {
                this.isEditModal = !this.isEditModal;
            },

            /**
             * Prepare the component.
             */
            prepareComponent() {
                this.getClients();
            },

            /**
             * Get all of the OAuth clients for the user.
             */
            getClients() {
                axios.get('/oauth/clients')
                        .then(response => {
                            this.clients = response.data;
                        });
            },

            /**
             * Create a new OAuth client for the user.
             */
            store() {
                this.persistClient(
                    'post', '/oauth/clients',
                    this.createForm, 'create'
                );
            },

            /**
             * Edit the given client.
             */
            edit(client) {
                this.editForm.id = client.id;
                this.editForm.name = client.name;
                this.editForm.redirect = client.redirect;

                this.toggleEditModal();
            },

            /**
             * Update the client being edited.
             */
            update() {
                this.persistClient(
                    'put', '/oauth/clients/' + this.editForm.id,
                    this.editForm, 'update'
                );
            },

            /**
             * Persist the client to storage using the given form.
             */
            persistClient(method, uri, form, type) {
                form.errors = [];

                axios[method](uri, form)
                    .then(response => {
                        this.getClients();

                        form.name = '';
                        form.redirect = '';
                        form.errors = [];

                        if(type == 'create')
                            this.toggleCreateModal();
                        else if (type == 'update')
                            this.toggleEditModal();
                        else
                            console.error('Undefined Request Type.');
                    })
                    .catch(error => {
                        if (typeof error.response.data === 'object') {
                            form.errors = _.flatten(_.toArray(error.response.data.errors));
                        } else {
                            form.errors = ['Что-то пошло не так. Попробуйте снова.'];
                        }
                    });
            },

            /**
             * Destroy the given client.
             */
            destroy(client) {
                axios.delete('/oauth/clients/' + client.id)
                        .then(response => {
                            this.getClients();
                        });
            }
        }
    }
</script>
