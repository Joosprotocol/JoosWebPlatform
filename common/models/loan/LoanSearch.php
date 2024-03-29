<?php

namespace common\models\loan;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * LoanSearch represents the model behind the search form about `custom_modules\loan\models\Loan`.
 */
class LoanSearch extends Loan
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'amount', 'lender_id', 'borrower_id', 'status', 'period', 'currency_type', 'init_type', 'created_at', 'updated_at'], 'integer'],
            [['secret_key'], 'safe'],
            [['hash_id'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
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
        $query = Loan::find();

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
            'lender_id' => $this->lender_id,
            'borrower_id' => $this->borrower_id,
            'status' => $this->status,
            'amount' => $this->amount,
            'period' => $this->period,
            'currency_type' => $this->currency_type,
            'init_type' => $this->init_type,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'hash_id', $this->hash_id]);

        return $dataProvider;
    }
}
