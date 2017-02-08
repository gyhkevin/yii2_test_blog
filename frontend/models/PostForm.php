<?php


namespace frontend\models;

use common\models\Posts;
use Yii;
use yii\base\Model;
use yii\db\Query;

class PostForm extends Model
{
    public $id;
    public $title;
    public $content;
    public $label_img;
    public $cat_id;
    public $tags;

    public $_lastError = '';
    /**
     * 定义场景
     */
    const SCENARIOS_CREATE = 'create';
    const SCENARIOS_UPDATE = 'update';
    const EVENT_AFTER_CREATE = 'eventAfterCreate';
    const EVENT_AFTER_UPDATE = 'eventAfterUpdate';
    /**
     * 场景设置
     */
    public function scenarios()
    {
        $scenarios = [
            self::SCENARIOS_CREATE => ['title','content','label_img','cat_id','tags'],
            self::SCENARIOS_UPDATE => ['title','content','label_img','cat_id','tags'],
        ];
        return array_merge(parent::scenarios(),$scenarios);
    }

    public function rules()
    {
        return [
            [['id','title','content','cat_id'],'required'],
            [['id','cat_id'],'integer'],
            [['title'],'string','min'=>4,'max'=>50],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>\Yii::t('common', 'ID'),
            'title'=>'标题',
            'content'=>'内容',
            'label_img'=>'标签图',
            'tags'=>'标签'
        ];
    }

    /**
     * 文章创建
     * @return bool
     */
    public function create()
    {
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $model = new Posts();
            $model->setAttributes($this->attributes);
            $model->summary = $this->_getSummary();
            $model->user_id = Yii::$app->user->identity->id;
            $model->user_name = Yii::$app->user->identity->username;
            $model->is_valid = Posts::IS_VALID;
            $model->created_at = time();
            $model->updated_at = time();
            if(!$model->save()){
                throw new \Exception('文章保存失败');
            }
            $this->id = $model->id;

            // 调用事件
            $data = array_merge($this->getAttributes(), $model->getAttributes());
            $this->_eventAfterCreate($data);

            $transaction->commit();
            return true;
        }catch (\Exception $e){
            $transaction->rollBack();
            $this->_lastError = $e->getMessage();
            return false;
        }
    }

    /**
     * 截取文章摘要
     * @param int $start
     * @param int $end
     * @param string $char
     * @return null
     */
    private function _getSummary($start=0, $end=90, $char='utf-8')
    {
        if (empty($this->content)){
            return null;
        }
        return mb_substr(str_replace('&nbsp;','',strip_tags($this->content)), $start, $end, $char);
    }

    /**
     * 创建完成后调用事件
     */
    public function _eventAfterCreate($data)
    {
        // 注册事件
        $this->on(self::EVENT_AFTER_CREATE, [$this, '_eventAddTag'], $data);
        // 触发事件
        $this->trigger(self::EVENT_AFTER_CREATE);
    }

    public function _eventAddTag($event)
    {
        $tag = new TagForm();
        $tag->tags = $event->data['tags'];
        $tagIds = $tag->saveTags();

        // 删除原来的关联
        RelationPostTagModel::deleteAll(['post_id'=>$event->data['id']]);

        // 批量保存
        if (!empty($tagIds)){
            foreach ($tagIds as $k=>$id){
                $row[$k]['post_id'] = $this->id;
                $row[$k]['tag_id'] = $id;
            }
            $res = (new Query())->createCommand()
                    ->batchInsert(RelationPostTagModel::tableName(), ['post_id', 'tag_id'], $row)
                    ->execute();
            if (!$res){
                throw new \Exception('标签保存失败');
            }
        }
    }
}