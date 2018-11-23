> 这里是本项目自带的面板,这里将说明面板提供的接口

### 嵌入点
> 这里说明本面板中的一些嵌入点,替换规则为```[数组键名]```,如果未指明则为直接输出

#### MB_HTML_HEAD
面板头部,需要引用的文件

#### MB_LEFT_MENU 
面板左边的导航栏
```html
<li class="nav-item"><a class="nav-link" href="[href]">[title]</a></li>
```

#### MB_PLUGIN
插件子栏
```html
<a class="dropdown-item" href="[link]">[title]</a>
```

#### MB_SETTING
设置子栏
````html
<a class="dropdown-item" href="[link]">[title]</a>
````
