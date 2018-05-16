# DLE Avatar-Select (light) by Sander
Модуль для DLE. Выбор аватарки из списка
<img src="https://sandev.pro/uploads/posts/2018-05/1526470540_screenshot_1.png" />


## Установка
Залить папки **engine** и **uploads** в корень сайта

Открыть файл шаблона **main.tpl**

В подвале сайта, например перед ```</body>``` вставить:
```
<script src="/engine/mods/AvatarSelect/assets/libs.js"></script>
```

### Вызов окна
Триггер/кнопку открывающую окно выбора аватарки можно разместить в любом месте и любом файле шаблона сайта.
К примеру, в **userinfo.tpl**
```
[not-logged]<a href="#" class="ava-modal-trigger">Выбрать фото</a>[/not-logged]
```

Триггером является наличие класса ***ava-modal-trigger***

Достаточно добавить этот класс к любому элементу на сайте, чтобы при клике на этот элемент открылось окно модуля.

К примеру в **login.tpl**
```
[not-group=5]<img src="{foto}" class="yourClass ava-modal-trigger" alt="Фото" />[/not-group]
```


### Автообновление фотографии на сайте
Чтобы при выборе новой аватарки так же обновлялось фото и в шаблонах **login.tpl** и в **userinfo.tpl** достаточно для блока с картинкой добавить аттрибут
```
data-foto="img"
```
или
```
data-foto="background"
```

Первый - для тегов изображений:
```
<img src="{foto}" alt="foto" data-foto="img" class="ava-modal-trigger" />
```

Второй - если аватарка выводится в виде фона:
```
<a href="#"><span class="cover" style="background-image: url({foto});" data-foto="background">{usertitle}</span></a>
```

### FAQ по загрузке:
Аватарки хранятся в папке **/uploads/fotos/bank/{картинка}**

Имя картинки должно содрежать только латинские символы, цифры и символ подчеркивания _
