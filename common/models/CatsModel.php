<?php

namespace common\models;

use common\models\base\BaseModel;
use Yii;

/**
 * This is the model class for table "cats".
 *
 * @property integer $id
 * @property string $cat_name
 */
class CatsModel extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cats';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cat_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'cat_name' => Yii::t('common', 'Cat Name'),
        ];
    }

    /**
     * 获取所有分类
     * @return array
     */
    public static function getAllCats()
    {
        $res = self::find()->asArray()->all();
        if ($res)
        {
//            $cat = ['0'=>'全部'];
            foreach ($res as $k=>$list) {
                $cat[$list['id']] = $list['cat_name'];
            }
        } else {
            $cat = ['0'=>'全部'];
        }
        return $cat;
    }
}
