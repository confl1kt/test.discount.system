env =  ENV['APPLICATION_ENV']

desc "Update all packages"
multitask :packages  => ['packages:bundle','packages:composer','packages:npm']
namespace :packages do
  task :bundle do
    system "bundle install"
  end
  task :composer do
    system "composer install -o"
  end
  task :npm do
    system "npm install"
  end
end

desc "Create temp directories if not exist and clean"
task :clean do
  FileUtils.remove_dir 'data/cache', true
  FileUtils.remove_dir 'public/assets/build', true
  FileUtils.mkdir_p 'data/cache/', :mode => 0777
  FileUtils.mkdir_p 'data/logs', :mode => 0777
end

namespace :assets do
  namespace :precompile do
    desc "JSHint and compass dev precompile"
    task :dev do
      system "grunt"
    end

    desc "Precompile compass and requirejs"
    task :production do
      system "grunt build"
    end
  end
end

desc "Update crontab"
task :cron do
   system "bundle exec whenever -f config/schedule.rb -i something -s environment=#{env}"
end
namespace :cron do
   desc "Update crontab"
   task :clean do
      system "bundle exec whenever -f config/schedule.rb -c something -s environment=#{env}"
   end
end

namespace :foreman do
   defaults = {:user => ENV['USER'], :concurrent => ''}
   desc "Export workers to upstart (default: #{defaults})"
   task :export, :concurrent, :user do |t,args|
      FileUtils.mkdir_p 'tmp', :mode => 0777
      File.open('tmp/.env', 'w') { |file| file.write("APPLICATION_ENV=#{env}") }
      args.with_defaults defaults
      command = "bundle exec foreman export upstart ~/.init -a something -c #{args[:concurrent].gsub('&',',')} -u #{args[:user]} -e tmp/.env -t data/export/upstart -l ~/log/something"
      puts command
      system command
   end
end

desc "Doctrine"
task :doctrine => ['doctrine:clear','doctrine:proxy']
namespace :doctrine do
   desc "Clear doctrine cache"
   task :clear do
      system({"APPLICATION_ENV"=>env},"php vendor/doctrine/doctrine-module/bin/doctrine-module.php orm:clear-cache:metadata")
      system({"APPLICATION_ENV"=>env},"php vendor/doctrine/doctrine-module/bin/doctrine-module.php orm:clear-cache:query")
      system({"APPLICATION_ENV"=>env},"php vendor/doctrine/doctrine-module/bin/doctrine-module.php orm:clear-cache:result")
   end

   desc "Generate proxies"
   task :proxy do
      system({"APPLICATION_ENV"=>env},"php vendor/doctrine/doctrine-module/bin/doctrine-module.php orm:generate-proxies")
      system({"APPLICATION_ENV"=>env},"php vendor/doctrine/doctrine-module/bin/doctrine-module.php odm:generate:hydrators")
      system({"APPLICATION_ENV"=>env},"php vendor/doctrine/doctrine-module/bin/doctrine-module.php odm:generate:proxies")
   end

   desc "Create migration"
   task :migration do
      system({"APPLICATION_ENV"=>env},"vendor/bin/doctrine-module migrations:generate --configuration=data/migrations-config.xml")
   end

   desc "Migrate"
   task :migrate do
      system({"APPLICATION_ENV"=>env},"vendor/bin/doctrine-module migrations:migrate --configuration=data/migrations-config.xml --no-interaction")
   end
end

desc "Autoload classmaps"
task :classmaps do
    Dir.glob('module' + '/*').each do |path|
        system "vendor/bin/classmap_generator.php -l #{path}"
        if File.exists?(path+'/view')
            system "vendor/bin/templatemap_generator.php -l #{path} -v #{path}/view"
        end
    end
end

desc "Generate html pages"
task :htmlpages do
    FileUtils.mkdir_p 'public/html', :mode => 0755
    system({"APPLICATION_ENV"=>env},"php public/index.php html-page-generation --template=\"500.%s.html\" --alias=internal-server-error")
end

task :default => ['packages', 'classmaps', 'clean', 'assets:precompile:dev', 'doctrine']