<?php

namespace common\models\log;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%log_on_urls}}".
 *
 * @property integer $id
 * @property string $ip
 * @property string $date_time
 * @property string $url_from
 * @property string $url_to
 *
 */
class Urls extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%log_on_urls}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ip', 'date_time', 'url_from', 'url_to'], 'required'],
            [['ip', 'date_time', 'url_from', 'url_to'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'ip' => Yii::t('app', 'Ip'),
            'date_time' => Yii::t('app', 'Date Time'),
            'url_from' => Yii::t('app', 'Url From'),
            'url_to' => Yii::t('app', 'Url To'),
        ];
    }

}
