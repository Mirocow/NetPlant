<?php

class NginxOnlyConfiguration extends AbstractSiteConfig {

	public function createSite($platform, $site, $renderedConfigs=array()) {
		$script = "# enable site " . escapeshellarg($site->name) . "\n";

		$script .= $this->createSitePathes($platform, $site);

		if (!isset($renderedConfigs['nginx'], $renderedConfigs['phpFCGI'])) {
			return "# bad netplant configuration - no config nginx\n\n";
		}

		$script .= $this->uploadFile($platform, $renderedConfigs['nginx'], "/etc/nginx/sites-enabled/");

		$script .= $this->uploadFile($platform, $renderedConfigs['phpFCGI'], "/etc/php5/fpm/pool.d/netplant-".$platform->systemUser.".conf");

		$script .= $this->reloadPhp5fpm($platform);
		$script .= $this->reloadNginx($platform);

		return $script;
	}

	public function disableSite($platform, $site) {
		// to be done
		return "# disable site here";
	}

}