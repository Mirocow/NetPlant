<?php
/**
 * TaggableCacheBehavior
 * 
 *
 * Based on article http://korzh.net/2011-04-tegirovanie-kesha-v-yii-framework-eto-ne-bolno.html
 */
class TaggableCacheBehavior extends CBehavior {
	const PREFIX = '__tag__';

	/**
	 * Invalidates data, that was marked with tag
	 * List of tags is used as arguments
	 *
	 * @params tag1, tag2, ..., tagN
	 * @return void
	 */
	public function clear() {
		$tags = func_get_args();
		foreach ((array)$tags as $tag) {
			$this->owner->set(self::PREFIX.$tag, time());
		}
		
	}
}