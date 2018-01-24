<?php

namespace common\models\log;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%log_on_os}}".
 *
 * @property integer $id
 * @property string $ip
 * @property string $browser
 * @property string $os
 *
 */
class Os extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%log_on_os}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ip', 'browser', 'os'], 'required'],
            [['ip', 'browser', 'os'], 'string', 'max' => 255]
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
            'browser' => Yii::t('app', 'Browser'),
            'os' => Yii::t('app', 'Os'),
        ];
    }

}
