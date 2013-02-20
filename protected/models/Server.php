<?php

class Server extends CActiveRecord {

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
		return '{{Server}}';
	}


	public function relations() {
		return array(
				'platform' => array(self::HAS_MANY, "Platform", "Server_id"),
				
			);
	}

	public function rules() {
		return array(
				array('name', 'length', 'max'=>255),
				array('description', 'safe'),
				array('ip', 'length', 'max'=>45),

			);
	}

	public function search()
    {
    	$t = $this->getTableAlias(false,false);

        $criteria=new CDbCriteria;
     
        $criteria->compare($t.'.id',$this->id);
        $criteria->compare($t.'.name', $this->name, true);
        $criteria->compare($t.'.ip', $this->ip, true);
        $criteria->compare($t.'.description', $this->description, true);


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
}