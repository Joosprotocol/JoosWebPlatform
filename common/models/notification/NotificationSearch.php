<?php

namespace common\models\notification;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * NotificationSearch represents the model behind the search form about `common\models\notification\Notification`.
 */
class NotificationSearch extends Notification
{
    /** @var  int */
    public $userIdStrong;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'type', 'created_at', 'updated_at'], 'integer'],
            [['text'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Notification::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!empty($params['pageSize'])) {
            $dataProvider->pagination->pageSize = $params['pageSize'];
        }

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'type' => $this->type,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere([
            'user_id' => $this->userIdStrong,
        ]);


        $query->andFilterWhere(['like', 'text', $this->text]);

        return $dataProvider;
    }
}
