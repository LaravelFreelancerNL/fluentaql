# AQB Façade
A façade is included for ease of use.
You can just call it to quickly create new query, for example:
```php
use LaravelFreelancerNL\FluentAQL\Facades\AQB;

AQB::for(‘u’, ‘users’)->filter(‘u.surname’, ‘Stark’)->return(‘u’);
```

> Do not use the façade to express anything other than a subquery as it will break automated data binding.
