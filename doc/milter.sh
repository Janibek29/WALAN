#!/bin/bash

# Чтение почты из stdin и передача в PHP-скрипт
cat | php /var/www/html/email_processor.php