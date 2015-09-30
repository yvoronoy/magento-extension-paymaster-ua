# Official PayMasterUA Magento Extension
![GitHub Logo](https://paymaster.ua/img/logo.png)

PayMaster - платежный агрегатор и интегратор Украины (https://paymaster.ua/)

Современный сервис для организации приема платежей в интернете для вашего интернет магазина на Magento.
Модуль позволяет легко и быстро подключить самые популярные платежные системы Украины, включая: карты Visa/MasterCard, WebMoney, Терминалы Украины, Приват 24, EasyPay, MoneXy, карты НСМЭП, мобильные деньги Киевстар.

##Установка
Для установки модуля 
 - Распакуйте модуль в директорию с установленным магазином на Magento. 
 - Очистить кэш в Admin -> System -> Cache Management -> Flush Cache

##Настройка
Базовая валюта магазина должна быть установлена Украинская гривна.

Настройки модуля находятся в Admin -> System -> Configuration -> Payment Methods -> PayMasterUA General Settings.
Здесь вы должны ввести Merchant ID и Merchant Secret Key которые получили при регистрации на сайте PayMasterUA.

Выберите необходимые платежные методы которые будут отображаться на сайте.
 - PayMasterUA All in One - используйте этот платежный метод если вам необходимо дать возможность пользователю выбрать метод оплаты на сайте PayMasterUA.
 - PayMasterUA Test Method - используйте в тестовом режиме для проверки.
 - PayMasterUA Visa/MasterCard - позволяет пользователю расплатится платежной картой.

##Удаление модуля
Для того чтобы полностью удалить модуль из системы удалите следующие файлы и папки:

`#rm -rf app/code/community/Voronoy app/design/frontend/base/default/template/paymaster app/etc/modules/Voronoy_Paymaster.xml app/locale/en_US/Voronoy_Paymaster.csv app/locale/ru_RU/Voronoy_Paymaster.csv app/locale/uk_UA/Voronoy_Paymaster.csv`
