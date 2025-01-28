# opencart-3x-plugin
Плагин оплаты в cms OpenCart: включает возможность передачи товарной корзины

## Версия
Плагин разрабатывался на версии **3.0.3.2**

## Установка плагина
1. В административной панели перейти в «Боковое меню»-> «Модули» -> «Установка модулей»
2. Загружаем скаченный из директория файл
3. В «Боковом меню» выбираем «Модули» ->« Модули» -> «Платежи»
4. Находим «Hbpay» -> «Редактировать»
5. В настройках добавляете ваши банковские данные:
    - ID клиента: (выдается банком)
    - Секретный ключ: (выдается банком)
    - Терминал: (выдается банком)
    - Статус заказа для успешных транзакций: **Complete**
    - Статус заказа для незавершенных транзакций: **Pending**
    - Статус заказа для неуспешных транзакций: **Cancelled**
    - Кликаете «Сохранить» для сохранения настроек.

   При выборе тестовой версии, вы можете ввести [следующие данные](https://epayment.kz/docs/platezhnaya-stranica)
    - ID клиента: `test`
    - Секретный ключ: `yF587AV9Ms94qN2QShFzVR3vFnWkhjbAK3sG`
    - Терминал: `67e34d63-102f-4bd1-898e-370781d0074d`
6.  Далее, переходим в Dashboard (для кликните на лого opencart  в левом верхнем углу)
    ->«Настройки» (в правом верхнем углу)
7.  Нажимаем Refresh для Theme и SASS
8.  Готово. Теперь вы можете протестировать оплату заказа.
