<?php

class SiteConfiguration extends CActiveRecord {

	/**
	 * Returns the static model of the specified AR class.
	 * @return Config the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{SiteConfiguration}}';
	}


	public function relations() {
		return array(
				'configTemplates' => array(self::HAS_MANY, "ConfigTemplate", "SiteConfiguration_id"),

			);
	}

	public function rules() {
		return array(
				array('name', 'length', 'max'=>255),
				array('handlerClass', 'length', 'max'=>80),
				
				array('name, handlerClass', 'required'),
			);
	}

	public function search()
    {
    	$t = $this->getTableAlias(false,false);

        $criteria=new CDbCriteria;
     
        $criteria->compare($t.'.id',$this->id);
        $criteria->compare($t.'.name', $this->name, true);
        $criteria->compare($t.'.handlerClass', $this->handlerClass, true);
        


        $criteria->together = true;
 
        return new CActiveDataProvider($this, array(
                'criteria'=>$criteria,
                'sort'=>array(
                    'defaultOrder' => 't.name ASC',
                    'attributes'=>array(
                        '*',
                    ),
                ),
                'pagination'=>array(
                    'pageSize'=>20,
                ),

        ));
    }

    /**
     * Creates site
     * @param $platform Platform model
     * @param $site Site model
     * @return string Script
     */
    public function createSite($platform, $site) {
    	// first render all configs to file
    	$configFiles = array();
    	foreach ($this->configTemplates as $configTemplate) {
    		$filename = "/tmp/netplant_".$platform->id."_".$site->id."_".time().".conf";
    		$data = Yii::app()->controller->renderPartial($configTemplate->view, array(
    				'platform' => $platform,
    				'site' => $site,
    				'siteConfiguration'=>$this,
    			), true);
    		file_put_contents($filename, $data);
    		$configFiles[$configTemplate->name] = $filename;
    	}

    	// now create handler class instance
    	$handlerClassName = $this->handlerClass;
    	$class = new $handlerClassName();
    	return $class->createSite($platform, $site, $configFiles);
    	
    }
}