<?php

namespace Deployer;

require 'recipe/symfony.php';

// Config
set('repository', 'git@github.com:MR-M4RV1N/neurify.git');

set('allow_anonymous_stats', false);

// Hosts
host('production')
    ->set('hostname', '91.203.69.240') // IP твоего сервера
    ->set('remote_user', 'neurifyl')      // SSH пользователь
    ->set('deploy_path', '/home2/neurifyl/deploy_neurify'); // Папка на сервере

// Hooks
after('deploy:failed', 'deploy:unlock');
