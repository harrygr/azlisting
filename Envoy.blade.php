@servers(['web' => 'aws-azlisting'])

@setup
$repo = 'https://github.com/harrygr/azlisting.git';
$release_dir = '/var/www/releases';
$app_dir = '/var/www/app';
$release = 'release_' . date('YmdHis');
@endsetup

@macro('deploy', ['on' => 'web'])
    fetch_repo
    run_composer
    update_permissions
    update_symlinks
    link_env
@endmacro

@task('fetch_repo')
    echo "fetching repo"
    [ -d {{ $release_dir }} ] || mkdir {{ $release_dir }};
    cd {{ $release_dir }};
    git clone {{ $repo }} {{ $release }};
@endtask

@task('run_composer')
    cd {{ $release_dir }}/{{ $release }};
    composer install --prefer-dist;
@endtask

@task('update_permissions')
    echo "setting permissions"
    cd {{ $release_dir }};
    sudo chgrp -R www-data {{ $release }};
    sudo chmod -R ug+rwx {{ $release }};
@endtask

@task('update_symlinks')
    echo "updating symlinks"
    ln -nfs {{ $release_dir }}/{{ $release }} {{ $app_dir }};
    sudo chgrp -h www-data {{ $app_dir }};
@endtask

@task('link_env')
    ln -nfs /var/www/.env {{ $release_dir }}/{{ $release }}/.env;
@endtask