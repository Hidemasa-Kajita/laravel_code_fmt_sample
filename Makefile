stan:
	vendor/bin/phpstan analyse -c phpstan.neon  --memory-limit=-1

w:
	php artisan ide-helper:models -W