<?php

namespace common\models\loan;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * LoanStatusHistorySearch represents the model behind the search form about `custom_modules\loan\models\LoanStatusHistory`.
 */
class LoanStatusHistorySearch extends LoanStatusHistory
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'loan_id', 'status', 'created_at', 'updated_at'], 'integer'],
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
        $query = LoanStatusHistory::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'loan_id' => $this->loan_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        return $dataProvider;
    }
}
