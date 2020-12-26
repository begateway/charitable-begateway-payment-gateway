# BeGateway payment gateway for WP Charitable

[Русская версия](#платежный-шлюз-begateway-для-wp-charitable)

## Installation

  * Backup your WordPress and database
  * Download [charitable-begateway.zip](https://github.com/beGateway/charitable-begateway-payment-gateway/blob/master/charitable-begateway.zip?raw=true)
  * Start up the administrative panel for WordPress (www.yourshop.com/wp-admin/)
  * Choose _Plugins → Add New_
  * Upload the payment module archive via **Upload Plugin**.
  * Choose _Plugins → Installed Plugins_ and find the _BeGateway Payment Gateway for WP Charitable_ plugin and activate it.

## Setup

Now go to _Charitable → Settings → Payment Gateways_

Look for a table row called `BeGateway` and click the `Gateway Settings` button to configure settings of the payment gateway.

Enter in fields as follows:

  * _Gateway Label_ e.g. _Credit or debit card_
  * _Shop ID_
  * _Secret key_
  * _Public key_
  * _Payment page domain_
  * _Widget CSS_

values received from your payment processor.

  * click _Save Changes_

Now the payment gateway is configured.

## Notes

Tested and developed with:

  * WordPress 5.6
  * Charitable 1.6.46
  * PHP 7.4.13

## Testing

You can use the following information to adjust the payment method in test mode:

  * __Shop ID:__ 361
  * __Secret key:__ b8647b68898b084b836474ed8d61ffe117c9a01168d867f24953b776ddcb134d
  * __Public key:__ MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEArO7bNKtnJgCn0PJVn2X7QmhjGQ2GNNw412D+NMP4y3Qs69y6i5T/zJBQAHwGKLwAxyGmQ2mMpPZCk4pT9HSIHwHiUVtvdZ/78CX1IQJON/Xf22kMULhquwDZcy3Cp8P4PBBaQZVvm7v1FwaxswyLD6WTWjksRgSH/cAhQzgq6WC4jvfWuFtn9AchPf872zqRHjYfjgageX3uwo9vBRQyXaEZr9dFR+18rUDeeEzOEmEP+kp6/Pvt3ZlhPyYm/wt4/fkk9Miokg/yUPnk3MDU81oSuxAw8EHYjLfF59SWQpQObxMaJR68vVKH32Ombct2ZGyzM7L5Tz3+rkk7C4z9oQIDAQAB
  * __Payment page domain:__ checkout.begateway.com

Enable `Turn on Test Mode` in the Charitable Payment Gateways tab.

Use the following test card to make successful test payment:

  * Card number: 4200000000000000
  * Name on card: JOHN DOE
  * Card expiry date: 01/30
  * CVC: 123

Use the following test card to make failed test payment:

  * Card number: 4005550000000019
  * Name on card: JOHN DOE
  * Card expiry date: 01/30
  * CVC: 123

# Платежный шлюз BeGateway для WP Charitable

[English version](#begateway-payment-gateway-for-wp-charitable)

## Установка

  * Создайте резервную копию WordPress и базы данных
  * Загрузите [charitable-begateway.zip](https://github.com/beGateway/charitable-begateway-payment-gateway/blob/master/charitable-begateway.zip?raw=true)
  * Зайдите в панель администратора WordPress (www.yourshop.com/wp-admin/)
  * Выберите _Плагины → Добавить новый_
  * Загрузите модуль через **Добавить новый**
  * Выберите _Плагины → Установленные_ и найдите _Платежный шлюз BeGateway для WP Charitable_ плагин и активируйте его.

## Настройка

Зайдите в _Charitable → Настройка. → Платежная система_

Найдите в таблице доступных платежных шлюзов `BeGateway` и нажмите кнопку `Настройки шлюза`, чтобы настроить данный способ оплаты.

В следующих полях

  * _Метка шлюза_
  * _ID магазина_
  * _Секретный ключ_
  * _Публичный ключ_
  * _Домен страницы оплаты_
  * _CSS стили виджета_

введите значения, полученные от вашей платежной компании.

  * нажмите _Сохранить изменения_

Платежный шлюз настроен и готов к работе.

## Примечания

Разработанно и протестировано с:

  * WordPress 5.6
  * Charitable 1.6.46
  * PHP 7.4.13

## Тестирование

Вы можете использовать следующие данные, чтобы настроить способ оплаты в тестовом режиме

  * __ID магазина:__ 361
  * __Секретный ключ:__ b8647b68898b084b836474ed8d61ffe117c9a01168d867f24953b776ddcb134d
  * __Публичный ключ:__ MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEArO7bNKtnJgCn0PJVn2X7QmhjGQ2GNNw412D+NMP4y3Qs69y6i5T/zJBQAHwGKLwAxyGmQ2mMpPZCk4pT9HSIHwHiUVtvdZ/78CX1IQJON/Xf22kMULhquwDZcy3Cp8P4PBBaQZVvm7v1FwaxswyLD6WTWjksRgSH/cAhQzgq6WC4jvfWuFtn9AchPf872zqRHjYfjgageX3uwo9vBRQyXaEZr9dFR+18rUDeeEzOEmEP+kp6/Pvt3ZlhPyYm/wt4/fkk9Miokg/yUPnk3MDU81oSuxAw8EHYjLfF59SWQpQObxMaJR68vVKH32Ombct2ZGyzM7L5Tz3+rkk7C4z9oQIDAQAB
  * __Домен страницы оплаты:__ checkout.begateway.com

Включите тестовый режим работы платежной системы на странице _Charitable → Настройка. → Платежная система_

Используйте следующие данные карты для успешного тестового платежа:

  * Номер карты: 4200000000000000
  * Имя на карте: JOHN DOE
  * Месяц срока действия карты: 01/30
  * CVC: 123

Используйте следующие данные карты для неуспешного тестового платежа:

  * Номер карты: 4005550000000019
  * Имя на карте: JOHN DOE
  * Месяц срока действия карты: 01/30
  * CVC: 123
