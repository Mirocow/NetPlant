<?php

abstract class AbstractSiteConfig {
	/**
	 * Creates site 
	 * In this function such actions can be:
	 * - create path for site
	 * - create configs
	 * - reload nginx/apache
	 * - create separated php-fcgid with separated socket
	 * - or anything else
	 *
	 * @param $platform Platform model
	 * @param $site Site model
	 * @param $renderedConfigs array: 'nginx.conf' => 'localPath'
	 *
	 * @return string Script for creating
	 */
	abstract public function createSite($platform, $site, $renderedConfigs=array());

	abstract public function disableSite($platform, $site);

	public function uploadFile($platform, $localPath, $remotePath) {
		return "scp -P 22 $localPath root@".$platform->server->ip.":$remotePath || exit 1\n";
	}

	public function reloadNginx($platform) {
		return "ssh root@".$platform->server->ip." 'service nginx reload' || exit 1\n";
	}

	public function reloadApache2($platform) {
		return "ssh root@".$platform->server->ip." 'service apache2 reload' || exit 1\n";
	}

	public function reloadPhp5fpm($platform) {
		return "ssh root@".$platform->server->ip." 'service php5-fpm reload' || exit 1\n";
	}

	public function createSitePathes($platform, $site) {
		$rootPath = "/home/"
			. escapeshellcmd($platform->systemUser)
			. "/sites/"
			. escapeshellcmd($site->name);
		$command = "mkdir -p $rootPath"
			. "/{htdocs|logs|tmp}/";
		$script = $this->ssh($platform, $command);

		$user = $platform->systemUser;

		$script .= $this->ssh($platform, "chown -R $user:$user $rootPath");

		// make sample index.html
		$command = "echo Hello > $rootPath/htdocs/index.html";
		$script .= $this->ssh($platform, $command);

		return $script;
	}

	public function ssh($platform, $command) {
		return "ssh root@".$platform->server->ip." '".escapeshellcmd($command)."' || exit 1\n";
	}
}