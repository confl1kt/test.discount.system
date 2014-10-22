env =  ENV['APPLICATION_ENV']

desc "Create temp directories if not exist and clean"
task :clean do
  FileUtils.remove_dir 'data/cache', true
  FileUtils.remove_dir 'public/assets/build', true
  FileUtils.mkdir_p 'data/cache/', :mode => 0777
  FileUtils.mkdir_p 'data/logs', :mode => 0777
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

task :default => ['classmaps', 'clean']