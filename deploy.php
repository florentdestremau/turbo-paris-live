<?php
namespace Deployer;

require 'recipe/symfony.php';

// Config

set('repository', 'git@github.com:florentdestremau/turbo-paris-live.git');

add('shared_files', []);
add('shared_dirs', []);
add('writable_dirs', []);

// Hosts

host('54.38.41.168')
    ->set('remote_user', 'deployer')
    ->set('deploy_path', '~/paris-live');

// Hooks

after('deploy:failed', 'deploy:unlock');
