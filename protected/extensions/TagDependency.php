<?php
/**
 * TaggableCacheBehavior
 * 
 *
 * Based on article http://korzh.net/2011-04-tegirovanie-kesha-v-yii-framework-eto-ne-bolno.html
 *
 *
 * How to use:
 * 1. Configure cache component in config to use TaggableCacheBehavior:
 * 
 * 2. Caching with tag1 and tag2
 * 		Yii::app()->cache->set($key, $value, 0, new TagDependency('tag1', 'tag2'));
 *
 * 3. Clear cache by tag2 when it is needed
 * 		Yii::app()->cache->clear('tag2');
 *
 *
 *
 */
class TagDependency implements ICacheDependency {

	protected $timestamp;
	protected $tags;

	/**
	 * List of tags is used as arguments for dependency
	 *
	 * @params tag1, tag2, ..., tagN
	 */
	public function __construct() {
		$this->tags = func_get_args();
	}

	/**
	 * Evaluates the dependency by generating and saving the data related with dependency.
	 * This method is invoked by cache before writing data into it.
	 */
	public function evaluateDependency() {
		$this->timestamp = time();
	}

	/**
	 * @return boolean whether the dependency has changed.
	 */
	public function getHasChanged() {
		$tags = array_map(function($i) { return TaggableCacheBehavior::PREFIX.$i; }, $this->tags);
		$values = Yii::app()->cache->mget($tags);

		foreach ($values as $value) {
			if ((integer)$value > $this->timestamp) {
				return true;
			}
		}

		return false;
	}
}