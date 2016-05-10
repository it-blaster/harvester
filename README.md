Harvester
==========

It's time to gather logs

### Использование
1. Добавьте на страницу на input-hidden поле #current_user_name и передайте в его значение логин текущего пользоаптеля

Для sf2 (sf3):
``` html
<input type="hidden" id="current_user_name" value="{{ app.user }}">
```

Для sf1:
``` html
<input type="hidden" id="current_user_name" value="<?php echo $sf_user->getObject()->getEmail() ?>">
```

2. Подключите js-скрипт
``` js
<script src="http://harvester_server_path/logs/event.js"></script>
```