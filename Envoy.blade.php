@servers(['web' => 'deployer@ec2-52-49-111-241.eu-west-1.compute.amazonaws.com'])

@setup
$repo = 'https://github.com/harrygr/azlisting.git';
$site_dir = '/var/www';
$release_dir = $site_dir.'/releases';
$app_dir = $site_dir.'/app';
$release = 'release_' . date('Ymd_Hi_s');
@endsetup

@macro('deploy', ['on' => 'web'])
    fetch_repo
    run_composer
    update_permissions
    update_symlinks
@endmacro

@task('fetch_repo')
    echo "fetching repo"
    [ -d {{ $release_dir }} ] || mkdir {{ $release_dir }};
    cd {{ $release_dir }};
    git clone -b master --depth=1 {{ $repo }} {{ $release }};
@endtask

@task('run_composer')
    cd {{ $release_dir }}/{{ $release }};
    composer install --prefer-dist;
@endtask

@task('update_permissions')
    echo "setting permissions"
    cd {{ $release_dir }};
    chgrp -R www-data {{ $release }};
    chmod -R ug+rwx {{ $release }};
@endtask

@task('update_symlinks')
    echo "updating symlinks";

    {{-- project folder --}}
    echo "- linking project folder";
    ln -nfs {{ $release_dir }}/{{ $release }} {{ $app_dir }};
    chgrp -h www-data {{ $app_dir }};

    {{-- environment file --}}
    echo "- linking environment file";
    cd {{ $release_dir }}/{{ $release }};
    ln -nfs ../../.env .env;
    chgrp -h www-data .env;

    {{-- storage folder --}}
    echo "- linking storage folder";

    {{-- Build up the storage folder if it doesn't exist --}}
    [ -d {{ $site_dir }}/storage ] || { cp -a {{ $release_dir }}/{{ $release }}/storage {{ $site_dir }}/storage; chgrp -R www-data {{ $site_dir }}/storage; chmod -R ug+rwx {{ $site_dir }}/storage;}

    {{-- Remove the release storage dir and symlink to the external one --}}
    rm -rf {{ $release_dir }}/{{ $release }}/storage;
    cd {{ $release_dir }}/{{ $release }};
    ln -nfs ../../storage storage;
    chgrp www-data storage;

    {{-- Deploying user must have permission to restart php via sudo without password --}}
    sudo service php7.0-fpm reload;
@endtask
