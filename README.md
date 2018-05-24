# meshgroup
Проект для Meshgroup
<hr/>
<p>Процедура установки:</p>
<ol>
  <li>Клонировать проект</li>
  <li>После клонирования в корне проекта запустить <code lang="php">composer install</code></li>
  <li>Создать файл <code lang="php">.env</code></li>
  <li>Подключить к проекту базу данных</li>
  <li>Выполнить <code lang="php">php artisan migrate --seed</code></li>
  <li>Подключить Laravel Passport командой <code lang="php">php artisan passport:install</code></li>
  <li>Запустить проект <code lang="php">php artisan serve</code></li>
</ol>
