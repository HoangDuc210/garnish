<?php
    namespace Deployer;

    require 'recipe/laravel.php';

    // Project repository
    set('repository', 'mintame@mintame.git.backlog.com:/PJ_GNSH/garnish.git');

    // Set release_or_current_path
    set('deploy_dev_path', '/var/www/garnish');

    // [Optional] Allocate tty for git clone. Default value is false.
    // set('git_tty', true);

    // Shared files/dirs between deploys
    add('shared_files', [
        '.env',
    ]);
    add('shared_dirs', []);

    // Writable dirs by web server
    add('writable_dirs', [
        'bootstrap/cache',
        'storage',
        'storage/app',
        'storage/app/public',
        'storage/framework',
        'storage/framework/cache',
        'storage/framework/sessions',
        'storage/framework/views',
        'storage/logs',
    ]);

    // Hosts
    // host('dev')
    //     ->hostname('194.233.65.27')
    //     ->stage('dev')
    //     ->user('deployer')
    //     ->identityFile('~/.ssh/id_rsa')
    //     ->set('branch', 'develop')
    //     ->set('deploy_path', '{{deploy_dev_path}}');

    host('dev')
        ->hostname('157.245.202.70')
        ->stage('dev')
        ->user('deployer')
        ->identityFile('~/.ssh/id_rsa')
        ->set('branch', 'develop')
        ->set('deploy_path', '{{deploy_dev_path}}');

    // Tasks
    // task('reload:php-fpm', function () {
    //     run('sudo /usr/sbin/service php7.4-fpm reload');
    // });
    
    task('npm:install', function () {
        run('cd {{release_path}} && npm install');
    });

    task('npm:build', function () {
        run('cd {{release_path}} && npm run build');
    });

    task('deploy', [
        // outputs the branch and IP address to the command line
        'deploy:info',
        // preps the environment for deploy, creating release and shared directories
        'deploy:prepare',
        // adds a .lock file to the file structure to prevent numerous deploys executing at once
        'deploy:lock',
        // removes outdated release directories and creates a new release directory for deploy
        'deploy:release',
        // clones the project Git repository
        'deploy:update_code',
        // loops around t he list of shared directories defined in the config file
        // and generates symlinks for each
        'deploy:shared',
        // loops around the list of writable directories defined in the config file
        // and changes the owner and permissions of each file or directory
        'deploy:writable',
        // if Composer is used on the site, the Composer install command is executed
        'deploy:vendors',
        // install node module
        'npm:install',
        // build asset files
        'npm:build',
        // loops around release and removes unwanted directories and files
        'deploy:clear_paths',
        // links the deployed release to the "current" symlink
        'deploy:symlink',
        // deletes the unlock file, allowing further deploys to be executed
        'deploy:unlock',
        // loops around a list of release directories and removes any which are now outdated
        'cleanup',
        // can be used by the user to assign custom tasks to execute on successful deployments
        'artisan:storage:link',
        // 'reload:php-fpm',
        'success',
    ]);

    // [Optional] if deploy fails automatically unlock.
    after('deploy:failed', 'deploy:unlock');

    // Migrate database before symlink new release.
    before('deploy:symlink', 'artisan:migrate');