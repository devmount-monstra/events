<h1><?php echo __('Events', 'events'); ?></h1>
<p>An event managment plugin for Monstra. See <a href="https://github.com/devmount-monstra/events" target="_blank">documentation</a> for further information.</p>
<div class="row">
    <div class="col-md-6">
        <h2><?php echo __('Upcoming events', 'events'); ?></h2>
        <h2><?php echo __('Past events', 'events'); ?></h2>
    </div>
    <div class="col-md-6">
        <h2><?php echo __('Add event', 'events'); ?></h2>
        <?php
            echo (
                Form::open() .
                Form::hidden('csrf', Security::token()) .
                Form::label('events_easing', __('Slide easing', 'events')) .
                Form::select('events_easing', array('linear' => 'linear', 'swing' => 'swing'), Option::get('events_easing'), array('class' => 'form-control')) . '<br />' .
                Form::submit('events_submitted', __('Add', 'events'), array('class' => 'btn btn-phone btn-primary')) .
                Form::close()
            );
        ?>
        <h2><?php echo __('Categories', 'events'); ?></h2>
        <ul>
        <?php
            $categories = new Table('categories');
            $categories = $categories->select(null, 'all');
            foreach ($categories as $category) {
        ?>
                <li><?php echo $category['title'] ?></li>
        <?php } ?>
        </ul>
    </div>
</div>