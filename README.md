# Laravel Theme Management

This is a theme management package for Laravel 5. You can easily integrate this package with any Laravel based project.

### Features

* Custom theme location
* Ceparation `frontend` & `backend`
* Unlimited Parent view finding
* Multiple theme config extension
* Multiple theme changelog extension
* Artisan console commands
* Translate

## Installation

Install it via Composer. Run this command in your terminal from your project directory:

```sh
composer require vdhoangson/theme
```

Wait for a while, Composer will automatically install in your project.

## Configuration

Call this package service in `config/app.php` config file. To do that, add this line in `app.php` in `providers` array:

```php
vdhoangson\Theme\ThemeServiceProvider::class,
```

Now run this command in your terminal to publish this package resources:

```
php artisan vendor:publish --provider="vdhoangson\Theme\ThemeServiceProvider"
```

## Artisan Command
Run this command in your terminal from your project directory.

Create a theme directory:
```sh
php artisan theme:create place theme_name
```

`place` : frontend or backend

`theme_name` : Theme Name

```sh

 What is theme description? []:
 > 

 What is theme author name? []:
 >  

 What is theme version? []:
 > 

```
List of all themes:
```sh
php artisan theme:list

+---------+---------+------------+---------+
| Place   | Name    | Author     | Version |
+---------+---------+------------+---------+
| backend | default | vdhoangson | 1.0     |
+---------+---------+------------+---------+
```

## Example folder structure:
```
- app/
- ..
- ..
- public/
    - themes/
        - default/
            - assets
                - css
                - img
                - js
            - views/
                - layouts
            - changelog.json        
            - theme.json
```
You can change `theme.json` and `changelog.json` name from `config/theme.php`

```php
// ..
'config' => [
    'name' => 'theme.json',
    'changelog' => 'changelog.json'
],
// ..
```

## Set theme using middleware

Use `VSThemeFront` for Frontend

```php
Route::group(['prefix' => 'frontend', 'middleware'=>'VSThemeFront'], function() {
    // Theme will be applied.
});
```

Use `VSThemeBack` for Backend

```php
Route::group(['prefix' => 'backend', 'middleware'=>'VSThemeBack'], function() {
    // Theme will be applied.
});
```

## API List
- [setTheme](https://github.com/vdhoangson/theme#setTheme)
- [getTheme](https://github.com/vdhoangson/theme#getTheme)
- [currentTheme](https://github.com/vdhoangson/theme#currentTheme)
- [all](https://github.com/vdhoangson/theme#all)
- [exists](https://github.com/vdhoangson/theme#exists)
- [themeInfo](https://github.com/vdhoangson/theme#themeInfo)
- [assets](https://github.com/vdhoangson/theme#assets)
- [vtrans](https://github.com/vdhoangson/theme#vtrans)

### setTheme

For switching current theme you can use `setTheme` method.

```php
Theme::setTheme('theme-name');
```

### getTheme

For getting current theme details you can use `getTheme` method:

```php
Theme::getTheme(); // return Array
```
You can also get particular theme details:
```php
Theme::getTheme('theme-name'); // return Array
```

```php
Theme::getTheme('theme-name', true); // return Collection
```

### currentTheme

Retrieve current theme's name:

```php
Theme::currentTheme(); // return string
```

### all

Retrieve all theme information:

```php
Theme::all(); // return Array
```

### exists

For getting whether the theme exists or not:

```php
Theme::exists(); // return bool
```

### themeInfo

For info about the specified theme:

```php
$themeInfo = Theme::themeInfo('theme-name'); // return Collection

$themeName = $themeInfo->get('name');
// or
$themeName = $themeInfo['name'];
```
Also fallback support:
```php
$themeInfo = Theme::themeInfo('theme-name'); // return Collection

$themeName = $themeInfo->get('changelog.versions');
// or
$themeName = $themeInfo['changelog.versions'];

// or you can also call like as multi dimension
$themeName = $themeInfo['changelog']['versions'];
```

### assets

For binding theme assets you can use the `assets` method:

```php
Theme::assets('asset_path'); // return string
```

When using helper you can also get assets path:
```php
vassets('asset_path'); // return string
```

**Add asset to html:**
```php
<link rel="stylesheet" href="{{ vassets('style.css') }}">
```

### vtrans

You can translate language by `vtrans` method:

When using helper you can also get assets path:
```php
vtrans('your_file.your_key'); // return string
```

## Credits

- [vdhoangson](https://github.com/vdhoangson)