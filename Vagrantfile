# -*- mode: ruby -*-
# vi: set ft=ruby :

$project_name = 'biker'

# Vagrantfile API/syntax version. Don't touch unless you know what you're doing!
VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
  config.vm.box = "trusty64"
  config.vm.box_url = "https://cloud-images.ubuntu.com/vagrant/trusty/current/trusty-server-cloudimg-amd64-vagrant-disk1.box"

  config.vm.provider "virtualbox" do |v|
    v.memory = 4048
    v.cpus = 4
  end

  config.hostmanager.enabled = true
  config.hostmanager.manage_host = true

  config.vm.hostname = "#{$project_name}.dev"

  config.vm.network :private_network, ip: "192.168.144.129"

	#"Normal" file sync
   config.vm.synced_folder "config", "/root/config",
      :nfs => true
   config.vm.synced_folder "www", "/var/www",
      :nfs => true
   config.vm.synced_folder "varnish_config", "/var/varnish_config",
      :nfs => true

  config.vm.provision :shell, :path => "bootstrap.sh"

end

