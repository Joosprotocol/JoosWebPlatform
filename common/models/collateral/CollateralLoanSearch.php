<?php

namespace common\models\collateral;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * CollateralSearch represents the model behind the search form about `custom_modules\collateral\models\Collateral`.
 */
class CollateralLoanSearch extends CollateralLoan
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['amount', 'collateral_amount', 'lender_id',  'collateral_id', 'period', 'status', 'currency_type', 'created_at', 'withdrawn_profile_id'], 'integer'],
            [['lvr', 'fee'], 'double'],
            [['hash_id'], 'string'],
            ['is_platform', 'boolean'],


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
        $query = CollateralLoan::find();

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
            'collateral_id' => $this->collateral_id,
            'lvr' => $this->lvr,
            'fee' => $this->fee,
            'status' => $this->status,
            'amount' => $this->amount,
            'period' => $this->period,
            'collateral_amount' => $this->collateral_amount,
            'currency_type' => $this->currency_type,
            'created_at' => $this->created_at,
            'withdrawn_profile_id' => $this->withdrawn_profile_id,
            'is_platform' => $this->is_platform,
        ]);

        $query->andFilterWhere(['like', 'hash_id', $this->hash_id]);


        return $dataProvider;
    }
}
