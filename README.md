Events
======

An event managment plugin for Monstra.

## Usage: Frontend
Shortcode for content pages:

    {events click="some link text" toggle="some toggle content"}

Codesnippet for templates:

    <?php Events::show('The answer to life, the universe and everything?', '42'); ?>

## Usage: Backend

### Events
All events are listed here with the option to add a new, edit or remove an existing event to trash. Event records contain the following data:
* __Title__ — event name, type: `string`
* __Timestamp__ — date-time in `yyyy-mm-ddThh:mm:ss` format, specifies the event date and list order, type: `string`
* __Date__ — date, format-free, type: `string`
* __Time__ — time, format-free, type: `string`
* __Category__ — assigns an existing category, type: `int` (cagtegory id)
* __Color__ — hexadecimal number to specify an event specific color; if not set, category color is used, `#` is automatically inserted, type: `string`
* __Location__ — location, type: `string`
* __Short description__ — description that is displayed in one line, type: `string`
* __Description__ — long description, type: `string`
* __Image file__ — assigns an existing image from the configured image directory, type: `string`
* __Clip file__ — defines the section that is used to build a square image, type: `string`
* __Audio file__ — assigns an existing audio file, type: `string`

### Categories
All categories are listed here with the option to add a new, edit or remove an existing category to trash. Note that categories with events assigned can not be deleted. Category records contain the following data:
* __Title__ — recognizable name, type: `string`
* __Color__ — hexadecimal number to specify a category specific color, `#` is automatically inserted, type: `string`

### Configuration
* __Image directory__ — directory for event images. Those images will be displayed in the select list of the events add/edit formula.
  
### Trash
The trash contains deleted events and categories. Those can be either restored or removed permanently. This can not be undone.

## License
This Plugin is distributed under [MIT-License](http://opensource.org/licenses/mit-license.html).

## Sources
This plugin is developed by [devmount](http://devmount.de).