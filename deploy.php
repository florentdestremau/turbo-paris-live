<?php
namespace Deployer;

require 'recipe/symfony.php';

// Config

set('repository', 'git@github.com:florentdestremau/turbo-paris-live.git');

add('shared_files', ['.env.local']);
add('shared_dirs', ['var/log', 'var/sessions']);
add('writable_dirs', ['var/cache', 'var/log', 'var/sessions', 'public/build']);

// Hosts

host('sf-live.flodz.ovh')
    ->set('remote_user', 'deployer')
    ->set('deploy_path', '~/paris-live');

// Hooks
task('deploy:build', function () {
    run('cd {{release_path}} && npm install && npm run build');
});
after('deploy:vendors', 'deploy:build');
after('deploy:failed', 'deploy:unlock');
