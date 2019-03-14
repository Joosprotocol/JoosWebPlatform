<?php

namespace common\models\payment;


use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * PaymentSearch represents the model behind the search form about `common\models\payment\Payment`.
 */
class PaymentSearch extends Payment
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'loan_id', 'created_at', 'updated_at'], 'integer'],
            [['amount'], 'number'],
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
        $query = Payment::find();

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
            'loan_id' => $this->loan_id,
            'amount' => $this->amount,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        return $dataProvider;
    }
}
