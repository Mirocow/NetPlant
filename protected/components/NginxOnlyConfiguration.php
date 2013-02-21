<?php

class NginxOnlyConfiguration extends AbstractSiteConfig {

	public function createSite($platform, $site, $renderedConfigs=array()) {
		$script = "# enable site " . escapeshellarg($site->name) . "\n";

		$script .= $this->createSitePathes($platform, $site);

		if (!isset($renderedConfigs['nginx'])) {
			die(var_dump($renderedConfigs));
			return "# bad netplant configuration - no config nginx\n\n";
		}

		$script .= $this->uploadFile($platform, $renderedConfigs['nginx'], "/etc/nginx/netplantHosts/");

		return $script;
	}

	public function disableSite($platform, $site) {
		// to be done
		return "# disable site here";
	}

}