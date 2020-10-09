## Модуль интеграции с CMS Prestashop  (v1.7)

Данный модуль обеспечивает взаимодействие между интернет-магазином на базе CMS **Prestashop** и сервисом платежей [ХуткiГрош](www.hutkigrosh.by).
После установки модуля для клиента будут доступны два новых способа оплаты: оплата через ЕРИП и опалата картой (через сервис webpay). 
Технически оба варианта добавляются счета в ЕРИП через сервис ХуткiГрош, но второй способ более очевидно перенаправляет клиента к оплате счета картой 
и не содержит инструкций по оплате в ЕРИП. 

### Требования ###
1. PHP 5.6 и выше 
1. Библиотека Curl 

### Инструкция по установке:
1. Создайте резервную копию вашего магазина и базы данны
1. Установите модуль [ps_hutkigrosh.zip](https://bitbucket.org/esasby/cmsgate-prestashop-hutkigrosh/raw/master/ps_hutkigrosh.zip) с помощью __Module  -> Module Manager -> Загрузить модуль__

## Инструкция по настройке
1. Перейдите к настройке плагина через меню __Module  -> Module Manager -> Прочее (Other)__
1. Напротив модуля ХуткiГрош нажмите «Настроить».
1. Укажите обязательные параметры
    * Логин интернет-магазина – логин в системе ХуткiГрош.
    * Пароль интернет-магазина – пароль в системе ХуткiГрош.
    * Уникальный идентификатор услуги ЕРИП – ID ЕРИП услуги
    * Код услуги – код услуги в деревер ЕРИП. Используется при генерации QR-кода
    * Sandbox - перевод модуля в тестовый режим работы. В этом режиме счета выставляются в тестовую систему wwww.trial.hgrosh.by
    * Email оповещение - включить информирование клиента по email при успешном выставлении счета (выполняется шлюзом Хуткiгрош)
    * Sms оповещение - включить информирование клиента по смс при успешном выставлении счета (выполняется шлюзом Хуткiгрош)
    * Путь в дереве ЕРИП - путь для оплаты счета в дереве ЕРИП, который будет показан клиенту после оформления заказа (например, Платежи > Магазин > Заказы)
    * Срок действия счета - как долго счет, будет доступен в ЕРИП для оплаты    
    * Статус при выставлении счета  - какой статус выставить заказу при успешном выставлении счета в ЕРИП (идентификатор существующего статуса из Магазин > Настройки > Статусы)
    * Статус при успешной оплате счета - какой статус выставить заказу при успешной оплате выставленного счета (идентификатор существующего статуса)
    * Статус при отмене оплаты счета - какой статус выставить заказу при отмене оплаты счета (идентификатор существующего статуса)
    * Статус при ошибке оплаты счета - какой статус выставить заказу при ошибке выставленния счета (идентификатор существующего статуса)
    * Секция "Инструкция" - если включена, то на итоговом экране клиенту будет доступна пошаговая инструкция по оплате счета в ЕРИП
    * Секция QR-code - если включена, то на итоговом экране клиенту будет доступна оплата счета по QR-коду
    * Секция Alfaclick - если включена, то на итоговом экране клиенту отобразится кнопка для выставления счета в Alfaclick
    * Секция Webpay - если включена, то на итоговом экране клиенту отобразится кнопка для оплаты счета картой (переход на Webpay)
    * Текст успешного выставления счета - текст, отображаемый кленту после успешного выставления счета. Может содержать html. В тексте допустимо ссылаться на переменные @order_id, @order_number, @order_total, @order_currency, @order_fullname, @order_phone, @order_address
    * Название способы оплаты - название, отображаемое клиенту, при выборе способа оплаты
    * Описание способа оплаты - подробное описание, отображаемое клиенту
    * Название способы оплаты "webpay" - название, отображаемое клиенту, при выборе способа оплаты картой
    * Описание способа оплаты webpay - подробное описание, отображаемое клиенту
1. Сохраните изменения.

### Внимание!
* Для автоматического обновления статуса заказа (после оплаты клиентом выставленного в ЕРИП счета) необходимо сообщить в службу технической поддержки сервиса «Хуткi Грош» адрес обработчика:
    ```
    http://mydomen.my/prestashop/ru/module/ps_hutkigrosh/callback
    ```
* Модуль ведет лог файл по пути _site_root/modules/ps_hutkigrosh/vendor/esas/cmsgate-core/logs/cmsgate.log_
Для обеспечения **безопасности** необходимо убедиться, что в настройках http-сервера включена директива _AllowOverride All_ для корневой папки.

### Тестовые данные
Для настрой оплаты в тестовом режиме
 * воспользуйтесь данными для подключения к тестовой системе, полученными при регистрации в ХуткiГрош
 * включите в настройках модуля режим "Песочницы" 
 * для эмуляции оплаты клиентом выставленного счета воспльзуйтесь личным кабинетом [тестовой системы](https://trial.hgrosh.by) (меню _Тест оплаты ЕРИП_)
_Разработано и протестировано с OpenCart v.3.0.0.2_

