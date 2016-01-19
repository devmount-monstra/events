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

### Categories
All categories are listed here with the option to add a new, edit or remove an existing category to trash. Note that categories with events assigned can not be deleted. Category records contain the following data:
* __Title__ — a recognizable name, type: `string`
* __Color__ — a hexadecimal number to specify a category specific color, `#` is automatically inserted, type: `string`

### Configuration
* __Image directory__:  
  Select a directory where event images are. Those images will be displayed in the select list of the events add/edit formula.
  
### Trash
The trash contains deleted events and categories. Those can be either restored or removed permanently. This can not be undone.

## License
This Plugin is distributed under [MIT-License](http://opensource.org/licenses/mit-license.html).

## Sources
This plugin is developed by [devmount](http://devmount.de).