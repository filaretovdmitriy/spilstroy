# Impresio CMS Yii2 Edition #
============================

Обновление через композер

```
#!bash

composer require yiisoft/yii2 2.0.6
```
[![Latest Stable Version](https://poser.pugx.org/yiisoft/yii2-app-basic/v/stable.png)](https://packagist.org/packages/yiisoft/yii2-app-basic)

Структура директорий
-------------------

      assets/                         классы assets сайта и cms
      components/                     классы и внешние компоненты, которые не обновляются через composer
      config/                         файлы конфигурации
      controllers/                    контроллеры фронтенда
      models/                         модели
      modules/icms                    модуль cms
      modules/icms/controllers        контроллеры
      modules/icms/themes             скрипты и стили для cms
      modules/icms/views              вьюхи для cms
      modules/icms/widgets            виджеты cms
      vendor/                         файлы из внешних источников
      vendor/icms                     изображения, скрипты и стили для виджетов
      views/                          файлы вьюх фтронтенда
      web/                            точка входа



Требования
------------

Минимально поддерживаемая версия **PHP 5.4.0**


Установка
------------

Создать форк репозитория

Настройка
------------
**/config/web.php** - файл общих настроек

**/config/frontend.php** - настройки для сайта

**/config/backend.php** - настройки для cms

В файлах **frontend.php** и **backend.php** можно переопределить любые настройки из **web.php**, а так же добавить новые

Рекомендации
-------------
### Models

- Модели следует именовать по шаблону - ИмяТаблицы - пример - Catalog

### Database

Для наименования полей в таблицах:

- не забывать про title_seo, description_seo, keywords_seo, h1_seo
- родительскую категорию обозначать через ИмяТаблицы_НазваниеПоля - пример - user_id
- таблицы именовать как ПрефиксИмяВМножественномЧисле - пример - yiicms_users