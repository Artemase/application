<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "application".
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $status
 * @property string|null $message
 * @property string|null $comment
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class Application extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'application';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'message', 'comment'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'email'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'email' => Yii::t('app', 'Email'),
            'status' => Yii::t('app', 'Status'),
            'message' => Yii::t('app', 'Message'),
            'comment' => Yii::t('app', 'Comment'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\ApplicationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\ApplicationQuery(get_called_class());
    }
}
