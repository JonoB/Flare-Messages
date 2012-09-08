# Flare Message Bundle

Help you add and display messages throughout your app.

## Installation

Install via the Artisan CLI:
```sh
php artisan bundle:install messagely
```

## Getting Started

Add the following to your application/bundles.php file:
```php
'messagely' => array(
	'auto' => true,
)
```

You may also want to alias the namespace, so add the following to
the aliases in config/application.php:
```php
'Messagely' => 'Flare\\Messagely',
```

## Adding messages

#### Adding a single message
All messages must belong to a group. You can give your group any name.
```php
// Add a message to the 'default' group
Messagely::add('default', 'My first message');

// Add a message to the 'errors' group
Messagely::add('errors', 'There seems to be an error here');
```

#### Adding multiple messages
You can add multiple messages to the same group by passing an array to the second paramter.
```php
$messages = array(
	'My first message',
	'This is the second message',
	'Another item you need to read',
)
Messagely::add('errors', $messages);
```

#### Adding flash messages
Use this option if you want to store an item in the session that automatically expires after the next request. You
can also pass in mulitple messages to the second parameter if you wish
```php
// Add a message to the 'default' group
Messagely::flash('success', 'This message will only be shown');
```

## Retrieving messages

#### Retrieving all messages from all groups
```php
Messagely::get();
```

#### Retrieving all messages from a single group
```php
Messagely::get('success');
```

## Rendering messages
This is totally up to you. One method is to have a view in your application that
automatically renders your messages. The following example gets all the messages
and uses Twitter Bootstrap markup to style the messages.
```php
@if ($messages = Messagely::get())
	<div class="info-messages">
		@foreach ($messages as $group => $msgs)
			<div class="alert alert-<?php echo $group; ?>">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
				@foreach ($msgs as $msg)
					<p>{{ $msg }}</p>
				@endforeach
			</div>
		@endforeach
	</div>
@endif
```