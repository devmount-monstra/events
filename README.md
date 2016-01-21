Events
======

An event managment plugin for Monstra. CRUD for events and categories in backend and show custom event lists in frontend.

## Installation
1. Download latest version of `Events`
2. Log into Monstra backend and go to `Extends > Plugins > Install new`
3. Select downloaded file and first click `Upload`, then click `Install`
4. Go to `Content > Files`, click `Create New Directory` and give a name like "events"; this directory will be used to store event files [recomended]
5. Now go to `Content > Events > Configuration`, select the "events" directory and click `Save`
6. Now everything is ready to use!

## Frontend
Shortcode for content pages:

    {events-list type="extended" range="future" order="ASC"}

Codesnippet for templates:

    <?php Events::list(<type>, <range>, <order>); ?>

## Backend

### Events
All events are listed here with the option to add a new, edit or remove an existing event to trash. Event records contain the following data:

| field       | description                                                                                                              | type     |
|-------------|--------------------------------------------------------------------------------------------------------------------------|----------|
| Timestamp   | date-time in `yyyy-mm-ddThh:mm:ss` format, specifies the event date and list order                                       | `string` |
| Date        | date, format-free                                                                                                        | `string` |
| Time        | time, format-free                                                                                                        | `string` |
| Category    | assigns an existing category                                                                                             | `int`    |
| Color       | hexadecimal number to specify an event specific color; if not set, category color is used, `#` is automatically inserted | `string` |
| Location    | location, format-free                                                                                                    | `string` |
| Short       | description that is displayed in one line                                                                                | `string` |
| Description | long description                                                                                                         | `string` |
| Image file  | assigns an existing image from the configured image directory                                                            | `string` |
| Clip image  | defines the section that is used to build a square image                                                                 | `string` |
| Audio file  | assigns an existing audio file                                                                                           | `string` |

### Categories
All categories are listed here with the option to add a new, edit or remove an existing category to trash. Note that categories with events assigned can not be deleted. Category records contain the following data:

| field | description                                                                            | type     |
|-------|----------------------------------------------------------------------------------------|----------|
| Title | recognizable name                                                                      | `string` |
| Color | hexadecimal number to specify a category specific color, `#` is automatically inserted | `string` |

### Configuration

| field           | description                                                                                                   |
|-----------------|---------------------------------------------------------------------------------------------------------------|
| Image directory | directory for event images. Those images will be displayed in the select list of the events add/edit formula. |
  
### Trash
The trash contains deleted events and categories. Those can be either restored or removed permanently. This can not be undone.

## License
This Plugin is distributed under [MIT-License](http://opensource.org/licenses/mit-license.html).

## Sources
This plugin is developed by [devmount](http://devmount.de).