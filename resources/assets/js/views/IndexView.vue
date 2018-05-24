<template>
    <section class="section">
        <div class="box">
            <nav class="level">
                <div class="level-left">
                    <div class="level-item">
                        <h5 class="subtitle">
                            Небольшое описание проекта.
                        </h5>
                    </div>
                </div>
            </nav>
            <hr>
            <div class="container">
                Все операции с категориями и товарами доступны как с фронтальный части, так и через <code lang="php">API</code>. <br>
                Чтобы получить доступ к действиям над товарами и категориями из фронтальной части, необходимо зарегистрироваться и войти в учетную запись. <br>
                Данные можно добавлять как вручную, так и с помощью и сидера <code lang="php">CategorySeeder</code>. <br>
                Все <abbr title="Create Read Update Delete">CRUD</abbr>-операции, кроме <abbr title="Read">R</abbr>, защищены посредником Laravel Passport и описаны в соответствующих контроллерах,
                то есть для манипулирования данными посредством <code lang="php">API</code>, необходимо получить <code lang="php">Access Token</code>.
            </div>
        </div>
    </section>
</template>

<script>


    export default {
        name: "IndexView",
    }
</script>

<style scoped>

</style>