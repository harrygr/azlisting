@servers(['web' => 'deployer@ec2-52-49-111-241.eu-west-1.compute.amazonaws.com'])

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

    {{-- handle project folder --}}
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
    rm -rf {{ $release_dir }}/{{ $release }}/storage;
    cd {{ $release_dir }}/{{ $release }};
    ln -nfs ../../storage storage;
    chgrp -h www-data storage;
@endtask

@task('link_env')
    ln -nfs /var/www/.env {{ $release_dir }}/{{ $release }}/.env;
@endtask