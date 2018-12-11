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
            [['id', 'lender_id', 'borrower_id', 'status', 'period', 'type', 'created_at', 'updated_at'], 'integer'],
            [['amount'], 'number'],
            [['secret_key', 'ref_slug'], 'safe'],
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
            'type' => $this->type,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'secret_key', $this->secret_key])
            ->andFilterWhere(['like', 'ref_slug', $this->ref_slug]);

        return $dataProvider;
    }
}
